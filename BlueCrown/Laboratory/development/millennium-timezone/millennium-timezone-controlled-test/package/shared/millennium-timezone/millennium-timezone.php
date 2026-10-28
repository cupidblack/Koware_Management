<?php
declare(strict_types=1);
/**
 * Millennium Timezone central engine.
 * Retains the original Palmier historical-date formula. Include-safe: rendering
 * occurs only when this file is the direct web entry point.
 */
date_default_timezone_set('UTC');
require_once dirname(__DIR__) . '/db_helpers.php';

if (!defined('MTZ_CACHE_TTL')) define('MTZ_CACHE_TTL', 12345);
if (!defined('MTZ_LOG_FILE')) define('MTZ_LOG_FILE', __DIR__ . '/millennium-timezone.log');
if (!defined('MTZ_TABLE_PREFIX')) define('MTZ_TABLE_PREFIX', 'millennium_timezone_');

if (!function_exists('mtz_log')) {
    function mtz_log(string $message, array $context = []): void {
        $line = gmdate('c') . ' [MTZ] ' . $message;
        if ($context) $line .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $dir = dirname(MTZ_LOG_FILE);
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        @file_put_contents(MTZ_LOG_FILE, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
if (!function_exists('mtz_ordinal')) {
    function mtz_ordinal(int $n): string {
        if ($n % 100 >= 11 && $n % 100 <= 13) return $n . 'th';
        return $n . ([1 => 'st', 2 => 'nd', 3 => 'rd'][$n % 10] ?? 'th');
    }
}
if (!function_exists('mtz_jubilee')) {
    function mtz_jubilee(int $year): string {
        $raw = (($year + 3760) - 2503) / 50;
        $whole = (int) floor($raw);
        $fraction = $raw - $whole;
        $yy = (int) floor(($fraction * 100) / 2);
        return $whole . 'Y' . str_pad((string)$yy, 2, '0', STR_PAD_LEFT);
    }
}
if (!function_exists('mtz_build_calendar_result')) {
    function mtz_build_calendar_result(?DateTimeImmutable $now = null): array {
        $hourMap = [0 => 0, 1 => 14, 2 => 3, 3 => 17, 4 => 6, 5 => 20, 6 => 10];
        $daysShort = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $daysFull = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        $utc = new DateTimeZone('UTC');
        $now = ($now ?? new DateTimeImmutable('now', $utc))->setTimezone($utc);
        $epoch = new DateTimeImmutable('1899-12-30 00:00:00', $utc);
        $diffDays = ($now->getTimestamp() - $epoch->getTimestamp()) / 86400;
        $j = (((($diffDays / 365.262541748) + 1900) / 7) - 2 - 184) * 365.262541748;
        $hist = $epoch->modify(((int) round($j)) . ' days');
        $weekday = (int)$now->format('w');
        $base = $hourMap[$weekday] ?? 0;
        $secToday = ((int)$now->format('H') * 3600) + ((int)$now->format('i') * 60) + (int)$now->format('s');
        $histSec = ($secToday / 86400) * 14400;
        $h = (int)floor($histSec / 3600);
        $m = (int)floor(fmod($histSec, 3600) / 60);
        $s = (int)floor(fmod($histSec, 60));
        $hist = $hist->setTime(($base + $h) % 24, $m, $s);
        $historicalWeekday = (int)$hist->format('w');
        return [
            'status' => 'ok', 'timezone' => 'UTC',
            'generated_at' => gmdate('Y-m-d\\TH:i:s\\Z'), 'timestamp' => time(),
            'day' => $daysShort[$historicalWeekday] . $daysFull[(int)$now->format('w')],
            'date' => mtz_ordinal((int)$hist->format('j')),
            'month' => $months[(int)$hist->format('n') - 1],
            'jubilee' => mtz_jubilee((int)$now->format('Y')),
            'reference_year' => (int)$hist->format('Y'),
            'local' => $hist->format('H:i:s'),
            'current' => $now->format('Y-m-d\\TH:i:s\\Z'),
            'historical_iso' => $hist->format('Y-m-d\\TH:i:s\\Z'),
            'historical_date' => $hist->format('Y-m-d'),
            'historical_timestamp' => $hist->getTimestamp(),
        ];
    }
}
if (!function_exists('mtz_read_last_log_entry')) {
    function mtz_read_last_log_entry(): ?array {
        if (!is_file(MTZ_LOG_FILE) || !is_readable(MTZ_LOG_FILE)) return null;
        $lines = @file(MTZ_LOG_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) return null;
        for ($i = count($lines) - 1; $i >= 0; $i--) {
            // Log rows are JSON objects; diagnostic rows are intentionally ignored.
            $row = json_decode($lines[$i], true);
            if (is_array($row) && isset($row['generated_at'], $row['historical_iso'])) return $row;
        }
        return null;
    }
}
if (!function_exists('mtz_write_log_entry')) {
    function mtz_write_log_entry(array $payload): void {
        $dir = dirname(MTZ_LOG_FILE);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            mtz_log('LOG_DIRECTORY_UNAVAILABLE', ['directory' => $dir]); return;
        }
        @file_put_contents(MTZ_LOG_FILE, json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
if (!function_exists('mtz_db_connection')) {
    function mtz_db_connection() {
        if (!function_exists('get_iapd_db_conn')) return false;
        $db = get_iapd_db_conn();
        return ($db instanceof mysqli && !$db->connect_errno) ? $db : false;
    }
}
if (!function_exists('mtz_db_read_snapshot')) {
    function mtz_db_read_snapshot(): ?array {
        $db = mtz_db_connection(); if (!$db) return null;
        $table = '`koware_iapd`.`millennium_timezone_snapshot`';
        $result = @$db->query("SELECT payload_json, updated_at FROM {$table} WHERE id=1 LIMIT 1");
        if (!$result) return null;
        $row = $result->fetch_assoc(); $result->free();
        if (!$row || empty($row['payload_json'])) return null;
        $data = json_decode($row['payload_json'], true);
        return is_array($data) ? $data : null;
    }
}
if (!function_exists('mtz_db_write_snapshot')) {
    function mtz_db_write_snapshot(array $payload): void {
        $db = mtz_db_connection(); if (!$db) { mtz_log('DB_UNAVAILABLE_SNAPSHOT_NOT_SAVED'); return; }
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) { mtz_log('SNAPSHOT_JSON_ENCODE_FAILED'); return; }
        $table = '`koware_iapd`.`millennium_timezone_snapshot`';
        $stmt = @$db->prepare("INSERT INTO {$table} (id, payload_json, updated_at) VALUES (1, ?, UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE payload_json=VALUES(payload_json), updated_at=UTC_TIMESTAMP()");
        if (!$stmt) { mtz_log('DB_SNAPSHOT_PREPARE_FAILED', ['error' => $db->error]); return; }
        $stmt->bind_param('s', $json);
        if (!$stmt->execute()) mtz_log('DB_SNAPSHOT_WRITE_FAILED', ['error' => $stmt->error]);
        $stmt->close();
    }
}
if (!function_exists('mtz_get_timezone_payload')) {
    function mtz_get_timezone_payload(bool $forceRefresh = false): array {
        $last = mtz_read_last_log_entry();
        if (!$last) $last = mtz_db_read_snapshot();
        $age = isset($last['timestamp']) ? max(0, time() - (int)$last['timestamp']) : PHP_INT_MAX;
        if (!$forceRefresh && is_array($last) && isset($last['historical_iso']) && $age < MTZ_CACHE_TTL) {
            if (!isset($last['probe'])) { require_once __DIR__ . '/millennium-probe.php'; $last['probe'] = mtz_get_probe_result(); $last['probe_value'] = $last['probe']['probe_iso'] ?? null; }
            $last['cache'] = ['stale' => false, 'cache_age_seconds' => $age, 'ttl_seconds' => MTZ_CACHE_TTL];
            return $last;
        }
        $lockPath = __DIR__ . '/millennium-timezone-refresh.lock';
        $lock = @fopen($lockPath, 'c');
        if ($lock && !flock($lock, LOCK_EX)) { fclose($lock); $lock = null; }
        try {
            // Another request may have refreshed while this request waited for the lock.
            $latest = mtz_read_last_log_entry();
            if (!$forceRefresh && $latest && isset($latest['timestamp']) && time() - (int)$latest['timestamp'] < MTZ_CACHE_TTL) {
                $latest['cache'] = ['stale' => false, 'cache_age_seconds' => time() - (int)$latest['timestamp'], 'ttl_seconds' => MTZ_CACHE_TTL];
                return $latest;
            }
            $payload = mtz_build_calendar_result();
            require_once __DIR__ . '/millennium-probe.php';
            $payload['probe'] = mtz_get_probe_result();
            $payload['probe_value'] = $payload['probe']['probe_iso'] ?? null;
            $payload['cache'] = ['stale' => false, 'cache_age_seconds' => 0, 'ttl_seconds' => MTZ_CACHE_TTL];
            mtz_write_log_entry($payload);
            mtz_db_write_snapshot($payload);
            return $payload;
        } catch (Throwable $e) {
            mtz_log('REFRESH_FAILED', ['error' => $e->getMessage()]);
            if (is_array($last) && isset($last['historical_iso'])) {
                $last['cache'] = ['stale' => true, 'cache_age_seconds' => $age, 'ttl_seconds' => MTZ_CACHE_TTL, 'refresh_error' => true];
                return $last;
            }
            throw $e;
        } finally {
            if (is_resource($lock)) { flock($lock, LOCK_UN); fclose($lock); }
        }
    }
}
if (!function_exists('mtz_json_response')) {
    function mtz_json_response(array $payload, int $status = 200): void {
        http_response_code($status); header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: public, max-age=60, stale-while-revalidate=300');
        echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); exit;
    }
}

// Only execute HTTP behavior when directly requested, never when included by another PHP file.
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath((string)$_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    try {
        $force = isset($_GET['force']) && $_GET['force'] === '1';
        $payload = mtz_get_timezone_payload($force);
        $endpoint = (string)($_GET['endpoint'] ?? 'timezone');
        if ((isset($_GET['format']) && strtolower((string)$_GET['format']) === 'json') || isset($_GET['endpoint'])) {
            if ($endpoint === 'historical') mtz_json_response(['iso' => $payload['historical_iso'], 'historical_iso' => $payload['historical_iso'], 'reference_year' => $payload['reference_year']]);
            if ($endpoint === 'current') mtz_json_response(['iso' => $payload['current'], 'current' => $payload['current']]);
            if ($endpoint === 'probe') {
                require_once __DIR__ . '/millennium-probe.php';
                mtz_json_response(mtz_get_probe_result());
            }
            mtz_json_response($payload);
        }
        $esc = static fn($v) => htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        ?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Millennium Timezone</title><style>body{font:16px Arial;background:#0f172a;color:#f8fafc;margin:0;padding:24px}.wrap{max-width:1000px;margin:auto}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(135px,1fr));gap:12px}.item{padding:16px;background:#1e293b;border-radius:10px}.value{font-size:1.4rem;font-weight:bold;overflow-wrap:anywhere}.label{font-size:.75rem;opacity:.7;text-transform:uppercase;margin-top:8px}</style></head><body><main class="wrap"><h1>Millennium Timezone</h1><div class="grid"><?php foreach (['day'=>'Day','date'=>'Date','month'=>'Month','jubilee'=>'Jubilee','reference_year'=>'Reference Year','local'=>'Local','current'=>'Current','historical_iso'=>'Historical ISO','probe_value'=>'Probe'] as $key=>$label): ?><div class="item"><div class="value"><?=$esc($payload[$key] ?? '')?></div><div class="label"><?=$esc($label)?></div></div><?php endforeach; ?></div><p>Snapshot generated: <?=$esc($payload['generated_at'])?> · Cache age: <?=$esc($payload['cache']['cache_age_seconds'] ?? 0)?> seconds</p><p><a href="?format=json">JSON</a> · <a href="?endpoint=historical">Historical date JSON</a> · <a href="?endpoint=current">Current time JSON</a></p></main></body></html><?php
    } catch (Throwable $e) { mtz_log('HTTP_REQUEST_FAILED', ['error' => $e->getMessage()]); http_response_code(500); header('Content-Type: application/json; charset=UTF-8'); echo json_encode(['status'=>'error','message'=>'Millennium Timezone could not produce a response.']); }
}
