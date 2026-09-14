<?php
/**
 * Plugin Name: Buzzjuice Connections Sync
 * Description: Canonical synchronization of BuddyBoss connections and moderation across Buzzjuice WordPress, Streams and Socials.
 * Version: 4.1.0
 * Author: Buzzjuice
 * License: GPL-2.0-or-later
 *
 * Architecture:
 * - WordPress/BuddyBoss is the sole canonical authority.
 * - BuddyBoss request restrictions remain authoritative; external requests are
 *   submitted through friends_add_friend() and are never force-accepted.
 * - Connections and blocks are modeled separately.
 * - Blocks are directional and always clear the canonical relationship.
 * - External fan-out reconciles current canonical state, not the historical
 *   event payload. This makes retries and out-of-order events safe.
 * - External platforms report only after their own local DB write succeeds.
 * - HMAC-SHA256 authenticates external reports.
 * - A durable WordPress event queue provides retries; diagnostics use a file.
 *
 * IMPORTANT:
 * This plugin intentionally does not load WordPress from Streams or Socials.
 * External platforms call the authenticated REST endpoint only.
 */

defined('ABSPATH') || exit;

if (!defined('BZJ_CONNECTIONS_SYNC_VERSION')) define('BZJ_CONNECTIONS_SYNC_VERSION', '4.1.0');

define('BZJ_REL_NONE',      'none');
define('BZJ_REL_REQUESTED', 'requested');
define('BZJ_REL_CONNECTED', 'connected');

define('BZJ_ORIGIN_WORDPRESS', 'wordpress');
define('BZJ_ORIGIN_STREAMS',   'streams');
define('BZJ_ORIGIN_SOCIALS',   'socials');

define('BZJ_OP_REQUEST', 'request');
define('BZJ_OP_ACCEPT',  'accept');
define('BZJ_OP_REMOVE',  'remove');
define('BZJ_OP_BLOCK',   'block');
define('BZJ_OP_UNBLOCK', 'unblock');

define('BZJ_EVENT_PENDING',   'pending');
define('BZJ_EVENT_RETRY',     'retry');
define('BZJ_EVENT_PROCESSED', 'processed');
define('BZJ_EVENT_FAILED',    'failed');

if (!defined('BZJ_CONNECTIONS_SYNC_LOG')) {
    define('BZJ_CONNECTIONS_SYNC_LOG', WP_CONTENT_DIR . '/bzj-connections-sync.log');
}
if (!defined('BZJ_CONNECTIONS_SYNC_NS')) {
    define('BZJ_CONNECTIONS_SYNC_NS', 'bzj/v3');
}
if (!defined('BZJ_CONNECTIONS_SYNC_MAX_RETRIES')) {
    define('BZJ_CONNECTIONS_SYNC_MAX_RETRIES', 8);
}
if (!defined('BZJ_CONNECTIONS_SYNC_RETRY_BASE')) {
    define('BZJ_CONNECTIONS_SYNC_RETRY_BASE', 60);
}

/* --------------------------------------------------------------------------
 * Boot
 * -------------------------------------------------------------------------- */
add_action('init', 'bzj_connections_sync_boot', 1);

function bzj_connections_sync_boot() {
    bzj_connections_sync_install();
    bzj_connections_sync_register_hooks();
    bzj_connections_sync_register_rest();
    bzj_connections_sync_register_cron();
}

/* --------------------------------------------------------------------------
 * Logging
 * -------------------------------------------------------------------------- */
function bzj_connections_sync_log($message, $context = array()) {
    $line = '[' . gmdate('Y-m-d H:i:s') . '] ' . $message;
    if (!empty($context)) {
        $line .= ' ' . wp_json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    $line .= PHP_EOL;
    @error_log($line, 3, BZJ_CONNECTIONS_SYNC_LOG);
}

/* --------------------------------------------------------------------------
 * Tables / schema
 * -------------------------------------------------------------------------- */
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

    $sql = "
    CREATE TABLE {$rel} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_a BIGINT UNSIGNED NOT NULL,
        user_b BIGINT UNSIGNED NOT NULL,
        relationship_state VARCHAR(20) NOT NULL DEFAULT 'none',
        block_a_to_b TINYINT(1) NOT NULL DEFAULT 0,
        block_b_to_a TINYINT(1) NOT NULL DEFAULT 0,
        requested_by BIGINT UNSIGNED NULL,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL,
        last_event_uuid CHAR(36) NULL,
        PRIMARY KEY (id),
        UNIQUE KEY relationship_pair (user_a,user_b),
        KEY relationship_state (relationship_state),
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
    ) {$charset};
    ";

    dbDelta($sql);
    update_option('bzj_connections_sync_schema_version', BZJ_CONNECTIONS_SYNC_VERSION, false);
    bzj_connections_sync_log('Schema installed/updated', array('version' => BZJ_CONNECTIONS_SYNC_VERSION));
}

