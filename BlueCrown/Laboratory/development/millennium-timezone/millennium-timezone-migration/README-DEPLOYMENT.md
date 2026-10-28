# Millennium Timezone migration package

## Important scope note
This package supplies the two new target endpoints and a deployment checklist. It is **not** a blind replacement for all eleven source files: the existing gold, oscillator, currency and logger scripts contain application-specific behavior that should be merged against the live versions before replacement. Preserve those originals and apply the integration changes below.

## Target layout
- `shared/millennium-timezone/millennium-timezone.php` (new; replaces the old sabbatical-date endpoint)
- `shared/millennium-timezone/millennium-probe.php` (moved from the old probe location)
- `shared/millennium-timezone/logs/` (must be writable by PHP; deny direct web access)

## Deployment steps
1. Back up the current eleven PHP files and database schema.
2. Create `/shared/millennium-timezone/` and upload both PHP files from this package.
3. Create the `logs` directory with owner/group matching the PHP process and restrictive permissions (normally 0750). Add an Apache `.htaccess` containing `Require all denied` in the logs directory, or equivalent Nginx deny rules. Never expose the JSON state/log files over HTTP.
4. Verify the server PHP version supports PHP 8.0+ syntax used by this package. If not, adapt types before deployment.
5. Update `gold_calc_helpers.php` to require the new engine by local path and use `millennium_timezone_get_latest()['historical_iso']`; do not call the old endpoint URL. Keep the function signature `getPalmierHistoricalDate($endpoint = null)` for compatibility with existing callers.
6. Update oscillator consumers to call a local oscillator function only after extracting the existing oscillator math into a function. Do not replace that math with a new formula without validating against historical outputs.
7. Search the repository for both old paths and replace references:
   - `/shared/palmier/calendar/sabbatical-date.php`
   - `/shared/palmier/probe/millennium-probe.php`
8. Test `millennium-timezone.php?format=json`, `?endpoint=historical`, `?endpoint=current`, `?endpoint=probe`, and `millennium-probe.php?probe=now`. Confirm JSON parses and all eight requested display fields appear in HTML.
9. Confirm a second request within 12,345 seconds reuses the state file and does not append another timezone history entry. Force refresh with `?format=json&refresh=1` during testing only.
10. Run `php -l` on every deployed PHP file, then test gold calculations, oscillator output, CSV conversion, currency multiplier, and activity logging in staging before production cutover.

## API contract
`millennium-timezone.php?format=json` returns `day`, `date`, `month`, `jubilee`, `reference_year`, `local`, `current`, `historical_iso`, `probe`, and supporting probe/cache fields. `?endpoint=historical` returns `{"iso":"..."}` for backward compatibility; `?endpoint=current` returns the current ISO; `?endpoint=probe` returns probe fields.

## Database guidance
The timezone engine in this package uses a JSON state file and append-only log file; it does not create tables or write history to the DB. If you also want an IAPD DB state table, use the existing `get_iapd_db_conn()` connection and safely quoted table name `` `koware_iapd`.`millennium_timezone_state` `` only after confirming the DB account has permission. Do not assume that `IAPD_DB_NAME` equals `koware_iapd` without checking the live environment.

## Validation warning
The original probe implementation's exact `fetchOscIndex()` / `probe()` formulas must be preserved when porting the complete chart dashboard. The local service here is a safe integration starting point, not proof that its computed probe progression is numerically identical to the old implementation. Compare a fixed set of timestamps against the original implementation before treating probe values as authoritative.
