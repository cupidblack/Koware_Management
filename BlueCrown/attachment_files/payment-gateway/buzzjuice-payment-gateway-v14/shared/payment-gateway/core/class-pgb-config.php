<?php
if (!defined('ABSPATH')) {
    exit;
}

class PGB_Config {
    public static function defaults() {
        return array(
            'logging_enabled' => true,
            'log_level' => 'info',
            'wowonder_url' => '',
            'buzzsocial_url' => '',
            'handoff_path' => '/pgb-checkout/',
            'enable_subscriptions' => true,
            'enable_affiliate_wp' => true,
            'enable_jewel_affiliate' => true,
            'timestamp_tolerance' => 600,
            'processing_timeout' => 600,
            'retry_delay' => 60,
        );
    }

    public static function get($key, $default = null) {
        $defaults = self::defaults();
        if ($default === null && array_key_exists($key, $defaults)) {
            $default = $defaults[$key];
        }
        return get_option('bzj_pgb_' . $key, $default);
    }

    public static function secret() {
        $secret = getenv('BZJ_PGB_HANDOFF_SECRET');
        if (!$secret) {
            throw new RuntimeException('BZJ_PGB_HANDOFF_SECRET is not configured.');
        }
        return $secret;
    }

    public static function sign($request_id, $token, $timestamp) {
        $message = $request_id . "\n" . $token . "\n" . $timestamp;
        return hash_hmac('sha256', $message, self::secret());
    }

    public static function verify($request_id, $token, $timestamp, $signature) {
        if (!$request_id || !$token || !$timestamp || !$signature) {
            return false;
        }

        $timestamp = (int) $timestamp;
        if (abs(time() - $timestamp) > (int) self::get('timestamp_tolerance', 600)) {
            return false;
        }

        $expected = self::sign($request_id, $token, $timestamp);
        return hash_equals($expected, (string) $signature);
    }

    public static function handoff_url($request_id, $token, $timestamp, $signature) {
        $base = home_url(self::get('handoff_path', '/pgb-checkout/'));
        return add_query_arg(array(
            'request_id' => $request_id,
            'token' => $token,
            'ts' => $timestamp,
            'sig' => $signature,
        ), $base);
    }
}
