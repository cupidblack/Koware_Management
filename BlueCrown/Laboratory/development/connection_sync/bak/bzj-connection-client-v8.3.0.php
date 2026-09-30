<?php
/**
 * Buzzjuice external-platform synchronization client.
 * Include from WoWonder Streams or QuickDate after the application's DB/session is available.
 */
if (!defined('BZJ_CONNECTION_CLIENT_LOADED')) define('BZJ_CONNECTION_CLIENT_LOADED', true);

if (!function_exists('bzj_connection_sync_log')) {
    function bzj_connection_sync_log($platform,$message,$context=array()) {
        $root=defined('BZJ_CONNECTION_LOG_ROOT')?BZJ_CONNECTION_LOG_ROOT:dirname(__DIR__).'/data/logs';
        if (!is_dir($root)) @mkdir($root,0755,true);
        @file_put_contents(rtrim($root,'/\\').'/bzj-'.$platform.'-sync.log',json_encode(array('time'=>gmdate('c'),'message'=>$message,'context'=>$context),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL,FILE_APPEND|LOCK_EX);
    }
}
if (!function_exists('bzj_connection_secret')) {
    function bzj_connection_secret($platform) {
        $key=$platform==='streams'?'BZJ_STREAMS_SYNC_SECRET':'BZJ_SOCIALS_SYNC_SECRET';
        $s=getenv($key); if ($s) return $s;
        $s=getenv('BUZZ_SSO_SECRET'); if ($s) return $s;
        if (defined('BUZZ_SSO_SECRET')&&BUZZ_SSO_SECRET) return BUZZ_SSO_SECRET;
        return '';
    }
}
if (!function_exists('bzj_connection_uuid')) {
    function bzj_connection_uuid() {
        $d=random_bytes(16); $d[6]=chr((ord($d[6])&0x0f)|0x40); $d[8]=chr((ord($d[8])&0x3f)|0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s',str_split(bin2hex($d),4));
    }
}
if (!function_exists('bzj_connection_sync_command')) {
    function bzj_connection_sync_command($platform,$operation,$actor_id,$target_id) {
        $actor=(int)$actor_id; $target=(int)$target_id;
        if ($actor<1||$target<1||$actor===$target) return false;
        $secret=bzj_connection_secret($platform); if (!$secret) { bzj_connection_sync_log($platform,'Missing synchronization secret',array('operation'=>$operation)); return false; }
        $base=getenv('WORDPRESS_API_BASE'); if (!$base) $base='https://buzzjuice.net/wp-json';
        $url=rtrim($base,'/').'/bzj/v6/connection-management';
        $body=json_encode(array('origin'=>$platform,'operation'=>(string)$operation,'actor_id'=>$actor,'target_id'=>$target,'event_id'=>bzj_connection_uuid(),'authenticated_id'=>$actor),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $ts=(string)time(); $sig=hash_hmac('sha256',$ts.'.'.$body,$secret);
        if (!function_exists('curl_init')) { bzj_connection_sync_log($platform,'cURL unavailable'); return false; }
        $ch=curl_init($url); curl_setopt_array($ch,array(CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$body,CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>15,CURLOPT_HTTPHEADER=>array('Content-Type: application/json','X-BZJ-Timestamp: '.$ts,'X-BZJ-Signature: '.$sig)));
        $raw=curl_exec($ch); $errno=curl_errno($ch); $error=curl_error($ch); $code=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
        $data=json_decode((string)$raw,true);
        if ($errno||$code<200||$code>=300||!is_array($data)||empty($data['success'])) {
            bzj_connection_sync_log($platform,'WordPress synchronization request failed',array('operation'=>$operation,'actor'=>$actor,'target'=>$target,'http_code'=>$code,'curl_error'=>$error,'response'=>$raw));
            return false;
        }
        bzj_connection_sync_log($platform,'WordPress synchronization accepted',array('operation'=>$operation,'actor'=>$actor,'target'=>$target,'status'=>isset($data['status'])?$data['status']:'processed'));
        return true;
    }
}
