<?php
/**
 * Plugin Name: BZJ Connections Synchronization Control Plane
 * Description: Canonical Buzzjuice relationship control plane. BuddyBoss/WordPress is the source of truth; WoWonder Streams and QuickDate Socials are projections.
 * Version: 8.6.0
 * Author: Buzzjuice
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;
if (defined('BZJ_CONNECTIONS_SYNC_LOADED')) { return; }
define('BZJ_CONNECTIONS_SYNC_LOADED', true);

final class BZJ_Connections_Sync {
    const VERSION='8.6.0';
    const NS='bzj/v6';
    const ROUTE='/connection-management';
    const LEDGER='bzj_relationships';
    const EVENTS='bzj_relationship_events';
    const LOG_DIR='/data/logs';
    const LOG_FILE='bzj-connections-sync.log';
    const LOCK_TIMEOUT=15;
    const AUTH_WINDOW=300;
    const MAX_ATTEMPTS=12;
    const CRON='bzj_connections_sync_recovery';
    const CRON_RECONCILE='bzj_connections_sync_reconcile';
    const WP='wordpress'; const STREAMS='streams'; const SOCIALS='socials';
    const REQUEST='connection_request'; const ACCEPT='connection_accept'; const REJECT='connection_reject';
    const WITHDRAW='connection_withdraw'; const REMOVE='connection_remove'; const FOLLOW='follow'; const UNFOLLOW='unfollow';
    const BLOCK='block'; const UNBLOCK='unblock';
    private static $instance;
    private $ledger=''; private $events=''; private $locks=array(); private $context=array();
    public static function instance(){ return self::$instance ?: (self::$instance=new self()); }
    private function __construct(){
        global $wpdb; $this->ledger=$wpdb->prefix.self::LEDGER; $this->events=$wpdb->prefix.self::EVENTS;
        add_action('rest_api_init',array($this,'routes'));
        add_action('friends_friendship_requested',array($this,'bb_requested'),20,4);
        add_action('friends_friendship_accepted',array($this,'bb_accepted'),20,4);
        add_action('friends_friendship_rejected',array($this,'bb_rejected'),20,2);
        add_action('friends_friendship_withdrawn',array($this,'bb_withdrawn'),20,2);
        add_action('friends_friendship_whithdrawn',array($this,'bb_withdrawn'),20,2);
        add_action('friends_friendship_deleted',array($this,'bb_deleted'),20,3);
        add_action('friends_friendship_post_delete',array($this,'bb_post_deleted'),20,2);
        add_action('bp_start_following',array($this,'bb_follow_start'),20,1);
        add_action('bp_stop_following',array($this,'bb_follow_stop'),20,1);
        add_action('bp_follow_start_following',array($this,'bb_follow_start'),20,1);
        add_action('bp_follow_stop_following',array($this,'bb_follow_stop'),20,1);
        add_action('bp_moderation_after_save',array($this,'bb_block_saved'),20,1);
        add_action('bb_moderation_after_delete',array($this,'bb_block_deleted'),20,1);
        add_action(self::CRON,array($this,'recover'));
        add_action(self::CRON_RECONCILE,array($this,'reconcile_recent'));
        $this->schema(); $this->schedule(); $this->log('Plugin bootstrap',array('version'=>self::VERSION));
    }
    private function schema(){
        $v=get_option('bzj_connections_sync_schema_version',''); if($v===self::VERSION){return;}
        require_once ABSPATH.'wp-admin/includes/upgrade.php'; global $wpdb; $c=$wpdb->get_charset_collate();
        dbDelta("CREATE TABLE {$this->ledger} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,user_a bigint(20) unsigned NOT NULL,user_b bigint(20) unsigned NOT NULL,
            connection_state varchar(20) NOT NULL DEFAULT 'none',requested_by bigint(20) unsigned NOT NULL DEFAULT 0,
            follow_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,follow_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            block_a_to_b tinyint(1) unsigned NOT NULL DEFAULT 0,block_b_to_a tinyint(1) unsigned NOT NULL DEFAULT 0,
            version bigint(20) unsigned NOT NULL DEFAULT 1,last_event_uuid char(36) NOT NULL DEFAULT '',created_at datetime NOT NULL,updated_at datetime NOT NULL,
            PRIMARY KEY(id),UNIQUE KEY user_pair(user_a,user_b),KEY state(connection_state),KEY updated_at(updated_at),KEY requested_by(requested_by)
        ) $c;");
        dbDelta("CREATE TABLE {$this->events} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,event_uuid char(36) NOT NULL,origin varchar(20) NOT NULL,operation varchar(40) NOT NULL,
            actor_wp_id bigint(20) unsigned NOT NULL DEFAULT 0,target_wp_id bigint(20) unsigned NOT NULL DEFAULT 0,pair_a bigint(20) unsigned NOT NULL DEFAULT 0,pair_b bigint(20) unsigned NOT NULL DEFAULT 0,
            payload longtext NULL,status varchar(20) NOT NULL DEFAULT 'pending',attempts int(10) unsigned NOT NULL DEFAULT 0,next_attempt_at datetime NOT NULL,last_error text NULL,
            processed_at datetime NULL,created_at datetime NOT NULL,updated_at datetime NOT NULL,PRIMARY KEY(id),UNIQUE KEY event_uuid(event_uuid),KEY queue(status,next_attempt_at),KEY pair_key(pair_a,pair_b)
        ) $c;");
        update_option('bzj_connections_sync_schema_version',self::VERSION,false);
    }
    private function schedule(){
        if(!wp_next_scheduled(self::CRON)) wp_schedule_event(time()+300,'hourly',self::CRON);
        if(!wp_next_scheduled(self::CRON_RECONCILE)) wp_schedule_event(time()+600,'hourly',self::CRON_RECONCILE);
    }
    private function log($message,$ctx=array()){
        $dir=trailingslashit(ABSPATH).ltrim(self::LOG_DIR,'/'); if(!is_dir($dir)) wp_mkdir_p($dir); if(!is_dir($dir)||!is_writable($dir)) return;
        $entry=array('time'=>gmdate('c'),'message'=>(string)$message,'context'=>$ctx); @file_put_contents(trailingslashit($dir).self::LOG_FILE,wp_json_encode($entry,JSON_UNESCAPED_SLASHES).PHP_EOL,FILE_APPEND|LOCK_EX);
    }
    public function routes(){
        register_rest_route(self::NS,self::ROUTE,array('methods'=>WP_REST_Server::CREATABLE,'callback'=>array($this,'command'),'permission_callback'=>'__return_true'));
        register_rest_route(self::NS,self::ROUTE,array('methods'=>WP_REST_Server::READABLE,'callback'=>array($this,'status'),'permission_callback'=>function(){return current_user_can('manage_options');}));
        register_rest_route(self::NS,self::ROUTE.'/diagnostics',array('methods'=>WP_REST_Server::READABLE,'callback'=>array($this,'diagnostics'),'permission_callback'=>function(){return current_user_can('manage_options');}));
    }
    public function status(WP_REST_Request $r){$a=absint($r->get_param('user_a'));$b=absint($r->get_param('user_b'));if(!$a||!$b||$a===$b)return new WP_Error('bzj_pair','Two different user IDs are required.',array('status'=>400));return rest_ensure_response(array('success'=>true,'version'=>self::VERSION,'relationship'=>$this->ledger_get($a,$b)));}
    public function diagnostics(){
        $dir=trailingslashit(ABSPATH).ltrim(self::LOG_DIR,'/'); $s=$this->db('streams');$q=$this->db('socials');
        $st=$s?array('followers'=>$this->table($s,array('Wo_Followers','wo_followers','followers')),'blocks'=>$this->table($s,array('Wo_Blocks','wo_blocks','blocks'))):array();
        $qt=$q?array('followers'=>$this->table($q,array('followers')),'blocks'=>$this->table($q,array('blocks'))):array();
        $this->log('Diagnostics requested',array('wp_database'=>defined('DB_NAME')?DB_NAME:'','streams_connected'=>(bool)$s,'socials_connected'=>(bool)$q,'streams_tables'=>$st,'socials_tables'=>$qt));
        return rest_ensure_response(array('plugin_version'=>self::VERSION,'route'=>rest_url(self::NS.self::ROUTE),'log'=>trailingslashit($dir).self::LOG_FILE,'databases'=>array('streams_connected'=>(bool)$s,'socials_connected'=>(bool)$q,'streams_tables'=>$st,'socials_tables'=>$qt),'cron'=>array('recovery'=>wp_next_scheduled(self::CRON),'reconcile'=>wp_next_scheduled(self::CRON_RECONCILE))));
    }
    private function secret(){if(function_exists('bzj_get_sso_secret')){$s=bzj_get_sso_secret();if($s)return $s;}return defined('BUZZ_SSO_SECRET')&&BUZZ_SSO_SECRET?BUZZ_SSO_SECRET:(getenv('BUZZ_SSO_SECRET')?:'');}
    private function auth(WP_REST_Request $r,$raw){$ts=$r->get_header('X-BZJ-Timestamp');$sig=$r->get_header('X-BZJ-Signature');if(!$ts||!ctype_digit((string)$ts)||!$sig)return new WP_Error('bzj_auth','Missing synchronization authentication.',array('status'=>401));if(abs(time()-(int)$ts)>self::AUTH_WINDOW)return new WP_Error('bzj_auth_expired','Synchronization request expired.',array('status'=>401));$secret=$this->secret();if(!$secret)return new WP_Error('bzj_secret','Synchronization secret unavailable.',array('status'=>503));$expected=hash_hmac('sha256',$ts.'.'.$raw,$secret);return hash_equals($expected,trim($sig))?true:new WP_Error('bzj_auth','Invalid synchronization signature.',array('status'=>401));}
    public function command(WP_REST_Request $r){
        $raw=$r->get_body();$a=$this->auth($r,$raw);if(is_wp_error($a))return $a;$p=json_decode($raw,true);if(!is_array($p))return new WP_Error('bzj_json','Malformed JSON.',array('status'=>400));
        $origin=sanitize_key($p['origin']??'');$op=sanitize_key($p['operation']??'');$event=sanitize_text_field($p['event_id']??'');$ae=absint($p['actor_id']??0);$te=absint($p['target_id']??0);
        if(!in_array($origin,array(self::STREAMS,self::SOCIALS),true))return new WP_Error('bzj_origin','Origin must be streams or socials.',array('status'=>400));
        if(!in_array($op,array(self::REQUEST,self::ACCEPT,self::REJECT,self::WITHDRAW,self::REMOVE,self::FOLLOW,self::UNFOLLOW,self::BLOCK,self::UNBLOCK),true))return new WP_Error('bzj_operation','Unsupported operation.',array('status'=>400));
        if(!$this->uuid($event)||$ae<1||$te<1||$ae===$te)return new WP_Error('bzj_identity','Valid actor, target and event ID are required.',array('status'=>400));
        if(isset($p['authenticated_id'])&&absint($p['authenticated_id'])!==$ae)return new WP_Error('bzj_actor','Authenticated actor mismatch.',array('status'=>403));
        $aw=$this->map_external($origin,$ae);$tw=$this->map_external($origin,$te);if(!$aw||!$tw||$aw===$tw)return new WP_Error('bzj_mapping','External IDs could not be mapped to WordPress users.',array('status'=>409));
        if($this->event_exists($event))return rest_ensure_response(array('success'=>true,'status'=>'already_processed','event_id'=>$event,'relationship'=>$this->ledger_get($aw,$tw)));
        if(!$this->lock($aw,$tw))return new WP_Error('bzj_busy','Relationship pair is busy.',array('status'=>503));
        try{$this->event_insert($event,$origin,$op,$aw,$tw,$p);$result=$this->external_command($op,$aw,$tw,$event,$origin,$p);if(is_wp_error($result)){ $this->retry($event,$result->get_error_message()); return $result; }$this->done($event);return rest_ensure_response(array('success'=>true,'status'=>'processed','event_id'=>$event,'relationship'=>$this->ledger_get($aw,$tw)));}catch(Throwable $e){$this->retry($event,$e->getMessage());$this->log('REST command exception',array('event_id'=>$event,'origin'=>$origin,'operation'=>$op,'actor_wp'=>$aw,'target_wp'=>$tw,'error'=>$e->getMessage()));return new WP_Error('bzj_internal','Synchronization failed.',array('status'=>500,'event_id'=>$event));}finally{$this->unlock($aw,$tw);}
    }
    private function external_command($op,$a,$b,$event,$origin,$p){
        if($op===self::FOLLOW && $origin===self::SOCIALS)$op=self::REQUEST;
        if($op===self::UNFOLLOW && $origin===self::SOCIALS)$op=self::WITHDRAW;
        switch($op){
            case self::REQUEST:return $this->bb_request($a,$b);
            case self::ACCEPT:return $this->bb_accept($a,$b);
            case self::REJECT:return $this->bb_reject($a,$b);
            case self::WITHDRAW:return $this->bb_withdraw($a,$b);
            case self::REMOVE:return $this->bb_remove($a,$b);
            case self::FOLLOW:return $this->bb_follow($a,$b);
            case self::UNFOLLOW:return $this->bb_unfollow($a,$b);
            case self::BLOCK:return $this->bb_block($a,$b,$event);
            case self::UNBLOCK:return $this->bb_unblock($a,$b,$event);
        }return new WP_Error('bzj_unknown','Unknown operation.');
    }
    private function bb_request($a,$b){if($this->blocked($a,$b))return new WP_Error('bzj_blocked','Blocked users cannot connect.');if(!function_exists('friends_add_friend'))return new WP_Error('bzj_bb','BuddyBoss connections unavailable.');$s=friends_check_friendship_status($a,$b);if($s==='is_friend'||$s==='pending')return true;if($s==='awaiting_response')return new WP_Error('bzj_reverse','A connection request already exists in the opposite direction.');return friends_add_friend($a,$b)?true:new WP_Error('bzj_request','BuddyBoss rejected the request.');}
    private function friendship_id($a,$b){if(!function_exists('friends_get_friendship_id'))return 0;$id=(int)friends_get_friendship_id($a,$b);return $id?: (int)friends_get_friendship_id($b,$a);}
    private function bb_accept($a,$b){$id=$this->friendship_id($a,$b);if(!$id)return new WP_Error('bzj_request_missing','No pending BuddyBoss request found.');if(!friends_accept_friendship($id)&&friends_check_friendship_status($a,$b)!=='is_friend')return new WP_Error('bzj_accept','BuddyBoss could not accept the request.');return true;}
    private function bb_reject($a,$b){$id=$this->friendship_id($a,$b);if(!$id)return true;if(!friends_reject_friendship($id)&&friends_check_friendship_status($a,$b)!=='not_friends')return new WP_Error('bzj_reject','BuddyBoss could not reject the request.');return true;}
    private function bb_withdraw($a,$b){if(!function_exists('friends_withdraw_friendship'))return new WP_Error('bzj_bb','BuddyBoss withdrawal unavailable.');if(!friends_withdraw_friendship($a,$b)&&friends_check_friendship_status($a,$b)!=='not_friends')return new WP_Error('bzj_withdraw','BuddyBoss could not withdraw the request.');return true;}
    private function bb_remove($a,$b){if(!function_exists('friends_remove_friend'))return new WP_Error('bzj_bb','BuddyBoss removal unavailable.');if(friends_check_friendship_status($a,$b)==='is_friend'){if(!friends_remove_friend($a,$b)&&!friends_remove_friend($b,$a))return new WP_Error('bzj_remove','BuddyBoss could not remove the connection.');}return true;}
    private function bb_follow($a,$b){if($this->blocked($a,$b))return new WP_Error('bzj_blocked','Blocked users cannot follow.');if(!function_exists('bp_start_following'))return new WP_Error('bzj_bb_follow','BuddyBoss follow unavailable.');if(function_exists('bp_is_following')&&bp_is_following(array('leader_id'=>$b,'follower_id'=>$a)))return true;$r=bp_start_following(array('leader_id'=>$b,'follower_id'=>$a));return is_wp_error($r)?$r:($r===false?new WP_Error('bzj_follow','BuddyBoss rejected the follow.'):true);}
    private function bb_unfollow($a,$b){if(!function_exists('bp_stop_following'))return new WP_Error('bzj_bb_follow','BuddyBoss unfollow unavailable.');if(function_exists('bp_is_following')&&!bp_is_following(array('leader_id'=>$b,'follower_id'=>$a)))return true;$r=bp_stop_following(array('leader_id'=>$b,'follower_id'=>$a));return is_wp_error($r)?$r:($r===false?new WP_Error('bzj_unfollow','BuddyBoss rejected the unfollow.'):true);}
    private function bb_block($a,$b,$event){if(!class_exists('BP_Moderation')||!class_exists('BP_Moderation_Members'))return new WP_Error('bzj_moderation','BuddyBoss moderation unavailable.');if(!$this->bb_block_exists($a,$b)){$m=new BP_Moderation($b,BP_Moderation_Members::$moderation_type,$a);$m->user_report=0;$m->content='';$this->push();try{if(!$m->save())return new WP_Error('bzj_block','BuddyBoss could not create the block.');}finally{$this->pop();}}$this->enforce_bb_block($a,$b);$this->canonical_block($a,$b,$event,true);$this->project_block($a,$b,true,'streams');$this->project_block($a,$b,true,'socials');return true;}
    private function bb_unblock($a,$b,$event){if(!class_exists('BP_Moderation')||!class_exists('BP_Moderation_Members'))return new WP_Error('bzj_moderation','BuddyBoss moderation unavailable.');$m=new BP_Moderation($b,BP_Moderation_Members::$moderation_type,$a);if(!empty($m->id)&&empty($m->user_report)){$this->push();try{if(!$m->delete(false))return new WP_Error('bzj_unblock','BuddyBoss could not remove the block.');}finally{$this->pop();}}$this->canonical_block($a,$b,$event,false);$this->project_block($a,$b,false,'streams');$this->project_block($a,$b,false,'socials');return true;}
    public function bb_requested($id,$a,$b){$this->bb_connection('requested',$a,$b,$id);}
    public function bb_accepted($id,$a,$b){$this->bb_connection('accepted',$a,$b,$id);}
    public function bb_rejected($id,$f=null){if($this->internal()||!is_object($f))return;$this->bb_connection('rejected',(int)$f->initiator_user_id,(int)$f->friend_user_id,$id);}
    public function bb_withdrawn($id,$f=null){if($this->internal()||!is_object($f))return;$this->bb_connection('withdrawn',(int)$f->initiator_user_id,(int)$f->friend_user_id,$id);}
    public function bb_deleted($id,$a,$b){if($this->internal())return;$this->bb_connection('deleted',(int)$a,(int)$b,$id);}
    public function bb_post_deleted($a,$b){if($this->internal())return;$this->bb_connection('post_deleted',(int)$a,(int)$b,0);}
    private function bb_connection($event,$a,$b,$id){$a=absint($a);$b=absint($b);if(!$a||!$b||$a===$b)return;if(!$this->lock($a,$b)){ $u=wp_generate_uuid4();$this->event_insert($u,self::WP,'connection_'.$event,$a,$b,array('friendship_id'=>$id));$this->retry($u,'Pair lock busy.');return;}$u=wp_generate_uuid4();try{$this->event_insert($u,self::WP,'connection_'.$event,$a,$b,array('friendship_id'=>$id));$state='none';$requester=0;if($event==='requested'){$state='requested';$requester=$a;}elseif($event==='accepted')$state='connected';elseif(in_array($event,array('deleted','post_deleted','rejected','withdrawn'),true)){$s=$this->bb_status($a,$b);if($event==='deleted'||$event==='post_deleted'){$state='none';$requester=0;}elseif($s==='is_friend'){$state='connected';}elseif($s==='pending'){$state='requested';$requester=$a;}elseif($s==='awaiting_response'){$state='requested';$requester=$b;}}
        $this->ledger_save($a,$b,array('connection_state'=>$state,'requested_by'=>$requester),$u);
        $this->project_social_connection($a,$b,$state);$this->done($u);
        }catch(Throwable $e){$this->retry($u,$e->getMessage());$this->log('BuddyBoss connection hook failed',array('event'=>$event,'actor'=>$a,'target'=>$b,'error'=>$e->getMessage()));}finally{$this->unlock($a,$b);}}
    public function bb_follow_start($f){$this->bb_follow_hook(self::FOLLOW,$f);} public function bb_follow_stop($f){$this->bb_follow_hook(self::UNFOLLOW,$f);}
    private function bb_follow_hook($op,$f){if($this->internal()||!is_object($f))return;$a=absint($f->follower_id??0);$b=absint($f->leader_id??0);if(!$a||!$b||$a===$b)return;if(!$this->lock($a,$b))return;$u=wp_generate_uuid4();try{$this->event_insert($u,self::WP,$op,$a,$b);$this->ledger_save($a,$b,array($this->follow_field($a,$b)=>$op===self::FOLLOW?1:0),$u);$this->project_follow($a,$b,$op===self::FOLLOW,'streams');/* Deliberately no QuickDate follow projection. */$this->done($u);}catch(Throwable $e){$this->retry($u,$e->getMessage());$this->log('BuddyBoss follow hook failed',array('operation'=>$op,'actor'=>$a,'target'=>$b,'error'=>$e->getMessage()));}finally{$this->unlock($a,$b);}}
    public function bb_block_saved($m){if(!$this->valid_block($m))return;$this->bb_block_hook(self::BLOCK,(int)$m->user_id,(int)$m->item_id,(int)$m->id);}
    public function bb_block_deleted($m){if(!$this->valid_block($m))return;$this->bb_block_hook(self::UNBLOCK,(int)$m->user_id,(int)$m->item_id,(int)$m->id);}
    private function valid_block($m){return is_object($m)&&($m->item_type??'')==='user'&&empty($m->user_report)&&absint($m->user_id)>0&&absint($m->item_id)>0;}
    private function bb_block_hook($op,$a,$b,$mid){if($this->internal()||$a===$b)return;if(!$this->lock($a,$b))return;$u=wp_generate_uuid4();try{$this->event_insert($u,self::WP,$op,$a,$b,array('moderation_id'=>$mid));if($op===self::BLOCK){$this->enforce_bb_block($a,$b);$this->canonical_block($a,$b,$u,true);$this->project_block($a,$b,true,'streams');$this->project_block($a,$b,true,'socials');}else{$this->canonical_block($a,$b,$u,false);$this->project_block($a,$b,false,'streams');$this->project_block($a,$b,false,'socials');}$this->done($u);}catch(Throwable $e){$this->retry($u,$e->getMessage());$this->log('BuddyBoss block hook failed',array('operation'=>$op,'actor'=>$a,'target'=>$b,'error'=>$e->getMessage()));}finally{$this->unlock($a,$b);}}
    private function enforce_bb_block($a,$b){if(function_exists('friends_check_friendship_status')&&function_exists('friends_remove_friend')&&friends_check_friendship_status($a,$b)==='is_friend'){$this->push();try{friends_remove_friend($a,$b);}finally{$this->pop();}}if(function_exists('bp_stop_following'))foreach(array(array($a,$b),array($b,$a)) as $x){if(function_exists('bp_is_following')&&bp_is_following(array('leader_id'=>$x[1],'follower_id'=>$x[0]))){$this->push();try{bp_stop_following(array('leader_id'=>$x[1],'follower_id'=>$x[0]));}finally{$this->pop();}}}}
    private function canonical_block($a,$b,$u,$on){$this->ledger_save($a,$b,array($this->block_field($a,$b)=>$on?1:0,'connection_state'=>'none','requested_by'=>0,'follow_a_to_b'=>0,'follow_b_to_a'=>0),$u);}
    private function bb_status($a,$b){return function_exists('friends_check_friendship_status')?friends_check_friendship_status($a,$b):'not_friends';}
    private function blocked($a,$b){return $this->bb_block_exists($a,$b)||$this->bb_block_exists($b,$a);}
    private function bb_block_exists($a,$b){if(class_exists('BP_Moderation')&&class_exists('BP_Moderation_Members')){$m=new BP_Moderation($b,BP_Moderation_Members::$moderation_type,$a);if(!empty($m->id)&&empty($m->user_report))return true;} $l=$this->ledger_get($a,$b);$p=$this->pair($a,$b);return ((int)$a===$p[0])?!empty($l['block_a_to_b']):!empty($l['block_b_to_a']);}
    private function pair($a,$b){$a=absint($a);$b=absint($b);return $a<$b?array($a,$b):array($b,$a);} private function follow_field($a,$b){$p=$this->pair($a,$b);return $a===$p[0]?'follow_a_to_b':'follow_b_to_a';} private function block_field($a,$b){$p=$this->pair($a,$b);return $a===$p[0]?'block_a_to_b':'block_b_to_a';}
    private function ledger_get($a,$b){global $wpdb;$p=$this->pair($a,$b);$r=$wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->ledger} WHERE user_a=%d AND user_b=%d LIMIT 1",$p[0],$p[1]),ARRAY_A);return $r?:array('user_a'=>$p[0],'user_b'=>$p[1],'connection_state'=>'none','requested_by'=>0,'follow_a_to_b'=>0,'follow_b_to_a'=>0,'block_a_to_b'=>0,'block_b_to_a'=>0,'version'=>0,'last_event_uuid'=>'');}
    private function ledger_save($a,$b,$ch,$u=''){global $wpdb;$p=$this->pair($a,$b);$e=$this->ledger_get($a,$b);$now=gmdate('Y-m-d H:i:s');$d=array('user_a'=>$p[0],'user_b'=>$p[1],'connection_state'=>$ch['connection_state']??$e['connection_state'],'requested_by'=>absint($ch['requested_by']??$e['requested_by']),'follow_a_to_b'=>isset($ch['follow_a_to_b'])?(int)!!$ch['follow_a_to_b']:(int)$e['follow_a_to_b'],'follow_b_to_a'=>isset($ch['follow_b_to_a'])?(int)!!$ch['follow_b_to_a']:(int)$e['follow_b_to_a'],'block_a_to_b'=>isset($ch['block_a_to_b'])?(int)!!$ch['block_a_to_b']:(int)$e['block_a_to_b'],'block_b_to_a'=>isset($ch['block_b_to_a'])?(int)!!$ch['block_b_to_a']:(int)$e['block_b_to_a'],'version'=>max(1,(int)$e['version']+1),'last_event_uuid'=>$u?:$e['last_event_uuid'],'updated_at'=>$now);if(!$e['version']){$d['created_at']=$now;$wpdb->insert($this->ledger,$d);}else{$wpdb->update($this->ledger,$d,array('user_a'=>$p[0],'user_b'=>$p[1]));}return $this->ledger_get($a,$b);}
    private function load_helpers(){static $x=null;if($x!==null)return $x;$p=array(ABSPATH.'shared/db_helpers.php',dirname(ABSPATH).'/shared/db_helpers.php');foreach($p as $f){if(is_file($f)){require_once $f;$x=true;return true;}}$this->log('db_helpers.php missing',array('paths'=>$p));$x=false;return false;}
    private function db($platform){if(!$this->load_helpers())return false;$f=$platform==='streams'?'get_wowonder_db':'get_qd_db_conn';if(!function_exists($f))return false;$d=$f();return $d instanceof mysqli?$d:false;}
    private function map_wp($id,$platform){$k=$platform==='streams'?'wo_user_id':'qd_user_id';return absint(get_user_meta($id,$k,true));}
    private function map_external($platform,$id){global $wpdb;$k=$platform==='streams'?'wo_user_id':'qd_user_id';return (int)$wpdb->get_var($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key=%s AND meta_value=%s ORDER BY umeta_id ASC LIMIT 1",$k,(string)absint($id)));}
    private function table($db,$c){foreach($c as $t){$safe=preg_replace('/[^A-Za-z0-9_]/','',$t);$r=$db->query("SHOW TABLES LIKE '".$db->real_escape_string($safe)."'");if($r&&$r->num_rows)return $safe;}return false;}
    private function columns($db,$table){$o=array();$r=$db->query("SHOW COLUMNS FROM `".preg_replace('/[^A-Za-z0-9_]/','',$table)."`");if($r)while($x=$r->fetch_assoc())$o[strtolower($x['Field'])]=true;return $o;}
    private function follow_table($db,$platform){return $this->table($db,$platform==='streams'?array('Wo_Followers','wo_followers','followers'):array('followers'));}
    private function block_table($db,$platform){return $this->table($db,$platform==='streams'?array('Wo_Blocks','wo_blocks','blocks'):array('blocks'));}
    private function project_follow($a,$b,$on,$platform){$ea=$this->map_wp($a,$platform);$eb=$this->map_wp($b,$platform);if(!$ea||!$eb){$this->queue($platform,$on?self::FOLLOW:self::UNFOLLOW,$a,$b);return false;}$db=$this->db($platform);if(!$db){$this->queue($platform,$on?self::FOLLOW:self::UNFOLLOW,$a,$b);return false;}$t=$this->follow_table($db,$platform);if(!$t){$this->queue($platform,$on?self::FOLLOW:self::UNFOLLOW,$a,$b);return false;}try{if($on){$this->follow_upsert($db,$t,$eb,$ea);}else{$this->follow_delete($db,$t,$eb,$ea);} $this->log('Projection success',array('platform'=>$platform,'table'=>$t,'operation'=>$on?self::FOLLOW:self::UNFOLLOW,'actor_external'=>$ea,'target_external'=>$eb));return true;}catch(Throwable $e){$this->queue($platform,$on?self::FOLLOW:self::UNFOLLOW,$a,$b,array('error'=>$e->getMessage()));$this->log('Follow projection failed',array('platform'=>$platform,'table'=>$t,'error'=>$e->getMessage()));return false;}}
    private function follow_upsert($db,$t,$following,$follower){$q=$db->prepare("SELECT id FROM `{$t}` WHERE following_id=? AND follower_id=? LIMIT 1");if(!$q)throw new RuntimeException($db->error);$q->bind_param('ii',$following,$follower);$q->execute();$q->bind_result($id);$exists=$q->fetch();$q->close();if($exists){$q=$db->prepare("UPDATE `{$t}` SET active=1 WHERE id=?");if(!$q)throw new RuntimeException($db->error);$q->bind_param('i',$id);$ok=$q->execute();$err=$q->error;$q->close();if(!$ok)throw new RuntimeException($err?:'Follow update failed.');return;}$cols=$this->columns($db,$t);if(isset($cols['created_at'])){$now=time();$q=$db->prepare("INSERT INTO `{$t}` (following_id,follower_id,active,created_at) VALUES(?,?,1,?)");$q->bind_param('iii',$following,$follower,$now);}else{$q=$db->prepare("INSERT INTO `{$t}` (following_id,follower_id,active) VALUES(?,?,1)");$q->bind_param('ii',$following,$follower);}if(!$q)throw new RuntimeException($db->error);$ok=$q->execute();$err=$q->error;$q->close();if(!$ok)throw new RuntimeException($err?:'Follow insert failed.');}
    private function follow_delete($db,$t,$following,$follower){$q=$db->prepare("DELETE FROM `{$t}` WHERE following_id=? AND follower_id=?");if(!$q)throw new RuntimeException($db->error);$q->bind_param('ii',$following,$follower);$ok=$q->execute();$err=$q->error;$q->close();if(!$ok)throw new RuntimeException($err?:'Follow delete failed.');}
    private function project_social_connection($a,$b,$state){$qa=$this->map_wp($a,'socials');$qb=$this->map_wp($b,'socials');$db=$this->db('socials');$t=$db?$this->follow_table($db,'socials'):false;if(!$qa||!$qb||!$db||!$t){$this->queue('socials','connection_'.$state,$a,$b);return false;}try{if($state==='requested'){ $l=$this->ledger_get($a,$b);$r=absint($l['requested_by']);if(!$r)throw new RuntimeException('No requester in ledger.');$recipient=$r===$a?$b:$a;$qr=$this->map_wp($r,'socials');$qt=$this->map_wp($recipient,'socials');if(!$qr||!$qt)throw new RuntimeException('QuickDate mapping missing.');$this->qd_friend_row($db,$t,$qt,$qr,0);}elseif($state==='connected'){$this->qd_friend_row($db,$t,$qb,$qa,1);}else{$this->qd_remove_pair($db,$t,$qa,$qb);} $this->log('QuickDate connection projection success',array('table'=>$t,'state'=>$state,'user_a'=>$qa,'user_b'=>$qb));return true;}catch(Throwable $e){$this->queue('socials','connection_'.$state,$a,$b,array('error'=>$e->getMessage()));$this->log('QuickDate connection projection failed',array('table'=>$t,'state'=>$state,'error'=>$e->getMessage()));return false;}}
    private function qd_friend_row($db,$t,$following,$follower,$active){$q=$db->prepare("SELECT id FROM `{$t}` WHERE following_id=? AND follower_id=? LIMIT 1");if(!$q)throw new RuntimeException($db->error);$q->bind_param('ii',$following,$follower);$q->execute();$q->bind_result($id);$exists=$q->fetch();$q->close();if($exists){$q=$db->prepare("UPDATE `{$t}` SET active=? WHERE id=?");$q->bind_param('ii',$active,$id);}else{$cols=$this->columns($db,$t);if(isset($cols['created_at'])){$now=time();$q=$db->prepare("INSERT INTO `{$t}` (following_id,follower_id,active,created_at) VALUES(?,?,?,?)");$q->bind_param('iiii',$following,$follower,$active,$now);}else{$q=$db->prepare("INSERT INTO `{$t}` (following_id,follower_id,active) VALUES(?,?,?)");$q->bind_param('iii',$following,$follower,$active);}}if(!$q)throw new RuntimeException($db->error);$ok=$q->execute();$err=$q->error;$q->close();if(!$ok)throw new RuntimeException($err?:'QuickDate relationship write failed.');}
    private function qd_remove_pair($db,$t,$a,$b){$q=$db->prepare("DELETE FROM `{$t}` WHERE (following_id=? AND follower_id=?) OR (following_id=? AND follower_id=?)");if(!$q)throw new RuntimeException($db->error);$q->bind_param('iiii',$a,$b,$b,$a);$ok=$q->execute();$err=$q->error;$q->close();if(!$ok)throw new RuntimeException($err?:'QuickDate relationship delete failed.');}
    private function project_block($a,$b,$on,$platform){$ea=$this->map_wp($a,$platform);$eb=$this->map_wp($b,$platform);$db=$this->db($platform);$t=$db?$this->block_table($db,$platform):false;if(!$ea||!$eb||!$db||!$t){$this->queue($platform,$on?self::BLOCK:self::UNBLOCK,$a,$b);return false;}try{if($on){if($platform==='streams')$q=$db->prepare("SELECT id FROM `{$t}` WHERE blocker=? AND blocked=? LIMIT 1");else $q=$db->prepare("SELECT id FROM `{$t}` WHERE user_id=? AND block_userid=? LIMIT 1");$q->bind_param('ii',$ea,$eb);$q->execute();$q->bind_result($id);$exists=$q->fetch();$q->close();if(!$exists){if($platform==='streams'){$q=$db->prepare("INSERT INTO `{$t}` (blocker,blocked) VALUES(?,?)");$q->bind_param('ii',$ea,$eb);}else{$now=gmdate('Y-m-d H:i:s');$q=$db->prepare("INSERT INTO `{$t}` (user_id,block_userid,created_at) VALUES(?,?,?)");$q->bind_param('iis',$ea,$eb,$now);}if(!$q)throw new RuntimeException($db->error);if(!$q->execute()){$e=$q->error;$q->close();throw new RuntimeException($e?:'Block insert failed.');}$q->close();} $ft=$this->follow_table($db,$platform);if($ft)$this->external_follow_pair_delete($db,$ft,$ea,$eb);}else{if($platform==='streams')$q=$db->prepare("DELETE FROM `{$t}` WHERE blocker=? AND blocked=?");else $q=$db->prepare("DELETE FROM `{$t}` WHERE user_id=? AND block_userid=?");$q->bind_param('ii',$ea,$eb);$q->execute();$q->close();} $this->log('Block projection success',array('platform'=>$platform,'table'=>$t,'operation'=>$on?self::BLOCK:self::UNBLOCK,'actor_external'=>$ea,'target_external'=>$eb));return true;}catch(Throwable $e){$this->queue($platform,$on?self::BLOCK:self::UNBLOCK,$a,$b,array('error'=>$e->getMessage()));$this->log('Block projection failed',array('platform'=>$platform,'table'=>$t,'error'=>$e->getMessage()));return false;}}
    private function external_follow_pair_delete($db,$t,$a,$b){$q=$db->prepare("DELETE FROM `{$t}` WHERE (following_id=? AND follower_id=?) OR (following_id=? AND follower_id=?)");if(!$q)throw new RuntimeException($db->error);$q->bind_param('iiii',$a,$b,$b,$a);$q->execute();$q->close();}
    private function event_exists($u){global $wpdb;return (bool)$wpdb->get_var($wpdb->prepare("SELECT id FROM {$this->events} WHERE event_uuid=%s LIMIT 1",$u));}
    private function event_insert($u,$o,$op,$a,$b,$p=array()){global $wpdb;if($this->event_exists($u))return;$x=$this->pair($a,$b);$now=gmdate('Y-m-d H:i:s');$wpdb->insert($this->events,array('event_uuid'=>$u,'origin'=>sanitize_key($o),'operation'=>sanitize_key($op),'actor_wp_id'=>absint($a),'target_wp_id'=>absint($b),'pair_a'=>$x[0],'pair_b'=>$x[1],'payload'=>wp_json_encode($p),'status'=>'pending','attempts'=>0,'next_attempt_at'=>$now,'created_at'=>$now,'updated_at'=>$now));}
    private function done($u){global $wpdb;$now=gmdate('Y-m-d H:i:s');$wpdb->update($this->events,array('status'=>'processed','processed_at'=>$now,'updated_at'=>$now,'last_error'=>null),array('event_uuid'=>$u));}
    private function retry($u,$err){global $wpdb;$n=(int)$wpdb->get_var($wpdb->prepare("SELECT attempts FROM {$this->events} WHERE event_uuid=%s",$u))+1;$status=$n>=self::MAX_ATTEMPTS?'failed':'pending';$next=$status==='failed'?gmdate('Y-m-d H:i:s'):gmdate('Y-m-d H:i:s',time()+min(3600,max(60,2**min(10,$n))));$wpdb->update($this->events,array('status'=>$status,'attempts'=>$n,'next_attempt_at'=>$next,'last_error'=>substr((string)$err,0,65000),'updated_at'=>gmdate('Y-m-d H:i:s')),array('event_uuid'=>$u));}
    private function queue($platform,$op,$a,$b,$p=array()){$u=wp_generate_uuid4();$p['projection']=true;$this->event_insert($u,$platform,$op,$a,$b,$p);return $u;}
    public function recover(){global $wpdb;$rows=$wpdb->get_results("SELECT * FROM {$this->events} WHERE status='pending' AND next_attempt_at<=UTC_TIMESTAMP() ORDER BY id ASC LIMIT 25");foreach((array)$rows as $r)$this->recover_one($r);}
    private function recover_one($r){$a=(int)$r->actor_wp_id;$b=(int)$r->target_wp_id;if(!$a||!$b){$this->retry($r->event_uuid,'Invalid recovery users.');return;}if(!$this->lock($a,$b)){return;}try{$this->refresh($a,$b);$this->project_pair($a,$b);$this->done($r->event_uuid);}catch(Throwable $e){$this->retry($r->event_uuid,$e->getMessage());}finally{$this->unlock($a,$b);}}
    public function reconcile_recent(){global $wpdb;$rows=$wpdb->get_results("SELECT user_a,user_b FROM {$this->ledger} WHERE updated_at>=UTC_TIMESTAMP()-INTERVAL 7 DAY ORDER BY updated_at DESC LIMIT 100");foreach((array)$rows as $r){$a=(int)$r->user_a;$b=(int)$r->user_b;if($this->lock($a,$b)){try{$this->refresh($a,$b);$this->project_pair($a,$b);}finally{$this->unlock($a,$b);}}}}
    private function refresh($a,$b){$c='none';$rq=0;$s=$this->bb_status($a,$b);if($s==='is_friend')$c='connected';elseif($s==='pending'){$c='requested';$rq=$a;}elseif($s==='awaiting_response'){$c='requested';$rq=$b;}$fa=function_exists('bp_is_following')&&bp_is_following(array('leader_id'=>$b,'follower_id'=>$a));$fb=function_exists('bp_is_following')&&bp_is_following(array('leader_id'=>$a,'follower_id'=>$b));$ba=$this->bb_block_exists($a,$b);$bb=$this->bb_block_exists($b,$a);if($ba||$bb){$c='none';$rq=0;$fa=$fb=false;}$this->ledger_save($a,$b,array('connection_state'=>$c,'requested_by'=>$rq,'follow_a_to_b'=>$fa?1:0,'follow_b_to_a'=>$fb?1:0,'block_a_to_b'=>$ba?1:0,'block_b_to_a'=>$bb?1:0),wp_generate_uuid4());}
    private function project_pair($a,$b){$l=$this->ledger_get($a,$b);if(!empty($l['block_a_to_b']))$this->project_block($a,$b,true,'streams');else $this->project_block($a,$b,false,'streams');if(!empty($l['block_b_to_a']))$this->project_block($b,$a,true,'streams');else $this->project_block($b,$a,false,'streams');if(!empty($l['block_a_to_b']))$this->project_block($a,$b,true,'socials');else $this->project_block($a,$b,false,'socials');if(!empty($l['block_b_to_a']))$this->project_block($b,$a,true,'socials');else $this->project_block($b,$a,false,'socials');if(empty($l['block_a_to_b'])&&empty($l['block_b_to_a'])){$this->project_social_connection($a,$b,$l['connection_state']);$this->project_follow($a,$b,!empty($l['follow_a_to_b']),'streams');$this->project_follow($b,$a,!empty($l['follow_b_to_a']),'streams');}}
    private function push(){$this->context[]=1;}private function pop(){array_pop($this->context);}private function internal(){return !empty($this->context);}
    private function lock($a,$b){global $wpdb;$p=$this->pair($a,$b);$n='bzj_rel_'.$p[0].'_'.$p[1];if(isset($this->locks[$n])){$this->locks[$n]++;return true;}$r=$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,%d)',$n,self::LOCK_TIMEOUT));if((int)$r!==1)return false;$this->locks[$n]=1;return true;}
    private function unlock($a,$b){global $wpdb;$p=$this->pair($a,$b);$n='bzj_rel_'.$p[0].'_'.$p[1];if(!isset($this->locks[$n]))return;if(--$this->locks[$n]<=0){unset($this->locks[$n]);$wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)',$n));}}
    private function uuid($u){return (bool)preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',(string)$u);}
}
BZJ_Connections_Sync::instance();
