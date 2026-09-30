<?php
/**
 * Plugin Name: Buzzjuice Connections Sync
 * Description: Canonical synchronization of BuddyBoss connections, follows and moderation across Buzzjuice WordPress, Streams and Socials.
 * Version: 5.0.0
 * Author: Buzzjuice
 * License: GPL-2.0-or-later
 *
 * Architecture
 * - WordPress/BuddyBoss is the canonical authority.
 * - External platforms never load WordPress with wp-load.
 * - External actions are reported to the WordPress REST endpoint with HMAC-SHA256.
 * - WordPress applies external requests through native BuddyBoss APIs, preserving
 *   connection-request restrictions.
 * - Canonical state is reconciled to both external databases asynchronously.
 * - Blocks are directional and always dominate relationships.
 * - Unblocking never restores an old friendship/connection.
 * - UUIDs make reports idempotent; the durable event queue provides retries.
 * - Diagnostics use a file; operational state uses two small WordPress tables.
 */

defined('ABSPATH') || exit;

if (!defined('BZJ_CONNECTIONS_SYNC_VERSION')) define('BZJ_CONNECTIONS_SYNC_VERSION', '5.0.0');
if (!defined('BZJ_REL_NONE')) define('BZJ_REL_NONE', 'none');
if (!defined('BZJ_REL_REQUESTED')) define('BZJ_REL_REQUESTED', 'requested');
if (!defined('BZJ_REL_CONNECTED')) define('BZJ_REL_CONNECTED', 'connected');
if (!defined('BZJ_ORIGIN_WORDPRESS')) define('BZJ_ORIGIN_WORDPRESS', 'wordpress');
if (!defined('BZJ_ORIGIN_STREAMS')) define('BZJ_ORIGIN_STREAMS', 'streams');
if (!defined('BZJ_ORIGIN_SOCIALS')) define('BZJ_ORIGIN_SOCIALS', 'socials');
if (!defined('BZJ_OP_REQUEST')) define('BZJ_OP_REQUEST', 'request');
if (!defined('BZJ_OP_ACCEPT')) define('BZJ_OP_ACCEPT', 'accept');
if (!defined('BZJ_OP_REMOVE')) define('BZJ_OP_REMOVE', 'remove');
if (!defined('BZJ_OP_BLOCK')) define('BZJ_OP_BLOCK', 'block');
if (!defined('BZJ_OP_UNBLOCK')) define('BZJ_OP_UNBLOCK', 'unblock');
if (!defined('BZJ_EVENT_PENDING')) define('BZJ_EVENT_PENDING', 'pending');
if (!defined('BZJ_EVENT_RETRY')) define('BZJ_EVENT_RETRY', 'retry');
if (!defined('BZJ_EVENT_PROCESSED')) define('BZJ_EVENT_PROCESSED', 'processed');
if (!defined('BZJ_EVENT_FAILED')) define('BZJ_EVENT_FAILED', 'failed');
if (!defined('BZJ_CONNECTIONS_SYNC_NS')) define('BZJ_CONNECTIONS_SYNC_NS', 'bzj/v4');
if (!defined('BZJ_CONNECTIONS_SYNC_MAX_RETRIES')) define('BZJ_CONNECTIONS_SYNC_MAX_RETRIES', 8);
if (!defined('BZJ_CONNECTIONS_SYNC_RETRY_BASE')) define('BZJ_CONNECTIONS_SYNC_RETRY_BASE', 60);
if (!defined('BZJ_CONNECTIONS_SYNC_HMAC_WINDOW')) define('BZJ_CONNECTIONS_SYNC_HMAC_WINDOW', 300);
if (!defined('BZJ_CONNECTIONS_SYNC_LOG')) define('BZJ_CONNECTIONS_SYNC_LOG', WP_CONTENT_DIR . '/bzj-connections-sync.log');

add_action('init', 'bzj_connections_sync_boot', 1);

function bzj_connections_sync_boot() {
    bzj_connections_sync_install();
    bzj_connections_sync_register_hooks();
    bzj_connections_sync_register_cron();
}

