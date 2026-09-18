<?php
/**
 * Plugin Name: Buzzjuice Connections Sync
 * Description: Canonical BuddyBoss relationship and moderation synchronization for Buzzjuice WordPress, Streams and Socials.
 * Version: 4.0.0
 * Author: Buzzjuice
 * License: GPL-2.0-or-later
 *
 * WordPress is the source of truth.
 * External platforms may report an action, but only WordPress decides the
 * canonical relationship/block state and fans that state back out.
 *
 * State model:
 *   relationship: none | requested | connected
 *   blocks: directional, independent: A->B and B->A
 *
 * A block always forces relationship=none.
 * Unblocking never restores a previous relationship.
 */
defined('ABSPATH') || exit;

define('BZJ_CONNECTIONS_SYNC_VERSION', '4.0.1');
define('BZJ_REL_NONE', 'none');
define('BZJ_REL_REQUESTED', 'requested');
define('BZJ_REL_CONNECTED', 'connected');

define('BZJ_ORIGIN_WORDPRESS', 'wordpress');
define('BZJ_ORIGIN_STREAMS', 'streams');
define('BZJ_ORIGIN_SOCIALS', 'socials');

if (!defined('BZJ_CONNECTIONS_SYNC_LOG')) {
    define('BZJ_CONNECTIONS_SYNC_LOG', WP_CONTENT_DIR . '/bzj-connections-sync.log');
}
if (!defined('BZJ_CONNECTIONS_SYNC_REST_NS')) {
    define('BZJ_CONNECTIONS_SYNC_REST_NS', 'bzj/v2');
}

/* -------------------------------------------------------------------------
 * Logging
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_log($message, array $context = []) {
    $line = '[' . gmdate('Y-m-d H:i:s') . '] ' . $message;
    if ($context) {
        $line .= ' ' . wp_json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    $line .= PHP_EOL;
    @error_log($line, 3, BZJ_CONNECTIONS_SYNC_LOG);
}

/* -------------------------------------------------------------------------
 * Tables / installation
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_relationships_table() {
    global $wpdb;
    return $wpdb->prefix . 'bzj_relationships';
}
function bzj_connections_sync_events_table() {
    global $wpdb;
    return $wpdb->prefix . 'bzj_relationship_events';
}

function bzj_connections_sync_install() {
    global $wpdb;

    $version = get_option('bzj_connections_sync_schema_version', '');
    if ($version === BZJ_CONNECTIONS_SYNC_VERSION) {
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $charset = $wpdb->get_charset_collate();
    $rel = bzj_connections_sync_relationships_table();
    $evt = bzj_connections_sync_events_table();

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
        event_type VARCHAR(60) NOT NULL,
        operation VARCHAR(30) NOT NULL,
        actor_wp_id BIGINT UNSIGNED NOT NULL,
        target_wp_id BIGINT UNSIGNED NOT NULL,
        relationship_state VARCHAR(20) NOT NULL,
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
    ) {$charset};
    ";

    dbDelta($sql);
    update_option('bzj_connections_sync_schema_version', BZJ_CONNECTIONS_SYNC_VERSION, false);
    bzj_connections_sync_log('Schema installed/updated', ['version' => BZJ_CONNECTIONS_SYNC_VERSION]);
}

/* -------------------------------------------------------------------------
 * Execution context / locking
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_internal($set = null) {
    if ($set !== null) {
        $GLOBALS['bzj_connections_sync_internal'] = (bool) $set;
    }
    return !empty($GLOBALS['bzj_connections_sync_internal']);
}

function bzj_connections_sync_pair($a, $b) {
    $a = absint($a);
    $b = absint($b);
    if (!$a || !$b || $a === $b) return false;
    if ($a > $b) [$a, $b] = [$b, $a];
    return ['user_a' => $a, 'user_b' => $b];
}

function bzj_connections_sync_lock($a, $b) {
    global $wpdb;
    $pair = bzj_connections_sync_pair($a, $b);
    if (!$pair) return false;
    $name = 'bzj_rel_' . $pair['user_a'] . '_' . $pair['user_b'];
    return ((int)$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,5)', $name)) === 1) ? $name : false;
}
function bzj_connections_sync_unlock($name) {
    global $wpdb;
    if ($name) $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $name));
}

/* -------------------------------------------------------------------------
 * Canonical state
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_get_relationship($a, $b) {
    global $wpdb;
    $pair = bzj_connections_sync_pair($a, $b);
    if (!$pair) return false;
    return $wpdb->get_row($wpdb->prepare(
        'SELECT * FROM ' . bzj_connections_sync_relationships_table() .
        ' WHERE user_a=%d AND user_b=%d LIMIT 1',
        $pair['user_a'], $pair['user_b']
    ));
}
function bzj_connections_sync_is_blocked($a, $b) {
    $r = bzj_connections_sync_get_relationship($a, $b);
    return $r && ((int)$r->block_a_to_b === 1 || (int)$r->block_b_to_a === 1);
}
function bzj_connections_sync_is_directionally_blocked($blocker, $blocked) {
    $pair = bzj_connections_sync_pair($blocker, $blocked);
    $r = $pair ? bzj_connections_sync_get_relationship($blocker, $blocked) : false;
    if (!$r) return false;
    return $pair['user_a'] === absint($blocker)
        ? (int)$r->block_a_to_b === 1
        : (int)$r->block_b_to_a === 1;
}

function bzj_connections_sync_uuid() {
    return wp_generate_uuid4();
}

/* -------------------------------------------------------------------------
 * Durable events
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_create_event($uuid,$origin,$type,$operation,$actor,$target,$state,$payload) {
    global $wpdb;
    $table = bzj_connections_sync_events_table();

    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$table} WHERE event_uuid=%s LIMIT 1",$uuid));
    if ($existing) return (int)$existing;

    $now = current_time('mysql', true);
    $ok = $wpdb->insert($table, [
        'event_uuid'=>$uuid,
        'origin'=>sanitize_key($origin),
        'event_type'=>sanitize_key($type),
        'operation'=>sanitize_key($operation),
        'actor_wp_id'=>absint($actor),
        'target_wp_id'=>absint($target),
        'relationship_state'=>sanitize_key($state),
        'payload'=>wp_json_encode($payload),
        'status'=>'pending',
        'attempts'=>0,
        'next_attempt_at'=>$now,
        'created_at'=>$now,
    ], ['%s','%s','%s','%s','%d','%d','%s','%s','%s','%d','%s','%s']);

    if (!$ok) {
        bzj_connections_sync_log('Event creation failed',['error'=>$wpdb->last_error,'uuid'=>$uuid]);
        return false;
    }
    return (int)$wpdb->insert_id;
}

/**
 * Write canonical state. This function never restores a relationship when
 * an unblock occurs; callers explicitly pass the desired state.
 */
