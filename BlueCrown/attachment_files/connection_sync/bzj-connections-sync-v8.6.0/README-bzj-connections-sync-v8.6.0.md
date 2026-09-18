# BZJ Connections Sync 8.6.0

## Canonical model
BuddyBoss/WordPress is authoritative. External platforms are projections.

### Direction matrix
| Origin | Action | BuddyBoss | Streams | Socials |
|---|---|---|---|---|
| BuddyBoss | connection request/accept/reject/withdraw/remove | canonical | none | friend request/friend/unfriend |
| BuddyBoss | follow/unfollow | canonical | follow/unfollow | **never projected** |
| BuddyBoss | block/unblock | canonical | block/unblock | block/unblock |
| Streams | follow/unfollow | BB follow/unfollow | native row | none |
| Streams | block/unblock | BB block/unblock | native row | block/unblock |
| Socials | add-friend/follow | BB connection request | none | native friend row |
| Socials | approve | BB connection accept | none | native friend row |
| Socials | reject/cancel/unfriend | BB reject/withdraw/remove | none | native row |
| Socials | block/unblock | BB block/unblock | block/unblock | native row |

## Important behavior
- WoWonder Friends System remains unused; Streams follows are forced immediate.
- QuickDate `followers` is a legacy union used by its friend system. It is not treated as a generic Buzzjuice follow projection.
- A QuickDate follow/add-friend is interpreted as a BuddyBoss connection request.
- BuddyBoss follow is projected only to Streams.
- Blocking dominates connections and follows. Existing BuddyBoss friendship is removed when a block is created. Unblocking does not recreate relationships.
- Pair locks, event UUIDs and an outbox/recovery queue prevent common loops and transient DB failures.
- Logs are written to `/data/logs/bzj-connections-sync.log`.
- REST endpoint: `/wp-json/bzj/v6/connection-management`.
- External requests use HMAC SHA-256 with `BUZZ_SSO_SECRET`; WordPress functions are never expected to run in Streams/Socials.

## Install
1. Back up the current v8.4.1/v8.5.x plugin and the three databases.
2. Install `bzj-connections-sync-v8.6.0.php` in `wp-content/mu-plugins/`.
3. Keep the existing `shared/db_helpers.php`; v8.6.0 uses its `get_wowonder_db()` and `get_qd_db_conn()` helpers.
4. Add the client file where the external applications can include it, or copy its single `bzj_connection_client()` function into the existing external client.
5. Apply the surgical patches to Streams/Socials in the accompanying patch document.
6. Confirm `/data/logs/bzj-connections-sync.log` records database/table discovery.
7. Test in the order in the verification matrix.

## Do not use
Do not call WoWonder or QuickDate APIs for relationship synchronization. External DB writes remain direct SQL projections from the authenticated control plane.