function bzj_connections_sync_log($message, $context = array()) {
    $line = '[' . gmdate('Y-m-d H:i:s') . '] ' . $message;
    if ($context) {
        $line .= ' ' . wp_json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    @error_log($line . PHP_EOL, 3, BZJ_CONNECTIONS_SYNC_LOG);
}

function bzj_connections_sync_rel_table() {
    global $wpdb;
    return $wpdb->prefix . 'bzj_relationships';
}
function bzj_connections_sync_event_table() {
    global $wpdb;
    return $wpdb->prefix . 'bzj_relationship_events';
}

function bzj_connections_sync_install() {
    global $wpdb;
    $version = get_option('bzj_connections_sync_schema_version', '');
    if ($version === BZJ_CONNECTIONS_SYNC_VERSION) return;

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $charset = $wpdb->get_charset_collate();
    $rel = bzj_connections_sync_rel_table();
    $evt = bzj_connections_sync_event_table();

    $sql = "CREATE TABLE {$rel} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_a BIGINT UNSIGNED NOT NULL,
        user_b BIGINT UNSIGNED NOT NULL,
        relationship_state VARCHAR(20) NOT NULL DEFAULT 'none',
        requested_by BIGINT UNSIGNED NULL,
        block_a_to_b TINYINT(1) NOT NULL DEFAULT 0,
        block_b_to_a TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL,
        last_event_uuid CHAR(36) NULL,
        PRIMARY KEY (id),
        UNIQUE KEY relationship_pair (user_a,user_b),
        KEY relationship_state (relationship_state),
        KEY requested_by (requested_by),
        KEY block_a_to_b (block_a_to_b),
        KEY block_b_to_a (block_b_to_a),
        KEY updated_at (updated_at)
    ) {$charset};

    CREATE TABLE {$evt} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        event_uuid CHAR(36) NOT NULL,
        origin VARCHAR(20) NOT NULL,
        operation VARCHAR(20) NOT NULL,
        actor_wp_id BIGINT UNSIGNED NOT NULL,
        target_wp_id BIGINT UNSIGNED NOT NULL,
        payload LONGTEXT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'pending',
        attempts INT UNSIGNED NOT NULL DEFAULT 0,
        next_attempt_at DATETIME NOT NULL,
        last_error TEXT NULL,
        created_at DATETIME NOT NULL,
        processed_at DATETIME NULL,
        PRIMARY KEY (id),
        UNIQUE KEY event_uuid (event_uuid),
        KEY retry_lookup (status,next_attempt_at),
        KEY pair_lookup (actor_wp_id,target_wp_id),
        KEY created_at (created_at)
    ) {$charset};";

    dbDelta($sql);
    update_option('bzj_connections_sync_schema_version', BZJ_CONNECTIONS_SYNC_VERSION, false);
    bzj_connections_sync_log('Schema installed/updated', array('version' => BZJ_CONNECTIONS_SYNC_VERSION));
}

function bzj_connections_sync_uuid() {
    return wp_generate_uuid4();
}

function bzj_connections_sync_pair($a, $b) {
    $a = absint($a); $b = absint($b);
    if (!$a || !$b || $a === $b) return false;
    return $a < $b ? array('user_a'=>$a,'user_b'=>$b) : array('user_a'=>$b,'user_b'=>$a);
}

function bzj_connections_sync_lock($a, $b, $timeout = 5) {
    global $wpdb;
    $pair = bzj_connections_sync_pair($a, $b);
    if (!$pair) return false;
    $name = 'bzj_rel_' . $pair['user_a'] . '_' . $pair['user_b'];
    return (int)$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,%d)', $name, $timeout)) === 1 ? $name : false;
}
function bzj_connections_sync_unlock($lock) {
    global $wpdb;
    if ($lock) $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock));
}

function bzj_connections_sync_get($a, $b) {
    global $wpdb;
    $pair = bzj_connections_sync_pair($a, $b);
    if (!$pair) return false;
    return $wpdb->get_row($wpdb->prepare(
        'SELECT * FROM ' . bzj_connections_sync_rel_table() . ' WHERE user_a=%d AND user_b=%d LIMIT 1',
        $pair['user_a'], $pair['user_b']
    ));
}

function bzj_connections_sync_set_state($actor, $target, $state, $options = array()) {
    global $wpdb;
    $pair = bzj_connections_sync_pair($actor, $target);
    if (!$pair || !in_array($state, array(BZJ_REL_NONE,BZJ_REL_REQUESTED,BZJ_REL_CONNECTED), true)) return false;

    $lock = bzj_connections_sync_lock($actor, $target);
    if (!$lock) return false;
    try {
        $existing = bzj_connections_sync_get($actor, $target);
        $now = gmdate('Y-m-d H:i:s');
        $ba = array_key_exists('block_a_to_b',$options) ? (int)$options['block_a_to_b'] : ($existing ? (int)$existing->block_a_to_b : 0);
        $bb = array_key_exists('block_b_to_a',$options) ? (int)$options['block_b_to_a'] : ($existing ? (int)$existing->block_b_to_a : 0);
        $requested_by = $state === BZJ_REL_REQUESTED
            ? (array_key_exists('requested_by',$options) ? absint($options['requested_by']) : ($existing ? absint($existing->requested_by) : 0))
            : 0;
        $uuid = !empty($options['event_uuid']) ? sanitize_text_field($options['event_uuid']) : bzj_connections_sync_uuid();

        $data = array(
            'relationship_state'=>$state,
            'requested_by'=>$requested_by ?: null,
            'block_a_to_b'=>$ba,
            'block_b_to_a'=>$bb,
            'updated_at'=>$now,
            'last_event_uuid'=>$uuid,
        );
        if ($existing) {
            $ok = $wpdb->update(bzj_connections_sync_rel_table(), $data,
                array('user_a'=>$pair['user_a'],'user_b'=>$pair['user_b']),
                array('%s','%d','%d','%d','%s','%s'), array('%d','%d'));
        } else {
            $data['user_a']=$pair['user_a']; $data['user_b']=$pair['user_b']; $data['created_at']=$now;
            $ok = $wpdb->insert(bzj_connections_sync_rel_table(), $data,
                array('%s','%d','%d','%d','%s','%s','%d','%d','%s'));
        }
        if ($ok === false) {
            bzj_connections_sync_log('Canonical relationship write failed', array('error'=>$wpdb->last_error,'actor'=>$actor,'target'=>$target));
            return false;
        }
        return true;
    } finally { bzj_connections_sync_unlock($lock); }
}