function bzj_connections_sync_set_state($actor,$target,$state,$options=[]) {
    global $wpdb;
    $pair = bzj_connections_sync_pair($actor,$target);
    if (!$pair) return false;

    $state = sanitize_key($state);
    if (!in_array($state,[BZJ_REL_NONE,BZJ_REL_REQUESTED,BZJ_REL_CONNECTED],true)) return false;

    $lock = bzj_connections_sync_lock($actor,$target);
    if (!$lock) return false;

    try {
        $existing = bzj_connections_sync_get_relationship($actor,$target);
        $now = current_time('mysql',true);

        $ba = array_key_exists('block_a_to_b',$options)
            ? (int)$options['block_a_to_b'] : ($existing ? (int)$existing->block_a_to_b : 0);
        $bb = array_key_exists('block_b_to_a',$options)
            ? (int)$options['block_b_to_a'] : ($existing ? (int)$existing->block_b_to_a : 0);

        /* A block always dominates the relationship. */
        if ($ba || $bb) $state = BZJ_REL_NONE;

        $requested_by = array_key_exists('requested_by',$options)
            ? absint($options['requested_by'])
            : ($existing ? absint($existing->requested_by) : 0);

        $uuid = !empty($options['event_uuid']) ? sanitize_text_field($options['event_uuid']) : bzj_connections_sync_uuid();

        $data = [
            'relationship_state'=>$state,
            'block_a_to_b'=>$ba,
            'block_b_to_a'=>$bb,
            'requested_by'=>$requested_by ?: null,
            'updated_at'=>$now,
            'last_event_uuid'=>$uuid,
        ];

        if ($existing) {
            $ok = $wpdb->update(
                bzj_connections_sync_relationships_table(),$data,
                ['user_a'=>$pair['user_a'],'user_b'=>$pair['user_b']],
                ['%s','%d','%d','%d','%s','%s'],['%d','%d']
            );
        } else {
            $data['user_a']=$pair['user_a'];
            $data['user_b']=$pair['user_b'];
            $data['created_at']=$now;
            $ok = $wpdb->insert(
                bzj_connections_sync_relationships_table(),$data,
                ['%d','%d','%s','%d','%d','%d','%s','%s','%s']
            );
        }

        if ($ok === false) {
            bzj_connections_sync_log('Canonical state write failed',['error'=>$wpdb->last_error,'actor'=>$actor,'target'=>$target]);
            return false;
        }

        bzj_connections_sync_create_event(
            $uuid,
            $options['origin'] ?? BZJ_ORIGIN_WORDPRESS,
            $options['event_type'] ?? 'relationship_changed',
            $options['operation'] ?? 'sync',
            $actor,$target,$state,
            [
                'user_a'=>$pair['user_a'],'user_b'=>$pair['user_b'],
                'relationship'=>$state,'block_a_to_b'=>$ba,'block_b_to_a'=>$bb
            ]
        );

        return $uuid;
    } finally {
        bzj_connections_sync_unlock($lock);
    }
}

