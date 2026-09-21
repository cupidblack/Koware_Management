<?php
/**
 * Buzzjuice Streams - authoritative follow/unfollow endpoint.
 *
 * Behaviour:
 *   Follow     -> creates/promotes active=1 -> returns following
 *   Following  -> deletes relationship      -> returns follow
 *
 * Requested is never returned by this endpoint.
 */

if ($f !== 'follow_user') {
    exit();
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$data = array(
    'status'       => 401,
    'state'        => 'error',
    'following_id' => 0,
    'follower_id'  => 0,
    'active'       => 0,
    'requested'    => 0,
    'relationship_id' => 0,
    'error'        => 'Unauthorized request',
);

if (empty($wo['loggedin']) || $wo['loggedin'] !== true) {
    if (function_exists('Buzzjuice_FollowLog')) {
        Buzzjuice_FollowLog('endpoint_rejected', array(
            'reason' => 'not_logged_in',
        ));
    }

    echo json_encode($data);
    exit();
}

if (!Wo_CheckMainSession($hash_id)) {
    $data['status'] = 403;
    $data['error']  = 'Invalid session';

    if (function_exists('Buzzjuice_FollowLog')) {
        Buzzjuice_FollowLog('endpoint_rejected', array(
            'reason' => 'invalid_main_session',
        ));
    }

    echo json_encode($data);
    exit();
}

$following_id = isset($_GET['following_id'])
    ? (int) $_GET['following_id']
    : 0;

$follower_id = !empty($wo['user']['user_id'])
    ? (int) $wo['user']['user_id']
    : (!empty($wo['user']['id']) ? (int) $wo['user']['id'] : 0);

$data['following_id'] = $following_id;
$data['follower_id']  = $follower_id;

if ($following_id < 1 || $follower_id < 1 || $following_id === $follower_id) {
    $data['status'] = 400;
    $data['error']  = 'Invalid follow relationship';

    if (function_exists('Buzzjuice_FollowLog')) {
        Buzzjuice_FollowLog('endpoint_rejected', array(
            'reason'        => 'invalid_ids',
            'following_id'  => $following_id,
            'follower_id'   => $follower_id,
        ));
    }

    echo json_encode($data);
    exit();
}

$followers_table = defined('T_FOLLOWERS') ? T_FOLLOWERS : 'Wo_Followers';

/*
 * Determine state directly from the database. Do not use cached Wo_UserData()
 * and do not use the button's CSS class as the source of truth.
 */
$relationship_query = mysqli_query(
    $sqlConnect,
    "SELECT `id`, `active`
     FROM {$followers_table}
     WHERE `following_id` = {$following_id}
       AND `follower_id` = {$follower_id}
     ORDER BY `id` DESC
     LIMIT 1"
);

if (!$relationship_query) {
    $data['status'] = 500;
    $data['error']  = 'Unable to read follow state';

    if (function_exists('Buzzjuice_FollowLog')) {
        Buzzjuice_FollowLog('endpoint_read_failed', array(
            'following_id' => $following_id,
            'follower_id'  => $follower_id,
            'mysql_error'  => mysqli_error($sqlConnect),
        ));
    }

    echo json_encode($data);
    exit();
}

$current_relationship = mysqli_fetch_assoc($relationship_query);

$currently_active = (
    $current_relationship &&
    (int) $current_relationship['active'] === 1
);

if ($currently_active) {
    /*
     * Following -> Follow.
     */
    $success   = Wo_DeleteFollow($following_id, $follower_id);
    $operation = 'unfollow';
} else {
    /*
     * Follow or legacy Requested -> Following.
     */
    $success   = Wo_RegisterFollow($following_id, $follower_id);
    $operation = 'follow';
}

if (!$success) {
    $data['status'] = 500;
    $data['error']  = 'The follow operation failed';

    if (function_exists('Buzzjuice_FollowLog')) {
        Buzzjuice_FollowLog('endpoint_operation_failed', array(
            'operation'    => $operation,
            'following_id' => $following_id,
            'follower_id'  => $follower_id,
            'mysql_error'  => mysqli_error($sqlConnect),
        ));
    }

    echo json_encode($data);
    exit();
}

/*
 * Invalidate both users and the normal WoWonder user cache.
 */
if (function_exists('cache')) {
    @cache($following_id, 'users', 'delete');
    @cache($follower_id, 'users', 'delete');
}

if (function_exists('Wo_CleanCache')) {
    Wo_CleanCache();
}

/*
 * Verify the final database state after the write.
 */
$verify_query = mysqli_query(
    $sqlConnect,
    "SELECT `id`, `active`
     FROM {$followers_table}
     WHERE `following_id` = {$following_id}
       AND `follower_id` = {$follower_id}
     ORDER BY `id` DESC
     LIMIT 1"
);

if (!$verify_query) {
    $data['status'] = 500;
    $data['error']  = 'The relationship changed, but final verification failed';

    if (function_exists('Buzzjuice_FollowLog')) {
        Buzzjuice_FollowLog('endpoint_verification_query_failed', array(
            'operation'    => $operation,
            'following_id' => $following_id,
            'follower_id'  => $follower_id,
            'mysql_error'  => mysqli_error($sqlConnect),
        ));
    }

    echo json_encode($data);
    exit();
}

$verified_relationship = mysqli_fetch_assoc($verify_query);

$active = (
    $verified_relationship &&
    (int) $verified_relationship['active'] === 1
);

$data = array(
    'status'          => 200,
    'state'           => $active ? 'following' : 'follow',
    'following_id'    => $following_id,
    'follower_id'     => $follower_id,
    'active'          => $active ? 1 : 0,
    'requested'       => 0,
    'relationship_id' => $verified_relationship
        ? (int) $verified_relationship['id']
        : 0,
);

if (function_exists('Buzzjuice_FollowLog')) {
    Buzzjuice_FollowLog('endpoint_operation_complete', array(
        'operation'       => $operation,
        'following_id'    => $following_id,
        'follower_id'     => $follower_id,
        'state'           => $data['state'],
        'active'          => $data['active'],
        'relationship_id' => $data['relationship_id'],
    ));
}

echo json_encode($data);
exit();