/* --------------------------------------------------------------------------
 * State / locks
 * -------------------------------------------------------------------------- */
function bzj_connections_sync_pair($a, $b) {
    $a = absint($a); $b = absint($b);
    if (!$a || !$b || $a === $b) return false;
    return ($a < $b)
        ? array('user_a'=>$a,'user_b'=>$b)
        : array('user_a'=>$b,'user_b'=>$a);
}

function bzj_connections_sync_lock($a, $b, $timeout = 5) {
    global $wpdb;
    $pair = bzj_connections_sync_pair($a, $b);
    if (!$pair) return false;
    $name = 'bzj_rel_' . $pair['user_a'] . '_' . $pair['user_b'];
    $ok = (int)$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,%d)', $name, $timeout));
    return $ok === 1 ? $name : false;
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
        'SELECT * FROM ' . bzj_connections_sync_rel_table() .
        ' WHERE user_a=%d AND user_b=%d LIMIT 1',
        $pair['user_a'], $pair['user_b']
    ));
}

function bzj_connections_sync_uuid() {
    return wp_generate_uuid4();
}

function bzj_connections_sync_internal($set = null) {
    static $depth = 0;
    if ($set === true) $depth++;
    if ($set === false) $depth = max(0, $depth - 1);
    return $depth > 0;
}

/* --------------------------------------------------------------------------
 * Canonical state writer
 * -------------------------------------------------------------------------- */
function bzj_connections_sync_set_state($actor, $target, $state, $options = array()) {
    global $wpdb;

    $pair = bzj_connections_sync_pair($actor, $target);
    if (!$pair || !in_array($state, array(BZJ_REL_NONE,BZJ_REL_REQUESTED,BZJ_REL_CONNECTED), true)) {
        return false;
    }

    $lock = bzj_connections_sync_lock($actor, $target);
    if (!$lock) return false;

    try {
        $existing = bzj_connections_sync_get($actor, $target);
        $now = current_time('mysql', true);

        $ba = array_key_exists('block_a_to_b', $options)
            ? (int)$options['block_a_to_b']
            : ($existing ? (int)$existing->block_a_to_b : 0);
        $bb = array_key_exists('block_b_to_a', $options)
            ? (int)$options['block_b_to_a']
            : ($existing ? (int)$existing->block_b_to_a : 0);

        $requested_by = array_key_exists('requested_by', $options)
            ? absint($options['requested_by'])
            : ($existing ? absint($existing->requested_by) : 0);

        if ($state !== BZJ_REL_REQUESTED) $requested_by = 0;

        $uuid = !empty($options['event_uuid']) ? sanitize_text_field($options['event_uuid']) : bzj_connections_sync_uuid();

        $data = array(
            'relationship_state' => $state,
            'block_a_to_b'       => $ba,
            'block_b_to_a'       => $bb,
            'requested_by'       => $requested_by ?: null,
            'updated_at'         => $now,
            'last_event_uuid'    => $uuid,
        );

        if ($existing) {
            $ok = $wpdb->update(
                bzj_connections_sync_rel_table(),
                $data,
                array('user_a'=>$pair['user_a'],'user_b'=>$pair['user_b']),
                array('%s','%d','%d','%d','%s','%s'),
                array('%d','%d')
            );
        } else {
            $data['user_a'] = $pair['user_a'];
            $data['user_b'] = $pair['user_b'];
            $data['created_at'] = $now;
            $ok = $wpdb->insert(
                bzj_connections_sync_rel_table(),
                $data,
                array('%d','%d','%s','%d','%d','%d','%s','%s','%s')
            );
        }

        if ($ok === false) {
            bzj_connections_sync_log('Canonical state write failed', array('error'=>$wpdb->last_error,'actor'=>$actor,'target'=>$target));
            return false;
        }
        return $uuid;
    } finally {
        bzj_connections_sync_unlock($lock);
    }
}