/* -------------------------------------------------------------------------
 * BuddyBoss cleanup
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_remove_wp_relationships($a,$b) {
    if (!function_exists('friends_check_friendship_status') || !function_exists('friends_remove_friend')) return;

    bzj_connections_sync_internal(true);
    try {
        if (friends_check_friendship_status($a,$b) === 'is_friend') friends_remove_friend($a,$b);
        if (friends_check_friendship_status($b,$a) === 'is_friend') friends_remove_friend($b,$a);

        if (function_exists('friends_get_friendship_id') && function_exists('friends_reject_friendship')) {
            $id = friends_get_friendship_id($a,$b);
            if ($id) friends_reject_friendship($id);
            $id = friends_get_friendship_id($b,$a);
            if ($id) friends_reject_friendship($id);
        }
    } finally {
        bzj_connections_sync_internal(false);
    }
}

/* -------------------------------------------------------------------------
 * Identity
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_ids($wp_user_id) {
    $wp_user_id = absint($wp_user_id);
    if (!$wp_user_id) return false;
    return [
        'wp'=>$wp_user_id,
        'wo'=>absint(get_user_meta($wp_user_id,'wo_user_id',true)),
        'qd'=>absint(get_user_meta($wp_user_id,'qd_user_id',true)),
    ];
}
function bzj_connections_sync_resolve_wp($external_id,$platform) {
    $external_id=absint($external_id);
    $platform=sanitize_key($platform);
    if (!$external_id || !in_array($platform,['streams','socials'],true)) return 0;
    $key=$platform==='streams'?'wo_user_id':'qd_user_id';
    $ids=get_users(['meta_key'=>$key,'meta_value'=>(string)$external_id,'number'=>1,'fields'=>'ids']);
    return !empty($ids)?absint($ids[0]):0;
}

/* -------------------------------------------------------------------------
 * Canonical -> external platform fan-out
 *
 * This uses the existing wwqd_bridge DB connections. The bridge must expose:
 * get_wowonder_db(), get_qd_db_conn().
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_external_table($db,$preferred,$needle) {
    $preferred = trim($preferred);
    if ($preferred) {
        $safe=$db->real_escape_string($preferred);
        $r=@$db->query("SHOW TABLES LIKE '{$safe}'");
        if ($r && $r->num_rows) return $preferred;
    }
    $r=@$db->query("SHOW TABLES");
    if (!$r) return false;
    while ($row=$r->fetch_row()) {
        if (stripos($row[0],$needle)!==false) return $row[0];
    }
    return false;
}
function bzj_connections_sync_external_delete_pair($db,$table,$a,$b,$cols) {
    $sql="DELETE FROM `".$db->real_escape_string($table)."` WHERE ".
        "((`".$cols[0]."`={$a} AND `".$cols[1]."`={$b}) OR ".
        "(`".$cols[0]."`={$b} AND `".$cols[1]."`={$a}))";
    return (bool)@$db->query($sql);
}
function bzj_connections_sync_external_insert($db,$table,$data) {
    $fields=[];$values=[];
    foreach($data as $f=>$v) {
        $fields[]='`'.$db->real_escape_string($f).'`';
        $values[]=(int)$v;
    }
    return (bool)@$db->query(
        "INSERT INTO `".$db->real_escape_string($table)."` (".implode(',',$fields).") VALUES (".implode(',',$values).")"
    );
}

function bzj_connections_sync_fanout_platform($platform,$a_wp,$b_wp,$state,$ba,$bb) {
    $ia=bzj_connections_sync_ids($a_wp);
    $ib=bzj_connections_sync_ids($b_wp);
    if (!$ia || !$ib) return false;

    if ($platform==='streams') {
        if (!$ia['wo'] || !$ib['wo'] || !function_exists('get_wowonder_db')) return false;
        $db=get_wowonder_db(); if (!$db) return false;

        /* WoWonder normally uses Wo_Followers/Wo_Blocks; discover safely if custom-prefixed. */
        $followers=bzj_connections_sync_external_table($db,'Wo_Followers','followers');
        $blocks=bzj_connections_sync_external_table($db,'Wo_Blocks','blocks');
        if (!$followers || !$blocks) return false;

        $a=(int)$ia['wo'];$b=(int)$ib['wo'];
        $fcols=['following_id','follower_id'];
        $bcols=['blocker','blocked'];

        bzj_connections_sync_external_delete_pair($db,$followers,$a,$b,$fcols);
        bzj_connections_sync_external_delete_pair($db,$blocks,$a,$b,$bcols);

        if ($state===BZJ_REL_REQUESTED) {
            /* Actor A -> target B is a pending request: following_id=B, follower_id=A. */
            bzj_connections_sync_external_insert($db,$followers,$fcols[0]==='following_id'
                ? ['following_id'=>$b,'follower_id'=>$a,'active'=>0] : []);
        } elseif ($state===BZJ_REL_CONNECTED) {
            bzj_connections_sync_external_insert($db,$followers,['following_id'=>$a,'follower_id'=>$b,'active'=>1]);
            bzj_connections_sync_external_insert($db,$followers,['following_id'=>$b,'follower_id'=>$a,'active'=>1]);
        }
        if ($ba) bzj_connections_sync_external_insert($db,$blocks,['blocker'=>$a,'blocked'=>$b]);
        if ($bb) bzj_connections_sync_external_insert($db,$blocks,['blocker'=>$b,'blocked'=>$a]);
        return true;
    }

    if ($platform==='socials') {
        if (!$ia['qd'] || !$ib['qd'] || !function_exists('get_qd_db_conn')) return false;
        $db=get_qd_db_conn(); if (!$db) return false;
        $followers=bzj_connections_sync_external_table($db,'followers','followers');
        $blocks=bzj_connections_sync_external_table($db,'blocks','blocks');
        if (!$followers || !$blocks) return false;

        $a=(int)$ia['qd'];$b=(int)$ib['qd'];
        bzj_connections_sync_external_delete_pair($db,$followers,$a,$b,['following_id','follower_id']);
        /* Socials block schema is user_id/block_userid. */
        bzj_connections_sync_external_delete_pair($db,$blocks,$a,$b,['user_id','block_userid']);

        if ($state===BZJ_REL_REQUESTED) {
            bzj_connections_sync_external_insert($db,$followers,['following_id'=>$b,'follower_id'=>$a,'active'=>0]);
        } elseif ($state===BZJ_REL_CONNECTED) {
            bzj_connections_sync_external_insert($db,$followers,['following_id'=>$a,'follower_id'=>$b,'active'=>1]);
            bzj_connections_sync_external_insert($db,$followers,['following_id'=>$b,'follower_id'=>$a,'active'=>1]);
        }
        if ($ba) bzj_connections_sync_external_insert($db,$blocks,['user_id'=>$a,'block_userid'=>$b]);
        if ($bb) bzj_connections_sync_external_insert($db,$blocks,['user_id'=>$b,'block_userid'=>$a]);
        return true;
    }
    return false;
}

