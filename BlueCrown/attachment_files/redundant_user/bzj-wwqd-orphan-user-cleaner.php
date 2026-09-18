<?php
/**
 * Buzzjuice WoWonder + QuickDate orphan-user cleaner and platform-ID integrity auditor.
 *
 * Install:
 *   /buzzjuice.net/shared/bzj-wwqd-orphan-user-cleaner.php
 *
 * Requirements:
 *   - shared/db_helpers.php
 *   - WordPress at the project root
 *   - mysqli
 *
 * Safety model:
 *   - Web UI only; WordPress administrator (manage_options) required.
 *   - Dry-run is the default.
 *   - Wo_Users and QuickDate users are authoritative and are NEVER deleted.
 *   - Media/S3 objects are NEVER deleted by this utility.
 *   - Only confirmed user-reference columns are eligible for automatic cleanup.
 *   - Schema-discovered references are advisory only and are never auto-deleted.
 *   - Destructive actions require CSRF + an exact confirmation phrase.
 *   - A fresh scan is performed immediately before every destructive action.
 *   - Mapping repair is limited to unique, non-conflicting username/email matches.
 *   - Every destructive operation is logged and followed by verification.
 */

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

if (PHP_SAPI === 'cli') {
    http_response_code(403);
    exit("This utility is web-admin only.\n");
}

const BZJ_CLEANER_VERSION = '2026-09-18.2';
const BZJ_CLEANER_CSRF = 'bzj_wwqd_orphan_cleaner_csrf';
const BZJ_CLEAN_PHRASE = 'DELETE CONFIRMED ORPHANS';
const BZJ_REPAIR_PHRASE = 'REPAIR CONFIRMED ID MISMATCHES';
const BZJ_SAMPLE_LIMIT = 25;
const BZJ_MAX_SAMPLE_LIMIT = 100;
const BZJ_REPORT_DIR = 'data/logs';

require_once __DIR__ . '/db_helpers.php';

/* -------------------------------------------------------------------------
 * WordPress bootstrap and authentication
 * ---------------------------------------------------------------------- */

$wpLoad = dirname(__DIR__) . '/wp-load.php';
if (!is_file($wpLoad)) {
    http_response_code(500);
    exit('WordPress bootstrap could not be located at the project root.');
}
require_once $wpLoad;

if (!function_exists('wp_get_current_user') || !function_exists('current_user_can')) {
    http_response_code(500);
    exit('WordPress authentication functions are unavailable.');
}

$currentUser = wp_get_current_user();
if (!$currentUser || empty($currentUser->ID) || !current_user_can('manage_options')) {
    http_response_code(403);
    exit('Administrator access is required.');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION[BZJ_CLEANER_CSRF])) {
    $_SESSION[BZJ_CLEANER_CSRF] = bin2hex(random_bytes(32));
}
$csrfToken = (string) $_SESSION[BZJ_CLEANER_CSRF];

/* -------------------------------------------------------------------------
 * Generic helpers
 * ---------------------------------------------------------------------- */

function bzj_h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function bzj_fail(string $message): void
{
    throw new RuntimeException($message);
}

function bzj_ident(string $identifier): string
{
    if (!preg_match('/^[A-Za-z0-9_]+$/', $identifier)) {
        bzj_fail('Unsafe SQL identifier: ' . $identifier);
    }
    return '`' . $identifier . '`';
}

function bzj_query(mysqli $db, string $sql): mysqli_result|bool
{
    $result = $db->query($sql);
    if ($result === false) {
        throw new RuntimeException($db->error . ' | SQL: ' . $sql);
    }
    return $result;
}

function bzj_table_exists(mysqli $db, string $table): bool
{
    $escaped = $db->real_escape_string($table);
    $result = bzj_query($db, "SHOW TABLES LIKE '{$escaped}'");
    return $result instanceof mysqli_result && $result->num_rows > 0;
}

function bzj_columns(mysqli $db, string $table): array
{
    $result = bzj_query($db, 'SHOW COLUMNS FROM ' . bzj_ident($table));
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Field'])) {
            $columns[strtolower((string) $row['Field'])] = (string) $row['Field'];
        }
    }
    return $columns;
}

function bzj_table_engine(mysqli $db, string $table): array
{
    $escaped = $db->real_escape_string($table);
    $result = bzj_query($db, "SHOW TABLE STATUS LIKE '{$escaped}'");
    $row = $result instanceof mysqli_result ? $result->fetch_assoc() : [];
    $engine = (string) ($row['Engine'] ?? '');
    return [
        'engine' => $engine,
        'transactional' => strcasecmp($engine, 'InnoDB') === 0,
    ];
}

function bzj_tables(mysqli $db): array
{
    $result = bzj_query($db, 'SHOW TABLES');
    $tables = [];
    while ($row = $result->fetch_row()) {
        if (isset($row[0])) {
            $tables[] = (string) $row[0];
        }
    }
    return $tables;
}

function bzj_normalize(mixed $value): string
{
    $value = trim((string) $value);
    return function_exists('mb_strtolower')
        ? mb_strtolower($value, 'UTF-8')
        : strtolower($value);
}