function bzj_connections_sync_set_block($blocker, $blocked, $is_block, $event_uuid = '') {
    $pair = bzj_connections_sync_pair($blocker, $blocked);
    if (!$pair) return false;
    $existing = bzj_connections_sync_get($blocker, $blocked);
    $ba = $existing ? (int)$existing->block_a_to_b : 0;
    $bb = $existing ? (int)$existing->block_b_to_a : 0;
    if ($pair['user_a'] === absint($blocker)) $ba = $is_block ? 1 : 0;
    else $bb = $is_block ? 1 : 0;

    // Blocks dominate. If either directional block exists, there is no relationship.
    $state = ($is_block || $ba || $bb) ? BZJ_REL_NONE : BZJ_REL_NONE;
    return bzj_connections_sync_set_state($blocker, $blocked, $state, array(
        'block_a_to_b'=>$ba,
        'block_b_to_a'=>$bb,
        'event_uuid'=>$event_uuid ?: bzj_connections_sync_uuid(),
    ));
}

function bzj_connections_sync_queue_event($origin, $operation, $actor, $target, $payload = array(), $uuid = '') {
    global $wpdb;
    $uuid = $uuid ?: bzj_connections_sync_uuid();
    $table = bzj_connections_sync_event_table();
    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$table} WHERE event_uuid=%s LIMIT 1", $uuid));
    if ($existing) return (int)$existing;
    $now = gmdate('Y-m-d H:i:s');
    $ok = $wpdb->insert($table, array(
        'event_uuid'=>$uuid,
        'origin'=>sanitize_key($origin),
        'operation'=>sanitize_key($operation),
        'actor_wp_id'=>absint($actor),
        'target_wp_id'=>absint($target),
        'payload'=>wp_json_encode($payload),
        'status'=>BZJ_EVENT_PENDING,
        'attempts'=>0,
        'next_attempt_at'=>$now,
        'created_at'=>$now,
    ), array('%s','%s','%s','%d','%d','%s','%s','%d','%s','%s'));
    if (!$ok) {
        bzj_connections_sync_log('Event queue insert failed', array('error'=>$wpdb->last_error,'uuid'=>$uuid));
        return false;
    }
    return (int)$wpdb->insert_id;
}

function bzj_connections_sync_canonical_reconcile_enqueue($a, $b, $reason) {
    $row = bzj_connections_sync_get($a,$b);
    if (!$row) return false;
    return bzj_connections_sync_queue_event(
        BZJ_ORIGIN_WORDPRESS,
        'reconcile',
        $row->user_a,
        $row->user_b,
        array(
            'reason'=>sanitize_key($reason),
            'relationship_state'=>$row->relationship_state,
            'requested_by'=>(int)$row->requested_by,
            'block_a_to_b'=>(int)$row->block_a_to_b,
            'block_b_to_a'=>(int)$row->block_b_to_a,
        )
    );
}

function bzj_connections_sync_register_hooks() {
    static $done = false;
    if ($done) return;
    $done = true;

    add_action('friends_friendship_requested', 'bzj_connections_sync_wp_requested', 20, 3);
    add_action('friends_friendship_accepted', 'bzj_connections_sync_wp_accepted', 20, 3);
    add_action('friends_friendship_rejected', 'bzj_connections_sync_wp_rejected', 20, 3);
    add_action('friends_friendship_deleted', 'bzj_connections_sync_wp_deleted', 20, 3);
    add_action('bp_moderation_block_created', 'bzj_connections_sync_wp_block_created', 20, 2);
    add_action('bp_moderation_block_deleted', 'bzj_connections_sync_wp_block_deleted', 20, 2);
}

function bzj_connections_sync_wp_requested($friendship_id, $initiator_id, $friend_id) {
    $initiator_id=absint($initiator_id); $friend_id=absint($friend_id);
    if (!$initiator_id || !$friend_id) return;
    $row=bzj_connections_sync_get($initiator_id,$friend_id);
    $uuid=bzj_connections_sync_uuid();
    $blocked=$row && ((int)$row->block_a_to_b || (int)$row->block_b_to_a);
    if (!$blocked) bzj_connections_sync_set_state($initiator_id,$friend_id,BZJ_REL_REQUESTED,array('requested_by'=>$initiator_id,'event_uuid'=>$uuid));
    bzj_connections_sync_queue_event(BZJ_ORIGIN_WORDPRESS,BZJ_OP_REQUEST,$initiator_id,$friend_id,array('friendship_id'=>absint($friendship_id),'canonical'=>'requested'),$uuid);
    bzj_connections_sync_canonical_reconcile_enqueue($initiator_id,$friend_id,'wp_request');
}

function bzj_connections_sync_wp_accepted($friendship_id, $initiator_id, $friend_id) {
    $initiator_id=absint($initiator_id); $friend_id=absint($friend_id);
    if (!$initiator_id || !$friend_id) return;
    $uuid=bzj_connections_sync_uuid();
    $row=bzj_connections_sync_get($initiator_id,$friend_id);
    if (!$row || (!(int)$row->block_a_to_b && !(int)$row->block_b_to_a)) {
        bzj_connections_sync_set_state($initiator_id,$friend_id,BZJ_REL_CONNECTED,array('event_uuid'=>$uuid));
    }
    bzj_connections_sync_queue_event(BZJ_ORIGIN_WORDPRESS,BZJ_OP_ACCEPT,$friend_id,$initiator_id,array('friendship_id'=>absint($friendship_id),'canonical'=>'connected'),$uuid);
    bzj_connections_sync_canonical_reconcile_enqueue($initiator_id,$friend_id,'wp_accept');
}