function bzj_connections_sync_queue_event($uuid, $origin, $operation, $actor, $target) {
    global $wpdb;
    $table = bzj_connections_sync_event_table();

    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$table} WHERE event_uuid=%s LIMIT 1", $uuid));
    if ($existing) return (int)$existing;

    $now = current_time('mysql', true);
    $ok = $wpdb->insert($table, array(
        'event_uuid'=>$uuid,
        'origin'=>sanitize_key($origin),
        'operation'=>sanitize_key($operation),
        'actor_wp_id'=>absint($actor),
        'target_wp_id'=>absint($target),
        'status'=>BZJ_EVENT_PENDING,
        'attempts'=>0,
        'next_attempt_at'=>$now,
        'created_at'=>$now,
    ), array('%s','%s','%s','%d','%d','%s','%d','%s','%s'));

    if ($ok === false) {
        bzj_connections_sync_log('Event queue insert failed', array('error'=>$wpdb->last_error,'uuid'=>$uuid));
        return false;
    }
    return (int)$wpdb->insert_id;
}

/* --------------------------------------------------------------------------
 * BuddyBoss canonical hooks
 * -------------------------------------------------------------------------- */
function bzj_connections_sync_record_wp_relationship($initiator, $receiver, $state, $operation, $requested_by = 0) {
    if (bzj_connections_sync_internal()) return;

    $uuid = bzj_connections_sync_uuid();
    $opts = array('event_uuid'=>$uuid);
    if ($state === BZJ_REL_REQUESTED) $opts['requested_by'] = absint($requested_by);

    if (!bzj_connections_sync_set_state($initiator, $receiver, $state, $opts)) return;

    bzj_connections_sync_queue_event($uuid, BZJ_ORIGIN_WORDPRESS, $operation, $initiator, $receiver);
    bzj_connections_sync_log('Canonical relationship changed', array(
        'operation'=>$operation,'actor'=>$initiator,'target'=>$receiver,'state'=>$state
    ));
}

function bzj_connections_sync_register_hooks() {
    static $registered = false;
    if ($registered) return;
    $registered = true;

    add_action('friends_friendship_requested', function($friendship) {
        if (is_object($friendship)) {
            bzj_connections_sync_record_wp_relationship(
                $friendship->initiator_user_id, $friendship->friend_user_id,
                BZJ_REL_REQUESTED, BZJ_OP_REQUEST, $friendship->initiator_user_id
            );
        }
    }, 20, 1);

    add_action('friends_friendship_accepted', function($id = 0, $initiator = 0, $friend = 0, $friendship = null) {
        if ($friendship && is_object($friendship)) {
            $initiator = $friendship->initiator_user_id;
            $friend = $friendship->friend_user_id;
        } elseif (is_object($id)) {
            $friendship = $id;
            $initiator = $friendship->initiator_user_id;
            $friend = $friendship->friend_user_id;
        }
        if ($initiator && $friend) {
            bzj_connections_sync_record_wp_relationship($initiator, $friend, BZJ_REL_CONNECTED, BZJ_OP_ACCEPT);
        }
    }, 20, 4);

    add_action('friends_friendship_rejected', function($id = 0, $initiator = 0, $friend = 0, $friendship = null) {
        if ($friendship && is_object($friendship)) {
            $initiator = $friendship->initiator_user_id;
            $friend = $friendship->friend_user_id;
        } elseif (is_object($id)) {
            $friendship = $id;
            $initiator = $friendship->initiator_user_id;
            $friend = $friendship->friend_user_id;
        }
        if ($initiator && $friend) {
            bzj_connections_sync_record_wp_relationship($initiator, $friend, BZJ_REL_NONE, BZJ_OP_REMOVE);
        }
    }, 20, 4);

    add_action('friends_friendship_deleted', function($id = 0, $initiator = 0, $friend = 0, $friendship = null) {
        if ($friendship && is_object($friendship)) {
            $initiator = $friendship->initiator_user_id;
            $friend = $friendship->friend_user_id;
        } elseif (is_object($id)) {
            $friendship = $id;
            $initiator = $friendship->initiator_user_id;
            $friend = $friendship->friend_user_id;
        }
        if ($initiator && $friend) {
            bzj_connections_sync_record_wp_relationship($initiator, $friend, BZJ_REL_NONE, BZJ_OP_REMOVE);
        }
    }, 20, 4);

    add_action('bp_moderation_block_created', 'bzj_connections_sync_wp_block_created', 20, 2);
    add_action('bp_moderation_block_deleted', 'bzj_connections_sync_wp_block_deleted', 20, 2);
}