function bzj_connections_sync_fanout($event) {
    $p=json_decode($event->payload,true);
    if (!$p) return false;

    $ia=bzj_connections_sync_ids((int)$p['user_a']);
    $ib=bzj_connections_sync_ids((int)$p['user_b']);
    if(!$ia||!$ib)return false;

    $need_streams=!empty($ia['wo'])&&!empty($ib['wo']);
    $need_socials=!empty($ia['qd'])&&!empty($ib['qd']);

    $ok1=!$need_streams || bzj_connections_sync_fanout_platform(
        'streams',(int)$p['user_a'],(int)$p['user_b'],
        $p['relationship'],(int)$p['block_a_to_b'],(int)$p['block_b_to_a']
    );
    $ok2=!$need_socials || bzj_connections_sync_fanout_platform(
        'socials',(int)$p['user_a'],(int)$p['user_b'],
        $p['relationship'],(int)$p['block_a_to_b'],(int)$p['block_b_to_a']
    );
    /* If an external identity is not provisioned yet, that platform is
       intentionally skipped; the event remains successful for provisioned
       platforms. */
    return $ok1 && $ok2;
}

/* -------------------------------------------------------------------------
 * WordPress -> canonical hooks
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_register_hooks() {
    add_action('friends_friendship_requested','bzj_connections_sync_on_requested',10,4);
    add_action('friends_friendship_accepted','bzj_connections_sync_on_accepted',10,4);
    add_action('friends_friendship_rejected','bzj_connections_sync_on_rejected',10,4);
    add_action('friends_friendship_deleted','bzj_connections_sync_on_deleted',10,4);
    add_action('friends_friendship_withdrawn','bzj_connections_sync_on_withdrawn',10,4);
    add_action('bp_moderation_block_created','bzj_connections_sync_on_block_created',10,2);
    add_action('bp_moderation_block_deleted','bzj_connections_sync_on_block_deleted',10,2);
}
function bzj_connections_sync_on_requested($id,$actor,$target,$friendship=null) {
    if (bzj_connections_sync_internal()) return;
    if (bzj_connections_sync_is_blocked($actor,$target)) return;
    bzj_connections_sync_set_state($actor,$target,BZJ_REL_REQUESTED,[
        'origin'=>BZJ_ORIGIN_WORDPRESS,'event_type'=>'connection_requested',
        'operation'=>'request','requested_by'=>absint($actor)
    ]);
}
function bzj_connections_sync_on_accepted($id,$actor,$target,$friendship=null) {
    if (bzj_connections_sync_internal()) return;
    if (bzj_connections_sync_is_blocked($actor,$target)) return;
    bzj_connections_sync_set_state($actor,$target,BZJ_REL_CONNECTED,[
        'origin'=>BZJ_ORIGIN_WORDPRESS,'event_type'=>'connection_accepted','operation'=>'accept'
    ]);
}
function bzj_connections_sync_on_rejected($id,$actor,$target,$friendship=null) {
    if (bzj_connections_sync_internal()) return;
    bzj_connections_sync_set_state($actor,$target,BZJ_REL_NONE,[
        'origin'=>BZJ_ORIGIN_WORDPRESS,'event_type'=>'connection_rejected','operation'=>'remove'
    ]);
}
function bzj_connections_sync_on_deleted($id,$actor,$target,$friendship=null) {
    if (bzj_connections_sync_internal()) return;
    bzj_connections_sync_set_state($actor,$target,BZJ_REL_NONE,[
        'origin'=>BZJ_ORIGIN_WORDPRESS,'event_type'=>'connection_removed','operation'=>'remove'
    ]);
}
function bzj_connections_sync_on_withdrawn($id,$actor,$target,$friendship=null) {
    if (bzj_connections_sync_internal()) return;
    bzj_connections_sync_set_state($actor,$target,BZJ_REL_NONE,[
        'origin'=>BZJ_ORIGIN_WORDPRESS,'event_type'=>'connection_withdrawn','operation'=>'remove'
    ]);
}
function bzj_connections_sync_on_block_created($block_id,$block_object) {
    if (!is_object($block_object)) return;
    $a=absint($block_object->user_id??0);$b=absint($block_object->item_id??0);
    if (!$a||!$b||$a===$b) return;

    bzj_connections_sync_remove_wp_relationships($a,$b);
    $pair=bzj_connections_sync_pair($a,$b); if(!$pair) return;
    $opts=['origin'=>BZJ_ORIGIN_WORDPRESS,'event_type'=>'block_created','operation'=>'block'];
    if($pair['user_a']===$a)$opts['block_a_to_b']=1;else$opts['block_b_to_a']=1;
    bzj_connections_sync_set_state($a,$b,BZJ_REL_NONE,$opts);
}
function bzj_connections_sync_on_block_deleted($block_id,$block_object) {
    if (!is_object($block_object)) return;
    $a=absint($block_object->user_id??0);$b=absint($block_object->item_id??0);
    if(!$a||!$b||$a===$b)return;
    $pair=bzj_connections_sync_pair($a,$b);$old=bzj_connections_sync_get_relationship($a,$b);
    if(!$pair||!$old)return;
    $ba=(int)$old->block_a_to_b;$bb=(int)$old->block_b_to_a;
    if($pair['user_a']===$a)$ba=0;else$bb=0;
    /* Do NOT restore relationship after unblock. */
    bzj_connections_sync_set_state($a,$b,BZJ_REL_NONE,[
        'origin'=>BZJ_ORIGIN_WORDPRESS,'event_type'=>'block_deleted','operation'=>'unblock',
        'block_a_to_b'=>$ba,'block_b_to_a'=>$bb
    ]);
}

