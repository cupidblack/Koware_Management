<?php
declare(strict_types=1);
/** Millennium Timezone endpoint. Keeps the original sabbatical-date calculation. */
if (!defined('MILLENNIUM_TIMEZONE_TTL_SECONDS')) define('MILLENNIUM_TIMEZONE_TTL_SECONDS', 12345);
if (!defined('MILLENNIUM_TIMEZONE_LOG_DIR')) define('MILLENNIUM_TIMEZONE_LOG_DIR', __DIR__ . '/logs');
if (!defined('MILLENNIUM_TIMEZONE_LOG_FILE')) define('MILLENNIUM_TIMEZONE_LOG_FILE', MILLENNIUM_TIMEZONE_LOG_DIR . '/millennium-timezone.log');
require_once __DIR__ . '/millennium-probe.php';

if (!function_exists('millennium_timezone_ordinal')) {
    function millennium_timezone_ordinal(int|string $n): string {
        $n = (int)$n;
        if ($n % 100 >= 11 && $n % 100 <= 13) return $n . 'th';
        return $n . ([1=>'st',2=>'nd',3=>'rd'][$n % 10] ?? 'th');
    }
}
if (!function_exists('millennium_timezone_jubilee')) {
    function millennium_timezone_jubilee(int|string $year): string {
        $hebrew = (int)$year + 3760;
        $raw = ($hebrew - 2503) / 50;
        $int = (int)floor($raw);
        $yy = (int)floor((($raw - $int) * 100) / 2);
        return $int . 'Y' . str_pad((string)$yy, 2, '0', STR_PAD_LEFT);
    }
}
if (!function_exists('millennium_timezone_calculate')) {
    function millennium_timezone_calculate(?DateTimeImmutable $now = null): array {
        $now = ($now ?? new DateTimeImmutable('now', new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('UTC'));
        $hourMap = [0=>0,1=>14,2=>3,3=>17,4=>6,5=>20,6=>10];
        $daysShort = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $daysFull = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        $epoch = new DateTimeImmutable('1899-12-30 00:00:00', new DateTimeZone('UTC'));
        $diffDays = ($now->getTimestamp() - $epoch->getTimestamp()) / 86400;
        // Original sabbatical-date.php equation, intentionally preserved.
        $j = (((($diffDays / 365.262541748) + 1900) / 7) - 2 - 184) * 365.262541748;
        $hist = $epoch->modify(sprintf('%+d days', (int)round($j)));
        $weekday = (int)$now->format('w');
        $base = $hourMap[$weekday] ?? 0;
        $secToday = ((int)$now->format('H')*3600) + ((int)$now->format('i')*60) + (int)$now->format('s');
        $histSec = ($secToday / 86400) * 14400;
        $h = (int)floor($histSec / 3600);
        $m = (int)floor(fmod($histSec, 3600) / 60);
        $s = (int)floor(fmod($histSec, 60));
        $hist = $hist->setTime(($base + $h) % 24, $m, $s);
        $probe = millennium_probe_now($now->getTimestamp());
        return [
            'day' => $daysShort[(int)$hist->format('w')] . $daysFull[$weekday],
            'date' => millennium_timezone_ordinal($hist->format('j')),
            'month' => $months[(int)$hist->format('n') - 1],
            'jubilee' => millennium_timezone_jubilee($now->format('Y')),
            'reference_year' => $hist->format('Y'),
            'local' => $hist->format('H:i:s'),
            'current' => $now->format(DateTimeInterface::ATOM),
            'historical_iso' => $hist->format(DateTimeInterface::ATOM),
            'probe' => $probe['probe_iso'] ?? null,
            'probe_value_days' => $probe['probe_value_days'] ?? null,
            'phase_index' => $probe['phase_index'] ?? null,
            'phase_label' => $probe['phase_label'] ?? null,
            'generated_at_ts' => $now->getTimestamp(),
        ];
    }
}
if (!function_exists('millennium_timezone_append_log')) {
    function millennium_timezone_append_log(array $state): void {
        $dir = MILLENNIUM_TIMEZONE_LOG_DIR;
        if (!is_dir($dir) && !@mkdir($dir, 0750, true) && !is_dir($dir)) return;
        @file_put_contents(MILLENNIUM_TIMEZONE_LOG_FILE, json_encode($state, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND|LOCK_EX);
    }
}
if (!function_exists('millennium_timezone_read_cached_state')) {
    function millennium_timezone_read_cached_state(): ?array {
        $file = MILLENNIUM_TIMEZONE_LOG_DIR . '/millennium-timezone-state.json';
        if (!is_file($file)) return null;
        $raw = @file_get_contents($file);
        $data = is_string($raw) ? json_decode($raw, true) : null;
        return is_array($data) ? $data : null;
    }
}
if (!function_exists('millennium_timezone_write_cached_state')) {
    function millennium_timezone_write_cached_state(array $state): void {
        $dir = MILLENNIUM_TIMEZONE_LOG_DIR;
        if (!is_dir($dir) && !@mkdir($dir, 0750, true) && !is_dir($dir)) return;
        @file_put_contents($dir . '/millennium-timezone-state.json', json_encode($state, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX);
    }
}
if (!function_exists('millennium_timezone_get_latest')) {
    function millennium_timezone_get_latest(bool $forceRefresh = false): array {
        $cached = millennium_timezone_read_cached_state();
        $now = time();
        if (!$forceRefresh && is_array($cached) && isset($cached['generated_at_ts'])
            && ($now - (int)$cached['generated_at_ts']) >= 0
            && ($now - (int)$cached['generated_at_ts']) < MILLENNIUM_TIMEZONE_TTL_SECONDS) return $cached;
        $state = millennium_timezone_calculate(new DateTimeImmutable('now', new DateTimeZone('UTC')));
        millennium_timezone_append_log($state);
        millennium_timezone_write_cached_state($state);
        return $state;
    }
}

// JSON API: ?format=json or ?endpoint=historical|current|probe
if (isset($_GET['format']) || isset($_GET['endpoint'])) {
    $format = (string)($_GET['format'] ?? $_GET['endpoint'] ?? 'json');
    $state = millennium_timezone_get_latest(isset($_GET['refresh']) && $_GET['refresh'] === '1');
    if ($format === 'historical') $payload = ['iso'=>$state['historical_iso'] ?? null];
    elseif ($format === 'current') $payload = ['iso'=>$state['current'] ?? null];
    elseif ($format === 'probe') $payload = ['probe'=>$state['probe'] ?? null, 'probe_value_days'=>$state['probe_value_days'] ?? null, 'phase_label'=>$state['phase_label'] ?? null];
    else $payload = $state;
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, max-age=0');
    echo json_encode($payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}
$state = millennium_timezone_get_latest();
$fields = ['day'=>'Day','date'=>'Date','month'=>'Month','jubilee'=>'Jubilee','reference_year'=>'Reference Year','local'=>'Local','current'=>'Current','probe'=>'Probe'];
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Millennium Timezone</title><style>body{margin:0;background:#0f172a;color:#fff;font:16px Arial,sans-serif;text-align:center}.wrap{max-width:1000px;margin:auto;padding:24px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px}.item{padding:16px;background:#17233b;border-radius:10px;overflow-wrap:anywhere}.value{font-size:1.35rem;font-weight:700}.label{font-size:.75rem;opacity:.7;margin-top:8px}.machine{font-family:ui-monospace,monospace;font-size:.95rem}</style></head><body><main class="wrap"><h1>Millennium Timezone</h1><section class="grid"><?php foreach ($fields as $key=>$label): ?><div class="item"><div class="value <?=$key==='current'||$key==='local'||$key==='probe'?'machine':''?>"><?=htmlspecialchars((string)($state[$key] ?? '--'), ENT_QUOTES, 'UTF-8')?></div><div class="label"><?=htmlspecialchars($label, ENT_QUOTES, 'UTF-8')?></div></div><?php endforeach; ?></section><p class="machine">Historical ISO: <?=htmlspecialchars((string)($state['historical_iso'] ?? ''), ENT_QUOTES, 'UTF-8')?></p><p class="machine">Generated: <?=htmlspecialchars(gmdate(DateTimeInterface::ATOM, (int)($state['generated_at_ts'] ?? time())), ENT_QUOTES, 'UTF-8')?></p></main></body></html>
