# BZJ Connections Sync 8.7.0

## Canonical model

BuddyBoss/WordPress is the source of truth.

- BuddyBoss connection ↔ QuickDate Add Friend/friendship.
- BuddyBoss follow ↔ WoWonder Streams follow.
- QuickDate ordinary follow ↔ BuddyBoss follow.
- WoWonder Streams follow ↔ BuddyBoss follow.
- Blocks are directional, destructive and non-restorative.
- QuickDate ordinary follows are never converted into BuddyBoss connections.
- Streams Friends System remains unused.

## Files

1. `wp-content/mu-plugins/bzj-connections-sync.php`
2. `shared/bzj-connection-client.php`
3. Surgical patches for:
   - `streams/assets/includes/functions_one.php`
   - `streams/themes/sunshine/layout/extra_js/content.phtml`
   - `social/requests/ajax/profile.php`
   - `social/requests/ajax/useractions.php`
   - `social/core.php`

## Authentication

Set platform-specific secrets where possible:

- `BZJ_STREAMS_SYNC_SECRET`
- `BZJ_SOCIALS_SYNC_SECRET`

`BUZZ_SSO_SECRET` is the final fallback. The client signs `timestamp.raw_json` with HMAC-SHA256 and sends `X-BZJ-Platform`, `X-BZJ-Timestamp`, and `X-BZJ-Signature`.

## Important implementation correction

Do NOT modify `Wo_IsFollowRequested()` to perform a write. It is a predicate and must remain read-only. Streams immediate-follow behavior is implemented in `Wo_RegisterFollow()` by forcing `active = 1`.

## Verification

The control plane verifies external writes by reading the row back. Logs are written under `/data/logs` and include database/table information, affected rows, verified status, event UUID and errors, but never secrets or authorization headers.

## Deployment

1. Back up all three databases.
2. Keep v8.4.1 available for rollback.
3. Install the MU plugin.
4. Install the pure-PHP external client.
5. Apply the surgical patches.
6. Confirm only one BZJ connection-sync MU plugin is active.
7. Confirm `/data/logs` is writable.
8. Open `/wp-json/bzj/v6/connection-management/diagnostics` while authenticated as an administrator.
9. Run the test matrix in the deployment instructions.

The plugin creates/maintains `wp_bzj_relationships` and `wp_bzj_relationship_events` and does not require additional logging tables.
