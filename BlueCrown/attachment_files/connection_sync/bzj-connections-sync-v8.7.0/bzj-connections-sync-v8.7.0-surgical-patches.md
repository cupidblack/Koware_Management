# BZJ Connections Sync 8.7.0 — surgical integration patches

## 1. Streams: functions_one.php

Add once near the top of the file, after the normal includes:

```php
require_once __DIR__ . '/../../../shared/bzj-connection-client.php';
```

Replace `Wo_RegisterFollow()` with the existing WoWonder function body, but force `$active = 1` and remove/bypass the `confirm_followers` and `connectivitySystem` assignments that can set it back to 0. Keep its normal notification/activity behavior.

After the successful local INSERT, perform a read-back:

```php
$verify = mysqli_query(
    $sqlConnect,
    "SELECT id FROM " . T_FOLLOWERS .
    " WHERE following_id = {$following_id}
      AND follower_id = {$follower_id}
      AND active = 1 LIMIT 1"
);
if (!$verify || mysqli_num_rows($verify) < 1) {
    return false;
}
```

Then log locally and do not call the WordPress endpoint from the BuddyBoss→Streams path. BuddyBoss already projects the follow into Streams.

### Streams local unfollow

In `Wo_DeleteFollow()`, after the local directional DELETE succeeds, verify that the active row no longer exists, then call:

```php
$sync = bzj_connection_client(
    'streams',
    'unfollow',
    (int) $follower_id,
    (int) $following_id
);
```

The call is a notification to the WordPress control plane; it is not the source of the Streams deletion.

### Streams local block

After the local `T_BLOCKS` INSERT succeeds:

```php
$sync = bzj_connection_client(
    'streams',
    'block',
    (int) $logged_user_id,
    (int) $user_id
);
```

After the local block DELETE succeeds:

```php
$sync = bzj_connection_client(
    'streams',
    'unblock',
    (int) $logged_user_id,
    (int) $user_id
);
```

## 2. Streams: content.phtml

If `Wo_DeleteFollow()` is defined here as well, make it identical to the updated version in `functions_one.php`. Do not maintain two divergent implementations.

## 3. Socials: profile.php

This file is a class (`Profile extends Aj`). Do not paste procedural replacements such as `function add_friend($user_id)`.

Add once near the top:

```php
require_once __DIR__ . '/../../../shared/bzj-connection-client.php';
```

### add_friend()

Keep the existing QuickDate validation and local `Wo_RegisterFollow()` behavior. Immediately after a successful Add Friend request is created, send:

```php
$sync = bzj_connection_client(
    'socials',
    'connection_request',
    (int) self::ActiveUser()->id,
    (int) $to
);
```

The local QuickDate row must be pending (`active = 0`) before this call.

### approve_friend_request()

After the existing successful local UPDATE from `active = 0` to `active = 1`, send:

```php
$sync = bzj_connection_client(
    'socials',
    'connection_accept',
    (int) $friend_request_userid,
    (int) $friend_request_to_userid
);
```

Use the actual QuickDate requester as actor and recipient as target. Do not derive these from the current session alone.

### disapprove_friend_request()

After the existing successful pending-row DELETE, send:

```php
$sync = bzj_connection_client(
    'socials',
    'connection_reject',
    (int) self::ActiveUser()->id,
    (int) $friend_request_userid
);
```

For the requester-cancel path, use `connection_withdraw` with the original requester as actor and recipient as target.

Most importantly, pending cancellation must correspond to:

```sql
DELETE FROM followers
WHERE following_id = <recipient>
  AND follower_id = <requester>
  AND active = 0
```

Never use a generic DELETE without `active = 0` for cancellation.

## 4. Socials: useractions.php

Add the client include once.

After a successful local block INSERT:

```php
bzj_connection_client(
    'socials',
    'block',
    (int) self::ActiveUser()->id,
    (int) $userid
);
```

After a successful local unblock DELETE:

```php
bzj_connection_client(
    'socials',
    'unblock',
    (int) self::ActiveUser()->id,
    (int) $userid
);
```

Preserve QuickDate's existing validation/session-cache behavior.

## 5. Socials: core.php

Do NOT make generic `Wo_DeleteFollow()` a synchronization trigger. QuickDate uses the same `followers` table for ordinary follows and Add Friend relationships.

For the actual accepted-friend removal handler, after the local friend rows are removed, call:

```php
bzj_connection_client(
    'socials',
    'connection_remove',
    (int) $actor_id,
    (int) $target_id
);
```

For ordinary QuickDate unfollow, use:

```php
bzj_connection_client(
    'socials',
    'unfollow',
    (int) $actor_id,
    (int) $target_id
);
```

That maps to BuddyBoss unfollow, NOT connection removal.

## 6. Do not modify Wo_IsFollowRequested()

It must remain read-only. Automatic acceptance is achieved by forcing Streams `Wo_RegisterFollow()` to insert `active = 1`. Writing from a predicate would introduce side effects during status checks.

## 7. Test matrix

1. BB request → QD pending Add Friend.
2. BB requester cancels → only QD `active=0` row disappears.
3. BB recipient rejects → only QD `active=0` row disappears.
4. BB accept → QD accepted friendship.
5. BB remove → QD accepted friendship removed.
6. BB follow → Streams `active=1`.
7. BB unfollow → Streams row removed.
8. BB block → both external block rows plus relationship/follows removed.
9. BB unblock → external block rows removed; relationships are not restored.
10. Streams follow → BB follow.
11. Streams unfollow → BB unfollow.
12. Streams block/unblock → BB + QD block/unblock.
13. Socials Add Friend → BB connection request.
14. Socials accept → BB connection accepted.
15. Socials reject/withdraw → BB request removed.
16. Socials unfriend → BB connection removed.
17. Socials ordinary follow → BB follow.
18. Socials ordinary unfollow → BB unfollow.

For every test inspect `/data/logs/bzj-connections-sync.log` and confirm:
`database`, `table`, `affected_rows`, `verified`, `event_uuid`, and `error`.
