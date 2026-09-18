<?php
/**
 * Plugin Name: BZJ Connections Synchronization Control Plane
 * Description: Buzzjuice Network relationship control plane. BuddyBoss is the source of truth; WoWonder Streams and QuickDate Socials are synchronized projections.
 * Version: 8.2.0
 * Author: Buzzjuice
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */
defined('ABSPATH') || exit;
if (defined('BZJ_CONNECTIONS_SYNC_LOADED')) return;
define('BZJ_CONNECTIONS_SYNC_LOADED', true);

final class BZJ_Connections_Sync {
    const VERSION='8.2.0';
    const NS='bzj/v6';
    const ROUTE='/connection-management';
    const LEDGER='bzj_relationships';
    const EVENTS='bzj_relationship_events';
    const LOG='data/logs/bzj-connections-sync.log';
    const LOCK_TIMEOUT=15;
    const TIMESTAMP_WINDOW=300;
    const MAX_ATTEMPTS=12;
    const RECOVERY='bzj_connections_sync_recovery';

    const WP='wordpress'; const STREAMS='streams'; const SOCIALS='socials';
    const REQUEST='connection_request'; const ACCEPT='connection_accept';
    const REJECT='connection_reject'; const WITHDRAW='connection_withdraw';
    const REMOVE='connection_remove'; const FOLLOW='follow'; const UNFOLLOW='unfollow';
    const BLOCK='block'; const UNBLOCK='unblock';

    private static $instance;
    private $ledger_table;
    private $events_table;
    private $internal_depth=0;

    public static function instance() {
        return self::$instance ?: (self::$instance=new self());
    }
    private function __construct() {
        global $wpdb;
        $this->ledger_table=$wpdb->prefix.self::LEDGER;
        $this->events_table=$wpdb->prefix.self::EVENTS;

        add_action('rest_api_init',[$this,'register_routes']);

        add_action('friends_friendship_requested',[$this,'bb_requested'],20,4);
        add_action('friends_friendship_accepted',[$this,'bb_accepted'],20,4);
        add_action('friends_friendship_rejected',[$this,'bb_rejected'],20,2);
        add_action('friends_friendship_withdrawn',[$this,'bb_withdrawn'],20,2);
        add_action('friends_friendship_whithdrawn',[$this,'bb_withdrawn'],20,2);
        add_action('friends_friendship_deleted',[$this,'bb_deleted'],20,3);
        add_action('friends_friendship_post_delete',[$this,'bb_post_deleted'],20,2);

        add_action('bp_start_following',[$this,'bb_follow_started'],20,1);
        add_action('bp_stop_following',[$this,'bb_follow_stopped'],20,1);
        add_action('bp_follow_start_following',[$this,'bb_follow_started'],20,1);
        add_action('bp_follow_stop_following',[$this,'bb_follow_stopped'],20,1);

        add_action('bp_moderation_after_save',[$this,'bb_moderation_saved'],20,1);
        add_action('bb_moderation_after_delete',[$this,'bb_moderation_deleted'],20,1);

        add_filter('cron_schedules',[$this,'cron_schedules']);
        add_action(self::RECOVERY,[$this,'process_recovery']);

        $this->install_schema();
        $this->schedule();
    }

    public function cron_schedules($s) {
        if(empty($s['bzj_five_minutes'])) $s['bzj_five_minutes']=['interval'=>300,'display'=>'BZJ every five minutes'];
        return $s;
    }
    private function schedule() {
        if(!wp_next_scheduled(self::RECOVERY)) wp_schedule_event(time()+60,'bzj_five_minutes',self::RECOVERY);
    }

