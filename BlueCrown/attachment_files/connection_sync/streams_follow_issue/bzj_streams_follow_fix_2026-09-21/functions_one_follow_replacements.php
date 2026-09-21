<?php
/*
 * BUZZJUICE STREAMS FOLLOW FIX
 *
 * Apply these complete replacements in:
 * streams/assets/includes/functions_one.php
 *
 * 1. Add Buzzjuice_FollowLog() once.
 * 2. Replace Wo_IsFollowing().
 * 3. Replace Wo_IsFollowRequested().
 * 4. Replace Wo_RegisterFollow().
 * 5. Replace Wo_DeleteFollow().
 *
 * IMPORTANT:
 * - Do not leave another active Wo_RegisterFollow() definition later in the
 *   request path.
 * - The existing original Wo_RegisterFollow()/Wo_DeleteFollow() blocks may
 *   remain commented out if desired; only one executable definition must exist.
 */

/* -------------------------------------------------------------------------
 * 1. Centralized diagnostics
 * ---------------------------------------------------------------------- */

if (!function_exists('Buzzjuice_FollowLog')) {
    function Buzzjuice_FollowLog($event, array $context = array())
    {
        $base_dir = dirname(__DIR__, 3) . '/data.logs/follow';

        if (!is_dir($base_dir)) {
            @mkdir($base_dir, 0750, true);
        }

        if (!isset($context['request_id']) || $context['request_id'] === '') {
            $context['request_id'] = !empty($_SERVER['HTTP_X_REQUEST_ID'])
                ? (string) $_SERVER['HTTP_X_REQUEST_ID']
                : bin2hex(function_exists('random_bytes') ? random_bytes(8) : openssl_random_pseudo_bytes(8));
        }

        $context['timestamp_utc'] = gmdate('c');

        $line = json_encode(
            array(
                'event'   => (string) $event,
                'context' => $context,
            ),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        if ($line !== false) {
            @file_put_contents(
                $base_dir . '/follow-' . gmdate('Y-m-d') . '.log',
                $line . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
        }
    }
}

/* -------------------------------------------------------------------------
 * 2. Server-authoritative "Following" check.
 *
 * Any active row means Following. This deliberately uses > 0 rather than
 * COUNT == 1, because duplicate legacy rows must not make a real follow
 * appear to be absent.
 * ---------------------------------------------------------------------- */

function Wo_IsFollowing($following_id, $user_id = 0)
{
    global $sqlConnect, $wo;

    if (empty($wo['loggedin']) || $wo['loggedin'] !== true) {
        return false;
    }

    $following_id = (int) $following_id;

    if ($user_id === 0 || $user_id === null || $user_id === '' ||
        !is_numeric($user_id) || (int) $user_id < 1) {
        $user_id = !empty($wo['user']['user_id']) ? (int) $wo['user']['user_id'] : 0;
    } else {
        $user_id = (int) $user_id;
    }

    if ($following_id < 1 || $user_id < 1) {
        return false;
    }

    $query = mysqli_query(
        $sqlConnect,
        "SELECT 1
         FROM " . T_FOLLOWERS . "
         WHERE `following_id` = {$following_id}
           AND `follower_id` = {$user_id}
           AND `active` = '1'
         LIMIT 1"
    );

    if (!$query) {
        Buzzjuice_FollowLog('is_following_query_failed', array(
            'following_id' => $following_id,
            'follower_id'  => $user_id,
            'mysql_error'  => mysqli_error($sqlConnect),
        ));
        return false;
    }

    return mysqli_num_rows($query) > 0;
}

/* -------------------------------------------------------------------------
 * 3. Explicit pending-state check.
 * ---------------------------------------------------------------------- */

function Wo_IsFollowRequested($following_id = 0, $follower_id = 0)
{
    global $sqlConnect, $wo;

    if (empty($wo['loggedin']) || $wo['loggedin'] !== true) {
        return false;
    }

    $following_id = (int) $following_id;

    if ($follower_id === 0 || $follower_id === null || $follower_id === '' ||
        !is_numeric($follower_id) || (int) $follower_id < 1) {
        $follower_id = !empty($wo['user']['user_id']) ? (int) $wo['user']['user_id'] : 0;
    } else {
        $follower_id = (int) $follower_id;
    }

    if ($following_id < 1 || $follower_id < 1) {
        return false;
    }

    $query = mysqli_query(
        $sqlConnect,
        "SELECT 1
         FROM " . T_FOLLOWERS . "
         WHERE `following_id` = {$following_id}
           AND `follower_id` = {$follower_id}
           AND `active` = '0'
         LIMIT 1"
    );

    if (!$query) {
        Buzzjuice_FollowLog('is_requested_query_failed', array(
            'following_id' => $following_id,
            'follower_id'  => $follower_id,
            'mysql_error'  => mysqli_error($sqlConnect),
        ));
        return false;
    }

    return mysqli_num_rows($query) > 0;
}

/* -------------------------------------------------------------------------
 * 4. Immediate-follow registration.
 *
 * Behaviour:
 * - never creates active=0
 * - converts an existing pending row to active=1
 * - collapses duplicate legacy rows by retaining the newest row
 * - verifies the final database state
 * - invalidates user caches
 * - preserves existing notification/activity behaviour
 * ---------------------------------------------------------------------- */

function Wo_RegisterFollow($following_id = 0, $followers_id = 0)
{
    global $wo, $sqlConnect;

    if (empty($wo['loggedin']) || $wo['loggedin'] !== true) {
        Buzzjuice_FollowLog('register_rejected', array(
            'reason' => 'not_logged_in',
        ));
        return false;
    }

    $following_id = (int) $following_id;

    if ($following_id < 1) {
        Buzzjuice_FollowLog('register_rejected', array(
            'reason'        => 'invalid_following_id',
            'following_id'  => $following_id,
        ));
        return false;
    }

    if (!is_array($followers_id)) {
        $followers_id = array($followers_id);
    }

    $overall_success = false;

    foreach ($followers_id as $raw_follower_id) {
        $follower_id = (int) $raw_follower_id;

        if ($follower_id < 1 || $follower_id === $following_id) {
            Buzzjuice_FollowLog('register_rejected', array(
                'reason'       => 'invalid_follower_id',
                'following_id' => $following_id,
                'follower_id'  => $follower_id,
            ));
            continue;
        }

        /* Validate both users directly. */
        $users_table = defined('T_USERS') ? T_USERS : 'Wo_Users';

        $user_query = mysqli_query(
            $sqlConnect,
            "SELECT `user_id`, `active`, `username`
             FROM {$users_table}
             WHERE `user_id` IN ({$following_id}, {$follower_id})"
        );

        if (!$user_query) {
            Buzzjuice_FollowLog('register_failed', array(
                'reason'        => 'user_validation_query_failed',
                'following_id'  => $following_id,
                'follower_id'   => $follower_id,
                'mysql_error'   => mysqli_error($sqlConnect),
            ));
            continue;
        }

        $valid_users = array();

        while ($row = mysqli_fetch_assoc($user_query)) {
            $valid_users[(int) $row['user_id']] = $row;
        }

        if (
            empty($valid_users[$following_id]) ||
            empty($valid_users[$follower_id]) ||
            (int) $valid_users[$following_id]['active'] !== 1 ||
            (int) $valid_users[$follower_id]['active'] !== 1
        ) {
            Buzzjuice_FollowLog('register_rejected', array(
                'reason'        => 'user_missing_or_inactive',
                'following_id'  => $following_id,
                'follower_id'   => $follower_id,
                'users_found'   => array_keys($valid_users),
            ));
            continue;
        }

        /* Respect an actual block, but never use follow privacy/confirmation
         * to create a pending follow. */
        if (function_exists('Wo_IsBlocked') && Wo_IsBlocked($following_id)) {
            Buzzjuice_FollowLog('register_rejected', array(
                'reason'        => 'blocked',
                'following_id'  => $following_id,
                'follower_id'   => $follower_id,
            ));
            continue;
        }

        $followers_table = defined('T_FOLLOWERS') ? T_FOLLOWERS : 'Wo_Followers';

        /*
         * Read all legacy rows, newest first. A single relationship should be
         * represented by one row. If duplicates exist, retain the newest row.
         */
        $existing_query = mysqli_query(
            $sqlConnect,
            "SELECT `id`, `active`
             FROM {$followers_table}
             WHERE `following_id` = {$following_id}
               AND `follower_id` = {$follower_id}
             ORDER BY `id` DESC"
        );

        if (!$existing_query) {
            Buzzjuice_FollowLog('register_failed', array(
                'reason'        => 'existing_relationship_query_failed',
                'following_id'  => $following_id,
                'follower_id'   => $follower_id,
                'mysql_error'   => mysqli_error($sqlConnect),
            ));
            continue;
        }

        $rows = array();

        while ($row = mysqli_fetch_assoc($existing_query)) {
            $rows[] = array(
                'id'     => (int) $row['id'],
                'active' => (int) $row['active'],
            );
        }

        $relationship_id = 0;

        if (!empty($rows)) {
            $relationship_id = $rows[0]['id'];

            /* Promote the retained row. */
            $updated = mysqli_query(
                $sqlConnect,
                "UPDATE {$followers_table}
                 SET `active` = '1'
                 WHERE `id` = {$relationship_id}"
            );

            if (!$updated) {
                Buzzjuice_FollowLog('register_failed', array(
                    'reason'          => 'promote_existing_failed',
                    'following_id'    => $following_id,
                    'follower_id'     => $follower_id,
                    'relationship_id' => $relationship_id,
                    'mysql_error'     => mysqli_error($sqlConnect),
                ));
                continue;
            }

            /* Remove older duplicate rows, if any. */
            if (count($rows) > 1) {
                $duplicate_ids = array();

                foreach (array_slice($rows, 1) as $duplicate) {
                    $duplicate_ids[] = (int) $duplicate['id'];
                }

                if (!empty($duplicate_ids)) {
                    $delete_duplicates = mysqli_query(
                        $sqlConnect,
                        "DELETE FROM {$followers_table}
                         WHERE `id` IN (" . implode(',', $duplicate_ids) . ")"
                    );

                    if (!$delete_duplicates) {
                        /* The follow itself is still valid. Log the cleanup
                         * failure rather than falsely reporting failure. */
                        Buzzjuice_FollowLog('duplicate_cleanup_failed', array(
                            'following_id'  => $following_id,
                            'follower_id'   => $follower_id,
                            'retained_id'   => $relationship_id,
                            'duplicate_ids' => $duplicate_ids,
                            'mysql_error'   => mysqli_error($sqlConnect),
                        ));
                    } else {
                        Buzzjuice_FollowLog('duplicate_rows_removed', array(
                            'following_id'  => $following_id,
                            'follower_id'   => $follower_id,
                            'retained_id'   => $relationship_id,
                            'removed_ids'   => $duplicate_ids,
                        ));
                    }
                }
            }

            Buzzjuice_FollowLog('existing_relationship_activated', array(
                'following_id'    => $following_id,
                'follower_id'     => $follower_id,
                'relationship_id' => $relationship_id,
                'previous_active' => $rows[0]['active'],
            ));
        } else {
            /*
             * Explicitly write active=1. confirm_followers,
             * connectivitySystem and follow_privacy are not allowed to
             * convert this into a pending row in Buzzjuice immediate-follow
             * mode.
             */
            $inserted = mysqli_query(
                $sqlConnect,
                "INSERT INTO {$followers_table}
                 (`following_id`, `follower_id`, `active`)
                 VALUES ({$following_id}, {$follower_id}, '1')"
            );

            if (!$inserted) {
                Buzzjuice_FollowLog('register_failed', array(
                    'reason'        => 'insert_failed',
                    'following_id'  => $following_id,
                    'follower_id'   => $follower_id,
                    'mysql_error'   => mysqli_error($sqlConnect),
                ));
                continue;
            }

            $relationship_id = (int) mysqli_insert_id($sqlConnect);

            Buzzjuice_FollowLog('new_relationship_inserted', array(
                'following_id'    => $following_id,
                'follower_id'     => $follower_id,
                'relationship_id' => $relationship_id,
                'active'          => 1,
            ));
        }

        /* Verify immediately from the database. */
        $verify = mysqli_query(
            $sqlConnect,
            "SELECT `id`, `active`
             FROM {$followers_table}
             WHERE `following_id` = {$following_id}
               AND `follower_id` = {$follower_id}
               AND `active` = '1'
             ORDER BY `id` DESC
             LIMIT 1"
        );

        if (!$verify || mysqli_num_rows($verify) < 1) {
            Buzzjuice_FollowLog('register_verification_failed', array(
                'following_id'    => $following_id,
                'follower_id'     => $follower_id,
                'relationship_id' => $relationship_id,
                'mysql_error'     => mysqli_error($sqlConnect),
            ));
            continue;
        }

        $verified_row = mysqli_fetch_assoc($verify);
        $relationship_id = (int) $verified_row['id'];

        /* Invalidate both user caches. */
        if (function_exists('cache')) {
            @cache($following_id, 'users', 'delete');
            @cache($follower_id, 'users', 'delete');
        }

        /*
         * Re-fetch the follower data after cache invalidation is not needed
         * for identity, but WoWonder's notification/activity functions expect
         * the normal user structure.
         */
        $follower_data = Wo_UserData($follower_id, false);

        if (empty($follower_data['user_id'])) {
            Buzzjuice_FollowLog('notification_skipped', array(
                'reason'          => 'follower_data_unavailable',
                'following_id'    => $following_id,
                'follower_id'     => $follower_id,
                'relationship_id' => $relationship_id,
            ));
        } else {
            $notification_data = array(
                'recipient_id' => $following_id,
                'notifier_id'  => $follower_id,
                'type'         => 'following',
                'url'          => 'index.php?link1=timeline&u=' . $follower_data['username'],
            );

            try {
                Wo_RegisterNotification($notification_data);
            } catch (Throwable $e) {
                Buzzjuice_FollowLog('notification_exception', array(
                    'following_id'    => $following_id,
                    'follower_id'     => $follower_id,
                    'relationship_id' => $relationship_id,
                    'error'           => $e->getMessage(),
                ));
            }

            $activity_data = array(
                'user_id'       => $follower_id,
                'follow_id'     => $following_id,
                'activity_type' => 'following',
            );

            try {
                Wo_RegisterActivity($activity_data);
            } catch (Throwable $e) {
                Buzzjuice_FollowLog('activity_exception', array(
                    'following_id'    => $following_id,
                    'follower_id'     => $follower_id,
                    'relationship_id' => $relationship_id,
                    'error'           => $e->getMessage(),
                ));
            }
        }

        $overall_success = true;

        Buzzjuice_FollowLog('register_complete', array(
            'following_id'    => $following_id,
            'follower_id'     => $follower_id,
            'relationship_id' => $relationship_id,
            'state'            => 'following',
            'active'           => 1,
        ));
    }

    return $overall_success;
}

/* -------------------------------------------------------------------------
 * 5. Immediate-follow deletion.
 *
 * Deletes every row for the exact pair so legacy duplicates cannot leave the
 * relationship appearing active after an unfollow.
 * ---------------------------------------------------------------------- */

function Wo_DeleteFollow($following_id = 0, $follower_id = 0)
{
    global $wo, $sqlConnect;

    if (empty($wo['loggedin']) || $wo['loggedin'] !== true) {
        Buzzjuice_FollowLog('delete_rejected', array(
            'reason' => 'not_logged_in',
        ));
        return false;
    }

    $following_id = (int) $following_id;
    $follower_id  = (int) $follower_id;

    if ($following_id < 1 || $follower_id < 1 || $following_id === $follower_id) {
        Buzzjuice_FollowLog('delete_rejected', array(
            'reason'        => 'invalid_relationship',
            'following_id'  => $following_id,
            'follower_id'   => $follower_id,
        ));
        return false;
    }

    $followers_table = defined('T_FOLLOWERS') ? T_FOLLOWERS : 'Wo_Followers';

    /*
     * Direct DELETE is intentional. It handles active rows and old pending
     * rows without depending on Wo_IsFollowing()/Wo_IsFollowRequested().
     */
    $query = mysqli_query(
        $sqlConnect,
        "DELETE FROM {$followers_table}
         WHERE `following_id` = {$following_id}
           AND `follower_id` = {$follower_id}"
    );

    if (!$query) {
        Buzzjuice_FollowLog('delete_failed', array(
            'following_id' => $following_id,
            'follower_id'  => $follower_id,
            'mysql_error'  => mysqli_error($sqlConnect),
        ));
        return false;
    }

    $deleted_rows = mysqli_affected_rows($sqlConnect);

    if ($deleted_rows < 1) {
        Buzzjuice_FollowLog('delete_no_relationship', array(
            'following_id' => $following_id,
            'follower_id'  => $follower_id,
        ));
        return false;
    }

    /*
     * Preserve WoWonder's existing reciprocal cleanup for connectivity mode.
     */
    if (!empty($wo['config']['connectivitySystem']) && (int) $wo['config']['connectivitySystem'] === 1) {
        mysqli_query(
            $sqlConnect,
            "DELETE FROM {$followers_table}
             WHERE `follower_id` = {$following_id}
               AND `following_id` = {$follower_id}"
        );

        if (function_exists('Wo_DeleteSelectedActivity')) {
            Wo_DeleteSelectedActivity($follower_id, 'friend', $following_id);
            Wo_DeleteSelectedActivity($following_id, 'friend', $follower_id);
        }
    } else {
        if (function_exists('Wo_DeleteSelectedActivity')) {
            Wo_DeleteSelectedActivity($follower_id, 'following', $following_id);
        }
    }

    /*
     * Streams is the origin of the unfollow action. The synchronization
     * client remains best-effort: failure to project to the canonical
     * WordPress control plane must not make a successful local delete look
     * like a failed unfollow.
     */
    if (function_exists('bzj_connection_client')) {
        try {
            bzj_connection_client(
                'streams',
                'unfollow',
                (int) $follower_id,
                (int) $following_id
            );
        } catch (Throwable $e) {
            Buzzjuice_FollowLog('sync_exception', array(
                'operation'    => 'unfollow',
                'following_id' => $following_id,
                'follower_id'  => $follower_id,
                'error'        => $e->getMessage(),
            ));
        }
    }

    if (function_exists('cache')) {
        @cache($following_id, 'users', 'delete');
        @cache($follower_id, 'users', 'delete');
    }

    Buzzjuice_FollowLog('delete_complete', array(
        'following_id' => $following_id,
        'follower_id'  => $follower_id,
        'deleted_rows' => $deleted_rows,
        'state'        => 'follow',
    ));

    return true;
}
