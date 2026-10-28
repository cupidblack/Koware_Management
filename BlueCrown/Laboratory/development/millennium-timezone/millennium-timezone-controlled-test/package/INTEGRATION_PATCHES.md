# Integration patches for the existing Palmier files

This package replaces the two moved engines and updates the Gold calculator. The following source files do not require a path change because they do not reference `sabbatical-date.php` or the old Probe URL:

- `shared/palmier/scripts/bulk_convert_currency_csvs.php`
- `shared/palmier/scripts/currency-bulk-multiplier.php`
- `shared/palmier/palmier-helpers.php`
- `shared/palmier/palmier_logger.php` — this writes user activity (`Wo_UserActivityLog`), not calculation history. Keep it as a business activity log; do not redirect it into the timezone calculation log.

## `palmier_calc.php`: replace its `fetch_json()` implementation

Keep the rest of the existing calculator intact. Replace only the existing `fetch_json($url, $timeout=20)` function (lines near the top of the current file) with the complete function below. This removes internal HTTP calls for the historical gold calculator and oscillator, while retaining HTTP for external providers and the existing WordPress currency endpoints.

```php
function fetch_json($url, $timeout = 20) {
    $url = (string) $url;

    // Historical gold average: same-domain endpoint replaced by direct local include.
    if (defined('HISTORICAL_API') && strpos($url, HISTORICAL_API) === 0) {
        require_once __DIR__ . '/gold_calc/gold_calc_helpers.php';
        $slot = getLatest6hSlot();
        $row = readLogRow(getLogFile($slot), $slot);
        if (!$row) {
            // The calculator endpoint normally computes this payload. Avoid recursive
            // inclusion; if no current slot exists, read the latest cached log row.
            $files = glob(__DIR__ . '/gold_calc/gold_prices/gold_cache_*.log') ?: [];
            rsort($files);
            foreach ($files as $file) {
                $lines = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (!$lines) continue;
                for ($i = count($lines) - 1; $i >= 0; --$i) {
                    $candidate = json_decode($lines[$i], true);
                    if (is_array($candidate) && isset($candidate['historical_gold_average'], $candidate['historical_currencies_count'])) {
                        return $candidate;
                    }
                }
            }
            return null;
        }
        return $row;
    }

    // Oscillator: local functions + local file cache; no same-domain HTTP request.
    if (defined('OSC_URL') && strpos($url, OSC_URL) === 0) {
        require_once __DIR__ . '/oscillator/palmier-oscillator.php';
        return bzj_osc_get_data();
    }

    $context = stream_context_create([
        'http' => [
            'timeout' => max(1, (int) $timeout),
            'ignore_errors' => true,
            'header' => "Accept: application/json\r\nCache-Control: no-cache\r\nPragma: no-cache\r\n",
        ],
    ]);
    $raw = @file_get_contents($url, false, $context);
    if ($raw === false || trim($raw) === '') return null;
    $json = json_decode($raw, true);
    return is_array($json) ? $json : null;
}
```

**Important:** The historical branch reads the already-created Gold cache log and deliberately does not trigger `gold_calc.php` recursively. On first deployment, warm the Gold calculator once before relying on `palmier_calc.php`, or refactor the historical calculation into a reusable function in a separate follow-up.

## Existing Probe URL references

Search the codebase for these strings and update any additional consumers not listed in the supplied resources:

- `/shared/palmier/calendar/sabbatical-date.php`
- `/shared/palmier/probe/millennium-probe.php`
- `/shared/palmier/oscillator/palmier-oscillator.php?oscillation=live`

New canonical URLs:

- `/shared/millennium-timezone/millennium-timezone.php`
- `/shared/millennium-timezone/millennium-probe.php`