function bzj_connections_sync_remove_wp_friendship($a, $b) {
    if (!function_exists('friends_check_friendship_status') || !function_exists('friends_remove_friend')) return;

    if (friends_check_friendship_status($a,$b) === 'is_friend') friends_remove_friend($a,$b);
    if (friends_check_friendship_status($b,$a) === 'is_friend') friends_remove_friend($b,$a);

    if (function_exists('friends_get_friendship_id') && function_exists('friends_reject_friendship')) {
        $id = friends_get_friendship_id($a,$b);
        if ($id) friends_reject_friendship($id);
        $id = friends_get_friendship_id($b,$a);
        if ($id) friends_reject_friendship($id);
    }
}

function bzj_connections_sync_wp_block_created($block_id, $block_object) {
    if (bzj_connections_sync_internal() || !is_object($block_object)) return;
    $blocker = absint($block_object->user_id);
    $blocked = absint($block_object->item_id);
    if (!$blocker || !$blocked || $blocker === $blocked) return;

    /*
     * Do not suppress BuddyBoss friendship hooks while removing the
     * relationship. The final block event is queued below and later events
     * are harmless because reconciliation uses current canonical state.
     */
    bzj_connections_sync_remove_wp_friendship($blocker, $blocked);

    $pair = bzj_connections_sync_pair($blocker, $blocked);
    $existing = bzj_connections_sync_get($blocker, $blocked);
    $opts = array('event_uuid'=>bzj_connections_sync_uuid());

    if ($pair['user_a'] === $blocker) $opts['block_a_to_b'] = 1;
    else $opts['block_b_to_a'] = 1;

    /* If an earlier relationship-delete event ran, preserve the new block. */
    $uuid = $opts['event_uuid'];
    if (!bzj_connections_sync_set_state($blocker, $blocked, BZJ_REL_NONE, $opts)) return;

    bzj_connections_sync_queue_event($uuid, BZJ_ORIGIN_WORDPRESS, BZJ_OP_BLOCK, $blocker, $blocked);
    bzj_connections_sync_log('Canonical block created', array('blocker'=>$blocker,'blocked'=>$blocked));
}

function bzj_connections_sync_wp_block_deleted($block_id, $block_object) {
    if (bzj_connections_sync_internal() || !is_object($block_object)) return;
    $blocker = absint($block_object->user_id);
    $blocked = absint($block_object->item_id);
    if (!$blocker || !$blocked || $blocker === $blocked) return;

    $pair = bzj_connections_sync_pair($blocker, $blocked);
    $opts = array('event_uuid'=>bzj_connections_sync_uuid());
    if ($pair['user_a'] === $blocker) $opts['block_a_to_b'] = 0;
    else $opts['block_b_to_a'] = 0;

    /*
     * Unblock never restores the former relationship. It is therefore
     * canonical NONE, unless the opposite-direction block remains active.
     */
    $uuid = $opts['event_uuid'];
    if (!bzj_connections_sync_set_state($blocker, $blocked, BZJ_REL_NONE, $opts)) return;
    bzj_connections_sync_queue_event($uuid, BZJ_ORIGIN_WORDPRESS, BZJ_OP_UNBLOCK, $blocker, $blocked);
    bzj_connections_sync_log('Canonical block removed', array('blocker'=>$blocker,'blocked'=>$blocked));
}

/* --------------------------------------------------------------------------
 * REST authentication and external-origin operations
 * -------------------------------------------------------------------------- */
add_action('rest_api_init', function() {
    register_rest_route(BZJ_CONNECTIONS_SYNC_NS, '/relationship', array(
        'methods'=>'POST',
        'callback'=>'bzj_connections_sync_rest_relationship',
        'permission_callback'=>'__return_true',
    ));
});