/* -------------------------------------------------------------------------
 * External intent endpoint
 *
 * External platforms call this after their local action. WordPress then:
 * 1. resolves identities,
 * 2. applies WordPress/BuddyBoss policy,
 * 3. updates canonical state,
 * 4. queues/fans out canonical state.
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_rest_auth(WP_REST_Request $request) {
    $secret=function_exists('bzj_get_sso_secret')?bzj_get_sso_secret():getenv('BUZZ_SSO_SECRET');
    $ts=(string)$request->get_header('x-bzj-timestamp');
    $sig=(string)$request->get_header('x-bzj-signature');
    if(!$secret||!$ts||!$sig||abs(time()-(int)$ts)>90)return new WP_Error('bzj_auth','Invalid or expired signature',['status'=>401]);
    $raw=$request->get_body();
    $expected=hash_hmac('sha256',$ts.'.'.$raw,$secret);
    if(!hash_equals($expected,$sig))return new WP_Error('bzj_auth','Invalid signature',['status'=>403]);
    return true;
}
function bzj_connections_sync_register_rest() {
    register_rest_route(BZJ_CONNECTIONS_SYNC_REST_NS,'/relationship',[
        'methods'=>'POST','callback'=>'bzj_connections_sync_rest_relationship',
        'permission_callback'=>'bzj_connections_sync_rest_auth'
    ]);
}
function bzj_connections_sync_rest_relationship(WP_REST_Request $r) {
    $origin=sanitize_key($r->get_param('origin'));
    $op=sanitize_key($r->get_param('operation'));
    $au=absint($r->get_param('origin_user_id'));
    $tu=absint($r->get_param('target_user_id'));
    $uuid=sanitize_text_field($r->get_param('event_uuid'));

    if(!in_array($origin,['streams','socials'],true) ||
       !in_array($op,['request','accept','remove','block','unblock'],true) ||
       !$au||!$tu||$au===$tu)
        return new WP_Error('bzj_invalid','Invalid relationship request',['status'=>400]);

    $actor=bzj_connections_sync_resolve_wp($au,$origin);
    $target=bzj_connections_sync_resolve_wp($tu,$origin);
    if(!$actor||!$target)return new WP_Error('bzj_identity','User mapping not found',['status'=>404]);

    /* A platform may never override a canonical block. */
    if($op!=='unblock' && bzj_connections_sync_is_blocked($actor,$target))
        return new WP_Error('bzj_blocked','Users are blocked',['status'=>403]);

    if($op==='request') {
        if(!function_exists('friends_check_friendship_status')||!function_exists('friends_add_friend'))
            return new WP_Error('bzj_buddyboss','BuddyBoss API unavailable',['status'=>503]);

        $status=friends_check_friendship_status($actor,$target);
        if($status==='is_friend') {
            /* Already canonical connection; hooks may already have updated state. */
        } elseif($status==='pending') {
            /* Preserve pending state. */
        } else {
            /* force_accept=false is deliberate: preserves BuddyBoss request policy. */
            if(!friends_add_friend($actor,$target,false))
                return new WP_Error('bzj_denied','BuddyBoss denied the connection request',['status'=>403]);
        }
    } elseif($op==='accept') {
        if(function_exists('friends_get_friendship_id')&&function_exists('friends_accept_friendship')) {
            $fid=friends_get_friendship_id($target,$actor);
            if(!$fid)$fid=friends_get_friendship_id($actor,$target);
            if($fid)friends_accept_friendship($fid);
        }
    } elseif($op==='remove') {
        bzj_connections_sync_remove_wp_relationships($actor,$target);
        bzj_connections_sync_set_state($actor,$target,BZJ_REL_NONE,[
            'origin'=>$origin,'event_type'=>'external_remove','operation'=>'remove','event_uuid'=>$uuid?:bzj_connections_sync_uuid()
        ]);
    } elseif($op==='block') {
        /* BuddyBoss moderation API is version-dependent. Use a filter adapter. */
        $done=false;
        if(function_exists('bp_moderation_block_user'))$done=(bool)bp_moderation_block_user($actor,$target);
        $done=(bool)apply_filters('bzj_connections_sync_external_block',$done,$actor,$target,$origin);
        if(!$done)return new WP_Error('bzj_block_unavailable','BuddyBoss block adapter unavailable',['status'=>503]);
    } elseif($op==='unblock') {
        $done=false;
        if(function_exists('bp_moderation_unblock_user'))$done=(bool)bp_moderation_unblock_user($actor,$target);
        $done=(bool)apply_filters('bzj_connections_sync_external_unblock',$done,$actor,$target,$origin);
        if(!$done)return new WP_Error('bzj_unblock_unavailable','BuddyBoss unblock adapter unavailable',['status'=>503]);
    }

    $rel=bzj_connections_sync_get_relationship($actor,$target);
    return rest_ensure_response([
        'success'=>true,
        'canonical'=>[
            'relationship'=>$rel?$rel->relationship_state:BZJ_REL_NONE,
            'block_a_to_b'=>$rel?(int)$rel->block_a_to_b:0,
            'block_b_to_a'=>$rel?(int)$rel->block_b_to_a:0
        ]
    ]);
}

