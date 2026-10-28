<?php
declare(strict_types=1);
date_default_timezone_set('UTC');
require_once __DIR__ . '/millennium-timezone-core.php';

function millennium_timezone_json(array $payload, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, max-age=0');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

$state = millennium_timezone_calculate();
$endpoint = strtolower((string) ($_GET['endpoint'] ?? (isset($_GET['format']) && $_GET['format'] === 'json' ? 'full' : '')));
if ($endpoint !== '') {
    switch ($endpoint) {
        case 'full':
        case 'current':
            millennium_timezone_json($endpoint === 'current'
                ? ['iso' => $state['current'], 'current' => $state['current']]
                : $state);
        case 'historical':
            millennium_timezone_json([
                'iso' => $state['reference_iso'],
                'reference_iso' => $state['reference_iso'],
                'reference_year' => $state['reference_year'],
                'date' => $state['date'],
                'month' => $state['month'],
                'local' => $state['local'],
            ]);
        case 'probe':
            // Probe is served by the dedicated probe endpoint; do not make an HTTP self-call.
            millennium_timezone_json(['error' => 'Use the dedicated millennium-probe.php endpoint.', 'probe_endpoint' => 'millennium-probe.php?probe=now'], 409);
        default:
            millennium_timezone_json(['error' => 'Invalid endpoint. Supported values: full, historical, current, probe.'], 400);
    }
}

function millennium_timezone_h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Millennium Timezone</title>
<style>body{background:#0f172a;color:#fff;font-family:Arial,sans-serif;margin:0;text-align:center}.wrap{max-width:900px;margin:auto;padding:24px}.row{display:flex;flex-wrap:wrap;justify-content:center;gap:16px;margin:16px 0}.group{min-width:100px}.big{font-size:1.8rem;font-weight:700}.mid{font:1rem monospace;overflow-wrap:anywhere}.label{font-size:11px;opacity:.7}@media(max-width:600px){.big{font-size:1.2rem}.mid{font-size:.85rem}}</style></head><body><main class="wrap">
<h1>Millennium Timezone</h1><div class="row">
<?php foreach (['day'=>'Day','date'=>'Date','month'=>'Month','jubilee'=>'Jubilee','reference_year'=>'Reference Year'] as $key=>$label): ?><div class="group"><div class="big"><?= millennium_timezone_h((string)$state[$key]) ?></div><div class="label"><?= millennium_timezone_h($label) ?></div></div><?php endforeach; ?>
</div><div class="row">
<?php foreach (['local'=>'Local','current'=>'Current'] as $key=>$label): ?><div class="group"><div class="mid"><?= millennium_timezone_h((string)$state[$key]) ?></div><div class="label"><?= millennium_timezone_h($label) ?></div></div><?php endforeach; ?>
<div class="group"><div class="mid">Use the dedicated probe endpoint</div><div class="label">Probe</div></div></div>
<p>Machine-readable snapshot: <a style="color:#93c5fd" href="?endpoint=full">JSON</a></p></main></body></html>
