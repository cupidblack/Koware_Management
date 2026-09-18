# Surgical patches for 8.6.0

## 1. Streams: force immediate follows
File: `streams/assets/includes/functions_one.php`

Inside `Wo_RegisterFollow()`, after `$active = 1;`, remove the two rules that can set `$active = 0`:

```php
if ($following_data['confirm_followers'] == 1) { $active = 0; }
if ($wo['config']['connectivitySystem'] == 1) { $active = 0; }
```

Replace them with:

```php
// Buzzjuice Streams uses follows only. The WoWonder Friends System is disabled.
// Every follow is therefore immediate and never a pending friend/follow request.
$active = 1;
```

Also replace `Wo_IsFollowRequested()` with:

```php
function Wo_IsFollowRequested($following_id = 0, $follower_id = 0)
{
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) { return false; }
    if (!is_numeric($following_id) || (int)$following_id < 1) { return false; }
    if (!is_numeric($follower_id) || (int)$follower_id < 1) { $follower_id = $wo['user']['user_id']; }
    $following_id = (int) Wo_Secure($following_id);
    $follower_id  = (int) Wo_Secure($follower_id);
    $query = mysqli_query($sqlConnect, "SELECT `id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$follower_id} AND `following_id` = {$following_id} AND `active` = '0' LIMIT 1");
    if ($query && mysqli_num_rows($query) > 0) {
        // Legacy pending rows are normalized to an immediate follow.
        mysqli_query($sqlConnect, "UPDATE " . T_FOLLOWERS . " SET `active` = '1' WHERE `follower_id` = {$follower_id} AND `following_id` = {$following_id} AND `active` = '0'");
        cache($following_id, 'users', 'delete');
        cache($follower_id, 'users', 'delete');
    }
    return false;
}
```

Do not use this function to represent a QuickDate friend request. QuickDate has its own request semantics and is synchronized to BuddyBoss connections.

## 2. Streams follow/unfollow callers
When a Streams follow/unfollow action is performed, call the external client after the native Streams DB operation succeeds:

```php
bzj_connection_client('streams', 'follow', (int)$actor_wp_id, (int)$target_wp_id);
```

or:

```php
bzj_connection_client('streams', 'unfollow', (int)$actor_wp_id, (int)$target_wp_id);
```

Use the existing WP-ID mapping in the site. Do not send WoWonder IDs as actor/target to this endpoint; the client expects external platform IDs. The control plane maps them back to WordPress.

## 3. Streams block/unblock
After `Wo_RegisterBlock()` succeeds:

```php
bzj_connection_client('streams', 'block', $streams_actor_id, $streams_target_id);
```

After `Wo_RemoveBlock()` succeeds:

```php
bzj_connection_client('streams', 'unblock', $streams_actor_id, $streams_target_id);
```

## 4. QuickDate add-friend and follow
After the native QuickDate request row is successfully created, use:

```php
bzj_connection_client('socials', 'connection_request', $qd_actor_id, $qd_target_id);
```

For the QuickDate follow action, use the same operation:

```php
bzj_connection_client('socials', 'follow', $qd_actor_id, $qd_target_id);
```

The control plane deliberately converts a Socials `follow` operation into a BuddyBoss connection request. It does not create a BuddyBoss or Streams follow.

## 5. QuickDate approve
After native approval succeeds:

```php
bzj_connection_client('socials', 'connection_accept', $qd_actor_id, $qd_target_id);
```

The actor/target pair must identify the requester and recipient as they exist in the QuickDate operation. The control plane locates the BuddyBoss friendship row.

## 6. QuickDate reject/cancel/unfriend
Use:

```php
bzj_connection_client('socials', 'connection_reject', $qd_actor_id, $qd_target_id);
```

for a pending request rejection/decline, and:

```php
bzj_connection_client('socials', 'connection_withdraw', $qd_actor_id, $qd_target_id);
```

when the requester cancels it.

For an already accepted QuickDate friendship:

```php
bzj_connection_client('socials', 'connection_remove', $qd_actor_id, $qd_target_id);
```

The control plane then projects the resulting BuddyBoss state back to QuickDate.

## 7. QuickDate block/unblock
After native QuickDate block succeeds:

```php
bzj_connection_client('socials', 'block', $qd_actor_id, $qd_target_id);
```

After native unblock succeeds:

```php
bzj_connection_client('socials', 'unblock', $qd_actor_id, $qd_target_id);
```

## 8. Authentication
The client signs exactly:

`timestamp + "." + raw_json_body`

with HMAC-SHA256 using `BUZZ_SSO_SECRET`, sending:

`X-BZJ-Timestamp`
`X-BZJ-Signature`

## 9. Important placement rule
Do not fire a synchronization request before the native external DB operation has succeeded. The native action must remain the first mutation on the originating platform; the control plane then makes BuddyBoss authoritative and projects the canonical result outward.