function bzj_connections_sync_wp_rejected($friendship_id, $initiator_id, $friend_id) {
    bzj_connections_sync_wp_deleted($friendship_id,$initiator_id,$friend_id);
}

function bzj_connections_sync_wp_deleted($friendship_id, $initiator_id, $friend_id) {
    $initiator_id=absint($initiator_id); $friend_id=absint($friend_id);
    if (!$initiator_id || !$friend_id) return;
    $row=bzj_connections_sync_get($initiator_id,$friend_id);
    $uuid=bzj_connections_sync_uuid();
    if ($row && !(int)$row->block_a_to_b && !(int)$row->block_b_to_a) {
        bzj_connections_sync_set_state($initiator_id,$friend_id,BZJ_REL_NONE,array('event_uuid'=>$uuid));
    }
    bzj_connections_sync_queue_event(BZJ_ORIGIN_WORDPRESS,BZJ_OP_REMOVE,$initiator_id,$friend_id,array('friendship_id'=>absint($friendship_id),'canonical'=>'none'),$uuid);
    bzj_connections_sync_canonical_reconcile_enqueue($initiator_id,$friend_id,'wp_remove');
}

function bzj_connections_sync_wp_block_created($block_id, $block_object) {
    if (!is_object($block_object)) return;
    $blocker=absint($block_object->user_id); $blocked=absint($block_object->item_id);
    if (!$blocker || !$blocked || $blocker===$blocked) return;
    // BuddyBoss itself has already created the block. Remove any existing friendship without suppressing hooks.
    if (function_exists('friends_check_friendship_status') && function_exists('friends_remove_friend')) {
        if (friends_check_friendship_status($blocker,$blocked)==='is_friend') friends_remove_friend($blocker,$blocked);
        if (friends_check_friendship_status($blocked,$blocker)==='is_friend') friends_remove_friend($blocked,$blocker);
    }
    $uuid=bzj_connections_sync_uuid();
    if (bzj_connections_sync_set_block($blocker,$blocked,true,$uuid)) {
        bzj_connections_sync_queue_event(BZJ_ORIGIN_WORDPRESS,BZJ_OP_BLOCK,$blocker,$blocked,array('block_id'=>absint($block_id)), $uuid);
        bzj_connections_sync_canonical_reconcile_enqueue($blocker,$blocked,'wp_block');
    }
}

function bzj_connections_sync_wp_block_deleted($block_id, $block_object) {
    if (!is_object($block_object)) return;
    $blocker=absint($block_object->user_id); $blocked=absint($block_object->item_id);
    if (!$blocker || !$blocked || $blocker===$blocked) return;
    $uuid=bzj_connections_sync_uuid();
    if (bzj_connections_sync_set_block($blocker,$blocked,false,$uuid)) {
        bzj_connections_sync_queue_event(BZJ_ORIGIN_WORDPRESS,BZJ_OP_UNBLOCK,$blocker,$blocked,array('block_id'=>absint($block_id)), $uuid);
        bzj_connections_sync_canonical_reconcile_enqueue($blocker,$blocked,'wp_unblock');
    }
}

function bzj_connections_sync_secret() {
    if (function_exists('bzj_get_sso_secret')) {
        $secret=bzj_get_sso_secret();
        if ($secret) return (string)$secret;
    }
    if (defined('BUZZ_SSO_SECRET') && BUZZ_SSO_SECRET) return (string)BUZZ_SSO_SECRET;
    $env=getenv('BUZZ_SSO_SECRET');
    return $env ? (string)$env : '';
}

function bzj_connections_sync_rest_auth($request, &$payload = null) {
    $secret=bzj_connections_sync_secret();
    if (!$secret) return new WP_Error('bzj_sync_config','Missing synchronization secret',array('status'=>503));
    $timestamp=(string)$request->get_header('x-bzj-timestamp');
    $signature=(string)$request->get_header('x-bzj-signature');
    if ($timestamp==='' || !ctype_digit($timestamp) || abs(time()-(int)$timestamp)>BZJ_CONNECTIONS_SYNC_HMAC_WINDOW) {
        return new WP_Error('bzj_sync_timestamp','Expired or invalid request timestamp',array('status'=>401));
    }
    $body=$request->get_body();
    $expected=hash_hmac('sha256',$timestamp.'.'.$body,$secret);
    if (!$signature || !hash_equals($expected,$signature)) return new WP_Error('bzj_sync_auth','Unauthorized',array('status'=>401));
    $payload=json_decode($body,true);
    if (!is_array($payload)) return new WP_Error('bzj_sync_json','Invalid JSON',array('status'=>400));
    return true;
}

add_action('rest_api_init', function() {
    register_rest_route(BZJ_CONNECTIONS_SYNC_NS,'/relationship',array(
        'methods'=>'POST',
        'callback'=>'bzj_connections_sync_rest_relationship',
        'permission_callback'=>'__return_true',
    ));
});

