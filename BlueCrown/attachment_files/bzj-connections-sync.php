<?php
/**
 * Plugin Name: BZJ Connections Synchronization Control Plane
 * Description: Canonical Buzzjuice relationship control plane. WordPress/BuddyBoss is the source of truth for connections, follows and blocks; Streams and Socials are synchronized projections.
 * Version: 7.0.0
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

    const VERSION = '7.0.0';
    const REST_NAMESPACE = 'bzj/v6';
    const REST_ROUTE = '/connection-management';

    /*
     * These are operational state/event tables, not log tables.
     * Audit/debug logging is written to /data/logs.
     */
    const LEDGER_TABLE = 'bzj_relationships';
    const EVENTS_TABLE = 'bzj_relationship_events';

    const LOG_FILE = 'bzj-connections-sync.log';
    const LOCK_TTL = 30;
    const MAX_ATTEMPTS = 12;
    const EXTERNAL_TIMESTAMP_WINDOW = 300;

    private static $instance;
    private $ledger_table;
    private $events_table;
    private $context = array();
    private $held_locks = array();

    public static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        global $wpdb;

        $this->ledger_table = $wpdb->prefix . self::LEDGER_TABLE;
        $this->events_table = $wpdb->prefix . self::EVENTS_TABLE;

        add_action('rest_api_init', array($this, 'register_rest_routes'));

        /*
         * BuddyBoss connection hooks. These signatures are based on the
         * Buzzjuice BuddyBoss source currently in use.
         */
        add_action('friends_friendship_requested', array($this, 'bb_connection_requested'), 20, 4);
        add_action('friends_friendship_accepted', array($this, 'bb_connection_accepted'), 20, 4);
        add_action('friends_friendship_rejected', array($this, 'bb_connection_rejected'), 20, 2);
        add_action('friends_friendship_withdrawn', array($this, 'bb_connection_withdrawn'), 20, 2);
        add_action('friends_friendship_deleted', array($this, 'bb_connection_deleted'), 1, 3);

        /*
         * Both names are supported because BuddyBoss/BuddyPress builds may
         * expose either hook name.
         */
        add_action('bp_start_following', array($this, 'bb_follow_started'), 20, 2);
        add_action('bp_follow_start_following', array($this, 'bb_follow_started'), 20, 2);
        add_action('bp_stop_following', array($this, 'bb_follow_stopped'), 20, 2);
        add_action('bp_follow_stop_following', array($this, 'bb_follow_stopped'), 20, 2);

        /*
         * Current Buzzjuice BuddyBoss moderation implementation exposes these
         * lifecycle hooks. We deliberately inspect only member-block records.
         */
        add_action('bp_moderation_after_save', array($this, 'bb_moderation_saved'), 20, 1);
        add_action('bb_moderation_after_delete', array($this, 'bb_moderation_deleted'), 20, 1);

        add_action('bzj_connections_sync_process_queue', array($this, 'process_queue'));
        add_action('bzj_connections_sync_reconcile', array($this, 'reconcile_recent'));

        add_filter('cron_schedules', array($this, 'cron_schedules'));

        /*
         * MU plugins do not have a dependable activation lifecycle, so schema
         * and schedules are checked at init.
         */
        add_action('init', array($this, 'bootstrap'), 1);
    }

    public function cron_schedules($schedules) {
        if (!isset($schedules['bzj_minute'])) {
            $schedules['bzj_minute'] = array(
                'interval' => 60,
                'display'  => 'BZJ Every Minute',
            );
        }
        return $schedules;
    }

    public function bootstrap() {
        $version = get_option('bzj_connections_sync_schema_version', '');
        if ($version !== self::VERSION) {
            $this->install_schema();
        }
        if (!wp_next_scheduled('bzj_connections_sync_process_queue')) {
            wp_schedule_event(time() + 30, 'bzj_minute', 'bzj_connections_sync_process_queue');
        }
        if (!wp_next_scheduled('bzj_connections_sync_reconcile')) {
            wp_schedule_event(time() + 300, 'hourly', 'bzj_connections_sync_reconcile');
        }
    }

    public static function activate() {
        self::instance()->install_schema();
        self::instance()->bootstrap();
    }

    public static function deactivate() {
        wp_clear_scheduled_hook('bzj_connections_sync_process_queue');
        wp_clear_scheduled_hook('bzj_connections_sync_reconcile');
    }

    private function install_schema() {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset = $wpdb->get_charset_collate();

        $sql1 = "CREATE TABLE {$this->ledger_table} (
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
            PRIMARY KEY (id),
            UNIQUE KEY user_pair (user_a,user_b),
            KEY connection_state (connection_state),
            KEY requested_by (requested_by),
            KEY block_a_to_b (block_a_to_b),
            KEY block_b_to_a (block_b_to_a),
            KEY updated_at (updated_at)
        ) {$charset};";

        $sql2 = "CREATE TABLE {$this->events_table} (
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
            KEY origin (origin)
        ) {$charset};";

        dbDelta($sql1);
        dbDelta($sql2);

        update_option('bzj_connections_sync_schema_version', self::VERSION, false);
    }

    /* ---------------------------------------------------------------------
     * REST API
     * ------------------------------------------------------------------ */

    public function register_rest_routes() {
        register_rest_route(self::REST_NAMESPACE, self::REST_ROUTE, array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'rest_command'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route(self::REST_NAMESPACE, self::REST_ROUTE, array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array($this, 'rest_status'),
            'permission_callback' => function () {
                return current_user_can('manage_options');
            },
        ));
    }

    public function rest_status(WP_REST_Request $request) {
        $a = absint($request->get_param('user_a'));
        $b = absint($request->get_param('user_b'));

        if ($a < 1 || $b < 1 || $a === $b) {
            return new WP_Error('bzj_invalid_pair', 'Two different valid WordPress user IDs are required.', array('status' => 400));
        }

        return rest_ensure_response(array(
            'success' => true,
            'version' => self::VERSION,
            'relationship' => $this->get_ledger($a, $b),
            'queue' => $this->get_pair_queue($a, $b),
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

        $origin = sanitize_key(isset($payload['origin']) ? $payload['origin'] : '');
        $operation = sanitize_key(isset($payload['operation']) ? $payload['operation'] : '');
        $actor_external = absint(isset($payload['actor_id']) ? $payload['actor_id'] : 0);
        $target_external = absint(isset($payload['target_id']) ? $payload['target_id'] : 0);
        $event_uuid = sanitize_text_field(isset($payload['event_id']) ? $payload['event_id'] : '');

        $allowed_origins = array('streams', 'socials');
        $allowed_operations = array(
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

        if (!in_array($origin, $allowed_origins, true) ||
            !in_array($operation, $allowed_operations, true)) {
            return new WP_Error('bzj_invalid_command', 'Invalid origin or operation.', array('status' => 400));
        }

        if ($actor_external < 1 || $target_external < 1 || $actor_external === $target_external) {
            return new WP_Error('bzj_invalid_users', 'actor_id and target_id must identify two different users.', array('status' => 400));
        }

        if (!$this->is_uuid($event_uuid)) {
            return new WP_Error('bzj_invalid_event', 'A valid UUID event_id is required.', array('status' => 400));
        }

        $actor_wp = $this->map_external_to_wp($origin, $actor_external);
        $target_wp = $this->map_external_to_wp($origin, $target_external);

        if (!$actor_wp || !$target_wp || $actor_wp === $target_wp) {
            return new WP_Error('bzj_mapping_missing', 'External IDs could not be mapped to WordPress users.', array('status' => 409));
        }

        if ($this->event_exists($event_uuid)) {
            return rest_ensure_response(array(
                'success' => true,
                'status' => 'already_processed',
                'event_id' => $event_uuid,
                'relationship' => $this->get_ledger($actor_wp, $target_wp),
            ));
        }

        /*
         * BuddyBoss is authoritative. External commands therefore execute
         * the native BuddyBoss operation first. Hooks then project the
         * resulting canonical state to both external platforms.
         */
        if (!$this->acquire_pair_lock($actor_wp, $target_wp)) {
            return new WP_Error('bzj_pair_busy', 'The relationship pair is busy. Retry the command.', array('status' => 409));
        }

        try {
            $this->insert_event($event_uuid, $origin, $operation, $actor_wp, $target_wp, $payload);

            $result = $this->apply_external_command($operation, $actor_wp, $target_wp, $event_uuid, $origin);
            if (is_wp_error($result)) {
                $this->mark_event_retry($event_uuid, $result->get_error_message());
                return new WP_Error(
                    'bzj_command_failed',
                    $result->get_error_message(),
                    array('status' => 409, 'event_id' => $event_uuid)
                );
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
            $this->log('REST command exception', array(
                'event_id' => $event_uuid,
                'origin' => $origin,
                'operation' => $operation,
                'error' => $e->getMessage(),
            ));
            return new WP_Error('bzj_internal_error', 'The synchronization command could not be completed.', array(
                'status' => 500,
                'event_id' => $event_uuid,
            ));
        } finally {
            $this->release_pair_lock($actor_wp, $target_wp);
        }
    }

    private function authenticate_external_request(WP_REST_Request $request, $raw) {
        $timestamp = $request->get_header('X-BZJ-Timestamp');
        $signature = $request->get_header('X-BZJ-Signature');

        if (!$timestamp || !$signature || !ctype_digit((string) $timestamp)) {
            return new WP_Error('bzj_auth_missing', 'Missing authentication headers.', array('status' => 401));
        }

        $timestamp = (int) $timestamp;

        if (abs(time() - $timestamp) > self::EXTERNAL_TIMESTAMP_WINDOW) {
            return new WP_Error('bzj_auth_expired', 'Request timestamp is outside the allowed window.', array('status' => 401));
        }

        $secret = function_exists('bzj_get_sso_secret') ? bzj_get_sso_secret() : '';
        if (!$secret) {
            $secret = getenv('BUZZ_SSO_SECRET');
        }
        if (!$secret && defined('BUZZ_SSO_SECRET')) {
            $secret = BUZZ_SSO_SECRET;
        }

        if (!$secret) {
            return new WP_Error('bzj_auth_unavailable', 'BZJ authentication secret is unavailable.', array('status' => 503));
        }

        $expected = hash_hmac('sha256', $timestamp . '.' . $raw, $secret);

        if (!hash_equals($expected, trim($signature))) {
            return new WP_Error('bzj_auth_invalid', 'Invalid request signature.', array('status' => 401));
        }

        return true;
    }

    /* ---------------------------------------------------------------------
     * BuddyBoss canonical state
     * ------------------------------------------------------------------ */

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

            return new WP_Error('bzj_unknown_operation', 'Unknown synchronization operation.');
        } finally {
            $this->pop_context();
        }
    }

    private function bb_connection_request_from_external($actor, $target, $event_uuid) {
        if (!function_exists('friends_add_friend')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss friendship functions are unavailable.');
        }

        if ($this->canonical_block_exists($actor, $target)) {
            return new WP_Error('bzj_blocked', 'The users are blocked; connection request rejected.');
        }

        $status = function_exists('friends_check_friendship_status')
            ? friends_check_friendship_status($actor, $target)
            : 'not_friends';

        if ($status === 'is_friend') {
            $this->save_ledger($actor, $target, array(
                'connection_state' => 'connected',
                'requested_by' => 0,
            ), $event_uuid);
            $this->project_canonical_pair($actor, $target);
            return true;
        }

        if ($status === 'pending' || $status === 'awaiting_response') {
            return true;
        }

        if (!friends_add_friend($actor, $target, false)) {
            return new WP_Error(
                'bzj_bb_request_rejected',
                'BuddyBoss rejected the connection request. Existing BuddyBoss connection restrictions remain authoritative.'
            );
        }

        /*
         * The BuddyBoss hook normally updates the ledger/projection. This
         * fallback ensures older builds without the expected hook still work.
         */
        $this->save_ledger($actor, $target, array(
            'connection_state' => 'requested',
            'requested_by' => $actor,
        ), $event_uuid);
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
            if (function_exists('friends_check_friendship_status') &&
                friends_check_friendship_status($actor, $target) === 'is_friend') {
                $this->save_ledger($actor, $target, array(
                    'connection_state' => 'connected',
                    'requested_by' => 0,
                ), $event_uuid);
                $this->project_social_connection($actor, $target, 'connected');
                return true;
            }
            return new WP_Error('bzj_no_pending_connection', 'No BuddyBoss connection request exists for this pair.');
        }

        if (function_exists('friends_check_friendship_status') &&
            friends_check_friendship_status($actor, $target) === 'is_friend') {
            $this->save_ledger($actor, $target, array(
                'connection_state' => 'connected',
                'requested_by' => 0,
            ), $event_uuid);
            $this->project_social_connection($actor, $target, 'connected');
            return true;
        }

        if (!friends_accept_friendship($friendship_id)) {
            return new WP_Error('bzj_bb_accept_failed', 'BuddyBoss could not accept the connection request.');
        }

        $this->save_ledger($actor, $target, array(
            'connection_state' => 'connected',
            'requested_by' => 0,
        ), $event_uuid);
        $this->project_social_connection($actor, $target, 'connected');

        return true;
    }

    private function bb_connection_reject_from_external($actor, $target, $event_uuid) {
        if (!function_exists('friends_get_friendship_id') || !function_exists('friends_reject_friendship')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss friendship functions are unavailable.');
        }

        $id = friends_get_friendship_id($actor, $target);
        if (!$id) {
            $id = friends_get_friendship_id($target, $actor);
        }

        if ($id && !friends_reject_friendship($id)) {
            return new WP_Error('bzj_bb_reject_failed', 'BuddyBoss could not reject the connection request.');
        }

        $this->save_ledger($actor, $target, array(
            'connection_state' => 'none',
            'requested_by' => 0,
        ), $event_uuid);
        $this->project_social_connection($actor, $target, 'none');

        return true;
    }

    private function bb_connection_withdraw_from_external($actor, $target, $event_uuid) {
        if (!function_exists('friends_withdraw_friendship')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss withdrawal function is unavailable.');
        }

        if (!friends_withdraw_friendship($actor, $target)) {
            $status = function_exists('friends_check_friendship_status')
                ? friends_check_friendship_status($actor, $target)
                : 'not_friends';

            if ($status !== 'not_friends') {
                return new WP_Error('bzj_bb_withdraw_failed', 'BuddyBoss could not withdraw the connection request.');
            }
        }

        $this->save_ledger($actor, $target, array(
            'connection_state' => 'none',
            'requested_by' => 0,
        ), $event_uuid);
        $this->project_social_connection($actor, $target, 'none');

        return true;
    }

    private function bb_connection_remove_from_external($actor, $target, $event_uuid) {
        if (!function_exists('friends_remove_friend')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss friendship functions are unavailable.');
        }

        if (function_exists('friends_check_friendship_status') &&
            friends_check_friendship_status($actor, $target) === 'is_friend') {
            if (!friends_remove_friend($actor, $target) && !friends_remove_friend($target, $actor)) {
                return new WP_Error('bzj_bb_remove_failed', 'BuddyBoss could not remove the connection.');
            }
        }

        $this->save_ledger($actor, $target, array(
            'connection_state' => 'none',
            'requested_by' => 0,
        ), $event_uuid);
        $this->project_social_connection($actor, $target, 'none');

        return true;
    }

    private function bb_follow_from_external($actor, $target, $event_uuid) {
        if (!function_exists('bp_start_following')) {
            return new WP_Error('bzj_bb_follow_unavailable', 'BuddyBoss follow function is unavailable.');
        }

        if ($this->canonical_block_exists($actor, $target)) {
            return new WP_Error('bzj_blocked', 'The users are blocked; follow rejected.');
        }

        $result = bp_start_following(array(
            'leader_id' => $target,
            'follower_id' => $actor,
        ));

        if (is_wp_error($result)) {
            return $result;
        }

        if ($result === false && function_exists('bp_is_following') &&
            !bp_is_following(array('leader_id' => $target, 'follower_id' => $actor))) {
            return new WP_Error('bzj_bb_follow_failed', 'BuddyBoss rejected the follow operation.');
        }

        $this->set_follow_state($actor, $target, true, $event_uuid);
        $this->project_external_follow($actor, $target, true, 'streams');
        $this->project_external_follow($actor, $target, true, 'socials');

        return true;
    }

    private function bb_unfollow_from_external($actor, $target, $event_uuid) {
        if (!function_exists('bp_stop_following')) {
            return new WP_Error('bzj_bb_follow_unavailable', 'BuddyBoss unfollow function is unavailable.');
        }

        $result = bp_stop_following(array(
            'leader_id' => $target,
            'follower_id' => $actor,
        ));

        if (is_wp_error($result)) {
            return $result;
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

        if ($this->canonical_block_exists($actor, $target)) {
            return true;
        }

        $moderation = new BP_Moderation(
            $target,
            BP_Moderation_Members::$moderation_type,
            $actor
        );
        $moderation->user_report = 0;
        $moderation->content = '';

        if (!$moderation->save()) {
            return new WP_Error('bzj_bb_block_failed', 'BuddyBoss could not create the member block.');
        }

        /*
         * Block dominates every relationship dimension. Existing friendship
         * and both directional follows are removed from BuddyBoss itself.
         */
        $this->enforce_block_on_buddyboss($actor, $target, $event_uuid);

        $this->save_ledger($actor, $target, array(
            $this->directional_block_field($actor, $target) => 1,
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

        $moderation = new BP_Moderation(
            $target,
            BP_Moderation_Members::$moderation_type,
            $actor
        );

        if (!empty($moderation->id) && empty($moderation->user_report)) {
            if (!$moderation->delete(false)) {
                return new WP_Error('bzj_bb_unblock_failed', 'BuddyBoss could not remove the member block.');
            }
        }

        $this->save_ledger($actor, $target, array(
            $this->directional_block_field($actor, $target) => 0,
        ), $event_uuid);

        /*
         * Deliberately do not recreate connection/follow relationships.
         */
        $this->project_external_block($actor, $target, false, 'streams');
        $this->project_external_block($actor, $target, false, 'socials');

        return true;
    }

    private function enforce_block_on_buddyboss($actor, $target, $event_uuid) {
        if (function_exists('friends_check_friendship_status') &&
            function_exists('friends_remove_friend') &&
            friends_check_friendship_status($actor, $target) === 'is_friend') {
            $this->push_context(array(
                'event_uuid' => $event_uuid,
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

        if (function_exists('bp_stop_following')) {
            foreach (array(array($actor, $target), array($target, $actor)) as $pair) {
                $this->push_context(array(
                    'event_uuid' => $event_uuid,
                    'origin' => 'wordpress',
                    'operation' => 'unfollow',
                    'actor' => $pair[0],
                    'target' => $pair[1],
                ));
                try {
                    bp_stop_following(array(
                        'leader_id' => $pair[1],
                        'follower_id' => $pair[0],
                    ));
                } catch (Throwable $e) {
                    $this->log('BuddyBoss follow removal during block failed', array(
                        'actor' => $pair[0],
                        'target' => $pair[1],
                        'error' => $e->getMessage(),
                    ));
                } finally {
                    $this->pop_context();
                }
            }
        }
    }

    /* ---------------------------------------------------------------------
     * BuddyBoss event handlers
     * ------------------------------------------------------------------ */

    public function bb_connection_requested($id, $initiator, $friend, $friendship = null) {
        $this->handle_bb_connection_event('connection_request', absint($initiator), absint($friend), absint($id));
    }

    public function bb_connection_accepted($id, $initiator, $friend, $friendship = null) {
        $this->handle_bb_connection_event('connection_accept', absint($initiator), absint($friend), absint($id));
    }

    public function bb_connection_rejected($id, $friendship) {
        if (!is_object($friendship)) {
            return;
        }
        $this->handle_bb_connection_event(
            'connection_reject',
            absint($friendship->initiator_user_id),
            absint($friendship->friend_user_id),
            absint($id)
        );
    }

    public function bb_connection_withdrawn($id, $friendship) {
        if (!is_object($friendship)) {
            return;
        }
        $this->handle_bb_connection_event(
            'connection_withdraw',
            absint($friendship->initiator_user_id),
            absint($friendship->friend_user_id),
            absint($id)
        );
    }

    public function bb_connection_deleted($id, $initiator, $friend) {
        $a = absint($initiator);
        $b = absint($friend);

        if ($this->is_contextual_duplicate('connection_remove', $a, $b) ||
            $this->is_contextual_duplicate('connection_remove', $b, $a)) {
            return;
        }

        $ledger = $this->get_ledger($a, $b);
        $operation = ($ledger['connection_state'] === 'requested')
            ? 'connection_withdraw'
            : 'connection_remove';

        $this->handle_bb_connection_event($operation, $a, $b, absint($id));
    }

    private function handle_bb_connection_event($operation, $actor, $target, $native_id = 0) {
        if ($actor < 1 || $target < 1 || $actor === $target ||
            $this->is_contextual_duplicate($operation, $actor, $target)) {
            return;
        }

        $uuid = wp_generate_uuid4();

        if (!$this->acquire_pair_lock($actor, $target)) {
            $this->insert_event($uuid, 'wordpress', $operation, $actor, $target, array('native_id' => $native_id));
            $this->mark_event_retry($uuid, 'Pair lock busy.');
            return;
        }

        try {
            $this->insert_event($uuid, 'wordpress', $operation, $actor, $target, array('native_id' => $native_id));

            switch ($operation) {
                case 'connection_request':
                    $this->save_ledger($actor, $target, array(
                        'connection_state' => 'requested',
                        'requested_by' => $actor,
                    ), $uuid);
                    $this->project_social_connection($actor, $target, 'requested');
                    break;

                case 'connection_accept':
                    $this->save_ledger($actor, $target, array(
                        'connection_state' => 'connected',
                        'requested_by' => 0,
                    ), $uuid);
                    $this->project_social_connection($actor, $target, 'connected');
                    break;

                case 'connection_reject':
                case 'connection_withdraw':
                case 'connection_remove':
                    $this->save_ledger($actor, $target, array(
                        'connection_state' => 'none',
                        'requested_by' => 0,
                    ), $uuid);
                    $this->project_social_connection($actor, $target, 'none');
                    break;
            }

            $this->mark_event_processed($uuid);
        } catch (Throwable $e) {
            $this->mark_event_retry($uuid, $e->getMessage());
            $this->log('BuddyBoss connection event failed', array(
                'operation' => $operation,
                'actor' => $actor,
                'target' => $target,
                'error' => $e->getMessage(),
            ));
        } finally {
            $this->release_pair_lock($actor, $target);
        }
    }

    public function bb_follow_started($leader_id, $follower_id) {
        $this->handle_bb_follow_event('follow', absint($follower_id), absint($leader_id));
    }

    public function bb_follow_stopped($leader_id, $follower_id) {
        $this->handle_bb_follow_event('unfollow', absint($follower_id), absint($leader_id));
    }

    private function handle_bb_follow_event($operation, $actor, $target) {
        if ($actor < 1 || $target < 1 || $actor === $target ||
            $this->is_contextual_duplicate($operation, $actor, $target)) {
            return;
        }

        $uuid = wp_generate_uuid4();

        if (!$this->acquire_pair_lock($actor, $target)) {
            $this->insert_event($uuid, 'wordpress', $operation, $actor, $target);
            $this->mark_event_retry($uuid, 'Pair lock busy.');
            return;
        }

        try {
            $this->insert_event($uuid, 'wordpress', $operation, $actor, $target);

            $following = ($operation === 'follow');
            $this->set_follow_state($actor, $target, $following, $uuid);

            if ($following && $this->canonical_block_exists($actor, $target)) {
                $following = false;
                $this->set_follow_state($actor, $target, false, $uuid);
            }

            $this->project_external_follow($actor, $target, $following, 'streams');
            $this->project_external_follow($actor, $target, $following, 'socials');

            $this->mark_event_processed($uuid);
        } catch (Throwable $e) {
            $this->mark_event_retry($uuid, $e->getMessage());
            $this->log('BuddyBoss follow event failed', array(
                'operation' => $operation,
                'actor' => $actor,
                'target' => $target,
                'error' => $e->getMessage(),
            ));
        } finally {
            $this->release_pair_lock($actor, $target);
        }
    }

    public function bb_moderation_saved($moderation) {
        if (!is_object($moderation) ||
            !isset($moderation->item_type) ||
            $moderation->item_type !== 'user' ||
            !empty($moderation->user_report)) {
            return;
        }

        $this->handle_bb_block_event(
            'block',
            absint($moderation->user_id),
            absint($moderation->item_id),
            absint($moderation->id)
        );
    }

    public function bb_moderation_deleted($moderation) {
        if (!is_object($moderation) ||
            !isset($moderation->item_type) ||
            $moderation->item_type !== 'user' ||
            !empty($moderation->user_report)) {
            return;
        }

        $this->handle_bb_block_event(
            'unblock',
            absint($moderation->user_id),
            absint($moderation->item_id),
            absint($moderation->id)
        );
    }

    private function handle_bb_block_event($operation, $actor, $target, $native_id = 0) {
        if ($actor < 1 || $target < 1 || $actor === $target ||
            $this->is_contextual_duplicate($operation, $actor, $target)) {
            return;
        }

        $uuid = wp_generate_uuid4();

        if (!$this->acquire_pair_lock($actor, $target)) {
            $this->insert_event($uuid, 'wordpress', $operation, $actor, $target, array('native_id' => $native_id));
            $this->mark_event_retry($uuid, 'Pair lock busy.');
            return;
        }

        try {
            $this->insert_event($uuid, 'wordpress', $operation, $actor, $target, array('native_id' => $native_id));

            if ($operation === 'block') {
                $this->enforce_block_on_buddyboss($actor, $target, $uuid);

                $this->save_ledger($actor, $target, array(
                    $this->directional_block_field($actor, $target) => 1,
                    'connection_state' => 'none',
                    'requested_by' => 0,
                    'follow_a_to_b' => 0,
                    'follow_b_to_a' => 0,
                ), $uuid);

                $this->project_external_block($actor, $target, true, 'streams');
                $this->project_external_block($actor, $target, true, 'socials');
            } else {
                $this->save_ledger($actor, $target, array(
                    $this->directional_block_field($actor, $target) => 0,
                ), $uuid);

                /*
                 * Unblocking never restores a connection or follow.
                 */
                $this->project_external_block($actor, $target, false, 'streams');
                $this->project_external_block($actor, $target, false, 'socials');
            }

            $this->mark_event_processed($uuid);
        } catch (Throwable $e) {
            $this->mark_event_retry($uuid, $e->getMessage());
            $this->log('BuddyBoss block event failed', array(
                'operation' => $operation,
                'actor' => $actor,
                'target' => $target,
                'error' => $e->getMessage(),
            ));
        } finally {
            $this->release_pair_lock($actor, $target);
        }
    }

    /* ---------------------------------------------------------------------
     * Ledger
     * ------------------------------------------------------------------ */

    private function pair($a, $b) {
        $a = absint($a);
        $b = absint($b);
        return ($a < $b) ? array($a, $b) : array($b, $a);
    }

    private function get_ledger($a, $b) {
        global $wpdb;
        $pair = $this->pair($a, $b);

        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->ledger_table} WHERE user_a = %d AND user_b = %d LIMIT 1",
            $pair[0],
            $pair[1]
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

        foreach (array(
            'user_a',
            'user_b',
            'requested_by',
            'follow_a_to_b',
            'follow_b_to_a',
            'block_a_to_b',
            'block_b_to_a',
            'version'
        ) as $key) {
            $row[$key] = isset($row[$key]) ? (int) $row[$key] : 0;
        }

        return $row;
    }

    private function save_ledger($a, $b, $changes, $event_uuid = '') {
        global $wpdb;

        $pair = $this->pair($a, $b);
        $existing = $this->get_ledger($pair[0], $pair[1]);
        $now = gmdate('Y-m-d H:i:s');

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
            'requested_by' => (int) $state['requested_by'],
            'follow_a_to_b' => (int) $state['follow_a_to_b'],
            'follow_b_to_a' => (int) $state['follow_b_to_a'],
            'block_a_to_b' => (int) $state['block_a_to_b'],
            'block_b_to_a' => (int) $state['block_b_to_a'],
            'version' => ((int) $existing['version'] + 1),
            'last_event_uuid' => $event_uuid,
            'updated_at' => $now,
        );

        if ((int) $existing['version'] === 0) {
            $data['created_at'] = $now;
            $wpdb->insert($this->ledger_table, $data);
        } else {
            $wpdb->update(
                $this->ledger_table,
                $data,
                array('user_a' => $pair[0], 'user_b' => $pair[1])
            );
        }

        return $this->get_ledger($pair[0], $pair[1]);
    }

    private function set_follow_state($actor, $target, $active, $event_uuid = '') {
        $pair = $this->pair($actor, $target);
        $field = ((int) $actor === $pair[0]) ? 'follow_a_to_b' : 'follow_b_to_a';

        return $this->save_ledger($actor, $target, array(
            $field => $active ? 1 : 0,
        ), $event_uuid);
    }

    private function directional_block_field($actor, $target) {
        $pair = $this->pair($actor, $target);
        return ((int) $actor === $pair[0]) ? 'block_a_to_b' : 'block_b_to_a';
    }

    private function canonical_block_exists($a, $b) {
        $row = $this->get_ledger($a, $b);
        return !empty($row['block_a_to_b']) || !empty($row['block_b_to_a']);
    }

    private function project_canonical_pair($a, $b) {
        $ledger = $this->get_ledger($a, $b);

        if ($ledger['block_a_to_b']) {
            $this->project_external_block($a, $b, true, 'streams');
            $this->project_external_block($a, $b, true, 'socials');
        }
        if ($ledger['block_b_to_a']) {
            $this->project_external_block($b, $a, true, 'streams');
            $this->project_external_block($b, $a, true, 'socials');
        }

        $this->project_social_connection($a, $b, $ledger['connection_state']);

        $this->project_external_follow($a, $b, !empty($ledger['follow_a_to_b']), 'streams');
        $this->project_external_follow($a, $b, !empty($ledger['follow_a_to_b']), 'socials');
        $this->project_external_follow($b, $a, !empty($ledger['follow_b_to_a']), 'streams');
        $this->project_external_follow($b, $a, !empty($ledger['follow_b_to_a']), 'socials');
    }

    /* ---------------------------------------------------------------------
     * External DB projection
     * ------------------------------------------------------------------ */

    private function load_db_helpers() {
        $path = ABSPATH . 'shared/db_helpers.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    private function streams_db() {
        $this->load_db_helpers();
        if (function_exists('get_wowonder_db')) {
            $db = get_wowonder_db();
            if ($db instanceof mysqli && !$db->connect_errno) {
                return $db;
            }
        }
        return false;
    }

    private function socials_db() {
        $this->load_db_helpers();
        if (function_exists('get_qd_db_conn')) {
            $db = get_qd_db_conn();
            if ($db instanceof mysqli && !$db->connect_errno) {
                return $db;
            }
        }
        return false;
    }

    private function map_wp_to_external($wp_id, $platform) {
        $meta_key = ($platform === 'streams') ? 'wo_user_id' : 'qd_user_id';
        $value = get_user_meta($wp_id, $meta_key, true);
        return absint($value);
    }

    private function map_external_to_wp($platform, $external_id) {
        global $wpdb;

        $meta_key = ($platform === 'streams') ? 'wo_user_id' : 'qd_user_id';

        $value = $wpdb->get_var($wpdb->prepare(
            "SELECT user_id FROM {$wpdb->usermeta}
             WHERE meta_key = %s AND meta_value = %s
             ORDER BY umeta_id ASC LIMIT 1",
            $meta_key,
            (string) $external_id
        ));

        return absint($value);
    }

    private function table_exists($db, $table) {
        $table = $db->real_escape_string($table);
        $result = $db->query("SHOW TABLES LIKE '{$table}'");
        return ($result instanceof mysqli_result && $result->num_rows > 0);
    }

    private function first_existing_table($db, $candidates) {
        foreach ($candidates as $candidate) {
            if ($this->table_exists($db, $candidate)) {
                return $candidate;
            }
        }
        return false;
    }

    private function project_social_connection($a, $b, $state) {
        $qa = $this->map_wp_to_external($a, 'socials');
        $qb = $this->map_wp_to_external($b, 'socials');

        if (!$qa || !$qb) {
            $this->queue_projection('socials', 'connection_' . $state, $a, $b, array());
            return false;
        }

        $db = $this->socials_db();

        if (!$db) {
            $this->queue_projection('socials', 'connection_' . $state, $a, $b, array());
            return false;
        }

        try {
            if ($state === 'requested') {
                $this->qd_set_pending_friend($db, $qb, $qa);
            } elseif ($state === 'connected') {
                $this->qd_set_active_friend($db, $qb, $qa);
            } else {
                $this->qd_remove_relationship($db, $qa, $qb);
            }
            return true;
        } catch (Throwable $e) {
            $this->queue_projection('socials', 'connection_' . $state, $a, $b, array('error' => $e->getMessage()));
            $this->log('QuickDate connection projection failed', array(
                'actor' => $a,
                'target' => $b,
                'state' => $state,
                'error' => $e->getMessage(),
            ));
            return false;
        }
    }

    private function project_external_follow($actor, $target, $active, $platform) {
        $ea = $this->map_wp_to_external($actor, $platform);
        $et = $this->map_wp_to_external($target, $platform);

        if (!$ea || !$et) {
            $this->queue_projection($platform, $active ? 'follow' : 'unfollow', $actor, $target, array());
            return false;
        }

        $db = ($platform === 'streams') ? $this->streams_db() : $this->socials_db();

        if (!$db) {
            $this->queue_projection($platform, $active ? 'follow' : 'unfollow', $actor, $target, array());
            return false;
        }

        try {
            if ($active) {
                $this->external_set_follow($db, $et, $ea, $platform);
            } else {
                $this->external_delete_follow($db, $et, $ea);
            }
            return true;
        } catch (Throwable $e) {
            $this->queue_projection($platform, $active ? 'follow' : 'unfollow', $actor, $target, array('error' => $e->getMessage()));
            $this->log('External follow projection failed', array(
                'platform' => $platform,
                'actor' => $actor,
                'target' => $target,
                'active' => $active,
                'error' => $e->getMessage(),
            ));
            return false;
        }
    }

    private function project_external_block($actor, $target, $active, $platform) {
        $ea = $this->map_wp_to_external($actor, $platform);
        $et = $this->map_wp_to_external($target, $platform);

        if (!$ea || !$et) {
            $this->queue_projection($platform, $active ? 'block' : 'unblock', $actor, $target, array());
            return false;
        }

        $db = ($platform === 'streams') ? $this->streams_db() : $this->socials_db();

        if (!$db) {
            $this->queue_projection($platform, $active ? 'block' : 'unblock', $actor, $target, array());
            return false;
        }

        try {
            if ($active) {
                $this->external_set_block($db, $ea, $et, $platform);
                /*
                 * Block dominates external relationships in both directions.
                 */
                $this->external_delete_follow($db, $et, $ea);
                $this->external_delete_follow($db, $ea, $et);
            } else {
                $this->external_delete_block($db, $ea, $et, $platform);
            }

            return true;
        } catch (Throwable $e) {
            $this->queue_projection($platform, $active ? 'block' : 'unblock', $actor, $target, array('error' => $e->getMessage()));
            $this->log('External block projection failed', array(
                'platform' => $platform,
                'actor' => $actor,
                'target' => $target,
                'active' => $active,
                'error' => $e->getMessage(),
            ));
            return false;
        }
    }

    private function external_follow_table($db) {
        $table = $this->first_existing_table($db, array('Wo_Followers', 'followers'));
        if (!$table) {
            throw new RuntimeException('External followers table not found.');
        }
        return $table;
    }

    private function external_block_table($db, $platform) {
        if ($platform === 'streams') {
            /*
             * WoWonder's current source uses T_BLOCKS. Standard Buzzjuice
             * deployment is Wo_Blocks; the second candidate supports older
             * installs without hard-coding a wrong table.
             */
            $table = $this->first_existing_table($db, array('Wo_Blocks', 'Wo_BlockedUsers'));
        } else {
            $table = $this->first_existing_table($db, array('blocks'));
        }

        if (!$table) {
            throw new RuntimeException('External blocks table not found.');
        }

        return $table;
    }

    private function external_set_follow($db, $following_id, $follower_id, $platform) {
        $table = $this->external_follow_table($db);

        $stmt = $db->prepare(
            "SELECT id, active FROM `{$table}` WHERE following_id = ? AND follower_id = ? LIMIT 1"
        );
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }

        $stmt->bind_param('ii', $following_id, $follower_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if ($row) {
            $stmt = $db->prepare("UPDATE `{$table}` SET active = 1 WHERE id = ?");
            if (!$stmt) {
                throw new RuntimeException($db->error);
            }
            $stmt->bind_param('i', $row['id']);
            $ok = $stmt->execute();
            $err = $stmt->error;
            $stmt->close();

            if (!$ok) {
                throw new RuntimeException($err ?: 'Follow update failed.');
            }
            return true;
        }

        $created = time();

        if ($platform === 'streams') {
            $stmt = $db->prepare(
                "INSERT INTO `{$table}` (following_id, follower_id, active, created_at) VALUES (?, ?, 1, ?)"
            );
        } else {
            $stmt = $db->prepare(
                "INSERT INTO `{$table}` (following_id, follower_id, active, created_at) VALUES (?, ?, 1, ?)"
            );
        }

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

    private function external_delete_follow($db, $following_id, $follower_id) {
        $table = $this->external_follow_table($db);

        $stmt = $db->prepare(
            "DELETE FROM `{$table}` WHERE following_id = ? AND follower_id = ?"
        );

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

        return true;
    }

    private function qd_set_pending_friend($db, $actor, $target) {
        /*
         * QuickDate friend requests are followers rows with active=0.
         * The current add_friend() implementation creates:
         * following_id = requester, follower_id = recipient.
         */
        $table = $this->external_follow_table($db);

        $this->qd_delete_direction($db, $target, $actor, $table, 1);

        $stmt = $db->prepare(
            "SELECT id FROM `{$table}` WHERE following_id = ? AND follower_id = ? LIMIT 1"
        );
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }

        $stmt->bind_param('ii', $actor, $target);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if ($row) {
            $stmt = $db->prepare("UPDATE `{$table}` SET active = 0 WHERE id = ?");
            $stmt->bind_param('i', $row['id']);
            $ok = $stmt->execute();
            $err = $stmt->error;
            $stmt->close();

            if (!$ok) {
                throw new RuntimeException($err ?: 'QuickDate pending request update failed.');
            }
            return true;
        }

        $now = time();
        $stmt = $db->prepare(
            "INSERT INTO `{$table}` (following_id, follower_id, active, created_at) VALUES (?, ?, 0, ?)"
        );
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }

        $stmt->bind_param('iii', $actor, $target, $now);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException($err ?: 'QuickDate pending request insert failed.');
        }

        return true;
    }

    private function qd_set_active_friend($db, $actor, $target) {
        $table = $this->external_follow_table($db);

        $stmt = $db->prepare(
            "SELECT id FROM `{$table}` WHERE following_id = ? AND follower_id = ? LIMIT 1"
        );
        if (!$stmt) {
            throw new RuntimeException($db->error);
        }

        $stmt->bind_param('ii', $actor, $target);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if (!$row) {
            /*
             * The request is represented in the same direction as QuickDate's
             * add_friend(): requester -> recipient.
             */
            $now = time();
            $stmt = $db->prepare(
                "INSERT INTO `{$table}` (following_id, follower_id, active, created_at) VALUES (?, ?, 1, ?)"
            );
            if (!$stmt) {
                throw new RuntimeException($db->error);
            }
            $stmt->bind_param('iii', $actor, $target, $now);
            $ok = $stmt->execute();
            $err = $stmt->error;
            $stmt->close();

            if (!$ok) {
                throw new RuntimeException($err ?: 'QuickDate friend insert failed.');
            }
            return true;
        }

        $stmt = $db->prepare("UPDATE `{$table}` SET active = 1 WHERE id = ?");
        $stmt->bind_param('i', $row['id']);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException($err ?: 'QuickDate friend activation failed.');
        }

        return true;
    }

    private function qd_remove_relationship($db, $a, $b) {
        $table = $this->external_follow_table($db);

        $stmt = $db->prepare(
            "DELETE FROM `{$table}`
             WHERE (following_id = ? AND follower_id = ?)
                OR (following_id = ? AND follower_id = ?)"
        );

        if (!$stmt) {
            throw new RuntimeException($db->error);
        }

        $stmt->bind_param('iiii', $a, $b, $b, $a);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException($err ?: 'QuickDate relationship removal failed.');
        }

        return true;
    }

    private function qd_delete_direction($db, $following, $follower, $table, $active = null) {
        if ($active === null) {
            $stmt = $db->prepare(
                "DELETE FROM `{$table}` WHERE following_id = ? AND follower_id = ?"
            );
        } else {
            $stmt = $db->prepare(
                "DELETE FROM `{$table}` WHERE following_id = ? AND follower_id = ? AND active = ?"
            );
        }

        if (!$stmt) {
            throw new RuntimeException($db->error);
        }

        if ($active === null) {
            $stmt->bind_param('ii', $following, $follower);
        } else {
            $stmt->bind_param('iii', $following, $follower, $active);
        }

        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException($err ?: 'QuickDate relationship deletion failed.');
        }
    }

    private function external_set_block($db, $actor, $target, $platform) {
        $table = $this->external_block_table($db);

        if ($platform === 'streams') {
            $stmt = $db->prepare(
                "SELECT id FROM `{$table}` WHERE blocker = ? AND blocked = ? LIMIT 1"
            );
        } else {
            $stmt = $db->prepare(
                "SELECT id FROM `{$table}` WHERE user_id = ? AND block_userid = ? LIMIT 1"
            );
        }

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

        if ($platform === 'streams') {
            $stmt = $db->prepare(
                "INSERT INTO `{$table}` (blocker, blocked) VALUES (?, ?)"
            );
            if (!$stmt) {
                throw new RuntimeException($db->error);
            }
            $stmt->bind_param('ii', $actor, $target);
        } else {
            $now = date('Y-m-d H:i:s');
            $stmt = $db->prepare(
                "INSERT INTO `{$table}` (user_id, block_userid, created_at) VALUES (?, ?, ?)"
            );
            if (!$stmt) {
                throw new RuntimeException($db->error);
            }
            $stmt->bind_param('iis', $actor, $target, $now);
        }

        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException($err ?: 'External block insert failed.');
        }

        return true;
    }

    private function external_delete_block($db, $actor, $target, $platform) {
        $table = $this->external_block_table($db);

        if ($platform === 'streams') {
            $stmt = $db->prepare(
                "DELETE FROM `{$table}` WHERE blocker = ? AND blocked = ?"
            );
        } else {
            $stmt = $db->prepare(
                "DELETE FROM `{$table}` WHERE user_id = ? AND block_userid = ?"
            );
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

    /* ---------------------------------------------------------------------
     * Durable event queue
     * ------------------------------------------------------------------ */

    private function event_exists($uuid) {
        global $wpdb;
        return (bool) $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$this->events_table} WHERE event_uuid = %s LIMIT 1",
            $uuid
        ));
    }

    private function insert_event($uuid, $origin, $operation, $actor, $target, $payload = array()) {
        global $wpdb;

        if ($this->event_exists($uuid)) {
            return true;
        }

        $pair = $this->pair($actor, $target);
        $now = gmdate('Y-m-d H:i:s');

        $ok = $wpdb->insert(
            $this->events_table,
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
            )
        );

        if ($ok === false && !$this->event_exists($uuid)) {
            throw new RuntimeException('Could not create synchronization event: ' . $wpdb->last_error);
        }

        return true;
    }

    private function mark_event_processed($uuid) {
        global $wpdb;

        $now = gmdate('Y-m-d H:i:s');

        $wpdb->update(
            $this->events_table,
            array(
                'status' => 'processed',
                'processed_at' => $now,
                'updated_at' => $now,
                'last_error' => null,
            ),
            array('event_uuid' => $uuid)
        );
    }

    private function mark_event_retry($uuid, $error) {
        global $wpdb;

        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT attempts FROM {$this->events_table} WHERE event_uuid = %s LIMIT 1",
            $uuid
        ));

        $attempts = $row ? ((int) $row->attempts + 1) : 1;
        $delay = min(3600, max(60, (int) pow(2, min($attempts, 10)) * 30));
        $status = ($attempts >= self::MAX_ATTEMPTS) ? 'failed' : 'retry';
        $next = gmdate('Y-m-d H:i:s', time() + $delay);

        $wpdb->update(
            $this->events_table,
            array(
                'status' => $status,
                'attempts' => $attempts,
                'next_attempt_at' => $next,
                'last_error' => function_exists('mb_substr')
                    ? mb_substr((string) $error, 0, 2000)
                    : substr((string) $error, 0, 2000),
                'updated_at' => gmdate('Y-m-d H:i:s'),
            ),
            array('event_uuid' => $uuid)
        );
    }

    private function queue_projection($platform, $operation, $actor, $target, $payload = array()) {
        $uuid = wp_generate_uuid4();

        $this->insert_event(
            $uuid,
            'wordpress',
            'projection_' . $operation,
            $actor,
            $target,
            array_merge(array('platform' => $platform), $payload)
        );

        $this->mark_event_retry($uuid, 'Projection deferred because the external platform is temporarily unavailable or unmapped.');

        return $uuid;
    }

    public function process_queue() {
        global $wpdb;

        $now = gmdate('Y-m-d H:i:s');

        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->events_table}
             WHERE status IN ('pending','retry')
             AND next_attempt_at <= %s
             ORDER BY id ASC
             LIMIT 25",
            $now
        ));

        foreach ((array) $rows as $row) {
            $this->process_event_row($row);
        }
    }

    private function process_event_row($row) {
        $payload = json_decode((string) $row->payload, true);

        if (!is_array($payload)) {
            $this->mark_event_retry($row->event_uuid, 'Invalid stored event payload.');
            return;
        }

        $operation = (string) $row->operation;

        if (strpos($operation, 'projection_') !== 0) {
            /*
             * The canonical BuddyBoss action has already happened. Never
             * blindly replay it. Re-read canonical BuddyBoss state and repair
             * external projections.
             */
            $this->reconcile_pair((int) $row->pair_a, (int) $row->pair_b);
            $this->mark_event_processed($row->event_uuid);
            return;
        }

        $platform = isset($payload['platform']) ? sanitize_key($payload['platform']) : '';
        $actor = (int) $row->actor_wp_id;
        $target = (int) $row->target_wp_id;
        $op = substr($operation, strlen('projection_'));

        try {
            if ($platform === 'socials' && strpos($op, 'connection_') === 0) {
                $state = substr($op, strlen('connection_'));
                if (!$this->project_social_connection($actor, $target, $state)) {
                    throw new RuntimeException('QuickDate connection projection unavailable.');
                }
            } elseif (in_array($platform, array('streams','socials'), true) &&
                in_array($op, array('follow','unfollow'), true)) {
                if (!$this->project_external_follow($actor, $target, $op === 'follow', $platform)) {
                    throw new RuntimeException('Follow projection unavailable.');
                }
            } elseif (in_array($platform, array('streams','socials'), true) &&
                in_array($op, array('block','unblock'), true)) {
                if (!$this->project_external_block($actor, $target, $op === 'block', $platform)) {
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

    /* ---------------------------------------------------------------------
     * Reconciliation
     * ------------------------------------------------------------------ */

    public function reconcile_recent() {
        global $wpdb;

        $rows = $wpdb->get_results(
            "SELECT user_a,user_b
             FROM {$this->ledger_table}
             WHERE updated_at >= UTC_TIMESTAMP() - INTERVAL 7 DAY
             ORDER BY updated_at DESC
             LIMIT 100"
        );

        foreach ((array) $rows as $row) {
            $this->reconcile_pair((int) $row->user_a, (int) $row->user_b);
        }
    }

    private function reconcile_pair($a, $b) {
        if (!$this->acquire_pair_lock($a, $b)) {
            return false;
        }

        try {
            /*
             * IMPORTANT: BuddyBoss is the authority. Refresh the ledger from
             * BuddyBoss before projecting. This prevents a stale ledger from
             * recreating a relationship that no longer exists in BuddyBoss.
             */
            $this->refresh_ledger_from_buddyboss($a, $b);
            $this->project_canonical_pair($a, $b);
            return true;
        } finally {
            $this->release_pair_lock($a, $b);
        }
    }

    private function refresh_ledger_from_buddyboss($a, $b) {
        $changes = array();

        if (function_exists('friends_check_friendship_status')) {
            $status = friends_check_friendship_status($a, $b);

            if ($status === 'is_friend') {
                $changes['connection_state'] = 'connected';
                $changes['requested_by'] = 0;
            } elseif ($status === 'pending') {
                $changes['connection_state'] = 'requested';
                $changes['requested_by'] = $a;
            } elseif ($status === 'awaiting_response') {
                $changes['connection_state'] = 'requested';
                $changes['requested_by'] = $b;
            } else {
                $changes['connection_state'] = 'none';
                $changes['requested_by'] = 0;
            }
        }

        if (function_exists('bp_is_following')) {
            $changes['follow_a_to_b'] = bp_is_following(array(
                'leader_id' => $b,
                'follower_id' => $a,
            )) ? 1 : 0;

            $changes['follow_b_to_a'] = bp_is_following(array(
                'leader_id' => $a,
                'follower_id' => $b,
            )) ? 1 : 0;
        }

        /*
         * Member block state is checked directly against BuddyBoss moderation
         * when the helper exists. The ledger is never treated as the authority.
         */
        if (function_exists('bp_moderation_is_user_blocked')) {
            $changes['block_a_to_b'] = bp_moderation_is_user_blocked($b, $a) ? 1 : 0;
            $changes['block_b_to_a'] = bp_moderation_is_user_blocked($a, $b) ? 1 : 0;
        }

        $this->save_ledger($a, $b, $changes);
    }

    /* ---------------------------------------------------------------------
     * Pair locking / loop protection
     * ------------------------------------------------------------------ */

    private function lock_key($a, $b) {
        $pair = $this->pair($a, $b);
        return 'bzj_rel_' . $pair[0] . '_' . $pair[1];
    }

    private function acquire_pair_lock($a, $b) {
        global $wpdb;

        $key = $this->lock_key($a, $b);

        /*
         * GET_LOCK is atomic within the MySQL connection and avoids the
         * get_transient()/set_transient() race present in earlier versions.
         */
        $name = 'bzj:' . $key;

        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT GET_LOCK(%s, 0)",
            $name
        ));

        if ((int) $result === 1) {
            $this->held_locks[$name] = true;
            return true;
        }

        return false;
    }

    private function release_pair_lock($a, $b) {
        global $wpdb;

        $name = 'bzj:' . $this->lock_key($a, $b);

        if (!empty($this->held_locks[$name])) {
            $wpdb->get_var($wpdb->prepare(
                "SELECT RELEASE_LOCK(%s)",
                $name
            ));
            unset($this->held_locks[$name]);
        }
    }

    private function push_context($context) {
        $this->context[] = $context;
    }

    private function pop_context() {
        array_pop($this->context);
    }

    private function current_context() {
        if (!$this->context) {
            return null;
        }
        return end($this->context);
    }

    private function is_contextual_duplicate($operation, $actor, $target) {
        $ctx = $this->current_context();

        if (!$ctx) {
            return false;
        }

        return isset($ctx['operation'], $ctx['actor'], $ctx['target']) &&
            $ctx['operation'] === $operation &&
            (int) $ctx['actor'] === (int) $actor &&
            (int) $ctx['target'] === (int) $target;
    }

    private function get_pair_queue($a, $b) {
        global $wpdb;

        $pair = $this->pair($a, $b);

        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT event_uuid,origin,operation,status,attempts,next_attempt_at,last_error,created_at,updated_at
             FROM {$this->events_table}
             WHERE pair_a = %d AND pair_b = %d
             ORDER BY id DESC LIMIT 20",
            $pair[0],
            $pair[1]
        ), ARRAY_A);

        return $rows ?: array();
    }

    /* ---------------------------------------------------------------------
     * Logging
     * ------------------------------------------------------------------ */

    private function log($message, $context = array()) {
        $root = rtrim(ABSPATH, DIRECTORY_SEPARATOR);
        $dir = $root . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'logs';

        if (!is_dir($dir)) {
            wp_mkdir_p($dir);
        }

        $record = array(
            'time' => gmdate('c'),
            'message' => (string) $message,
            'context' => $context,
        );

        @file_put_contents(
            $dir . DIRECTORY_SEPARATOR . self::LOG_FILE,
            wp_json_encode($record, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    private function is_uuid($value) {
        return (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            (string) $value
        );
    }
}

function bzj_connections_sync() {
    return BZJ_Connections_Sync::instance();
}

register_activation_hook(__FILE__, array('BZJ_Connections_Sync', 'activate'));
register_deactivation_hook(__FILE__, array('BZJ_Connections_Sync', 'deactivate'));

bzj_connections_sync();
