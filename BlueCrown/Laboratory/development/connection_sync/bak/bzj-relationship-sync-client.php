<?php
/**
 * BZJ external relationship sync client
 * Install at: /shared/bzj-relationship-sync-client.php
 * Include from both Streams and Socials after the platform bootstrap.
 */
defined('BZJ_REL_SYNC_CLIENT') || define('BZJ_REL_SYNC_CLIENT', true);

if (!function_exists('bzj_rel_sync_config')) {
function bzj_rel_sync_config($platform) {
    $platform = strtolower((string)$platform);
    $secret_const = $platform === 'streams' ? 'BZJ_STREAMS_SYNC_SECRET' : 'BZJ_SOCIALS_SYNC_SECRET';
    $secret = defined($secret_const) ? constant($secret_const) : '';
    if (!$secret) $secret = getenv($secret_const) ?: '';
    if (!$secret && defined('BUZZ_SSO_SECRET')) $secret = BUZZ_SSO_SECRET;
    if (!$secret) $secret = getenv('BUZZ_SSO_SECRET') ?: '';
    return [
        'platform' => $platform,
        'secret' => $secret,
        'endpoint' => 'https://buzzjuice.net/wp-json/bzj/v6/connection-management',
        'outbox' => rtrim(dirname(__DIR__), '/').'/data/logs/bzj-'.$platform.'-sync-outbox.jsonl',
        'log' => rtrim(dirname(__DIR__), '/').'/data/logs/bzj-'.$platform.'-sync.log',
    ];
}
function bzj_rel_sync_log($platform,$message,$context=[]) {
    $c=bzj_rel_sync_config($platform);$dir=dirname($c['log']);
    if(!is_dir($dir))@mkdir($dir,0755,true);
    @file_put_contents($c['log'],json_encode(['time'=>gmdate('c'),'message'=>$message,'context'=>$context],JSON_UNESCAPED_SLASHES).PHP_EOL,FILE_APPEND|LOCK_EX);
}
function bzj_rel_sync_uuid() {
    $d=function_exists('random_bytes')?random_bytes(16):md5(uniqid('',true),true);
    $d[6]=chr((ord($d[6])&15)|64);$d[8]=chr((ord($d[8])&63)|128);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s',str_split(bin2hex($d),4));
}
function bzj_rel_sync_http($config,$payload) {
    $raw=json_encode($payload,JSON_UNESCAPED_SLASHES);
    $ts=(string)time();
    $sig=hash_hmac('sha256',$ts.'.'.$raw,$config['secret']);
    $headers=[
        'Content-Type: application/json',
        'X-BZJ-Timestamp: '.$ts,
        'X-BZJ-Signature: '.$sig,
        'X-BZJ-Platform: '.$config['platform'],
    ];
    if(function_exists('curl_init')){
        $ch=curl_init($config['endpoint']);
        curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$raw,CURLOPT_HTTPHEADER=>$headers,CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>15,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2]);
        $body=curl_exec($ch);$err=curl_error($ch);$code=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
        if($body===false)return ['ok'=>false,'code'=>$code,'error'=>$err];
        return ['ok'=>$code>=200&&$code<300,'code'=>$code,'body'=>$body];
    }
    $ctx=stream_context_create(['http'=>['method'=>'POST','header'=>implode("\r\n",$headers)."\r\n",'content'=>$raw,'timeout'=>15,'ignore_errors'=>true]]);
    $body=@file_get_contents($config['endpoint'],false,$ctx);$code=0;
    if(isset($http_response_header[0])&&preg_match('/\s(\d{3})\s/',$http_response_header[0],$m))$code=(int)$m[1];
    return ['ok'=>$code>=200&&$code<300,'code'=>$code,'body'=>$body===false?'':$body,'error'=>$body===false?'HTTP request failed':''];
}
function bzj_rel_sync_append_outbox($platform,$payload) {
    $c=bzj_rel_sync_config($platform);$dir=dirname($c['outbox']);if(!is_dir($dir))@mkdir($dir,0755,true);
    @file_put_contents($c['outbox'],json_encode(['queued_at'=>gmdate('c'),'payload'=>$payload],JSON_UNESCAPED_SLASHES).PHP_EOL,FILE_APPEND|LOCK_EX);
}
function bzj_rel_sync_send($platform,$operation,$actor_id,$target_id,$extra=[]) {
    $c=bzj_rel_sync_config($platform);
    if(!$c['secret']){bzj_rel_sync_log($platform,'Sync secret missing',['operation'=>$operation]);return false;}
    $payload=array_merge(['origin'=>$platform,'operation'=>$operation,'event_id'=>bzj_rel_sync_uuid(),'actor_id'=>(int)$actor_id,'target_id'=>(int)$target_id,'authenticated_id'=>(int)$actor_id],$extra);
    $r=bzj_rel_sync_http($c,$payload);
    bzj_rel_sync_log($platform,$r['ok']?'Sync sent':'Sync failed',['operation'=>$operation,'actor_id'=>$actor_id,'target_id'=>$target_id,'event_id'=>$payload['event_id'],'http_code'=>$r['code'],'error'=>$r['error']??'']);
    if(!$r['ok'])bzj_rel_sync_append_outbox($platform,$payload);
    return $r['ok'];
}
function bzj_rel_sync_retry_outbox($platform,$limit=25) {
    $c=bzj_rel_sync_config($platform);if(!is_file($c['outbox']))return 0;
    $lines=@file($c['outbox'],FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);if(!$lines)return 0;
    $keep=[];$sent=0;$n=0;
    foreach($lines as $line){
        $row=json_decode($line,true);if(!is_array($row)||empty($row['payload']))continue;
        if($n++ >= $limit){$keep[]=$line;continue;}
        $r=bzj_rel_sync_http($c,$row['payload']);
        if($r['ok']){$sent++;continue;}
        $keep[]=$line;
    }
    @file_put_contents($c['outbox'],$keep?implode(PHP_EOL,$keep).PHP_EOL:'',LOCK_EX);
    return $sent;
}
}