function bzj_connections_sync_rest_relationship(WP_REST_Request $request) {
    $payload=null;
    $auth=bzj_connections_sync_rest_auth($request,$payload);
    if (is_wp_error($auth)) return $auth;
    $origin=sanitize_key($payload['origin']??'');
    $actor_ext=absint($payload['origin_user_id']??0);
    $target_ext=absint($payload['target_user_id']??0);
    $operation=sanitize_key($payload['operation']??'');
    $uuid=sanitize_text_field($payload['event_uuid']??'');
    if (!in_array($origin,array(BZJ_ORIGIN_STREAMS,BZJ_ORIGIN_SOCIALS),true) || !$actor_ext || !$target_ext || !$uuid || !in_array($operation,array(BZJ_OP_REQUEST,BZJ_OP_ACCEPT,BZJ_OP_REMOVE,BZJ_OP_BLOCK,BZJ_OP_UNBLOCK),true)) {
        return new WP_Error('bzj_sync_payload','Invalid synchronization payload',array('status'=>400));
    }
    $actor=bzj_connections_sync_resolve_wp($origin,$actor_ext);
    $target=bzj_connections_sync_resolve_wp($origin,$target_ext);
    if (!$actor || !$target || $actor===$target) return new WP_Error('bzj_sync_identity','User mapping not found',array('status'=>409));

    global $wpdb;
    $existing=$wpdb->get_row($wpdb->prepare('SELECT * FROM '.bzj_connections_sync_event_table().' WHERE event_uuid=%s LIMIT 1',$uuid));
    if ($existing) return rest_ensure_response(array('success'=>true,'duplicate'=>true,'event_id'=>(int)$existing->id));

    $result=bzj_connections_sync_apply_external_operation($origin,$operation,$actor,$target,$uuid);
    if (!$result) return new WP_Error('bzj_sync_rejected','Canonical WordPress operation was not accepted',array('status'=>409));
    return rest_ensure_response(array('success'=>true,'duplicate'=>false,'event_id'=>$result));
}

function bzj_connections_sync_resolve_wp($origin,$external_id) {
    $meta=$origin===BZJ_ORIGIN_STREAMS?'wo_user_id':'qd_user_id';
    $users=get_users(array('meta_key'=>$meta,'meta_value'=>(string)absint($external_id),'number'=>1,'fields'=>'ids'));
    return !empty($users)?absint($users[0]):0;
}

function bzj_connections_sync_apply_external_operation($origin,$operation,$actor,$target,$uuid) {
    $row=bzj_connections_sync_get($actor,$target);
    if ($row && ((int)$row->block_a_to_b || (int)$row->block_b_to_a) && $operation!==BZJ_OP_UNBLOCK && $operation!==BZJ_OP_BLOCK) {
        bzj_connections_sync_queue_event($origin,$operation,$actor,$target,array('rejected'=>'canonical_block'),$uuid);
        return false;
    }

    switch ($operation) {
        case BZJ_OP_REQUEST:
            if (!function_exists('friends_add_friend')) return false;
            $status=function_exists('friends_check_friendship_status')?friends_check_friendship_status($actor,$target):'';
            if ($status==='is_friend' || $status==='pending') {
                $id=bzj_connections_sync_queue_event($origin,$operation,$actor,$target,array('status'=>$status),$uuid);
                return $id ?: false;
            }
            // Deliberately use native BuddyBoss policy. Do not force-accept or bypass restrictions.
            $ok=friends_add_friend($actor,$target,false);
            if (!$ok) {
                bzj_connections_sync_log('External request rejected by BuddyBoss policy',array('origin'=>$origin,'actor'=>$actor,'target'=>$target));
                return false;
            }
            return bzj_connections_sync_queue_event($origin,$operation,$actor,$target,array('status'=>'pending'),$uuid);

        case BZJ_OP_ACCEPT:
            if (!function_exists('friends_accept_friendship')) return false;
            $id=0;
            if (function_exists('friends_get_friendship_id')) {
                $id=absint(friends_get_friendship_id($actor,$target));
                if (!$id) $id=absint(friends_get_friendship_id($target,$actor));
            } elseif (class_exists('BP_Friends_Friendship') && method_exists('BP_Friends_Friendship','get_friendship_id')) {
                $id=absint(BP_Friends_Friendship::get_friendship_id($actor,$target));
                if (!$id) $id=absint(BP_Friends_Friendship::get_friendship_id($target,$actor));
            }
            if (!$id) return false;
            return friends_accept_friendship($id) ? (bzj_connections_sync_queue_event($origin,$operation,$actor,$target,array('friendship_id'=>$id),$uuid) ?: false) : false;

        case BZJ_OP_REMOVE:
            if (!function_exists('friends_remove_friend')) return false;
            $status1=function_exists('friends_check_friendship_status')?friends_check_friendship_status($actor,$target):'';
            $status2=function_exists('friends_check_friendship_status')?friends_check_friendship_status($target,$actor):'';
            $ok1=$status1==='not_friends'?true:(bool)friends_remove_friend($actor,$target);
            $ok2=$status2==='not_friends'?true:(bool)friends_remove_friend($target,$actor);
            if (!$ok1 && !$ok2) return false;
            return bzj_connections_sync_queue_event($origin,$operation,$actor,$target,array('status'=>'removed'),$uuid) ?: false;

        case BZJ_OP_BLOCK:
            $ok=bzj_connections_sync_create_wp_block($actor,$target,$origin);
            return $ok ? (bzj_connections_sync_queue_event($origin,$operation,$actor,$target,array('status'=>'blocked'),$uuid) ?: false) : false;

        case BZJ_OP_UNBLOCK:
            $ok=bzj_connections_sync_delete_wp_block($actor,$target,$origin);
            return $ok ? (bzj_connections_sync_queue_event($origin,$operation,$actor,$target,array('status'=>'unblocked'),$uuid) ?: false) : false;
    }
    return false;
}

