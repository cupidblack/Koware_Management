<?php
/**
 * Plugin Name: BZJ Connections Synchronization Control Plane
 * Description: Buzzjuice Network relationship control plane. BuddyBoss is canonical; WoWonder Streams and QuickDate Socials are synchronized projections.
 * Version: 8.1.2
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
    const VERSION = '8.1.2';
    const REST_NAMESPACE = 'bzj/v6';
    const REST_ROUTE = '/connection-management';
    const LEDGER_TABLE = 'bzj_relationships';
    const EVENTS_TABLE = 'bzj_relationship_events';
    const LOG_DIR = 'data/logs';
    const LOG_FILE = 'bzj-connections-sync.log';
    const LOCK_TIMEOUT = 15;
    const TIMESTAMP_WINDOW = 300;
    const MAX_ATTEMPTS = 12;
    const CRON_HOOK = 'bzj_connections_sync_recovery';
    const RECONCILE_HOOK = 'bzj_connections_sync_reconcile';

    const ORIGIN_WORDPRESS = 'wordpress';
    const ORIGIN_STREAMS = 'streams';
    const ORIGIN_SOCIALS = 'socials';

    const OP_CONNECTION_REQUEST = 'connection_request';
    const OP_CONNECTION_ACCEPT  = 'connection_accept';
    const OP_CONNECTION_REJECT  = 'connection_reject';
    const OP_CONNECTION_WITHDRAW = 'connection_withdraw';
    const OP_CONNECTION_REMOVE  = 'connection_remove';
    const OP_FOLLOW = 'follow';
    const OP_UNFOLLOW = 'unfollow';
    const OP_BLOCK = 'block';
    const OP_UNBLOCK = 'unblock';

    private static $instance = null;
    private $ledger_table;
    private $events_table;
    private $internal_depth = 0;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        global $wpdb;
        $this->ledger_table = $wpdb->prefix . self::LEDGER_TABLE;
        $this->events_table = $wpdb->prefix . self::EVENTS_TABLE;

        add_filter('cron_schedules', array($this, 'cron_schedules'));
        add_action('rest_api_init', array($this, 'register_rest_routes'));

        /* BuddyBoss connections. */
        add_action('friends_friendship_requested', array($this, 'bb_requested'), 20, 4);
        add_action('friends_friendship_accepted', array($this, 'bb_accepted'), 20, 4);
        add_action('friends_friendship_rejected', array($this, 'bb_rejected'), 20, 2);
        add_action('friends_friendship_withdrawn', array($this, 'bb_withdrawn'), 20, 2);
        add_action('friends_friendship_whithdrawn', array($this, 'bb_withdrawn'), 20, 2);
        add_action('friends_friendship_deleted', array($this, 'bb_deleted'), 1, 3);
        add_action('friends_friendship_post_delete', array($this, 'bb_post_deleted'), 20, 2);

        /* BuddyBoss follows. */
        add_action('bp_start_following', array($this, 'bb_follow_started'), 20, 1);
        add_action('bp_stop_following', array($this, 'bb_follow_stopped'), 20, 1);
        add_action('bp_follow_start_following', array($this, 'bb_follow_started'), 20, 1);
        add_action('bp_follow_stop_following', array($this, 'bb_follow_stopped'), 20, 1);

        /* Installed BuddyBoss moderation lifecycle. */
        add_action('bp_moderation_after_save', array($this, 'bb_moderation_saved'), 20, 1);
        add_action('bb_moderation_after_delete', array($this, 'bb_moderation_deleted'), 20, 1);

        add_action(self::CRON_HOOK, array($this, 'process_recovery'));
        add_action(self::RECONCILE_HOOK, array($this, 'reconcile_recent'));

        $this->install_schema();
        $this->schedule();
    }

    public function cron_schedules($schedules) {
        if (!isset($schedules['bzj_five_minutes'])) {
            $schedules['bzj_five_minutes'] = array(
                'interval' => 300,
                'display'  => 'BZJ every five minutes',
            );
        }
        return $schedules;
    }

    private function install_schema() {
        $installed = get_option('bzj_connections_sync_schema_version', '');
        if ($installed === self::VERSION) {
            return;
        }
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        global $wpdb;
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
        $this->assert_wpdb('install_ledger');
        dbDelta($sql2);
        $this->assert_wpdb('install_events');
        update_option('bzj_connections_sync_schema_version', self::VERSION, false);
    }

    private function schedule() {
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_event(time() + 60, 'bzj_five_minutes', self::CRON_HOOK);
        }
        if (!wp_next_scheduled(self::RECONCILE_HOOK)) {
            wp_schedule_event(time() + 300, 'hourly', self::RECONCILE_HOOK);
        }
    }

    private function log($message, $context = array()) {
        $dir = trailingslashit(ABSPATH) . self::LOG_DIR;
        if (!is_dir($dir)) {
            wp_mkdir_p($dir);
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            error_log('[BZJ Sync] Log directory unavailable: ' . $dir);
            return false;
        }
        $entry = array(
            'time' => gmdate('c'),
            'message' => (string) $message,
            'context' => is_array($context) ? $context : array('value' => $context),
        );
        $ok = @file_put_contents(
            trailingslashit($dir) . self::LOG_FILE,
            wp_json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
        if (false === $ok) {
            error_log('[BZJ Sync] Unable to write log file.');
            return false;
        }
        return true;
    }

    private function assert_wpdb($operation, $context = array()) {
        global $wpdb;
        if (!empty($wpdb->last_error)) {
            $ctx = array_merge(array('operation' => $operation, 'error' => $wpdb->last_error), $context);
            $this->log('WordPress database error', $ctx);
            throw new RuntimeException('WordPress database operation failed: ' . $wpdb->last_error);
        }
    }

    private function pair($a, $b) {
        $a = absint($a);
        $b = absint($b);
        return ($a < $b) ? array($a, $b) : array($b, $a);
    }

    private function lock_key($a, $b) {
        $p = $this->pair($a, $b);
        return 'bzj_pair_' . $p[0] . '_' . $p[1];
    }

    private function acquire_lock($a, $b) {
        global $wpdb;
        $key = $this->lock_key($a, $b);
        $got = $wpdb->get_var($wpdb->prepare("SELECT GET_LOCK(%s,%d)", $key, self::LOCK_TIMEOUT));
        return (string) $got === '1';
    }

    private function release_lock($a, $b) {
        global $wpdb;
        $wpdb->get_var($wpdb->prepare("SELECT RELEASE_LOCK(%s)", $this->lock_key($a, $b)));
    }

    private function push_internal() { $this->internal_depth++; }
    private function pop_internal() { $this->internal_depth = max(0, $this->internal_depth - 1); }
    private function internal() { return $this->internal_depth > 0; }

    private function load_db_helpers() {
        static $loaded = false;
        if ($loaded) return true;
        $paths = array(
            trailingslashit(ABSPATH) . 'shared/db_helpers.php',
            trailingslashit(dirname(ABSPATH)) . 'shared/db_helpers.php',
            trailingslashit(dirname(ABSPATH, 2)) . 'shared/db_helpers.php',
        );
        foreach ($paths as $path) {
            if (is_file($path)) {
                require_once $path;
                $loaded = true;
                $this->log('Database helper loaded', array('path' => $path));
                return true;
            }
        }
        $this->log('Database helper not found', array('paths_checked' => $paths));
        return false;
    }

    private function streams_db() {
        if (!$this->load_db_helpers() || !function_exists('get_wowonder_db')) return false;
        $db = get_wowonder_db();
        return ($db instanceof mysqli) ? $db : false;
    }

    private function socials_db() {
        if (!$this->load_db_helpers() || !function_exists('get_qd_db_conn')) return false;
        $db = get_qd_db_conn();
        return ($db instanceof mysqli) ? $db : false;
    }

    private function map_wp_to_external($wp_id, $platform) {
        $key = ($platform === 'streams') ? 'wo_user_id' : 'qd_user_id';
        return absint(get_user_meta(absint($wp_id), $key, true));
    }

    private function map_external_to_wp($platform, $external_id) {
        global $wpdb;
        $key = ($platform === 'streams') ? 'wo_user_id' : 'qd_user_id';
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key=%s AND meta_value=%s ORDER BY umeta_id ASC LIMIT 1",
            $key, (string) absint($external_id)
        ));
    }

    private function table_exists($db, $table) {
        if (!$db || !$table) return false;
        $safe = preg_replace('/[^A-Za-z0-9_]/', '', $table);
        $r = $db->query("SHOW TABLES LIKE '" . $db->real_escape_string($safe) . "'");
        return ($r instanceof mysqli_result && $r->num_rows > 0);
    }

    private function columns($db, $table) {
        static $cache = array();
        $key = spl_object_hash($db) . ':' . $table;
        if (isset($cache[$key])) return $cache[$key];
        $out = array();
        if (!$this->table_exists($db, $table)) return $out;
        $safe = preg_replace('/[^A-Za-z0-9_]/', '', $table);
        $r = $db->query("SHOW COLUMNS FROM `{$safe}`");
        if ($r) {
            while ($row = $r->fetch_assoc()) $out[strtolower($row['Field'])] = true;
            $r->free();
        }
        $cache[$key] = $out;
        return $out;
    }

    private function external_follow_table($db, $platform) {
        $c = ($platform === 'streams')
            ? array('Wo_Followers','wo_followers','followers')
            : array('followers');
        foreach ($c as $t) if ($this->table_exists($db, $t)) return $t;
        return false;
    }

    private function external_block_table($db, $platform) {
        $c = ($platform === 'streams')
            ? array('Wo_Blocks','wo_blocks','blocks')
            : array('blocks');
        foreach ($c as $t) if ($this->table_exists($db, $t)) return $t;
        return false;
    }

    private function uuid($value) {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', (string)$value);
    }

    private function event_exists($uuid) {
        global $wpdb;
        return (bool) $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$this->events_table} WHERE event_uuid=%s LIMIT 1", $uuid
        ));
    }

    private function insert_event($uuid, $origin, $operation, $actor, $target, $payload=array()) {
        global $wpdb;
        if ($this->event_exists($uuid)) return true;
        $p = $this->pair($actor, $target);
        $now = current_time('mysql', true);
        $ok = $wpdb->insert($this->events_table, array(
            'event_uuid'=>$uuid, 'origin'=>sanitize_key($origin), 'operation'=>sanitize_key($operation),
            'actor_wp_id'=>absint($actor), 'target_wp_id'=>absint($target),
            'pair_a'=>$p[0], 'pair_b'=>$p[1], 'payload'=>wp_json_encode($payload),
            'status'=>'pending', 'attempts'=>0, 'next_attempt_at'=>$now,
            'created_at'=>$now, 'updated_at'=>$now
        ));
        if (false === $ok) {
            $this->assert_wpdb('insert_event', array('event_uuid'=>$uuid));
            throw new RuntimeException('Event insertion failed.');
        }
        return true;
    }

    private function mark_processed($uuid) {
        global $wpdb;
        $now = current_time('mysql', true);
        $ok = $wpdb->update($this->events_table, array(
            'status'=>'processed','processed_at'=>$now,'updated_at'=>$now,'last_error'=>null
        ), array('event_uuid'=>$uuid));
        if (false === $ok) {
            $this->assert_wpdb('mark_processed', array('event_uuid'=>$uuid));
            throw new RuntimeException('Event status update failed.');
        }
    }

    private function mark_retry($uuid, $error) {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT attempts FROM {$this->events_table} WHERE event_uuid=%s LIMIT 1", $uuid
        ));
        $attempts = $row ? ((int)$row->attempts + 1) : 1;
        $status = ($attempts >= self::MAX_ATTEMPTS) ? 'failed' : 'pending';
        $delay = min(3600, max(60, (int)pow(2, min(10, $attempts))));
        $next = gmdate('Y-m-d H:i:s', time() + $delay);
        $ok = $wpdb->update($this->events_table, array(
            'status'=>$status,'attempts'=>$attempts,'next_attempt_at'=>$next,
            'last_error'=>substr((string)$error,0,65000),'updated_at'=>current_time('mysql',true)
        ), array('event_uuid'=>$uuid));
        if (false === $ok) {
            $this->assert_wpdb('mark_retry', array('event_uuid'=>$uuid));
        }
    }

    private function ledger_defaults($a, $b) {
        $p = $this->pair($a, $b);
        return array(
            'user_a'=>$p[0], 'user_b'=>$p[1], 'connection_state'=>'none', 'requested_by'=>0,
            'follow_a_to_b'=>0, 'follow_b_to_a'=>0, 'block_a_to_b'=>0, 'block_b_to_a'=>0,
            'version'=>1, 'last_event_uuid'=>''
        );
    }

    private function get_ledger($a, $b) {
        global $wpdb;
        $p = $this->pair($a, $b);
        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->ledger_table} WHERE user_a=%d AND user_b=%d LIMIT 1", $p[0], $p[1]
        ), ARRAY_A);
        return $row ? $row : $this->ledger_defaults($a,$b);
    }

    private function save_ledger($a, $b, $changes, $event_uuid='') {
        global $wpdb;
        $p = $this->pair($a,$b);
        $existing = $this->get_ledger($a,$b);
        $data = array_merge($existing, $changes);
        $data['user_a']=$p[0]; $data['user_b']=$p[1];
        $data['version']=max(1,(int)$existing['version'])+1;
        $data['last_event_uuid']=$event_uuid;
        $data['updated_at']=current_time('mysql',true);
        if (empty($existing['id'])) {
            $data['created_at']=current_time('mysql',true);
            unset($data['id']);
            $ok = $wpdb->insert($this->ledger_table,$data);
        } else {
            $id=(int)$existing['id'];
            unset($data['id']);
            $ok = $wpdb->update($this->ledger_table,$data,array('id'=>$id));
        }
        if (false === $ok) {
            $this->assert_wpdb('save_ledger',array('actor'=>$a,'target'=>$b));
            throw new RuntimeException('Relationship ledger write failed.');
        }
        return true;
    }

    private function bb_blocked($a,$b) {
        if (function_exists('bp_moderation_is_user_blocked')) {
            return (bool)bp_moderation_is_user_blocked($b, $a);
        }
        $l=$this->get_ledger($a,$b);
        $p=$this->pair($a,$b);
        return ($a===$p[0]) ? !empty($l['block_a_to_b']) : !empty($l['block_b_to_a']);
    }

    private function update_connection_state($a,$b,$state,$requested_by,$uuid) {
        $this->save_ledger($a,$b,array(
            'connection_state'=>$state,
            'requested_by'=>absint($requested_by)
        ),$uuid);
        $this->project_social_direction($a,$b);
        $this->project_social_direction($b,$a);
    }

    private function qd_desired_direction_state($actor_wp,$target_wp) {
        $l=$this->get_ledger($actor_wp,$target_wp);
        if ($l['connection_state']==='connected') return 1;
        if ($l['connection_state']==='requested') return 0;
        $p=$this->pair($actor_wp,$target_wp);
        if ((int)$actor_wp === (int)$p[0]) {
            return !empty($l['follow_a_to_b']) ? 1 : 0;
        }
        return !empty($l['follow_b_to_a']) ? 1 : 0;
    }

    private function project_social_direction($actor_wp,$target_wp) {
        $qa=$this->map_wp_to_external($actor_wp,'socials');
        $qt=$this->map_wp_to_external($target_wp,'socials');
        if (!$qa || !$qt) throw new RuntimeException('QuickDate user mapping missing.');
        $db=$this->socials_db();
        if (!$db) throw new RuntimeException('QuickDate database unavailable.');
        $table=$this->external_follow_table($db,'socials');
        if (!$table) throw new RuntimeException('QuickDate followers table missing.');
        $active=$this->qd_desired_direction_state($actor_wp,$target_wp) ? 1 : 0;
        if ($active) {
            $this->qd_upsert($db,$table,$qt,$qa,1);
        } else {
            $stmt=$db->prepare("DELETE FROM `{$table}` WHERE following_id=? AND follower_id=?");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$qt,$qa);
            if (!$stmt->execute()) {
                $e=$stmt->error ?: $db->error; $stmt->close();
                throw new RuntimeException('QuickDate directional deletion failed: '.$e);
            }
            $stmt->close();
        }
    }

    private function qd_upsert($db,$table,$following,$follower,$active) {
        $stmt=$db->prepare("SELECT id FROM `{$table}` WHERE following_id=? AND follower_id=? LIMIT 1");
        if (!$stmt) throw new RuntimeException($db->error);
        $stmt->bind_param('ii',$following,$follower);
        if (!$stmt->execute()) { $e=$stmt->error ?: $db->error; $stmt->close(); throw new RuntimeException($e); }
        $r=$stmt->get_result(); $row=$r ? $r->fetch_assoc() : null; $stmt->close();
        if ($row) {
            $id=(int)$row['id'];
            $stmt=$db->prepare("UPDATE `{$table}` SET active=? WHERE id=?");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$active,$id);
            if (!$stmt->execute()) { $e=$stmt->error ?: $db->error; $stmt->close(); throw new RuntimeException($e); }
            $stmt->close(); return true;
        }
        $cols=$this->columns($db,$table);
        if (isset($cols['created_at'])) {
            $now=gmdate('Y-m-d H:i:s');
            $stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active,created_at) VALUES (?,?,?,?)");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('iiis',$following,$follower,$active,$now);
        } elseif (isset($cols['time'])) {
            $now=time();
            $stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active,time) VALUES (?,?,?,?)");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('iiii',$following,$follower,$active,$now);
        } else {
            $stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active) VALUES (?,?,?)");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('iii',$following,$follower,$active);
        }
        if (!$stmt->execute()) { $e=$stmt->error ?: $db->error; $stmt->close(); throw new RuntimeException('QuickDate insert failed: '.$e); }
        $stmt->close(); return true;
    }

    private function project_external_follow($actor,$target,$active,$platform) {
        $ea=$this->map_wp_to_external($actor,$platform);
        $et=$this->map_wp_to_external($target,$platform);
        if (!$ea || !$et) throw new RuntimeException($platform.' user mapping missing.');
        $db=($platform==='streams')?$this->streams_db():$this->socials_db();
        if (!$db) throw new RuntimeException($platform.' database unavailable.');
        $table=$this->external_follow_table($db,$platform);
        if (!$table) throw new RuntimeException($platform.' followers table missing.');
        if ($active) {
            $this->external_upsert_follow($db,$table,$et,$ea);
        } else {
            $stmt=$db->prepare("DELETE FROM `{$table}` WHERE following_id=? AND follower_id=?");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$et,$ea);
            if (!$stmt->execute()) { $e=$stmt->error ?: $db->error; $stmt->close(); throw new RuntimeException($platform.' follow deletion failed: '.$e); }
            $stmt->close();
        }
    }

    private function external_upsert_follow($db,$table,$following,$follower) {
        $cols=$this->columns($db,$table);
        $stmt=$db->prepare("SELECT id,active FROM `{$table}` WHERE following_id=? AND follower_id=? LIMIT 1");
        if (!$stmt) throw new RuntimeException($db->error);
        $stmt->bind_param('ii',$following,$follower);
        if (!$stmt->execute()) { $e=$stmt->error ?: $db->error; $stmt->close(); throw new RuntimeException($e); }
        $r=$stmt->get_result(); $row=$r ? $r->fetch_assoc() : null; $stmt->close();
        if ($row) {
            $id=(int)$row['id'];
            if ((int)$row['active']===1) return true;
            $stmt=$db->prepare("UPDATE `{$table}` SET active=1 WHERE id=?");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('i',$id);
            if (!$stmt->execute()) { $e=$stmt->error ?: $db->error; $stmt->close(); throw new RuntimeException($e); }
            $stmt->close(); return true;
        }
        if (isset($cols['created_at'])) {
            $now=gmdate('Y-m-d H:i:s');
            $stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active,created_at) VALUES (?,?,1,?)");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('iis',$following,$follower,$now);
        } elseif (isset($cols['time'])) {
            $now=time();
            $stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active,time) VALUES (?,?,1,?)");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('iii',$following,$follower,$now);
        } else {
            $stmt=$db->prepare("INSERT INTO `{$table}` (following_id,follower_id,active) VALUES (?,?,1)");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$following,$follower);
        }
        if (!$stmt->execute()) { $e=$stmt->error ?: $db->error; $stmt->close(); throw new RuntimeException($e); }
        $stmt->close(); return true;
    }

    private function project_external_block($actor,$target,$active,$platform) {
        $ea=$this->map_wp_to_external($actor,$platform); $et=$this->map_wp_to_external($target,$platform);
        if (!$ea || !$et) throw new RuntimeException($platform.' user mapping missing for block.');
        $db=($platform==='streams')?$this->streams_db():$this->socials_db();
        if (!$db) throw new RuntimeException($platform.' database unavailable.');
        $table=$this->external_block_table($db,$platform);
        if (!$table) throw new RuntimeException($platform.' blocks table missing.');
        if ($active) {
            if ($platform==='streams') {
                $stmt=$db->prepare("SELECT id FROM `{$table}` WHERE blocker=? AND blocked=? LIMIT 1");
                if (!$stmt) throw new RuntimeException($db->error);
                $stmt->bind_param('ii',$ea,$et); $stmt->execute(); $r=$stmt->get_result(); $exists=$r && $r->fetch_assoc(); $stmt->close();
                if ($exists) return true;
                $stmt=$db->prepare("INSERT INTO `{$table}` (blocker,blocked) VALUES (?,?)");
            } else {
                $stmt=$db->prepare("SELECT id FROM `{$table}` WHERE user_id=? AND block_userid=? LIMIT 1");
                if (!$stmt) throw new RuntimeException($db->error);
                $stmt->bind_param('ii',$ea,$et); $stmt->execute(); $r=$stmt->get_result(); $exists=$r && $r->fetch_assoc(); $stmt->close();
                if ($exists) return true;
                $stmt=$db->prepare("INSERT INTO `{$table}` (user_id,block_userid) VALUES (?,?)");
            }
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$ea,$et);
            if (!$stmt->execute()) { $e=$stmt->error ?: $db->error; $stmt->close(); throw new RuntimeException($platform.' block insert failed: '.$e); }
            $stmt->close();
        } else {
            if ($platform==='streams') $stmt=$db->prepare("DELETE FROM `{$table}` WHERE blocker=? AND blocked=?");
            else $stmt=$db->prepare("DELETE FROM `{$table}` WHERE user_id=? AND block_userid=?");
            if (!$stmt) throw new RuntimeException($db->error);
            $stmt->bind_param('ii',$ea,$et);
            if (!$stmt->execute()) { $e=$stmt->error ?: $db->error; $stmt->close(); throw new RuntimeException($platform.' block delete failed: '.$e); }
            $stmt->close();
        }
    }

    private function project_follow_pair($a,$b) {
        $l=$this->get_ledger($a,$b); $p=$this->pair($a,$b);
        $fa=!empty($l['follow_a_to_b']); $fb=!empty($l['follow_b_to_a']);
        $this->project_external_follow($p[0],$p[1],$fa,'streams');
        $this->project_external_follow($p[1],$p[0],$fb,'streams');
        $this->project_social_direction($p[0],$p[1]);
        $this->project_social_direction($p[1],$p[0]);
    }

    private function cascade_block($actor,$target,$uuid) {
        $p=$this->pair($actor,$target);
        if (function_exists('friends_check_friendship_status') && function_exists('friends_remove_friend')) {
            if (friends_check_friendship_status($actor,$target)==='is_friend') {
                $this->push_internal(); try { friends_remove_friend($actor,$target); } finally { $this->pop_internal(); }
            }
        }
        if (function_exists('bp_stop_following')) {
            foreach (array(array($actor,$target),array($target,$actor)) as $d) {
                if (function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$d[1],'follower_id'=>$d[0]))) {
                    $this->push_internal(); try { bp_stop_following(array('leader_id'=>$d[1],'follower_id'=>$d[0])); } finally { $this->pop_internal(); }
                }
            }
        }
        $this->save_ledger($p[0],$p[1],array(
            'connection_state'=>'none','requested_by'=>0,'follow_a_to_b'=>0,'follow_b_to_a'=>0
        ),$uuid);
    }

    private function handle_bb_block($operation,$actor,$target,$moderation_id=0) {
        if ($this->internal() || $actor<1 || $target<1 || $actor===$target) return;
        $uuid=wp_generate_uuid4();
        if (!$this->acquire_lock($actor,$target)) {
            $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$operation,$actor,$target,array('moderation_id'=>$moderation_id));
            $this->mark_retry($uuid,'Pair lock busy.'); return;
        }
        try {
            $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$operation,$actor,$target,array('moderation_id'=>$moderation_id));
            if ($operation===self::OP_BLOCK) {
                $this->cascade_block($actor,$target,$uuid);
                $this->save_ledger($actor,$target,array(
                    $this->block_field($actor,$target)=>1,'connection_state'=>'none','requested_by'=>0,
                    'follow_a_to_b'=>0,'follow_b_to_a'=>0
                ),$uuid);
                $this->project_external_block($actor,$target,true,'streams');
                $this->project_external_block($actor,$target,true,'socials');
                $this->project_follow_pair($actor,$target);
            } else {
                $this->save_ledger($actor,$target,array($this->block_field($actor,$target)=>0),$uuid);
                $this->project_external_block($actor,$target,false,'streams');
                $this->project_external_block($actor,$target,false,'socials');
            }
            $this->mark_processed($uuid);
        } catch (Throwable $e) {
            $this->mark_retry($uuid,$e->getMessage());
            $this->log('BuddyBoss block synchronization failed',array('operation'=>$operation,'actor'=>$actor,'target'=>$target,'error'=>$e->getMessage()));
        } finally { $this->release_lock($actor,$target); }
    }

    private function block_field($actor,$target) {
        $p=$this->pair($actor,$target);
        return ((int)$actor===$p[0]) ? 'block_a_to_b' : 'block_b_to_a';
    }

    private function handle_bb_follow($operation,$follow) {
        if ($this->internal()) return;
        $actor=0;$target=0;
        if (is_object($follow)) { $actor=absint(isset($follow->follower_id)?$follow->follower_id:0); $target=absint(isset($follow->leader_id)?$follow->leader_id:0); }
        elseif (is_array($follow)) { $actor=absint(isset($follow['follower_id'])?$follow['follower_id']:0); $target=absint(isset($follow['leader_id'])?$follow['leader_id']:0); }
        if ($actor<1 || $target<1 || $actor===$target) { $this->log('Invalid BuddyBoss follow object',array('operation'=>$operation)); return; }
        if ($operation===self::OP_FOLLOW && $this->bb_blocked($actor,$target)) { $this->log('Blocked follow ignored',array('actor'=>$actor,'target'=>$target)); return; }
        $uuid=wp_generate_uuid4();
        if (!$this->acquire_lock($actor,$target)) { $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$operation,$actor,$target); $this->mark_retry($uuid,'Pair lock busy.'); return; }
        try {
            $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$operation,$actor,$target);
            $p=$this->pair($actor,$target);
            $field=((int)$actor===$p[0])?'follow_a_to_b':'follow_b_to_a';
            $this->save_ledger($actor,$target,array($field=>$operation===self::OP_FOLLOW?1:0),$uuid);
            $this->project_external_follow($actor,$target,$operation===self::OP_FOLLOW,'streams');
            $this->project_social_direction($actor,$target);
            $this->mark_processed($uuid);
        } catch(Throwable $e) {
            $this->mark_retry($uuid,$e->getMessage());
            $this->log('BuddyBoss follow projection failed',array('operation'=>$operation,'actor'=>$actor,'target'=>$target,'error'=>$e->getMessage()));
        } finally { $this->release_lock($actor,$target); }
    }

    public function bb_requested($id,$initiator,$friend,$friendship=null) {
        if ($this->internal()) return;
        $this->connection_event(self::OP_CONNECTION_REQUEST,(int)$initiator,(int)$friend,(int)$id);
    }
    public function bb_accepted($id,$initiator,$friend,$friendship=null) {
        if ($this->internal()) return;
        $this->connection_event(self::OP_CONNECTION_ACCEPT,(int)$initiator,(int)$friend,(int)$id);
    }
    public function bb_rejected($id,$friendship) {
        if ($this->internal()) return;
        $a=is_object($friendship)?absint($friendship->initiator_user_id):0;
        $b=is_object($friendship)?absint($friendship->friend_user_id):0;
        if ($a&&$b) $this->connection_event(self::OP_CONNECTION_REJECT,$a,$b,(int)$id);
    }
    public function bb_withdrawn($id,$friendship) {
        if ($this->internal()) return;
        $a=is_object($friendship)?absint($friendship->initiator_user_id):0;
        $b=is_object($friendship)?absint($friendship->friend_user_id):0;
        if ($a&&$b) $this->connection_event(self::OP_CONNECTION_WITHDRAW,$a,$b,(int)$id);
    }
    public function bb_deleted($id,$initiator,$friend) {
        if ($this->internal()) return;
        $this->connection_event(self::OP_CONNECTION_REMOVE,(int)$initiator,(int)$friend,(int)$id);
    }
    public function bb_post_deleted($initiator,$friend) {
        if ($this->internal()) return;
        $this->connection_event(self::OP_CONNECTION_REMOVE,(int)$initiator,(int)$friend,0);
    }

    private function connection_event($op,$initiator,$friend,$source_id=0) {
        if ($initiator<1||$friend<1||$initiator===$friend) return;
        $uuid=wp_generate_uuid4();
        if (!$this->acquire_lock($initiator,$friend)) { $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$op,$initiator,$friend,array('source_id'=>$source_id)); $this->mark_retry($uuid,'Pair lock busy.'); return; }
        try {
            $this->insert_event($uuid,self::ORIGIN_WORDPRESS,$op,$initiator,$friend,array('source_id'=>$source_id));
            switch($op) {
                case self::OP_CONNECTION_REQUEST:
                    $this->save_ledger($initiator,$friend,array('connection_state'=>'requested','requested_by'=>$initiator),$uuid);
                    break;
                case self::OP_CONNECTION_ACCEPT:
                    $this->save_ledger($initiator,$friend,array('connection_state'=>'connected','requested_by'=>$initiator),$uuid);
                    break;
                default:
                    $this->save_ledger($initiator,$friend,array('connection_state'=>'none','requested_by'=>0),$uuid);
                    break;
            }
            $this->project_social_direction($initiator,$friend);
            $this->project_social_direction($friend,$initiator);
            $this->mark_processed($uuid);
        } catch(Throwable $e) {
            $this->mark_retry($uuid,$e->getMessage());
            $this->log('BuddyBoss connection projection failed',array('operation'=>$op,'actor'=>$initiator,'target'=>$friend,'error'=>$e->getMessage()));
        } finally { $this->release_lock($initiator,$friend); }
    }

    public function bb_follow_started($follow) { $this->handle_bb_follow(self::OP_FOLLOW,$follow); }
    public function bb_follow_stopped($follow) { $this->handle_bb_follow(self::OP_UNFOLLOW,$follow); }

    public function bb_moderation_saved($moderation) {
        if (!$this->is_member_block($moderation)) return;
        $this->handle_bb_block(self::OP_BLOCK,absint($moderation->user_id),absint($moderation->item_id),absint($moderation->id));
    }
    public function bb_moderation_deleted($moderation) {
        if (!$this->is_member_block($moderation)) return;
        $this->handle_bb_block(self::OP_UNBLOCK,absint($moderation->user_id),absint($moderation->item_id),absint($moderation->id));
    }
    private function is_member_block($m) {
        return is_object($m) && isset($m->item_type) && $m->item_type==='user'
            && empty($m->user_report) && absint($m->user_id)>0 && absint($m->item_id)>0;
    }

    private function run_as_user($user_id,$callback) {
        $user_id=absint($user_id);
        if ($user_id<1||!is_callable($callback)) return new WP_Error('bzj_invalid_user','Valid WordPress user required.');
        $previous=get_current_user_id();
        wp_set_current_user($user_id);
        try { return call_user_func($callback); }
        catch(Throwable $e) { return new WP_Error('bzj_user_context_exception',$e->getMessage()); }
        finally { wp_set_current_user($previous); }
    }

    private function process_external($op,$actor,$target,$uuid) {
        switch($op) {
            case self::OP_CONNECTION_REQUEST:
                if ($this->bb_blocked($actor,$target)) return new WP_Error('bzj_blocked','Blocked users cannot connect.');
                if (!function_exists('friends_add_friend')) return new WP_Error('bzj_unavailable','BuddyBoss connection functions unavailable.');
                $status=friends_check_friendship_status($actor,$target);
                if (in_array($status,array('is_friend','pending'),true)) return true;
                if ($status==='awaiting_response') return new WP_Error('bzj_reverse_request','Reverse pending request exists.');
                return friends_add_friend($actor,$target) ? true : new WP_Error('bzj_request_failed','BuddyBoss rejected the request.');

            case self::OP_CONNECTION_ACCEPT:
                if (!function_exists('friends_accept_friendship')) return new WP_Error('bzj_unavailable','BuddyBoss acceptance unavailable.');
                $fid=function_exists('friends_get_friendship_id')?(int)friends_get_friendship_id($target,$actor):0;
                if (!$fid) return new WP_Error('bzj_missing_request','Pending connection not found.');
                $r=$this->run_as_user($actor,function()use($fid){return friends_accept_friendship($fid);});
                if (is_wp_error($r)) return $r;
                if (!$r && friends_check_friendship_status($actor,$target)!=='is_friend') return new WP_Error('bzj_accept_failed','BuddyBoss could not accept the request.');
                return true;

            case self::OP_CONNECTION_REJECT:
                if (!function_exists('friends_reject_friendship')) return new WP_Error('bzj_unavailable','BuddyBoss rejection unavailable.');
                $fid=function_exists('friends_get_friendship_id')?(int)friends_get_friendship_id($target,$actor):0;
                if (!$fid) return true;
                $r=$this->run_as_user($actor,function()use($fid){return friends_reject_friendship($fid);});
                if (is_wp_error($r)) return $r;
                if (!$r && friends_check_friendship_status($actor,$target)!=='not_friends') return new WP_Error('bzj_reject_failed','BuddyBoss could not reject the request.');
                return true;

            case self::OP_CONNECTION_WITHDRAW:
                if (!function_exists('friends_withdraw_friendship')) return new WP_Error('bzj_unavailable','BuddyBoss withdrawal unavailable.');
                $r=$this->run_as_user($actor,function()use($actor,$target){return friends_withdraw_friendship($actor,$target);});
                if (is_wp_error($r)) return $r;
                if (!$r && friends_check_friendship_status($actor,$target)!=='not_friends') return new WP_Error('bzj_withdraw_failed','BuddyBoss could not withdraw the request.');
                return true;

            case self::OP_CONNECTION_REMOVE:
                if (!function_exists('friends_remove_friend')) return new WP_Error('bzj_unavailable','BuddyBoss removal unavailable.');
                if (friends_check_friendship_status($actor,$target)==='is_friend') {
                    $r=$this->run_as_user($actor,function()use($actor,$target){return friends_remove_friend($actor,$target);});
                    if (is_wp_error($r)) return $r;
                    if (!$r && friends_check_friendship_status($actor,$target)==='is_friend') return new WP_Error('bzj_remove_failed','BuddyBoss could not remove the connection.');
                }
                return true;

            case self::OP_FOLLOW:
                if ($this->bb_blocked($actor,$target)) return new WP_Error('bzj_blocked','Blocked users cannot follow.');
                if (!function_exists('bp_start_following')) return new WP_Error('bzj_unavailable','BuddyBoss follow unavailable.');
                if (function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) return true;
                $r=bp_start_following(array('leader_id'=>$target,'follower_id'=>$actor));
                if (is_wp_error($r)) return $r;
                if ($r===false && function_exists('bp_is_following') && !bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) return new WP_Error('bzj_follow_failed','BuddyBoss rejected the follow.');
                return true;

            case self::OP_UNFOLLOW:
                if (!function_exists('bp_stop_following')) return new WP_Error('bzj_unavailable','BuddyBoss unfollow unavailable.');
                if (function_exists('bp_is_following') && !bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) return true;
                $r=bp_stop_following(array('leader_id'=>$target,'follower_id'=>$actor));
                if (is_wp_error($r)) return $r;
                if ($r===false && function_exists('bp_is_following') && bp_is_following(array('leader_id'=>$target,'follower_id'=>$actor))) return new WP_Error('bzj_unfollow_failed','BuddyBoss rejected the unfollow.');
                return true;

            case self::OP_BLOCK:
                if (!class_exists('BP_Moderation') || !class_exists('BP_Moderation_Members')) return new WP_Error('bzj_unavailable','BuddyBoss moderation unavailable.');
                $ledger=$this->get_ledger($actor,$target);
                $field=$this->block_field($actor,$target);
                if (!empty($ledger[$field])) return true;
                $m=new BP_Moderation($target,BP_Moderation_Members::$moderation_type,$actor);
                $m->user_report=0; $m->content='';
                $r=$this->run_as_user($actor,function()use($m){return $m->save();});
                if (is_wp_error($r)) return $r;
                if (!$r) return new WP_Error('bzj_block_failed','BuddyBoss could not create the block.');
                /* bp_moderation_after_save performs the canonical destructive cascade. */
                return true;

            case self::OP_UNBLOCK:
                if (!class_exists('BP_Moderation') || !class_exists('BP_Moderation_Members')) return new WP_Error('bzj_unavailable','BuddyBoss moderation unavailable.');
                $m=new BP_Moderation($target,BP_Moderation_Members::$moderation_type,$actor);
                if (empty($m->id)) return true;
                $r=$this->run_as_user($actor,function()use($m){return $m->delete(true);});
                if (is_wp_error($r)) return $r;
                if (!$r) return new WP_Error('bzj_unblock_failed','BuddyBoss could not remove the block.');
                /* bb_moderation_after_delete removes only the external block. */
                return true;
        }
        return new WP_Error('bzj_unknown_operation','Unsupported operation.');
    }

    public function register_rest_routes() {
        register_rest_route(self::REST_NAMESPACE,self::REST_ROUTE,array(
            'methods'=>WP_REST_Server::CREATABLE,
            'callback'=>array($this,'rest_command'),
            'permission_callback'=>'__return_true'
        ));
        register_rest_route(self::REST_NAMESPACE,self::REST_ROUTE,array(
            'methods'=>WP_REST_Server::READABLE,
            'callback'=>array($this,'rest_status'),
            'permission_callback'=>function(){return current_user_can('manage_options');}
        ));
        register_rest_route(self::REST_NAMESPACE,self::REST_ROUTE.'/diagnostics',array(
            'methods'=>WP_REST_Server::READABLE,
            'callback'=>array($this,'rest_diagnostics'),
            'permission_callback'=>function(){return current_user_can('manage_options');}
        ));
    }

    private function secret() {
        if (function_exists('bzj_get_sso_secret')) { $s=bzj_get_sso_secret(); if ($s) return $s; }
        if (defined('BUZZ_SSO_SECRET') && BUZZ_SSO_SECRET) return BUZZ_SSO_SECRET;
        $s=getenv('BUZZ_SSO_SECRET'); return $s?$s:'';
    }

    private function authenticate($request,$raw) {
        $ts=$request->get_header('X-BZJ-Timestamp'); $sig=$request->get_header('X-BZJ-Signature');
        if (!$ts||!$sig||!ctype_digit((string)$ts)) return new WP_Error('bzj_auth_missing','Missing authentication headers.',array('status'=>401));
        if (abs(time()-(int)$ts)>self::TIMESTAMP_WINDOW) return new WP_Error('bzj_auth_expired','Request timestamp expired.',array('status'=>401));
        $secret=$this->secret();
        if (!$secret) return new WP_Error('bzj_auth_unavailable','Synchronization secret unavailable.',array('status'=>503));
        $expected=hash_hmac('sha256',(int)$ts.'.'.$raw,$secret);
        if (!hash_equals($expected,trim($sig))) return new WP_Error('bzj_auth_invalid','Invalid request signature.',array('status'=>401));
        return true;
    }

    public function rest_command(WP_REST_Request $request) {
        $raw=$request->get_body();
        $auth=$this->authenticate($request,$raw); if (is_wp_error($auth)) return $auth;
        $p=json_decode($raw,true);
        if (!is_array($p)) return new WP_Error('bzj_invalid_json','Malformed JSON.',array('status'=>400));
        $origin=sanitize_key($p['origin']??''); $op=sanitize_key($p['operation']??''); $event=sanitize_text_field($p['event_id']??'');
        $actor_ext=absint($p['actor_id']??0); $target_ext=absint($p['target_id']??0);
        if (!in_array($origin,array(self::ORIGIN_STREAMS,self::ORIGIN_SOCIALS),true)) return new WP_Error('bzj_origin','Invalid origin.',array('status'=>400));
        $allowed=array(self::OP_CONNECTION_REQUEST,self::OP_CONNECTION_ACCEPT,self::OP_CONNECTION_REJECT,self::OP_CONNECTION_WITHDRAW,self::OP_CONNECTION_REMOVE,self::OP_FOLLOW,self::OP_UNFOLLOW,self::OP_BLOCK,self::OP_UNBLOCK);
        if (!in_array($op,$allowed,true)) return new WP_Error('bzj_operation','Invalid operation.',array('status'=>400));
        if (!$this->uuid($event)) return new WP_Error('bzj_event','Valid UUID required.',array('status'=>400));
        if ($actor_ext<1||$target_ext<1||$actor_ext===$target_ext) return new WP_Error('bzj_users','Invalid actor/target.',array('status'=>400));
        if (isset($p['authenticated_id']) && absint($p['authenticated_id'])!==$actor_ext) return new WP_Error('bzj_actor_mismatch','Authenticated actor mismatch.',array('status'=>403));
        $actor=$this->map_external_to_wp($origin,$actor_ext); $target=$this->map_external_to_wp($origin,$target_ext);
        if ($actor<1||$target<1||$actor===$target) return new WP_Error('bzj_mapping','External users could not be mapped.',array('status'=>409));
        if ($this->event_exists($event)) return rest_ensure_response(array('success'=>true,'status'=>'already_processed','event_id'=>$event,'relationship'=>$this->get_ledger($actor,$target)));
        if (!$this->acquire_lock($actor,$target)) return new WP_Error('bzj_busy','Relationship pair is busy.',array('status'=>503));
        try {
            $this->insert_event($event,$origin,$op,$actor,$target,$p);
            $result=$this->process_external($op,$actor,$target,$event);
            if (is_wp_error($result)) { $this->mark_retry($event,$result->get_error_message()); return $result; }
            /* External action fires BuddyBoss hooks; those hooks update the canonical ledger and projections. */
            $this->mark_processed($event);
            return rest_ensure_response(array('success'=>true,'status'=>'processed','event_id'=>$event,'relationship'=>$this->get_ledger($actor,$target)));
        } catch(Throwable $e) {
            $this->mark_retry($event,$e->getMessage());
            $this->log('REST command failed',array('event_id'=>$event,'origin'=>$origin,'operation'=>$op,'actor'=>$actor,'target'=>$target,'error'=>$e->getMessage()));
            return new WP_Error('bzj_internal','Synchronization command failed.',array('status'=>500,'event_id'=>$event));
        } finally { $this->release_lock($actor,$target); }
    }

    public function rest_status(WP_REST_Request $r) {
        $a=absint($r->get_param('user_a')); $b=absint($r->get_param('user_b'));
        if ($a<1||$b<1||$a===$b) return new WP_Error('bzj_pair','Two valid different WP user IDs are required.',array('status'=>400));
        return rest_ensure_response(array('success'=>true,'version'=>self::VERSION,'relationship'=>$this->get_ledger($a,$b)));
    }

    public function rest_diagnostics() {
        $stream=$this->streams_db(); $social=$this->socials_db();
        $log=trailingslashit(ABSPATH).self::LOG_DIR.'/'.self::LOG_FILE;
        return rest_ensure_response(array(
            'plugin_version'=>self::VERSION,
            'route_registered'=>true,
            'cron'=>array(
                'recovery_next'=>wp_next_scheduled(self::CRON_HOOK)?gmdate('c',wp_next_scheduled(self::CRON_HOOK)):null,
                'reconcile_next'=>wp_next_scheduled(self::RECONCILE_HOOK)?gmdate('c',wp_next_scheduled(self::RECONCILE_HOOK)):null
            ),
            'log'=>array('path'=>$log,'exists'=>file_exists($log),'directory_exists'=>is_dir(dirname($log)),'directory_writable'=>is_dir(dirname($log))&&is_writable(dirname($log))),
            'databases'=>array(
                'streams_connected'=>(bool)$stream,
                'socials_connected'=>(bool)$social,
                'streams_followers_table'=>$stream?$this->external_follow_table($stream,'streams'):false,
                'streams_blocks_table'=>$stream?$this->external_block_table($stream,'streams'):false,
                'socials_followers_table'=>$social?$this->external_follow_table($social,'socials'):false,
                'socials_blocks_table'=>$social?$this->external_block_table($social,'socials'):false
            )
        ));
    }

    public function process_recovery() {
        global $wpdb;
        $rows=$wpdb->get_results("SELECT * FROM {$this->events_table} WHERE status='pending' AND next_attempt_at<=UTC_TIMESTAMP() ORDER BY id ASC LIMIT 25");
        foreach((array)$rows as $row) $this->recover_event($row);
    }

    private function recover_event($row) {
        $a=absint($row->actor_wp_id); $b=absint($row->target_wp_id); $op=$row->operation; $uuid=$row->event_uuid;
        if (!$this->acquire_lock($a,$b)) return;
        try {
            /* Never roll BuddyBoss backward during recovery. Re-read canonical state and re-project it. */
            if ($op===self::OP_FOLLOW || $op===self::OP_UNFOLLOW) {
                $this->project_follow_pair($a,$b);
            } elseif ($op===self::OP_BLOCK) {
                $l=$this->get_ledger($a,$b);
                $blocked=((int)$l[$this->block_field($a,$b)]===1);
                $this->project_external_block($a,$b,$blocked,'streams');
                $this->project_external_block($a,$b,$blocked,'socials');
                $this->project_follow_pair($a,$b);
                $this->project_social_direction($a,$b);
                $this->project_social_direction($b,$a);
            } else {
                $this->project_social_direction($a,$b);
                $this->project_social_direction($b,$a);
                $this->project_follow_pair($a,$b);
            }
            $this->mark_processed($uuid);
        } catch(Throwable $e) {
            $this->mark_retry($uuid,$e->getMessage());
            $this->log('Recovery failed',array('event_id'=>$uuid,'error'=>$e->getMessage()));
        } finally { $this->release_lock($a,$b); }
    }

    public function reconcile_recent() {
        global $wpdb;
        $rows=$wpdb->get_results("SELECT user_a,user_b FROM {$this->ledger_table} ORDER BY updated_at DESC LIMIT 100");
        foreach((array)$rows as $r) {
            $a=(int)$r->user_a; $b=(int)$r->user_b;
            if (!$this->acquire_lock($a,$b)) continue;
            try {
                $this->project_social_direction($a,$b);
                $this->project_social_direction($b,$a);
                $this->project_follow_pair($a,$b);
            } catch(Throwable $e) {
                $this->log('Reconciliation failed',array('actor'=>$a,'target'=>$b,'error'=>$e->getMessage()));
            } finally { $this->release_lock($a,$b); }
        }
    }
}

BZJ_Connections_Sync::instance();
