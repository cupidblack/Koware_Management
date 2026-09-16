<?php
/**
 * Plugin Name: BZJ Connections Synchronization Control Plane
 * Description: Canonical Buzzjuice relationship control plane. BuddyBoss/WordPress is the source of truth for connections, follows and blocks; synchronizes projections to Buzzjuice Streams and Socials.
 * Version: 6.1.0
 * Author: Buzzjuice
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;

if (defined('BZJ_CONNECTIONS_SYNC_LOADED')) {
    return;
}
define('BZJ_CONNECTIONS_SYNC_LOADED', true);

final class BZJ_Connections_Sync {
    const VERSION = '6.1.0';
    const REST_NAMESPACE = 'bzj/v6';
    const REST_ROUTE = '/connection-management';
    const LEDGER_TABLE = 'bzj_relationships';
    const EVENTS_TABLE = 'bzj_relationship_events';
    const LOG_FILE = 'bzj-connections-sync.log';
    const LOCK_TTL = 15;
    const EVENT_TTL = 86400;
    const MAX_ATTEMPTS = 12;

    private static $instance = null;
    private $context_stack = array();
    private $table_ledger = '';
    private $table_events = '';

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        global $wpdb;
        $this->table_ledger = $wpdb->prefix . self::LEDGER_TABLE;
        $this->table_events = $wpdb->prefix . self::EVENTS_TABLE;

        add_action('rest_api_init', array($this, 'register_rest_routes'));

        /* BuddyBoss Connections lifecycle. */
        add_action('friends_friendship_requested', array($this, 'bb_connection_requested'), 20, 4);
        add_action('friends_friendship_accepted', array($this, 'bb_connection_accepted'), 20, 4);
        add_action('friends_friendship_rejected', array($this, 'bb_connection_rejected'), 20, 2);
        add_action('friends_friendship_withdrawn', array($this, 'bb_connection_withdrawn'), 20, 2);
        add_action('friends_friendship_deleted', array($this, 'bb_connection_deleted'), 1, 3);

        /* BuddyBoss Activity follows. Both names are supported because BuddyBoss versions differ. */
        add_action('bp_start_following', array($this, 'bb_follow_started'), 20, 2);
        add_action('bp_follow_start_following', array($this, 'bb_follow_started'), 20, 2);
        add_action('bp_stop_following', array($this, 'bb_follow_stopped'), 20, 2);
        add_action('bp_follow_stop_following', array($this, 'bb_follow_stopped'), 20, 2);

        /*
         * BuddyBoss member blocking is implemented by the Moderation component.
         * The current source exposes bp_moderation_after_save and bb_moderation_after_delete.
         * We inspect only item_type=user and user_report=0, which is the member-block record.
         */
        add_action('bp_moderation_after_save', array($this, 'bb_moderation_saved'), 20, 1);
        add_action('bb_moderation_after_delete', array($this, 'bb_moderation_deleted'), 20, 1);

        /* Durable queue processor. */
        add_action('bzj_connections_sync_process_queue', array($this, 'process_queue'));
        add_action('bzj_connections_sync_reconcile', array($this, 'reconcile_queue'));

        add_action('init', array($this, 'maybe_install_and_schedule'), 1);
    }

    public static function activate() {
        self::instance()->install_schema();
        self::instance()->schedule_events();
    }

    public static function deactivate() {
        wp_clear_scheduled_hook('bzj_connections_sync_process_queue');
        wp_clear_scheduled_hook('bzj_connections_sync_reconcile');
    }

    private function install_schema() {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset = $wpdb->get_charset_collate();

        $sql1 = "CREATE TABLE {$this->table_ledger} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_a bigint(20) unsigned NOT NULL,
            user_b bigint(20) unsigned NOT NULL,
            connection_state varchar(20) NOT NULL DEFAULT 'none',
            requested_by bigint(20) unsigned NOT NULL DEFAULT 0,
            follow_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,
            follow_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            block_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,
            block_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            version bigint(20) unsigned NOT NULL DEFAULT 1,
            last_event_uuid char(36) NOT NULL DEFAULT '',
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY user_pair (user_a,user_b),
            KEY connection_state (connection_state),
            KEY requested_by (requested_by),
            KEY block_a_to_b (block_a_to_b),
            KEY block_b_to_a (block_b_to_a),
            KEY updated_at (updated_at)
        ) {$charset};";

        $sql2 = "CREATE TABLE {$this->table_events} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            event_uuid char(36) NOT NULL,
            origin varchar(20) NOT NULL,
            operation varchar(40) NOT NULL,
            actor_wp_id bigint(20) unsigned NOT NULL DEFAULT 0,
            target_wp_id bigint(20) unsigned NOT NULL DEFAULT 0,
            pair_a bigint(20) unsigned NOT NULL DEFAULT 0,
            pair_b bigint(20) unsigned NOT NULL DEFAULT 0,
            payload longtext NULL,
            status varchar(20) NOT NULL DEFAULT 'pending',
            attempts int(10) unsigned NOT NULL DEFAULT 0,
            next_attempt_at datetime NOT NULL,
            last_error text NULL,
            processed_at datetime NULL,
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY event_uuid (event_uuid),
            KEY queue_status (status,next_attempt_at),
            KEY pair_key (pair_a,pair_b),
            KEY operation (operation),
            KEY origin (origin),
            KEY actor_target (actor_wp_id,target_wp_id)
        ) {$charset};";

        dbDelta($sql1);
        dbDelta($sql2);

        update_option('bzj_connections_sync_schema_version', self::VERSION, false);
    }

    private function schedule_events() {
        if (!wp_next_scheduled('bzj_connections_sync_process_queue')) {
            wp_schedule_event(time() + 30, 'minute', 'bzj_connections_sync_process_queue');
        }
        if (!wp_next_scheduled('bzj_connections_sync_reconcile')) {
            wp_schedule_event(time() + 300, 'hourly', 'bzj_connections_sync_reconcile');
        }
    }

    public function maybe_install_and_schedule() {
        $installed = get_option('bzj_connections_sync_schema_version', '');
        if ($installed !== self::VERSION) {
            $this->install_schema();
        }
        $this->schedule_events();
    }

    public function register_rest_routes() {
        register_rest_route(self::REST_NAMESPACE, self::REST_ROUTE, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'rest_command'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route(self::REST_NAMESPACE, self::REST_ROUTE, array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'rest_status'),
            'permission_callback' => function() {
                return current_user_can('manage_options');
            },
        ));
    }

    public function rest_status(WP_REST_Request $request) {
        $pair_a = absint($request->get_param('user_a'));
        $pair_b = absint($request->get_param('user_b'));
        if ($pair_a < 1 || $pair_b < 1 || $pair_a === $pair_b) {
            return new WP_Error('bzj_invalid_pair', 'Two different valid WordPress user IDs are required.', array('status' => 400));
        }
        return rest_ensure_response(array(
            'success' => true,
            'version' => self::VERSION,
            'relationship' => $this->get_ledger($pair_a, $pair_b),
            'queue' => $this->get_pair_queue($pair_a, $pair_b),
        ));
    }

    public function rest_command(WP_REST_Request $request) {
        $raw = $request->get_body();
        $payload = json_decode($raw, true);

        if (!is_array($payload)) {
            return new WP_Error('bzj_invalid_json', 'Request body must contain valid JSON.', array('status' => 400));
        }

        $auth = $this->authenticate_external_request($request, $raw);
        if (is_wp_error($auth)) {
            return $auth;
        }

        $event_uuid = isset($payload['event_id']) ? sanitize_text_field($payload['event_id']) : '';
        $origin = isset($payload['origin_platform']) ? sanitize_key($payload['origin_platform']) : '';
        $operation = isset($payload['operation']) ? sanitize_key($payload['operation']) : '';
        $actor_external = isset($payload['actor_id']) ? absint($payload['actor_id']) : 0;
        $target_external = isset($payload['target_id']) ? absint($payload['target_id']) : 0;

        if (!$this->is_uuid($event_uuid)) {
            return new WP_Error('bzj_invalid_event_id', 'event_id must be a valid UUID.', array('status' => 400));
        }

        if (!in_array($origin, array('streams', 'socials'), true)) {
            return new WP_Error('bzj_invalid_origin', 'origin_platform must be streams or socials.', array('status' => 400));
        }

        $allowed = array(
            'connection_request',
            'connection_accept',
            'connection_reject',
            'connection_withdraw',
            'connection_remove',
            'follow',
            'unfollow',
            'block',
            'unblock',
        );
        if (!in_array($operation, $allowed, true)) {
            return new WP_Error('bzj_invalid_operation', 'Unsupported relationship operation.', array('status' => 400));
        }

        if ($actor_external < 1 || $target_external < 1 || $actor_external === $target_external) {
            return new WP_Error('bzj_invalid_users', 'actor_id and target_id must be valid, different external user IDs.', array('status' => 400));
        }

        $actor_wp = $this->map_external_to_wp($origin, $actor_external);
        $target_wp = $this->map_external_to_wp($origin, $target_external);

        if (!$actor_wp || !$target_wp || $actor_wp === $target_wp) {
            return new WP_Error('bzj_mapping_missing', 'External user IDs could not be mapped to two valid WordPress users.', array('status' => 409));
        }

        if ($this->event_exists($event_uuid)) {
            return rest_ensure_response(array(
                'success' => true,
                'status' => 'already_processed',
                'event_id' => $event_uuid,
                'relationship' => $this->get_ledger($actor_wp, $target_wp),
            ));
        }

        if (!$this->acquire_pair_lock($actor_wp, $target_wp)) {
            return new WP_Error('bzj_pair_busy', 'The relationship pair is currently being synchronized. Retry the event.', array('status' => 409));
        }

        try {
            $this->insert_event($event_uuid, $origin, $operation, $actor_wp, $target_wp, $payload);

            $result = $this->apply_external_command($operation, $actor_wp, $target_wp, $event_uuid, $origin);

            if (is_wp_error($result)) {
                $this->mark_event_retry($event_uuid, $result->get_error_message());
                return new WP_Error('bzj_command_failed', $result->get_error_message(), array('status' => 409, 'event_id' => $event_uuid));
            }

            $this->mark_event_processed($event_uuid);
            return rest_ensure_response(array(
                'success' => true,
                'status' => 'processed',
                'event_id' => $event_uuid,
                'relationship' => $this->get_ledger($actor_wp, $target_wp),
            ));
        } catch (Throwable $e) {
            $this->mark_event_retry($event_uuid, $e->getMessage());
            $this->log('REST command exception', array('event_id' => $event_uuid, 'error' => $e->getMessage()));
            return new WP_Error('bzj_internal_error', 'The synchronization command could not be completed.', array('status' => 500, 'event_id' => $event_uuid));
        } finally {
            $this->release_pair_lock($actor_wp, $target_wp);
        }
    }

    private function authenticate_external_request(WP_REST_Request $request, $raw) {
        $timestamp = $request->get_header('X-BZJ-Timestamp');
        $signature = $request->get_header('X-BZJ-Signature');

        if (!$timestamp || !$signature || !ctype_digit((string)$timestamp)) {
            return new WP_Error('bzj_auth_missing', 'Missing authentication headers.', array('status' => 401));
        }

        $timestamp = (int)$timestamp;
        if (abs(time() - $timestamp) > 300) {
            return new WP_Error('bzj_auth_expired', 'Request timestamp is outside the allowed window.', array('status' => 401));
        }

        $secret = $this->get_sso_secret();
        if (!$secret) {
            return new WP_Error('bzj_auth_unavailable', 'BZJ authentication secret is unavailable.', array('status' => 503));
        }

        $expected = hash_hmac('sha256', $timestamp . '.' . $raw, $secret);
        if (!hash_equals($expected, trim($signature))) {
            return new WP_Error('bzj_auth_invalid', 'Invalid request signature.', array('status' => 401));
        }

        return true;
    }

    private function get_sso_secret() {
        $secret = getenv('BUZZ_SSO_SECRET');
        if (!$secret && defined('BUZZ_SSO_SECRET')) {
            $secret = BUZZ_SSO_SECRET;
        }
        if (!$secret && function_exists('bzj_get_sso_secret')) {
            $secret = bzj_get_sso_secret();
        }
        return is_string($secret) ? trim($secret) : '';
    }

    private function map_external_to_wp($origin, $external_id) {
        global $wpdb;
        $meta_key = ('streams' === $origin) ? 'wo_user_id' : 'qd_user_id';
        $value = $wpdb->get_var($wpdb->prepare(
            "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value = %s ORDER BY umeta_id ASC LIMIT 1",
            $meta_key,
            (string)$external_id
        ));
        return absint($value);
    }

    private function map_wp_to_external($wp_id, $platform) {
        $key = ('streams' === $platform) ? 'wo_user_id' : 'qd_user_id';
        $value = get_user_meta($wp_id, $key, true);
        return absint($value);
    }

    private function pair($a, $b) {
        $a = absint($a);
        $b = absint($b);
        return ($a < $b) ? array($a, $b) : array($b, $a);
    }

    private function lock_key($a, $b) {
        $pair = $this->pair($a, $b);
        return 'bzj_rel_lock_' . $pair[0] . '_' . $pair[1];
    }

    private function acquire_pair_lock($a, $b) {
        $key = $this->lock_key($a, $b);
        if (get_transient($key)) {
            return false;
        }
        set_transient($key, wp_generate_uuid4(), self::LOCK_TTL);
        return true;
    }

    private function release_pair_lock($a, $b) {
        delete_transient($this->lock_key($a, $b));
    }

    private function push_context($context) {
        $this->context_stack[] = $context;
    }

    private function pop_context() {
        array_pop($this->context_stack);
    }

    private function current_context() {
        if (empty($this->context_stack)) {
            return null;
        }
        return end($this->context_stack);
    }

    private function is_contextual_duplicate($operation, $actor, $target) {
        $ctx = $this->current_context();
        if (!$ctx) {
            return false;
        }
        return isset($ctx['operation'], $ctx['actor'], $ctx['target'])
            && $ctx['operation'] === $operation
            && (int)$ctx['actor'] === (int)$actor
            && (int)$ctx['target'] === (int)$target;
    }

    private function event_exists($event_uuid) {
        global $wpdb;
        return (bool)$wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$this->table_events} WHERE event_uuid = %s LIMIT 1",
            $event_uuid
        ));
    }

    private function insert_event($uuid, $origin, $operation, $actor, $target, $payload) {
        global $wpdb;
        $pair = $this->pair($actor, $target);
        $now = current_time('mysql', true);

        $ok = $wpdb->insert(
            $this->table_events,
            array(
                'event_uuid' => $uuid,
                'origin' => $origin,
                'operation' => $operation,
                'actor_wp_id' => $actor,
                'target_wp_id' => $target,
                'pair_a' => $pair[0],
                'pair_b' => $pair[1],
                'payload' => wp_json_encode($payload),
                'status' => 'pending',
                'attempts' => 0,
                'next_attempt_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ),
            array('%s','%s','%s','%d','%d','%d','%d','%s','%s','%d','%s','%s','%s')
        );

        if (!$ok && $wpdb->last_error) {
            if ($this->event_exists($uuid)) {
                return true;
            }
            throw new RuntimeException('Could not create synchronization event: ' . $wpdb->last_error);
        }
        return true;
    }

    private function mark_event_processed($uuid) {
        global $wpdb;
        $now = current_time('mysql', true);
        $wpdb->update(
            $this->table_events,
            array('status' => 'processed', 'processed_at' => $now, 'updated_at' => $now, 'last_error' => null),
            array('event_uuid' => $uuid),
            array('%s','%s','%s','%s'),
            array('%s')
        );
    }

    private function mark_event_retry($uuid, $error) {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT attempts FROM {$this->table_events} WHERE event_uuid = %s LIMIT 1",
            $uuid
        ));
        $attempts = $row ? ((int)$row->attempts + 1) : 1;
        $delay = min(3600, max(60, (int)pow(2, min($attempts, 10)) * 30));
        $status = ($attempts >= self::MAX_ATTEMPTS) ? 'failed' : 'retry';
        $next = gmdate('Y-m-d H:i:s', time() + $delay);
        $wpdb->update(
            $this->table_events,
            array(
                'status' => $status,
                'attempts' => $attempts,
                'next_attempt_at' => $next,
                'last_error' => mb_substr((string)$error, 0, 2000),
                'updated_at' => current_time('mysql', true),
            ),
            array('event_uuid' => $uuid),
            array('%s','%d','%s','%s','%s'),
            array('%s')
        );
    }

    private function get_ledger($a, $b) {
        global $wpdb;
        $pair = $this->pair($a, $b);
        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_ledger} WHERE user_a = %d AND user_b = %d LIMIT 1",
            $pair[0], $pair[1]
        ), ARRAY_A);

        if (!$row) {
            return array(
                'user_a' => $pair[0],
                'user_b' => $pair[1],
                'connection_state' => 'none',
                'requested_by' => 0,
                'follow_a_to_b' => 0,
                'follow_b_to_a' => 0,
                'block_a_to_b' => 0,
                'block_b_to_a' => 0,
                'version' => 0,
            );
        }

        foreach (array('user_a','user_b','requested_by','follow_a_to_b','follow_b_to_a','block_a_to_b','block_b_to_a','version') as $key) {
            $row[$key] = isset($row[$key]) ? (int)$row[$key] : 0;
        }
        return $row;
    }

    private function save_ledger($a, $b, $changes, $event_uuid = '') {
        global $wpdb;
        $pair = $this->pair($a, $b);
        $existing = $this->get_ledger($pair[0], $pair[1]);
        $now = current_time('mysql', true);

        $state = array(
            'connection_state' => $existing['connection_state'],
            'requested_by' => $existing['requested_by'],
            'follow_a_to_b' => $existing['follow_a_to_b'],
            'follow_b_to_a' => $existing['follow_b_to_a'],
            'block_a_to_b' => $existing['block_a_to_b'],
            'block_b_to_a' => $existing['block_b_to_a'],
        );

        foreach ($changes as $key => $value) {
            if (array_key_exists($key, $state)) {
                $state[$key] = $value;
            }
        }

        if ($state['block_a_to_b'] || $state['block_b_to_a']) {
            $state['connection_state'] = 'none';
            $state['requested_by'] = 0;
            $state['follow_a_to_b'] = 0;
            $state['follow_b_to_a'] = 0;
        }

        $data = array(
            'user_a' => $pair[0],
            'user_b' => $pair[1],
            'connection_state' => $state['connection_state'],
            'requested_by' => (int)$state['requested_by'],
            'follow_a_to_b' => (int)$state['follow_a_to_b'],
            'follow_b_to_a' => (int)$state['follow_b_to_a'],
            'block_a_to_b' => (int)$state['block_a_to_b'],
            'block_b_to_a' => (int)$state['block_b_to_a'],
            'version' => ((int)$existing['version'] + 1),
            'last_event_uuid' => $event_uuid,
            'updated_at' => $now,
        );

        if ((int)$existing['version'] === 0) {
            $data['created_at'] = $now;
            $wpdb->insert($this->table_ledger, $data, array(
                '%d','%d','%s','%d','%d','%d','%d','%d','%d','%s','%s'
            ));
        } else {
            $wpdb->update(
                $this->table_ledger,
                $data,
                array('user_a' => $pair[0], 'user_b' => $pair[1]),
                array('%d','%d','%s','%d','%d','%d','%d','%d','%d','%s','%s'),
                array('%d','%d')
            );
        }

        return $this->get_ledger($pair[0], $pair[1]);
    }

    private function operation_event($origin, $operation, $actor, $target, $payload = array()) {
        $uuid = wp_generate_uuid4();
        $this->insert_event($uuid, $origin, $operation, $actor, $target, $payload);
        return $uuid;
    }

    private function apply_external_command($operation, $actor, $target, $event_uuid, $origin) {
        $this->push_context(array(
            'event_uuid' => $event_uuid,
            'origin' => $origin,
            'operation' => $operation,
            'actor' => $actor,
            'target' => $target,
        ));

        try {
            switch ($operation) {
                case 'connection_request':
                    return $this->bb_connection_request_from_external($actor, $target, $event_uuid);
                case 'connection_accept':
                    return $this->bb_connection_accept_from_external($actor, $target, $event_uuid);
                case 'connection_reject':
                    return $this->bb_connection_reject_from_external($actor, $target, $event_uuid);
                case 'connection_withdraw':
                    return $this->bb_connection_withdraw_from_external($actor, $target, $event_uuid);
                case 'connection_remove':
                    return $this->bb_connection_remove_from_external($actor, $target, $event_uuid);
                case 'follow':
                    return $this->bb_follow_from_external($actor, $target, $event_uuid);
                case 'unfollow':
                    return $this->bb_unfollow_from_external($actor, $target, $event_uuid);
                case 'block':
                    return $this->bb_block_from_external($actor, $target, $event_uuid);
                case 'unblock':
                    return $this->bb_unblock_from_external($actor, $target, $event_uuid);
            }
            return new WP_Error('bzj_unknown_operation', 'Unknown operation.');
        } finally {
            $this->pop_context();
        }
    }

    private function bb_connection_request_from_external($actor, $target, $event_uuid) {
        if (!function_exists('friends_add_friend')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss friendship functions are unavailable.');
        }

        if ($this->is_blocked_canonical($actor, $target)) {
            return new WP_Error('bzj_blocked', 'The users are blocked; connection request rejected.');
        }

        $status = friends_check_friendship_status($actor, $target);
        if ($status === 'is_friend') {
            $this->save_ledger($actor, $target, array('connection_state' => 'connected', 'requested_by' => 0), $event_uuid);
            $this->project_social_connection($actor, $target, 'connected');
            return true;
        }

        if ($status === 'pending' || $status === 'awaiting_response') {
            $requested_by = $this->find_pending_request_initiator($actor, $target);
            $this->save_ledger($actor, $target, array('connection_state' => 'requested', 'requested_by' => $requested_by), $event_uuid);
            $this->project_social_connection($actor, $target, 'requested');
            return true;
        }

        $ok = friends_add_friend($actor, $target, false);
        if (!$ok) {
            return new WP_Error('bzj_bb_request_rejected', 'BuddyBoss rejected the connection request. Existing BuddyBoss restrictions remain authoritative.');
        }

        $this->save_ledger($actor, $target, array('connection_state' => 'requested', 'requested_by' => $actor), $event_uuid);
        $this->project_social_connection($actor, $target, 'requested');
        return true;
    }

    private function bb_connection_accept_from_external($actor, $target, $event_uuid) {
        if (!function_exists('friends_get_friendship_id') || !function_exists('friends_accept_friendship')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss friendship functions are unavailable.');
        }

        $friendship_id = friends_get_friendship_id($target, $actor);
        if (!$friendship_id) {
            $friendship_id = friends_get_friendship_id($actor, $target);
        }
        if (!$friendship_id) {
            return new WP_Error('bzj_no_pending_connection', 'No BuddyBoss connection request exists for this pair.');
        }

        $status = friends_check_friendship_status($actor, $target);
        if ($status === 'is_friend') {
            $this->save_ledger($actor, $target, array('connection_state' => 'connected', 'requested_by' => 0), $event_uuid);
            $this->project_social_connection($actor, $target, 'connected');
            return true;
        }

        if (!friends_accept_friendship($friendship_id)) {
            return new WP_Error('bzj_bb_accept_failed', 'BuddyBoss could not accept the connection request.');
        }

        $this->save_ledger($actor, $target, array('connection_state' => 'connected', 'requested_by' => 0), $event_uuid);
        $this->project_social_connection($actor, $target, 'connected');
        return true;
    }

    private function bb_connection_reject_from_external($actor, $target, $event_uuid) {
        if (!function_exists('friends_get_friendship_id') || !function_exists('friends_reject_friendship')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss friendship functions are unavailable.');
        }

        $friendship_id = friends_get_friendship_id($actor, $target);
        if (!$friendship_id) {
            $friendship_id = friends_get_friendship_id($target, $actor);
        }
        if ($friendship_id && !friends_reject_friendship($friendship_id)) {
            return new WP_Error('bzj_bb_reject_failed', 'BuddyBoss could not reject the connection request.');
        }

        $this->save_ledger($actor, $target, array('connection_state' => 'none', 'requested_by' => 0), $event_uuid);
        $this->project_social_connection($actor, $target, 'none');
        return true;
    }

    private function bb_connection_withdraw_from_external($actor, $target, $event_uuid) {
        if (!function_exists('friends_withdraw_friendship')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss friendship functions are unavailable.');
        }

        $ok = friends_withdraw_friendship($actor, $target);
        if (!$ok) {
            $status = function_exists('friends_check_friendship_status') ? friends_check_friendship_status($actor, $target) : '';
            if ($status !== 'not_friends') {
                return new WP_Error('bzj_bb_withdraw_failed', 'BuddyBoss could not withdraw the connection request.');
            }
        }

        $this->save_ledger($actor, $target, array('connection_state' => 'none', 'requested_by' => 0), $event_uuid);
        $this->project_social_connection($actor, $target, 'none');
        return true;
    }

    private function bb_connection_remove_from_external($actor, $target, $event_uuid) {
        if (!function_exists('friends_remove_friend')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss friendship functions are unavailable.');
        }

        $status = friends_check_friendship_status($actor, $target);
        if ($status === 'is_friend') {
            if (!friends_remove_friend($actor, $target)) {
                return new WP_Error('bzj_bb_remove_failed', 'BuddyBoss could not remove the connection.');
            }
        }

        $this->save_ledger($actor, $target, array('connection_state' => 'none', 'requested_by' => 0), $event_uuid);
        $this->project_social_connection($actor, $target, 'none');
        return true;
    }

    private function bb_follow_from_external($actor, $target, $event_uuid) {
        if (!function_exists('bp_start_following')) {
            return new WP_Error('bzj_bb_follow_unavailable', 'BuddyBoss follow functions are unavailable.');
        }

        if ($this->is_blocked_canonical($actor, $target)) {
            return new WP_Error('bzj_blocked', 'The users are blocked; follow rejected.');
        }

        $ok = bp_start_following(array('leader_id' => $target, 'follower_id' => $actor));
        if (is_wp_error($ok)) {
            return $ok;
        }
        if ($ok === false) {
            return new WP_Error('bzj_bb_follow_failed', 'BuddyBoss rejected the follow operation.');
        }

        $this->set_follow_state($actor, $target, true, $event_uuid);
        $this->project_external_follow($actor, $target, true, 'streams');
        $this->project_external_follow($actor, $target, true, 'socials');
        return true;
    }

    private function bb_unfollow_from_external($actor, $target, $event_uuid) {
        if (!function_exists('bp_stop_following')) {
            return new WP_Error('bzj_bb_follow_unavailable', 'BuddyBoss follow functions are unavailable.');
        }

        $ok = bp_stop_following(array('leader_id' => $target, 'follower_id' => $actor));
        if (is_wp_error($ok)) {
            return $ok;
        }
        if ($ok === false) {
            return new WP_Error('bzj_bb_unfollow_failed', 'BuddyBoss rejected the unfollow operation.');
        }

        $this->set_follow_state($actor, $target, false, $event_uuid);
        $this->project_external_follow($actor, $target, false, 'streams');
        $this->project_external_follow($actor, $target, false, 'socials');
        return true;
    }

    private function bb_block_from_external($actor, $target, $event_uuid) {
        if (!class_exists('BP_Moderation') || !class_exists('BP_Moderation_Members')) {
            return new WP_Error('bzj_bb_moderation_unavailable', 'BuddyBoss moderation classes are unavailable.');
        }

        if ($this->is_blocked_canonical($actor, $target)) {
            return true;
        }

        /*
         * Use BuddyBoss's own moderation object rather than writing its
         * moderation tables directly. The constructor's third argument is
         * the blocking user ID; save() executes the native BP_Core_Suspend
         * path and fires bp_moderation_after_save.
         */
        $this->push_context(array(
            'event_uuid' => $event_uuid,
            'origin' => 'external',
            'operation' => 'block',
            'actor' => $actor,
            'target' => $target,
        ));
        try {
            $moderation = new BP_Moderation($target, BP_Moderation_Members::$moderation_type, $actor);
            $moderation->user_report = 0;
            $moderation->content = '';
            if (!$moderation->save()) {
                return new WP_Error('bzj_bb_block_failed', 'BuddyBoss could not create the member block.');
            }
        } finally {
            $this->pop_context();
        }

        /*
         * The after_save hook normally records the canonical block and
         * performs enforcement. Re-read state here so an older BuddyBoss
         * build without that hook still gets a canonical update.
         */
        $this->save_ledger($actor, $target, array(
            $this->directional_field($actor, $target, 'block') => 1,
            'connection_state' => 'none',
            'requested_by' => 0,
            'follow_a_to_b' => 0,
            'follow_b_to_a' => 0,
        ), $event_uuid);

        $this->project_external_block($actor, $target, true, 'streams');
        $this->project_external_block($actor, $target, true, 'socials');
        return true;
    }

    private function bb_unblock_from_external($actor, $target, $event_uuid) {
        if (!class_exists('BP_Moderation') || !class_exists('BP_Moderation_Members')) {
            return new WP_Error('bzj_bb_moderation_unavailable', 'BuddyBoss moderation classes are unavailable.');
        }

        $this->push_context(array(
            'event_uuid' => $event_uuid,
            'origin' => 'external',
            'operation' => 'unblock',
            'actor' => $actor,
            'target' => $target,
        ));
        try {
            $moderation = new BP_Moderation($target, BP_Moderation_Members::$moderation_type, $actor);
            if (!empty($moderation->id) && empty($moderation->user_report)) {
                if (!$moderation->delete(false)) {
                    return new WP_Error('bzj_bb_unblock_failed', 'BuddyBoss could not remove the member block.');
                }
            }
        } finally {
            $this->pop_context();
        }

        $this->save_ledger($actor, $target, array(
            $this->directional_field($actor, $target, 'block') => 0,
        ), $event_uuid);

        $this->project_external_block($actor, $target, false, 'streams');
        $this->project_external_block($actor, $target, false, 'socials');
        return true;
    }

    private function find_pending_request_initiator($a, $b) {
        if (function_exists('friends_check_friendship_status')) {
            $status_ab = friends_check_friendship_status($a, $b);
            if ($status_ab === 'awaiting_response') {
                return $a;
            }
            $status_ba = friends_check_friendship_status($b, $a);
            if ($status_ba === 'awaiting_response') {
                return $b;
            }
        }
        return 0;
    }

    private function set_follow_state($actor, $target, $state, $event_uuid) {
        $pair = $this->pair($actor, $target);
        $field = ((int)$actor === $pair[0]) ? 'follow_a_to_b' : 'follow_b_to_a';
        return $this->save_ledger($pair[0], $pair[1], array($field => $state ? 1 : 0), $event_uuid);
    }

    private function is_blocked_canonical($a, $b) {
        $row = $this->get_ledger($a, $b);
        return !empty($row['block_a_to_b']) || !empty($row['block_b_to_a']);
    }

    /* ---------- BuddyBoss-origin event handlers ---------- */

    public function bb_connection_requested($friendship_id, $initiator, $friend, $friendship = null) {
        if ($this->is_contextual_duplicate('connection_request', $initiator, $friend)) {
            return;
        }
        $this->handle_bb_connection_event('connection_request', $initiator, $friend, $friendship_id);
    }

    public function bb_connection_accepted($friendship_id, $initiator, $friend, $friendship = null) {
        if ($this->is_contextual_duplicate('connection_accept', $initiator, $friend)) {
            return;
        }
        $this->handle_bb_connection_event('connection_accept', $initiator, $friend, $friendship_id);
    }

    public function bb_connection_rejected($friendship_id, $friendship) {
        if (!is_object($friendship)) {
            return;
        }
        $actor = absint($friendship->initiator_user_id);
        $target = absint($friendship->friend_user_id);
        if ($this->is_contextual_duplicate('connection_reject', $actor, $target)) {
            return;
        }
        $this->handle_bb_connection_event('connection_reject', $actor, $target, $friendship_id);
    }

    public function bb_connection_withdrawn($friendship_id, $friendship) {
        if (!is_object($friendship)) {
            return;
        }
        $actor = absint($friendship->initiator_user_id);
        $target = absint($friendship->friend_user_id);
        if ($this->is_contextual_duplicate('connection_withdraw', $actor, $target)) {
            return;
        }
        $this->handle_bb_connection_event('connection_withdraw', $actor, $target, $friendship_id);
    }

    public function bb_connection_deleted($friendship_id, $initiator, $friend) {
        if ($this->is_contextual_duplicate('connection_remove', $initiator, $friend)) {
            return;
        }

        $row = $this->get_ledger($initiator, $friend);
        /*
         * If there is no confirmed connection but a pending state exists,
         * don't treat a deletion as a friendship removal unless the ledger
         * confirms a connected state.
         */
        $operation = ($row['connection_state'] === 'requested') ? 'connection_withdraw' : 'connection_remove';
        $this->handle_bb_connection_event($operation, $initiator, $friend, $friendship_id);
    }

    private function handle_bb_connection_event($operation, $actor, $target, $native_id = 0) {
        if ($actor < 1 || $target < 1 || $actor === $target) {
            return;
        }

        if (!$this->acquire_pair_lock($actor, $target)) {
            $uuid = $this->operation_event('wordpress', $operation, $actor, $target, array('native_id' => $native_id));
            $this->mark_event_retry($uuid, 'Pair lock busy.');
            return;
        }

        try {
            $uuid = $this->operation_event('wordpress', $operation, $actor, $target, array('native_id' => $native_id));
            switch ($operation) {
                case 'connection_request':
                    $this->save_ledger($actor, $target, array('connection_state' => 'requested', 'requested_by' => $actor), $uuid);
                    $this->project_social_connection($actor, $target, 'requested');
                    break;
                case 'connection_accept':
                    $this->save_ledger($actor, $target, array('connection_state' => 'connected', 'requested_by' => 0), $uuid);
                    $this->project_social_connection($actor, $target, 'connected');
                    break;
                case 'connection_reject':
                case 'connection_withdraw':
                case 'connection_remove':
                    $this->save_ledger($actor, $target, array('connection_state' => 'none', 'requested_by' => 0), $uuid);
                    $this->project_social_connection($actor, $target, 'none');
                    break;
            }
            $this->mark_event_processed($uuid);
        } catch (Throwable $e) {
            if (!empty($uuid)) {
                $this->mark_event_retry($uuid, $e->getMessage());
            }
            $this->log('BuddyBoss connection event failed', array('operation' => $operation, 'actor' => $actor, 'target' => $target, 'error' => $e->getMessage()));
        } finally {
            $this->release_pair_lock($actor, $target);
        }
    }

    public function bb_follow_started($leader_id, $follower_id) {
        $target = absint($leader_id);
        $actor = absint($follower_id);
        if ($actor < 1 || $target < 1 || $actor === $target || $this->is_contextual_duplicate('follow', $actor, $target)) {
            return;
        }
        $this->handle_bb_follow_event('follow', $actor, $target);
    }

    public function bb_follow_stopped($leader_id, $follower_id) {
        $target = absint($leader_id);
        $actor = absint($follower_id);
        if ($actor < 1 || $target < 1 || $actor === $target || $this->is_contextual_duplicate('unfollow', $actor, $target)) {
            return;
        }
        $this->handle_bb_follow_event('unfollow', $actor, $target);
    }

    private function handle_bb_follow_event($operation, $actor, $target) {
        if (!$this->acquire_pair_lock($actor, $target)) {
            $uuid = $this->operation_event('wordpress', $operation, $actor, $target);
            $this->mark_event_retry($uuid, 'Pair lock busy.');
            return;
        }

        try {
            $uuid = $this->operation_event('wordpress', $operation, $actor, $target);
            $this->set_follow_state($actor, $target, $operation === 'follow', $uuid);
            $this->project_external_follow($actor, $target, $operation === 'follow', 'streams');
            $this->project_external_follow($actor, $target, $operation === 'follow', 'socials');
            $this->mark_event_processed($uuid);
        } catch (Throwable $e) {
            if (!empty($uuid)) {
                $this->mark_event_retry($uuid, $e->getMessage());
            }
            $this->log('BuddyBoss follow event failed', array('operation' => $operation, 'actor' => $actor, 'target' => $target, 'error' => $e->getMessage()));
        } finally {
            $this->release_pair_lock($actor, $target);
        }
    }

    public function bb_moderation_saved($moderation) {
        if (!is_object($moderation)) {
            return;
        }
        if (!isset($moderation->item_type) || $moderation->item_type !== 'user') {
            return;
        }
        if (!empty($moderation->user_report)) {
            return;
        }

        $actor = absint($moderation->user_id);
        $target = absint($moderation->item_id);
        if ($actor < 1 || $target < 1 || $actor === $target) {
            return;
        }

        $this->handle_bb_block_event('block', $actor, $target, absint($moderation->id));
    }

    public function bb_moderation_deleted($moderation) {
        if (!is_object($moderation)) {
            return;
        }
        if (!isset($moderation->item_type) || $moderation->item_type !== 'user') {
            return;
        }
        if (!empty($moderation->user_report)) {
            return;
        }

        $actor = absint($moderation->user_id);
        $target = absint($moderation->item_id);
        if ($actor < 1 || $target < 1 || $actor === $target) {
            return;
        }

        $this->handle_bb_block_event('unblock', $actor, $target, absint($moderation->id));
    }

    private function handle_bb_block_event($operation, $actor, $target, $native_id = 0) {
        if ($this->is_contextual_duplicate($operation, $actor, $target)) {
            return;
        }

        if (!$this->acquire_pair_lock($actor, $target)) {
            $uuid = $this->operation_event('wordpress', $operation, $actor, $target, array('native_id' => $native_id));
            $this->mark_event_retry($uuid, 'Pair lock busy.');
            return;
        }

        try {
            $uuid = $this->operation_event('wordpress', $operation, $actor, $target, array('native_id' => $native_id));

            if ($operation === 'block') {
                /*
                 * Block dominates connection/follow. If a connection exists,
                 * remove it through the native BuddyBoss API. This fires the
                 * normal friendship hooks, which are ignored via context.
                 */
                $this->save_ledger($actor, $target, array(
                    $this->directional_field($actor, $target, 'block') => 1,
                    'connection_state' => 'none',
                    'requested_by' => 0,
                    'follow_a_to_b' => 0,
                    'follow_b_to_a' => 0,
                ), $uuid);

                if (function_exists('friends_check_friendship_status') && function_exists('friends_remove_friend')) {
                    if (friends_check_friendship_status($actor, $target) === 'is_friend') {
                        $this->push_context(array(
                            'event_uuid' => $uuid,
                            'origin' => 'wordpress',
                            'operation' => 'connection_remove',
                            'actor' => $actor,
                            'target' => $target,
                        ));
                        try {
                            friends_remove_friend($actor, $target);
                        } finally {
                            $this->pop_context();
                        }
                    }
                }

                if (function_exists('bp_stop_following')) {
                    foreach (array($actor, $target) as $possible_actor) {
                        $possible_target = ($possible_actor === $actor) ? $target : $actor;
                        $this->push_context(array(
                            'event_uuid' => $uuid,
                            'origin' => 'wordpress',
                            'operation' => 'unfollow',
                            'actor' => $possible_actor,
                            'target' => $possible_target,
                        ));
                        try {
                            bp_stop_following(array('leader_id' => $possible_target, 'follower_id' => $possible_actor));
                        } catch (Throwable $e) {
                            $this->log('BuddyBoss follow removal during block failed', array('error' => $e->getMessage(), 'actor' => $possible_actor, 'target' => $possible_target));
                        } finally {
                            $this->pop_context();
                        }
                    }
                }

                $this->project_external_block($actor, $target, true, 'streams');
                $this->project_external_block($actor, $target, true, 'socials');
            } else {
                $this->save_ledger($actor, $target, array(
                    $this->directional_field($actor, $target, 'block') => 0,
                ), $uuid);

                $this->project_external_block($actor, $target, false, 'streams');
                $this->project_external_block($actor, $target, false, 'socials');
            }

            $this->mark_event_processed($uuid);
        } catch (Throwable $e) {
            if (!empty($uuid)) {
                $this->mark_event_retry($uuid, $e->getMessage());
            }
            $this->log('BuddyBoss block event failed', array('operation' => $operation, 'actor' => $actor, 'target' => $target, 'error' => $e->getMessage()));
        } finally {
            $this->release_pair_lock($actor, $target);
        }
    }

    private function directional_field($actor, $target, $type) {
        $pair = $this->pair($actor, $target);
        $prefix = ((int)$actor === $pair[0]) ? 'a' : 'b';
        return $type === 'block' ? 'block_' . $prefix . '_to_' . ($prefix === 'a' ? 'b' : 'a') : '';
    }

    /* ---------- External projections ---------- */

    private function project_social_connection($a, $b, $state) {
        $qd_a = $this->map_wp_to_external($a, 'socials');
        $qd_b = $this->map_wp_to_external($b, 'socials');
        if (!$qd_a || !$qd_b) {
            $this->log('QuickDate connection projection skipped: missing user mapping', array('a' => $a, 'b' => $b));
            return false;
        }

        $db = $this->get_qd_db();
        if (!$db) {
            $this->queue_projection('socials', 'connection_' . $state, $a, $b, array('qd_a' => $qd_a, 'qd_b' => $qd_b));
            return false;
        }

        if ($state === 'requested') {
            $this->qd_set_pending_friend($db, $qd_a, $qd_b);
        } elseif ($state === 'connected') {
            $this->qd_set_active_friend($db, $qd_a, $qd_b);
        } else {
            $this->qd_remove_relationship($db, $qd_a, $qd_b);
        }
        return true;
    }

    private function project_external_follow($actor, $target, $active, $platform) {
        $external_actor = $this->map_wp_to_external($actor, $platform);
        $external_target = $this->map_wp_to_external($target, $platform);
        if (!$external_actor || !$external_target) {
            $this->log('Follow projection skipped: missing user mapping', array('platform' => $platform, 'actor' => $actor, 'target' => $target));
            return false;
        }

        $db = ('streams' === $platform) ? $this->get_streams_db() : $this->get_qd_db();
        if (!$db) {
            $this->queue_projection($platform, $active ? 'follow' : 'unfollow', $actor, $target, array(
                'external_actor' => $external_actor,
                'external_target' => $external_target,
            ));
            return false;
        }

        if ($active) {
            $this->external_set_follow($db, $external_target, $external_actor, $platform);
        } else {
            $this->external_delete_follow($db, $external_target, $external_actor, $platform);
        }
        return true;
    }

    private function project_external_block($actor, $target, $active, $platform) {
        $external_actor = $this->map_wp_to_external($actor, $platform);
        $external_target = $this->map_wp_to_external($target, $platform);
        if (!$external_actor || !$external_target) {
            $this->log('Block projection skipped: missing user mapping', array('platform' => $platform, 'actor' => $actor, 'target' => $target));
            return false;
        }

        $db = ('streams' === $platform) ? $this->get_streams_db() : $this->get_qd_db();
        if (!$db) {
            $this->queue_projection($platform, $active ? 'block' : 'unblock', $actor, $target, array(
                'external_actor' => $external_actor,
                'external_target' => $external_target,
            ));
            return false;
        }

        if ($active) {
            $this->external_set_block($db, $external_actor, $external_target, $platform);
            $this->external_delete_follow($db, $external_target, $external_actor, $platform);
            $this->external_delete_follow($db, $external_actor, $external_target, $platform);
        } else {
            $this->external_delete_block($db, $external_actor, $external_target, $platform);
        }
        return true;
    }

    private function queue_projection($platform, $operation, $actor, $target, $payload) {
        $uuid = $this->operation_event('wordpress', 'projection_' . $operation, $actor, $target, array_merge(
            array('platform' => $platform),
            $payload
        ));
        $this->mark_event_retry($uuid, 'External platform database unavailable or mapping unavailable.');
        return $uuid;
    }

    private function get_qd_db() {
        if (function_exists('get_qd_db_conn')) {
            $db = get_qd_db_conn();
            if ($db instanceof mysqli && !$db->connect_errno) {
                return $db;
            }
        }
        $path = ABSPATH . 'shared/db_helpers.php';
        if (file_exists($path)) {
            require_once $path;
        }
        if (function_exists('get_qd_db_conn')) {
            $db = get_qd_db_conn();
            if ($db instanceof mysqli && !$db->connect_errno) {
                return $db;
            }
        }
        return false;
    }

    private function get_streams_db() {
        if (function_exists('get_wowonder_db')) {
            $db = get_wowonder_db();
            if ($db instanceof mysqli && !$db->connect_errno) {
                return $db;
            }
        }
        $path = ABSPATH . 'shared/db_helpers.php';
        if (file_exists($path)) {
            require_once $path;
        }
        if (function_exists('get_wowonder_db')) {
            $db = get_wowonder_db();
            if ($db instanceof mysqli && !$db->connect_errno) {
                return $db;
            }
        }
        return false;
    }

    private function external_set_follow($db, $following_id, $follower_id, $platform) {
        $table = 'followers';
        $following_id = absint($following_id);
        $follower_id = absint($follower_id);

        $stmt = $db->prepare("SELECT id FROM `{$table}` WHERE following_id = ? AND follower_id = ? LIMIT 1");
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $stmt->bind_param('ii', $following_id, $follower_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if ($existing) {
            $stmt = $db->prepare("UPDATE `{$table}` SET active = 1 WHERE id = ?");
            $stmt->bind_param('i', $existing['id']);
            $ok = $stmt->execute();
            $err = $stmt->error;
            $stmt->close();
            if (!$ok) {
                throw new RuntimeException($err ?: 'Follow update failed.');
            }
            return true;
        }

        $created = time();
        $stmt = $db->prepare("INSERT INTO `{$table}` (following_id, follower_id, active, created_at) VALUES (?, ?, 1, ?)");
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $stmt->bind_param('iii', $following_id, $follower_id, $created);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();
        if (!$ok) {
            throw new RuntimeException($err ?: 'Follow insert failed.');
        }
        return true;
    }

    private function external_delete_follow($db, $following_id, $follower_id, $platform) {
        $following_id = absint($following_id);
        $follower_id = absint($follower_id);
        $stmt = $db->prepare("DELETE FROM `followers` WHERE following_id = ? AND follower_id = ?");
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $stmt->bind_param('ii', $following_id, $follower_id);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();
        if (!$ok) {
            throw new RuntimeException($err ?: 'Follow deletion failed.');
        }

        /*
         * QuickDate's connectivity system removes the reciprocal row when
         * its native Wo_DeleteFollow() is used. We deliberately delete only
         * the requested directional row here; connection removal is handled
         * by project_social_connection().
         */
        return true;
    }

    private function qd_set_pending_friend($db, $actor, $target) {
        /*
         * QuickDate's native add_friend() creates a pending follower row
         * through Wo_RegisterFollow(). In the supplied Socials source this is
         * followers(following_id, follower_id, active=0).
         */
        $this->qd_remove_relationship($db, $actor, $target);
        $stmt = $db->prepare("INSERT INTO `followers` (following_id, follower_id, active, created_at) VALUES (?, ?, 0, ?)");
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $now = time();
        $stmt->bind_param('iii', $target, $actor, $now);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();
        if (!$ok) {
            throw new RuntimeException($err ?: 'QuickDate pending connection insert failed.');
        }
        return true;
    }

    private function qd_set_active_friend($db, $actor, $target) {
        /*
         * A QuickDate accepted friend is an active follower relationship.
         * Existing opposite-direction rows are preserved because the current
         * application uses the same follower infrastructure for connectivity.
         */
        $stmt = $db->prepare("SELECT id FROM `followers` WHERE following_id = ? AND follower_id = ? LIMIT 1");
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $stmt->bind_param('ii', $target, $actor);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if ($row) {
            $stmt = $db->prepare("UPDATE `followers` SET active = 1 WHERE id = ?");
            $stmt->bind_param('i', $row['id']);
            $ok = $stmt->execute();
            $err = $stmt->error;
            $stmt->close();
            if (!$ok) {
                throw new RuntimeException($err ?: 'QuickDate friend activation failed.');
            }
            return true;
        }

        $now = time();
        $stmt = $db->prepare("INSERT INTO `followers` (following_id, follower_id, active, created_at) VALUES (?, ?, 1, ?)");
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $stmt->bind_param('iii', $target, $actor, $now);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();
        if (!$ok) {
            throw new RuntimeException($err ?: 'QuickDate friend insert failed.');
        }
        return true;
    }

    private function qd_remove_relationship($db, $actor, $target) {
        $stmt = $db->prepare("DELETE FROM `followers` WHERE (following_id = ? AND follower_id = ?) OR (following_id = ? AND follower_id = ?)");
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $stmt->bind_param('iiii', $target, $actor, $actor, $target);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();
        if (!$ok) {
            throw new RuntimeException($err ?: 'QuickDate relationship removal failed.');
        }
        return true;
    }

    private function external_set_block($db, $actor, $target, $platform) {
        if ($platform === 'streams') {
            $stmt = $db->prepare("SELECT id FROM `blocks` WHERE blocker = ? AND blocked = ? LIMIT 1");
            if (!$stmt) {
                throw new RuntimeException($db->error);
            }
            $stmt->bind_param('ii', $actor, $target);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result ? $result->fetch_assoc() : null;
            $stmt->close();

            if ($row) {
                return true;
            }

            $stmt = $db->prepare("INSERT INTO `blocks` (blocker, blocked) VALUES (?, ?)");
            if (!$stmt) {
                throw new RuntimeException($db->error);
            }
            $stmt->bind_param('ii', $actor, $target);
            $ok = $stmt->execute();
            $err = $stmt->error;
            $stmt->close();
            if (!$ok) {
                throw new RuntimeException($err ?: 'Streams block insert failed.');
            }
            return true;
        }

        $stmt = $db->prepare("SELECT id FROM `blocks` WHERE user_id = ? AND block_userid = ? LIMIT 1");
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $stmt->bind_param('ii', $actor, $target);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if ($row) {
            return true;
        }

        $now = time();
        $stmt = $db->prepare("INSERT INTO `blocks` (user_id, block_userid, created_at) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $stmt->bind_param('iii', $actor, $target, $now);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();
        if (!$ok) {
            throw new RuntimeException($err ?: 'Socials block insert failed.');
        }
        return true;
    }

    private function external_delete_block($db, $actor, $target, $platform) {
        if ($platform === 'streams') {
            $stmt = $db->prepare("DELETE FROM `blocks` WHERE blocker = ? AND blocked = ?");
        } else {
            $stmt = $db->prepare("DELETE FROM `blocks` WHERE user_id = ? AND block_userid = ?");
        }
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }
        $stmt->bind_param('ii', $actor, $target);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();
        if (!$ok) {
            throw new RuntimeException($err ?: 'External block removal failed.');
        }
        return true;
    }

    /* ---------- Queue ---------- */

    public function process_queue() {
        global $wpdb;
        $now = current_time('mysql', true);

        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->table_events}
             WHERE status IN ('pending','retry') AND next_attempt_at <= %s
             ORDER BY id ASC LIMIT 25",
            $now
        ));

        foreach ((array)$rows as $row) {
            $this->process_event_row($row);
        }
    }

    private function process_event_row($row) {
        $uuid = $row->event_uuid;
        if ($this->event_exists($uuid) && $row->status === 'processed') {
            return;
        }

        $payload = json_decode((string)$row->payload, true);
        if (!is_array($payload)) {
            $this->mark_event_retry($uuid, 'Invalid stored event payload.');
            return;
        }

        $operation = (string)$row->operation;
        if (strpos($operation, 'projection_') === 0) {
            $this->process_projection_event($row, $payload);
            return;
        }

        /*
         * A non-projection external command has already been applied to
         * WordPress before it enters retry state. Do not repeat the command
         * blindly. Reconciliation will repair external projections.
         */
        $this->reconcile_pair((int)$row->pair_a, (int)$row->pair_b);
        $this->mark_event_processed($uuid);
    }

    private function process_projection_event($row, $payload) {
        $platform = isset($payload['platform']) ? sanitize_key($payload['platform']) : '';
        $operation = substr((string)$row->operation, strlen('projection_'));
        $actor = (int)$row->actor_wp_id;
        $target = (int)$row->target_wp_id;

        try {
            if ($platform === 'socials' && strpos($operation, 'connection_') === 0) {
                $state = substr($operation, strlen('connection_'));
                if (!$this->project_social_connection($actor, $target, $state)) {
                    throw new RuntimeException('QuickDate projection unavailable.');
                }
            } elseif (($platform === 'streams' || $platform === 'socials') && in_array($operation, array('follow','unfollow'), true)) {
                if (!$this->project_external_follow($actor, $target, $operation === 'follow', $platform)) {
                    throw new RuntimeException('Follow projection unavailable.');
                }
            } elseif (($platform === 'streams' || $platform === 'socials') && in_array($operation, array('block','unblock'), true)) {
                if (!$this->project_external_block($actor, $target, $operation === 'block', $platform)) {
                    throw new RuntimeException('Block projection unavailable.');
                }
            } else {
                throw new RuntimeException('Unknown projection event.');
            }

            $this->mark_event_processed($row->event_uuid);
        } catch (Throwable $e) {
            $this->mark_event_retry($row->event_uuid, $e->getMessage());
        }
    }

    /* ---------- Reconciliation ---------- */

    public function reconcile_queue() {
        global $wpdb;
        $rows = $wpdb->get_results(
            "SELECT user_a,user_b FROM {$this->table_ledger}
             WHERE updated_at >= UTC_TIMESTAMP() - INTERVAL 7 DAY
             ORDER BY updated_at DESC LIMIT 100"
        );

        foreach ((array)$rows as $row) {
            $this->reconcile_pair((int)$row->user_a, (int)$row->user_b);
        }
    }

    private function reconcile_pair($a, $b) {
        if (!$this->acquire_pair_lock($a, $b)) {
            return false;
        }

        try {
            $ledger = $this->get_ledger($a, $b);

            /* Canonical block state wins. */
            if (!empty($ledger['block_a_to_b'])) {
                $this->project_external_block($a, $b, true, 'streams');
                $this->project_external_block($a, $b, true, 'socials');
            }
            if (!empty($ledger['block_b_to_a'])) {
                $this->project_external_block($b, $a, true, 'streams');
                $this->project_external_block($b, $a, true, 'socials');
            }

            $this->project_social_connection($a, $b, $ledger['connection_state']);

            $this->project_external_follow($a, $b, !empty($ledger['follow_a_to_b']), 'streams');
            $this->project_external_follow($a, $b, !empty($ledger['follow_a_to_b']), 'socials');
            $this->project_external_follow($b, $a, !empty($ledger['follow_b_to_a']), 'streams');
            $this->project_external_follow($b, $a, !empty($ledger['follow_b_to_a']), 'socials');

            return true;
        } finally {
            $this->release_pair_lock($a, $b);
        }
    }

    private function get_pair_queue($a, $b) {
        global $wpdb;
        $pair = $this->pair($a, $b);
        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT event_uuid,origin,operation,status,attempts,next_attempt_at,last_error,created_at,updated_at
             FROM {$this->table_events}
             WHERE pair_a = %d AND pair_b = %d
             ORDER BY id DESC LIMIT 20",
            $pair[0], $pair[1]
        ), ARRAY_A);
        return $rows ?: array();
    }

    /* ---------- Logging ---------- */

    private function log($message, $context = array()) {
        $root = defined('ABSPATH') ? ABSPATH : dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $dir = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'logs';
        if (!is_dir($dir)) {
            @wp_mkdir_p($dir);
        }
        $file = $dir . DIRECTORY_SEPARATOR . self::LOG_FILE;
        $record = array(
            'time' => gmdate('c'),
            'message' => (string)$message,
            'context' => $context,
        );
        @file_put_contents(
            $file,
            wp_json_encode($record, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    private function is_uuid($value) {
        return (bool)preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            (string)$value
        );
    }
}

function bzj_connections_sync() {
    return BZJ_Connections_Sync::instance();
}

register_activation_hook(__FILE__, array('BZJ_Connections_Sync', 'activate'));
register_deactivation_hook(__FILE__, array('BZJ_Connections_Sync', 'deactivate'));

add_filter('cron_schedules', function($schedules) {
    if (!isset($schedules['minute'])) {
        $schedules['minute'] = array(
            'interval' => 60,
            'display' => 'Every Minute',
        );
    }
    return $schedules;
});

bzj_connections_sync();