function bzj_connections_sync_member_moderation_class() {
    if (!class_exists('BP_Moderation_Members')) return false;
    if (!class_exists('BP_Moderation_Abstract')) return false;
    $type=property_exists('BP_Moderation_Members','moderation_type') ? BP_Moderation_Members::$moderation_type : 'member';
    $class=BP_Moderation_Abstract::get_class($type);
    return $class ? $class : false;
}

function bzj_connections_sync_create_wp_block($blocker,$blocked,$origin='external') {
    if (!function_exists('bp_moderation_is_user_blocked')) return false;
    if (bp_moderation_is_user_blocked($blocked,$blocker)) return true;
    $class=bzj_connections_sync_member_moderation_class();
    if (!$class) return false;
    $type=property_exists('BP_Moderation_Members','moderation_type') ? BP_Moderation_Members::$moderation_type : 'member';
    $args=array('content_id'=>absint($blocked),'content_type'=>$type,'user_id'=>absint($blocker));
    foreach (array('block','create') as $method) {
        if (!method_exists($class,$method)) continue;
        try {
            $result=call_user_func(array($class,$method),$args);
            if ($result) return true;
        } catch (Throwable $e) {
            bzj_connections_sync_log('BuddyBoss block method failed',array('method'=>$method,'error'=>$e->getMessage(),'origin'=>$origin));
        }
    }
    bzj_connections_sync_log('No compatible BuddyBoss member-block method found',array('class'=>$class,'origin'=>$origin));
    return false;
}

function bzj_connections_sync_delete_wp_block($blocker,$blocked,$origin='external') {
    if (function_exists('bp_moderation_is_user_blocked') && !bp_moderation_is_user_blocked($blocked,$blocker)) return true;
    $class=bzj_connections_sync_member_moderation_class();
    if (!$class) return false;
    $type=property_exists('BP_Moderation_Members','moderation_type') ? BP_Moderation_Members::$moderation_type : 'member';
    $args=array('content_id'=>absint($blocked),'content_type'=>$type,'user_id'=>absint($blocker));
    foreach (array('unblock','delete','remove') as $method) {
        if (!method_exists($class,$method)) continue;
        try {
            $result=call_user_func(array($class,$method),$args);
            if ($result || (function_exists('bp_moderation_is_user_blocked') && !bp_moderation_is_user_blocked($blocked,$blocker))) return true;
        } catch (Throwable $e) {
            bzj_connections_sync_log('BuddyBoss unblock method failed',array('method'=>$method,'error'=>$e->getMessage(),'origin'=>$origin));
        }
    }
    return false;
}

function bzj_connections_sync_external_db($platform) {
    $file=ABSPATH.'shared/db_helpers.php';
    if (file_exists($file)) require_once $file;
    if ($platform===BZJ_ORIGIN_STREAMS && function_exists('get_wowonder_db')) return get_wowonder_db();
    if ($platform===BZJ_ORIGIN_SOCIALS && function_exists('get_qd_db_conn')) return get_qd_db_conn();
    return false;
}
function bzj_connections_sync_table($env,$default) {
    $value=getenv($env);
    return ($value && preg_match('/^[A-Za-z0-9_]+$/',$value)) ? $value : $default;
}
function bzj_connections_sync_ident($name) { return '`'.str_replace('`','',(string)$name).'`'; }

function bzj_connections_sync_reconcile_streams($a,$b,$row) {
    $db=bzj_connections_sync_external_db(BZJ_ORIGIN_STREAMS);
    if (!$db || $db->connect_errno) return false;
    $followers=bzj_connections_sync_ident(bzj_connections_sync_table('BUZZ_STREAMS_FOLLOWERS_TABLE','Wo_Followers'));
    $blocks=bzj_connections_sync_ident(bzj_connections_sync_table('BUZZ_STREAMS_BLOCKS_TABLE','Wo_Blocks'));
    return bzj_connections_sync_apply_external_state($db,$followers,$blocks,$a,$b,$row,'streams');
}
function bzj_connections_sync_reconcile_socials($a,$b,$row) {
    $db=bzj_connections_sync_external_db(BZJ_ORIGIN_SOCIALS);
    if (!$db || $db->connect_errno) return false;
    $followers=bzj_connections_sync_ident(bzj_connections_sync_table('BUZZ_SOCIALS_FOLLOWERS_TABLE','followers'));
    $blocks=bzj_connections_sync_ident(bzj_connections_sync_table('BUZZ_SOCIALS_BLOCKS_TABLE','blocks'));
    return bzj_connections_sync_apply_external_state($db,$followers,$blocks,$a,$b,$row,'socials');
}