function bzj_connections_sync_rest_relationship(WP_REST_Request $request) {
    $timestamp = $request->get_header('X-BZJ-Timestamp');
    $signature = $request->get_header('X-BZJ-Signature');
    $body = $request->get_body();

    if (!$timestamp || !$signature || !$body) {
        return new WP_Error('bzj_invalid_request','Missing authentication data',array('status'=>400));
    }
    if (!ctype_digit((string)$timestamp) || abs(time()-(int)$timestamp) > 300) {
        return new WP_Error('bzj_expired','Request expired',array('status'=>401));
    }

    $secret = function_exists('bzj_get_sso_secret') ? bzj_get_sso_secret() : getenv('BUZZ_SSO_SECRET');
    if (!$secret) return new WP_Error('bzj_auth','Authentication unavailable',array('status'=>503));

    $expected = hash_hmac('sha256', $timestamp.'.'.$body, $secret);
    if (!hash_equals($expected, $signature)) {
        bzj_connections_sync_log('Rejected external report: bad signature');
        return new WP_Error('bzj_auth','Authentication failed',array('status'=>401));
    }

    $p = json_decode($body, true);
    if (!is_array($p)) return new WP_Error('bzj_payload','Invalid JSON',array('status'=>400));

    $origin = sanitize_key($p['origin'] ?? '');
    $actor_platform = absint($p['origin_user_id'] ?? 0);
    $target_platform = absint($p['target_user_id'] ?? 0);
    $operation = sanitize_key($p['operation'] ?? '');
    $uuid = sanitize_text_field($p['event_uuid'] ?? '');

    if (!in_array($origin,array(BZJ_ORIGIN_STREAMS,BZJ_ORIGIN_SOCIALS),true) ||
        !$actor_platform || !$target_platform ||
        !in_array($operation,array(BZJ_OP_REQUEST,BZJ_OP_ACCEPT,BZJ_OP_REMOVE,BZJ_OP_BLOCK,BZJ_OP_UNBLOCK),true)) {
        return new WP_Error('bzj_payload','Invalid relationship payload',array('status'=>400));
    }
    if (!$uuid || !preg_match('/^[0-9a-fA-F-]{36}$/',$uuid)) {
        return new WP_Error('bzj_payload','Invalid event UUID',array('status'=>400));
    }

    $actor = bzj_connections_sync_resolve_wp($origin, $actor_platform);
    $target = bzj_connections_sync_resolve_wp($origin, $target_platform);
    if (!$actor || !$target || $actor === $target) {
        return new WP_Error('bzj_identity','Platform identity could not be resolved',array('status'=>409));
    }

    /* Duplicate reports are idempotent. */
    global $wpdb;
    $existing_event = $wpdb->get_row($wpdb->prepare(
        'SELECT * FROM '.bzj_connections_sync_event_table().' WHERE event_uuid=%s LIMIT 1', $uuid
    ));
    if ($existing_event) {
        return rest_ensure_response(array('success'=>true,'duplicate'=>true,'event_id'=>(int)$existing_event->id));
    }

    $ok = bzj_connections_sync_apply_external($origin,$actor,$target,$operation,$uuid);
    return rest_ensure_response(array('success'=>(bool)$ok));
}

function bzj_connections_sync_resolve_wp($origin, $platform_id) {
    $meta = $origin === BZJ_ORIGIN_STREAMS ? 'wo_user_id' : 'qd_user_id';
    $users = get_users(array(
        'meta_key'=>$meta,
        'meta_value'=>(string)absint($platform_id),
        'number'=>1,
        'fields'=>'ids',
    ));
    return !empty($users) ? absint($users[0]) : 0;
}

