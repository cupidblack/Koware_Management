# Buzzjuice Connections Sync v8.5.0 — Surgical Integration Patches

## 1. Shared client loading

Add the following once near the top of each external file that needs synchronization. Do not hard-code a fragile `dirname(..., N)` depth as the only path.

```php
if (!function_exists('bzj_connection_client')) {
    function bzj_connection_client($origin) {
        static $clients = array();

        if (isset($clients[$origin])) {
            return $clients[$origin];
        }

        $candidates = array();

        $env_root = getenv('BUZZJUICE_ROOT');
        if (!empty($env_root)) {
            $candidates[] = rtrim($env_root, '/\\');
        }

        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            $candidates[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
        }

        $candidates[] = dirname(__DIR__, 3);
        $candidates[] = dirname(__DIR__, 4);
        $candidates[] = dirname(__DIR__, 5);
        $candidates[] = dirname(__DIR__, 6);

        $candidates = array_values(array_unique(array_filter($candidates)));

        foreach ($candidates as $root) {
            $file = rtrim($root, '/\\') . '/shared/bzj-connection-client.php';
            if (is_file($file)) {
                require_once $file;
                $clients[$origin] = new BZJ_Connection_Client($origin);
                return $clients[$origin];
            }
        }

        throw new RuntimeException(
            'Buzzjuice sync client not found. Platform=' . $origin .
            '; source=' . __FILE__ .
            '; candidates=' . implode('|', $candidates)
        );
    }
}
```

If a file already has a helper with the same purpose, extend the existing helper instead of defining a duplicate function.

---

# 2. Streams — `streams/assets/includes/functions_one.php`

## 2.1 Replace only `Wo_RegisterFollow()`

The important correction is: **do not modify `Wo_IsFollowRequested()` to perform an UPDATE.**

The current WoWonder function is a predicate. Making it mutate the database causes callers to receive misleading results and can create duplicate inserts. The integration requirement is better enforced at the write point: `Wo_RegisterFollow()` must always create/activate a real follow with `active = 1`.

Replace the body of `Wo_RegisterFollow()` with this complete function:

```php
function Wo_RegisterFollow($following_id = 0, $followers_id = 0)
{
    global $wo, $sqlConnect;

    if ($wo['loggedin'] == false) {
        return false;
    }

    if (!isset($following_id) || empty($following_id) || !is_numeric($following_id) || $following_id < 1) {
        return false;
    }

    if (!is_array($followers_id)) {
        $followers_id = array($followers_id);
    }

    $overall_success = true;

    foreach ($followers_id as $follower_id) {
        if (!isset($follower_id) || empty($follower_id) || !is_numeric($follower_id) || $follower_id < 1) {
            $overall_success = false;
            continue;
        }

        if (Wo_IsBlocked($following_id)) {
            $overall_success = false;
            continue;
        }

        $following_id = (int) Wo_Secure($following_id);
        $follower_id  = (int) Wo_Secure($follower_id);

        if (Wo_IsFollowing($following_id, $follower_id) === true) {
            // Already active: treat as an idempotent success.
            continue;
        }

        $follower_data  = Wo_UserData($follower_id);
        $following_data = Wo_UserData($following_id);

        if (empty($follower_data['user_id']) || empty($following_data['user_id'])) {
            $overall_success = false;
            continue;
        }

        /*
         * Buzzjuice Streams has the Friends System disabled.
         * Therefore a follow is always immediate.
         *
         * Do NOT apply:
         *   follow_privacy
         *   confirm_followers
         *   connectivitySystem
         * to this integration path.
         */
        $active = 1;

        $existing_id = 0;
        $existing_query = mysqli_query(
            $sqlConnect,
            "SELECT `id`
             FROM " . T_FOLLOWERS . "
             WHERE `following_id` = {$following_id}
               AND `follower_id` = {$follower_id}
             LIMIT 1"
        );

        if ($existing_query && ($existing_row = mysqli_fetch_assoc($existing_query))) {
            $existing_id = (int) $existing_row['id'];
        }

        if ($existing_id > 0) {
            $query = mysqli_query(
                $sqlConnect,
                "UPDATE " . T_FOLLOWERS . "
                 SET `active` = 1
                 WHERE `id` = {$existing_id}"
            );
        } else {
            $query = mysqli_query(
                $sqlConnect,
                "INSERT INTO " . T_FOLLOWERS . "
                 (`following_id`,`follower_id`,`active`)
                 VALUES ({$following_id},{$follower_id},1)"
            );
        }

        if (!$query) {
            $overall_success = false;
            if (function_exists('error_log')) {
                error_log(
                    '[BZJ Streams Sync] Wo_RegisterFollow DB write failed: ' .
                    mysqli_error($sqlConnect)
                );
            }
            continue;
        }

        cache($following_id, 'users', 'delete');
        cache($follower_id, 'users', 'delete');

        /*
         * Read-back verification is mandatory before notifying WordPress.
         */
        $verify = mysqli_query(
            $sqlConnect,
            "SELECT `id`
             FROM " . T_FOLLOWERS . "
             WHERE `following_id` = {$following_id}
               AND `follower_id` = {$follower_id}
               AND `active` = 1
             LIMIT 1"
        );

        if (!$verify || mysqli_num_rows($verify) < 1) {
            $overall_success = false;
            if (function_exists('error_log')) {
                error_log('[BZJ Streams Sync] Wo_RegisterFollow read-back verification failed.');
            }
            continue;
        }

        /*
         * Keep the existing WoWonder notification/activity behavior.
         */
        $notification_data = array(
            'recipient_id' => $following_id,
            'notifier_id'  => $follower_id,
            'type'         => 'following',
            'url'          => 'index.php?link1=timeline&u=' . $follower_data['username']
        );
        Wo_RegisterNotification($notification_data);

        $activity_data = array(
            'user_id' => $follower_id,
            'follow_id' => $following_id,
            'activity_type' => 'following'
        );
        Wo_RegisterActivity($activity_data);

        /*
         * Only after the local write and read-back verification succeeds
         * notify the BuddyBoss control plane.
         *
         * The external IDs are WoWonder IDs, never WordPress IDs.
         */
        try {
            $client = bzj_connection_client('streams');
            $sync = $client->send(
                'follow',
                $follower_id,
                $following_id
            );

            if (empty($sync['success'])) {
                /*
                 * The local action remains successful.
                 * The shared client has placed the event into its durable
                 * outbox for retry with the same event UUID.
                 */
                error_log(
                    '[BZJ Streams Sync] Follow queued for retry: ' .
                    wp_json_encode($sync)
                );
            }
        } catch (Throwable $e) {
            /*
             * Never undo a successful user action because WordPress is
             * temporarily unavailable.
             */
            error_log(
                '[BZJ Streams Sync] Follow synchronization exception: ' .
                $e->getMessage()
            );
        }
    }

    return $overall_success;
}
```

### Important

Leave `Wo_IsFollowRequested()` read-only.

Do **not** insert the proposed `UPDATE ... active = 1` into it.

---

# 3. Streams — `streams/themes/sunshine/layout/extra_js/content.phtml`

## Replace only `Wo_DeleteFollow()`

Use:

```php
function Wo_DeleteFollow($following_id = 0, $follower_id = 0)
{
    global $wo, $sqlConnect;

    if ($wo['loggedin'] == false) {
        return false;
    }

    if (!isset($following_id) || empty($following_id) || !is_numeric($following_id) || $following_id < 1) {
        return false;
    }

    if (!isset($follower_id) || empty($follower_id) || !is_numeric($follower_id) || $follower_id < 1) {
        return false;
    }

    $following_id = (int) Wo_Secure($following_id);
    $follower_id  = (int) Wo_Secure($follower_id);

    $query = mysqli_query(
        $sqlConnect,
        "DELETE FROM " . T_FOLLOWERS . "
         WHERE `following_id` = {$following_id}
           AND `follower_id` = {$follower_id}"
    );

    if (!$query) {
        error_log(
            '[BZJ Streams Sync] Wo_DeleteFollow DB error: ' .
            mysqli_error($sqlConnect)
        );
        return false;
    }

    /*
     * A delete is idempotent. Verify that no directional row remains.
     */
    $verify = mysqli_query(
        $sqlConnect,
        "SELECT `id`
         FROM " . T_FOLLOWERS . "
         WHERE `following_id` = {$following_id}
           AND `follower_id` = {$follower_id}
         LIMIT 1"
    );

    if ($verify && mysqli_num_rows($verify) > 0) {
        error_log('[BZJ Streams Sync] Wo_DeleteFollow verification failed.');
        return false;
    }

    cache($following_id, 'users', 'delete');
    cache($follower_id, 'users', 'delete');

    if (function_exists('Wo_DeleteSelectedActivity')) {
        Wo_DeleteSelectedActivity($follower_id, 'following', $following_id);
    }

    /*
     * Local deletion succeeded. Now tell BuddyBoss.
     */
    try {
        $client = bzj_connection_client('streams');
        $sync = $client->send(
            'unfollow',
            $follower_id,
            $following_id
        );

        if (empty($sync['success'])) {
            error_log(
                '[BZJ Streams Sync] Unfollow queued for retry: ' .
                wp_json_encode($sync)
            );
        }
    } catch (Throwable $e) {
        error_log(
            '[BZJ Streams Sync] Unfollow synchronization exception: ' .
            $e->getMessage()
        );
    }

    return true;
}
```

---

# 4. Streams block functions — `streams/assets/includes/functions_one.php`

## 4.1 Modify `Wo_RegisterBlock()`

After the successful INSERT and before returning `true`, add:

```php
if ($query) {
    $verify = mysqli_query(
        $sqlConnect,
        "SELECT `id`
         FROM " . T_BLOCKS . "
         WHERE `blocker` = {$logged_user_id}
           AND `blocked` = {$user_id}
         LIMIT 1"
    );

    if (!$verify || mysqli_num_rows($verify) < 1) {
        error_log('[BZJ Streams Sync] Block read-back verification failed.');
        return false;
    }

    try {
        $client = bzj_connection_client('streams');
        $sync = $client->send('block', $logged_user_id, $user_id);

        if (empty($sync['success'])) {
            error_log(
                '[BZJ Streams Sync] Block queued for retry: ' .
                wp_json_encode($sync)
            );
        }
    } catch (Throwable $e) {
        error_log(
            '[BZJ Streams Sync] Block synchronization exception: ' .
            $e->getMessage()
        );
    }

    return true;
}
```

Keep the existing input validation and INSERT.

## 4.2 Modify `Wo_RemoveBlock()`

After the successful DELETE, add:

```php
if ($query) {
    $verify = mysqli_query(
        $sqlConnect,
        "SELECT `id`
         FROM " . T_BLOCKS . "
         WHERE `blocker` = {$logged_user_id}
           AND `blocked` = {$user_id}
         LIMIT 1"
    );

    if ($verify && mysqli_num_rows($verify) > 0) {
        error_log('[BZJ Streams Sync] Unblock read-back verification failed.');
        return false;
    }

    try {
        $client = bzj_connection_client('streams');
        $sync = $client->send('unblock', $logged_user_id, $user_id);

        if (empty($sync['success'])) {
            error_log(
                '[BZJ Streams Sync] Unblock queued for retry: ' .
                wp_json_encode($sync)
            );
        }
    } catch (Throwable $e) {
        error_log(
            '[BZJ Streams Sync] Unblock synchronization exception: ' .
            $e->getMessage()
        );
    }

    return true;
}
```

---

# 5. QuickDate — `social/requests/ajax/profile.php`

The existing `add_friend()` is semantically a **BuddyBoss connection request**, not an ordinary follow.

After:

```php
if (Wo_RegisterFollow($to, (int) self::ActiveUser()->id)) {
```

change the successful branch to:

```php
if (Wo_RegisterFollow($to, (int) self::ActiveUser()->id)) {

    /*
     * QuickDate Add Friend is a BuddyBoss connection request.
     * It is NOT a BuddyBoss/Streams ordinary follow.
     */
    try {
        $client = bzj_connection_client('socials');

        $sync = $client->send(
            'connection_request',
            (int) self::ActiveUser()->id,
            $to
        );

        if (empty($sync['success'])) {
            error_log(
                '[BZJ Socials Sync] Add Friend connection request queued: ' .
                json_encode($sync)
            );
        }
    } catch (Throwable $e) {
        error_log(
            '[BZJ Socials Sync] Add Friend synchronization exception: ' .
            $e->getMessage()
        );
    }

    return array(
        'status' => 200,
        'message' => __('Success'),
        'ajaxRedirect' => '/@'.$uname
    );
}
```

The local QuickDate mutation remains the first operation.

---

# 6. QuickDate reject — `disapprove_friend_request()`

Immediately after the local DELETE succeeds:

```php
if ($query) {

    try {
        $client = bzj_connection_client('socials');

        /*
         * The requester is friend_request_to_userid in this installed
         * QuickDate handler. Confirm this against the request payload before
         * deployment; do not substitute the currently logged-in user blindly.
         */
        $sync = $client->send(
            'connection_reject',
            $friend_request_to_userid,
            $friend_request_userid
        );

        if (empty($sync['success'])) {
            error_log(
                '[BZJ Socials Sync] Connection rejection queued: ' .
                json_encode($sync)
            );
        }
    } catch (Throwable $e) {
        error_log(
            '[BZJ Socials Sync] Connection rejection synchronization exception: ' .
            $e->getMessage()
        );
    }

    // Existing notification/response code follows.
```

The exact actor/target must represent the **recipient who rejected** and the **original requester**, respectively.

---

# 7. QuickDate accept — `approve_friend_request()`

After this local update succeeds:

```php
$query = mysqli_query(
    $conn,
    "UPDATE `followers`
     SET `active` = '1'
     WHERE `following_id` = {$friend_request_userid}
       AND `follower_id` = {$friend_request_to_userid}
       AND `active` = '0'"
);
```

add:

```php
if ($query) {

    try {
        $client = bzj_connection_client('socials');

        /*
         * The logged-in QuickDate user is the recipient/acceptor.
         * The friend_request_userid is the requester.
         */
        $sync = $client->send(
            'connection_accept',
            (int) self::ActiveUser()->id,
            $friend_request_userid
        );

        if (empty($sync['success'])) {
            error_log(
                '[BZJ Socials Sync] Connection acceptance queued: ' .
                json_encode($sync)
            );
        }
    } catch (Throwable $e) {
        error_log(
            '[BZJ Socials Sync] Connection acceptance synchronization exception: ' .
            $e->getMessage()
        );
    }

    // Existing notification/response code follows.
```

Do not call `bp_start_following()` for this operation.

---

# 8. QuickDate Add Friend withdrawal/cancellation

Where the requester cancels an existing pending QuickDate Add Friend request, delete the local pending row first and then call:

```php
$client = bzj_connection_client('socials');

$sync = $client->send(
    'connection_withdraw',
    $requester_id,
    $recipient_id
);
```

`$requester_id` must always be the original requester.

Do not use the current logged-in user merely because they happen to be the current session user.

---

# 9. QuickDate unfriend

After the accepted QuickDate friendship projection is removed, call:

```php
$client = bzj_connection_client('socials');

$sync = $client->send(
    'connection_remove',
    $actor_id,
    $target_id
);
```

The control plane removes only the BuddyBoss connection. Independent ordinary follows are preserved.

Do not use generic `Wo_DeleteFollow()` as the synchronization trigger.

---

# 10. QuickDate ordinary Follow

This is intentionally a separate relationship type.

**Do not make ordinary QuickDate Follow call `connection_request`.**

After the local ordinary QuickDate follow is successfully inserted/activated:

```php
$client = bzj_connection_client('socials');

$sync = $client->send(
    'follow',
    $quickdate_follower_id,
    $quickdate_following_id
);
```

After the local ordinary QuickDate follow is deleted:

```php
$client = bzj_connection_client('socials');

$sync = $client->send(
    'unfollow',
    $quickdate_follower_id,
    $quickdate_following_id
);
```

This gives:

```text
QuickDate Follow
      ↓
BuddyBoss Follow
      ↓
Streams Follow
```

and never:

```text
QuickDate Follow
      ↓
BuddyBoss Connection
```

This distinction prevents ordinary follows from consuming the BuddyBoss connection-request workflow.

---

# 11. QuickDate blocks — `social/requests/ajax/useractions.php`

After the local block INSERT succeeds:

```php
if ($saved) {

    try {
        $client = bzj_connection_client('socials');

        $sync = $client->send(
            'block',
            (int) self::ActiveUser()->id,
            $userid
        );

        if (empty($sync['success'])) {
            error_log(
                '[BZJ Socials Sync] Block queued: ' .
                json_encode($sync)
            );
        }
    } catch (Throwable $e) {
        error_log(
            '[BZJ Socials Sync] Block synchronization exception: ' .
            $e->getMessage()
        );
    }

    // Existing session/response code.
```

After the local unblock DELETE succeeds:

```php
if ($deleted) {

    try {
        $client = bzj_connection_client('socials');

        $sync = $client->send(
            'unblock',
            $target_id,
            $userid
        );

        if (empty($sync['success'])) {
            error_log(
                '[BZJ Socials Sync] Unblock queued: ' .
                json_encode($sync)
            );
        }
    } catch (Throwable $e) {
        error_log(
            '[BZJ Socials Sync] Unblock synchronization exception: ' .
            $e->getMessage()
        );
    }

    // Existing response code.
```

---

# 12. Do not synchronize from QuickDate `Wo_DeleteFollow()`

The installed QuickDate `followers` table is overloaded:

```text
active = 0 → pending Add Friend
active = 1 → ordinary follow OR accepted friendship
```

Therefore:

```php
Wo_DeleteFollow()
```

cannot determine whether it is deleting:

- an ordinary follow,
- a pending connection request,
- or an accepted connection.

Synchronization must be triggered at the semantic handler:

```text
add_friend()
approve_friend_request()
disapprove_friend_request()
withdraw/cancel handler
ordinary follow handler
ordinary unfollow handler
unfriend handler
block()
unblock()
```

not by the generic database helper.

---

# 13. Blocking semantics

The synchronized block policy is destructive:

```text
A blocks B
│
├─ BuddyBoss block A → B
├─ remove BuddyBoss connection
├─ remove BuddyBoss pending request
├─ remove BuddyBoss A → B follow
├─ remove BuddyBoss B → A follow
├─ Streams block A → B
├─ remove Streams A → B follow
├─ remove Streams B → A follow
├─ Socials block A → B
└─ remove Socials relationship projections
```

Unblocking is non-restorative:

```text
A unblocks B
│
├─ remove Streams A → B block
├─ remove Socials A → B block
└─ do NOT restore old connection/follows
```

---

# 14. What must NOT be changed

Do not:

```text
- write BuddyBoss friendship/follow/moderation tables from Streams
- write BuddyBoss friendship/follow/moderation tables from Socials
- pass WoWonder IDs into BuddyBoss functions
- pass QuickDate IDs into BuddyBoss functions
- use email as the relationship identity
- use Wo_IsFollowRequested() as a mutating function
- synchronize QuickDate Add Friend as a Streams follow
- synchronize ordinary QuickDate Follow as a BuddyBoss connection
- use the WoWonder API endpoints
```

The only external-to-WordPress route is:

```text
external local DB mutation
        ↓
read-back verification
        ↓
signed intent command
        ↓
WordPress ID resolution
        ↓
native BuddyBoss function
        ↓
BuddyBoss lifecycle hook
        ↓
canonical projection
```
