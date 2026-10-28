<?php
declare(strict_types=1);
// Run from the extracted package root: php tests/smoke.php
// This test intentionally uses no production database credentials.
$oscFile = __DIR__ . '/../shared/palmier/oscillator/palmier-oscillator.php';
require_once $oscFile;
$fixed = 1793222400; // 2026-10-29T00:00:00Z
$data = bzj_osc_build_data($fixed);
foreach (['timestamp','datetime','lunar_variance','solar_variance','palmier_variance','passover_variance','oscillation','oscillation_index'] as $key) {
    if (!array_key_exists($key, $data)) { fwrite(STDERR, "Missing oscillator key: {$key}\n"); exit(1); }
}
if (!is_finite((float)$data['oscillation']) || !is_finite((float)$data['oscillation_index'])) { fwrite(STDERR, "Non-finite oscillator output\n"); exit(1); }
require_once __DIR__ . '/../shared/millennium-timezone/millennium-probe.php';
$phase = mtz_probe_find_phase($fixed);
if (count($phase) !== 4 || $phase[0] < 0 || $phase[0] > 4) { fwrite(STDERR, "Invalid phase result\n"); exit(1); }
$probe = mtz_probe_value($fixed, $phase[1], $phase[2], (float)$data['oscillation_index']);
if (!is_finite($probe)) { fwrite(STDERR, "Non-finite Probe output\n"); exit(1); }
echo "PASS: oscillator payload and Probe phase/value smoke tests\n";
