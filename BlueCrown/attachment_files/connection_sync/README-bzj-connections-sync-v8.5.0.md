# Buzzjuice Network Connection Synchronization — v8.5.0

## Executive architecture

```text
                         ┌──────────────────────────────┐
                         │ WordPress + BuddyBoss         │
                         │ CANONICAL SOURCE OF TRUTH     │
                         │                              │
                         │ connections                  │
                         │ follows                      │
                         │ blocks                       │
                         └──────────────┬───────────────┘
                                        │
                       canonical hooks / projections
                                        │
                 ┌──────────────────────┴──────────────────────┐
                 │                                             │
        ┌────────▼─────────┐                         ┌─────────▼────────┐
        │ Buzzjuice Streams │                         │ Buzzjuice Socials│
        │ WoWonder          │                         │ QuickDate         │
        │ follows + blocks  │                         │ follows/friends  │
        └───────────────────┘                         └───────────────────┘
```

The control plane is:

```text
/wp-content/mu-plugins/bzj-connections-sync.php
```

External platforms use:

```text
/shared/bzj-connection-client.php
```

External failed commands are durably queued under:

```text
/data/logs/bzj-sync-outbox/
```

and retried by:

```text
/shared/bzj-sync-outbox-cron.php
```

---

# Canonical relationship semantics

## BuddyBoss Connection

```text
none
requested
connected
```

Projection:

| BuddyBoss | Streams | Socials |
|---|---|---|
| requested | none | pending Add Friend |
| connected | none | reciprocal accepted friendship |
| rejected | none | pending projection removed |
| withdrawn | none | pending projection removed |
| removed | none | connection projection removed |

Streams receives no friendship/connection projection because its Friends System is disabled.

## Ordinary Follow

Directional:

```text
A → B
```

Mapping:

```text
BuddyBoss Follow → Streams Follow

Streams Follow → BuddyBoss Follow

QuickDate Follow → BuddyBoss Follow → Streams Follow
```

QuickDate ordinary Follow is NOT a BuddyBoss connection.

## Block

Directional and destructive:

```text
A blocks B
```

results in:

```text
BuddyBoss block A → B
BuddyBoss connection removed
BuddyBoss pending request removed
BuddyBoss A → B follow removed
BuddyBoss B → A follow removed
Streams block A → B
Streams A → B follow removed
Streams B → A follow removed
Socials block A → B
Socials relationship projections removed
```

Unblock only removes the block.

It does not restore previous relationships.

---

# Identity rule

External IDs are never treated as WordPress IDs.

The control plane resolves:

```text
Streams external ID
    ↓
wp_usermeta.meta_key = wo_user_id
    ↓
WordPress user ID
```

and:

```text
Socials external ID
    ↓
wp_usermeta.meta_key = qd_user_id
    ↓
WordPress user ID
```

A missing or ambiguous mapping is rejected.

No email matching is used for relationship synchronization.

---

# Event flow

## BuddyBoss-origin action

```text
User action
    ↓
BuddyBoss native mutation
    ↓
BuddyBoss lifecycle hook
    ↓
Acquire deterministic pair lock
    ↓
Update canonical ledger
    ↓
Project external state
    ↓
Read-back verification
    ↓
Log
```

## Streams-origin action

```text
Streams local DB mutation
    ↓
Read-back verification
    ↓
Signed event
    ↓
WordPress connection-management endpoint
    ↓
Resolve wo_user_id
    ↓
Native BuddyBoss function
    ↓
BuddyBoss hook
    ↓
Canonical projection
```

## Socials-origin action

```text
Socials local DB mutation
    ↓
Read-back verification
    ↓
Signed event
    ↓
WordPress connection-management endpoint
    ↓
Resolve qd_user_id
    ↓
Native BuddyBoss function
    ↓
BuddyBoss hook
    ↓
Canonical projection
```

---

# QuickDate semantic split

QuickDate currently overloads:

```text
followers.active = 0
followers.active = 1
```

for different relationship meanings.

Therefore the integration never uses generic `Wo_DeleteFollow()` as a synchronization trigger.

Semantic handlers are used instead:

```text
add_friend()               → connection_request
approve_friend_request()   → connection_accept
disapprove_friend_request()→ connection_reject
withdraw/cancel            → connection_withdraw
unfriend                   → connection_remove

ordinary follow            → follow
ordinary unfollow          → unfollow

block                      → block
unblock                    → unblock
```