function bzj_connections_sync_apply_external_state($db,$followers,$blocks,$a,$b,$row,$platform) {
    $a=absint($a); $b=absint($b);
    $pairs=array(array($a,$b),array($b,$a));

    // Always remove both directions first. Canonical state is then rebuilt.
    foreach ($pairs as $p) {
        $stmt=$db->prepare("DELETE FROM {$followers} WHERE follower_id=? AND following_id=?");
        if (!$stmt) return false;
        $stmt->bind_param('ii',$p[0],$p[1]);
        if (!$stmt->execute()) { $stmt->close(); return false; }
        $stmt->close();
    }
    foreach ($pairs as $p) {
        $sql=$platform==='socials'
            ? "DELETE FROM {$blocks} WHERE user_id=? AND block_userid=?"
            : "DELETE FROM {$blocks} WHERE blocker=? AND blocked=?";
        $stmt=$db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param('ii',$p[0],$p[1]);
        if (!$stmt->execute()) { $stmt->close(); return false; }
        $stmt->close();
    }

    $ua=(int)$row->user_a; $ub=(int)$row->user_b;
    $map=array($ua=>$a,$ub=>$b);
    if (!isset($map[$ua]) || !isset($map[$ub])) return false;
    $block_ab=(int)$row->block_a_to_b;
    $block_ba=(int)$row->block_b_to_a;

    if ($block_ab && !bzj_connections_sync_insert_block($db,$blocks,$map[$ua],$map[$ub],$platform)) return false;
    if ($block_ba && !bzj_connections_sync_insert_block($db,$blocks,$map[$ub],$map[$ua],$platform)) return false;
    if ($block_ab || $block_ba) return true;

    if ($row->relationship_state===BZJ_REL_CONNECTED) {
        if (!bzj_connections_sync_insert_follow($db,$followers,$a,$b)) return false;
        if (!bzj_connections_sync_insert_follow($db,$followers,$b,$a)) return false;
    } elseif ($row->relationship_state===BZJ_REL_REQUESTED && (int)$row->requested_by) {
        $requester=(int)$row->requested_by;
        $requester_ext=isset($map[$requester])?$map[$requester]:0;
        $recipient_wp=$requester===$ua?$ub:$ua;
        $recipient_ext=isset($map[$recipient_wp])?$map[$recipient_wp]:0;
        if (!$requester_ext || !$recipient_ext) return false;
        if (!bzj_connections_sync_insert_follow($db,$followers,$recipient_ext,$requester_ext,0)) return false;
    }
    return true;
}
function bzj_connections_sync_insert_follow($db,$table,$following,$follower,$active=1) {
    if (strpos($table,'`')===false) return false;
    $sql="INSERT INTO {$table} (following_id,follower_id,active) VALUES (?,?,?)";
    // Avoid relying on a unique key being present: remove the exact row first.
    $del=$db->prepare("DELETE FROM {$table} WHERE following_id=? AND follower_id=?");
    if (!$del) return false;
    $del->bind_param('ii',$following,$follower); $del->execute(); $del->close();
    $stmt=$db->prepare($sql); if (!$stmt) return false;
    $stmt->bind_param('iii',$following,$follower,$active); $ok=$stmt->execute(); $stmt->close();
    return (bool)$ok;
}
function bzj_connections_sync_insert_block($db,$table,$blocker,$blocked,$platform) {
    if ($platform==='socials') {
        $stmt=$db->prepare("INSERT INTO {$table} (user_id,block_userid,created_at) VALUES (?,?,?)");
        if (!$stmt) return false;
        $created=time(); $stmt->bind_param('iii',$blocker,$blocked,$created); $ok=$stmt->execute(); $stmt->close(); return (bool)$ok;
    }
    $stmt=$db->prepare("INSERT INTO {$table} (blocker,blocked) VALUES (?,?)");
    if (!$stmt) return false;
    $stmt->bind_param('ii',$blocker,$blocked); $ok=$stmt->execute(); $stmt->close(); return (bool)$ok;
}

