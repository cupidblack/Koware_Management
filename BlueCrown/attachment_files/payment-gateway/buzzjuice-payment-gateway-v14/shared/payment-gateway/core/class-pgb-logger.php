<?php
if (!defined('ABSPATH')) {
    exit;
}

class PGB_Logger {
    private $enabled;
    private $level;
    private $log_dir;

    private static $levels = array(
        'debug' => 10,
        'info' => 20,
        'warning' => 30,
        'error' => 40,
        'critical' => 50,
        'off' => 1000,
    );

    public function __construct() {
        $this->enabled = (bool) get_option('bzj_pgb_logging_enabled', true);
        $this->level = sanitize_key(get_option('bzj_pgb_log_level', 'info'));
        if (!isset(self::$levels[$this->level])) {
            $this->level = 'info';
        }

        $this->log_dir = dirname(__DIR__) . '/log/';
        if (!is_dir($this->log_dir)) {
            wp_mkdir_p($this->log_dir);
        }
    }

    public function log($level, $message, $context = array(), $source = null) {
        if (!$this->enabled || !isset(self::$levels[$level])) {
            return;
        }

        if (self::$levels[$level] < self::$levels[$this->level]) {
            return;
        }

        $source = $source ?: __FILE__;
        $file = basename($source, '.php') . '.log';
        $path = $this->log_dir . sanitize_file_name($file);

        $safe_context = $this->redact($context);
        $line = '[' . gmdate('Y-m-d H:i:s') . '] [' . strtoupper($level) . '] ' .
            $message . (empty($safe_context) ? '' : ' ' . wp_json_encode($safe_context)) . PHP_EOL;

        @file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
    }

    public function debug($message, $context = array(), $source = null) {
        $this->log('debug', $message, $context, $source);
    }

    public function info($message, $context = array(), $source = null) {
        $this->log('info', $message, $context, $source);
    }

    public function warning($message, $context = array(), $source = null) {
        $this->log('warning', $message, $context, $source);
    }

    public function error($message, $context = array(), $source = null) {
        $this->log('error', $message, $context, $source);
    }

    public function critical($message, $context = array(), $source = null) {
        $this->log('critical', $message, $context, $source);
    }

    private function redact($value) {
        if (is_array($value)) {
            $out = array();
            foreach ($value as $key => $item) {
                $key_l = strtolower((string) $key);
                if (preg_match('/secret|password|token|signature|consumer_key|consumer_secret|api_key|api_secret/', $key_l)) {
                    $out[$key] = '[REDACTED]';
                } else {
                    $out[$key] = $this->redact($item);
                }
            }
            return $out;
        }

        if (is_object($value)) {
            return $this->redact((array) $value);
        }

        return $value;
    }
}
