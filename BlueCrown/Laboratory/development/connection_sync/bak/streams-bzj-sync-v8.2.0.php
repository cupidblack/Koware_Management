<?php
/**
 * Buzzjuice Streams -> BuddyBoss synchronization adapter.
 * Include once from the WoWonder bootstrap/functions context after the DB/functions are available.
 */
defined('BZJ_STREAMS_SYNC_LOADED') || define('BZJ_STREAMS_SYNC_LOADED', true);

if (!function_exists('bzj_streams_sync_log')) {
    function bzj_streams_sync_log($message, $context = array()) {
        $dir = dirname(__DIR__, 2) . '/data/logs';
        if (!is_dir($dir)) @mkdir($dir, 0755, true);
        $entry = array('time'=>gmdate('c'),'message'=>(string)$message,'context'=>$context);
        @file_put_contents($dir.'/bzj-streams-sync.log', json_encode($entry, JSON_UNESCAPED_SLASHES).PHP_EOL, FILE_APPEND|LOCK_EX);
    }
}
if (!function_exists('bzj_streams_sync_command')) {
    function bzj_streams_sync_command($operation, $follower_id, $following_id) {
        $actor=(int)$follower_id; $target=(int)$following_id;
        if ($actor<1 || $target<1 || $actor===$target) return false;
        $secret=function_exists('bzj_get_sso_secret') ? bzj_get_sso_secret() : (defined('BUZZ_SSO_SECRET') ? BUZZ_SSO_SECRET : getenv('BUZZ_SSO_SECRET'));
        $base=getenv('WORDPRESS_API_BASE') ?: 'https://buzzjuice.net/wp-json';
        $url=rtrim($base,'/').'/bzj/v6/connection-management';
        $event=function_exists('wp_generate_uuid4') ? wp_generate_uuid4() : sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',mt_rand(0,65535),mt_rand(0,65535),mt_rand(0,65535),mt_rand(16384,20479),mt_rand(32768,49151),mt_rand(0,65535),mt_rand(0,65535),mt_rand(0,65535));
        $body=json_encode(array('origin'=>'streams','operation'=>(string)$operation,'actor_id'=>$actor,'target_id'=>$target,'event_id'=>$event,'authenticated_id'=>$actor),JSON_UNESCAPED_SLASHES);
        $ts=(string)time(); $sig=hash_hmac('sha256',(int)$ts.'.'.$body,$secret);
        $ch=curl_init($url);
        curl_setopt_array($ch,array(CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$body,CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>15,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_HTTPHEADER=>array('Content-Type: application/json','X-BZJ-Timestamp: '.$ts,'X-BZJ-Signature: '.$sig)));
        $raw=curl_exec($ch);$errno=curl_errno($ch);$error=curl_error($ch);$code=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
        if($errno||$code<200||$code>=300){bzj_streams_sync_log('WordPress synchronization request failed',array('operation'=>$operation,'actor'=>$actor,'target'=>$target,'http_code'=>$code,'curl_error'=>$error));return false;}
        $data=json_decode((string)$raw,true);if(!is_array($data)||empty($data['success'])){bzj_streams_sync_log('WordPress synchronization rejected',array('operation'=>$operation,'actor'=>$actor,'target'=>$target,'response'=>$raw));return false;}
        return true;
    }
}
