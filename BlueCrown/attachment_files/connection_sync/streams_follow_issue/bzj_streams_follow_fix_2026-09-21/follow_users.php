<?php
/**
 * Buzzjuice Streams - startup/bulk follows.
 */

if ($f !== 'follow_users') {
    exit();
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$data = array(
    'status'   => 400,
    'followed' => array(),
    'failed'   => array(),
);

if (empty($wo['loggedin']) || $wo['loggedin'] !== true) {
    $data['status'] = 401;
    $data['error']  = 'Unauthorized request';

    echo json_encode($data);
    exit();
}

$raw_users = isset($_POST['user']) ? (string) $_POST['user'] : '';

$ids = array_filter(
    array_map(
        'intval',
        explode(',', $raw_users)
    )
);

$ids = array_values(array_unique($ids));

$current_user_id = !empty($wo['user']['user_id'])
    ? (int) $wo['user']['user_id']
    : (!empty($wo['user']['id']) ? (int) $wo['user']['id'] : 0);

if ($current_user_id < 1) {
    $data['status'] = 401;
    $data['error']  = 'Unable to determine current user';

    if (function_exists('Buzzjuice_FollowLog')) {
        Buzzjuice_FollowLog('bulk_follow_rejected', array(
            'reason' => 'missing_current_user',
        ));
    }

    echo json_encode($data);
    exit();
}

foreach ($ids as $id) {
    if ($id < 1 || $id === $current_user_id) {
        $data['failed'][] = $id;
        continue;
    }

    if (Wo_RegisterFollow($id, $current_user_id)) {
        $data['followed'][] = $id;
    } else {
        $data['failed'][] = $id;
    }
}

Wo_UpdateUserData($current_user_id, array(
    'startup_follow' => '1',
    'start_up'       => '1',
));

Wo_UpdateUserDetails($current_user_id, false, false, true);

if (function_exists('Wo_CleanCache')) {
    Wo_CleanCache();
}

$data['status'] = empty($data['failed']) ? 200 : 207;

if (function_exists('Buzzjuice_FollowLog')) {
    Buzzjuice_FollowLog('bulk_follow_complete', array(
        'follower_id' => $current_user_id,
        'followed'    => $data['followed'],
        'failed'      => $data['failed'],
    ));
}

echo json_encode($data);
exit();