function bzj_connections_sync_apply_external($origin,$actor,$target,$operation,$uuid) {
    /*
     * IMPORTANT: no internal/suppression context is used here.
     * BuddyBoss hooks must fire so WordPress becomes canonical.
     */
    if ($operation === BZJ_OP_REQUEST) {
        if (bzj_connections_sync_get($actor,$target) &&
            (bzj_connections_sync_get($actor,$target)->block_a_to_b || bzj_connections_sync_get($actor,$target)->block_b_to_a)) {
            bzj_connections_sync_queue_event($uuid,$origin,$operation,$actor,$target);
            return false;
        }

        if (!function_exists('friends_add_friend')) return false;

        $status = friends_check_friendship_status($actor,$target);
        if ($status === 'is_friend' || $status === 'pending') {
            return true;
        }

        /*
         * This is deliberately false. It preserves BuddyBoss's normal
         * request-policy path instead of force-accepting external follows.
         */
        $result = friends_add_friend($actor,$target,false);
        if ($result) return true;

        /* Policy denied or request could not be created: keep canonical state unchanged. */
        bzj_connections_sync_log('External request rejected by BuddyBoss',array(
            'origin'=>$origin,'actor'=>$actor,'target'=>$target
        ));
        return false;
    }

    if ($operation === BZJ_OP_ACCEPT) {
        if (!function_exists('friends_check_friendship_status') || !function_exists('friends_accept_friendship')) return false;
        $status = friends_check_friendship_status($actor,$target);
        if ($status === 'is_friend') return true;

        $id = function_exists('friends_get_friendship_id')
            ? friends_get_friendship_id($actor,$target)
            : (class_exists('BP_Friends_Friendship') ? BP_Friends_Friendship::get_friendship_id($actor,$target) : 0);

        if (!$id) {
            /* Try the reverse pair because the external actor may be the acceptor. */
            $id = function_exists('friends_get_friendship_id')
                ? friends_get_friendship_id($target,$actor)
                : (class_exists('BP_Friends_Friendship') ? BP_Friends_Friendship::get_friendship_id($target,$actor) : 0);
        }

        if (!$id) return false;
        return (bool)friends_accept_friendship((int)$id);
    }

    if ($operation === BZJ_OP_REMOVE) {
        if (!function_exists('friends_remove_friend')) return false;
        $status1 = function_exists('friends_check_friendship_status') ? friends_check_friendship_status($actor,$target) : '';
        $status2 = function_exists('friends_check_friendship_status') ? friends_check_friendship_status($target,$actor) : '';
        if ($status1 === 'not_friends' && $status2 === 'not_friends') {
            return true;
        }
        $ok1 = ($status1 !== 'not_friends') ? friends_remove_friend($actor,$target) : true;
        $ok2 = ($status2 !== 'not_friends') ? friends_remove_friend($target,$actor) : true;
        return (bool)($ok1 || $ok2);
    }

    if ($operation === BZJ_OP_BLOCK) {
        /*
         * BuddyBoss block-create APIs vary by BuddyBoss release. Do not guess
         * a function name or write an unknown moderation table. Instead use
         * the installed BuddyBoss block implementation through a dedicated
         * filter/action adapter when present.
         *
         * A site-specific adapter can return true after creating the block.
         */
        $handled = apply_filters('bzj_connections_sync_external_block', false, $actor, $target, $origin);
        if ($handled) return true;

        bzj_connections_sync_log('External block requires BuddyBoss block adapter',array(
            'actor'=>$actor,'target'=>$target,'origin'=>$origin
        ));
        return false;
    }

    if ($operation === BZJ_OP_UNBLOCK) {
        $handled = apply_filters('bzj_connections_sync_external_unblock', false, $actor, $target, $origin);
        if ($handled) return true;

        bzj_connections_sync_log('External unblock requires BuddyBoss block adapter',array(
            'actor'=>$actor,'target'=>$target,'origin'=>$origin
        ));
        return false;
    }

    return false;
}

/* --------------------------------------------------------------------------
 * Cron / reconciliation
 * -------------------------------------------------------------------------- */
add_filter('cron_schedules', function($schedules) {
    if (!isset($schedules['bzj_five_minutes'])) {
        $schedules['bzj_five_minutes'] = array('interval'=>300,'display'=>'Every 5 minutes');
    }
    return $schedules;
});

function bzj_connections_sync_register_cron() {
    if (!wp_next_scheduled('bzj_connections_sync_process_events')) {
        wp_schedule_event(time()+300,'bzj_five_minutes','bzj_connections_sync_process_events');
    }
}
add_action('bzj_connections_sync_process_events','bzj_connections_sync_process_events');