function bzj_log(array $event): void
{
    $dir = dirname(__DIR__) . '/' . BZJ_REPORT_DIR;
    if (!is_dir($dir)) {
        @mkdir($dir, 0750, true);
    }

    $event['timestamp_utc'] = gmdate('c');
    $event['admin_wp_user_id'] = function_exists('get_current_user_id')
        ? (int) get_current_user_id()
        : 0;

    $json = json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($json !== false) {
        @file_put_contents(
            $dir . '/bzj-wwqd-orphan-user-cleaner.log',
            $json . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}

function bzj_write_report(array $report): ?string
{
    $dir = dirname(__DIR__) . '/' . BZJ_REPORT_DIR;
    if (!is_dir($dir) && !@mkdir($dir, 0750, true) && !is_dir($dir)) {
        return null;
    }

    $filename = 'bzj-wwqd-orphan-user-report-' .
        gmdate('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.json';
    $path = $dir . '/' . $filename;

    $json = json_encode(
        $report,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );

    if ($json === false || @file_put_contents($path, $json . PHP_EOL, LOCK_EX) === false) {
        return null;
    }

    return $path;
}

function bzj_wp_table(string $name): string
{
    $prefix = defined('WP_TABLE_PREFIX') ? WP_TABLE_PREFIX : 'wp_';
    return $prefix . $name;
}

/* -------------------------------------------------------------------------
 * Database connections
 * ---------------------------------------------------------------------- */

function bzj_connections(): array
{
    $wp = get_wp_db_conn();
    $wo = get_wowonder_db();
    $qd = get_qd_db_conn();

    foreach (['wp' => $wp, 'wo' => $wo, 'qd' => $qd] as $name => $conn) {
        if (!$conn instanceof mysqli || $conn->connect_errno) {
            throw new RuntimeException(ucfirst($name) . ' database connection failed.');
        }
        $conn->set_charset('utf8mb4');
    }

    return ['wp' => $wp, 'wo' => $wo, 'qd' => $qd];
}

/* -------------------------------------------------------------------------
 * Authoritative users and confirmed reference maps
 * ---------------------------------------------------------------------- */

function bzj_authoritative(): array
{
    return [
        'wowonder' => [
            'label' => 'WoWonder',
            'table' => 'Wo_Users',
            'id' => 'user_id',
            'username' => 'username',
            'email' => 'email',
        ],
        'quickdate' => [
            'label' => 'QuickDate',
            'table' => defined('QD_USERS_TABLE') ? QD_USERS_TABLE : 'users',
            'id' => 'id',
            'username' => 'username',
            'email' => 'email',
        ],
    ];
}

/*
 * Based on the supplied Wo_DeleteUser() and QuickDate delete_user() functions.
 * Only tables in this confirmed map are automatically cleaned.
 */
function bzj_reference_maps(): array
{
    return [
        'wowonder' => [
            'Wo_Users_Fields' => ['user_id'],
            'Wo_RecentSearches' => ['user_id', 'search_id'],
            'Wo_GamesPlayers' => ['user_id'],
            'Wo_UserProjects' => ['user_id'],
            'Wo_UserOpenTo' => ['user_id'],
            'Wo_Followers' => ['follower_id', 'following_id'],
            'Wo_Messages' => ['from_id', 'to_id'],
            'Wo_VideoCalls' => ['from_id', 'to_id'],
            'Wo_AudioCalls' => ['from_id', 'to_id'],
            'Wo_Agora' => ['from_id', 'to_id', 'from_id '],
            'Wo_Notification' => ['notifier_id', 'recipient_id'],
            'Wo_Reports' => ['user_id', 'report_userid'],
            'Wo_AppsSessions' => ['user_id'],
            'Wo_AppSessions' => ['user_id'],
            'Wo_Comments' => ['user_id'],
            'Wo_AnnouncementViews' => ['user_id'],
            'Wo_Likes' => ['user_id'],
            'Wo_Wonders' => ['user_id'],
            'Wo_CommentRepliesLikes' => ['user_id'],
            'Wo_CommentRepliesWonders' => ['user_id'],
            'Wo_SavedPosts' => ['user_id'],
            'Wo_CommentLikes' => ['user_id'],
            'Wo_CommentWonders' => ['user_id'],
            'Wo_CommentsReplies' => ['user_id'],
            'Wo_EventsGoing' => ['user_id'],
            'Wo_EventsInterested' => ['user_id'],
            'Wo_EventsInt' => ['user_id'],
            'Wo_BmLikes' => ['user_id'],
            'Wo_BmDislikes' => ['user_id'],
            'Wo_UserAdsData' => ['user_id'],
            'Wo_PaymentTransactions' => ['userid'],
            'Wo_Activities' => ['user_id', 'follow_id'],
            'Wo_EventsInv' => ['inviter_id', 'invited_id'],
            'Wo_GroupMembers' => ['user_id'],
            'Wo_PagesInvites' => ['inviter_id', 'invited_id'],
            'Wo_PagesInvaites' => ['inviter_id', 'invited_id'],
            'Wo_PinnedPosts' => ['user_id'],
            'Wo_Apps' => ['app_user_id'],
            'Wo_AppsPermission' => ['user_id'],
            'Wo_Codes' => ['user_id'],
            'Wo_Tokens' => ['user_id'],
            'Wo_BlogReaction' => ['user_id'],
            'Wo_PagesLikes' => ['user_id'],
            'Wo_VerificationRequests' => ['user_id'],
            'Wo_ARequests' => ['user_id'],
            'Wo_Blocks' => ['blocker', 'blocked'],
            'Wo_UChats' => ['conversation_user_id', 'user_id'],
            'Wo_Blog' => ['user'],
            'Wo_BlogCommentsReplies' => ['user_id'],
            'Wo_BlogComments' => ['user_id'],
            'Wo_MovieCommentsReplies' => ['user_id'],
            'Wo_MovieComments' => ['user_id'],
            'Wo_AppsHash' => ['user_id'],
            'Wo_ForumThreads' => ['user'],
            'Wo_ForumThreadReplies' => ['poster_id'],
            'Wo_Events' => ['poster_id'],
            'Wo_UserAds' => ['user_id'],
            'Wo_UserStory' => ['user_id'],
            'Wo_HiddenPosts' => ['user_id'],
            'Wo_GroupChat' => ['user_id'],
            'Wo_GroupChatUsers' => ['user_id'],
            'Wo_PageRating' => ['user_id'],
            'Wo_Family' => ['user_id', 'member_id'],
            'Wo_Relationship' => ['from_id', 'to_id'],
            'Wo_PageAdmins' => ['user_id'],
            'Wo_GroupAdmins' => ['user_id'],
            'Wo_Reactions' => ['user_id'],
            'Wo_Job' => ['user_id'],
            'Wo_JobApply' => ['user_id'],
            'Wo_Pokes' => ['received_user_id', 'send_user_id'],
            'Wo_UserGifts' => ['from', 'to'],
            'Wo_StorySeen' => ['user_id'],
            'Wo_Refund' => ['user_id'],
            'Wo_InvitationLinks' => ['user_id', 'invited_id'],
            'Wo_Mute' => ['user_id'],
            'Wo_MuteStory' => ['user_id', 'story_user_id'],
            'Wo_Cast' => ['user_id'],
            'Wo_CastUsers' => ['user_id'],
            'Wo_LiveSubscriptions' => ['user_id'],
            'Wo_LiveSub' => ['user_id'],
            'Wo_Votes' => ['user_id'],
            'Wo_BankTransfer' => ['user_id'],
            'Wo_UserCard' => ['user_id'],
            'Wo_UserAddress' => ['user_id'],
            'Wo_UserOrders' => ['user_id', 'product_owner_id'],
            'Wo_Purchases' => ['user_id', 'owner_id'],
            'Wo_Email' => ['user_id'],
            'Wo_Emails' => ['user_id'],
            'Wo_Posts' => ['user_id', 'recipient_id'],
            'Wo_Pages' => ['user_id'],
            'Wo_Groups' => ['user_id'],
            'Wo_Funding' => ['user_id'],
            'Wo_Offer' => ['user_id'],
            'Wo_UserExperience' => ['user_id'],
            'Wo_UserCertification' => ['user_id'],
            'Wo_UserMonetization' => ['user_id'],
            'Wo_UserMonetizations' => ['user_id'],
            'Wo_MonetizationSubscription' => ['user_id'],
            'Wo_MonetizationSubscribtion' => ['user_id'],
            'Wo_MonetizationSubscriptions' => ['user_id'],
        ],
        'quickdate' => [
            'blocks' => ['user_id', 'block_userid'],
            'conversations' => ['sender_id', 'receiver_id'],
            'likes' => ['user_id', 'like_userid'],
            'mediafiles' => ['user_id'],
            'messages' => ['from', 'to'],
            'notifications' => ['notifier_id', 'recipient_id'],
            'reports' => ['user_id', 'report_userid'],
            'user_gifts' => ['from', 'to'],
            'views' => ['user_id', 'view_userid'],
            'sessions' => ['user_id'],
            'payments' => ['user_id'],
            'verification_requests' => ['user_id'],
        ],
    ];
}

function bzj_aliases(): array
{
    return [
        'Wo_AppsSessions' => ['Wo_AppsSessions', 'Wo_AppSessions'],
        'Wo_EventsInterested' => ['Wo_EventsInterested', 'Wo_EventsInt'],
        'Wo_PagesInvites' => ['Wo_PagesInvites', 'Wo_PagesInvaites'],
        'Wo_LiveSubscriptions' => ['Wo_LiveSubscriptions', 'Wo_LiveSub'],
        'Wo_Email' => ['Wo_Email', 'Wo_Emails'],
        'Wo_UserMonetization' => ['Wo_UserMonetization', 'Wo_UserMonetizations'],
        'Wo_MonetizationSubscription' => [
            'Wo_MonetizationSubscription',
            'Wo_MonetizationSubscribtion',
            'Wo_MonetizationSubscriptions',
        ],
    ];
}

function bzj_resolve_table(mysqli $db, string $configured): ?string
{
    $candidates = bzj_aliases()[$configured] ?? [$configured];
    foreach ($candidates as $candidate) {
        if (bzj_table_exists($db, $candidate)) {
            return $candidate;
        }
    }
    return null;
}

function bzj_authoritative_columns(mysqli $db, array $config): array
{
    if (!bzj_table_exists($db, $config['table'])) {
        bzj_fail('Authoritative table does not exist: ' . $config['table']);
    }

    $cols = bzj_columns($db, $config['table']);
    foreach ([$config['id'], $config['username'], $config['email']] as $needed) {
        if (!isset($cols[strtolower($needed)])) {
            bzj_fail('Required authoritative column is missing: ' .
                $config['table'] . '.' . $needed);
        }
    }

    return [
        'table' => $config['table'],
        'id' => $cols[strtolower($config['id'])],
        'username' => $cols[strtolower($config['username'])],
        'email' => $cols[strtolower($config['email'])],
    ];
}

/* -------------------------------------------------------------------------
 * Consistent authoritative-user snapshot
 * ---------------------------------------------------------------------- */

function bzj_create_user_snapshot(mysqli $db, string $authTable, string $authId): void
{
    bzj_query($db, 'DROP TEMPORARY TABLE IF EXISTS `bzj_existing_user_ids`');

    bzj_query($db, '
        CREATE TEMPORARY TABLE `bzj_existing_user_ids` (
            `user_id` BIGINT UNSIGNED NOT NULL,
            PRIMARY KEY (`user_id`)
        ) ENGINE=InnoDB
    ');

    bzj_query(
        $db,
        'INSERT INTO `bzj_existing_user_ids` (`user_id`)
         SELECT DISTINCT CAST(' . bzj_ident($authId) . ' AS UNSIGNED)
         FROM ' . bzj_ident($authTable) . '
         WHERE ' . bzj_ident($authId) . ' IS NOT NULL
           AND TRIM(CAST(' . bzj_ident($authId) . ' AS CHAR)) REGEXP \'^[0-9]+$\'
           AND CAST(' . bzj_ident($authId) . ' AS UNSIGNED) > 0'
    );
}

function bzj_existing_user_count(mysqli $db): int
{
    $result = bzj_query($db, 'SELECT COUNT(*) AS total FROM `bzj_existing_user_ids`');
    $row = $result->fetch_assoc();
    return (int) ($row['total'] ?? 0);
}

function bzj_orphan_condition(string $column): string
{
    $c = bzj_ident($column);

    return $c . ' IS NOT NULL
        AND TRIM(CAST(' . $c . ' AS CHAR)) REGEXP \'^[0-9]+$\'
        AND CAST(' . $c . ' AS UNSIGNED) > 0
        AND NOT EXISTS (
            SELECT 1
            FROM `bzj_existing_user_ids` AS eu
            WHERE eu.`user_id` = CAST(' . $c . ' AS UNSIGNED)
        )';
}

/* -------------------------------------------------------------------------
 * Platform orphan scanning
 * ---------------------------------------------------------------------- */

function bzj_scan_platform(string $platform, mysqli $db, int $sampleLimit = BZJ_SAMPLE_LIMIT): array
{
    $auth = bzj_authoritative()[$platform];
    $resolvedAuth = bzj_authoritative_columns($db, $auth);

    bzj_create_user_snapshot($db, $resolvedAuth['table'], $resolvedAuth['id']);
    $existingCount = bzj_existing_user_count($db);

    $findings = [];
    $confirmedLookup = [];

    foreach (bzj_reference_maps()[$platform] as $configuredTable => $configuredColumns) {
        $table = bzj_resolve_table($db, $configuredTable);

        if ($table === null || strcasecmp($table, $resolvedAuth['table']) === 0) {
            continue;
        }

        $actualColumns = bzj_columns($db, $table);
        $conditions = [];
        $columnFindings = [];

        foreach ($configuredColumns as $configuredColumn) {
            $key = strtolower($configuredColumn);

            if (!isset($actualColumns[$key])) {
                continue;
            }

            $column = $actualColumns[$key];
            $condition = bzj_orphan_condition($column);

            $countResult = bzj_query(
                $db,
                'SELECT COUNT(*) AS total FROM ' . bzj_ident($table) .
                ' WHERE ' . $condition
            );
            $countRow = $countResult->fetch_assoc();
            $count = (int) ($countRow['total'] ?? 0);

            if ($count < 1) {
                continue;
            }

            $sampleLimit = max(1, min($sampleLimit, BZJ_MAX_SAMPLE_LIMIT));

            $sampleResult = bzj_query(
                $db,
                'SELECT DISTINCT ' . bzj_ident($column) . ' AS orphan_id
                 FROM ' . bzj_ident($table) . '
                 WHERE ' . $condition . '
                 ORDER BY CAST(' . bzj_ident($column) . ' AS UNSIGNED)
                 LIMIT ' . $sampleLimit
            );

            $sampleIds = [];
            while ($sample = $sampleResult->fetch_assoc()) {
                $sampleIds[] = (string) $sample['orphan_id'];
            }

            $conditions[] = '(' . $condition . ')';
            $columnFindings[] = [
                'column' => $column,
                'orphan_references' => $count,
                'sample_user_ids' => $sampleIds,
            ];
        }

        if (!$conditions) {
            continue;
        }

        $confirmedLookup[$table] = true;
        $combined = implode(' OR ', $conditions);

        $rowCountResult = bzj_query(
            $db,
            'SELECT COUNT(*) AS total FROM ' . bzj_ident($table) .
            ' WHERE ' . $combined
        );
        $rowCount = $rowCountResult->fetch_assoc();
        $engine = bzj_table_engine($db, $table);

        $findings[] = [
            'table' => $table,
            'columns' => $columnFindings,
            'orphan_rows' => (int) ($rowCount['total'] ?? 0),
            'automatic_cleanup' => true,
            'transactional' => $engine['transactional'],
            'engine' => $engine['engine'],
        ];
    }

    /*
     * Advisory discovery. A column name alone is not proof that it references
     * a platform user, so these findings are never automatically deleted.
     */
    $candidateColumns = [
        'user_id', 'userid', 'user', 'owner_id', 'creator_id', 'member_id',
        'poster_id', 'author_id', 'follower_id', 'following_id', 'from_id',
        'to_id', 'recipient_id', 'inviter_id', 'invited_id', 'blocked',
        'blocker', 'send_user_id', 'received_user_id', 'story_user_id',
        'product_owner_id', 'conversation_user_id', 'app_user_id', 'search_id',
        'follow_id', 'like_userid', 'block_userid', 'sender_id', 'receiver_id',
        'view_userid', 'report_userid',
    ];

    $discovery = [];

    foreach (bzj_tables($db) as $table) {
        if (
            strcasecmp($table, $resolvedAuth['table']) === 0 ||
            isset($confirmedLookup[$table])
        ) {
            continue;
        }

        $actualColumns = bzj_columns($db, $table);

        foreach ($candidateColumns as $candidate) {
            if (!isset($actualColumns[strtolower($candidate)])) {
                continue;
            }

            $column = $actualColumns[strtolower($candidate)];
            $condition = bzj_orphan_condition($column);

            $countResult = bzj_query(
                $db,
                'SELECT COUNT(*) AS total FROM ' . bzj_ident($table) .
                ' WHERE ' . $condition
            );
            $row = $countResult->fetch_assoc();
            $count = (int) ($row['total'] ?? 0);

            if ($count < 1) {
                continue;
            }

            $sampleResult = bzj_query(
                $db,
                'SELECT DISTINCT ' . bzj_ident($column) . ' AS orphan_id
                 FROM ' . bzj_ident($table) . '
                 WHERE ' . $condition . '
                 ORDER BY CAST(' . bzj_ident($column) . ' AS UNSIGNED)
                 LIMIT ' . BZJ_SAMPLE_LIMIT
            );

            $samples = [];
            while ($sample = $sampleResult->fetch_assoc()) {
                $samples[] = (string) $sample['orphan_id'];
            }

            $discovery[] = [
                'table' => $table,
                'column' => $column,
                'orphan_references' => $count,
                'sample_user_ids' => $samples,
                'automatic_cleanup' => false,
                'reason' => 'Schema-discovered reference; not in the confirmed deletion map.',
            ];
        }
    }

    return [
        'platform' => $platform,
        'authoritative_table' => $resolvedAuth['table'],
        'authoritative_id_column' => $resolvedAuth['id'],
        'existing_user_count' => $existingCount,
        'findings' => $findings,
        'discovery' => $discovery,
    ];
}

/* -------------------------------------------------------------------------
 * WordPress ↔ platform mapping scan
 * ---------------------------------------------------------------------- */

function bzj_load_platform_users(mysqli $db, string $platform): array
{
    $config = bzj_authoritative()[$platform];
    $resolved = bzj_authoritative_columns($db, $config);

    $result = bzj_query(
        $db,
        'SELECT ' . bzj_ident($resolved['id']) . ' AS platform_id,
                ' . bzj_ident($resolved['username']) . ' AS username,
                ' . bzj_ident($resolved['email']) . ' AS email
         FROM ' . bzj_ident($resolved['table'])
    );

    $users = [];

    while ($row = $result->fetch_assoc()) {
        $id = trim((string) ($row['platform_id'] ?? ''));

        if (!ctype_digit($id) || (int) $id < 1) {
            continue;
        }

        $users[$id] = [
            'id' => (int) $id,
            'username' => (string) ($row['username'] ?? ''),
            'email' => (string) ($row['email'] ?? ''),
        ];
    }

    return $users;
}

function bzj_index_users(array $users): array
{
    $index = ['email' => [], 'username' => []];

    foreach ($users as $user) {
        $email = bzj_normalize($user['email']);
        $username = bzj_normalize($user['username']);

        if ($email !== '') {
            $index['email'][$email][] = $user;
        }

        if ($username !== '') {
            $index['username'][$username][] = $user;
        }
    }

    return $index;
}

function bzj_wp_meta_value(
    mysqli $wp,
    string $metaTable,
    int $wpUserId,
    string $metaKey
): string {
    $key = $wp->real_escape_string($metaKey);

    $result = bzj_query(
        $wp,
        'SELECT meta_value
         FROM ' . bzj_ident($metaTable) . '
         WHERE user_id = ' . $wpUserId . '
           AND meta_key = \'' . $key . '\'
         ORDER BY umeta_id ASC
         LIMIT 1'
    );

    $row = $result->fetch_assoc();
    return (string) ($row['meta_value'] ?? '');
}

function bzj_mapping_scan(mysqli $wp, mysqli $wo, mysqli $qd): array
{
    $usersTable = bzj_wp_table('users');
    $metaTable = bzj_wp_table('usermeta');

    if (!bzj_table_exists($wp, $usersTable) || !bzj_table_exists($wp, $metaTable)) {
        bzj_fail('WordPress users/usermeta tables were not found.');
    }

    $woUsers = bzj_load_platform_users($wo, 'wowonder');
    $qdUsers = bzj_load_platform_users($qd, 'quickdate');

    $woIndex = bzj_index_users($woUsers);
    $qdIndex = bzj_index_users($qdUsers);

    $wpResult = bzj_query(
        $wp,
        'SELECT ID, user_login, user_email
         FROM ' . bzj_ident($usersTable) . '
         ORDER BY ID ASC'
    );

    $findings = [];
    $summary = [
        'wordpress_user_count' => 0,
        'wowonder_user_count' => count($woUsers),
        'quickdate_user_count' => count($qdUsers),
        'ok' => 0,
        'repairable' => 0,
        'ambiguous' => 0,
        'unmatched' => 0,
    ];

    while ($wpUser = $wpResult->fetch_assoc()) {
        $summary['wordpress_user_count']++;

        $wpId = (int) $wpUser['ID'];
        $wpUsername = bzj_normalize($wpUser['user_login'] ?? '');
        $wpEmail = bzj_normalize($wpUser['user_email'] ?? '');

        foreach ([
            'wowonder' => [
                'meta_key' => 'wo_user_id',
                'users' => $woUsers,
                'index' => $woIndex,
            ],
            'quickdate' => [
                'meta_key' => 'qd_user_id',
                'users' => $qdUsers,
                'index' => $qdIndex,
            ],
        ] as $platform => $data) {
            $emailMatches = (
                $wpEmail !== '' &&
                isset($data['index']['email'][$wpEmail])
            ) ? $data['index']['email'][$wpEmail] : [];

            $usernameMatches = (
                $wpUsername !== '' &&
                isset($data['index']['username'][$wpUsername])
            ) ? $data['index']['username'][$wpUsername] : [];

            $candidateIds = [];

            foreach (array_merge($emailMatches, $usernameMatches) as $match) {
                $candidateIds[(string) $match['id']] = true;
            }

            $candidateIds = array_keys($candidateIds);

            $storedId = trim(
                bzj_wp_meta_value(
                    $wp,
                    $metaTable,
                    $wpId,
                    $data['meta_key']
                )
            );

            $storedValid = ctype_digit($storedId) && (int) $storedId > 0;
            $storedExists = $storedValid &&
                isset($data['users'][(string) ((int) $storedId)]);

            $emailIds = [];
            foreach ($emailMatches as $match) {
                $emailIds[(string) $match['id']] = true;
            }

            $usernameIds = [];
            foreach ($usernameMatches as $match) {
                $usernameIds[(string) $match['id']] = true;
            }

            $sameEvidence = array_intersect(
                array_keys($emailIds),
                array_keys($usernameIds)
            );

            $status = 'ok';
            $repairable = false;
            $repairId = null;

            if (count($candidateIds) === 0) {
                $status = $storedId === ''
                    ? 'no_platform_match'
                    : (
                        $storedExists
                            ? 'stored_id_has_no_username_or_email_match'
                            : 'stored_id_missing_from_platform'
                    );
            } elseif (count($candidateIds) > 1) {
                $status = 'ambiguous_username_or_email_match';
            } elseif (
                count($emailMatches) > 1 ||
                count($usernameMatches) > 1
            ) {
                $status = 'ambiguous_duplicate_platform_identity';
            } else {
                $repairId = (int) $candidateIds[0];

                /*
                 * If both WordPress identifiers are populated, username and
                 * email must resolve to the same platform account.
                 * A single-field match is allowed when the other field is
                 * empty; it is still blocked if the populated field conflicts.
                 */
                $hasEmail = $wpEmail !== '';
                $hasUsername = $wpUsername !== '';

                $emailSupports = !$hasEmail || count($emailMatches) === 1;
                $usernameSupports =
                    !$hasUsername || count($usernameMatches) === 1;

                $bothAgree =
                    !$hasEmail ||
                    !$hasUsername ||
                    count($sameEvidence) === 1;

                if ($emailSupports && $usernameSupports && $bothAgree) {
                    $repairable = true;
                    $status = $storedId === (string) $repairId
                        ? 'ok'
                        : 'repairable_mismatch';
                } else {
                    $status = 'conflicting_identity_match';
                }
            }

            if ($status === 'ok') {
                $summary['ok']++;
                continue;
            }

            if ($repairable) {
                $summary['repairable']++;
            } elseif (in_array(
                $status,
                [
                    'ambiguous_username_or_email_match',
                    'ambiguous_duplicate_platform_identity',
                    'conflicting_identity_match',
                ],
                true
            )) {
                $summary['ambiguous']++;
            } else {
                $summary['unmatched']++;
            }

            $findings[] = [
                'wp_user_id' => $wpId,
                'wp_username' => (string) $wpUser['user_login'],
                'wp_email' => (string) $wpUser['user_email'],
                'platform' => $platform,
                'meta_key' => $data['meta_key'],
                'stored_id' => $storedId,
                'stored_id_exists' => $storedExists,
                'candidate_id' => $repairId,
                'repairable' => $repairable,
                'status' => $status,
                'email_match_count' => count($emailMatches),
                'username_match_count' => count($usernameMatches),
            ];
        }
    }

    return [
        'findings' => $findings,
        'summary' => $summary,
    ];
}

/* -------------------------------------------------------------------------
 * Destructive operations
 * ---------------------------------------------------------------------- */

function bzj_clean_platform(mysqli $db, string $platform, array $scan): array
{
    $results = [];
    $auth = bzj_authoritative()[$platform];
    $resolved = bzj_authoritative_columns($db, $auth);

    /*
     * Fresh authoritative snapshot immediately before deletion.
     * The previously displayed scan is informational only.
     */
    bzj_create_user_snapshot($db, $resolved['table'], $resolved['id']);

    foreach ($scan['findings'] as $finding) {
        $table = (string) $finding['table'];

        if (!bzj_table_exists($db, $table)) {
            continue;
        }

        $actualColumns = bzj_columns($db, $table);
        $conditions = [];

        foreach ($finding['columns'] as $columnFinding) {
            $key = strtolower((string) $columnFinding['column']);

            if (!isset($actualColumns[$key])) {
                continue;
            }

            $column = $actualColumns[$key];
            $conditions[] = '(' . bzj_orphan_condition($column) . ')';
        }

        if (!$conditions) {
            continue;
        }

        $sql = 'DELETE FROM ' . bzj_ident($table) .
            ' WHERE ' . implode(' OR ', $conditions);

        $engine = bzj_table_engine($db, $table);
        $transactionStarted = false;

        try {
            if ($engine['transactional']) {
                if (!$db->begin_transaction()) {
                    throw new RuntimeException(
                        'Unable to begin transaction for ' . $table
                    );
                }
                $transactionStarted = true;
            }

            bzj_query($db, $sql);
            $deleted = (int) $db->affected_rows;

            if ($transactionStarted && !$db->commit()) {
                throw new RuntimeException(
                    'Unable to commit transaction for ' . $table
                );
            }

            $results[] = [
                'platform' => $platform,
                'table' => $table,
                'deleted_rows' => $deleted,
                'status' => 'committed',
                'transactional' => $engine['transactional'],
                'engine' => $engine['engine'],
            ];
        } catch (Throwable $e) {
            if ($transactionStarted) {
                $db->rollback();
            }

            $results[] = [
                'platform' => $platform,
                'table' => $table,
                'deleted_rows' => 0,
                'status' => 'rolled_back_or_failed',
                'transactional' => $engine['transactional'],
                'engine' => $engine['engine'],
                'error' => $e->getMessage(),
            ];

            throw $e;
        }
    }

    return $results;
}

function bzj_repair_mappings(mysqli $wp, array $mappingScan): array
{
    $metaTable = bzj_wp_table('usermeta');

    if (!bzj_table_exists($wp, $metaTable)) {
        bzj_fail('WordPress usermeta table was not found.');
    }

    $results = [];

    foreach ($mappingScan['findings'] as $finding) {
        if (
            empty($finding['repairable']) ||
            $finding['candidate_id'] === null
        ) {
            continue;
        }

        $wpUserId = (int) $finding['wp_user_id'];
        $metaKey = (string) $finding['meta_key'];
        $newValue = (string) $finding['candidate_id'];
        $oldValue = (string) $finding['stored_id'];

        $escapedKey = $wp->real_escape_string($metaKey);
        $escapedValue = $wp->real_escape_string($newValue);

        $transactionStarted = false;

        try {
            if (!$wp->begin_transaction()) {
                throw new RuntimeException(
                    'Unable to begin WordPress metadata transaction.'
                );
            }

            $transactionStarted = true;

            bzj_query(
                $wp,
                'UPDATE ' . bzj_ident($metaTable) . '
                 SET meta_value = \'' . $escapedValue . '\'
                 WHERE user_id = ' . $wpUserId . '
                   AND meta_key = \'' . $escapedKey . '\''
            );

            $affected = (int) $wp->affected_rows;

            /*
             * If the metadata row did not exist, create it. If it existed but
             * already contained the candidate value, affected_rows can be 0;
             * in that case do not create a duplicate.
             */
            if ($affected === 0) {
                $existing = bzj_query(
                    $wp,
                    'SELECT umeta_id
                     FROM ' . bzj_ident($metaTable) . '
                     WHERE user_id = ' . $wpUserId . '
                       AND meta_key = \'' . $escapedKey . '\'
                     LIMIT 1'
                );

                if (!$existing->fetch_assoc()) {
                    bzj_query(
                        $wp,
                        'INSERT INTO ' . bzj_ident($metaTable) .
                        ' (user_id, meta_key, meta_value)
                         VALUES (' . $wpUserId . ', \'' .
                        $escapedKey . '\', \'' . $escapedValue . '\')'
                    );
                    $affected = 1;
                }
            }

            if (!$wp->commit()) {
                throw new RuntimeException(
                    'Unable to commit WordPress metadata transaction.'
                );
            }

            $results[] = [
                'wp_user_id' => $wpUserId,
                'platform' => $finding['platform'],
                'meta_key' => $metaKey,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'status' => 'committed',
                'affected' => $affected,
            ];
        } catch (Throwable $e) {
            if ($transactionStarted) {
                $wp->rollback();
            }

            $results[] = [
                'wp_user_id' => $wpUserId,
                'platform' => $finding['platform'],
                'meta_key' => $metaKey,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'status' => 'rolled_back_or_failed',
                'error' => $e->getMessage(),
            ];

            throw $e;
        }
    }

    return $results;
}

/* -------------------------------------------------------------------------
 * Full scan and rendering
 * ---------------------------------------------------------------------- */

function bzj_full_scan(array $connections): array
{
    return [
        'version' => BZJ_CLEANER_VERSION,
        'timestamp_utc' => gmdate('c'),
        'wowonder' => bzj_scan_platform('wowonder', $connections['wo']),
        'quickdate' => bzj_scan_platform('quickdate', $connections['qd']),
        'mapping' => bzj_mapping_scan(
            $connections['wp'],
            $connections['wo'],
            $connections['qd']
        ),
    ];
}

function bzj_render_orphans(array $scan): string
{
    $html = '';

    foreach (['wowonder', 'quickdate'] as $platform) {
        $label = $platform === 'wowonder' ? 'WoWonder' : 'QuickDate';

        $html .= '<h4>' . bzj_h($label) . ' — ' .
            (int) $scan[$platform]['existing_user_count'] .
            ' authoritative users</h4>';

        if (!$scan[$platform]['findings']) {
            $html .= '<p class="success">No confirmed orphan reference rows found.</p>';
        } else {
            $html .= '<table><thead><tr>' .
                '<th>Table</th><th>Reference columns</th>' .
                '<th>Rows to remove</th><th>Engine</th><th>Sample orphan IDs</th>' .
                '</tr></thead><tbody>';

            foreach ($scan[$platform]['findings'] as $finding) {
                $html .= '<tr><td><code>' .
                    bzj_h($finding['table']) .
                    '</code></td><td>';

                foreach ($finding['columns'] as $col) {
                    $html .= '<div><code>' .
                        bzj_h($col['column']) .
                        '</code>: ' .
                        (int) $col['orphan_references'] .
                        '</div>';
                }

                $html .= '</td><td><strong>' .
                    (int) $finding['orphan_rows'] .
                    '</strong></td>';

                $engine = $finding['engine'] !== ''
                    ? $finding['engine']
                    : 'unknown';
                $engine .= !empty($finding['transactional'])
                    ? ' / transactional'
                    : ' / non-transactional';

                $html .= '<td>' . bzj_h($engine) . '</td><td>';

                foreach ($finding['columns'] as $col) {
                    $html .= '<div><code>' .
                        bzj_h($col['column']) .
                        '</code>: ' .
                        bzj_h(implode(', ', $col['sample_user_ids'])) .
                        '</div>';
                }

                $html .= '</td></tr>';
            }

            $html .= '</tbody></table>';
        }

        if ($scan[$platform]['discovery']) {
            $html .= '<h5>Advisory schema discovery — NOT automatically deleted</h5>';
            $html .= '<table><thead><tr>' .
                '<th>Table</th><th>Column</th>' .
                '<th>Orphan references</th><th>Sample IDs</th>' .
                '</tr></thead><tbody>';

            foreach ($scan[$platform]['discovery'] as $finding) {
                $html .= '<tr><td><code>' .
                    bzj_h($finding['table']) .
                    '</code></td><td><code>' .
                    bzj_h($finding['column']) .
                    '</code></td><td>' .
                    (int) $finding['orphan_references'] .
                    '</td><td>' .
                    bzj_h(implode(', ', $finding['sample_user_ids'])) .
                    '</td></tr>';
            }

            $html .= '</tbody></table>';
        } else {
            $html .= '<p class="muted">No additional schema-discovered orphan references found.</p>';
        }
    }

    return $html;
}

function bzj_render_mapping(array $mapping): string
{
    $summary = $mapping['summary'];

    $html = '<div class="stats">';

    foreach ([
        'wordpress_user_count' => 'WordPress users',
        'wowonder_user_count' => 'WoWonder users',
        'quickdate_user_count' => 'QuickDate users',
        'ok' => 'OK mappings',
        'repairable' => 'Repairable',
        'ambiguous' => 'Ambiguous/conflicting',
        'unmatched' => 'Unmatched',
    ] as $key => $label) {
        $html .= '<div class="stat"><span>' .
            bzj_h($label) .
            '</span><strong>' .
            (int) ($summary[$key] ?? 0) .
            '</strong></div>';
    }

    $html .= '</div>';

    if (!$mapping['findings']) {
        return $html .
            '<p class="success">All scanned WordPress platform IDs are aligned.</p>';
    }

    $html .= '<table><thead><tr>' .
        '<th>WP ID</th><th>Username</th><th>Email</th>' .
        '<th>Platform</th><th>Meta key</th><th>Stored ID</th>' .
        '<th>Matched ID</th><th>Status</th><th>Repair?</th>' .
        '</tr></thead><tbody>';

    foreach ($mapping['findings'] as $f) {
        $html .= '<tr>' .
            '<td>' . (int) $f['wp_user_id'] . '</td>' .
            '<td>' . bzj_h($f['wp_username']) . '</td>' .
            '<td>' . bzj_h($f['wp_email']) . '</td>' .
            '<td>' . bzj_h($f['platform']) . '</td>' .
            '<td><code>' . bzj_h($f['meta_key']) . '</code></td>' .
            '<td>' . bzj_h($f['stored_id']) . '</td>' .
            '<td>' . bzj_h($f['candidate_id'] ?? '') . '</td>' .
            '<td><code>' . bzj_h($f['status']) . '</code></td>' .
            '<td>' .
            (
                !empty($f['repairable'])
                    ? '<strong class="success-text">YES</strong>'
                    : '<strong class="danger-text">NO</strong>'
            ) .
            '</td></tr>';
    }

    return $html . '</tbody></table>';
}

function bzj_render_results(array $results): string
{
    if (!$results) {
        return '<p class="muted">No rows or metadata required processing.</p>';
    }

    $html = '<table><thead><tr>' .
        '<th>Platform</th><th>Target</th><th>Action</th>' .
        '<th>Status</th><th>Details</th>' .
        '</tr></thead><tbody>';

    foreach ($results as $r) {
        $target = $r['table'] ??
            ('WP user ' . ($r['wp_user_id'] ?? ''));

        if (isset($r['deleted_rows'])) {
            $action = 'Deleted ' . (int) $r['deleted_rows'] . ' row(s)';
        } else {
            $action = 'Metadata update ' .
                bzj_h($r['meta_key'] ?? '');
        }

        $details = $r['error'] ?? '';

        if ($details === '' && isset($r['old_value'])) {
            $details = 'Old: ' . $r['old_value'] .
                ' → New: ' . $r['new_value'];
        }

        if ($details === '' && isset($r['engine'])) {
            $details = $r['engine'] .
                (!empty($r['transactional'])
                    ? ' / transaction'
                    : ' / non-transactional');
        }

        $html .= '<tr><td>' .
            bzj_h($r['platform'] ?? '') .
            '</td><td>' .
            bzj_h($target) .
            '</td><td>' .
            $action .
            '</td><td>' .
            bzj_h($r['status'] ?? '') .
            '</td><td>' .
            bzj_h($details) .
            '</td></tr>';
    }

    return $html . '</tbody></table>';
}

/* -------------------------------------------------------------------------
 * Request handling
 * ---------------------------------------------------------------------- */

$action = isset($_POST['bzj_action'])
    ? trim((string) $_POST['bzj_action'])
    : '';

$scan = null;
$operationResults = [];
$message = '';
$error = '';
$reportPath = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postedToken = isset($_POST['bzj_csrf'])
            ? (string) $_POST['bzj_csrf']
            : '';

        if ($postedToken === '' || !hash_equals($csrfToken, $postedToken)) {
            bzj_fail('Invalid security token. Please reload the page.');
        }

        $connections = bzj_connections();

        if ($action === 'scan') {
            $scan = bzj_full_scan($connections);

            $reportPath = bzj_write_report([
                'type' => 'dry_run',
                'scan' => $scan,
            ]);

            bzj_log([
                'event' => 'dry_run_scan',
                'scan' => $scan,
                'report' => $reportPath,
            ]);

            $message = 'Dry run completed. No database rows were changed.';
        } elseif ($action === 'clean') {
            $phrase = trim((string) ($_POST['confirmation_phrase'] ?? ''));

            if (!hash_equals(BZJ_CLEAN_PHRASE, $phrase)) {
                bzj_fail('The orphan-cleanup confirmation phrase is incorrect.');
            }

            /* Fresh scan immediately before destructive work. */
            $fresh = bzj_full_scan($connections);
            $results = [];

            $results = array_merge(
                $results,
                bzj_clean_platform(
                    $connections['wo'],
                    'wowonder',
                    $fresh['wowonder']
                )
            );

            $results = array_merge(
                $results,
                bzj_clean_platform(
                    $connections['qd'],
                    'quickdate',
                    $fresh['quickdate']
                )
            );

            /* Full post-clean verification. */
            $verification = bzj_full_scan($connections);

            $operationResults = $results;
            $scan = $verification;

            $reportPath = bzj_write_report([
                'type' => 'orphan_cleanup',
                'before' => $fresh,
                'results' => $results,
                'after' => $verification,
            ]);

            bzj_log([
                'event' => 'orphan_cleanup',
                'before' => $fresh,
                'results' => $results,
                'after' => $verification,
                'report' => $reportPath,
            ]);

            $message = 'Orphan cleanup completed and verified.';
        } elseif ($action === 'repair') {
            $phrase = trim((string) ($_POST['confirmation_phrase'] ?? ''));

            if (!hash_equals(BZJ_REPAIR_PHRASE, $phrase)) {
                bzj_fail('The metadata-repair confirmation phrase is incorrect.');
            }

            /* Fresh identity scan immediately before repair. */
            $freshMapping = bzj_mapping_scan(
                $connections['wp'],
                $connections['wo'],
                $connections['qd']
            );

            $results = bzj_repair_mappings(
                $connections['wp'],
                $freshMapping
            );

            $verification = bzj_full_scan($connections);

            $operationResults = $results;
            $scan = $verification;

            $reportPath = bzj_write_report([
                'type' => 'wordpress_mapping_repair',
                'before' => $freshMapping,
                'results' => $results,
                'after' => $verification['mapping'],
            ]);

            bzj_log([
                'event' => 'wordpress_mapping_repair',
                'before' => $freshMapping,
                'results' => $results,
                'after' => $verification['mapping'],
                'report' => $reportPath,
            ]);

            $message = 'WordPress platform-ID repair completed and verified.';
        } else {
            $scan = bzj_full_scan($connections);
            $message = 'Dry run completed. No database rows were changed.';
        }
    }
} catch (Throwable $e) {
    $error = $e->getMessage();

    bzj_log([
        'event' => 'error',
        'action' => $action,
        'error' => $error,
    ]);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Buzzjuice WoWonder + QuickDate User Data Auditor</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{margin:0;padding:24px;background:#f4f6f8;color:#202124;font:14px/1.5 Arial,sans-serif}
main{max-width:1450px;margin:0 auto;background:#fff;padding:26px;border-radius:10px;box-shadow:0 2px 14px rgba(0,0,0,.08)}
h1,h2,h3,h4,h5{color:#172b4d}
h1{margin-top:0}.panel{border:1px solid #d9e0e7;border-radius:8px;padding:18px;margin:18px 0}
.notice,.warning,.success,.danger{padding:12px 14px;border-radius:6px;margin:12px 0}
.notice{background:#eaf2ff;border-left:4px solid #2463eb}
.warning{background:#fff7df;border-left:4px solid #d99b00}
.success{background:#eaf8ee;border-left:4px solid #2e9d54}
.danger{background:#fff0f0;border-left:4px solid #d93025}
.muted{color:#687078}
table{width:100%;border-collapse:collapse;margin:12px 0 22px;font-size:13px}
th,td{border:1px solid #d9e0e7;padding:8px;text-align:left;vertical-align:top}
th{background:#eef2f6}
code{font-family:Consolas,Monaco,monospace}
button{border:0;border-radius:5px;padding:10px 15px;margin:4px 8px 4px 0;cursor:pointer;color:#fff;background:#2463eb}
button.danger-button{background:#c62828}
button.repair-button{background:#7c3aed}
input[type=text]{padding:9px;border:1px solid #b9c1c9;border-radius:5px;min-width:300px}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(145px,1fr));gap:10px;margin:12px 0 18px}
.stat{border:1px solid #d9e0e7;border-radius:7px;padding:12px;background:#fafbfc}
.stat span{display:block;color:#687078;font-size:12px}
.stat strong{font-size:22px}
.success-text{color:#176b35}
.danger-text{color:#9d1c1c}
.small{font-size:12px}
.sticky{position:sticky;top:0;background:#fff;padding:8px 0;z-index:2}
</style>
</head>
<body>
<main>
<div class="sticky">
<h1>Buzzjuice WoWonder + QuickDate User Data Auditor</h1>
<p class="small">
Version <?= bzj_h(BZJ_CLEANER_VERSION) ?> · Signed in as WP administrator #<?= (int) get_current_user_id() ?>
</p>
</div>

<div class="notice">
<strong>Authoritative sources:</strong>
WoWonder <code>Wo_Users.user_id</code> and QuickDate <code>users.id</code>.
The cleaner removes only orphaned reference rows; it does
<strong>not</strong> delete authoritative users, media files, or S3 objects.
</div>

<?php if ($message !== ''): ?>
<div class="success">
<?= bzj_h($message) ?>
<?php if ($reportPath): ?>
<span class="small">Report: <?= bzj_h($reportPath) ?></span>
<?php endif; ?>
</div>
<?php endif; ?>

<?php if ($error !== ''): ?>
<div class="danger">
<strong>Operation failed:</strong> <?= bzj_h($error) ?>
</div>
<?php endif; ?>

<section class="panel">
<h2>1. Dry run / audit scan</h2>
<p>
Scans confirmed reference tables in both platforms for user IDs that no longer exist
in their authoritative user table. It also scans WordPress
<code>wo_user_id</code> and <code>qd_user_id</code> against platform
username/email records.
</p>
<form method="post">
<input type="hidden" name="bzj_csrf" value="<?= bzj_h($csrfToken) ?>">
<input type="hidden" name="bzj_action" value="scan">
<button type="submit">Start dry run</button>
</form>
</section>

<?php if (is_array($scan)): ?>
<section class="panel">
<h2>2. Dry-run results</h2>
<?= bzj_render_orphans($scan) ?>

<h3>WordPress ↔ platform ID integrity</h3>
<?= bzj_render_mapping($scan['mapping']) ?>
</section>

<section class="panel">
<h2>3. Clean confirmed orphan rows</h2>
<div class="warning">
This performs a <strong>fresh scan immediately before deletion</strong>.
Only the confirmed reference-map findings are deleted. Advisory schema-discovered
findings are displayed for manual review and are not deleted by this tool.
</div>
<p>Type exactly:</p>
<p><code><?= bzj_h(BZJ_CLEAN_PHRASE) ?></code></p>
<form method="post" onsubmit="return confirm('This will delete confirmed orphan reference rows from WoWonder and QuickDate. Continue?');">
<input type="hidden" name="bzj_csrf" value="<?= bzj_h($csrfToken) ?>">
<input type="hidden" name="bzj_action" value="clean">
<input type="text" name="confirmation_phrase" autocomplete="off" placeholder="Confirmation phrase">
<button class="danger-button" type="submit">Delete confirmed orphan rows</button>
</form>
</section>

<section class="panel">
<h2>4. Repair WordPress platform IDs</h2>
<p>
Only uniquely identified, non-conflicting matches are repaired. If username and email
point to different platform accounts, or either field produces multiple platform users,
the record is flagged and left unchanged.
</p>
<p>Type exactly:</p>
<p><code><?= bzj_h(BZJ_REPAIR_PHRASE) ?></code></p>
<form method="post" onsubmit="return confirm('This will update only unambiguous WordPress platform-ID metadata. Continue?');">
<input type="hidden" name="bzj_csrf" value="<?= bzj_h($csrfToken) ?>">
<input type="hidden" name="bzj_action" value="repair">
<input type="text" name="confirmation_phrase" autocomplete="off" placeholder="Confirmation phrase">
<button class="repair-button" type="submit">Repair unambiguous WordPress IDs</button>
</form>
</section>
<?php endif; ?>

<?php if ($operationResults): ?>
<section class="panel">
<h2>5. Operation results</h2>
<?= bzj_render_results($operationResults) ?>
<?php if (is_array($scan)): ?>
<p><strong>Post-operation verification:</strong> the current scan above reflects the database after processing.</p>
<?php endif; ?>
</section>
<?php endif; ?>

<section class="panel">
<h2>Important safeguards</h2>
<ul>
<li><strong>Never deletes <code>Wo_Users</code> or QuickDate <code>users</code>.</strong></li>
<li><strong>Never deletes local media or S3 objects.</strong> This avoids accidentally removing files still referenced elsewhere.</li>
<li><strong>Confirmed map only:</strong> automatic deletion is restricted to references established by the supplied platform deletion functions.</li>
<li><strong>Advisory discovery:</strong> other tables with plausible user-reference columns are reported separately for schema review.</li>
<li><strong>Fresh destructive scan:</strong> the dry-run result is never reused blindly for deletion.</li>
<li><strong>Transactions:</strong> InnoDB tables are processed inside per-table transactions; non-transactional tables are explicitly reported.</li>
<li><strong>Mapping repair is conservative:</strong> ambiguous or conflicting username/email matches are never repaired automatically.</li>
<li><strong>Audit trail:</strong> scans and destructive actions are logged under <code>data/logs/</code> when writable.</li>
</ul>
</section>

</main>
</body>
</html>
