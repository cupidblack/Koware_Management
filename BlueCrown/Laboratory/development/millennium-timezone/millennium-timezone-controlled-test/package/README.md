# Millennium Timezone controlled-testing package

## Contents

- `shared/db_helpers.php` — supplied helper implementation plus validated Millennium Timezone table-name support.
- `shared/millennium-timezone/millennium-timezone.php` — canonical timezone/calendar engine, JSON/HTML interface, file log, TTL cache, database snapshot persistence, refresh locking.
- `shared/millennium-timezone/millennium-probe.php` — Probe phase calculation with direct local oscillator function calls.
- `shared/palmier/oscillator/palmier-oscillator.php` — include-safe oscillator functions and its JSON/HTML interface.
- `shared/palmier/gold_calc/gold_calc_helpers.php` and `gold_calc.php` — local historical-date integration and file-backed gold cache.
- `sql/001_millennium_timezone_snapshot.sql` — one-row current snapshot table migration.
- `INTEGRATION_PATCHES.md` — exact integration change for the existing `palmier_calc.php`, plus unaffected-file disposition.
- `tests/` — basic CLI smoke test.

## Before deployment

1. Back up the existing `shared/db_helpers.php`, `sabbatical-date.php`, Probe, oscillator, Gold calculator files, and Palmier calculation file.
2. Deploy to a staging copy, not the production web root.
3. Confirm `mysqli`, `DotEnv.php`, `.env`, and the existing `get_iapd_db_conn()` configuration are present.
4. Run `sql/001_millennium_timezone_snapshot.sql` against `koware_iapd` using an account with permission to create that table.
5. Ensure the web/PHP process can write to `shared/millennium-timezone/` and `shared/palmier/oscillator/`. Prefer a directory outside the public web root for logs if the host configuration permits; otherwise deny direct HTTP access to `.log` and `.lock` files via server configuration.
6. Apply the `palmier_calc.php` function replacement described in `INTEGRATION_PATCHES.md`.
7. Run the tests and manually compare outputs against the legacy endpoint at the same fixed UTC timestamp before changing DNS/routes or removing the old file.
8. Keep `sabbatical-date.php` and the old Probe file available as rollback copies outside the public web directory until the comparison passes.

## Endpoints

- HTML: `/shared/millennium-timezone/millennium-timezone.php`
- JSON: `/shared/millennium-timezone/millennium-timezone.php?format=json`
- Historical date: `/shared/millennium-timezone/millennium-timezone.php?endpoint=historical`
- Current UTC: `/shared/millennium-timezone/millennium-timezone.php?endpoint=current`
- Probe: `/shared/millennium-timezone/millennium-timezone.php?endpoint=probe`
- Probe direct JSON: `/shared/millennium-timezone/millennium-probe.php?probe=now`
- Oscillator JSON: `/shared/palmier/oscillator/palmier-oscillator.php?oscillation=live`

`force=1` forces a refresh. Normal refresh TTL is 12,345 seconds. Calculation history is appended to files; the database stores only the current structured snapshot.

## Data model

`koware_iapd.millennium_timezone_snapshot` stores a single JSON payload (`id=1`) with `updated_at`. The required prefix convention is implemented by `millennium_timezone_table($suffix)` in `db_helpers.php`; the initial migration intentionally creates only the snapshot table. Additional tables should be added only when a concrete structured state/query requirement exists.

## Validation boundary

PHP syntax and deterministic engine smoke tests are included. A true production sign-off still requires staging credentials and the live CSV data files, database permissions, and comparison against the original live endpoint. Those environment-specific checks cannot be inferred from source code alone.