function bzj_connections_sync_process_events() {
    global $wpdb;
    $table = bzj_connections_sync_event_table();

    $events = $wpdb->get_results(
        "SELECT * FROM {$table}
         WHERE status IN ('pending','retry')
           AND next_attempt_at <= UTC_TIMESTAMP()
         ORDER BY created_at ASC
         LIMIT 50"
    );
    foreach ((array)$events as $event) {
        if ((int)$event->attempts >= BZJ_CONNECTIONS_SYNC_MAX_RETRIES) {
            $wpdb->update($table,array(
                'status'=>BZJ_EVENT_FAILED,
                'last_error'=>'Maximum retry count reached',
                'processed_at'=>current_time('mysql',true)
            ),array('id'=>$event->id),array('%s','%s','%s'),array('%d'));
            continue;
        }

        $ok = bzj_connections_sync_reconcile_pair((int)$event->actor_wp_id,(int)$event->target_wp_id,$event);
        if ($ok) {
            $wpdb->update($table,array(
                'status'=>BZJ_EVENT_PROCESSED,
                'attempts'=>(int)$event->attempts+1,
                'processed_at'=>current_time('mysql',true),
                'last_error'=>null
            ),array('id'=>$event->id),array('%s','%d','%s','%s'),array('%d'));
        } else {
            $attempt = (int)$event->attempts + 1;
            $delay = min(3600, BZJ_CONNECTIONS_SYNC_RETRY_BASE * pow(2,max(0,$attempt-1)));
            $wpdb->update($table,array(
                'status'=>BZJ_EVENT_RETRY,
                'attempts'=>$attempt,
                'next_attempt_at'=>gmdate('Y-m-d H:i:s',time()+$delay),
                'last_error'=>'One or more external platform reconciliations failed'
            ),array('id'=>$event->id),array('%s','%d','%s','%s'),array('%d'));
        }
    }
}

function bzj_connections_sync_reconcile_pair($actor,$target,$event=null) {
    $row = bzj_connections_sync_get($actor,$target);
    if (!$row) return true;

    $streams_a = absint(get_user_meta($actor,'wo_user_id',true));
    $streams_b = absint(get_user_meta($target,'wo_user_id',true));
    $socials_a = absint(get_user_meta($actor,'qd_user_id',true));
    $socials_b = absint(get_user_meta($target,'qd_user_id',true));

    $ok_streams = true;
    $ok_socials = true;

    if ($streams_a && $streams_b) {
        $ok_streams = bzj_connections_sync_reconcile_streams($streams_a,$streams_b,$row);
    }

    if ($socials_a && $socials_b) {
        $ok_socials = bzj_connections_sync_reconcile_socials($socials_a,$socials_b,$row);
    }

    /*
     * A missing platform identity is not a failure. The platform simply
     * cannot participate until the user's mapping exists.
     */
    return $ok_streams && $ok_socials;
}

/* --------------------------------------------------------------------------
 * External DB helpers
 * -------------------------------------------------------------------------- */
function bzj_connections_sync_external_db($platform) {
    $file = dirname(ABSPATH) . '/shared/db_helpers.php';
    if (file_exists($file)) require_once $file;

    if ($platform === BZJ_ORIGIN_STREAMS && function_exists('get_wowonder_db')) {
        return get_wowonder_db();
    }
    if ($platform === BZJ_ORIGIN_SOCIALS && function_exists('get_qd_db_conn')) {
        return get_qd_db_conn();
    }
    return false;
}

function bzj_connections_sync_table($env_name,$default) {
    $value = getenv($env_name);
    return ($value && preg_match('/^[A-Za-z0-9_]+$/',$value)) ? $value : $default;
}

function bzj_connections_sync_safe_table($name) {
    return '`'.str_replace('`','',$name).'`';
}

function bzj_connections_sync_reconcile_streams($a,$b,$row) {
    $db = bzj_connections_sync_external_db(BZJ_ORIGIN_STREAMS);
    if (!$db) return false;

    $followers = bzj_connections_sync_safe_table(bzj_connections_sync_table('BUZZ_STREAMS_FOLLOWERS_TABLE','Wo_Followers'));
    $blocks    = bzj_connections_sync_safe_table(bzj_connections_sync_table('BUZZ_STREAMS_BLOCKS_TABLE','Wo_Blocks'));

    return bzj_connections_sync_apply_external_state($db,$followers,$blocks,$a,$b,$row,'streams');
}

