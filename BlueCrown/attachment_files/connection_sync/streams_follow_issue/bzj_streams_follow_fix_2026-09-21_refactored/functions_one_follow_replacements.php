<?php
/**
 * Buzzjuice Streams - Follow relationship replacements
 *
 * PURPOSE
 * -------
 * Replace the ACTIVE implementations of the following functions in:
 *   streams/assets/includes/functions_one.php
 *
 *   Wo_IsFollowing()
 *   Wo_RegisterFollow()
 *   Wo_IsFollowRequested()
 *   Wo_DeleteFollow()
 *
 * IMPORTANT
 * ---------
 * Keep only ONE executable definition of each function.
 * The old Wo_RegisterFollow/Wo_DeleteFollow implementations must not remain
 * executable elsewhere in the loaded request path.
 *
 * Buzzjuice Streams uses:
 *   FOLLOW    -> Wo_Followers.active = 1
 *   FOLLOWING -> Wo_Followers row exists with active = 1
 *   UNFOLLOW  -> all matching Wo_Followers rows are deleted
 *
 * There is intentionally NO Requested state in this implementation.
 */

if (!function_exists('bzj_follow_log')) {
    function bzj_follow_log($event, $context = array())
    {
        $base = dirname(__DIR__, 3) . '/data.logs/follow';
        if (!is_dir($base)) {
            @mkdir($base, 0755, true);
        }

        $request_id = '';
        if (!empty($_SERVER['HTTP_X_REQUEST_ID'])) {
            $request_id = preg_replace('/[^A-Za-z0-9._-]/', '', (string)$_SERVER['HTTP_X_REQUEST_ID']);
        }
        if ($request_id === '') {
            $request_id = bin2hex(random_bytes(8));
        }

        $record = array(
            'event' => (string)$event,
            'request_id' => $request_id,
            'timestamp_utc' => gmdate('c'),
            'method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '',
            'uri' => isset($_SERVER['REQUEST_URI']) ? (string)$_SERVER['REQUEST_URI'] : '',
            'user_id' => isset($GLOBALS['wo']['user']['user_id']) ? (int)$GLOBALS['wo']['user']['user_id'] : 0,
            'context' => is_array($context) ? $context : array('value' => $context),
        );

        @file_put_contents(
            $base . '/follow-' . gmdate('Y-m-d') . '.log',
            json_encode($record, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );

        return $request_id;
    }
}

if (!function_exists('bzj_follow_normalize_id')) {
    function bzj_follow_normalize_id($value)
    {
        if (is_array($value) || is_object($value)) {
            return 0;
        }

        $value = trim((string)$value);
        if ($value === '' || !ctype_digit($value)) {
            return 0;
        }

        $id = (int)$value;
        return ($id > 0) ? $id : 0;
    }
}

if (!function_exists('bzj_follow_relationship_state')) {
    function bzj_follow_relationship_state($following_id, $follower_id)
    {
        global $sqlConnect;

        $following_id = bzj_follow_normalize_id($following_id);
        $follower_id  = bzj_follow_normalize_id($follower_id);

        if (!$following_id || !$follower_id || !$sqlConnect) {
            return array(
                'following' => false,
                'requested' => false,
                'active_count' => 0,
                'pending_count' => 0,
                'total_count' => 0,
                'rows' => array(),
                'db_error' => 'invalid_arguments_or_connection'
            );
        }

        $rows = array();
        $sql = "SELECT `id`,`following_id`,`follower_id`,`active`,`notify`,`time`
                FROM " . T_FOLLOWERS . "
                WHERE `following_id` = {$following_id}
                  AND `follower_id` = {$follower_id}
                ORDER BY `id` DESC";

        $query = mysqli_query($sqlConnect, $sql);
        if (!$query) {
            bzj_follow_log('relationship_state_query_failed', array(
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'mysql_error' => mysqli_error($sqlConnect),
                'sql' => $sql
            ));
            return array(
                'following' => false,
                'requested' => false,
                'active_count' => 0,
                'pending_count' => 0,
                'total_count' => 0,
                'rows' => array(),
                'db_error' => mysqli_error($sqlConnect)
            );
        }

        $active_count = 0;
        $pending_count = 0;

        while ($row = mysqli_fetch_assoc($query)) {
            $row['id'] = (int)$row['id'];
            $row['following_id'] = (int)$row['following_id'];
            $row['follower_id'] = (int)$row['follower_id'];
            $row['active'] = (int)$row['active'];
            $row['notify'] = isset($row['notify']) ? (int)$row['notify'] : 0;
            $row['time'] = isset($row['time']) ? (int)$row['time'] : 0;
            $rows[] = $row;

            if ($row['active'] === 1) {
                $active_count++;
            } else {
                $pending_count++;
            }
        }

        mysqli_free_result($query);

        return array(
            'following' => ($active_count > 0),
            'requested' => (!$active_count && $pending_count > 0),
            'active_count' => $active_count,
            'pending_count' => $pending_count,
            'total_count' => count($rows),
            'rows' => $rows,
            'db_error' => null
        );
    }
}

function Wo_IsFollowing($following_id, $user_id = 0)
{
    global $sqlConnect, $wo;

    if (empty($wo['loggedin'])) {
        return false;
    }

    $following_id = bzj_follow_normalize_id($following_id);

    if (!$user_id || !is_numeric($user_id) || (int)$user_id < 1) {
        $user_id = isset($wo['user']['user_id']) ? (int)$wo['user']['user_id'] : 0;
    }

    $user_id = bzj_follow_normalize_id($user_id);

    if (!$following_id || !$user_id || !$sqlConnect) {
        return false;
    }

    $following_id_sql = (int)$following_id;
    $user_id_sql = (int)$user_id;

    $sql = "SELECT `id`
            FROM " . T_FOLLOWERS . "
            WHERE `following_id` = {$following_id_sql}
              AND `follower_id` = {$user_id_sql}
              AND `active` = 1
            LIMIT 1";

    $query = mysqli_query($sqlConnect, $sql);

    if (!$query) {
        bzj_follow_log('is_following_query_failed', array(
            'following_id' => $following_id,
            'follower_id' => $user_id,
            'mysql_error' => mysqli_error($sqlConnect)
        ));
        return false;
    }

    $result = (mysqli_num_rows($query) > 0);
    mysqli_free_result($query);

    return $result;
}

function Wo_IsFollowRequested($following_id = 0, $follower_id = 0)
{
    global $sqlConnect, $wo;

    if (empty($wo['loggedin'])) {
        return false;
    }

    $following_id = bzj_follow_normalize_id($following_id);

    if (!$follower_id || !is_numeric($follower_id) || (int)$follower_id < 1) {
        $follower_id = isset($wo['user']['user_id']) ? (int)$wo['user']['user_id'] : 0;
    }

    $follower_id = bzj_follow_normalize_id($follower_id);

    if (!$following_id || !$follower_id || !$sqlConnect) {
        return false;
    }

    $sql = "SELECT `id`
            FROM " . T_FOLLOWERS . "
            WHERE `following_id` = " . (int)$following_id . "
              AND `follower_id` = " . (int)$follower_id . "
              AND `active` = 0
            LIMIT 1";

    $query = mysqli_query($sqlConnect, $sql);

    if (!$query) {
        bzj_follow_log('is_follow_requested_query_failed', array(
            'following_id' => $following_id,
            'follower_id' => $follower_id,
            'mysql_error' => mysqli_error($sqlConnect)
        ));
        return false;
    }

    $result = (mysqli_num_rows($query) > 0);
    mysqli_free_result($query);

    return $result;
}

function Wo_RegisterFollow($following_id = 0, $followers_id = 0)
{
    global $wo, $sqlConnect;

    $request_id = bzj_follow_log('register_follow_start', array(
        'following_id_raw' => $following_id,
        'followers_id_raw' => $followers_id
    ));

    if (empty($wo['loggedin'])) {
        bzj_follow_log('register_follow_rejected', array(
            'reason' => 'not_logged_in',
            'request_id' => $request_id
        ));
        return false;
    }

    $following_id = bzj_follow_normalize_id($following_id);

    if (!$following_id) {
        bzj_follow_log('register_follow_rejected', array(
            'reason' => 'invalid_following_id',
            'request_id' => $request_id
        ));
        return false;
    }

    if (!is_array($followers_id)) {
        $followers_id = array($followers_id);
    }

    $overall_success = false;

    foreach ($followers_id as $raw_follower_id) {
        $follower_id = bzj_follow_normalize_id($raw_follower_id);

        if (!$follower_id) {
            bzj_follow_log('register_follow_item_rejected', array(
                'reason' => 'invalid_follower_id',
                'following_id' => $following_id,
                'follower_id_raw' => $raw_follower_id,
                'request_id' => $request_id
            ));
            continue;
        }

        if ($following_id === $follower_id) {
            bzj_follow_log('register_follow_item_rejected', array(
                'reason' => 'self_follow',
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'request_id' => $request_id
            ));
            continue;
        }

        $follower_data = Wo_UserData($follower_id);
        $following_data = Wo_UserData($following_id);

        if (empty($follower_data['user_id']) || empty($following_data['user_id'])) {
            bzj_follow_log('register_follow_item_rejected', array(
                'reason' => 'user_not_found',
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'follower_found' => !empty($follower_data['user_id']),
                'following_found' => !empty($following_data['user_id']),
                'request_id' => $request_id
            ));
            continue;
        }

        /*
         * Preserve blocking as a hard safety boundary.
         * Do not use follow_privacy, confirm_followers or connectivitySystem
         * to create a pending follow. Buzzjuice requires immediate following.
         */
        $blocked = false;

        try {
            $blocked = (bool)Wo_IsBlocked($following_id);
        } catch (Throwable $e) {
            bzj_follow_log('register_follow_block_check_exception', array(
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'exception' => $e->getMessage(),
                'request_id' => $request_id
            ));
        }

        if (!$blocked && function_exists('Wo_IsBlocked')) {
            try {
                $original_user_id = isset($wo['user']['user_id']) ? (int)$wo['user']['user_id'] : 0;
                if ($original_user_id === $follower_id) {
                    $blocked = (bool)Wo_IsBlocked($following_id);
                }
            } catch (Throwable $e) {
                bzj_follow_log('register_follow_second_block_check_exception', array(
                    'exception' => $e->getMessage(),
                    'request_id' => $request_id
                ));
            }
        }

        if ($blocked) {
            bzj_follow_log('register_follow_item_rejected', array(
                'reason' => 'blocked',
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'request_id' => $request_id
            ));
            continue;
        }

        /*
         * Repair the exact relationship atomically at application level:
         * - existing active row(s): keep the newest and remove duplicates
         * - existing pending row: promote it to active
         * - no row: insert active=1
         */
        $state_before = bzj_follow_relationship_state($following_id, $follower_id);

        if (!empty($state_before['db_error'])) {
            bzj_follow_log('register_follow_failed', array(
                'reason' => 'state_read_failed',
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'state_before' => $state_before,
                'request_id' => $request_id
            ));
            continue;
        }

        $rows = $state_before['rows'];

        if (!empty($rows)) {
            $keep_id = (int)$rows[0]['id'];

            $update = mysqli_query(
                $sqlConnect,
                "UPDATE " . T_FOLLOWERS . "
                 SET `active` = 1
                 WHERE `id` = {$keep_id}
                 LIMIT 1"
            );

            if (!$update) {
                bzj_follow_log('register_follow_failed', array(
                    'reason' => 'promote_existing_row_failed',
                    'following_id' => $following_id,
                    'follower_id' => $follower_id,
                    'keep_id' => $keep_id,
                    'mysql_error' => mysqli_error($sqlConnect),
                    'request_id' => $request_id
                ));
                continue;
            }

            foreach ($rows as $row) {
                $row_id = (int)$row['id'];
                if ($row_id === $keep_id) {
                    continue;
                }

                $delete_duplicate = mysqli_query(
                    $sqlConnect,
                    "DELETE FROM " . T_FOLLOWERS . "
                     WHERE `id` = {$row_id}
                     LIMIT 1"
                );

                if (!$delete_duplicate) {
                    bzj_follow_log('duplicate_cleanup_failed', array(
                        'following_id' => $following_id,
                        'follower_id' => $follower_id,
                        'duplicate_id' => $row_id,
                        'mysql_error' => mysqli_error($sqlConnect),
                        'request_id' => $request_id
                    ));
                }
            }
        } else {
            $insert = mysqli_query(
                $sqlConnect,
                "INSERT INTO " . T_FOLLOWERS . "
                 (`following_id`,`follower_id`,`active`)
                 VALUES (" . (int)$following_id . "," . (int)$follower_id . ",1)"
            );

            if (!$insert) {
                bzj_follow_log('register_follow_failed', array(
                    'reason' => 'insert_failed',
                    'following_id' => $following_id,
                    'follower_id' => $follower_id,
                    'mysql_error' => mysqli_error($sqlConnect),
                    'request_id' => $request_id
                ));
                continue;
            }
        }

        $state_after = bzj_follow_relationship_state($following_id, $follower_id);

        if (!$state_after['following'] || $state_after['active_count'] < 1) {
            bzj_follow_log('register_follow_failed', array(
                'reason' => 'post_write_verification_failed',
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'state_before' => $state_before,
                'state_after' => $state_after,
                'request_id' => $request_id
            ));
            continue;
        }

        if ($state_after['pending_count'] > 0 || $state_after['total_count'] > 1) {
            bzj_follow_log('register_follow_inconsistent_after_write', array(
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'state_after' => $state_after,
                'request_id' => $request_id
            ));
        }

        if (function_exists('cache')) {
            @cache($following_id, 'users', 'delete');
            @cache($follower_id, 'users', 'delete');
        }

        /*
         * Notification/activity are secondary. The relationship itself is
         * already verified before these calls are attempted.
         */
        try {
            $notification_data = array(
                'recipient_id' => $following_id,
                'notifier_id' => $follower_id,
                'type' => 'following',
                'url' => 'index.php?link1=timeline&u=' . (isset($follower_data['username']) ? $follower_data['username'] : '')
            );
            Wo_RegisterNotification($notification_data);
        } catch (Throwable $e) {
            bzj_follow_log('follow_notification_failed', array(
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'exception' => $e->getMessage(),
                'request_id' => $request_id
            ));
        }

        try {
            Wo_RegisterActivity(array(
                'user_id' => $follower_id,
                'follow_id' => $following_id,
                'activity_type' => 'following'
            ));
        } catch (Throwable $e) {
            bzj_follow_log('follow_activity_failed', array(
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'exception' => $e->getMessage(),
                'request_id' => $request_id
            ));
        }

        /*
         * WP/BuddyBoss remains the source of truth. Keep the existing
         * connection client if it is available, but do not allow a projection
         * failure to undo a successfully committed Streams follow.
         */
        if (function_exists('bzj_connection_client')) {
            try {
                $sync_result = bzj_connection_client(
                    'streams',
                    'follow',
                    (int)$follower_id,
                    (int)$following_id
                );

                bzj_follow_log('follow_projection_result', array(
                    'following_id' => $following_id,
                    'follower_id' => $follower_id,
                    'sync_result' => $sync_result,
                    'request_id' => $request_id
                ));
            } catch (Throwable $e) {
                bzj_follow_log('follow_projection_exception', array(
                    'following_id' => $following_id,
                    'follower_id' => $follower_id,
                    'exception' => $e->getMessage(),
                    'request_id' => $request_id
                ));
            }
        }

        $overall_success = true;

        bzj_follow_log('register_follow_success', array(
            'following_id' => $following_id,
            'follower_id' => $follower_id,
            'state_before' => $state_before,
            'state_after' => $state_after,
            'request_id' => $request_id
        ));
    }

    return $overall_success;
}

function Wo_DeleteFollow($following_id = 0, $follower_id = 0)
{
    global $wo, $sqlConnect;

    $request_id = bzj_follow_log('delete_follow_start', array(
        'following_id_raw' => $following_id,
        'follower_id_raw' => $follower_id
    ));

    if (empty($wo['loggedin'])) {
        bzj_follow_log('delete_follow_rejected', array(
            'reason' => 'not_logged_in',
            'request_id' => $request_id
        ));
        return false;
    }

    $following_id = bzj_follow_normalize_id($following_id);
    $follower_id = bzj_follow_normalize_id($follower_id);

    if (!$follower_id && !empty($wo['user']['user_id'])) {
        $follower_id = (int)$wo['user']['user_id'];
    }

    if (!$following_id || !$follower_id || !$sqlConnect) {
        bzj_follow_log('delete_follow_rejected', array(
            'reason' => 'invalid_arguments',
            'following_id' => $following_id,
            'follower_id' => $follower_id,
            'request_id' => $request_id
        ));
        return false;
    }

    $before = bzj_follow_relationship_state($following_id, $follower_id);

    $sql = "DELETE FROM " . T_FOLLOWERS . "
            WHERE `following_id` = " . (int)$following_id . "
              AND `follower_id` = " . (int)$follower_id;

    $query = mysqli_query($sqlConnect, $sql);

    if (!$query) {
        bzj_follow_log('delete_follow_failed', array(
            'reason' => 'delete_query_failed',
            'following_id' => $following_id,
            'follower_id' => $follower_id,
            'mysql_error' => mysqli_error($sqlConnect),
            'before' => $before,
            'request_id' => $request_id
        ));
        return false;
    }

    $deleted_rows = mysqli_affected_rows($sqlConnect);
    $after = bzj_follow_relationship_state($following_id, $follower_id);

    if ($after['total_count'] > 0) {
        bzj_follow_log('delete_follow_failed', array(
            'reason' => 'post_delete_verification_failed',
            'following_id' => $following_id,
            'follower_id' => $follower_id,
            'deleted_rows' => $deleted_rows,
            'before' => $before,
            'after' => $after,
            'request_id' => $request_id
        ));
        return false;
    }

    if (function_exists('cache')) {
        @cache($following_id, 'users', 'delete');
        @cache($follower_id, 'users', 'delete');
    }

    try {
        Wo_DeleteSelectedActivity($follower_id, 'following', $following_id);
    } catch (Throwable $e) {
        bzj_follow_log('unfollow_activity_cleanup_failed', array(
            'exception' => $e->getMessage(),
            'request_id' => $request_id
        ));
    }

    if (function_exists('bzj_connection_client')) {
        try {
            $sync_result = bzj_connection_client(
                'streams',
                'unfollow',
                (int)$follower_id,
                (int)$following_id
            );

            bzj_follow_log('unfollow_projection_result', array(
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'sync_result' => $sync_result,
                'request_id' => $request_id
            ));
        } catch (Throwable $e) {
            bzj_follow_log('unfollow_projection_exception', array(
                'following_id' => $following_id,
                'follower_id' => $follower_id,
                'exception' => $e->getMessage(),
                'request_id' => $request_id
            ));
        }
    }

    bzj_follow_log('delete_follow_success', array(
        'following_id' => $following_id,
        'follower_id' => $follower_id,
        'deleted_rows' => $deleted_rows,
        'before' => $before,
        'after' => $after,
        'request_id' => $request_id
    ));

    return true;
}
