<?php
/**
 * Buzzjuice Streams - server-authoritative follow endpoint
 *
 * Route:
 *   requests.php?f=follow_user
 *
 * Security:
 *   requests.php already requires an authenticated WoWonder session for this
 *   endpoint. Do NOT require Wo_CheckMainSession() here unless the front-end
 *   explicitly supplies the corresponding hash_id. The previous fix rejected
 *   valid authenticated follow requests with "invalid_main_session".
 */

if ($f == 'follow_user') {
    global $wo, $sqlConnect;

    header("Content-Type: application/json; charset=utf-8");

    $request_id = function_exists('bzj_follow_log')
        ? bzj_follow_log('follow_endpoint_start', array(
            'get' => array(
                'following_id' => isset($_GET['following_id']) ? $_GET['following_id'] : null,
                'hash_present' => !empty($_GET['hash_id']) || !empty($_GET['hash'])
            )
        ))
        : bin2hex(random_bytes(8));

    $response = array(
        'status' => 500,
        'state' => 'error',
        'following' => false,
        'requested' => false,
        'can_send' => 0,
        'request_id' => $request_id
    );

    if (empty($wo['loggedin']) || empty($wo['user']['user_id'])) {
        $response['status'] = 401;
        $response['error'] = 'Authentication required.';
        if (function_exists('bzj_follow_log')) {
            bzj_follow_log('follow_endpoint_rejected', array(
                'reason' => 'not_logged_in',
                'request_id' => $request_id
            ));
        }
        echo json_encode($response);
        exit();
    }

    $current_user_id = (int)$wo['user']['user_id'];
    $following_id = isset($_GET['following_id']) ? trim((string)$_GET['following_id']) : '';

    if ($following_id === '' || !ctype_digit($following_id) || (int)$following_id < 1) {
        $response['status'] = 400;
        $response['error'] = 'Invalid following user.';
        if (function_exists('bzj_follow_log')) {
            bzj_follow_log('follow_endpoint_rejected', array(
                'reason' => 'invalid_following_id',
                'following_id_raw' => $following_id,
                'current_user_id' => $current_user_id,
                'request_id' => $request_id
            ));
        }
        echo json_encode($response);
        exit();
    }

    $following_id = (int)$following_id;

    if ($following_id === $current_user_id) {
        $response['status'] = 400;
        $response['error'] = 'You cannot follow yourself.';
        echo json_encode($response);
        exit();
    }

    $before = function_exists('bzj_follow_relationship_state')
        ? bzj_follow_relationship_state($following_id, $current_user_id)
        : array();

    $is_currently_following = !empty($before['following']);

    /*
     * The endpoint is a true toggle:
     *   Follow/Requested -> ensure active following.
     *   Following       -> remove relationship.
     *
     * Requested is never returned by the server.
     */
    if ($is_currently_following) {
        $ok = Wo_DeleteFollow($following_id, $current_user_id);
        $operation = 'unfollow';
    } else {
        $ok = Wo_RegisterFollow($following_id, $current_user_id);
        $operation = 'follow';
    }

    $after = function_exists('bzj_follow_relationship_state')
        ? bzj_follow_relationship_state($following_id, $current_user_id)
        : array(
            'following' => Wo_IsFollowing($following_id, $current_user_id),
            'requested' => false,
            'active_count' => 0,
            'pending_count' => 0,
            'total_count' => 0
        );

    if ($operation === 'follow' && !empty($after['following'])) {
        $response['status'] = 200;
        $response['state'] = 'following';
        $response['following'] = true;
        $response['requested'] = false;
        $response['html'] = '';
    } elseif ($operation === 'unfollow' && empty($after['following']) && empty($after['total_count'])) {
        $response['status'] = 200;
        $response['state'] = 'follow';
        $response['following'] = false;
        $response['requested'] = false;
        $response['html'] = '';
    } else {
        $response['status'] = 500;
        $response['state'] = !empty($after['following']) ? 'following' : 'follow';
        $response['following'] = !empty($after['following']);
        $response['requested'] = false;
        $response['error'] = 'The follow state could not be verified after the database operation.';
    }

    if (function_exists('Wo_CanSenEmails') && $response['status'] === 200 && $operation === 'follow') {
        try {
            if (Wo_CanSenEmails()) {
                $response['can_send'] = 1;
            }
        } catch (Throwable $e) {
            if (function_exists('bzj_follow_log')) {
                bzj_follow_log('follow_can_send_check_failed', array(
                    'exception' => $e->getMessage(),
                    'request_id' => $request_id
                ));
            }
        }
    }

    if (function_exists('Wo_CleanCache') && $wo['loggedin'] == true) {
        try {
            Wo_CleanCache();
        } catch (Throwable $e) {
            if (function_exists('bzj_follow_log')) {
                bzj_follow_log('follow_clean_cache_failed', array(
                    'exception' => $e->getMessage(),
                    'request_id' => $request_id
                ));
            }
        }
    }

    if (function_exists('bzj_follow_log')) {
        bzj_follow_log('follow_endpoint_complete', array(
            'operation' => $operation,
            'following_id' => $following_id,
            'follower_id' => $current_user_id,
            'function_returned' => (bool)$ok,
            'before' => $before,
            'after' => $after,
            'response' => $response,
            'request_id' => $request_id
        ));
    }

    echo json_encode($response);
    exit();
}