function bzj_connections_sync_process_events($limit = 50) {
    global $wpdb;

    $limit = max(1, min(50, absint($limit)));
    $table = bzj_connections_sync_event_table();

    $events = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
             FROM {$table}
             WHERE status IN ('pending','retry')
               AND next_attempt_at <= UTC_TIMESTAMP()
             ORDER BY created_at ASC
             LIMIT %d",
            $limit
        )
    );

    foreach ((array) $events as $event) {

        $attempt = (int) $event->attempts + 1;

        if ($attempt > BZJ_CONNECTIONS_SYNC_MAX_RETRIES) {

            $wpdb->update(
                $table,
                array(
                    'status'       => BZJ_EVENT_FAILED,
                    'attempts'     => $attempt,
                    'last_error'   => 'Maximum retry count reached',
                    'processed_at' => gmdate('Y-m-d H:i:s')
                ),
                array(
                    'id' => (int) $event->id
                ),
                array(
                    '%s',
                    '%d',
                    '%s',
                    '%s'
                ),
                array(
                    '%d'
                )
            );

            continue;
        }

        $a = (int) $event->actor_wp_id;
        $b = (int) $event->target_wp_id;

        /*
         * Always reconcile against the current canonical WordPress
         * relationship state, not merely the historical event.
         */
        $row = bzj_connections_sync_get($a, $b);

        if (!$row) {

            $wpdb->update(
                $table,
                array(
                    'status'       => BZJ_EVENT_PROCESSED,
                    'attempts'     => $attempt,
                    'processed_at' => gmdate('Y-m-d H:i:s'),
                    'last_error'   => null
                ),
                array(
                    'id' => (int) $event->id
                ),
                array(
                    '%s',
                    '%d',
                    '%s',
                    '%s'
                ),
                array(
                    '%d'
                )
            );

            continue;
        }

        $ok_streams = true;
        $ok_socials = true;

        $wo_a = absint(
            get_user_meta(
                $a,
                'wo_user_id',
                true
            )
        );

        $wo_b = absint(
            get_user_meta(
                $b,
                'wo_user_id',
                true
            )
        );

        $qd_a = absint(
            get_user_meta(
                $a,
                'qd_user_id',
                true
            )
        );

        $qd_b = absint(
            get_user_meta(
                $b,
                'qd_user_id',
                true
            )
        );

        if ($wo_a && $wo_b) {
            $ok_streams = bzj_connections_sync_reconcile_streams(
                $wo_a,
                $wo_b,
                $row
            );
        }

        if ($qd_a && $qd_b) {
            $ok_socials = bzj_connections_sync_reconcile_socials(
                $qd_a,
                $qd_b,
                $row
            );
        }

        if ($ok_streams && $ok_socials) {

            $wpdb->update(
                $table,
                array(
                    'status'       => BZJ_EVENT_PROCESSED,
                    'attempts'     => $attempt,
                    'processed_at' => gmdate('Y-m-d H:i:s'),
                    'last_error'   => null
                ),
                array(
                    'id' => (int) $event->id
                ),
                array(
                    '%s',
                    '%d',
                    '%s',
                    '%s'
                ),
                array(
                    '%d'
                )
            );

        } else {

            $delay = min(
                3600,
                BZJ_CONNECTIONS_SYNC_RETRY_BASE *
                pow(
                    2,
                    max(0, $attempt - 1)
                )
            );

            $wpdb->update(
                $table,
                array(
                    'status'         => BZJ_EVENT_RETRY,
                    'attempts'       => $attempt,
                    'next_attempt_at' =>
                        gmdate(
                            'Y-m-d H:i:s',
                            time() + $delay
                        ),
                    'last_error' =>
                        'One or more external platform reconciliations failed'
                ),
                array(
                    'id' => (int) $event->id
                ),
                array(
                    '%s',
                    '%d',
                    '%s',
                    '%s'
                ),
                array(
                    '%d'
                )
            );
        }
    }
}


function bzj_connections_sync_register_cron() {
    static $done = false;

    if ($done) {
        return;
    }

    $done = true;

    add_filter(
        'cron_schedules',
        function ($schedules) {

            if (!isset($schedules['bzj_five_minutes'])) {

                $schedules['bzj_five_minutes'] = array(
                    'interval' => 300,
                    'display'  => 'Every 5 minutes'
                );
            }

            return $schedules;
        }
    );

    if (
        !wp_next_scheduled(
            'bzj_connections_sync_process_events'
        )
    ) {

        wp_schedule_event(
            time() + 60,
            'bzj_five_minutes',
            'bzj_connections_sync_process_events'
        );
    }

    add_action(
        'bzj_connections_sync_process_events',
        function () {
            bzj_connections_sync_process_events(50);
        }
    );
}


/*
 * Opportunistically process a small number of due events during
 * normal WordPress requests.
 *
 * This is intentionally probabilistic so that high-traffic requests
 * do not all attempt reconciliation.
 *
 * IMPORTANT:
 * Do not use wp_rand() here. The queue processor should not depend
 * on a WordPress random-number helper during shutdown.
 */
add_action(
    'shutdown',
    function () {

        if (
            function_exists('wp_doing_ajax') &&
            wp_doing_ajax()
        ) {
            return;
        }

        if (
            function_exists('wp_doing_cron') &&
            wp_doing_cron()
        ) {
            return;
        }

        static $ran = false;

        if ($ran) {
            return;
        }

        $ran = true;

        /*
         * PHP-native random_int() avoids dependency on wp_rand().
         */
        try {
            if (random_int(1, 20) !== 1) {
                return;
            }
        } catch (Throwable $e) {
            /*
             * If secure randomness is unavailable, simply skip
             * opportunistic processing. WP-Cron remains responsible
             * for normal queue processing.
             */
            return;
        }

        bzj_connections_sync_process_events(5);

    },
    99
);
function bzj_connections_sync_process_events_limited($limit=5) {
    global $wpdb;
    $table=bzj_connections_sync_event_table();
    $events=$wpdb->get_results($wpdb->prepare("SELECT * FROM {$table} WHERE status IN ('pending','retry') AND next_attempt_at<=UTC_TIMESTAMP() ORDER BY created_at ASC LIMIT %d",absint($limit)));
    if (!$events) return;
    // Reuse the full processor; the full processor caps itself at 50.
    bzj_connections_sync_process_events();
}