/* -------------------------------------------------------------------------
 * Retry queue. Each platform is retried independently; an event is not
 * marked processed until both required platform writes succeed (or the
 * corresponding platform identity is not provisioned).
 * ------------------------------------------------------------------------- */
add_filter('cron_schedules', function($schedules) {
    if (!isset($schedules['five_minutes'])) {
        $schedules['five_minutes'] = ['interval'=>300,'display'=>__('Every 5 minutes')];
    }
    return $schedules;
});
function bzj_connections_sync_schedule() {
    if(!wp_next_scheduled('bzj_connections_sync_process_events'))
        wp_schedule_event(time()+60,'five_minutes','bzj_connections_sync_process_events');
}
function bzj_connections_sync_process_events() {
    global $wpdb;
    $t=bzj_connections_sync_events_table();
    $rows=$wpdb->get_results("SELECT * FROM {$t} WHERE status IN ('pending','retry') AND next_attempt_at<=UTC_TIMESTAMP() ORDER BY created_at ASC LIMIT 25");
    foreach($rows as $e) {
        $ok=bzj_connections_sync_fanout($e);
        $attempt=(int)$e->attempts+1;
        if($ok) {
            $wpdb->update($t,['status'=>'processed','attempts'=>$attempt,'processed_at'=>current_time('mysql',true),'last_error'=>null],
                ['id'=>$e->id],['%s','%d','%s','%s'],['%d']);
        } else {
            $delay=min(3600,pow(2,$attempt)*30);
            $wpdb->update($t,['status'=>$attempt>=8?'failed':'retry','attempts'=>$attempt,
                'next_attempt_at'=>gmdate('Y-m-d H:i:s',time()+$delay),
                'last_error'=>'One or more external platform writes failed'],
                ['id'=>$e->id],['%s','%d','%s','%s'],['%d']);
            bzj_connections_sync_log('Fan-out retry scheduled',['event_uuid'=>$e->event_uuid,'attempt'=>$attempt]);
        }
    }
}

/* -------------------------------------------------------------------------
 * Boot. Schema work is versioned and only occurs once per schema version.
 * ------------------------------------------------------------------------- */
function bzj_connections_sync_boot() {
    /* Existing shared bridge supplies the external DB connections. */
    $bridge = ABSPATH . 'shared/wwqd_bridge.php';
    if (is_readable($bridge)) {
        require_once $bridge;
    }
    bzj_connections_sync_install();
    bzj_connections_sync_register_hooks();
    bzj_connections_sync_schedule();
}
add_action('init','bzj_connections_sync_boot',1);
add_action('rest_api_init','bzj_connections_sync_register_rest');
add_action('bzj_connections_sync_process_events','bzj_connections_sync_process_events');
