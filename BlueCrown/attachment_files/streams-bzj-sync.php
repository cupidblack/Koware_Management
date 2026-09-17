<?php
/**
 * Buzzjuice Streams -> BuddyBoss signed synchronization helper.
 * Load this file from a Streams bootstrap/functions file.
 */
if (!function_exists('bzj_streams_sync_command')) {
    function bzj_streams_sync_command($operation, $actor_id, $target_id, $event_id = '') {
        $operation = preg_replace('/[^a-z_]/', '', strtolower((string)$operation));
        $actor_id = (int)$actor_id;
        $target_id = (int)$target_id;
        if (!$operation || $actor_id < 1 || $target_id < 1 || $actor_id === $target_id) {
            return array('success'=>false,'error'=>'Invalid synchronization command.');
        }
        $secret = getenv('BUZZ_SSO_SECRET');
        if (!$secret && defined('BUZZ_SSO_SECRET')) $secret = BUZZ_SSO_SECRET;
        if (!$secret) {
            error_log('[BZJ Streams Sync] BUZZ_SSO_SECRET is missing.');
            return array('success'=>false,'error'=>'Synchronization secret unavailable.');
        }
        if (!$event_id) {
            $event_id = function_exists('uuid_create')
                ? uuid_create(UUID_TYPE_RANDOM)
                : sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x%04x',
                    mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff),
                    mt_rand(0,0x0fff)|0x4000,mt_rand(0,0x3fff)|0x8000,
                    mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff));
        }
        $payload=array(
            'origin'=>'streams','event_id'=>$event_id,'operation'=>$operation,
            'actor_id'=>$actor_id,'target_id'=>$target_id,'authenticated_id'=>$actor_id
        );
        $raw=json_encode($payload,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $timestamp=time();
        $signature=hash_hmac('sha256',$timestamp.'.'.$raw,$secret);
        $ch=curl_init('https://buzzjuice.net/wp-json/bzj/v6/connection-management');
        curl_setopt_array($ch,array(
            CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$raw,CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>15,
            CURLOPT_HTTPHEADER=>array(
                'Content-Type: application/json','Content-Length: '.strlen($raw),
                'X-BZJ-Timestamp: '.$timestamp,'X-BZJ-Signature: '.$signature
            )
        ));
        $body=curl_exec($ch); $error=curl_error($ch); $code=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
        if ($body===false || $error) {
            error_log('[BZJ Streams Sync] HTTP failure: '.$error);
            return array('success'=>false,'error'=>'WordPress synchronization request failed.','event_id'=>$event_id);
        }
        $response=json_decode($body,true);
        if ($code<200 || $code>=300 || !is_array($response)) {
            error_log('[BZJ Streams Sync] Invalid response: '.$body);
            return array('success'=>false,'error'=>'WordPress returned an invalid response.','event_id'=>$event_id,'http_code'=>$code);
        }
        return $response;
    }
}
