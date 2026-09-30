<?php
/**
 * Plugin Name: Buzzjuice Connections Sync
 * Description: Canonical relationship control plane for BuddyBoss, Buzzjuice Streams and Buzzjuice Socials.
 * Version: 8.5.0
 * Author: Buzzjuice Network
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;

if (defined('BZJ_CONNECTIONS_SYNC_LOADED')) {
    return;
}
define('BZJ_CONNECTIONS_SYNC_LOADED', true);

final class BZJ_Connections_Sync {

    const VERSION = '8.5.0';
    const REST_NAMESPACE = 'bzj/v6';
    const REST_ROUTE = '/connection-management';

    const LEDGER_TABLE = 'bzj_relationships';
    const EVENTS_TABLE = 'bzj_relationship_events';

    const LOG_DIR = '/data/logs';
    const LOG_FILE = 'bzj-connections-sync.log';

    const LOCK_TIMEOUT = 15;
    const TIMESTAMP_WINDOW = 300;
    const MAX_ATTEMPTS = 12;
    const CRON_HOOK = 'bzj_connections_sync_recovery';

    const ORIGIN_WORDPRESS = 'wordpress';
    const ORIGIN_STREAMS = 'streams';
    const ORIGIN_SOCIALS = 'socials';

    const OP_CONNECTION_REQUEST = 'connection_request';
    const OP_CONNECTION_ACCEPT = 'connection_accept';
    const OP_CONNECTION_REJECT = 'connection_reject';
    const OP_CONNECTION_WITHDRAW = 'connection_withdraw';
    const OP_CONNECTION_REMOVE = 'connection_remove';
    const OP_FOLLOW = 'follow';
    const OP_UNFOLLOW = 'unfollow';
    const OP_BLOCK = 'block';
    const OP_UNBLOCK = 'unblock';

    private static $instance;
    private $ledger_table;
    private $events_table;
    private $context_stack = array();
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

        // Load the Buzzjuice shared environment/database helpers when present.
        // This exposes the existing BUZZ_SSO_SECRET and database connection
        // configuration without duplicating credentials in wp-config.php.
        $shared_helpers = ABSPATH . 'shared/db_helpers.php';
        if (is_file($shared_helpers)) {
            require_once $shared_helpers;
        }

        add_action('rest_api_init', array($this, 'register_rest_routes'));

        // BuddyBoss connection lifecycle.
        add_action('friends_friendship_requested', array($this, 'bb_requested'), 20, 4);
        add_action('friends_friendship_accepted', array($this, 'bb_accepted'), 20, 4);
        add_action('friends_friendship_rejected', array($this, 'bb_rejected'), 20, 2);
        add_action('friends_friendship_withdrawn', array($this, 'bb_withdrawn'), 20, 2);
        add_action('friends_friendship_whithdrawn', array($this, 'bb_withdrawn'), 20, 2);
        add_action('friends_friendship_deleted', array($this, 'bb_deleted'), 20, 3);

        // BuddyBoss follows.
        add_action('bp_start_following', array($this, 'bb_follow_started'), 20, 1);
        add_action('bp_stop_following', array($this, 'bb_follow_stopped'), 20, 1);
        add_action('bp_follow_start_following', array($this, 'bb_follow_started'), 20, 1);
        add_action('bp_follow_stop_following', array($this, 'bb_follow_stopped'), 20, 1);

        // Installed BuddyBoss moderation lifecycle.
        add_action('bp_moderation_after_save', array($this, 'bb_moderation_saved'), 20, 1);
        add_action('bb_moderation_after_delete', array($this, 'bb_moderation_deleted'), 20, 1);

        add_action(self::CRON_HOOK, array($this, 'process_recovery_queue'));

        $this->install_schema();
        $this->schedule_recovery();
        $this->startup_diagnostics();
    }

    /* --------------------------------------------------------------------- */
    /* Schema / logging                                                       */
    /* --------------------------------------------------------------------- */

    private function install_schema() {
        $installed = get_option('bzj_connections_sync_schema_version', '');
        if ($installed === self::VERSION) {
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $ledger = "CREATE TABLE {$this->ledger_table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_a bigint(20) unsigned NOT NULL,
            user_b bigint(20) unsigned NOT NULL,
            connection_state varchar(20) NOT NULL DEFAULT 'none',
            requested_by bigint(20) unsigned NOT NULL DEFAULT 0,
            follow_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,
            follow_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            block_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,
            block_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            socials_connection_projection varchar(20) NOT NULL DEFAULT 'none',
            socials_follow_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,
            socials_follow_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            streams_follow_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,
            streams_follow_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            version bigint(20) unsigned NOT NULL DEFAULT 1,
            last_event_uuid char(36) NOT NULL DEFAULT '',
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY user_pair (user_a,user_b),
            KEY connection_state (connection_state),
            KEY requested_by (requested_by),
            KEY updated_at (updated_at)
        ) {$charset};";

        $events = "CREATE TABLE {$this->events_table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            event_uuid char(36) NOT NULL,
            origin varchar(20) NOT NULL,
            operation varchar(40) NOT NULL,
            actor_wp_id bigint(20) unsigned NOT NULL DEFAULT 0,
            target_wp_id bigint(20) unsigned NOT NULL DEFAULT 0,
            actor_external_id bigint(20) unsigned NOT NULL DEFAULT 0,
            target_external_id bigint(20) unsigned NOT NULL DEFAULT 0,
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
            KEY operation (operation)
        ) {$charset};";

        dbDelta($ledger);
        dbDelta($events);
        update_option('bzj_connections_sync_schema_version', self::VERSION, false);
    }

    private function schedule_recovery() {
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_event(time() + 120, 'hourly', self::CRON_HOOK);
        }
    }

    private function log($message, $context = array()) {
        $dir = trailingslashit(ABSPATH) . ltrim(self::LOG_DIR, '/');
        if (!is_dir($dir)) {
            wp_mkdir_p($dir);
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            return;
        }
        $entry = array(
            'time' => gmdate('c'),
            'message' => (string) $message,
            'context' => $this->redact($context),
        );
        @file_put_contents(
            trailingslashit($dir) . self::LOG_FILE,
            wp_json_encode($entry, JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    private function redact($value) {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                if (preg_match('/password|secret|authorization|token|cookie|raw_body/i', (string) $k)) {
                    $value[$k] = '[redacted]';
                } else {
                    $value[$k] = $this->redact($v);
                }
            }
        }
        return $value;
    }

    private function startup_diagnostics() {
        static $done = false;
        if ($done) return;
        $done = true;

        global $wpdb;
        $this->log('Control plane startup', array(
            'version' => self::VERSION,
            'wordpress_database' => $wpdb->dbname,
            'wordpress_connection_test' => ((int) $wpdb->get_var('SELECT 1') === 1),
            'ledger_table' => $this->ledger_table,
            'events_table' => $this->events_table,
            'ledger_exists' => $this->table_exists_wp($this->ledger_table),
            'events_exists' => $this->table_exists_wp($this->events_table),
            'usermeta_table' => $wpdb->usermeta,
            'usermeta_exists' => $this->table_exists_wp($wpdb->usermeta),
            'streams' => $this->external_diagnostic('streams'),
            'socials' => $this->external_diagnostic('socials'),
        ));
    }

    private function external_diagnostic($platform) {
        $db = $this->external_db($platform);
        if (!$db) {
            return array('connected' => false, 'error' => 'connection_failed');
        }
        $test = $db->query('SELECT 1');
        $db_name_result = $db->query('SELECT DATABASE()');
        $result = array(
            'connected' => ($test && (int) $test->fetch_row()[0] === 1),
            'database' => $db_name_result ? $db_name_result->fetch_row()[0] : '',
            'tables' => array(),
        );
        $candidates = $platform === 'streams'
            ? array('Wo_Followers', 'Wo_Blocks')
            : array('followers', 'blocks');
        foreach ($candidates as $table) {
            $result['tables'][$table] = $this->table_exists($db, $table);
        }
        return $result;
    }

    /* --------------------------------------------------------------------- */
    /* REST control plane                                                     */
    /* --------------------------------------------------------------------- */

    public function register_rest_routes() {
        register_rest_route(self::REST_NAMESPACE, self::REST_ROUTE, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'rest_command'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route(self::REST_NAMESPACE, self::REST_ROUTE . '/diagnostics', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'rest_diagnostics'),
            'permission_callback' => function () {
                return current_user_can('manage_options');
            },
        ));
    }

    public function rest_diagnostics() {
        return rest_ensure_response(array(
            'success' => true,
            'version' => self::VERSION,
            'route' => home_url('/wp-json/' . self::REST_NAMESPACE . self::REST_ROUTE),
            'wordpress' => array(
                'database' => $GLOBALS['wpdb']->dbname,
                'ledger_table' => $this->ledger_table,
                'events_table' => $this->events_table,
                'usermeta_table' => $GLOBALS['wpdb']->usermeta,
            ),
            'streams' => $this->external_diagnostic('streams'),
            'socials' => $this->external_diagnostic('socials'),
        ));
    }

    public function rest_command(WP_REST_Request $request) {
        $raw = $request->get_body();
        $timestamp = $request->get_header('X-BZJ-Timestamp');
        $signature = $request->get_header('X-BZJ-Signature');

        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            return new WP_Error('bzj_invalid_json', 'Malformed JSON.', array('status' => 400));
        }

        $origin = sanitize_key(isset($payload['origin']) ? $payload['origin'] : '');
        $operation = sanitize_key(isset($payload['operation']) ? $payload['operation'] : '');
        $event_uuid = sanitize_text_field(isset($payload['event_uuid']) ? $payload['event_uuid'] : '');
        $actor_external = absint(isset($payload['actor_external_id']) ? $payload['actor_external_id'] : 0);
        $target_external = absint(isset($payload['target_external_id']) ? $payload['target_external_id'] : 0);

        if (!in_array($origin, array(self::ORIGIN_STREAMS, self::ORIGIN_SOCIALS), true)) {
            return new WP_Error('bzj_invalid_origin', 'Invalid origin.', array('status' => 400));
        }
        if (!$this->verify_signature($timestamp, $signature, $raw, $origin)) {
            $this->log('Rejected external command authentication', array(
                'origin' => $origin,
                'operation' => $operation,
            ));
            return new WP_Error('bzj_auth_failed', 'Authentication failed.', array('status' => 401));
        }
        if (!$this->is_uuid($event_uuid)) {
            return new WP_Error('bzj_invalid_event_uuid', 'Valid event_uuid required.', array('status' => 400));
        }
        if ($actor_external < 1 || $target_external < 1 || $actor_external === $target_external) {
            return new WP_Error('bzj_invalid_external_ids', 'Valid distinct external IDs required.', array('status' => 400));
        }

        $allowed = array(
            self::OP_CONNECTION_REQUEST, self::OP_CONNECTION_ACCEPT,
            self::OP_CONNECTION_REJECT, self::OP_CONNECTION_WITHDRAW,
            self::OP_CONNECTION_REMOVE, self::OP_FOLLOW, self::OP_UNFOLLOW,
            self::OP_BLOCK, self::OP_UNBLOCK,
        );
        if (!in_array($operation, $allowed, true)) {
            return new WP_Error('bzj_invalid_operation', 'Unsupported operation.', array('status' => 400));
        }

        if (!$this->operation_allowed($origin, $operation)) {
            return new WP_Error('bzj_operation_not_allowed', 'Operation is not allowed from this platform.', array('status' => 403));
        }

        if ($this->event_exists($event_uuid)) {
            $event = $this->get_event($event_uuid);
            if ($event && $event->status === 'processed') {
                return rest_ensure_response(array(
                    'success' => true,
                    'status' => 'already_processed',
                    'event_uuid' => $event_uuid,
                ));
            }
        }

        $actor_wp = $this->resolve_external_to_wp($origin, $actor_external);
        $target_wp = $this->resolve_external_to_wp($origin, $target_external);
        if ($actor_wp < 1 || $target_wp < 1 || $actor_wp === $target_wp) {
            return new WP_Error('bzj_identity_mapping_failed', 'External IDs could not be mapped uniquely to WordPress users.', array('status' => 409));
        }

        if (!$this->acquire_pair_lock($actor_wp, $target_wp)) {
            return new WP_Error('bzj_pair_busy', 'Relationship pair is busy; retry.', array('status' => 503));
        }

        try {
            $this->upsert_event($event_uuid, $origin, $operation, $actor_wp, $target_wp, $actor_external, $target_external, $payload);
            $result = $this->process_external_operation($operation, $actor_wp, $target_wp, $event_uuid);
            if (!is_wp_error($result) && $origin === self::ORIGIN_SOCIALS && $operation === self::OP_FOLLOW) {
                $this->save_ledger($actor_wp, $target_wp, array(
                    $this->directional_social_field($actor_wp, $target_wp) => 1
                ), $event_uuid);
            }
            if (!is_wp_error($result) && $origin === self::ORIGIN_SOCIALS && $operation === self::OP_UNFOLLOW) {
                $this->save_ledger($actor_wp, $target_wp, array(
                    $this->directional_social_field($actor_wp, $target_wp) => 0
                ), $event_uuid);
            }
            if (is_wp_error($result)) {
                $this->mark_retry($event_uuid, $result->get_error_message());
                return $result;
            }

            // Native BuddyBoss hooks have already projected canonical state.
            $this->mark_processed($event_uuid);
            $this->log('External command processed', array(
                'platform' => 'wordpress',
                'origin' => $origin,
                'operation' => $operation,
                'actor_wp_id' => $actor_wp,
                'target_wp_id' => $target_wp,
                'actor_external_id' => $actor_external,
                'target_external_id' => $target_external,
                'event_uuid' => $event_uuid,
            ));

            return rest_ensure_response(array(
                'success' => true,
                'status' => 'processed',
                'event_uuid' => $event_uuid,
                'actor_wp_id' => $actor_wp,
                'target_wp_id' => $target_wp,
            ));
        } catch (Throwable $e) {
            $this->mark_retry($event_uuid, $e->getMessage());
            $this->log('External command failed', array(
                'origin' => $origin,
                'operation' => $operation,
                'event_uuid' => $event_uuid,
                'error' => $e->getMessage(),
            ));
            return new WP_Error('bzj_sync_failed', 'Synchronization failed.', array('status' => 500, 'event_uuid' => $event_uuid));
        } finally {
            $this->release_pair_lock($actor_wp, $target_wp);
        }
    }

    private function operation_allowed($origin, $operation) {
        if ($origin === self::ORIGIN_STREAMS) {
            return in_array($operation, array(self::OP_FOLLOW, self::OP_UNFOLLOW, self::OP_BLOCK, self::OP_UNBLOCK), true);
        }
        return true;
    }

    private function verify_signature($timestamp, $signature, $raw, $origin) {
        if (!is_numeric($timestamp) || !$signature) return false;
        if (abs(time() - (int) $timestamp) > self::TIMESTAMP_WINDOW) return false;
        $secret = $this->secret_for_origin($origin);
        if (!$secret) return false;
        $expected = hash_hmac('sha256', $timestamp . '.' . $raw, $secret);
        return hash_equals($expected, $signature);
    }

    private function secret_for_origin($origin) {
        $specific = $origin === self::ORIGIN_STREAMS
            ? getenv('BZJ_STREAMS_SYNC_SECRET')
            : getenv('BZJ_SOCIALS_SYNC_SECRET');
        if ($specific) return $specific;
        $specific_constant = $origin === self::ORIGIN_STREAMS
            ? (defined('BZJ_STREAMS_SYNC_SECRET') ? BZJ_STREAMS_SYNC_SECRET : '')
            : (defined('BZJ_SOCIALS_SYNC_SECRET') ? BZJ_SOCIALS_SYNC_SECRET : '');
        if ($specific_constant) return $specific_constant;
        $fallback = getenv('BUZZ_SSO_SECRET');
        return $fallback ? $fallback : (defined('BUZZ_SSO_SECRET') ? BUZZ_SSO_SECRET : '');
    }

    /* --------------------------------------------------------------------- */
    /* External-origin mutations: native BuddyBoss only                       */
    /* --------------------------------------------------------------------- */

    private function process_external_operation($operation, $actor, $target, $event_uuid) {
        switch ($operation) {
            case self::OP_CONNECTION_REQUEST:
                return $this->external_connection_request($actor, $target, $event_uuid);
            case self::OP_CONNECTION_ACCEPT:
                return $this->external_connection_accept($actor, $target, $event_uuid);
            case self::OP_CONNECTION_REJECT:
                return $this->external_connection_reject($actor, $target, $event_uuid);
            case self::OP_CONNECTION_WITHDRAW:
                return $this->external_connection_withdraw($actor, $target, $event_uuid);
            case self::OP_CONNECTION_REMOVE:
                return $this->external_connection_remove($actor, $target, $event_uuid);
            case self::OP_FOLLOW:
                return $this->external_follow($actor, $target, $event_uuid);
            case self::OP_UNFOLLOW:
                return $this->external_unfollow($actor, $target, $event_uuid);
            case self::OP_BLOCK:
                return $this->external_block($actor, $target, $event_uuid);
            case self::OP_UNBLOCK:
                return $this->external_unblock($actor, $target, $event_uuid);
        }
        return new WP_Error('bzj_unknown_operation', 'Unknown operation.');
    }

    private function external_connection_request($actor, $target, $uuid) {
        if ($this->bb_is_blocked($actor, $target)) {
            return new WP_Error('bzj_blocked', 'Connection is blocked.');
        }
        if (!function_exists('friends_add_friend')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss connection function unavailable.');
        }
        $status = function_exists('friends_check_friendship_status') ? friends_check_friendship_status($actor, $target) : '';
        if ($status === 'is_friend' || $status === 'pending') return true;
        if ($status === 'awaiting_response') {
            return new WP_Error('bzj_reverse_pending', 'A reverse connection request already exists.');
        }
        $result = friends_add_friend($actor, $target);
        if (!$result) return new WP_Error('bzj_bb_request_failed', 'BuddyBoss rejected the connection request.');
        return true;
    }

    private function external_connection_accept($actor, $target, $uuid) {
        if (!function_exists('friends_accept_friendship')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss acceptance function unavailable.');
        }
        $friendship_id = $this->find_friendship_id_for_accept($actor, $target);
        if (!$friendship_id) return new WP_Error('bzj_request_missing', 'Pending BuddyBoss request not found.');
        $result = friends_accept_friendship($friendship_id);
        if (!$result && (!function_exists('friends_check_friendship_status') || friends_check_friendship_status($actor, $target) !== 'is_friend')) {
            return new WP_Error('bzj_bb_accept_failed', 'BuddyBoss could not accept the request.');
        }
        return true;
    }

    private function external_connection_reject($actor, $target, $uuid) {
        if (!function_exists('friends_reject_friendship')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss rejection function unavailable.');
        }
        $friendship_id = $this->find_friendship_id_for_accept($actor, $target);
        if (!$friendship_id) return true;
        $result = friends_reject_friendship($friendship_id);
        if (!$result && function_exists('friends_check_friendship_status') && friends_check_friendship_status($actor, $target) !== 'not_friends') {
            return new WP_Error('bzj_bb_reject_failed', 'BuddyBoss could not reject the request.');
        }
        return true;
    }

    private function external_connection_withdraw($actor, $target, $uuid) {
        if (!function_exists('friends_withdraw_friendship')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss withdrawal function unavailable.');
        }
        $result = friends_withdraw_friendship($actor, $target);
        if (!$result && function_exists('friends_check_friendship_status') && friends_check_friendship_status($actor, $target) !== 'not_friends') {
            return new WP_Error('bzj_bb_withdraw_failed', 'BuddyBoss could not withdraw the request.');
        }
        return true;
    }

    private function external_connection_remove($actor, $target, $uuid) {
        if (!function_exists('friends_remove_friend')) {
            return new WP_Error('bzj_bb_unavailable', 'BuddyBoss removal function unavailable.');
        }
        $status = function_exists('friends_check_friendship_status') ? friends_check_friendship_status($actor, $target) : '';
        if ($status !== 'is_friend') return true;

        $result = friends_remove_friend($actor, $target);
        if (!$result) $result = friends_remove_friend($target, $actor);
        if (!$result && function_exists('friends_check_friendship_status') && friends_check_friendship_status($actor, $target) === 'is_friend') {
            return new WP_Error('bzj_bb_remove_failed', 'BuddyBoss could not remove the connection.');
        }
        return true;
    }

    private function external_follow($actor, $target, $uuid) {
        if ($this->bb_is_blocked($actor, $target)) {
            return new WP_Error('bzj_blocked', 'Follow is blocked.');
        }
        if (!function_exists('bp_start_following')) {
            return new WP_Error('bzj_bb_follow_unavailable', 'BuddyBoss follow function unavailable.');
        }
        if (function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) {
            return true;
        }
        $result = bp_start_following(array('leader_id'=>$target,'follower_id'=>$actor));
        if (is_wp_error($result)) return $result;
        if ($result === false && function_exists('bp_is_following') && !bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) {
            return new WP_Error('bzj_bb_follow_failed', 'BuddyBoss rejected the follow.');
        }
        return true;
    }

    private function external_unfollow($actor, $target, $uuid) {
        if (!function_exists('bp_stop_following')) {
            return new WP_Error('bzj_bb_unfollow_unavailable', 'BuddyBoss unfollow function unavailable.');
        }
        if (function_exists('bp_is_following') && !bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) {
            return true;
        }
        $result = bp_stop_following(array('leader_id'=>$target,'follower_id'=>$actor));
        if (is_wp_error($result)) return $result;
        if ($result === false && function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) {
            return new WP_Error('bzj_bb_unfollow_failed', 'BuddyBoss rejected the unfollow.');
        }
        return true;
    }

    private function external_block($actor, $target, $uuid) {
        if (!class_exists('BP_Moderation') || !class_exists('BP_Moderation_Members')) {
            return new WP_Error('bzj_moderation_unavailable', 'BuddyBoss moderation classes unavailable.');
        }

        if (!$this->bb_block_exists_direction($actor, $target)) {
            $moderation = new BP_Moderation($target, BP_Moderation_Members::$moderation_type, $actor);
            $moderation->user_report = 0;
            $moderation->content = '';
            if (!$moderation->save()) {
                return new WP_Error('bzj_bb_block_failed', 'BuddyBoss could not create the block.');
            }
        }

        // The installed BuddyBoss moderation hook performs canonical cleanup
        // and external projection after the native block has been saved.
        if (!$this->bb_block_exists_direction($actor, $target)) {
            return new WP_Error('bzj_bb_block_unverified', 'BuddyBoss block was not verified after save.');
        }
        return true;
    }

    private function external_unblock($actor, $target, $uuid) {
        if (!class_exists('BP_Moderation') || !class_exists('BP_Moderation_Members')) {
            return new WP_Error('bzj_moderation_unavailable', 'BuddyBoss moderation classes unavailable.');
        }
        $moderation = new BP_Moderation($target, BP_Moderation_Members::$moderation_type, $actor);
        if (!empty($moderation->id) && empty($moderation->user_report)) {
            if (!$moderation->delete(false)) {
                return new WP_Error('bzj_bb_unblock_failed', 'BuddyBoss could not remove the block.');
            }
        }
        return true;
    }

    /* --------------------------------------------------------------------- */
    /* BuddyBoss canonical lifecycle                                          */
    /* --------------------------------------------------------------------- */

    public function bb_requested($friendship_id, $initiator, $friend, $friendship = null) {
        if ($this->in_internal_context()) return;
        $this->canonical_connection('requested', absint($initiator), absint($friend), absint($friendship_id));
    }

    public function bb_accepted($friendship_id, $initiator, $friend, $friendship = null) {
        if ($this->in_internal_context()) return;
        $this->canonical_connection('accepted', absint($initiator), absint($friend), absint($friendship_id));
    }

    public function bb_rejected($friendship_id, $friendship = null) {
        if ($this->in_internal_context()) return;
        $f = $this->friendship_object($friendship_id, $friendship, false);
        if (!$f) return;
        $this->canonical_connection('rejected', absint($f->initiator_user_id), absint($f->friend_user_id), absint($friendship_id));
    }

    public function bb_withdrawn($friendship_id, $friendship = null) {
        if ($this->in_internal_context()) return;
        $f = $this->friendship_object($friendship_id, $friendship, true);
        if (!$f) return;
        $this->canonical_connection('withdrawn', absint($f->initiator_user_id), absint($f->friend_user_id), absint($friendship_id));
    }

    public function bb_deleted($friendship_id, $initiator, $friend) {
        if ($this->in_internal_context()) return;
        $initiator = absint($initiator);
        $friend = absint($friend);
        if ($initiator < 1 || $friend < 1 || $initiator === $friend) return;

        // Deleted is a single deduplicated removal trigger. If a specific
        // reject/withdraw hook already handled the request, ledger is none and
        // this handler becomes a no-op. If the connection was established,
        // it removes only the connection-owned QuickDate projection.
        $ledger = $this->get_ledger($initiator, $friend);
        if ($ledger && $ledger['connection_state'] === 'none') return;

        $this->canonical_connection('deleted', $initiator, $friend, $friendship_id);
    }

    private function friendship_object($id, $object, $withdrawn) {
        if (is_object($object)) return $object;
        if ($id && class_exists('BP_Friends_Friendship')) {
            if ($withdrawn) return new BP_Friends_Friendship($id, true, false);
            return new BP_Friends_Friendship($id);
        }
        return null;
    }

    private function canonical_connection($event, $actor, $target, $friendship_id) {
        if ($actor < 1 || $target < 1 || $actor === $target) return;

        $existing = $this->get_ledger($actor, $target);
        if (in_array($event, array('rejected','withdrawn','deleted'), true)
            && $existing
            && $existing['connection_state'] === 'none') {
            return;
        }

        $uuid = wp_generate_uuid4();
        if (!$this->acquire_pair_lock($actor, $target)) {
            $this->upsert_event($uuid, self::ORIGIN_WORDPRESS, 'connection_' . $event, $actor, $target, 0, 0, array('friendship_id'=>$friendship_id));
            $this->mark_retry($uuid, 'Pair lock busy.');
            return;
        }

        try {
            $this->upsert_event($uuid, self::ORIGIN_WORDPRESS, 'connection_' . $event, $actor, $target, 0, 0, array('friendship_id'=>$friendship_id));

            $state = 'none';
            $requested_by = 0;

            if ($event === 'requested') {
                $state = 'requested';
                $requested_by = $actor;
            } elseif ($event === 'accepted') {
                $state = 'connected';
            } elseif (in_array($event, array('rejected','withdrawn'), true)) {
                $state = 'none';
            } elseif ($event === 'deleted') {
                $state = 'none';
            }

            $this->save_ledger($actor, $target, array(
                'connection_state' => $state,
                'requested_by' => $requested_by,
                'socials_connection_projection' => $state,
            ), $uuid);

            $this->project_social_connection($actor, $target, $state, $uuid);
            $this->mark_processed($uuid);

            $this->log('BuddyBoss connection projected', array(
                'platform'=>'wordpress',
                'origin'=>'wordpress',
                'operation'=>'connection_' . $event,
                'actor_wp_id'=>$actor,
                'target_wp_id'=>$target,
                'database'=>$this->external_database_name('socials'),
                'table'=>'followers',
                'event_uuid'=>$uuid,
                'verification'=>$state,
            ));
        } catch (Throwable $e) {
            $this->mark_retry($uuid, $e->getMessage());
            $this->log('BuddyBoss connection projection failed', array(
                'operation'=>'connection_' . $event,
                'actor_wp_id'=>$actor,
                'target_wp_id'=>$target,
                'event_uuid'=>$uuid,
                'error'=>$e->getMessage(),
            ));
        } finally {
            $this->release_pair_lock($actor, $target);
        }
    }

    public function bb_follow_started($follow) {
        $this->canonical_follow(true, $follow);
    }

    public function bb_follow_stopped($follow) {
        $this->canonical_follow(false, $follow);
    }

    private function canonical_follow($following, $follow) {
        if ($this->in_internal_context() || !is_object($follow)) return;
        $actor = absint(isset($follow->follower_id) ? $follow->follower_id : 0);
        $target = absint(isset($follow->leader_id) ? $follow->leader_id : 0);
        if ($actor < 1 || $target < 1 || $actor === $target) return;

        $uuid = wp_generate_uuid4();
        if (!$this->acquire_pair_lock($actor, $target)) {
            $this->upsert_event($uuid, self::ORIGIN_WORDPRESS, $following ? self::OP_FOLLOW : self::OP_UNFOLLOW, $actor, $target, 0, 0);
            $this->mark_retry($uuid, 'Pair lock busy.');
            return;
        }

        try {
            $op = $following ? self::OP_FOLLOW : self::OP_UNFOLLOW;
            $this->upsert_event($uuid, self::ORIGIN_WORDPRESS, $op, $actor, $target, 0, 0);

            if ($following && $this->bb_is_blocked($actor, $target)) {
                $following = false;
            }

            $this->save_ledger($actor, $target, array(
                $this->directional_follow_field($actor, $target) => $following ? 1 : 0,
                $this->directional_stream_field($actor, $target) => $following ? 1 : 0,
            ), $uuid);

            // BuddyBoss ordinary follows project to Streams only.
            $this->project_streams_follow($actor, $target, $following, $uuid);
            $this->mark_processed($uuid);
        } catch (Throwable $e) {
            $this->mark_retry($uuid, $e->getMessage());
            $this->log('BuddyBoss follow projection failed', array(
                'operation'=>$op,
                'actor_wp_id'=>$actor,
                'target_wp_id'=>$target,
                'event_uuid'=>$uuid,
                'error'=>$e->getMessage(),
            ));
        } finally {
            $this->release_pair_lock($actor, $target);
        }
    }

    public function bb_moderation_saved($moderation) {
        if (!$this->is_user_block($moderation) || $this->in_internal_context()) return;
        $this->canonical_block(true, absint($moderation->user_id), absint($moderation->item_id), absint(isset($moderation->id) ? $moderation->id : 0));
    }

    public function bb_moderation_deleted($moderation) {
        if (!$this->is_user_block($moderation) || $this->in_internal_context()) return;
        $this->canonical_block(false, absint($moderation->user_id), absint($moderation->item_id), absint(isset($moderation->id) ? $moderation->id : 0));
    }

    private function is_user_block($moderation) {
        return is_object($moderation)
            && isset($moderation->item_type)
            && $moderation->item_type === 'user'
            && empty($moderation->user_report)
            && absint($moderation->user_id) > 0
            && absint($moderation->item_id) > 0;
    }

    private function canonical_block($blocked_state, $actor, $target, $moderation_id) {
        if ($actor < 1 || $target < 1 || $actor === $target) return;

        $uuid = wp_generate_uuid4();
        if (!$this->acquire_pair_lock($actor, $target)) {
            $this->upsert_event($uuid, self::ORIGIN_WORDPRESS, $blocked_state ? self::OP_BLOCK : self::OP_UNBLOCK, $actor, $target, 0, 0, array('moderation_id'=>$moderation_id));
            $this->mark_retry($uuid, 'Pair lock busy.');
            return;
        }

        try {
            $op = $blocked_state ? self::OP_BLOCK : self::OP_UNBLOCK;
            $this->upsert_event($uuid, self::ORIGIN_WORDPRESS, $op, $actor, $target, 0, 0, array('moderation_id'=>$moderation_id));

            if ($blocked_state) {
                // Proactive cleanup through native BuddyBoss.
                $this->enforce_block_on_buddyboss($actor, $target);

                $this->save_ledger($actor, $target, array(
                    $this->directional_block_field($actor, $target) => 1,
                    'connection_state'=>'none',
                    'requested_by'=>0,
                    'follow_a_to_b'=>0,
                    'follow_b_to_a'=>0,
                ), $uuid);

                $this->project_streams_follow($actor, $target, false, $uuid);
                $this->project_streams_follow($target, $actor, false, $uuid);
                $this->project_social_connection($actor, $target, 'none', $uuid);
                $this->project_social_follow($actor, $target, false, $uuid);
                $this->project_social_follow($target, $actor, false, $uuid);
                $this->project_streams_block($actor, $target, true, $uuid);
                $this->project_social_block($actor, $target, true, $uuid);
            } else {
                $this->save_ledger($actor, $target, array(
                    $this->directional_block_field($actor, $target) => 0,
                ), $uuid);
                $this->project_streams_block($actor, $target, false, $uuid);
                $this->project_social_block($actor, $target, false, $uuid);
            }

            $this->mark_processed($uuid);
        } catch (Throwable $e) {
            $this->mark_retry($uuid, $e->getMessage());
            $this->log('BuddyBoss block projection failed', array(
                'operation'=>$op,
                'actor_wp_id'=>$actor,
                'target_wp_id'=>$target,
                'event_uuid'=>$uuid,
                'error'=>$e->getMessage(),
            ));
        } finally {
            $this->release_pair_lock($actor, $target);
        }
    }

    private function enforce_block_on_buddyboss($actor, $target) {
        if (function_exists('friends_check_friendship_status') && function_exists('friends_remove_friend')) {
            $status = friends_check_friendship_status($actor, $target);
            if ($status === 'is_friend') {
                $this->push_context(array('origin'=>'internal_block_cleanup','actor'=>$actor,'target'=>$target));
                try {
                    friends_remove_friend($actor, $target);
                } finally {
                    $this->pop_context();
                }
            }
        }
        if (function_exists('friends_get_friendship_id') && function_exists('friends_reject_friendship')) {
            $id = (int) friends_get_friendship_id($actor, $target);
            if (!$id) $id = (int) friends_get_friendship_id($target, $actor);
            if ($id) {
                $this->push_context(array('origin'=>'internal_block_cleanup','actor'=>$actor,'target'=>$target));
                try {
                    @friends_reject_friendship($id);
                } finally {
                    $this->pop_context();
                }
            }
        }
        if (function_exists('bp_stop_following')) {
            foreach (array(array($actor,$target),array($target,$actor)) as $pair) {
                if (function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$pair[1],'follower_id'=>$pair[0]))) {
                    $this->push_context(array('origin'=>'internal_block_cleanup','actor'=>$pair[0],'target'=>$pair[1]));
                    try {
                        @bp_stop_following(array('leader_id'=>$pair[1],'follower_id'=>$pair[0]));
                    } finally {
                        $this->pop_context();
                    }
                }
            }
        }
    }

    /* --------------------------------------------------------------------- */
    /* External projections                                                    */
    /* --------------------------------------------------------------------- */

    private function project_streams_follow($actor_wp, $target_wp, $enable, $uuid) {
        $actor = $this->external_id($actor_wp, 'streams');
        $target = $this->external_id($target_wp, 'streams');
        if (!$actor || !$target) throw new RuntimeException('Streams ID mapping missing.');

        $db = $this->external_db('streams');
        if (!$db) throw new RuntimeException('Streams database unavailable.');

        $table = $this->resolve_table($db, array('Wo_Followers','followers'));
        if (!$table) throw new RuntimeException('Streams followers table not found.');

        if ($enable) {
            $existing = $db->prepare("SELECT id FROM `{$table}` WHERE `following_id`=? AND `follower_id`=? LIMIT 1");
            if (!$existing) throw new RuntimeException($db->error);
            $existing->bind_param('ii', $target, $actor);
            $existing->execute();
            $existing_id = null;
            $existing->bind_result($existing_id);
            $found = $existing->fetch();
            $existing->close();

            if ($found) {
                $stmt = $db->prepare("UPDATE `{$table}` SET `active`=1 WHERE `id`=?");
                if (!$stmt) throw new RuntimeException($db->error);
                $stmt->bind_param('i', $existing_id);
            } else {
                $stmt = $db->prepare("INSERT INTO `{$table}` (`following_id`,`follower_id`,`active`) VALUES (?,?,1)");
                if (!$stmt) throw new RuntimeException($db->error);
                $stmt->bind_param('ii', $target, $actor);
            }
            $ok = $stmt->execute();
            $err = $stmt->error;
            $stmt->close();
            if (!$ok) throw new RuntimeException($err ?: 'Streams follow insert/update failed.');
        } else {
            $stmt = $db->prepare("DELETE FROM `{$table}` WHERE `following_id`=? AND `follower_id`=?");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii', $target, $actor);
            $ok = $stmt->execute();
            $affected = $stmt->affected_rows;
            $err = $stmt->error;
            $stmt->close();
            if (!$ok) throw new RuntimeException($err ?: 'Streams follow delete failed.');
        }

        $count = 0;
        $verify = $db->prepare("SELECT COUNT(*) FROM `{$table}` WHERE `following_id`=? AND `follower_id`=?");
        if (!$verify) throw new RuntimeException($db->error);
        $verify->bind_param('ii', $target, $actor);
        $verify->execute();
        $verify->bind_result($count);
        $verify->fetch();
        $verify->close();

        $verified = $enable ? ((int)$count >= 1) : ((int)$count === 0);
        if (!$verified) throw new RuntimeException('Streams follow read-back verification failed.');

        $this->log('Streams follow projection', array(
            'platform'=>'streams','operation'=>$enable?'insert_or_activate':'delete',
            'actor_wp_id'=>$actor_wp,'target_wp_id'=>$target_wp,
            'actor_external_id'=>$actor,'target_external_id'=>$target,
            'database'=>($db->query('SELECT DATABASE()') ? $db->query('SELECT DATABASE()')->fetch_row()[0] : ''),
            'table'=>$table,'affected_rows'=>isset($affected)?$affected:1,
            'verification'=>$verified,'event_uuid'=>$uuid,
        ));
    }

    private function project_social_connection($actor_wp, $target_wp, $state, $uuid) {
        $a = $this->external_id($actor_wp, 'socials');
        $b = $this->external_id($target_wp, 'socials');
        if (!$a || !$b) throw new RuntimeException('Socials ID mapping missing.');

        $db = $this->external_db('socials');
        if (!$db) throw new RuntimeException('Socials database unavailable.');
        $table = $this->resolve_table($db, array('followers'));
        if (!$table) throw new RuntimeException('Socials followers table not found.');

        if ($state === 'requested') {
            $ledger = $this->get_ledger($actor_wp, $target_wp);
            $requester = $ledger ? (int)$ledger['requested_by'] : $actor_wp;
            $r = $requester === $actor_wp ? $a : $b;
            $t = $requester === $actor_wp ? $b : $a;

            $this->upsert_qd_follow_row($db, $table, $t, $r, 0);
            // A pending connection masks the connection direction only.
            $this->delete_qd_row_if_other_direction($db, $table, $r, $t);
            $this->verify_qd_row($db, $table, $t, $r, 0, $uuid, 'connection_request');
            return;
        }

        if ($state === 'connected') {
            $this->upsert_qd_follow_row($db, $table, $a, $b, 1);
            $this->upsert_qd_follow_row($db, $table, $b, $a, 1);
            $this->verify_qd_row($db, $table, $a, $b, 1, $uuid, 'connection_accept');
            $this->verify_qd_row($db, $table, $b, $a, 1, $uuid, 'connection_accept');
            return;
        }

        // Connection removed/rejected/withdrawn. Restore ordinary follows
        // from the canonical ledger, otherwise remove the connection rows.
        $ledger = $this->get_ledger($actor_wp, $target_wp);
        $keep_ab = $ledger && !empty($ledger['socials_follow_a_to_b']);
        $keep_ba = $ledger && !empty($ledger['socials_follow_b_to_a']);

        if ($keep_ab) {
            $this->upsert_qd_follow_row($db, $table, $b, $a, 1);
        } else {
            $this->delete_qd_row($db, $table, $b, $a);
        }
        if ($keep_ba) {
            $this->upsert_qd_follow_row($db, $table, $a, $b, 1);
        } else {
            $this->delete_qd_row($db, $table, $a, $b);
        }

        $this->log('QuickDate connection projection cleared', array(
            'platform'=>'socials','operation'=>'connection_clear','actor_wp_id'=>$actor_wp,'target_wp_id'=>$target_wp,
            'database'=>($db->query('SELECT DATABASE()') ? $db->query('SELECT DATABASE()')->fetch_row()[0] : ''),'table'=>$table,'event_uuid'=>$uuid,
            'verification'=>true,
        ));
    }

    private function project_social_follow($actor_wp, $target_wp, $enable, $uuid) {
        $actor = $this->external_id($actor_wp, 'socials');
        $target = $this->external_id($target_wp, 'socials');
        if (!$actor || !$target) throw new RuntimeException('Socials ID mapping missing.');
        $db = $this->external_db('socials');
        if (!$db) throw new RuntimeException('Socials database unavailable.');
        $table = $this->resolve_table($db, array('followers'));
        if (!$table) throw new RuntimeException('Socials followers table not found.');

        if ($enable) $this->upsert_qd_follow_row($db, $table, $target, $actor, 1);
        else $this->delete_qd_row($db, $table, $target, $actor);

        $verify_active = $enable
            ? $this->row_exists($db, $table, $target, $actor, 1)
            : !$this->row_exists_any($db, $table, $target, $actor);
        if (!$verify_active) throw new RuntimeException('QuickDate follow verification failed.');

        $this->log('QuickDate ordinary follow projection', array(
            'platform'=>'socials','operation'=>$enable?'insert':'delete','actor_wp_id'=>$actor_wp,'target_wp_id'=>$target_wp,
            'database'=>($db->query('SELECT DATABASE()') ? $db->query('SELECT DATABASE()')->fetch_row()[0] : ''),'table'=>$table,'event_uuid'=>$uuid,'verification'=>true,
        ));
    }

    private function project_streams_block($actor_wp, $target_wp, $enable, $uuid) {
        $actor = $this->external_id($actor_wp, 'streams');
        $target = $this->external_id($target_wp, 'streams');
        if (!$actor || !$target) throw new RuntimeException('Streams ID mapping missing.');
        $db = $this->external_db('streams');
        if (!$db) throw new RuntimeException('Streams database unavailable.');
        $table = $this->resolve_table($db, array('Wo_Blocks','blocks'));
        if (!$table) throw new RuntimeException('Streams blocks table not found.');

        if ($enable) {
            $stmt = $db->prepare("INSERT INTO `{$table}` (`blocker`,`blocked`) VALUES (?,?)");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$actor,$target);
            $ok = $stmt->execute();
            $stmt->close();
            if (!$ok && !$this->row_exists_block($db,$table,$actor,$target)) throw new RuntimeException('Streams block insert failed.');
        } else {
            $stmt = $db->prepare("DELETE FROM `{$table}` WHERE `blocker`=? AND `blocked`=?");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$actor,$target);
            $stmt->execute();
            $stmt->close();
        }
        $exists = $this->row_exists_block($db,$table,$actor,$target);
        if ($enable !== $exists) throw new RuntimeException('Streams block verification failed.');
        $this->log('Streams block projection', array(
            'platform'=>'streams','operation'=>$enable?'insert':'delete','actor_wp_id'=>$actor_wp,'target_wp_id'=>$target_wp,
            'database'=>($db->query('SELECT DATABASE()') ? $db->query('SELECT DATABASE()')->fetch_row()[0] : ''),'table'=>$table,'event_uuid'=>$uuid,'verification'=>true,
        ));
    }

    private function project_social_block($actor_wp, $target_wp, $enable, $uuid) {
        $actor = $this->external_id($actor_wp, 'socials');
        $target = $this->external_id($target_wp, 'socials');
        if (!$actor || !$target) throw new RuntimeException('Socials ID mapping missing.');
        $db = $this->external_db('socials');
        if (!$db) throw new RuntimeException('Socials database unavailable.');
        $table = $this->resolve_table($db, array('blocks'));
        if (!$table) throw new RuntimeException('Socials blocks table not found.');

        if ($enable) {
            $stmt = $db->prepare("INSERT INTO `{$table}` (`user_id`,`block_userid`,`created_at`) VALUES (?,?,NOW())");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$actor,$target);
            $ok = $stmt->execute();
            $stmt->close();
            if (!$ok && !$this->row_exists_block($db,$table,$actor,$target)) throw new RuntimeException('Socials block insert failed.');
        } else {
            $stmt = $db->prepare("DELETE FROM `{$table}` WHERE `user_id`=? AND `block_userid`=?");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$actor,$target);
            $stmt->execute();
            $stmt->close();
        }
        $exists = $this->row_exists_block($db,$table,$actor,$target);
        if ($enable !== $exists) throw new RuntimeException('Socials block verification failed.');
        $this->log('QuickDate block projection', array(
            'platform'=>'socials','operation'=>$enable?'insert':'delete','actor_wp_id'=>$actor_wp,'target_wp_id'=>$target_wp,
            'database'=>($db->query('SELECT DATABASE()') ? $db->query('SELECT DATABASE()')->fetch_row()[0] : ''),'table'=>$table,'event_uuid'=>$uuid,'verification'=>true,
        ));
    }

    private function upsert_qd_follow_row($db, $table, $following, $follower, $active) {
        $existing = $db->prepare("SELECT id FROM `{$table}` WHERE `following_id`=? AND `follower_id`=? LIMIT 1");
        if (!$existing) throw new RuntimeException($db->error);
        $existing->bind_param('ii',$following,$follower);
        $existing->execute();
        $id = null;
        $existing->bind_result($id);
        $found = $existing->fetch();
        $existing->close();

        if ($found) {
            $stmt = $db->prepare("UPDATE `{$table}` SET `active`=? WHERE `id`=?");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$active,$id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $db->prepare("INSERT INTO `{$table}` (`following_id`,`follower_id`,`active`) VALUES (?,?,?)");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('iii',$following,$follower,$active);
            $ok = $stmt->execute();
            $err = $stmt->error;
            $stmt->close();
            if (!$ok) throw new RuntimeException($err ?: 'QuickDate follower insert failed.');
        }
    }

    private function verify_qd_row($db,$table,$following,$follower,$active,$uuid,$operation) {
        $ok = $this->row_exists($db,$table,$following,$follower,$active);
        if (!$ok) throw new RuntimeException('QuickDate connection read-back verification failed.');
        $this->log('QuickDate connection projection', array(
            'platform'=>'socials','operation'=>$operation,'actor_external_id'=>$follower,'target_external_id'=>$following,
            'database'=>($db->query('SELECT DATABASE()') ? $db->query('SELECT DATABASE()')->fetch_row()[0] : ''),'table'=>$table,'event_uuid'=>$uuid,'verification'=>true,
        ));
    }

    private function delete_qd_row($db,$table,$following,$follower) {
        $stmt = $db->prepare("DELETE FROM `{$table}` WHERE `following_id`=? AND `follower_id`=?");
        if (!$stmt) throw new RuntimeException($db->error);
        $stmt->bind_param('ii',$following,$follower);
        $stmt->execute();
        $stmt->close();
    }

    private function delete_qd_row_if_other_direction($db,$table,$following,$follower) {
        // A pending connection is represented by exactly one active=0 row.
        // Do not delete an independent reverse ordinary follow here.
    }

    /* --------------------------------------------------------------------- */
    /* Ledger / event queue                                                   */
    /* --------------------------------------------------------------------- */

    private function save_ledger($actor,$target,$changes,$uuid) {
        global $wpdb;
        list($a,$b) = $this->pair($actor,$target);
        $current = $this->get_ledger($a,$b);
        $now = current_time('mysql', true);

        if (!$current) {
            $row = array(
                'user_a'=>$a,'user_b'=>$b,'connection_state'=>'none','requested_by'=>0,
                'follow_a_to_b'=>0,'follow_b_to_a'=>0,'block_a_to_b'=>0,'block_b_to_a'=>0,
                'socials_connection_projection'=>'none','socials_follow_a_to_b'=>0,'socials_follow_b_to_a'=>0,
                'streams_follow_a_to_b'=>0,'streams_follow_b_to_a'=>0,'version'=>1,
                'last_event_uuid'=>$uuid,'created_at'=>$now,'updated_at'=>$now,
            );
            $row = array_merge($row,$changes);
            $wpdb->insert($this->ledger_table,$row);
        } else {
            $updates = $changes;
            $updates['version'] = ((int)$current['version']) + 1;
            $updates['last_event_uuid'] = $uuid;
            $updates['updated_at'] = $now;
            $wpdb->update($this->ledger_table,$updates,array('user_a'=>$a,'user_b'=>$b));
        }
        $verify = $this->get_ledger($a,$b);
        if (!$verify) throw new RuntimeException('Relationship ledger write/read-back failed.');
    }

    private function get_ledger($actor,$target) {
        global $wpdb;
        list($a,$b) = $this->pair($actor,$target);
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->ledger_table} WHERE user_a=%d AND user_b=%d LIMIT 1",$a,$b),ARRAY_A);
        return $row ?: null;
    }

    private function upsert_event($uuid,$origin,$operation,$actor,$target,$actor_ext=0,$target_ext=0,$payload=array()) {
        global $wpdb;
        $existing = $this->get_event($uuid);
        if ($existing) return;
        list($a,$b) = $this->pair($actor,$target);
        $now = current_time('mysql',true);
        $wpdb->insert($this->events_table,array(
            'event_uuid'=>$uuid,'origin'=>$origin,'operation'=>$operation,'actor_wp_id'=>$actor,'target_wp_id'=>$target,
            'actor_external_id'=>$actor_ext,'target_external_id'=>$target_ext,'pair_a'=>$a,'pair_b'=>$b,
            'payload'=>wp_json_encode($this->redact($payload)),'status'=>'pending','attempts'=>0,
            'next_attempt_at'=>$now,'created_at'=>$now,'updated_at'=>$now,
        ));
    }

    private function get_event($uuid) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->events_table} WHERE event_uuid=%s LIMIT 1",$uuid));
    }

    private function event_exists($uuid) {
        return (bool)$this->get_event($uuid);
    }

    private function mark_processed($uuid) {
        global $wpdb;
        $wpdb->query($wpdb->prepare("UPDATE {$this->events_table} SET status='processed', processed_at=%s, updated_at=%s WHERE event_uuid=%s",current_time('mysql',true),current_time('mysql',true),$uuid));
    }

    private function mark_retry($uuid,$error) {
        global $wpdb;
        $event = $this->get_event($uuid);
        $attempts = $event ? ((int)$event->attempts + 1) : 1;
        $status = $attempts >= self::MAX_ATTEMPTS ? 'failed' : 'pending';
        $delay = min(3600, 60 * (2 ** max(0,$attempts-1)));
        $next = gmdate('Y-m-d H:i:s',time()+$delay);
        $wpdb->query($wpdb->prepare("UPDATE {$this->events_table} SET status=%s, attempts=%d, next_attempt_at=%s, last_error=%s, updated_at=%s WHERE event_uuid=%s",$status,$attempts,$next,$error,current_time('mysql',true),$uuid));
    }

    public function process_recovery_queue() {
        global $wpdb;
        $rows = $wpdb->get_results("SELECT * FROM {$this->events_table} WHERE status='pending' AND next_attempt_at <= UTC_TIMESTAMP() ORDER BY id ASC LIMIT 20");
        foreach ($rows as $event) {
            $this->retry_event($event);
        }
    }

    private function retry_event($event) {
        $actor = (int)$event->actor_wp_id;
        $target = (int)$event->target_wp_id;
        if ($actor < 1 || $target < 1) return;
        if (!$this->acquire_pair_lock($actor,$target)) return;

        try {
            if ($event->origin === self::ORIGIN_WORDPRESS) {
                // WordPress-origin events represent failed projections. Reconcile
                // from current canonical BuddyBoss state rather than replaying a
                // stale mutation.
                $this->reconcile_pair($actor,$target,$event->event_uuid);
                $this->mark_processed($event->event_uuid);
                return;
            }

            $result = $this->process_external_operation($event->operation,$actor,$target,$event->event_uuid);
            if (is_wp_error($result)) {
                $this->mark_retry($event->event_uuid,$result->get_error_message());
                return;
            }
            $this->mark_processed($event->event_uuid);
        } catch (Throwable $e) {
            $this->mark_retry($event->event_uuid,$e->getMessage());
        } finally {
            $this->release_pair_lock($actor,$target);
        }
    }

    private function reconcile_pair($actor,$target,$uuid) {
        $connection = function_exists('friends_check_friendship_status') ? friends_check_friendship_status($actor,$target) : '';
        $state = 'none';
        $requested_by = 0;
        if ($connection === 'is_friend') $state = 'connected';
        elseif ($connection === 'pending') { $state='requested'; $requested_by=$actor; }
        elseif ($connection === 'awaiting_response') { $state='requested'; $requested_by=$target; }

        $blocked_ab = $this->bb_block_exists_direction($actor,$target);
        $blocked_ba = $this->bb_block_exists_direction($target,$actor);
        $follow_ab = $this->bb_follow_exists($actor,$target);
        $follow_ba = $this->bb_follow_exists($target,$actor);

        if ($blocked_ab || $blocked_ba) {
            $state='none'; $requested_by=0; $follow_ab=false; $follow_ba=false;
        }

        $this->save_ledger($actor,$target,array(
            'connection_state'=>$state,'requested_by'=>$requested_by,
            'follow_a_to_b'=>$follow_ab?1:0,'follow_b_to_a'=>$follow_ba?1:0,
            'block_a_to_b'=>$blocked_ab?1:0,'block_b_to_a'=>$blocked_ba?1:0,
            'streams_follow_a_to_b'=>$follow_ab?1:0,'streams_follow_b_to_a'=>$follow_ba?1:0,
            'socials_connection_projection'=>$state,
        ),$uuid);

        $this->project_social_connection($actor,$target,$state,$uuid);
        if (!$blocked_ab && !$blocked_ba) {
            $this->project_streams_follow($actor,$target,$follow_ab,$uuid);
            $this->project_streams_follow($target,$actor,$follow_ba,$uuid);
        }
    }

    /* --------------------------------------------------------------------- */
    /* DB / identity / locks                                                  */
    /* --------------------------------------------------------------------- */

    private function external_db($platform) {
        $this->load_db_helpers();
        if ($platform === 'streams' && function_exists('get_wowonder_db')) return get_wowonder_db();
        if ($platform === 'socials' && function_exists('get_qd_db_conn')) return get_qd_db_conn();
        return false;
    }

    private function load_db_helpers() {
        $root = defined('ABSPATH') ? rtrim(ABSPATH,'/') : dirname(__DIR__,2);
        $file = $root . '/shared/db_helpers.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }

    private function external_id($wp_id,$platform) {
        global $wpdb;
        $key = $platform === 'streams' ? 'wo_user_id' : 'qd_user_id';
        $ids = $wpdb->get_col($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key=%s AND meta_value=%s",$key,(string)$wp_id));
        // The previous implementation incorrectly assumed external IDs equal WP IDs.
        // Here we read the actual external ID from the user's metadata.
        $value = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id=%d AND meta_key=%s LIMIT 1",$wp_id,$key));
        return $value !== null && ctype_digit((string)$value) ? (int)$value : 0;
    }

    private function resolve_external_to_wp($platform,$external_id) {
        global $wpdb;
        $key = $platform === 'streams' ? 'wo_user_id' : 'qd_user_id';
        $rows = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT user_id FROM {$wpdb->usermeta} WHERE meta_key=%s AND meta_value=%s",$key,(string)$external_id));
        return count($rows) === 1 ? (int)$rows[0] : 0;
    }

    private function pair($a,$b) {
        $a=(int)$a; $b=(int)$b;
        return $a < $b ? array($a,$b) : array($b,$a);
    }

    private function directional_follow_field($actor,$target) {
        list($a,$b)=$this->pair($actor,$target);
        return ((int)$actor===$a) ? 'follow_a_to_b' : 'follow_b_to_a';
    }

    private function directional_stream_field($actor,$target) {
        list($a,$b)=$this->pair($actor,$target);
        return ((int)$actor===$a) ? 'streams_follow_a_to_b' : 'streams_follow_b_to_a';
    }

    private function directional_social_field($actor,$target) {
        list($a,$b)=$this->pair($actor,$target);
        return ((int)$actor===$a) ? 'socials_follow_a_to_b' : 'socials_follow_b_to_a';
    }

    private function directional_block_field($actor,$target) {
        list($a,$b)=$this->pair($actor,$target);
        return ((int)$actor===$a) ? 'block_a_to_b' : 'block_b_to_a';
    }

    private function bb_follow_exists($actor,$target) {
        return function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor));
    }

    private function bb_block_exists_direction($actor,$target) {
        if (!class_exists('BP_Moderation') || !class_exists('BP_Moderation_Members')) return false;
        $moderation = new BP_Moderation($target, BP_Moderation_Members::$moderation_type, $actor);
        return !empty($moderation->id) && empty($moderation->user_report);
    }

    private function bb_is_blocked($actor,$target) {
        return $this->bb_block_exists_direction($actor,$target) || $this->bb_block_exists_direction($target,$actor);
    }

    private function find_friendship_id_for_accept($actor,$target) {
        if (!function_exists('friends_get_friendship_id')) return 0;
        $id=(int)friends_get_friendship_id($target,$actor);
        if (!$id) $id=(int)friends_get_friendship_id($actor,$target);
        return $id;
    }

    private function resolve_table($db,$candidates) {
        foreach ($candidates as $table) {
            if ($this->table_exists($db,$table)) return $table;
        }
        return '';
    }

    private function table_exists($db,$table) {
        $stmt=$db->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=?");
        if (!$stmt) return false;
        $stmt->bind_param('s',$table);
        $stmt->execute();
        $count=0;
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return (int)$count===1;
    }

    private function table_exists_wp($table) {
        global $wpdb;
        return (bool)$wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s",$table));
    }

    private function row_exists_any($db,$table,$following,$follower) {
        $stmt=$db->prepare("SELECT COUNT(*) FROM `{$table}` WHERE `following_id`=? AND `follower_id`=?");
        if (!$stmt) return false;
        $stmt->bind_param('ii',$following,$follower);
        $stmt->execute();
        $count=0;
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return (int)$count>0;
    }

    private function row_exists($db,$table,$following,$follower,$active) {
        $stmt=$db->prepare("SELECT COUNT(*) FROM `{$table}` WHERE `following_id`=? AND `follower_id`=? AND `active`=?");
        if (!$stmt) return false;
        $stmt->bind_param('iii',$following,$follower,$active);
        $stmt->execute();
        $count=0;
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return (int)$count>0;
    }

    private function row_exists_block($db,$table,$blocker,$blocked) {
        $stmt=$db->prepare("SELECT COUNT(*) FROM `{$table}` WHERE `blocker`=? AND `blocked`=?");
        if (!$stmt) return false;
        $stmt->bind_param('ii',$blocker,$blocked);
        $stmt->execute();
        $count=0;
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return (int)$count>0;
    }

    private function external_database_name($platform) {
        $db=$this->external_db($platform);
        if (!$db) return '';
        $r=$db->query('SELECT DATABASE()');
        return $r ? $r->fetch_row()[0] : '';
    }

    private function acquire_pair_lock($a,$b) {
        global $wpdb;
        $pair=$this->pair($a,$b);
        $name='bzj_pair_'.$pair[0].'_'.$pair[1];
        if (isset($this->held_locks[$name])) {
            $this->held_locks[$name]++;
            return true;
        }
        $result=$wpdb->get_var($wpdb->prepare("SELECT GET_LOCK(%s,%d)",$name,self::LOCK_TIMEOUT));
        if ((int)$result!==1) return false;
        $this->held_locks[$name]=1;
        return true;
    }

    private function release_pair_lock($a,$b) {
        global $wpdb;
        $pair=$this->pair($a,$b);
        $name='bzj_pair_'.$pair[0].'_'.$pair[1];
        if (!isset($this->held_locks[$name])) return;
        $this->held_locks[$name]--;
        if ($this->held_locks[$name]<=0) {
            unset($this->held_locks[$name]);
            $wpdb->get_var($wpdb->prepare("SELECT RELEASE_LOCK(%s)",$name));
        }
    }

    private function push_context($context) { $this->context_stack[]=$context; }
    private function pop_context() { array_pop($this->context_stack); }
    private function in_internal_context() { return !empty($this->context_stack); }

    private function is_uuid($uuid) {
        return (bool)preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',(string)$uuid);
    }
}

BZJ_Connections_Sync::instance();
