<?php
if ($f == 'follow_user') {
    $data = array(
        'status'       => 400,
        'can_send'     => 0,
        'follow_state' => 'follow',
        'message'      => 'Unable to process follow request.'
    );

    $following_id = isset($_GET['following_id'])
        ? (int)$_GET['following_id']
        : 0;

    $main_session_valid = Wo_CheckMainSession($hash_id);

    if (function_exists('bz_streams_log')) {
        bz_streams_log(
            'follow_request_received',
            array(
                'following_id' => $following_id,
                'logged_user_id' => isset($wo['user']['user_id'])
                    ? (int)$wo['user']['user_id']
                    : 0,
                'session_active' => function_exists('session_status') &&
                    session_status() === PHP_SESSION_ACTIVE ? 1 : 0,
                'main_session_present' => isset($_SESSION['main_hash_id']) ? 1 : 0,
                'main_session_valid' => $main_session_valid ? 1 : 0,
                'hash_received' => $hash_id !== '' ? 1 : 0
            )
        );
    }

    if ($wo['loggedin'] !== true) {
        $data['status'] = 401;
        $data['message'] = 'Please log in again.';
    } elseif ($following_id < 1) {
        $data['message'] = 'Invalid user.';
    } elseif ($main_session_valid !== true) {
        $data['status'] = 403;
        $data['message'] = 'Session validation failed. Please refresh the page.';
    } elseif (
        isset($wo['user']['user_id']) &&
        $following_id === (int)$wo['user']['user_id']
    ) {
        $data['message'] = 'You cannot follow yourself.';
    } else {
        $user_followers = Wo_CountFollowing($wo['user']['id'], true);
        $friends_limit  = $wo['config']['connectivitySystemLimit'];

        /*
         * Existing relationship => unfollow.
         */
        if (
            Wo_IsFollowing($following_id, $wo['user']['user_id']) === true ||
            Wo_IsFollowRequested($following_id, $wo['user']['user_id']) === true
        ) {
            if (Wo_DeleteFollow($following_id, $wo['user']['user_id'])) {
                $data = array(
                    'status'       => 200,
                    'can_send'     => 0,
                    'follow_state' => 'follow',
                    'html'         => ''
                );
            } else {
                $data['message'] = 'Unable to remove the follow.';
            }
        } elseif (
            $wo['config']['connectivitySystem'] == 1 &&
            $user_followers >= $friends_limit
        ) {
            $data['message'] = 'You have reached your connection limit.';
        } else {
            /*
             * Wo_RegisterFollow() is the authoritative write path.
             * Its BCR&D implementation must insert active = 1.
             */
            if (Wo_RegisterFollow($following_id, $wo['user']['user_id'])) {
                $data = array(
                    'status'       => 200,
                    'can_send'     => 0,
                    'follow_state' => 'following',
                    'html'         => ''
                );

                if (Wo_CanSenEmails()) {
                    $data['can_send'] = 1;
                }
            } else {
                $data['message'] = 'Unable to create the follow.';
            }
        }
    }

    if ($wo['loggedin'] == true) {
        Wo_CleanCache();
    }

    if (function_exists('bz_streams_log')) {
        bz_streams_log(
            'follow_request_response',
            array(
                'following_id' => $following_id,
                'status'       => (int)$data['status'],
                'follow_state' => $data['follow_state'],
                'message'      => $data['message']
            )
        );
    }

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit();
}
