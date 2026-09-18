# Buzzjuice Relationship Sync v8.4.1

## Architecture

WordPress/BuddyBoss is canonical. Streams and Socials are projections and clients.

### Canonical actions

| Source action | WordPress | Streams | Socials |
|---|---|---|---|
| BB connection request | canonical pending | no friendship | Add Friend pending |
| BB connection accept | canonical connected | no friendship | reciprocal active friend rows |
| BB reject/withdraw | none | none | pending friend projection removed |
| BB unfriend | none | ordinary follows preserved | connection rows removed; independent follows preserved |
| BB follow | directional follow | directional follow | no automatic follow |
| BB unfollow | directional unfollow | directional unfollow | no automatic connection change |
| BB block | destructive | block + both follows removed | block + relationship rows removed |
| BB unblock | block removed only | unblock only | unblock only |
| Streams follow/unfollow | canonical BB follow/unfollow | local action already happened | no direct Socials follow |
| Socials Add Friend | BB connection request | no follow | local pending friend |
| Socials approve | BB connection accept | no friendship | local connected friend |
| Socials reject/withdraw | BB connection reject/withdraw | none | local request removed |
| Socials unfriend | BB connection remove | none | local connection removed |
| Socials ordinary follow/unfollow | BB follow/unfollow | BB hook projects to Streams | local action already happened |
| Streams/Socials block | BB block | local block | local block |
| Streams/Socials unblock | BB unblock | local unblock | local unblock |

## 1. WordPress installation

Replace the old connections-sync MU plugin with:

`wp-content/mu-plugins/bzj-connections-sync-v8.4.1.php`

Remove/disable every earlier BZJ connections-sync file so two copies cannot register the same hooks.

The v8.4.1 plugin intentionally uses the BuddyBoss moderation hooks that were present in the working v8.1 implementation:

- `bp_moderation_after_save`
- `bb_moderation_after_delete`

Do not add the failed `bp_moderation_block_created` / `bp_moderation_block_deleted` hooks.

The REST endpoint is:

`/wp-json/bzj/v6/connection-management`

Diagnostics:

`/wp-json/bzj/v6/connection-management/diagnostics`

The endpoint requires:

- `X-BZJ-Timestamp`
- `X-BZJ-Signature`
- `X-BZJ-Platform`

The signature is:

`HMAC-SHA256(timestamp + "." + raw_json_body, platform_secret)`

## 2. Secrets

Generate two independent secrets:

`openssl rand -hex 32`

Add to WordPress `wp-config.php`:

```php
define('BZJ_STREAMS_SYNC_SECRET', 'PUT_STREAMS_SECRET_HERE');
define('BZJ_SOCIALS_SYNC_SECRET', 'PUT_SOCIALS_SECRET_HERE');
```

Add the same respective secret to the Streams configuration and Socials configuration.

Do not send these secrets in JavaScript or expose them to browsers.

`BUZZ_SSO_SECRET` remains a fallback only.

## 3. Shared external client

Install:

`/shared/bzj-relationship-sync-client.php`

Both Streams and Socials include this file after their PHP bootstrap.

Example:

```php
require_once __DIR__ . '/../../shared/bzj-relationship-sync-client.php';
```

Use the actual relative path from the platform file. Do not use `wp-load.php`.

## 4. Streams patches

### A. Force follows to be immediate

In:

`/streams/assets/includes/functions_one.php`

inside `Wo_RegisterFollow()` leave all existing validation and block checks intact.

Immediately before the final `INSERT` into `T_FOLLOWERS`, force:

```php
$active = 1;
```

Do not modify `Wo_IsFollowRequested()` to perform database writes.

The resulting local follow must be `active=1`.

### B. Streams follow

Immediately after the successful local follow INSERT, call:

```php
bzj_rel_sync_send(
    'streams',
    'follow',
    (int) $wo->user->user_id,
    (int) $following_id
);
```

Use the actual authenticated-user variable already present in `Wo_RegisterFollow()` if it is not `$wo->user->user_id`.

`$following_id` is the user being followed.

### C. Streams unfollow

In the successful branch of the existing `Wo_DeleteFollow()` operation, after the local DELETE succeeds:

```php
bzj_rel_sync_send(
    'streams',
    'unfollow',
    (int) $wo->user->user_id,
    (int) $following_id
);
```

Use the actual actor/target variables from the function.

### D. Streams block

In `Wo_RegisterBlock()`, after the local block INSERT succeeds:

```php
bzj_rel_sync_send(
    'streams',
    'block',
    (int) $wo->user->user_id,
    (int) $blocked_user_id
);
```

### E. Streams unblock

In `Wo_RemoveBlock()`, after the local block DELETE succeeds:

```php
bzj_rel_sync_send(
    'streams',
    'unblock',
    (int) $wo->user->user_id,
    (int) $blocked_user_id
);
```

The actor must be the blocker, and the target must be the user being unblocked.

## 5. Socials patches

Use the existing QuickDate action functions. Do not infer connection semantics from generic `Wo_DeleteFollow()` because QuickDate's `followers` table contains both ordinary follows and friend relationships.

### A. Add Friend

At the successful end of the existing QuickDate `add_friend()` operation:

```php
bzj_rel_sync_send(
    'socials',
    'connection_request',
    (int) $actor_user_id,
    (int) $target_user_id
);
```

Actor = requester. Target = requested user.

Do not send `follow`.

### B. Approve Friend Request

At the successful end of `approve_friend_request()`:

```php
bzj_rel_sync_send(
    'socials',
    'connection_accept',
    (int) $actor_user_id,
    (int) $requester_user_id
);
```

The actor is the person approving the request; the target is the requester.

### C. Reject Friend Request

At the successful end of `disapprove_friend_request()` when the request is rejected:

```php
bzj_rel_sync_send(
    'socials',
    'connection_reject',
    (int) $actor_user_id,
    (int) $requester_user_id
);
```

### D. Withdraw Friend Request

Where Socials withdraws a request:

```php
bzj_rel_sync_send(
    'socials',
    'connection_withdraw',
    (int) $actor_user_id,
    (int) $target_user_id
);
```

### E. Unfriend

Where the existing QuickDate unfriend operation successfully removes an accepted friend relationship:

```php
bzj_rel_sync_send(
    'socials',
    'connection_remove',
    (int) $actor_user_id,
    (int) $target_user_id
);
```

Do not replace this with `unfollow`.

### F. Ordinary Follow

Where the ordinary QuickDate Follow action succeeds:

```php
bzj_rel_sync_send(
    'socials',
    'follow',
    (int) $actor_user_id,
    (int) $target_user_id
);
```

This is a follow, not an Add Friend request.

### G. Ordinary Unfollow

Where ordinary QuickDate Unfollow succeeds:

```php
bzj_rel_sync_send(
    'socials',
    'unfollow',
    (int) $actor_user_id,
    (int) $target_user_id
);
```

### H. Block

At the successful end of QuickDate `block()`:

```php
bzj_rel_sync_send(
    'socials',
    'block',
    (int) $actor_user_id,
    (int) $target_user_id
);
```

### I. Unblock

At the successful end of QuickDate `unblock()`:

```php
bzj_rel_sync_send(
    'socials',
    'unblock',
    (int) $actor_user_id,
    (int) $target_user_id
);
```

## 6. Important QuickDate rule

Do not make the synchronization client call:

```php
Wo_DeleteFollow()
```

to decide whether an action was an unfriend or ordinary unfollow.

The QuickDate `followers` table is overloaded:

- `active=0` = pending friend request
- `active=1` = accepted friend or ordinary follow

The function/action that the user actually selected is the source of the semantic command.

## 7. External outbox retry

Install:

`/shared/bzj-sync-outbox-cron.php`

Run every five minutes:

```cron
*/5 * * * * /usr/bin/php /FULL/PATH/shared/bzj-sync-outbox-cron.php >/dev/null 2>&1
```

Use the real PHP binary and real path.

If an external platform action succeeds locally but WordPress is temporarily unavailable, the client writes the exact same event UUID/payload to its outbox and retries it later.

Do not generate a new UUID when manually retrying an event.

## 8. Logging

WordPress:

`/data/logs/bzj-connections-sync.log`

Streams:

`/data/logs/bzj-streams-sync.log`

Socials:

`/data/logs/bzj-socials-sync.log`

Outboxes:

`/data/logs/bzj-streams-sync-outbox.jsonl`
`/data/logs/bzj-socials-sync-outbox.jsonl`

External database writes should record:

- platform
- operation
- origin
- WP IDs
- external IDs
- database
- table
- event UUID
- verification/error

## 9. Required database mappings

WordPress usermeta:

```text
wo_user_id -> Streams users.id
qd_user_id -> Socials users.id
```

Streams:

```text
Wo_Followers
    following_id = followed user
    follower_id  = actor
    active       = 1 for immediate follow

Wo_Blocks
    blocker
    blocked
```

Socials:

```text
followers
    following_id = followed/recipient user
    follower_id  = actor/requester
    active       = 0 pending friend request
    active       = 1 accepted friend/ordinary follow

blocks
    user_id
    block_userid
    created_at (if present)
```

## 10. Verification order

After installation:

1. Confirm only v8.4.1 is active.
2. Visit the diagnostics endpoint while logged in as an administrator.
3. Confirm:
   - request hook = true
   - accept hook = true
   - withdraw hook = true
   - follow hook = true
   - block_save = true
   - block_delete = true
4. Confirm Streams DB and tables are connected.
5. Confirm Socials DB and tables are connected.
6. Check the startup log for database names.
7. Test with two test users.

### Test A — BuddyBoss connection request

A -> B.

Expected:

- BuddyBoss pending.
- Socials A -> B exists with `active=0`.
- no Streams friendship/friend row.

### Test B — BuddyBoss accept

B accepts.

Expected:

- BuddyBoss connected.
- Socials A -> B active=1.
- Socials B -> A active=1.
- no Streams friendship.

### Test C — BuddyBoss withdraw

Create a new request and withdraw it.

Expected:

- BuddyBoss request gone.
- Socials pending row removed.
- independent ordinary follow rows, if present, are preserved where representable.

### Test D — BuddyBoss unfriend from either side

Expected:

- BuddyBoss connection removed.
- Socials connection projection removed.
- independent ordinary follows preserved.

### Test E — BuddyBoss follow

A follows B.

Expected:

- BuddyBoss A follows B.
- Streams A follows B.
- Socials is not changed by the BuddyBoss follow projection.

### Test F — Streams follow

A follows B in Streams.

Expected:

- Streams A follows B immediately.
- WordPress A follows B.
- WordPress hook projects the follow back to Streams idempotently.
- no Socials friend request.

### Test G — Socials Add Friend

A adds B.

Expected:

- Socials pending.
- WordPress pending connection.
- no Streams friendship/friend request.

### Test H — Socials approve

B approves A.

Expected:

- WordPress connected.
- Socials reciprocal active friend rows.
- no Streams friendship.

### Test I — Block from BuddyBoss

A blocks B.

Expected:

- BuddyBoss connection removed.
- both BuddyBoss follows removed.
- Streams block A -> B.
- Streams both follow directions removed.
- Socials block A -> B.
- Socials relationship rows removed.
- no automatic restoration after unblock.

### Test J — Unblock

A unblocks B.

Expected:

- BuddyBoss block removed.
- Streams block removed.
- Socials block removed.
- no friendship/follow is automatically restored.

### Test K — Block from Streams

A blocks B in Streams.

Expected:

- WordPress A blocks B.
- WordPress destructive block enforcement runs.
- Socials A blocks B.

### Test L — Block from Socials

A blocks B in Socials.

Expected:

- WordPress A blocks B.
- Streams A blocks B.

## 11. Rollback

Before installation:

1. Back up the current MU plugin.
2. Back up the WordPress database tables:
   - `wp_bzj_relationships`
   - `wp_bzj_relationship_events`
3. Back up `/data/logs`.

If v8.4.1 must be rolled back, disable it and restore the known-good v8.1.0 file. Do not leave both versions active.

## 12. Important implementation principle

Do not solve a failed synchronization by adding another independent synchronization hook.

The control plane is:

External action -> signed command -> BuddyBoss native operation -> BuddyBoss hook -> canonical ledger -> external projections.

BuddyBoss-origin actions go directly:

BuddyBoss native action -> canonical ledger -> external projections.

This keeps BuddyBoss as the source of truth and prevents synchronization loops.
