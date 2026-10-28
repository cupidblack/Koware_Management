# Millennium Timezone refactor package (core migration build)

## Included

- `shared/millennium-timezone/millennium-timezone-core.php`: self-contained core retaining the date formula from the existing `sabbatical-date.php`.
- `shared/millennium-timezone/millennium-timezone.php`: HTML view and stable JSON endpoint (`?endpoint=full`, `historical`, `current`).
- `shared/millennium-timezone/millennium-timezone-db.php`: optional adapter using the existing `get_iapd_db_conn()` from `shared/db_helpers.php`.
- `sql/millennium_timezone.sql`: optional structured snapshot/cache tables in `koware_iapd`.

## Important release status

This is a **core migration build**, not a verified replacement for all eleven supplied files. The existing probe engine has non-trivial phase, oscillator and log behaviour; it must be integrated from the current source and tested before replacing it. The package deliberately does not fabricate a probe timestamp or make a same-domain HTTP self-call. The `probe` field is therefore `null` until the dedicated probe engine is integrated. Do not present this build as the final production cutover.

The current source `db_helpers.php` is not replaced. The DB adapter expects its existing `get_iapd_db_conn()` function and fails closed if it is unavailable. The DB adapter is optional and is not automatically invoked by the public endpoint, avoiding unrequested write traffic on every request.

## Deployment staging

1. Back up the live files and database.
2. Create `shared/millennium-timezone/` and upload the three PHP files.
3. Run the SQL only if `koware_iapd` exists and the account has privileges.
4. Test `millennium-timezone.php?endpoint=full`; verify the date fields against the old endpoint at the same timestamp.
5. Do not remove the old files or change gold/probe consumers until the dedicated probe and the gold helper have been integration-tested.
6. After verification, update internal PHP consumers to `require_once` the core and call `millennium_timezone_calculate()` directly instead of fetching their own domain over HTTP.

## Public JSON schema

`day`, `date`, `month`, `jubilee`, `reference_year`, `local`, `current`, `probe`, plus `historical_time`, `reference_iso`, and `now_iso` for compatibility. `current` is the UTC current timestamp. `local` retains the existing engine's calculated historical clock value, as the old UI labels it “Local”.

## Logging

Keep calculation histories in locked, append-only files under a web-server-writable but non-public logs directory. Do not create a DB logging table. Existing `Wo_UserActivityLog` is application activity data, not timezone calculation history; do not replace it without a separate product decision.

## Security

- Rotate exposed third-party API credentials found in the current public `palmier_calc.php` before production deployment.
- Keep credentials in server environment variables, never in committed PHP source.
- Configure the `logs/` directory outside public access or deny HTTP access to it.
