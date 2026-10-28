# Required dependent-file edits after core verification

These are exact dependency changes to apply only after the new core passes comparison tests. Do not replace the whole dependent files with partial snippets.

## `shared/palmier/gold_calc/gold_calc_helpers.php`

Replace the HTTP-based `getPalmierHistoricalDate($endpoint)` implementation with a local include/function call:

```php
function getPalmierHistoricalDate($unusedEndpoint = null) {
    require_once dirname(__DIR__, 2) . '/millennium-timezone/millennium-timezone-core.php';
    $state = millennium_timezone_calculate();
    if (empty($state['reference_iso'])) {
        throw new RuntimeException('Millennium Timezone did not return a reference timestamp.');
    }
    return new DateTime($state['reference_iso'], new DateTimeZone('UTC'));
}
```

In `gold_calc.php`, remove the `$sabbatical` URL constant and change the call to `getPalmierHistoricalDate()` with no URL argument. Keep all currency and gold calculations unchanged.

## `shared/palmier/probe/millennium-probe.php`

Move only after the probe's oscillator dependency is switched to a local callable. The current oscillator file is an HTTP endpoint and should not be `require_once`d until its calculation functions are separated from its request/HTML dispatcher. Preserve phase boundaries, cache TTL, log format, and the probe formula during that extraction.

## `shared/palmier/palmier_calc.php`

Replace same-domain fetches only after creating local callable interfaces for the WooCommerce currency list, base-currency configuration, historical gold calculator, and oscillator. Do not replace external market-data providers with local DB reads unless the relevant source data is actually persisted and freshness is defined.

## `shared/palmier/palmier_logger.php`

Keep `bzj_log_activity()` writing to `Wo_UserActivityLog` unless the product owner explicitly decides to change activity-tracking semantics. Use file logs for calculation diagnostics/history, not as a blanket replacement for application activity records.

## CSV scripts and other Palmier files

`bulk_convert_currency_csvs.php`, `currency-bulk-multiplier.php`, `palmier-helpers.php`, and `palmier_calc.php` need regression tests, but their paths are not all affected by moving the calendar/probe files. Do not modify unrelated logic merely to make the change appear comprehensive.