function bzj_connections_sync_reconcile_socials($a,$b,$row) {
    $db = bzj_connections_sync_external_db(BZJ_ORIGIN_SOCIALS);
    if (!$db) return false;

    $followers = bzj_connections_sync_safe_table(bzj_connections_sync_table('BUZZ_SOCIALS_FOLLOWERS_TABLE','followers'));
    $blocks    = bzj_connections_sync_safe_table(bzj_connections_sync_table('BUZZ_SOCIALS_BLOCKS_TABLE','blocks'));

    return bzj_connections_sync_apply_external_state($db,$followers,$blocks,$a,$b,$row,'socials');
}

function bzj_connections_sync_apply_external_state($db,$followers,$blocks,$a,$b,$row,$platform) {
    $a = (int)$a; $b = (int)$b;
    $ua = $row->user_a == $a ? $a : $b;
    $ub = $row->user_a == $a ? $b : $a;

    $block_ab = ($row->user_a == $ua) ? (int)$row->block_a_to_b : (int)$row->block_b_to_a;
    $block_ba = ($row->user_a == $ub) ? (int)$row->block_a_to_b : (int)$row->block_b_to_a;

    /* Remove all connection rows first. Canonical state is rebuilt below. */
    $pairs = array(array($a,$b),array($b,$a));
    foreach ($pairs as $p) {
        $sql = $db->prepare("DELETE FROM {$followers} WHERE follower_id=? AND following_id=?");
        if (!$sql) return false;
        $sql->bind_param('ii',$p[0],$p[1]);
        if (!$sql->execute()) return false;
        $sql->close();
    }

    /* Remove only the two directional blocks, then recreate canonical ones. */
    foreach ($pairs as $p) {
        $sql = $db->prepare(
            $platform === 'socials'
            ? "DELETE FROM {$blocks} WHERE user_id=? AND block_userid=?"
            : "DELETE FROM {$blocks} WHERE blocker=? AND blocked=?"
        );
        if (!$sql) return false;
        $sql->bind_param('ii',$p[0],$p[1]);
        if (!$sql->execute()) return false;
        $sql->close();
    }

    /* Blocks always dominate relationships. */
    if ($block_ab) {
        if (!bzj_connections_sync_insert_block($db,$blocks,$ua,$ub,$platform)) return false;
    }
    if ($block_ba) {
        if (!bzj_connections_sync_insert_block($db,$blocks,$ub,$ua,$platform)) return false;
    }

    if ($block_ab || $block_ba) return true;

    if ($row->relationship_state === BZJ_REL_CONNECTED) {
        if (!bzj_connections_sync_insert_follow($db,$followers,$a,$b,1)) return false;
        if (!bzj_connections_sync_insert_follow($db,$followers,$b,$a,1)) return false;
    } elseif ($row->relationship_state === BZJ_REL_REQUESTED && (int)$row->requested_by) {
        $requester = (int)$row->requested_by;
        $requester_platform = ($requester === (int)$row->user_a) ? $a : $b;
        $receiver_platform  = ($requester === (int)$row->user_a) ? $b : $a;
        if (!bzj_connections_sync_insert_follow($db,$followers,$requester_platform,$receiver_platform,0)) return false;
    }

    return true;
}

function bzj_connections_sync_insert_follow($db,$table,$follower,$following,$active) {
    $sql = $db->prepare("INSERT INTO {$table} (following_id,follower_id,active) VALUES (?,?,?)");
    if (!$sql) return false;
    $following = (int)$following; $follower = (int)$follower; $active = (int)$active;
    $sql->bind_param('iii',$following,$follower,$active);
    $ok = $sql->execute();
    $sql->close();
    return (bool)$ok;
}

function bzj_connections_sync_insert_block($db,$table,$blocker,$blocked,$platform) {
    if ($platform === 'socials') {
        $sql = $db->prepare("INSERT INTO {$table} (user_id,block_userid,created_at) VALUES (?,?,NOW())");
    } else {
        $sql = $db->prepare("INSERT INTO {$table} (blocker,blocked) VALUES (?,?)");
    }
    if (!$sql) return false;
    $blocker=(int)$blocker; $blocked=(int)$blocked;
    $sql->bind_param('ii',$blocker,$blocked);
    $ok=$sql->execute();
    $sql->close();
    return (bool)$ok;
}

/* --------------------------------------------------------------------------
 * Uninstall cleanup intentionally omitted. MU plugins should not silently
 * delete canonical state or history when removed.
 * -------------------------------------------------------------------------- */
