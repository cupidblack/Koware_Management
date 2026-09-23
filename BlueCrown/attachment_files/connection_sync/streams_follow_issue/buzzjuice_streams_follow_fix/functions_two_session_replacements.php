/*
 * Replace the existing four functions in:
 * streams/assets/includes/functions_two.php
 *
 * Do not create duplicate definitions. Replace the existing definitions.
 */

function Wo_CreateSession()
{
    if (function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) {
        if (function_exists('bz_streams_log')) {
            bz_streams_log('application_session_hash_requested_without_active_php_session');
        }
        return '';
    }

    if (
        isset($_SESSION['hash_id']) &&
        is_string($_SESSION['hash_id']) &&
        preg_match('/^[a-f0-9]{40}$/i', $_SESSION['hash_id'])
    ) {
        return $_SESSION['hash_id'];
    }

    try {
        $hash = bin2hex(random_bytes(20));
    } catch (Throwable $exception) {
        $hash = sha1(uniqid((string)mt_rand(), true));

        if (function_exists('bz_streams_log')) {
            bz_streams_log(
                'application_session_hash_random_bytes_fallback',
                array('exception' => $exception->getMessage())
            );
        }
    }

    $_SESSION['hash_id'] = $hash;
    return $hash;
}

function Wo_CheckSession($hash = '')
{
    if (function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) {
        return false;
    }

    if (
        $hash === null ||
        $hash === '' ||
        !isset($_SESSION['hash_id']) ||
        !is_string($_SESSION['hash_id']) ||
        $_SESSION['hash_id'] === ''
    ) {
        return false;
    }

    return hash_equals((string)$_SESSION['hash_id'], (string)$hash);
}

function Wo_CreateMainSession()
{
    if (function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) {
        if (function_exists('bz_streams_log')) {
            bz_streams_log('main_application_session_requested_without_active_php_session');
        }
        return '';
    }

    if (
        isset($_SESSION['main_hash_id']) &&
        is_string($_SESSION['main_hash_id']) &&
        preg_match('/^[a-f0-9]{20}$/i', $_SESSION['main_hash_id'])
    ) {
        return $_SESSION['main_hash_id'];
    }

    try {
        $hash = bin2hex(random_bytes(10));
    } catch (Throwable $exception) {
        $hash = substr(
            hash('sha256', uniqid((string)mt_rand(), true)),
            0,
            20
        );

        if (function_exists('bz_streams_log')) {
            bz_streams_log(
                'main_application_session_random_bytes_fallback',
                array('exception' => $exception->getMessage())
            );
        }
    }

    $_SESSION['main_hash_id'] = $hash;
    return $hash;
}

function Wo_CheckMainSession($hash = '')
{
    if (function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) {
        return false;
    }

    if (
        $hash === null ||
        $hash === '' ||
        !isset($_SESSION['main_hash_id']) ||
        !is_string($_SESSION['main_hash_id']) ||
        $_SESSION['main_hash_id'] === ''
    ) {
        return false;
    }

    return hash_equals((string)$_SESSION['main_hash_id'], (string)$hash);
}
