# Validation report

Validation performed in the build container on PHP 8.4.24:

- `php -l` passed for every PHP file included in this package.
- `php tests/smoke.php` passed the oscillator payload and Probe phase/value checks.
- The tests do not connect to the production database and do not require external FX APIs.

Not yet verified in this environment:

- MySQL permissions and execution of the snapshot migration.
- `DotEnv.php` and `.env` loading in the live Buzzjuice tree.
- Writable permissions for the log and lock files under the web/PHP account.
- Equivalence of the historical formula against the currently deployed legacy endpoint at the same frozen UTC instant.
- Currency CSV availability and historical gold averages in the live data set.
- The `palmier_calc.php` integration patch in the live script.

This is a controlled-testing candidate, not a claim that production staging checks have passed. Do not remove the old endpoints until the comparison checklist in `README.md` passes.
