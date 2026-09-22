<?php
/**
 * Buzzjuice Streams - bulk follow endpoint
 *
 * Route:
 *   requests.php?f=follow_users
 *
 * Every relationship is written as active=1. Pending/requested state is not
 * used by Buzzjuice Streams.
 */

if ($f == 'follow_users') {
    global $wo;

    header("Content-Type: application/json; charset=utf-8");

    $request_id = function_exists('bzj_follow_log')
        ? bzj_follow_log('bulk_follow_start', array(
            'user_payload_present' => !empty($_POST['user'])
        ))
        : bin2hex(random_bytes(8));

    $response = array(
        'status' => 400,
        'request_id' => $request_id,
        'results' => array()
    );

    if (empty($wo['loggedin']) || empty($wo['user']['user_id'])) {
        $response['status'] = 401;
        $response['error'] = 'Authentication required.';
        echo json_encode($response);
        exit();
    }

    if (empty($_POST['user'])) {
        $response['error'] = 'No users supplied.';
        echo json_encode($response);
        exit();
    }

    $raw_ids = explode(',', (string)$_POST['user']);
    $unique_ids = array();

    foreach ($raw_ids as $raw_id) {
        $id = function_exists('bzj_follow_normalize_id')
            ? bzj_follow_normalize_id($raw_id)
            : ((ctype_digit(trim((string)$raw_id))) ? (int)$raw_id : 0);

        if ($id > 0 && $id !== (int)$wo['user']['user_id']) {
            $unique_ids[$id] = $id;
        }
    }

    if (empty($unique_ids)) {
        $response['error'] = 'No valid users supplied.';
        echo json_encode($response);
        exit();
    }

    $success_count = 0;
    $failure_count = 0;

    foreach ($unique_ids as $id) {
        $ok = Wo_RegisterFollow($id, (int)$wo['user']['user_id']);

        $state = function_exists('bzj_follow_relationship_state')
            ? bzj_follow_relationship_state($id, (int)$wo['user']['user_id'])
            : array(
                'following' => Wo_IsFollowing($id, (int)$wo['user']['user_id']),
                'requested' => false
            );

        $success = !empty($state['following']);

        $response['results'][] = array(
            'following_id' => (int)$id,
            'ok' => $success,
            'function_returned' => (bool)$ok,
            'state' => $success ? 'following' : 'follow'
        );

        if ($success) {
            $success_count++;
        } else {
            $failure_count++;
        }
    }

    try {
        Wo_UpdateUserData((int)$wo['user']['user_id'], array(
            'startup_follow' => '1',
            'start_up' => '1'
        ));
        Wo_UpdateUserDetails((int)$wo['user']['user_id'], false, false, true);
    } catch (Throwable $e) {
        if (function_exists('bzj_follow_log')) {
            bzj_follow_log('bulk_follow_profile_update_failed', array(
                'exception' => $e->getMessage(),
                'request_id' => $request_id
            ));
        }
    }

    $response['status'] = ($success_count > 0) ? 200 : 500;
    $response['success_count'] = $success_count;
    $response['failure_count'] = $failure_count;

    if ($success_count === 0) {
        $response['error'] = 'No follow relationship could be verified.';
    }

    if (function_exists('bzj_follow_log')) {
        bzj_follow_log('bulk_follow_complete', array(
            'success_count' => $success_count,
            'failure_count' => $failure_count,
            'results' => $response['results'],
            'request_id' => $request_id
        ));
    }

    echo json_encode($response);
    exit();
}