The ledger preserves the difference between:

```text
QuickDate ordinary follow
```

and:

```text
QuickDate accepted friendship projection
```

so removing a BuddyBoss connection does not blindly delete an independent QuickDate follow.

---

# WoWonder follow behavior

The Friends System is disabled.

Therefore:

```text
Streams follow = immediate active follow
```

`Wo_RegisterFollow()` is the correct write point to force:

```text
active = 1
```

`Wo_IsFollowRequested()` remains a read-only predicate.

The earlier suggestion to mutate the database inside `Wo_IsFollowRequested()` is intentionally rejected because it would make a predicate have side effects and can cause callers to take the wrong branch or insert duplicate rows.

---

# Authentication

External commands use:

```text
X-BZJ-Timestamp
X-BZJ-Signature
```

Signature:

```text
HMAC-SHA256(
    timestamp + "." + raw_json_body,
    platform_secret
)
```

Preferred secrets:

```text
BZJ_STREAMS_SYNC_SECRET
BZJ_SOCIALS_SYNC_SECRET
```

Fallback:

```text
BUZZ_SSO_SECRET
```

The control plane rejects:

- stale timestamps
- invalid signatures
- unknown origins
- disallowed operations
- invalid UUIDs
- invalid external IDs
- ambiguous user mappings

---

# Idempotency and retries

Every event has one UUID.

The same UUID is retained during retry.

The WordPress event ledger stores:

```text
pending
processing
processed
failed
```

The external client stores failed outbound commands in:

```text
/data/logs/bzj-sync-outbox/<platform>/
```

The retry runner reuses the same event UUID.

A successful local user action is never rolled back merely because WordPress is temporarily unavailable.

---

# Logging

WordPress:

```text
/data/logs/bzj-connections-sync.log
```

Streams:

```text
/data/logs/bzj-streams-sync.log
```

Socials:

```text
/data/logs/bzj-socials-sync.log
```

Outbox:

```text
/data/logs/bzj-sync-outbox/
```

Startup diagnostics record:

```text
WordPress database
QuickDate database
WoWonder database
WordPress usermeta table
QuickDate followers table
QuickDate blocks table
WoWonder followers table
WoWonder blocks table
```

Each projection records:

```text
platform
operation
origin
WordPress IDs
external IDs
database
table
affected rows
verification
event UUID
error
```

Secrets and authorization headers are never logged.

---

# Installation order

## Step 1 — Backup

Back up:

```text
WordPress database
QuickDate database
WoWonder database
```

and copy the current working:

```text
bzj-connections-sync-v8.4.1.php
bzj-relationship-sync-client.php
bzj-sync-outbox-cron.php
```

to a rollback directory.

Do not delete the existing files until the new installation passes the staged tests.

## Step 2 — Install MU plugin

Copy:

```text
bzj-connections-sync-v8.5.0.php
```

to:

```text
wp-content/mu-plugins/bzj-connections-sync.php
```

Only one Buzzjuice connection-sync MU plugin should be active.

## Step 3 — Install shared client

Copy:

```text
bzj-connection-client.php
```

to:

```text
/shared/bzj-connection-client.php
```

## Step 4 — Install outbox runner

Copy:

```text
bzj-sync-outbox-cron.php
```

to:

```text
/shared/bzj-sync-outbox-cron.php
```

Recommended server cron:

```text
*/5 * * * * /usr/bin/php /home/koware/public_html/buzzjuice.net/shared/bzj-sync-outbox-cron.php >/dev/null 2>&1
```

Adjust the PHP executable path if necessary.

## Step 5 — Apply surgical external-platform patches

Use:

```text
bzj-connection-sync-surgical-patches.md
```

to update:

```text
streams/assets/includes/functions_one.php
streams/themes/sunshine/layout/extra_js/content.phtml
social/requests/ajax/profile.php
social/requests/ajax/useractions.php
```

Do not regenerate the long files.

---

# Required staging tests

Run each test with two ordinary test users.

## BuddyBoss

### Connection request

Expected:

```text
BuddyBoss pending request = yes
Streams = unchanged
Socials pending Add Friend = yes
```

### Accept

Expected:

```text
BuddyBoss connected
Streams = unchanged
Socials reciprocal active rows = yes
```