    private function log($message,$context=[]) {
        $file=trailingslashit(ABSPATH).self::LOG;
        $dir=dirname($file);
        if(!is_dir($dir)) wp_mkdir_p($dir);
        $entry=['time'=>gmdate('c'),'message'=>(string)$message,'context'=>is_array($context)?$context:['value'=>$context]];
        if(!is_writable($dir)) { error_log('[BZJ Sync] log directory unavailable: '.$dir); return false; }
        return false!==@file_put_contents($file,wp_json_encode($entry,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL,FILE_APPEND|LOCK_EX);
    }

    private function install_schema() {
        $version=get_option('bzj_connections_sync_schema_version','');
        if($version===self::VERSION) return;
        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        global $wpdb; $charset=$wpdb->get_charset_collate();
        $sql1="CREATE TABLE {$this->ledger_table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_a bigint(20) unsigned NOT NULL,user_b bigint(20) unsigned NOT NULL,
            connection_state varchar(20) NOT NULL DEFAULT 'none',
            requested_by bigint(20) unsigned NOT NULL DEFAULT 0,
            follow_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,
            follow_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            block_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,
            block_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            version bigint(20) unsigned NOT NULL DEFAULT 1,last_event_uuid char(36) NOT NULL DEFAULT '',
            created_at datetime NOT NULL,updated_at datetime NOT NULL,
            PRIMARY KEY(id),UNIQUE KEY user_pair(user_a,user_b),
            KEY connection_state(connection_state),KEY updated_at(updated_at)
        ) $charset;";
        $sql2="CREATE TABLE {$this->events_table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,event_uuid char(36) NOT NULL,
            origin varchar(20) NOT NULL,operation varchar(40) NOT NULL,
            actor_wp_id bigint(20) unsigned NOT NULL DEFAULT 0,target_wp_id bigint(20) unsigned NOT NULL DEFAULT 0,
            pair_a bigint(20) unsigned NOT NULL DEFAULT 0,pair_b bigint(20) unsigned NOT NULL DEFAULT 0,
            payload longtext NULL,status varchar(20) NOT NULL DEFAULT 'pending',
            attempts int(10) unsigned NOT NULL DEFAULT 0,next_attempt_at datetime NOT NULL,
            last_error text NULL,processed_at datetime NULL,created_at datetime NOT NULL,updated_at datetime NOT NULL,
            PRIMARY KEY(id),UNIQUE KEY event_uuid(event_uuid),KEY queue(status,next_attempt_at),KEY pair(pair_a,pair_b)
        ) $charset;";
        dbDelta($sql1); dbDelta($sql2);
        if(!empty($wpdb->last_error)) $this->log('Schema installation error',['error'=>$wpdb->last_error]);
        update_option('bzj_connections_sync_schema_version',self::VERSION,false);
    }

    private function pair($a,$b) { $a=absint($a);$b=absint($b);return $a<$b?[$a,$b]:[$b,$a]; }
    private function lock_key($a,$b) { $p=$this->pair($a,$b);return 'bzj_pair_'.$p[0].'_'.$p[1]; }
    private function lock($a,$b) {
        global $wpdb; return '1'===(string)$wpdb->get_var($wpdb->prepare("SELECT GET_LOCK(%s,%d)",$this->lock_key($a,$b),self::LOCK_TIMEOUT));
    }
    private function unlock($a,$b) { global $wpdb;$wpdb->get_var($wpdb->prepare("SELECT RELEASE_LOCK(%s)",$this->lock_key($a,$b))); }
    private function internal() { return $this->internal_depth>0; }
    private function push_internal(){++$this->internal_depth;}
    private function pop_internal(){ $this->internal_depth=max(0,$this->internal_depth-1); }

    private function ledger($a,$b) {
        global $wpdb;$p=$this->pair($a,$b);
        $r=$wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->ledger_table} WHERE user_a=%d AND user_b=%d LIMIT 1",$p[0],$p[1]),ARRAY_A);
        return $r?:['user_a'=>$p[0],'user_b'=>$p[1],'connection_state'=>'none','requested_by'=>0,'follow_a_to_b'=>0,'follow_b_to_a'=>0,'block_a_to_b'=>0,'block_b_to_a'=>0,'version'=>1,'last_event_uuid'=>''];
    }
    private function save_ledger($a,$b,$changes,$uuid='') {
        global $wpdb;$p=$this->pair($a,$b);$old=$this->ledger($a,$b);
        $data=array_merge($old,$changes);
        $data['user_a']=$p[0];$data['user_b']=$p[1];$data['version']=max(1,(int)$old['version'])+1;
        $data['last_event_uuid']=$uuid;$data['updated_at']=gmdate('Y-m-d H:i:s');
        unset($data['id']);
        if(empty($old['id'])) {$data['created_at']=gmdate('Y-m-d H:i:s');$ok=$wpdb->insert($this->ledger_table,$data);}
        else {$ok=$wpdb->update($this->ledger_table,$data,['id'=>(int)$old['id']]);}
        if(false===$ok) throw new RuntimeException('Ledger write failed: '.$wpdb->last_error);
        return true;
    }
    private function event_exists($uuid) {
        global $wpdb;return (bool)$wpdb->get_var($wpdb->prepare("SELECT id FROM {$this->events_table} WHERE event_uuid=%s LIMIT 1",$uuid));
    }
    private function event($uuid,$origin,$op,$actor,$target,$payload=[]) {
        global $wpdb;if($this->event_exists($uuid))return true;$p=$this->pair($actor,$target);$now=gmdate('Y-m-d H:i:s');
        $ok=$wpdb->insert($this->events_table,['event_uuid'=>$uuid,'origin'=>$origin,'operation'=>$op,'actor_wp_id'=>$actor,'target_wp_id'=>$target,'pair_a'=>$p[0],'pair_b'=>$p[1],'payload'=>wp_json_encode($payload),'status'=>'pending','next_attempt_at'=>$now,'created_at'=>$now,'updated_at'=>$now]);
        if(false===$ok) throw new RuntimeException('Event insert failed: '.$wpdb->last_error);return true;
    }
    private function processed($uuid) {
        global $wpdb;$ok=$wpdb->update($this->events_table,['status'=>'processed','processed_at'=>gmdate('Y-m-d H:i:s'),'updated_at'=>gmdate('Y-m-d H:i:s'),'last_error'=>null],['event_uuid'=>$uuid]);
        if(false===$ok) throw new RuntimeException('Event status update failed: '.$wpdb->last_error);
    }
    private function retry($uuid,$error) {
        global $wpdb;$n=(int)$wpdb->get_var($wpdb->prepare("SELECT attempts FROM {$this->events_table} WHERE event_uuid=%s",$uuid))+1;
        $status=$n>=self::MAX_ATTEMPTS?'failed':'pending';$next=gmdate('Y-m-d H:i:s',time()+min(3600,max(60,2**min(10,$n))));
        $wpdb->update($this->events_table,['status'=>$status,'attempts'=>$n,'next_attempt_at'=>$next,'last_error'=>substr((string)$error,0,65000),'updated_at'=>gmdate('Y-m-d H:i:s')],['event_uuid'=>$uuid]);
    }

    private function db_helpers() {
        static $loaded=false;if($loaded)return true;
        $paths=[ABSPATH.'shared/db_helpers.php',dirname(ABSPATH).'/shared/db_helpers.php',dirname(ABSPATH,2).'/shared/db_helpers.php'];
        foreach($paths as $p) if(is_file($p)){require_once $p;$loaded=true;return true;}
        $this->log('db_helpers.php not found',['paths'=>$paths]);return false;
    }
    private function ext_id($wp,$platform){$key=$platform===self::STREAMS?'wo_user_id':'qd_user_id';return absint(get_user_meta($wp,$key,true));}
    private function wp_id($platform,$ext) {
        global $wpdb;$key=$platform===self::STREAMS?'wo_user_id':'qd_user_id';
        return (int)$wpdb->get_var($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key=%s AND meta_value=%s ORDER BY umeta_id ASC LIMIT 1",$key,(string)absint($ext)));
    }
    private function db($platform) {
        if(!$this->db_helpers())return false;
        $fn=$platform===self::STREAMS?'get_wowonder_db':'get_qd_db_conn';
        if(!function_exists($fn))return false;$db=$fn();return $db instanceof mysqli?$db:false;
    }
    private function table($db,$platform,$kind) {
        $c=$kind==='follow'?($platform===self::STREAMS?['Wo_Followers','wo_followers','followers']:['followers']):($platform===self::STREAMS?['Wo_Blocks','wo_blocks','blocks']:['blocks']);
        foreach($c as $t){$q=$db->query("SHOW TABLES LIKE '".$db->real_escape_string($t)."'");if($q instanceof mysqli_result&&$q->num_rows){$q->free();return $t;}}
        return false;
    }
    private function cols($db,$table){$out=[];$q=$db->query("SHOW COLUMNS FROM `".preg_replace('/[^A-Za-z0-9_]/','',$table)."`");if($q)while($r=$q->fetch_assoc())$out[strtolower($r['Field'])]=true;return $out;}

    private function upsert_follow($platform,$actor,$target,$active=1) {
        $ea=$this->ext_id($actor,$platform);$et=$this->ext_id($target,$platform);if(!$ea||!$et)throw new RuntimeException("$platform mapping missing");
        $db=$this->db($platform);if(!$db)throw new RuntimeException("$platform DB unavailable");$table=$this->table($db,$platform,'follow');if(!$table)throw new RuntimeException("$platform followers table missing");
        $s=$db->prepare("SELECT id,active FROM `$table` WHERE following_id=? AND follower_id=? LIMIT 1");if(!$s)throw new RuntimeException($db->error);
        $s->bind_param('ii',$et,$ea);$s->execute();$r=$s->get_result();$row=$r?$r->fetch_assoc():null;$s->close();
        if($row){$id=(int)$row['id'];$s=$db->prepare("UPDATE `$table` SET active=? WHERE id=?");$s->bind_param('ii',$active,$id);if(!$s->execute())throw new RuntimeException($db->error);$s->close();}
        else {$cols=$this->cols($db,$table);if(isset($cols['created_at'])){$now=gmdate('Y-m-d H:i:s');$s=$db->prepare("INSERT INTO `$table` (following_id,follower_id,active,created_at) VALUES (?,?,?,?)");$s->bind_param('iiis',$et,$ea,$active,$now);}
        elseif(isset($cols['time'])){$now=time();$s=$db->prepare("INSERT INTO `$table` (following_id,follower_id,active,time) VALUES (?,?,?,?)");$s->bind_param('iiii',$et,$ea,$active,$now);}
        else{$s=$db->prepare("INSERT INTO `$table` (following_id,follower_id,active) VALUES (?,?,?)");$s->bind_param('iii',$et,$ea,$active);}
        if(!$s->execute())throw new RuntimeException("$platform follow insert failed: ".$s->error);$s->close();}
        $this->verify_follow($platform,$actor,$target,$active);
    }
    private function delete_follow($platform,$actor,$target) {
        $ea=$this->ext_id($actor,$platform);$et=$this->ext_id($target,$platform);if(!$ea||!$et)throw new RuntimeException("$platform mapping missing");
        $db=$this->db($platform);if(!$db)throw new RuntimeException("$platform DB unavailable");$table=$this->table($db,$platform,'follow');if(!$table)throw new RuntimeException("$platform followers table missing");
        $s=$db->prepare("DELETE FROM `$table` WHERE following_id=? AND follower_id=?");$s->bind_param('ii',$et,$ea);if(!$s->execute())throw new RuntimeException("$platform follow delete failed: ".$s->error);$affected=$s->affected_rows;$s->close();
        if($affected<1){$this->log('Required follow delete changed zero rows',['platform'=>$platform,'actor'=>$actor,'target'=>$target]);}
        $this->verify_follow($platform,$actor,$target,0);
    }
    private function verify_follow($platform,$actor,$target,$active) {
        $ea=$this->ext_id($actor,$platform);$et=$this->ext_id($target,$platform);$db=$this->db($platform);$table=$this->table($db,$platform,'follow');
        $s=$db->prepare("SELECT active FROM `$table` WHERE following_id=? AND follower_id=? LIMIT 1");$s->bind_param('ii',$et,$ea);$s->execute();$r=$s->get_result();$row=$r?$r->fetch_assoc():null;$s->close();
        $actual=$row?(int)$row['active']:0;if($actual!==$active)throw new RuntimeException("$platform follow verification failed");
    }
    private function upsert_block($platform,$actor,$target,$active) {
        $ea=$this->ext_id($actor,$platform);$et=$this->ext_id($target,$platform);if(!$ea||!$et)throw new RuntimeException("$platform mapping missing");
        $db=$this->db($platform);if(!$db)throw new RuntimeException("$platform DB unavailable");$table=$this->table($db,$platform,'block');if(!$table)throw new RuntimeException("$platform blocks table missing");
        if ($active) {
            if ($platform===self::STREAMS) {
                $check=$db->prepare("SELECT id FROM `$table` WHERE blocker=? AND blocked=? LIMIT 1");
            } else {
                $check=$db->prepare("SELECT id FROM `$table` WHERE user_id=? AND block_userid=? LIMIT 1");
            }
            if (!$check) throw new RuntimeException("$platform block lookup failed: ".$db->error);
            $check->bind_param('ii',$ea,$et);$check->execute();$rr=$check->get_result();$exists=$rr&&$rr->fetch_assoc();$check->close();
            if (!$exists) {
                if ($platform===self::STREAMS) $s=$db->prepare("INSERT INTO `$table` (blocker,blocked) VALUES (?,?)");
                else $s=$db->prepare("INSERT INTO `$table` (user_id,block_userid) VALUES (?,?)");
                if (!$s) throw new RuntimeException("$platform block insert prepare failed: ".$db->error);
                $s->bind_param('ii',$ea,$et);if(!$s->execute())throw new RuntimeException("$platform block insert failed: ".$s->error);$s->close();
            }
        } else {
            if ($platform===self::STREAMS) $s=$db->prepare("DELETE FROM `$table` WHERE blocker=? AND blocked=?");
            else $s=$db->prepare("DELETE FROM `$table` WHERE user_id=? AND block_userid=?");
            if (!$s) throw new RuntimeException("$platform block delete prepare failed: ".$db->error);
            $s->bind_param('ii',$ea,$et);if(!$s->execute())throw new RuntimeException("$platform block delete failed: ".$s->error);$s->close();
        }
        $this->verify_block($platform,$actor,$target,$active);
    }
    private function verify_block($platform,$actor,$target,$active) {
        $ea=$this->ext_id($actor,$platform);$et=$this->ext_id($target,$platform);$db=$this->db($platform);$table=$this->table($db,$platform,'block');
        if($platform===self::STREAMS)$sql="SELECT id FROM `$table` WHERE blocker=? AND blocked=? LIMIT 1";else$sql="SELECT id FROM `$table` WHERE user_id=? AND block_userid=? LIMIT 1";
        $s=$db->prepare($sql);$s->bind_param('ii',$ea,$et);$s->execute();$r=$s->get_result();$exists=$r&&$r->fetch_assoc();$s->close();
        if((bool)$exists!==($active?true:false))throw new RuntimeException("$platform block verification failed");
    }

    private function desired_qd($a,$b) {
        $l=$this->ledger($a,$b);if($l['connection_state']==='connected')return 1;if($l['connection_state']==='requested')return 0;
        $p=$this->pair($a,$b);return (int)$a===$p[0]?(int)!empty($l['follow_a_to_b']):(int)!empty($l['follow_b_to_a']);
    }
    private function project_qd_direction($a,$b){$this->desired_qd($a,$b)?$this->upsert_follow(self::SOCIALS,$a,$b,1):$this->delete_follow(self::SOCIALS,$a,$b);}
    private function project_connection($a,$b) {$this->project_qd_direction($a,$b);$this->project_qd_direction($b,$a);}
    private function project_stream_follow($a,$b,$active){$active?$this->upsert_follow(self::STREAMS,$a,$b,1):$this->delete_follow(self::STREAMS,$a,$b);}

    private function cascade_block($actor,$target,$uuid) {
        if(function_exists('friends_check_friendship_status')&&function_exists('friends_remove_friend')&&friends_check_friendship_status($actor,$target)==='is_friend'){
            $this->push_internal();try{friends_remove_friend($actor,$target);}finally{$this->pop_internal();}
        }
        if(function_exists('bp_stop_following')&&function_exists('bp_is_following')){
            foreach([[$actor,$target],[$target,$actor]] as $d) if(bp_is_following(['leader_id'=>$d[1],'follower_id'=>$d[0]])){
                $this->push_internal();try{bp_stop_following(['leader_id'=>$d[1],'follower_id'=>$d[0]]);}finally{$this->pop_internal();}
            }
        }
        $p=$this->pair($actor,$target);
        $this->save_ledger($p[0],$p[1],['connection_state'=>'none','requested_by'=>0,'follow_a_to_b'=>0,'follow_b_to_a'=>0],$uuid);
    }

    private function bb_event($op,$a,$b,$source=0) {
        if($this->internal()||$a<1||$b<1||$a===$b)return;
        $uuid=wp_generate_uuid4();if(!$this->lock($a,$b)){$this->event($uuid,self::WP,$op,$a,$b,['source'=>$source]);$this->retry($uuid,'pair lock busy');return;}
        try{
            $this->event($uuid,self::WP,$op,$a,$b,['source'=>$source]);
            if($op===self::REQUEST)$this->save_ledger($a,$b,['connection_state'=>'requested','requested_by'=>$a],$uuid);
            elseif($op===self::ACCEPT)$this->save_ledger($a,$b,['connection_state'=>'connected','requested_by'=>$a],$uuid);
            else $this->save_ledger($a,$b,['connection_state'=>'none','requested_by'=>0],$uuid);
            $this->project_connection($a,$b);$this->processed($uuid);
        }catch(Throwable $e){$this->retry($uuid,$e->getMessage());$this->log('BuddyBoss connection sync failed',['operation'=>$op,'actor'=>$a,'target'=>$b,'error'=>$e->getMessage()]);}
        finally{$this->unlock($a,$b);}
    }
    private function bb_follow($op,$follow) {
        if($this->internal())return;$a=is_object($follow)?absint($follow->follower_id??0):absint($follow['follower_id']??0);$b=is_object($follow)?absint($follow->leader_id??0):absint($follow['leader_id']??0);
        if($a<1||$b<1||$a===$b)return;
        $uuid=wp_generate_uuid4();if(!$this->lock($a,$b)){$this->event($uuid,self::WP,$op,$a,$b);$this->retry($uuid,'pair lock busy');return;}
        try{$this->event($uuid,self::WP,$op,$a,$b);$p=$this->pair($a,$b);$field=$a===$p[0]?'follow_a_to_b':'follow_b_to_a';$this->save_ledger($a,$b,[$field=>$op===self::FOLLOW?1:0],$uuid);$this->project_stream_follow($a,$b,$op===self::FOLLOW);$this->project_qd_direction($a,$b);$this->processed($uuid);}
        catch(Throwable $e){$this->retry($uuid,$e->getMessage());$this->log('BuddyBoss follow sync failed',['error'=>$e->getMessage(),'actor'=>$a,'target'=>$b]);}finally{$this->unlock($a,$b);}
    }
    private function bb_block($op,$a,$b,$mid=0) {
        if($this->internal()||$a<1||$b<1||$a===$b)return;$uuid=wp_generate_uuid4();if(!$this->lock($a,$b)){$this->event($uuid,self::WP,$op,$a,$b,['moderation_id'=>$mid]);$this->retry($uuid,'pair lock busy');return;}
        try{$this->event($uuid,self::WP,$op,$a,$b,['moderation_id'=>$mid]);
            if($op===self::BLOCK){$this->cascade_block($a,$b,$uuid);$p=$this->pair($a,$b);$field=$a===$p[0]?'block_a_to_b':'block_b_to_a';$this->save_ledger($a,$b,[$field=>1],$uuid);$this->upsert_block(self::STREAMS,$a,$b,1);$this->upsert_block(self::SOCIALS,$a,$b,1);$this->project_stream_follow($a,$b,false);$this->project_stream_follow($b,$a,false);$this->project_connection($a,$b);}
            else{$p=$this->pair($a,$b);$field=$a===$p[0]?'block_a_to_b':'block_b_to_a';$this->save_ledger($a,$b,[$field=>0],$uuid);$this->upsert_block(self::STREAMS,$a,$b,0);$this->upsert_block(self::SOCIALS,$a,$b,0);}
            $this->processed($uuid);
        }catch(Throwable $e){$this->retry($uuid,$e->getMessage());$this->log('BuddyBoss block sync failed',['operation'=>$op,'actor'=>$a,'target'=>$b,'error'=>$e->getMessage()]);}finally{$this->unlock($a,$b);}
    }

    public function bb_requested($id,$i,$f,$friendship=null){$this->bb_event(self::REQUEST,absint($i),absint($f),absint($id));}
    public function bb_accepted($id,$i,$f,$friendship=null){$this->bb_event(self::ACCEPT,absint($i),absint($f),absint($id));}
    public function bb_rejected($id,$friendship){$this->bb_event(self::REJECT,absint($friendship->initiator_user_id??0),absint($friendship->friend_user_id??0),absint($id));}
    public function bb_withdrawn($id,$friendship){$this->bb_event(self::WITHDRAW,absint($friendship->initiator_user_id??0),absint($friendship->friend_user_id??0),absint($id));}
    public function bb_deleted($id,$i,$f){$this->bb_event(self::REMOVE,absint($i),absint($f),absint($id));}
    public function bb_post_deleted($i,$f){$this->bb_event(self::REMOVE,absint($i),absint($f),0);}
    public function bb_follow_started($follow){$this->bb_follow(self::FOLLOW,$follow);}
    public function bb_follow_stopped($follow){$this->bb_follow(self::UNFOLLOW,$follow);}
    public function bb_moderation_saved($m){if($this->is_user_block($m))$this->bb_block(self::BLOCK,absint($m->user_id),absint($m->item_id),absint($m->id));}
    public function bb_moderation_deleted($m){if($this->is_user_block($m))$this->bb_block(self::UNBLOCK,absint($m->user_id),absint($m->item_id),absint($m->id));}
    private function is_user_block($m){return is_object($m)&&($m->item_type??'')==='user'&&empty($m->user_report)&&absint($m->user_id)>0&&absint($m->item_id)>0;}

    private function run_as_user($id,$fn){$old=get_current_user_id();wp_set_current_user($id);try{return $fn();}finally{wp_set_current_user($old);}}
    private function external_command($origin,$op,$actor_ext,$target_ext,$event_id) {
        if(!$this->uuid($event_id))return new WP_Error('bzj_event','Invalid event id',['status'=>400]);
        $actor=$this->wp_id($origin,$actor_ext);$target=$this->wp_id($origin,$target_ext);
        if(!$actor||!$target||$actor===$target)return new WP_Error('bzj_mapping','External users could not be mapped',['status'=>409]);
        switch($op){
            case self::REQUEST:
                if(function_exists('friends_check_friendship_status')&&friends_check_friendship_status($actor,$target)==='is_friend')return true;
                if(!function_exists('friends_add_friend'))return new WP_Error('bzj_unavailable','BuddyBoss friends unavailable',['status'=>503]);
                return friends_add_friend($actor,$target)?true:new WP_Error('bzj_request_failed','BuddyBoss rejected request',['status'=>409]);
            case self::ACCEPT:
                if(!function_exists('friends_get_friendship_id')||!function_exists('friends_accept_friendship'))return new WP_Error('bzj_unavailable','BuddyBoss acceptance unavailable',['status'=>503]);
                $fid=(int)friends_get_friendship_id($target,$actor);if(!$fid)return new WP_Error('bzj_missing_request','Pending request not found',['status'=>409]);
                $r=$this->run_as_user($actor,fn()=>friends_accept_friendship($fid));return ($r||friends_check_friendship_status($actor,$target)==='is_friend')?true:new WP_Error('bzj_accept_failed','BuddyBoss acceptance failed',['status'=>409]);
            case self::REJECT:
                if(!function_exists('friends_get_friendship_id')||!function_exists('friends_reject_friendship'))return new WP_Error('bzj_unavailable','BuddyBoss rejection unavailable',['status'=>503]);
                $fid=(int)friends_get_friendship_id($target,$actor);if(!$fid)return true;$r=$this->run_as_user($actor,fn()=>friends_reject_friendship($fid));return ($r||friends_check_friendship_status($actor,$target)==='not_friends')?true:new WP_Error('bzj_reject_failed','BuddyBoss rejection failed',['status'=>409]);
            case self::WITHDRAW:
                if(!function_exists('friends_withdraw_friendship'))return new WP_Error('bzj_unavailable','BuddyBoss withdrawal unavailable',['status'=>503]);
                $r=$this->run_as_user($actor,fn()=>friends_withdraw_friendship($actor,$target));return ($r||friends_check_friendship_status($actor,$target)==='not_friends')?true:new WP_Error('bzj_withdraw_failed','BuddyBoss withdrawal failed',['status'=>409]);
            case self::REMOVE:
                if(!function_exists('friends_remove_friend'))return new WP_Error('bzj_unavailable','BuddyBoss removal unavailable',['status'=>503]);
                if(friends_check_friendship_status($actor,$target)==='is_friend'){$r=$this->run_as_user($actor,fn()=>friends_remove_friend($actor,$target));if(!$r&&friends_check_friendship_status($actor,$target)==='is_friend')return new WP_Error('bzj_remove_failed','BuddyBoss removal failed',['status'=>409]);}return true;
            case self::FOLLOW:
            case self::UNFOLLOW:
                if(!function_exists('bp_start_following')||!function_exists('bp_stop_following'))return new WP_Error('bzj_unavailable','BuddyBoss follow functions unavailable',['status'=>503]);
                $args=['leader_id'=>$target,'follower_id'=>$actor];$r=$op===self::FOLLOW?$this->run_as_user($actor,fn()=>bp_start_following($args)):$this->run_as_user($actor,fn()=>bp_stop_following($args));return $r===false?new WP_Error('bzj_follow_failed','BuddyBoss follow operation failed',['status'=>409]):true;
            case self::BLOCK:
                if(!class_exists('BP_Moderation')||!class_exists('BP_Moderation_Members'))return new WP_Error('bzj_unavailable','BuddyBoss moderation unavailable',['status'=>503]);
                $m=new BP_Moderation($target,BP_Moderation_Members::$moderation_type,$actor);$m->user_report=0;$m->content='';$r=$this->run_as_user($actor,fn()=> $m->save());return ($r||!empty($m->id))?true:new WP_Error('bzj_block_failed','BuddyBoss block failed',['status'=>409]);
            case self::UNBLOCK:
                if(!class_exists('BP_Moderation')||!class_exists('BP_Moderation_Members'))return new WP_Error('bzj_unavailable','BuddyBoss moderation unavailable',['status'=>503]);
                $m=new BP_Moderation($target,BP_Moderation_Members::$moderation_type,$actor);if(empty($m->id))return true;$r=$this->run_as_user($actor,fn()=> $m->delete(true));return ($r||empty($m->id))?true:new WP_Error('bzj_unblock_failed','BuddyBoss unblock failed',['status'=>409]);
        }
        return new WP_Error('bzj_operation','Unsupported operation',['status'=>400]);
    }
    private function uuid($v){return (bool)preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',(string)$v);}

    public function register_routes(){
        register_rest_route(self::NS,self::ROUTE,['methods'=>'POST','callback'=>[$this,'rest_command'],'permission_callback'=>'__return_true']);
        register_rest_route(self::NS,self::ROUTE.'/diagnostics',['methods'=>'GET','callback'=>[$this,'rest_diagnostics'],'permission_callback'=>fn()=>current_user_can('manage_options')]);
    }
    private function secret(){if(function_exists('bzj_get_sso_secret')&&($s=bzj_get_sso_secret()))return $s;if(defined('BUZZ_SSO_SECRET')&&BUZZ_SSO_SECRET)return BUZZ_SSO_SECRET;return getenv('BUZZ_SSO_SECRET')?:'';}
    private function authenticate($r,$raw){
        $ts=$r->get_header('X-BZJ-Timestamp');$sig=$r->get_header('X-BZJ-Signature');
        if(!$ts||!$sig||!ctype_digit((string)$ts)||abs(time()-(int)$ts)>self::TIMESTAMP_WINDOW)return new WP_Error('bzj_auth','Invalid or expired authentication',['status'=>401]);
        $secret=$this->secret();if(!$secret)return new WP_Error('bzj_auth_unavailable','Sync secret unavailable',['status'=>503]);
        $expected=hash_hmac('sha256',(int)$ts.'.'.$raw,$secret);return hash_equals($expected,trim($sig))?true:new WP_Error('bzj_auth_invalid','Invalid signature',['status'=>401]);
    }
    public function rest_command(WP_REST_Request $r){
        $raw=$r->get_body();$auth=$this->authenticate($r,$raw);if(is_wp_error($auth))return $auth;$p=json_decode($raw,true);if(!is_array($p))return new WP_Error('bzj_json','Malformed JSON',['status'=>400]);
        $origin=sanitize_key($p['origin']??'');$op=sanitize_key($p['operation']??'');$event=sanitize_text_field($p['event_id']??'');$actor=absint($p['actor_id']??0);$target=absint($p['target_id']??0);
        if(!in_array($origin,[self::STREAMS,self::SOCIALS],true)||!in_array($op,[self::REQUEST,self::ACCEPT,self::REJECT,self::WITHDRAW,self::REMOVE,self::FOLLOW,self::UNFOLLOW,self::BLOCK,self::UNBLOCK],true)||!$this->uuid($event)||$actor<1||$target<1||$actor===$target)return new WP_Error('bzj_request','Invalid synchronization command',['status'=>400]);
        if($this->event_exists($event))return ['success'=>true,'status'=>'already_processed','event_id'=>$event];
        $wp_actor=$this->wp_id($origin,$actor);$wp_target=$this->wp_id($origin,$target);if(!$wp_actor||!$wp_target)return new WP_Error('bzj_mapping','User mapping missing',['status'=>409]);
        if(!$this->lock($wp_actor,$wp_target)){ $this->log('REST pair busy',['event_id'=>$event]);return new WP_Error('bzj_busy','Relationship pair is busy',['status'=>503]);}
        try{$this->event($event,$origin,$op,$wp_actor,$wp_target,$p);$result=$this->external_command($origin,$op,$actor,$target,$event);if(is_wp_error($result)){ $this->retry($event,$result->get_error_message());return $result;}$this->processed($event);return ['success'=>true,'status'=>'processed','event_id'=>$event];}
        catch(Throwable $e){$this->retry($event,$e->getMessage());$this->log('REST command failed',['error'=>$e->getMessage(),'payload'=>$p]);return new WP_Error('bzj_internal','Synchronization failed',['status'=>500,'event_id'=>$event]);}
        finally{$this->unlock($wp_actor,$wp_target);}
    }
    public function rest_diagnostics(){
        global $wpdb;return ['version'=>self::VERSION,'ledger_table'=>$this->ledger_table,'events_table'=>$this->events_table,'pending_events'=>(int)$wpdb->get_var("SELECT COUNT(*) FROM {$this->events_table} WHERE status='pending'"),'failed_events'=>(int)$wpdb->get_var("SELECT COUNT(*) FROM {$this->events_table} WHERE status='failed'"),'log_file'=>trailingslashit(ABSPATH).self::LOG];
    }
    public function process_recovery(){
        global $wpdb;$rows=$wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->events_table} WHERE status='pending' AND next_attempt_at<=%s ORDER BY id ASC LIMIT 25",gmdate('Y-m-d H:i:s')),ARRAY_A);
        foreach($rows as $e){$this->log('Recovery event requires reconciliation',['event_id'=>$e['event_uuid'],'origin'=>$e['origin'],'operation'=>$e['operation']]);}
    }
}
BZJ_Connections_Sync::instance();
