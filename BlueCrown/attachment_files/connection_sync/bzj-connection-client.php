<?php
/**
 * Buzzjuice external relationship synchronization client.
 *
 * File:
 *   /shared/bzj-connection-client.php
 *
 * Loaded by Streams and Socials only. It never calls WordPress functions.
 * It sends platform-native IDs to the WordPress control plane.
 */

if (!class_exists('BZJ_Connection_Client')) {

final class BZJ_Connection_Client {

    private $origin;
    private $base_url;
    private $secret;
    private $timeout = 15;
    private $log_file;

    public function __construct($origin) {
        $origin = strtolower(trim((string)$origin));
        if (!in_array($origin, array('streams','socials'), true)) {
            throw new InvalidArgumentException('Invalid Buzzjuice synchronization origin.');
        }

        $this->origin = $origin;
        $this->base_url = rtrim((string)getenv('WP_BASE_SITE_URL'), '/');
        if ($this->base_url === '') {
            $this->base_url = 'https://buzzjuice.net';
        }

        $this->secret = $origin === 'streams'
            ? (getenv('BZJ_STREAMS_SYNC_SECRET') ?: getenv('BUZZ_SSO_SECRET'))
            : (getenv('BZJ_SOCIALS_SYNC_SECRET') ?: getenv('BUZZ_SSO_SECRET'));

        if ($this->secret === '') {
            throw new RuntimeException('Buzzjuice synchronization secret is not configured.');
        }

        $this->log_file = dirname(__DIR__) . '/data/logs/bzj-' . $origin . '-sync.log';
        $this->ensure_log_directory();
    }

    public function send($operation, $actor_external_id, $target_external_id, $event_uuid = null, array $extra = array()) {
        $operation = preg_replace('/[^a-z0-9_]/i', '', (string)$operation);
        $actor_external_id = (int)$actor_external_id;
        $target_external_id = (int)$target_external_id;

        if ($actor_external_id < 1 || $target_external_id < 1 || $actor_external_id === $target_external_id) {
            throw new InvalidArgumentException('Invalid synchronization IDs.');
        }

        $event_uuid = $event_uuid ?: $this->uuid();
        if (!$this->is_uuid($event_uuid)) {
            throw new InvalidArgumentException('Invalid event UUID.');
        }

        $payload = array(
            'origin' => $this->origin,
            'operation' => $operation,
            'actor_external_id' => $actor_external_id,
            'target_external_id' => $target_external_id,
            'event_uuid' => $event_uuid,
        );

        foreach ($extra as $key => $value) {
            if (in_array($key, array('origin','operation','actor_external_id','target_external_id','event_uuid'), true)) {
                continue;
            }
            $payload[$key] = $value;
        }

        $raw = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($raw === false) {
            throw new RuntimeException('Unable to encode synchronization payload.');
        }

        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp . '.' . $raw, $this->secret);
        $url = $this->base_url . '/wp-json/bzj/v6/connection-management';

        $response = $this->http_post($url, $raw, $timestamp, $signature);

        if ($response['ok']) {
            $this->log('Synchronization accepted', array(
                'origin'=>$this->origin,
                'operation'=>$operation,
                'actor_external_id'=>$actor_external_id,
                'target_external_id'=>$target_external_id,
                'event_uuid'=>$event_uuid,
                'http_status'=>$response['status'],
            ));
            return array(
                'success' => true,
                'event_uuid' => $event_uuid,
                'status' => $response['status'],
                'body' => $response['body'],
            );
        }

        $this->queue($payload, $raw, $timestamp, $signature, $response['error']);
        $this->log('Synchronization queued for retry', array(
            'origin'=>$this->origin,
            'operation'=>$operation,
            'actor_external_id'=>$actor_external_id,
            'target_external_id'=>$target_external_id,
            'event_uuid'=>$event_uuid,
            'http_status'=>$response['status'],
            'error'=>$response['error'],
        ));

        return array(
            'success' => false,
            'queued' => true,
            'event_uuid' => $event_uuid,
            'error' => $response['error'],
        );
    }

    public function flush($max = 25) {
        $dir = $this->outbox_directory();
        if (!is_dir($dir)) return 0;

        $files = glob($dir . '/*.json');
        if (!$files) return 0;

        sort($files, SORT_STRING);
        $processed = 0;

        foreach ($files as $file) {
            if ($processed >= (int)$max) break;

            $raw_job = @file_get_contents($file);
            $job = $raw_job ? json_decode($raw_job, true) : null;
            if (!is_array($job) || empty($job['payload']) || empty($job['event_uuid'])) {
                @unlink($file);
                continue;
            }

            if (isset($job['next_attempt_at']) && (int)$job['next_attempt_at'] > time()) {
                continue;
            }

            $timestamp = isset($job['timestamp']) ? (int)$job['timestamp'] : time();
            $raw = isset($job['raw']) ? $job['raw'] : json_encode($job['payload']);
            $signature = isset($job['signature']) ? $job['signature'] : hash_hmac('sha256', $timestamp . '.' . $raw, $this->secret);

            $response = $this->http_post(
                $this->base_url . '/wp-json/bzj/v6/connection-management',
                $raw,
                $timestamp,
                $signature
            );

            if ($response['ok']) {
                @unlink($file);
                $processed++;
                $this->log('Outbox event processed', array(
                    'origin'=>$this->origin,
                    'event_uuid'=>$job['event_uuid'],
                    'operation'=>isset($job['payload']['operation']) ? $job['payload']['operation'] : '',
                    'http_status'=>$response['status'],
                ));
                continue;
            }

            $attempts = isset($job['attempts']) ? ((int)$job['attempts'] + 1) : 1;
            $job['attempts'] = $attempts;
            $job['last_error'] = $response['error'];
            $job['next_attempt_at'] = time() + min(3600, 60 * (2 ** max(0, $attempts - 1)));
            @file_put_contents($file, json_encode($job, JSON_UNESCAPED_SLASHES), LOCK_EX);
        }

        return $processed;
    }

    private function http_post($url, $raw, $timestamp, $signature) {
        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'X-BZJ-Timestamp: ' . $timestamp,
            'X-BZJ-Signature: ' . $signature,
        );

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $raw,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ));
            $body = curl_exec($ch);
            $error = curl_error($ch);
            $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($body === false) {
                return array('ok'=>false,'status'=>$status,'body'=>'','error'=>$error ?: 'curl_failed');
            }
            $ok = $status >= 200 && $status < 300;
            return array('ok'=>$ok,'status'=>$status,'body'=>$body,'error'=>$ok ? '' : ('http_' . $status));
        }

        $context = stream_context_create(array(
            'http' => array(
                'method'=>'POST',
                'header'=>implode("\r\n",$headers),
                'content'=>$raw,
                'timeout'=>$this->timeout,
                'ignore_errors'=>true,
            ),
        ));
        $body = @file_get_contents($url, false, $context);
        $status = 0;
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
            $status = (int)$m[1];
        }
        $ok = $status >= 200 && $status < 300;
        return array('ok'=>$ok,'status'=>$status,'body'=>$body ?: '','error'=>$ok ? '' : 'http_request_failed');
    }

    private function queue(array $payload, $raw, $timestamp, $signature, $error) {
        $dir = $this->outbox_directory();
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            return false;
        }

        $file = $dir . '/' . $payload['event_uuid'] . '.json';
        if (file_exists($file)) return true;

        $job = array(
            'event_uuid'=>$payload['event_uuid'],
            'payload'=>$payload,
            'raw'=>$raw,
            'timestamp'=>$timestamp,
            'signature'=>$signature,
            'attempts'=>0,
            'next_attempt_at'=>time()+60,
            'last_error'=>$error,
            'created_at'=>time(),
        );
        return @file_put_contents($file, json_encode($job, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
    }

    private function outbox_directory() {
        return dirname(__DIR__) . '/data/logs/bzj-sync-outbox/' . $this->origin;
    }

    private function ensure_log_directory() {
        $dir = dirname($this->log_file);
        if (!is_dir($dir)) @mkdir($dir, 0750, true);
    }

    private function log($message, array $context = array()) {
        $dir = dirname($this->log_file);
        if (!is_dir($dir)) @mkdir($dir,0750,true);
        $entry = array('time'=>gmdate('c'),'message'=>$message,'context'=>$context);
        @file_put_contents($this->log_file, json_encode($entry,JSON_UNESCAPED_SLASHES).PHP_EOL,FILE_APPEND|LOCK_EX);
    }

    private function uuid() {
        $data = random_bytes(16);
        $hex = bin2hex($data);
        return substr($hex,0,8).'-'.substr($hex,8,4).'-4'.substr($hex,13,3).'-8'.substr($hex,17,3).'-'.substr($hex,20,12);
    }

    private function is_uuid($uuid) {
        return (bool)preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',(string)$uuid);
    }
}

}