### Reject

Expected:

```text
BuddyBoss pending request = gone
Socials pending row = gone
```

### Withdraw

Expected:

```text
BuddyBoss pending request = gone
Socials pending row = gone
```

### Unfriend

Expected:

```text
BuddyBoss connection = gone
Socials accepted connection projection = gone
ordinary follows = preserved
```

## BuddyBoss follow

Expected:

```text
BuddyBoss A → B = yes
Streams A → B active = yes
Socials = unchanged
```

Unfollow:

```text
BuddyBoss A → B = no
Streams A → B = no
Socials = unchanged
```

## BuddyBoss block

Expected:

```text
BuddyBoss block = yes
BuddyBoss connection = removed
BuddyBoss follows both directions = removed
Streams block = yes
Streams follows both directions = removed
Socials block = yes
Socials relationship projections = removed
```

## BuddyBoss unblock

Expected:

```text
BuddyBoss block = no
Streams block = no
Socials block = no
old connection/follows = NOT restored
```

## Streams follow

Expected:

```text
Streams local active follow = yes
BuddyBoss follow = yes
Socials ordinary follow = unchanged
```

## Streams unfollow

Expected:

```text
Streams follow = no
BuddyBoss follow = no
```

## Streams block/unblock

Expected:

```text
Streams block ↔ BuddyBoss block ↔ Socials block
```

with destructive block cleanup and non-restorative unblock.

## Socials Add Friend

Expected:

```text
Socials pending row = yes
BuddyBoss pending connection = yes
Streams follow = no
```

## Socials accept

Expected:

```text
Socials reciprocal active rows = yes
BuddyBoss connected = yes
Streams = unchanged
```

## Socials ordinary Follow

Expected:

```text
Socials ordinary follow = yes
BuddyBoss follow = yes
Streams active follow = yes
BuddyBoss connection = unchanged
```

## Socials ordinary Unfollow

Expected:

```text
Socials ordinary follow = no
BuddyBoss follow = no
Streams follow = no
BuddyBoss connection = unchanged
```

---

# Operational verification

After installation, verify:

```text
/data/logs/bzj-connections-sync.log
/data/logs/bzj-streams-sync.log
/data/logs/bzj-socials-sync.log
```

Look for:

```text
wordpress_database
wordpress_connection_test
ledger_table
events_table
usermeta_table
streams.database
streams.tables
socials.database
socials.tables
```

The WordPress diagnostics endpoint is:

```text
/wp-json/bzj/v6/connection-management/diagnostics
```

The diagnostics endpoint is administrator-only.

---

# Version 8.5 changes from the working 8.4.1 baseline

Preserved:

- BuddyBoss as source of truth
- existing BuddyBoss request restrictions
- canonical ledger/event architecture
- pair-level locking
- HMAC authentication
- direct external DB projections for BuddyBoss-origin events
- file logging
- retry queue
- working BuddyBoss → Socials connection synchronization
- working BuddyBoss → Streams follow synchronization
- working block/unblock synchronization

Corrected/refactored:

1. External IDs are explicitly resolved through `wo_user_id` and `qd_user_id`.
2. External commands are intent commands rather than direct BuddyBoss DB writes.
3. QuickDate Add Friend and ordinary Follow are separate semantics.
4. `Wo_IsFollowRequested()` remains read-only.
5. Streams follow writes are forced to `active = 1`.
6. External writes are read-back verified.
7. External failed commands retain the same event UUID.
8. Pair locks use `bzj_pair_<lower_wp_id>_<higher_wp_id>`.
9. Blocking remains destructive.
10. Unblocking remains non-restorative.
11. Fragile external path assumptions are replaced by controlled root discovery.
12. Startup database/table diagnostics are explicit.
13. The REST route remains:

```text
/wp-json/bzj/v6/connection-management
```

14. No WoWonder API endpoints are used.
15. No WordPress functions are expected to exist inside Streams or Socials.

---

# Final operating principle

The Buzzjuice Network is not three independent relationship systems.

It is one relationship system with:

```text
BuddyBoss
    =
canonical authority
```

and:

```text
Streams
    =
follow/block projection

Socials
    =
connection/follow/block projection
```

External platforms may initiate user intent, but they do not become authorities over the canonical WordPress relationship state.
