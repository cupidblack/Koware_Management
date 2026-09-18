<?php
/**
 * Plugin Name: BZJ Connections Synchronization Control Plane
 * Description: Canonical Buzzjuice relationship control plane. WordPress/BuddyBoss is the source of truth; Streams and Socials are synchronized projections.
 * Version: 8.4.1
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
    const VERSION = '8.4.1';
    const REST_NAMESPACE = 'bzj/v6';
    const REST_ROUTE = '/connection-management';
    const LEDGER_TABLE = 'bzj_relationships';
    const EVENTS_TABLE = 'bzj_relationship_events';
    const LOG_DIR = '/data/logs';
    const LOG_FILE = 'bzj-connections-sync.log';
    const STREAMS_LOG_FILE = 'bzj-streams-sync.log';
    const SOCIALS_LOG_FILE = 'bzj-socials-sync.log';
    const LOCK_TIMEOUT = 15;
    const EXTERNAL_TIMESTAMP_WINDOW = 300;
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

    private static $instance = null;
    private $ledger_table = '';
    private $events_table = '';
    private $context_stack = array();
    private $held_locks = array();

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        global $wpdb;
        $this->ledger_table = $wpdb->prefix . self::LEDGER_TABLE;
        $this->events_table = $wpdb->prefix . self::EVENTS_TABLE;

        add_action('rest_api_init', array($this, 'register_rest_routes'));

        // BuddyBoss connection lifecycle. These are the hooks used by the working v8.1 implementation.
        add_action('friends_friendship_requested', array($this, 'bb_connection_requested'), 20, 4);
        add_action('friends_friendship_accepted', array($this, 'bb_connection_accepted'), 20, 4);
        add_action('friends_friendship_rejected', array($this, 'bb_connection_rejected'), 20, 2);
        add_action('friends_friendship_withdrawn', array($this, 'bb_connection_withdrawn'), 20, 2);
        add_action('friends_friendship_whithdrawn', array($this, 'bb_connection_withdrawn'), 20, 2);
        add_action('friends_friendship_deleted', array($this, 'bb_connection_deleted'), 1, 3);
        add_action('friends_friendship_post_delete', array($this, 'bb_connection_post_deleted'), 20, 2);

        // BuddyBoss follow lifecycle.
        add_action('bp_start_following', array($this, 'bb_follow_started'), 20, 1);
        add_action('bp_stop_following', array($this, 'bb_follow_stopped'), 20, 1);
        add_action('bp_follow_start_following', array($this, 'bb_follow_started'), 20, 1);
        add_action('bp_follow_stop_following', array($this, 'bb_follow_stopped'), 20, 1);

        // BuddyBoss 8.1-style member moderation lifecycle. Do not replace these with the 8.4-only names.
        add_action('bp_moderation_after_save', array($this, 'bb_moderation_saved'), 20, 1);
        add_action('bb_moderation_after_delete', array($this, 'bb_moderation_deleted'), 20, 1);

        add_action(self::CRON_HOOK, array($this, 'process_recovery_queue'));
        add_action(self::CRON_HOOK . '_reconcile', array($this, 'reconcile_recent'));

        $this->maybe_install_schema();
        $this->maybe_schedule_cron();
        $this->verify_external_databases(true);
    }

    /* -------------------------------------------------------------------------
     * Bootstrap / schema / logging
     * ---------------------------------------------------------------------- */
    private function maybe_install_schema() {
        $installed = get_option('bzj_connections_sync_schema_version', '');
        if ($installed === self::VERSION) {
            return;
        }
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $sql_ledger = "CREATE TABLE {$this->ledger_table} (
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

        $sql_events = "CREATE TABLE {$this->events_table} (
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

        dbDelta($sql_ledger);
        dbDelta($sql_events);
        update_option('bzj_connections_sync_schema_version', self::VERSION, false);
    }

    private function maybe_schedule_cron() {
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_event(time() + 300, 'hourly', self::CRON_HOOK);
        }
        if (!wp_next_scheduled(self::CRON_HOOK . '_reconcile')) {
            wp_schedule_event(time() + 600, 'hourly', self::CRON_HOOK . '_reconcile');
        }
    }

    private function log($message, $context = array(), $platform = '') {
        $dir = trailingslashit(ABSPATH) . ltrim(self::LOG_DIR, '/');
        if (!is_dir($dir)) {
            wp_mkdir_p($dir);
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            return;
        }
        $file = self::LOG_FILE;
        if ($platform === self::ORIGIN_STREAMS) {
            $file = self::STREAMS_LOG_FILE;
        } elseif ($platform === self::ORIGIN_SOCIALS) {
            $file = self::SOCIALS_LOG_FILE;
        }
        $entry = array(
            'time' => gmdate('c'),
            'message' => (string) $message,
            'context' => $context,
        );
        @file_put_contents(trailingslashit($dir) . $file, wp_json_encode($entry, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private function external_log($platform, $operation, $origin, $actor_wp, $target_wp, $actor_ext, $target_ext, $db, $table, $sql_op, $affected, $verified, $event_uuid = '', $error = '') {
        $this->log('External DB operation', array(
            'platform' => $platform,
            'operation' => $operation,
            'origin' => $origin,
            'actor_wp_id' => (int) $actor_wp,
            'target_wp_id' => (int) $target_wp,
            'actor_external_id' => (int) $actor_ext,
            'target_external_id' => (int) $target_ext,
            'database' => $this->db_name($db),
            'table' => $table,
            'sql_operation' => $sql_op,
            'affected_rows' => (int) $affected,
            'verified' => (bool) $verified,
            'event_uuid' => $event_uuid,
            'error' => $error,
        ), $platform);
    }

    private function db_name($db) {
        if (!$db instanceof mysqli) {
            return '';
        }
        $name = '';
        try {
            $result = $db->query('SELECT DATABASE() AS db_name');
            if ($result) {
                $row = $result->fetch_assoc();
                $name = isset($row['db_name']) ? (string) $row['db_name'] : '';
                $result->free();
            }
        } catch (Throwable $e) {
            $name = '';
        }
        return $name;
    }

    /* -------------------------------------------------------------------------
     * REST endpoint and HMAC authentication
     * ---------------------------------------------------------------------- */
    public function register_rest_routes() {
        register_rest_route(self::REST_NAMESPACE, self::REST_ROUTE, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'rest_command'),
            'permission_callback' => '__return_true',
        ));
        register_rest_route(self::REST_NAMESPACE, self::REST_ROUTE, array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'rest_status'),
            'permission_callback' => function () { return current_user_can('manage_options'); },
        ));
        register_rest_route(self::REST_NAMESPACE, '/connection-management/diagnostics', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'rest_diagnostics'),
            'permission_callback' => function () { return current_user_can('manage_options'); },
        ));
    }

    public function rest_status(WP_REST_Request $request) {
        $a = absint($request->get_param('user_a'));
        $b = absint($request->get_param('user_b'));
        if ($a < 1 || $b < 1 || $a === $b) {
            return new WP_Error('bzj_invalid_pair', 'Two different valid WordPress user IDs are required.', array('status' => 400));
        }
        return rest_ensure_response(array('success' => true, 'version' => self::VERSION, 'relationship' => $this->get_ledger($a, $b)));
    }

    public function rest_diagnostics(WP_REST_Request $request) {
        $databases = $this->verify_external_databases(false);
        global $wp_filter;
        $log_dir = trailingslashit(ABSPATH) . ltrim(self::LOG_DIR, '/');
        return rest_ensure_response(array(
            'plugin_version' => self::VERSION,
            'route' => home_url('/wp-json/' . self::REST_NAMESPACE . self::REST_ROUTE),
            'cron' => array(
                'recovery_next' => ($t = wp_next_scheduled(self::CRON_HOOK)) ? gmdate('c', $t) : null,
                'reconcile_next' => ($t2 = wp_next_scheduled(self::CRON_HOOK . '_reconcile')) ? gmdate('c', $t2) : null,
            ),
            'logs' => array(
                'directory' => $log_dir,
                'writable' => is_dir($log_dir) ? is_writable($log_dir) : false,
                'connection' => trailingslashit($log_dir) . self::LOG_FILE,
                'streams' => trailingslashit($log_dir) . self::STREAMS_LOG_FILE,
                'socials' => trailingslashit($log_dir) . self::SOCIALS_LOG_FILE,
            ),
            'databases' => $databases,
            'hooks' => array(
                'friendship_requested' => has_action('friends_friendship_requested', array($this, 'bb_connection_requested')) !== false,
                'friendship_accepted' => has_action('friends_friendship_accepted', array($this, 'bb_connection_accepted')) !== false,
                'friendship_rejected' => has_action('friends_friendship_rejected', array($this, 'bb_connection_rejected')) !== false,
                'friendship_withdrawn' => has_action('friends_friendship_withdrawn', array($this, 'bb_connection_withdrawn')) !== false,
                'friendship_deleted' => has_action('friends_friendship_deleted', array($this, 'bb_connection_deleted')) !== false,
                'friendship_post_deleted' => has_action('friends_friendship_post_delete', array($this, 'bb_connection_post_deleted')) !== false,
                'follow_started' => has_action('bp_start_following', array($this, 'bb_follow_started')) !== false,
                'follow_stopped' => has_action('bp_stop_following', array($this, 'bb_follow_stopped')) !== false,
                'moderation_saved' => has_action('bp_moderation_after_save', array($this, 'bb_moderation_saved')) !== false,
                'moderation_deleted' => has_action('bb_moderation_after_delete', array($this, 'bb_moderation_deleted')) !== false,
            ),
        ));
    }

    public function rest_command(WP_REST_Request $request) {
        $raw = $request->get_body();
        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            return new WP_Error('bzj_invalid_json', 'Malformed JSON.', array('status' => 400));
        }
        $origin = sanitize_key(isset($payload['origin']) ? $payload['origin'] : '');
        $operation = sanitize_key(isset($payload['operation']) ? $payload['operation'] : '');
        $event_uuid = sanitize_text_field(isset($payload['event_id']) ? $payload['event_id'] : '');
        if (!in_array($origin, array(self::ORIGIN_STREAMS, self::ORIGIN_SOCIALS), true)) {
            return new WP_Error('bzj_invalid_origin', 'Origin must be streams or socials.', array('status' => 400));
        }
        if (!in_array($operation, array(self::OP_CONNECTION_REQUEST,self::OP_CONNECTION_ACCEPT,self::OP_CONNECTION_REJECT,self::OP_CONNECTION_WITHDRAW,self::OP_CONNECTION_REMOVE,self::OP_FOLLOW,self::OP_UNFOLLOW,self::OP_BLOCK,self::OP_UNBLOCK), true)) {
            return new WP_Error('bzj_invalid_operation', 'Unsupported synchronization operation.', array('status' => 400));
        }
        if (!$this->is_uuid($event_uuid)) {
            return new WP_Error('bzj_invalid_event', 'A valid event UUID is required.', array('status' => 400));
        }
        $auth = $this->authenticate_external_request($request, $raw, $origin);
        if (is_wp_error($auth)) {
            return $auth;
        }

        $actor_external = absint(isset($payload['actor_id']) ? $payload['actor_id'] : 0);
        $target_external = absint(isset($payload['target_id']) ? $payload['target_id'] : 0);
        if ($actor_external < 1 || $target_external < 1 || $actor_external === $target_external) {
            return new WP_Error('bzj_invalid_users', 'Actor and target must be different valid IDs.', array('status' => 400));
        }
        if (isset($payload['authenticated_id']) && absint($payload['authenticated_id']) !== $actor_external) {
            return new WP_Error('bzj_actor_mismatch', 'Authenticated actor does not match actor_id.', array('status' => 403));
        }
        $actor_wp = $this->map_external_to_wp($origin, $actor_external);
        $target_wp = $this->map_external_to_wp($origin, $target_external);
        if ($actor_wp < 1 || $target_wp < 1 || $actor_wp === $target_wp) {
            return new WP_Error('bzj_mapping_error', 'Could not map the external users to WordPress users.', array('status' => 409));
        }

        if ($this->event_exists($event_uuid)) {
            return rest_ensure_response(array('success'=>true,'status'=>'already_processed','event_id'=>$event_uuid,'relationship'=>$this->get_ledger($actor_wp,$target_wp)));
        }
        if (!$this->acquire_pair_lock($actor_wp, $target_wp)) {
            return new WP_Error('bzj_pair_locked', 'Relationship pair is busy; retry the request.', array('status' => 503));
        }
        try {
            $this->insert_event($event_uuid, $origin, $operation, $actor_wp, $target_wp, $payload);
            $result = $this->process_external_operation($operation, $actor_wp, $target_wp, $event_uuid);
            if (is_wp_error($result)) {
                $this->mark_event_retry($event_uuid, $result->get_error_message());
                return $result;
            }
            $this->mark_event_processed($event_uuid);
            return rest_ensure_response(array('success'=>true,'status'=>'processed','event_id'=>$event_uuid,'relationship'=>$this->get_ledger($actor_wp,$target_wp)));
        } catch (Throwable $e) {
            $this->mark_event_retry($event_uuid, $e->getMessage());
            $this->log('REST command exception', array('event_id'=>$event_uuid,'origin'=>$origin,'operation'=>$operation,'actor_wp'=>$actor_wp,'target_wp'=>$target_wp,'error'=>$e->getMessage()));
            return new WP_Error('bzj_internal_error', 'Synchronization command failed.', array('status'=>500,'event_id'=>$event_uuid));
        } finally {
            $this->release_pair_lock($actor_wp, $target_wp);
        }
    }

    private function authenticate_external_request(WP_REST_Request $request, $raw, $origin) {
        $timestamp = $request->get_header('X-BZJ-Timestamp');
        $signature = $request->get_header('X-BZJ-Signature');
        if (!$timestamp || !$signature || !ctype_digit((string)$timestamp)) {
            return new WP_Error('bzj_auth_missing', 'Missing authentication headers.', array('status'=>401));
        }
        $timestamp = (int)$timestamp;
        if (abs(time() - $timestamp) > self::EXTERNAL_TIMESTAMP_WINDOW) {
            return new WP_Error('bzj_auth_expired', 'Request timestamp is outside the allowed window.', array('status'=>401));
        }
        $secret = $this->platform_secret($origin);
        if (!$secret) {
            return new WP_Error('bzj_auth_unavailable', 'Synchronization secret is unavailable.', array('status'=>503));
        }
        $expected = hash_hmac('sha256', $timestamp . '.' . $raw, $secret);
        if (!hash_equals($expected, trim($signature))) {
            return new WP_Error('bzj_auth_invalid', 'Invalid request signature.', array('status'=>401));
        }
        return true;
    }

    private function platform_secret($platform) {
        $constant = $platform === self::ORIGIN_STREAMS ? 'BZJ_STREAMS_SYNC_SECRET' : 'BZJ_SOCIALS_SYNC_SECRET';
        if (defined($constant) && constant($constant)) {
            return (string)constant($constant);
        }
        $env = $platform === self::ORIGIN_STREAMS ? 'BZJ_STREAMS_SYNC_SECRET' : 'BZJ_SOCIALS_SYNC_SECRET';
        $secret = getenv($env);
        if ($secret) {
            return $secret;
        }
        return $this->sso_secret();
    }

    private function sso_secret() {
        if (function_exists('bzj_get_sso_secret')) {
            $secret = bzj_get_sso_secret();
            if ($secret) return $secret;
        }
        if (defined('BUZZ_SSO_SECRET') && BUZZ_SSO_SECRET) return BUZZ_SSO_SECRET;
        $secret = getenv('BUZZ_SSO_SECRET');
        return $secret ? $secret : '';
    }

    /* -------------------------------------------------------------------------
     * External-origin operations. BuddyBoss is always mutated first.
     * ---------------------------------------------------------------------- */
    private function process_external_operation($operation, $actor, $target, $event_uuid) {
        switch ($operation) {
            case self::OP_CONNECTION_REQUEST: return $this->bb_request_from_external($actor,$target,$event_uuid);
            case self::OP_CONNECTION_ACCEPT: return $this->bb_accept_from_external($actor,$target,$event_uuid);
            case self::OP_CONNECTION_REJECT: return $this->bb_reject_from_external($actor,$target,$event_uuid);
            case self::OP_CONNECTION_WITHDRAW: return $this->bb_withdraw_from_external($actor,$target,$event_uuid);
            case self::OP_CONNECTION_REMOVE: return $this->bb_remove_from_external($actor,$target,$event_uuid);
            case self::OP_FOLLOW: return $this->bb_follow_from_external($actor,$target,$event_uuid);
            case self::OP_UNFOLLOW: return $this->bb_unfollow_from_external($actor,$target,$event_uuid);
            case self::OP_BLOCK: return $this->bb_block_from_external($actor,$target,$event_uuid);
            case self::OP_UNBLOCK: return $this->bb_unblock_from_external($actor,$target,$event_uuid);
        }
        return new WP_Error('bzj_unknown_operation', 'Unknown synchronization operation.');
    }

    private function bb_request_from_external($actor,$target,$event_uuid) {
        if ($this->bb_is_blocked($actor,$target)) return new WP_Error('bzj_blocked','A blocked relationship cannot create a connection.');
        if (!function_exists('friends_add_friend') || !function_exists('friends_check_friendship_status')) return new WP_Error('bzj_bb_unavailable','BuddyBoss connection functions are unavailable.');
        $status = friends_check_friendship_status($actor,$target);
        if ($status === 'is_friend' || $status === 'pending') return true;
        if ($status === 'awaiting_response') return new WP_Error('bzj_reverse_request','The other user already has a pending connection request.');
        $ok = friends_add_friend($actor,$target);
        return $ok ? true : new WP_Error('bzj_bb_request_failed','BuddyBoss rejected the connection request.');
    }

    private function find_friendship_id($actor,$target) {
        if (!function_exists('friends_get_friendship_id')) return 0;
        $id = (int)friends_get_friendship_id($target,$actor);
        if (!$id) $id = (int)friends_get_friendship_id($actor,$target);
        return $id;
    }

    private function bb_accept_from_external($actor,$target,$event_uuid) {
        if (!function_exists('friends_accept_friendship')) return new WP_Error('bzj_bb_unavailable','BuddyBoss connection functions are unavailable.');
        $id = $this->find_friendship_id($actor,$target);
        if (!$id) return new WP_Error('bzj_bb_request_missing','No pending BuddyBoss connection request was found.');
        $ok = friends_accept_friendship($id);
        if (!$ok && function_exists('friends_check_friendship_status') && friends_check_friendship_status($actor,$target) !== 'is_friend') return new WP_Error('bzj_bb_accept_failed','BuddyBoss could not accept the connection request.');
        return true;
    }

    private function bb_reject_from_external($actor,$target,$event_uuid) {
        if (!function_exists('friends_reject_friendship')) return new WP_Error('bzj_bb_unavailable','BuddyBoss rejection function is unavailable.');
        $id = $this->find_friendship_id($actor,$target);
        if (!$id) return true;
        $ok = friends_reject_friendship($id);
        if (!$ok && function_exists('friends_check_friendship_status') && friends_check_friendship_status($actor,$target) !== 'not_friends') return new WP_Error('bzj_bb_reject_failed','BuddyBoss could not reject the connection request.');
        return true;
    }

    private function bb_withdraw_from_external($actor,$target,$event_uuid) {
        if (!function_exists('friends_withdraw_friendship')) return new WP_Error('bzj_bb_unavailable','BuddyBoss withdrawal function is unavailable.');
        $ok = friends_withdraw_friendship($actor,$target);
        if (!$ok && function_exists('friends_check_friendship_status') && friends_check_friendship_status($actor,$target) !== 'not_friends') return new WP_Error('bzj_bb_withdraw_failed','BuddyBoss could not withdraw the connection request.');
        return true;
    }

    private function bb_remove_from_external($actor,$target,$event_uuid) {
        if (!function_exists('friends_remove_friend') || !function_exists('friends_check_friendship_status')) return new WP_Error('bzj_bb_unavailable','BuddyBoss connection functions are unavailable.');
        if (friends_check_friendship_status($actor,$target) === 'is_friend') {
            $ok = friends_remove_friend($actor,$target); if (!$ok) $ok = friends_remove_friend($target,$actor);
            if (!$ok && friends_check_friendship_status($actor,$target) === 'is_friend') return new WP_Error('bzj_bb_remove_failed','BuddyBoss could not remove the connection.');
        }
        return true;
    }

    private function bb_follow_from_external($actor,$target,$event_uuid) {
        if ($this->bb_is_blocked($actor,$target)) return new WP_Error('bzj_blocked','A blocked relationship cannot create a follow.');
        if (!function_exists('bp_start_following')) return new WP_Error('bzj_bb_follow_unavailable','BuddyBoss follow function is unavailable.');
        if (function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) return true;
        $result = bp_start_following(array('leader_id'=>$target,'follower_id'=>$actor));
        if (is_wp_error($result)) return $result;
        if ($result === false && function_exists('bp_is_following') && !bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) return new WP_Error('bzj_bb_follow_failed','BuddyBoss rejected the follow operation.');
        return true;
    }

    private function bb_unfollow_from_external($actor,$target,$event_uuid) {
        if (!function_exists('bp_stop_following')) return new WP_Error('bzj_bb_unfollow_unavailable','BuddyBoss unfollow function is unavailable.');
        if (function_exists('bp_is_following') && !bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) return true;
        $result = bp_stop_following(array('leader_id'=>$target,'follower_id'=>$actor));
        if (is_wp_error($result)) return $result;
        if ($result === false && function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) return new WP_Error('bzj_bb_unfollow_failed','BuddyBoss rejected the unfollow operation.');
        return true;
    }

    private function bb_block_from_external($actor,$target,$event_uuid) {
        if (!class_exists('BP_Moderation') || !class_exists('BP_Moderation_Members')) return new WP_Error('bzj_bb_moderation_unavailable','BuddyBoss moderation classes are unavailable.');
        if (!$this->bb_block_exists_direction($actor,$target)) {
            $moderation = new BP_Moderation($target,BP_Moderation_Members::$moderation_type,$actor);
            $moderation->user_report = 0;
            $moderation->content = '';
            $this->push_context(array('event_uuid'=>$event_uuid,'origin'=>'external','operation'=>self::OP_BLOCK,'actor'=>$actor,'target'=>$target));
            try { if (!$moderation->save()) return new WP_Error('bzj_bb_block_failed','BuddyBoss could not create the member block.'); } finally { $this->pop_context(); }
        }
        $this->enforce_block_on_buddyboss($actor,$target,$event_uuid);
        $this->save_ledger($actor,$target,array($this->directional_block_field($actor,$target)=>1,'connection_state'=>'none','requested_by'=>0,'follow_a_to_b'=>0,'follow_b_to_a'=>0),$event_uuid);
        $this->project_block_pair($actor,$target,true,$event_uuid);
        return true;
    }

    private function bb_unblock_from_external($actor,$target,$event_uuid) {
        if (!class_exists('BP_Moderation') || !class_exists('BP_Moderation_Members')) return new WP_Error('bzj_bb_moderation_unavailable','BuddyBoss moderation classes are unavailable.');
        $moderation = new BP_Moderation($target,BP_Moderation_Members::$moderation_type,$actor);
        if (!empty($moderation->id) && empty($moderation->user_report)) {
            $this->push_context(array('event_uuid'=>$event_uuid,'origin'=>'external','operation'=>self::OP_UNBLOCK,'actor'=>$actor,'target'=>$target));
            try { if (!$moderation->delete(false)) return new WP_Error('bzj_bb_unblock_failed','BuddyBoss could not remove the member block.'); } finally { $this->pop_context(); }
        }
        $this->save_ledger($actor,$target,array($this->directional_block_field($actor,$target)=>0),$event_uuid);
        $this->project_external_block($actor,$target,false,self::ORIGIN_STREAMS,$event_uuid);
        $this->project_external_block($actor,$target,false,self::ORIGIN_SOCIALS,$event_uuid);
        return true;
    }

    /* -------------------------------------------------------------------------
     * BuddyBoss canonical hooks
     * ---------------------------------------------------------------------- */
    public function bb_connection_requested($friendship_id,$initiator,$friend,$friendship=null) {
        if ($this->in_internal_context()) return;
        $this->handle_bb_connection_event('requested',absint($initiator),absint($friend),(int)$friendship_id);
    }
    public function bb_connection_accepted($friendship_id,$initiator,$friend,$friendship=null) {
        if ($this->in_internal_context()) return;
        $this->handle_bb_connection_event('accepted',absint($initiator),absint($friend),(int)$friendship_id);
    }
    public function bb_connection_rejected($friendship_id,$friendship=null) {
        if ($this->in_internal_context()) return;
        if (!is_object($friendship) && $friendship_id && class_exists('BP_Friends_Friendship')) $friendship = new BP_Friends_Friendship($friendship_id);
        if (!is_object($friendship)) return;
        $this->handle_bb_connection_event('rejected',absint($friendship->initiator_user_id),absint($friendship->friend_user_id),(int)$friendship_id);
    }
    public function bb_connection_withdrawn($friendship_id,$friendship=null) {
        if ($this->in_internal_context()) return;
        if (!is_object($friendship) && $friendship_id && class_exists('BP_Friends_Friendship')) $friendship = new BP_Friends_Friendship($friendship_id,true,false);
        if (!is_object($friendship)) return;
        $this->handle_bb_connection_event('withdrawn',absint($friendship->initiator_user_id),absint($friendship->friend_user_id),(int)$friendship_id);
    }
    public function bb_connection_deleted($friendship_id,$initiator,$friend,$unused=null) {
        if ($this->in_internal_context()) return;
        $this->handle_bb_connection_event('deleted',absint($initiator),absint($friend),(int)$friendship_id);
    }
    public function bb_connection_post_deleted($initiator,$friend) {
        if ($this->in_internal_context()) return;
        $this->handle_bb_connection_event('post_deleted',absint($initiator),absint($friend),0);
    }

    private function handle_bb_connection_event($event,$initiator,$friend,$friendship_id=0) {
        if ($initiator<1 || $friend<1 || $initiator===$friend) return;
        $uuid = wp_generate_uuid4();
        if (!$this->acquire_pair_lock($initiator,$friend)) {
            $this->insert_event($uuid,self::ORIGIN_WORDPRESS,'connection_'.$event,$initiator,$friend,array('friendship_id'=>$friendship_id));
            $this->mark_event_retry($uuid,'Pair lock busy.');
            return;
        }
        try {
            $this->insert_event($uuid,self::ORIGIN_WORDPRESS,'connection_'.$event,$initiator,$friend,array('friendship_id'=>$friendship_id));
            $state='none'; $requested_by=0;
            if ($event==='requested') { $state='requested'; $requested_by=$initiator; }
            elseif ($event==='accepted') { $state='connected'; }
            elseif (in_array($event,array('deleted','post_deleted','rejected','withdrawn'),true)) {
                $bb=$this->buddyboss_connection_state($initiator,$friend);
                if ($event==='deleted' || $event==='post_deleted') { $state='none'; $requested_by=0; }
                elseif ($bb==='is_friend') $state='connected';
                elseif ($bb==='pending' || $bb==='awaiting_response') { $state='requested'; $requested_by=$this->get_bb_requester($initiator,$friend); }
            }
            $this->save_ledger($initiator,$friend,array('connection_state'=>$state,'requested_by'=>$requested_by),$uuid);
            $this->project_social_connection($initiator,$friend,$state,$uuid);
            $this->mark_event_processed($uuid);
        } catch (Throwable $e) {
            $this->mark_event_retry($uuid,$e->getMessage());
            $this->log('BuddyBoss connection event failed',array('event'=>$event,'actor'=>$initiator,'target'=>$friend,'error'=>$e->getMessage(),'event_uuid'=>$uuid));
        } finally { $this->release_pair_lock($initiator,$friend); }
    }

    public function bb_follow_started($follow) { $this->handle_bb_follow_event(self::OP_FOLLOW,$follow); }
    public function bb_follow_stopped($follow) { $this->handle_bb_follow_event(self::OP_UNFOLLOW,$follow); }
    private function handle_bb_follow_event($operation,$follow) {
        if ($this->in_internal_context() || !is_object($follow)) return;
        $actor=absint(isset($follow->follower_id)?$follow->follower_id:0); $target=absint(isset($follow->leader_id)?$follow->leader_id:0);
        if ($actor<1 || $target<1 || $actor===$target) return;
        $uuid=wp_generate_uuid4();
        if (!$this->acquire_pair_lock($actor,$target)) { $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$operation,$actor,$target); $this->mark_event_retry($uuid,'Pair lock busy.'); return; }
        try {
            $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$operation,$actor,$target);
            if ($operation===self::OP_FOLLOW && $this->bb_is_blocked($actor,$target)) { $this->set_follow_state($actor,$target,false,$uuid); $this->mark_event_processed($uuid); return; }
            $this->set_follow_state($actor,$target,$operation===self::OP_FOLLOW,$uuid);
            // Ordinary BB follows are projected to Streams only. QuickDate's followers table is also its friend table.
            $this->project_external_follow($actor,$target,$operation===self::OP_FOLLOW,self::ORIGIN_STREAMS,$uuid);
            $this->mark_event_processed($uuid);
        } catch (Throwable $e) { $this->mark_event_retry($uuid,$e->getMessage()); $this->log('BuddyBoss follow event failed',array('operation'=>$operation,'actor'=>$actor,'target'=>$target,'error'=>$e->getMessage(),'event_uuid'=>$uuid)); }
        finally { $this->release_pair_lock($actor,$target); }
    }

    public function bb_moderation_saved($moderation) {
        if (!$this->is_user_block_moderation($moderation)) return;
        $this->handle_bb_block_event(self::OP_BLOCK,absint($moderation->user_id),absint($moderation->item_id),absint(isset($moderation->id)?$moderation->id:0));
    }
    public function bb_moderation_deleted($moderation) {
        if (!$this->is_user_block_moderation($moderation)) return;
        $this->handle_bb_block_event(self::OP_UNBLOCK,absint($moderation->user_id),absint($moderation->item_id),absint(isset($moderation->id)?$moderation->id:0));
    }
    private function is_user_block_moderation($moderation) {
        return is_object($moderation) && isset($moderation->item_type) && $moderation->item_type==='user' && empty($moderation->user_report) && absint($moderation->user_id)>0 && absint($moderation->item_id)>0;
    }
    private function handle_bb_block_event($operation,$actor,$target,$moderation_id=0) {
        if ($actor<1 || $target<1 || $actor===$target || $this->in_internal_context()) return;
        $uuid=wp_generate_uuid4();
        if (!$this->acquire_pair_lock($actor,$target)) { $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$operation,$actor,$target,array('moderation_id'=>$moderation_id)); $this->mark_event_retry($uuid,'Pair lock busy.'); return; }
        try {
            $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$operation,$actor,$target,array('moderation_id'=>$moderation_id));
            if ($operation===self::OP_BLOCK) {
                $this->enforce_block_on_buddyboss($actor,$target,$uuid);
                $this->save_ledger($actor,$target,array($this->directional_block_field($actor,$target)=>1,'connection_state'=>'none','requested_by'=>0,'follow_a_to_b'=>0,'follow_b_to_a'=>0),$uuid);
                $this->project_block_pair($actor,$target,true,$uuid);
            } else {
                $this->save_ledger($actor,$target,array($this->directional_block_field($actor,$target)=>0),$uuid);
                $this->project_external_block($actor,$target,false,self::ORIGIN_STREAMS,$uuid);
                $this->project_external_block($actor,$target,false,self::ORIGIN_SOCIALS,$uuid);
            }
            $this->mark_event_processed($uuid);
        } catch (Throwable $e) { $this->mark_event_retry($uuid,$e->getMessage()); $this->log('BuddyBoss block event failed',array('operation'=>$operation,'actor'=>$actor,'target'=>$target,'error'=>$e->getMessage(),'event_uuid'=>$uuid)); }
        finally { $this->release_pair_lock($actor,$target); }
    }

    private function project_block_pair($actor,$target,$blocked,$event_uuid) {
        $this->project_external_block($actor,$target,$blocked,self::ORIGIN_STREAMS,$event_uuid);
        $this->project_external_block($actor,$target,$blocked,self::ORIGIN_SOCIALS,$event_uuid);
        if ($blocked) {
            $this->project_external_follow($actor,$target,false,self::ORIGIN_STREAMS,$event_uuid);
            $this->project_external_follow($target,$actor,false,self::ORIGIN_STREAMS,$event_uuid);
            $this->project_external_follow($actor,$target,false,self::ORIGIN_SOCIALS,$event_uuid);
            $this->project_external_follow($target,$actor,false,self::ORIGIN_SOCIALS,$event_uuid);
            $this->project_social_connection($actor,$target,'none',$event_uuid);
        }
    }

    private function enforce_block_on_buddyboss($actor,$target,$event_uuid) {
        if (function_exists('friends_check_friendship_status') && function_exists('friends_remove_friend') && friends_check_friendship_status($actor,$target)==='is_friend') {
            $this->push_context(array('event_uuid'=>$event_uuid,'origin'=>self::ORIGIN_WORDPRESS,'operation'=>self::OP_CONNECTION_REMOVE,'actor'=>$actor,'target'=>$target));
            try { friends_remove_friend($actor,$target); } finally { $this->pop_context(); }
        }
        if (function_exists('bp_stop_following')) {
            foreach (array(array($actor,$target),array($target,$actor)) as $p) {
                if (function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$p[1],'follower_id'=>$p[0]))) {
                    $this->push_context(array('event_uuid'=>$event_uuid,'origin'=>self::ORIGIN_WORDPRESS,'operation'=>self::OP_UNFOLLOW,'actor'=>$p[0],'target'=>$p[1]));
                    try { bp_stop_following(array('leader_id'=>$p[1],'follower_id'=>$p[0])); } finally { $this->pop_context(); }
                }
            }
        }
    }

    /* -------------------------------------------------------------------------
     * Ledger and BuddyBoss state
     * ---------------------------------------------------------------------- */
    private function pair($a,$b) { $a=absint($a); $b=absint($b); return $a<$b?array($a,$b):array($b,$a); }
    private function get_ledger($a,$b) {
        global $wpdb; $p=$this->pair($a,$b);
        $row=$wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->ledger_table} WHERE user_a=%d AND user_b=%d LIMIT 1",$p[0],$p[1]),ARRAY_A);
        return $row ?: array('user_a'=>$p[0],'user_b'=>$p[1],'connection_state'=>'none','requested_by'=>0,'follow_a_to_b'=>0,'follow_b_to_a'=>0,'block_a_to_b'=>0,'block_b_to_a'=>0,'version'=>0,'last_event_uuid'=>'');
    }
    private function save_ledger($actor,$target,$changes,$event_uuid='') {
        global $wpdb; $p=$this->pair($actor,$target); $existing=$this->get_ledger($p[0],$p[1]); $now=current_time('mysql',true);
        $data=array('user_a'=>$p[0],'user_b'=>$p[1],
            'connection_state'=>isset($changes['connection_state'])?sanitize_key($changes['connection_state']):$existing['connection_state'],
            'requested_by'=>isset($changes['requested_by'])?absint($changes['requested_by']):absint($existing['requested_by']),
            'follow_a_to_b'=>isset($changes['follow_a_to_b'])?(int)!!$changes['follow_a_to_b']:(int)$existing['follow_a_to_b'],
            'follow_b_to_a'=>isset($changes['follow_b_to_a'])?(int)!!$changes['follow_b_to_a']:(int)$existing['follow_b_to_a'],
            'block_a_to_b'=>isset($changes['block_a_to_b'])?(int)!!$changes['block_a_to_b']:(int)$existing['block_a_to_b'],
            'block_b_to_a'=>isset($changes['block_b_to_a'])?(int)!!$changes['block_b_to_a']:(int)$existing['block_b_to_a'],
            'version'=>max(1,(int)$existing['version']+1),'last_event_uuid'=>$event_uuid?:$existing['last_event_uuid'],'updated_at'=>$now);
        if ((int)$existing['version']===0) { $data['created_at']=$now; $wpdb->insert($this->ledger_table,$data); }
        else { $wpdb->update($this->ledger_table,$data,array('user_a'=>$p[0],'user_b'=>$p[1])); }
        return $this->get_ledger($p[0],$p[1]);
    }
    private function set_follow_state($actor,$target,$active,$event_uuid='') { $p=$this->pair($actor,$target); $field=((int)$actor===$p[0])?'follow_a_to_b':'follow_b_to_a'; return $this->save_ledger($actor,$target,array($field=>$active?1:0),$event_uuid); }
    private function directional_block_field($actor,$target) { $p=$this->pair($actor,$target); return ((int)$actor===$p[0])?'block_a_to_b':'block_b_to_a'; }
    private function buddyboss_connection_state($a,$b) { return function_exists('friends_check_friendship_status')?friends_check_friendship_status($a,$b):'not_friends'; }
    private function get_bb_requester($a,$b) { $s=$this->buddyboss_connection_state($a,$b); if($s==='pending')return $a; if($s==='awaiting_response')return $b; return 0; }
    private function bb_is_blocked($a,$b) { return $this->bb_block_exists_direction($a,$b)||$this->bb_block_exists_direction($b,$a); }
    private function bb_block_exists_direction($actor,$target) {
        if ($actor<1||$target<1||$actor===$target) return false;
        if (class_exists('BP_Moderation') && class_exists('BP_Moderation_Members')) {
            $m=new BP_Moderation($target,BP_Moderation_Members::$moderation_type,$actor);
            if (!empty($m->id) && empty($m->user_report)) return true;
        }
        $l=$this->get_ledger($actor,$target); $field=$this->directional_block_field($actor,$target); return !empty($l[$field]);
    }

    /* -------------------------------------------------------------------------
     * External DB mapping / schema inspection
     * ---------------------------------------------------------------------- */
    private function load_db_helpers() {
        static $loaded=false; if($loaded)return true;
        $paths=array(trailingslashit(ABSPATH).'shared/db_helpers.php',dirname(ABSPATH).'/shared/db_helpers.php');
        foreach($paths as $file){ if(is_file($file)){ require_once $file; $loaded=true; return true; } }
        $this->log('db_helpers.php not found',array('paths_checked'=>$paths)); return false;
    }
    private function streams_db() { if(!$this->load_db_helpers()||!function_exists('get_wowonder_db'))return false; $db=get_wowonder_db(); return $db instanceof mysqli?$db:false; }
    private function socials_db() { if(!$this->load_db_helpers()||!function_exists('get_qd_db_conn'))return false; $db=get_qd_db_conn(); return $db instanceof mysqli?$db:false; }
    private function map_wp_to_external($wp_id,$platform) { $key=$platform===self::ORIGIN_STREAMS?'wo_user_id':'qd_user_id'; return absint(get_user_meta(absint($wp_id),$key,true)); }
    private function map_external_to_wp($platform,$external_id) { global $wpdb; $key=$platform===self::ORIGIN_STREAMS?'wo_user_id':'qd_user_id'; return (int)$wpdb->get_var($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key=%s AND meta_value=%s ORDER BY umeta_id ASC LIMIT 1",$key,(string)absint($external_id))); }
    private function table_exists($db,$table) { if(!$db||!$table)return false; $table=preg_replace('/[^A-Za-z0-9_]/','',$table); $r=$db->query("SHOW TABLES LIKE '".$db->real_escape_string($table)."'"); return $r instanceof mysqli_result && $r->num_rows>0; }
    private function columns($db,$table) {
        static $cache=array(); $key=spl_object_hash($db).':'.$table; if(isset($cache[$key]))return $cache[$key]; $out=array(); if(!$this->table_exists($db,$table))return $out;
        $safe=preg_replace('/[^A-Za-z0-9_]/','',$table); $r=$db->query("SHOW COLUMNS FROM `{$safe}`"); if($r){while($row=$r->fetch_assoc())$out[strtolower($row['Field'])]=true;$r->free();} $cache[$key]=$out; return $out;
    }
    private function first_table($db,$candidates){foreach($candidates as $t)if($this->table_exists($db,$t))return $t;return false;}
    private function external_follow_table($db,$platform){return $platform===self::ORIGIN_STREAMS?$this->first_table($db,array('Wo_Followers','wo_followers','followers')):$this->first_table($db,array('followers'));}
    private function external_block_table($db,$platform){return $platform===self::ORIGIN_STREAMS?$this->first_table($db,array('Wo_Blocks','wo_blocks','blocks')):$this->first_table($db,array('blocks'));}

    private function verify_external_databases($log=true) {
        $out=array('streams'=>array('connected'=>false,'database'=>'','tables'=>array()),'socials'=>array('connected'=>false,'database'=>'','tables'=>array()));
        $s=$this->streams_db(); if($s){$out['streams']['connected']=true;$out['streams']['database']=$this->db_name($s);foreach(array('Wo_Followers','Wo_Blocks') as $t)$out['streams']['tables'][$t]=$this->table_exists($s,$t);}
        $q=$this->socials_db(); if($q){$out['socials']['connected']=true;$out['socials']['database']=$this->db_name($q);foreach(array('followers','blocks') as $t)$out['socials']['tables'][$t]=$this->table_exists($q,$t);}
        if($log)$this->log('External database verification',$out);
        return $out;
    }

    /* -------------------------------------------------------------------------
     * External follow projection. QuickDate follow rows are never confused with
     * the connection projection: the canonical ledger decides what may be removed.
     * ---------------------------------------------------------------------- */
    private function project_external_follow($actor,$target,$active,$platform,$event_uuid='') {
        $ea=$this->map_wp_to_external($actor,$platform); $et=$this->map_wp_to_external($target,$platform);
        if(!$ea||!$et){$this->queue_projection($platform,$active?self::OP_FOLLOW:self::OP_UNFOLLOW,$actor,$target,array('event_uuid'=>$event_uuid,'reason'=>'missing_external_mapping'));return false;}
        $db=$platform===self::ORIGIN_STREAMS?$this->streams_db():$this->socials_db();
        if(!$db){$this->queue_projection($platform,$active?self::OP_FOLLOW:self::OP_UNFOLLOW,$actor,$target,array('event_uuid'=>$event_uuid,'reason'=>'db_unavailable'));return false;}
        try {
            if($active)$this->external_upsert_follow($db,$platform,$et,$ea,$actor,$target,$event_uuid);
            else $this->external_delete_follow_if_safe($db,$platform,$et,$ea,$actor,$target,$event_uuid);
            return true;
        } catch(Throwable $e) {
            $this->queue_projection($platform,$active?self::OP_FOLLOW:self::OP_UNFOLLOW,$actor,$target,array('event_uuid'=>$event_uuid,'error'=>$e->getMessage()));
            $this->log('External follow projection failed',array('platform'=>$platform,'actor'=>$actor,'target'=>$target,'active'=>$active,'error'=>$e->getMessage(),'event_uuid'=>$event_uuid),$platform); return false;
        }
    }

    private function external_upsert_follow($db,$platform,$following_id,$follower_id,$actor_wp,$target_wp,$event_uuid='') {
        $table=$this->external_follow_table($db,$platform); if(!$table)throw new RuntimeException('External followers table not found.');
        $stmt=$db->prepare("SELECT id,active FROM `{$table}` WHERE following_id=? AND follower_id=? LIMIT 1"); if(!$stmt)throw new RuntimeException($db->error); $stmt->bind_param('ii',$following_id,$follower_id);$stmt->execute();$r=$stmt->get_result();$row=$r?$r->fetch_assoc():null;$stmt->close();
        $affected=0;
        if($row){$id=(int)$row['id'];$stmt=$db->prepare("UPDATE `{$table}` SET active=1 WHERE id=?");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('i',$id);if(!$stmt->execute()){$e=$stmt->error;$stmt->close();throw new RuntimeException($e?:'Follow update failed.');}$affected=$stmt->affected_rows;$stmt->close();}
        else {
            $cols=$this->columns($db,$table); if(isset($cols['created_at'])){$now=time();$stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active,created_at) VALUES (?,?,1,?)");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('iii',$following_id,$follower_id,$now);} else {$stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active) VALUES (?, ?, 1)");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('ii',$following_id,$follower_id);} if(!$stmt->execute()){$e=$stmt->error;$stmt->close();throw new RuntimeException($e?:'Follow insert failed.');}$affected=$stmt->affected_rows;$stmt->close();}
        $verified=$this->external_follow_exists($db,$table,$following_id,$follower_id,true);
        $this->external_log($platform,self::OP_FOLLOW,self::ORIGIN_WORDPRESS,$actor_wp,$target_wp,$follower_id,$following_id,$db,$table,'UPSERT',$affected,$verified,$event_uuid,$verified?'':'read-back verification failed');
        if(!$verified)throw new RuntimeException('External follow read-back verification failed.');
    }

    private function external_delete_follow_if_safe($db,$platform,$following_id,$follower_id,$actor_wp,$target_wp,$event_uuid='') {
        $table=$this->external_follow_table($db,$platform);if(!$table)throw new RuntimeException('External followers table not found.');
        if($platform===self::ORIGIN_SOCIALS){$ledger=$this->get_ledger($actor_wp,$target_wp);if(in_array($ledger['connection_state'],array('requested','connected'),true)){return $this->qd_preserve_friend_projection($db,$actor_wp,$target_wp,$ledger['connection_state'],$event_uuid);}}
        $stmt=$db->prepare("DELETE FROM `{$table}` WHERE following_id=? AND follower_id=?");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('ii',$following_id,$follower_id);if(!$stmt->execute()){$e=$stmt->error;$stmt->close();throw new RuntimeException($e?:'Follow deletion failed.');}$affected=$stmt->affected_rows;$stmt->close();
        $verified=!$this->external_follow_exists($db,$table,$following_id,$follower_id,true);$this->external_log($platform,self::OP_UNFOLLOW,self::ORIGIN_WORDPRESS,$actor_wp,$target_wp,$follower_id,$following_id,$db,$table,'DELETE',$affected,$verified,$event_uuid,$verified?'':'read-back verification failed');if(!$verified)throw new RuntimeException('External follow delete read-back verification failed.');
    }

    private function external_follow_exists($db,$table,$following,$follower,$any_active=false) {
        $sql="SELECT id,active FROM `{$table}` WHERE following_id=? AND follower_id=? LIMIT 1";$stmt=$db->prepare($sql);if(!$stmt)return false;$stmt->bind_param('ii',$following,$follower);$stmt->execute();$r=$stmt->get_result();$row=$r?$r->fetch_assoc():null;$stmt->close();return $row && (!$any_active || (int)$row['active']===1);
    }

    /* -------------------------------------------------------------------------
     * QuickDate connection projection
     * ---------------------------------------------------------------------- */
    private function project_social_connection($a,$b,$state,$event_uuid='') {
        $qa=$this->map_wp_to_external($a,self::ORIGIN_SOCIALS);$qb=$this->map_wp_to_external($b,self::ORIGIN_SOCIALS);
        if(!$qa||!$qb){$this->queue_projection(self::ORIGIN_SOCIALS,'connection_'.$state,$a,$b,array('event_uuid'=>$event_uuid,'reason'=>'missing_external_mapping'));return false;}
        $db=$this->socials_db();if(!$db){$this->queue_projection(self::ORIGIN_SOCIALS,'connection_'.$state,$a,$b,array('event_uuid'=>$event_uuid,'reason'=>'db_unavailable'));return false;}
        try {
            if($state==='requested'){
                $ledger=$this->get_ledger($a,$b);$requester=absint($ledger['requested_by']);if(!$requester)throw new RuntimeException('QuickDate pending projection has no requester.');$recipient=$requester===$a?$b:$a;$qr=$this->map_wp_to_external($requester,self::ORIGIN_SOCIALS);$qt=$this->map_wp_to_external($recipient,self::ORIGIN_SOCIALS);if(!$qr||!$qt)throw new RuntimeException('QuickDate user mapping unavailable for pending connection.');$this->qd_set_friend_projection($db,$qr,$qt,0,$requester,$recipient,$event_uuid);
            } elseif($state==='connected') {
                $this->qd_set_friend_projection($db,$qa,$qb,1,$a,$b,$event_uuid);
                $this->qd_set_friend_projection($db,$qb,$qa,1,$b,$a,$event_uuid);
            } else {
                $this->qd_remove_relationship_if_not_following($db,$a,$b,$event_uuid);
            }
            return true;
        } catch(Throwable $e){$this->queue_projection(self::ORIGIN_SOCIALS,'connection_'.$state,$a,$b,array('event_uuid'=>$event_uuid,'error'=>$e->getMessage()));$this->log('QuickDate connection projection failed',array('actor'=>$a,'target'=>$b,'state'=>$state,'error'=>$e->getMessage(),'event_uuid'=>$event_uuid),self::ORIGIN_SOCIALS);return false;}
    }

    private function qd_set_friend_projection($db,$requester_id,$recipient_id,$active,$actor_wp,$target_wp,$event_uuid='') {
        $table=$this->external_follow_table($db,self::ORIGIN_SOCIALS);if(!$table)throw new RuntimeException('QuickDate followers table not found.');
        $stmt=$db->prepare("SELECT id,active FROM `{$table}` WHERE following_id=? AND follower_id=? LIMIT 1");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('ii',$recipient_id,$requester_id);$stmt->execute();$r=$stmt->get_result();$row=$r?$r->fetch_assoc():null;$stmt->close();$active_int=$active?1:0;$affected=0;
        if($row){$id=(int)$row['id'];$stmt=$db->prepare("UPDATE `{$table}` SET active=? WHERE id=?");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('ii',$active_int,$id);if(!$stmt->execute()){$e=$stmt->error;$stmt->close();throw new RuntimeException($e?:'QuickDate friend projection update failed.');}$affected=$stmt->affected_rows;$stmt->close();}
        else {$cols=$this->columns($db,$table);if(isset($cols['created_at'])){$now=time();$stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active,created_at) VALUES (?,?,?,?)");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('iiii',$recipient_id,$requester_id,$active_int,$now);}else{$stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active) VALUES (?,?,?)");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('iii',$recipient_id,$requester_id,$active_int);}if(!$stmt->execute()){$e=$stmt->error;$stmt->close();throw new RuntimeException($e?:'QuickDate friend projection insert failed.');}$affected=$stmt->affected_rows;$stmt->close();}
        $verified=$this->qd_friend_projection_exists($db,$table,$requester_id,$recipient_id,$active_int);$this->external_log(self::ORIGIN_SOCIALS,$active?self::OP_CONNECTION_ACCEPT:self::OP_CONNECTION_REQUEST,self::ORIGIN_WORDPRESS,$actor_wp,$target_wp,$requester_id,$recipient_id,$db,$table,'UPSERT',$affected,$verified,$event_uuid,$verified?'':'read-back verification failed');if(!$verified)throw new RuntimeException('QuickDate connection read-back verification failed.');
    }

    private function qd_friend_projection_exists($db,$table,$requester,$recipient,$active) { $stmt=$db->prepare("SELECT active FROM `{$table}` WHERE following_id=? AND follower_id=? LIMIT 1");if(!$stmt)return false;$stmt->bind_param('ii',$recipient,$requester);$stmt->execute();$r=$stmt->get_result();$row=$r?$r->fetch_assoc():null;$stmt->close();return $row && (int)$row['active']===(int)$active; }

    private function qd_preserve_friend_projection($db,$a,$b,$state,$event_uuid='') {
        $ledger=$this->get_ledger($a,$b);if($state==='connected'){ $qa=$this->map_wp_to_external($a,self::ORIGIN_SOCIALS);$qb=$this->map_wp_to_external($b,self::ORIGIN_SOCIALS);if($qa&&$qb){$this->qd_set_friend_projection($db,$qa,$qb,1,$a,$b,$event_uuid);$this->qd_set_friend_projection($db,$qb,$qa,1,$b,$a,$event_uuid);return true;}return false; }
        if($state==='requested'&&!empty($ledger['requested_by'])){$r=absint($ledger['requested_by']);$t=$r===$a?$b:$a;$qr=$this->map_wp_to_external($r,self::ORIGIN_SOCIALS);$qt=$this->map_wp_to_external($t,self::ORIGIN_SOCIALS);if($qr&&$qt){$this->qd_set_friend_projection($db,$qr,$qt,0,$r,$t,$event_uuid);return true;}}
        return false;
    }

    private function qd_remove_relationship_if_not_following($db,$a,$b,$event_uuid='') {
        $table=$this->external_follow_table($db,self::ORIGIN_SOCIALS);if(!$table)throw new RuntimeException('QuickDate followers table not found.');$ledger=$this->get_ledger($a,$b);$qa=$this->map_wp_to_external($a,self::ORIGIN_SOCIALS);$qb=$this->map_wp_to_external($b,self::ORIGIN_SOCIALS);if(!$qa||!$qb)return false;
        $ops=array(); if(empty($ledger['follow_a_to_b']))$ops[]=array($qb,$qa); if(empty($ledger['follow_b_to_a']))$ops[]=array($qa,$qb);
        foreach($ops as $p){$stmt=$db->prepare("DELETE FROM `{$table}` WHERE following_id=? AND follower_id=?");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('ii',$p[0],$p[1]);if(!$stmt->execute()){$e=$stmt->error;$stmt->close();throw new RuntimeException($e?:'QuickDate relationship removal failed.');}$affected=$stmt->affected_rows;$stmt->close();$verified=!$this->external_follow_exists($db,$table,$p[0],$p[1]);$this->external_log(self::ORIGIN_SOCIALS,self::OP_CONNECTION_REMOVE,self::ORIGIN_WORDPRESS,$a,$b,$p[1],$p[0],$db,$table,'DELETE',$affected,$verified,$event_uuid,$verified?'':'read-back verification failed');if(!$verified)throw new RuntimeException('QuickDate relationship removal read-back verification failed.');}
        return true;
    }

    /* -------------------------------------------------------------------------
     * Blocks
     * ---------------------------------------------------------------------- */
    private function project_external_block($actor,$target,$blocked,$platform,$event_uuid='') {
        $ea=$this->map_wp_to_external($actor,$platform);$et=$this->map_wp_to_external($target,$platform);if(!$ea||!$et){$this->queue_projection($platform,$blocked?self::OP_BLOCK:self::OP_UNBLOCK,$actor,$target,array('event_uuid'=>$event_uuid,'reason'=>'missing_external_mapping'));return false;}
        $db=$platform===self::ORIGIN_STREAMS?$this->streams_db():$this->socials_db();if(!$db){$this->queue_projection($platform,$blocked?self::OP_BLOCK:self::OP_UNBLOCK,$actor,$target,array('event_uuid'=>$event_uuid,'reason'=>'db_unavailable'));return false;}
        try { if($blocked)$this->external_set_block($db,$ea,$et,$platform,$actor,$target,$event_uuid); else $this->external_delete_block($db,$ea,$et,$platform,$actor,$target,$event_uuid);return true; }
        catch(Throwable $e){$this->queue_projection($platform,$blocked?self::OP_BLOCK:self::OP_UNBLOCK,$actor,$target,array('event_uuid'=>$event_uuid,'error'=>$e->getMessage()));$this->log('External block projection failed',array('platform'=>$platform,'actor'=>$actor,'target'=>$target,'blocked'=>$blocked,'error'=>$e->getMessage(),'event_uuid'=>$event_uuid),$platform);return false;}
    }

    private function external_set_block($db,$actor,$target,$platform,$actor_wp,$target_wp,$event_uuid='') {
        $table=$this->external_block_table($db,$platform);if(!$table)throw new RuntimeException('External blocks table not found.');$exists=false;
        if($platform===self::ORIGIN_STREAMS)$stmt=$db->prepare("SELECT id FROM `{$table}` WHERE blocker=? AND blocked=? LIMIT 1");else $stmt=$db->prepare("SELECT id FROM `{$table}` WHERE user_id=? AND block_userid=? LIMIT 1");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('ii',$actor,$target);$stmt->execute();$r=$stmt->get_result();$exists=$r&&$r->fetch_assoc();$stmt->close();if($exists)return true;
        if($platform===self::ORIGIN_STREAMS){$stmt=$db->prepare("INSERT INTO `{$table}` (blocker,blocked) VALUES (?,?)");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('ii',$actor,$target);}else{$cols=$this->columns($db,$table);if(isset($cols['created_at'])){$now=gmdate('Y-m-d H:i:s');$stmt=$db->prepare("INSERT INTO `{$table}` (user_id,block_userid,created_at) VALUES (?,?,?)");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('iis',$actor,$target,$now);}else{$stmt=$db->prepare("INSERT INTO `{$table}` (user_id,block_userid) VALUES (?,?)");if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('ii',$actor,$target);}}
        if(!$stmt->execute()){$e=$stmt->error;$stmt->close();throw new RuntimeException($e?:'External block insert failed.');}$affected=$stmt->affected_rows;$stmt->close();$verified=$this->external_block_exists($db,$table,$actor,$target,$platform);$this->external_log($platform,self::OP_BLOCK,self::ORIGIN_WORDPRESS,$actor_wp,$target_wp,$actor,$target,$db,$table,'INSERT',$affected,$verified,$event_uuid,$verified?'':'read-back verification failed');if(!$verified)throw new RuntimeException('External block read-back verification failed.');
    }

    private function external_block_exists($db,$table,$actor,$target,$platform) { $sql=$platform===self::ORIGIN_STREAMS?"SELECT id FROM `{$table}` WHERE blocker=? AND blocked=? LIMIT 1":"SELECT id FROM `{$table}` WHERE user_id=? AND block_userid=? LIMIT 1";$stmt=$db->prepare($sql);if(!$stmt)return false;$stmt->bind_param('ii',$actor,$target);$stmt->execute();$r=$stmt->get_result();$row=$r?$r->fetch_assoc():null;$stmt->close();return (bool)$row; }

    private function external_delete_block($db,$actor,$target,$platform,$actor_wp,$target_wp,$event_uuid='') {
        $table=$this->external_block_table($db,$platform);if(!$table)throw new RuntimeException('External blocks table not found.');$sql=$platform===self::ORIGIN_STREAMS?"DELETE FROM `{$table}` WHERE blocker=? AND blocked=?":"DELETE FROM `{$table}` WHERE user_id=? AND block_userid=?";$stmt=$db->prepare($sql);if(!$stmt)throw new RuntimeException($db->error);$stmt->bind_param('ii',$actor,$target);if(!$stmt->execute()){$e=$stmt->error;$stmt->close();throw new RuntimeException($e?:'External block removal failed.');}$affected=$stmt->affected_rows;$stmt->close();$verified=!$this->external_block_exists($db,$table,$actor,$target,$platform);$this->external_log($platform,self::OP_UNBLOCK,self::ORIGIN_WORDPRESS,$actor_wp,$target_wp,$actor,$target,$db,$table,'DELETE',$affected,$verified,$event_uuid,$verified?'':'read-back verification failed');if(!$verified)throw new RuntimeException('External block removal read-back verification failed.');
    }

    /* -------------------------------------------------------------------------
     * Durable events / recovery / reconciliation
     * ---------------------------------------------------------------------- */
    private function event_exists($uuid) { global $wpdb; return (bool)$wpdb->get_var($wpdb->prepare("SELECT id FROM {$this->events_table} WHERE event_uuid=%s LIMIT 1",$uuid)); }
    private function insert_event($uuid,$origin,$operation,$actor,$target,$payload=array()) { global $wpdb;if($this->event_exists($uuid))return true;$p=$this->pair($actor,$target);$now=current_time('mysql',true);$wpdb->insert($this->events_table,array('event_uuid'=>$uuid,'origin'=>sanitize_key($origin),'operation'=>sanitize_key($operation),'actor_wp_id'=>absint($actor),'target_wp_id'=>absint($target),'pair_a'=>$p[0],'pair_b'=>$p[1],'payload'=>wp_json_encode($payload),'status'=>'pending','attempts'=>0,'next_attempt_at'=>$now,'created_at'=>$now,'updated_at'=>$now));return true; }
    private function mark_event_processed($uuid) { global $wpdb;$now=current_time('mysql',true);$wpdb->update($this->events_table,array('status'=>'processed','processed_at'=>$now,'updated_at'=>$now,'last_error'=>null),array('event_uuid'=>$uuid)); }
    private function mark_event_retry($uuid,$error) { global $wpdb;$row=$wpdb->get_row($wpdb->prepare("SELECT attempts FROM {$this->events_table} WHERE event_uuid=%s LIMIT 1",$uuid));$attempts=$row?(int)$row->attempts+1:1;$delay=min(3600,max(60,(int)pow(2,min(10,$attempts))));$status=$attempts>=self::MAX_ATTEMPTS?'failed':'pending';$next=$status==='failed'?current_time('mysql',true):gmdate('Y-m-d H:i:s',time()+$delay);$wpdb->update($this->events_table,array('status'=>$status,'attempts'=>$attempts,'next_attempt_at'=>$next,'last_error'=>substr((string)$error,0,65000),'updated_at'=>current_time('mysql',true)),array('event_uuid'=>$uuid)); }
    private function queue_projection($platform,$operation,$actor,$target,$payload=array()) { $uuid=isset($payload['event_uuid'])&&$this->is_uuid($payload['event_uuid'])?$payload['event_uuid']:wp_generate_uuid4();$payload['projection']=true;$payload['projection_platform']=$platform;$this->insert_event($uuid,$platform,$operation,$actor,$target,$payload);$this->log('Projection queued',array('event_uuid'=>$uuid,'platform'=>$platform,'operation'=>$operation,'actor'=>$actor,'target'=>$target),$platform);return $uuid; }

    public function process_recovery_queue() { global $wpdb;$rows=$wpdb->get_results("SELECT * FROM {$this->events_table} WHERE status='pending' AND next_attempt_at<=UTC_TIMESTAMP() ORDER BY id ASC LIMIT 25");foreach((array)$rows as $row)$this->process_recovery_event($row); }
    private function process_recovery_event($row) {
        $actor=(int)$row->actor_wp_id;$target=(int)$row->target_wp_id;if($actor<1||$target<1){$this->mark_event_retry($row->event_uuid,'Invalid recovery event users.');return;}
        if(!$this->acquire_pair_lock($actor,$target)){$this->mark_event_retry($row->event_uuid,'Pair lock busy.');return;}
        try {
            $payload=json_decode((string)$row->payload,true);$projection=!empty($payload['projection']);
            if($projection){$platform=$row->origin;switch($row->operation){case self::OP_FOLLOW:$ok=$this->project_external_follow($actor,$target,true,$platform,$row->event_uuid);break;case self::OP_UNFOLLOW:$ok=$this->project_external_follow($actor,$target,false,$platform,$row->event_uuid);break;case self::OP_BLOCK:$ok=$this->project_external_block($actor,$target,true,$platform,$row->event_uuid);break;case self::OP_UNBLOCK:$ok=$this->project_external_block($actor,$target,false,$platform,$row->event_uuid);break;default:$ok=$this->replay_connection_projection($actor,$target,$row->operation,$platform,$row->event_uuid);break;}}
            else {$this->reconcile_pair($actor,$target);$ok=true;}
            if($ok)$this->mark_event_processed($row->event_uuid);else $this->mark_event_retry($row->event_uuid,'Recovery projection did not complete.');
        } catch(Throwable $e){$this->mark_event_retry($row->event_uuid,$e->getMessage());}
        finally{$this->release_pair_lock($actor,$target);}
    }

    private function replay_connection_projection($a,$b,$operation,$platform,$event_uuid) {
        if($platform!==self::ORIGIN_SOCIALS)return false;$ledger=$this->get_ledger($a,$b);$state=$ledger['connection_state'];return $this->project_social_connection($a,$b,$state,$event_uuid);
    }

    public function reconcile_recent() { global $wpdb;$rows=$wpdb->get_results("SELECT user_a,user_b FROM {$this->ledger_table} WHERE updated_at>=UTC_TIMESTAMP()-INTERVAL 7 DAY ORDER BY updated_at DESC LIMIT 100");foreach((array)$rows as $row)$this->reconcile_pair((int)$row->user_a,(int)$row->user_b); }
    private function reconcile_pair($a,$b) {
        if(!$this->acquire_pair_lock($a,$b))return false;
        try{$uuid=wp_generate_uuid4();$this->refresh_ledger_from_buddyboss($a,$b,$uuid);$this->project_canonical_pair($a,$b,$uuid);return true;}catch(Throwable $e){$this->log('Reconciliation failed',array('actor'=>$a,'target'=>$b,'error'=>$e->getMessage()));return false;}finally{$this->release_pair_lock($a,$b);}
    }
    private function refresh_ledger_from_buddyboss($a,$b,$event_uuid='') {
        $changes=array('connection_state'=>'none','requested_by'=>0);$s=$this->buddyboss_connection_state($a,$b);if($s==='is_friend')$changes['connection_state']='connected';elseif($s==='pending'){$changes['connection_state']='requested';$changes['requested_by']=$a;}elseif($s==='awaiting_response'){$changes['connection_state']='requested';$changes['requested_by']=$b;}
        $changes['follow_a_to_b']=$this->bb_follow_exists($a,$b)?1:0;$changes['follow_b_to_a']=$this->bb_follow_exists($b,$a)?1:0;$changes['block_a_to_b']=$this->bb_block_exists_direction($a,$b)?1:0;$changes['block_b_to_a']=$this->bb_block_exists_direction($b,$a)?1:0;if($changes['block_a_to_b']||$changes['block_b_to_a']){$changes['connection_state']='none';$changes['requested_by']=0;$changes['follow_a_to_b']=0;$changes['follow_b_to_a']=0;}
        return $this->save_ledger($a,$b,$changes,$event_uuid);
    }
    private function project_canonical_pair($a,$b,$event_uuid='') {
        $l=$this->get_ledger($a,$b);if(!empty($l['block_a_to_b']))$this->project_block_pair($a,$b,true,$event_uuid);elseif(!empty($l['block_b_to_a']))$this->project_block_pair($b,$a,true,$event_uuid);else{$this->project_social_connection($a,$b,$l['connection_state'],$event_uuid);$this->project_external_follow($a,$b,!empty($l['follow_a_to_b']),self::ORIGIN_STREAMS,$event_uuid);$this->project_external_follow($b,$a,!empty($l['follow_b_to_a']),self::ORIGIN_STREAMS,$event_uuid);}}
    private function bb_follow_exists($actor,$target) { return function_exists('bp_is_following')?(bool)bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor)):false; }

    /* -------------------------------------------------------------------------
     * Locks / internal context / utilities
     * ---------------------------------------------------------------------- */
    private function acquire_pair_lock($a,$b) { global $wpdb;$p=$this->pair($a,$b);$key='bzj_pair_'.$p[0].'_'.$p[1];$got=$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,%d)',$key,self::LOCK_TIMEOUT));if((int)$got===1){$this->held_locks[$key]=true;return true;}return false; }
    private function release_pair_lock($a,$b) { global $wpdb;$p=$this->pair($a,$b);$key='bzj_pair_'.$p[0].'_'.$p[1];if(isset($this->held_locks[$key])){$wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)',$key));unset($this->held_locks[$key]);} }
    private function push_context($context) { $this->context_stack[]=$context; }
    private function pop_context() { array_pop($this->context_stack); }
    private function in_internal_context() { return !empty($this->context_stack); }
    private function is_uuid($value) { return (bool)preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',(string)$value); }
}

BZJ_Connections_Sync::instance();
