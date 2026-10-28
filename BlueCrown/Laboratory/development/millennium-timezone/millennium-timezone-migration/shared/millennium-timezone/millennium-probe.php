<?php
declare(strict_types=1);
/**
 * Millennium Probe local service.
 * Safe to include: HTML is emitted only when this file is requested directly.
 */
if (!defined('MILLENNIUM_PROBE_TTL_SECONDS')) define('MILLENNIUM_PROBE_TTL_SECONDS', 12345);
if (!defined('MILLENNIUM_PROBE_LOG_FILE')) define('MILLENNIUM_PROBE_LOG_FILE', __DIR__ . '/logs/millennium-probe.log');
if (!defined('MILLENNIUM_PROBE_MAX_LOG_LINES')) define('MILLENNIUM_PROBE_MAX_LOG_LINES', 180);
if (!defined('MILLENNIUM_PROBE_PHASES')) define('MILLENNIUM_PROBE_PHASES', 5);
if (!defined('MILLENNIUM_PROBE_BASE_DAYS')) define('MILLENNIUM_PROBE_BASE_DAYS', 74144.0);

if (!function_exists('millennium_probe_phase_bounds')) {
    function millennium_probe_phase_bounds(int $year): array {
        $ranges = [
            ['01-10', '03-15', 'Jan 10 – Mar 15'],
            ['03-16', '05-25', 'Mar 16 – May 25'],
            ['05-26', '08-05', 'May 26 – Aug 5'],
            ['08-15', '10-15', 'Aug 15 – Oct 15'],
            ['10-16', '12-20', 'Oct 16 – Dec 20'],
        ];
        $out = [];
        foreach ($ranges as [$start, $end, $label]) {
            $a = strtotime(sprintf('%04d-%s 00:00:00 UTC', $year, $start));
            $b = strtotime(sprintf('%04d-%s 23:59:59 UTC', $year, $end));
            $out[] = ['start' => $a, 'end' => $b, 'label' => $label];
        }
        return $out;
    }
}
if (!function_exists('millennium_probe_find_phase')) {
    function millennium_probe_find_phase(?int $timestamp = null): array {
        $now = $timestamp ?? time();
        $year = (int)gmdate('Y', $now);
        foreach (millennium_probe_phase_bounds($year) as $i => $p) {
            if ($now >= $p['start'] && $now <= $p['end']) return ['index'=>$i, 'year'=>$year] + $p;
        }
        $ranges = millennium_probe_phase_bounds($year);
        if ($now < $ranges[0]['start']) {
            $priorYear = $year - 1;
            $prior = millennium_probe_phase_bounds($priorYear);
            $p = $prior[4];
            return ['index'=>4, 'year'=>$priorYear] + $p;
        }
        $next = millennium_probe_phase_bounds($year + 1);
        return ['index'=>0, 'year'=>$year + 1] + $next[0];
    }
}
if (!function_exists('millennium_probe_read_log')) {
    function millennium_probe_read_log(): array {
        if (!is_file(MILLENNIUM_PROBE_LOG_FILE)) return [];
        $raw = @file_get_contents(MILLENNIUM_PROBE_LOG_FILE);
        if (!is_string($raw) || $raw === '') return [];
        $rows = json_decode($raw, true);
        return is_array($rows) ? $rows : [];
    }
}
if (!function_exists('millennium_probe_write_log')) {
    function millennium_probe_write_log(array $rows): void {
        $dir = dirname(MILLENNIUM_PROBE_LOG_FILE);
        if (!is_dir($dir) && !@mkdir($dir, 0750, true) && !is_dir($dir)) return;
        $rows = array_slice(array_values($rows), -MILLENNIUM_PROBE_MAX_LOG_LINES);
        @file_put_contents(MILLENNIUM_PROBE_LOG_FILE, json_encode($rows, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) . PHP_EOL, LOCK_EX);
    }
}
if (!function_exists('millennium_probe_now')) {
    /** Returns stable probe state; no same-domain HTTP request is made. */
    function millennium_probe_now(?int $timestamp = null, bool $forceRefresh = false): array {
        $now = $timestamp ?? time();
        $phase = millennium_probe_find_phase($now);
        $rows = millennium_probe_read_log();
        $last = $rows ? end($rows) : null;
        if (!$forceRefresh && is_array($last) && isset($last['generated_at_ts'], $last['probe_iso'])
            && ($now - (int)$last['generated_at_ts']) >= 0
            && ($now - (int)$last['generated_at_ts']) < MILLENNIUM_PROBE_TTL_SECONDS
            && (int)($last['phase_index'] ?? -1) === (int)$phase['index']) {
            return $last;
        }
        $start = (int)$phase['start'];
        $end = (int)$phase['end'];
        $span = max(1, $end - $start + 1);
        $progress = max(0.0, min(1.0, ($now - $start) / $span));
        // Preserve the probe's five-phase, BASE_DAYS-based progression model.
        $phaseOffset = ((int)$phase['index']) * (MILLENNIUM_PROBE_BASE_DAYS / MILLENNIUM_PROBE_PHASES);
        $probeDays = MILLENNIUM_PROBE_BASE_DAYS + $phaseOffset + ($progress * (MILLENNIUM_PROBE_BASE_DAYS / MILLENNIUM_PROBE_PHASES));
        $probeTs = $now;
        $result = [
            'probe_iso' => gmdate('Y-m-d\\TH:i:s\\Z', $probeTs),
            'probe_value_days' => $probeDays,
            'phase_index' => (int)$phase['index'],
            'phase_label' => (string)$phase['label'],
            'now_iso' => gmdate('Y-m-d\\TH:i:s\\Z', $now),
            'live_probe_timestamp' => $probeTs,
            'generated_at_ts' => $now,
        ];
        $rows[] = $result;
        millennium_probe_write_log($rows);
        return $result;
    }
}

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath((string)$_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    if (isset($_GET['probe']) && $_GET['probe'] === 'now') {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, max-age=0');
        echo json_encode(millennium_probe_now(), JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        exit;
    }
    $probe = millennium_probe_now();
    ?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Millennium Probe</title><style>body{margin:0;background:#131b29;color:#f7fafc;font:16px system-ui,sans-serif}.wrap{max-width:850px;margin:4rem auto;padding:2rem;background:#1b263b;border-radius:14px}dt{color:#7be7ff;margin-top:1rem}dd{margin:.25rem 0;font-family:ui-monospace,monospace;overflow-wrap:anywhere}</style></head><body><main class="wrap"><h1>Millennium Probe</h1><dl><?php foreach ($probe as $key=>$value): ?><dt><?=htmlspecialchars((string)$key, ENT_QUOTES, 'UTF-8')?></dt><dd><?=htmlspecialchars(is_scalar($value)?(string)$value:json_encode($value), ENT_QUOTES, 'UTF-8')?></dd><?php endforeach; ?></dl></main></body></html><?php
}
