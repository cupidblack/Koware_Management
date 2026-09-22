<?php
/**
 * Buzzjuice Streams - WoWonder account diagnostics
 *
 * Non-mutating diagnostic helper.
 *
 * It compares a Wo_Users account against the known working reference account
 * (user_id 66) and records the result in:
 *   buzzjuice.net/data.logs/follow/account-diagnostics-YYYY-MM-DD.log
 *
 * It deliberately does not modify the database.
 */

if (!function_exists('bzj_wo_diag_log')) {
    function bzj_wo_diag_log($event, $context = array())
    {
        $base = dirname(__DIR__) . '/data.logs/follow';
        if (!is_dir($base)) {
            @mkdir($base, 0755, true);
        }

        $record = array(
            'event' => $event,
            'timestamp_utc' => gmdate('c'),
            'context' => $context
        );

        @file_put_contents(
            $base . '/account-diagnostics-' . gmdate('Y-m-d') . '.log',
            json_encode($record, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}

if (!function_exists('bzj_wo_account_diagnostics')) {
    function bzj_wo_account_diagnostics($user_id, $reference_user_id = 66, $reason = 'manual')
    {
        global $sqlConnect;

        $user_id = (int)$user_id;
        $reference_user_id = (int)$reference_user_id;

        if (!$sqlConnect || $user_id < 1 || $reference_user_id < 1) {
            return array(
                'ok' => false,
                'error' => 'Invalid database connection or user IDs.'
            );
        }

        $fields = array(
            'user_id',
            'username',
            'email',
            'wp_user_id',
            'active',
            'start_up',
            'startup_follow',
            'confirm_followers',
            'follow_privacy',
            'type',
            'verified',
            'is_pro',
            'joined',
            'lastseen'
        );

        $select = implode(',', array_map(function($field) {
            return '`' . $field . '`';
        }, $fields));

        $rows = array();

        foreach (array('reference' => $reference_user_id, 'subject' => $user_id) as $label => $id) {
            $stmt = mysqli_prepare(
                $sqlConnect,
                "SELECT {$select} FROM " . T_USERS . " WHERE `user_id` = ? LIMIT 1"
            );

            if (!$stmt) {
                $error = mysqli_error($sqlConnect);
                bzj_wo_diag_log('query_prepare_failed', array(
                    'reason' => $reason,
                    'user_id' => $id,
                    'mysql_error' => $error
                ));
                return array('ok' => false, 'error' => $error);
            }

            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $rows[$label] = $result ? mysqli_fetch_assoc($result) : null;
            mysqli_stmt_close($stmt);
        }

        if (empty($rows['reference']) || empty($rows['subject'])) {
            $out = array(
                'ok' => false,
                'reason' => 'account_not_found',
                'reference_user_id' => $reference_user_id,
                'subject_user_id' => $user_id,
                'reference_found' => !empty($rows['reference']),
                'subject_found' => !empty($rows['subject'])
            );
            bzj_wo_diag_log('account_diagnostic_failed', $out);
            return $out;
        }

        $differences = array();

        foreach ($fields as $field) {
            if ($field === 'user_id') {
                continue;
            }

            $reference_value = isset($rows['reference'][$field]) ? $rows['reference'][$field] : null;
            $subject_value = isset($rows['subject'][$field]) ? $rows['subject'][$field] : null;

            if ((string)$reference_value !== (string)$subject_value) {
                $differences[$field] = array(
                    'reference' => $reference_value,
                    'subject' => $subject_value
                );
            }
        }

        $relationship = array();

        $relationship_sql = "
            SELECT
                SUM(CASE WHEN `following_id` = {$user_id} AND `active` = 1 THEN 1 ELSE 0 END) AS outgoing_active,
                SUM(CASE WHEN `following_id` = {$user_id} AND `active` = 0 THEN 1 ELSE 0 END) AS outgoing_pending,
                SUM(CASE WHEN `follower_id` = {$user_id} AND `active` = 1 THEN 1 ELSE 0 END) AS incoming_active,
                SUM(CASE WHEN `follower_id` = {$user_id} AND `active` = 0 THEN 1 ELSE 0 END) AS incoming_pending
            FROM " . T_FOLLOWERS;

        $q = mysqli_query($sqlConnect, $relationship_sql);
        if ($q && ($r = mysqli_fetch_assoc($q))) {
            $relationship['subject'] = array_map('intval', $r);
        }

        $relationship_sql_ref = "
            SELECT
                SUM(CASE WHEN `following_id` = {$reference_user_id} AND `active` = 1 THEN 1 ELSE 0 END) AS outgoing_active,
                SUM(CASE WHEN `following_id` = {$reference_user_id} AND `active` = 0 THEN 1 ELSE 0 END) AS outgoing_pending,
                SUM(CASE WHEN `follower_id` = {$reference_user_id} AND `active` = 1 THEN 1 ELSE 0 END) AS incoming_active,
                SUM(CASE WHEN `follower_id` = {$reference_user_id} AND `active` = 0 THEN 1 ELSE 0 END) AS incoming_pending
            FROM " . T_FOLLOWERS;

        $q2 = mysqli_query($sqlConnect, $relationship_sql_ref);
        if ($q2 && ($r2 = mysqli_fetch_assoc($q2))) {
            $relationship['reference'] = array_map('intval', $r2);
        }

        $result = array(
            'ok' => true,
            'reason' => $reason,
            'reference_user_id' => $reference_user_id,
            'subject_user_id' => $user_id,
            'reference' => $rows['reference'],
            'subject' => $rows['subject'],
            'differences' => $differences,
            'relationship_counts' => $relationship
        );

        bzj_wo_diag_log('account_diagnostic_complete', $result);

        return $result;
    }
}
