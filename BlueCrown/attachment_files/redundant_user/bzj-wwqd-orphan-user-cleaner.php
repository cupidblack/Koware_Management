<?php
/**
 * Buzzjuice WoWonder + QuickDate orphan-user cleaner and platform-ID auditor.
 *
 * Install:
 *   buzzjuice.net/shared/bzj-wwqd-orphan-user-cleaner.php
 *
 * Safety model:
 * - Web/admin UI only; WordPress administrator (manage_options) required.
 * - GET is read-only. POST requires a WordPress nonce AND BZJ_ORPHAN_CLEANER_KEY.
 * - Dry-run never changes database data.
 * - Wo_Users and QuickDate users are authoritative and are NEVER deleted.
 * - Media/S3 objects are NEVER deleted by this utility.
 * - Only references derived from the supplied deletion functions are automatically cleaned.
 * - Schema-discovered references are advisory only.
 * - Clean/repair always performs a fresh scan immediately before writing.
 * - Mapping repair requires an unambiguous username/email identity match.
 * - Existing bzj_log() is reused when available; it is NEVER redeclared here.
 *
 * Based on:
 * - Wo_DeleteUser()
 * - QuickDate delete_user()
 * - shared/db_helpers.php
 */
declare(strict_types=1);

if (PHP_SAPI === 'cli') {
    http_response_code(403);
    exit("This utility is web-admin only.\n");
}

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

const BZJ_ORPHAN_CLEANER_VERSION = '2026-09-18.4';
const BZJ_CLEAN_PHRASE = 'DELETE CONFIRMED ORPHANS';
const BZJ_REPAIR_PHRASE = 'REPAIR CONFIRMED ID MISMATCHES';
const BZJ_SAMPLE_LIMIT = 25;
const BZJ_MAX_SAMPLE_LIMIT = 100;

/*
 * Load WordPress first. This ensures the real WP table prefix, authentication,
 * nonce functions and MU plugins (including bzj-registration-kernel.php) exist.
 */
$wpLoad = dirname(__DIR__) . '/wp-load.php';
if (!is_file($wpLoad)) {
    http_response_code(500);
    exit('WordPress bootstrap could not be located.');
}
require_once $wpLoad;

require_once __DIR__ . '/db_helpers.php';

if (
    !function_exists('is_user_logged_in') ||
    !function_exists('current_user_can') ||
    !function_exists('wp_create_nonce') ||
    !function_exists('wp_verify_nonce')
) {
    http_response_code(500);
    exit('Required WordPress functions are unavailable.');
}

if (!is_user_logged_in() || !current_user_can('manage_options')) {
    http_response_code(403);
    exit('Administrator access is required.');
}

/* -------------------------------------------------------------------------
 * General helpers
 * ---------------------------------------------------------------------- */

function bzj_ou_h(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function bzj_ou_fail(string $message): never
{
    throw new RuntimeException($message);
}

function bzj_ou_identifier(string $identifier): string
{
    /*
     * One historical WoWonder column in the supplied delete function is
     * literally named "from_id " with a trailing space. Preserve support for it.
     */
    if (!preg_match('/^[A-Za-z0-9_]+ ?$/', $identifier)) {
        bzj_ou_fail('Unsafe SQL identifier: ' . $identifier);
    }
    return '`' . $identifier . '`';
}

function bzj_ou_query(mysqli $db, string $sql): mysqli_result|bool
{
    $result = $db->query($sql);
    if ($result === false) {
        throw new RuntimeException($db->error . ' | SQL: ' . $sql);
    }
    return $result;
}

function bzj_ou_table_exists(mysqli $db, string $table): bool
{
    $escaped = $db->real_escape_string($table);
    $result = bzj_ou_query($db, "SHOW TABLES LIKE '{$escaped}'");
    return $result instanceof mysqli_result && $result->num_rows > 0;
}

function bzj_ou_columns(mysqli $db, string $table): array
{
    $result = bzj_ou_query(
        $db,
        'SHOW COLUMNS FROM ' . bzj_ou_identifier($table)
    );
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Field'])) {
            $columns[strtolower((string)$row['Field'])] = (string)$row['Field'];
        }
    }
    return $columns;
}

function bzj_ou_tables(mysqli $db): array
{
    $result = bzj_ou_query($db, 'SHOW TABLES');
    $tables = [];
    while ($row = $result->fetch_row()) {
        if (isset($row[0])) {
            $tables[] = (string)$row[0];
        }
    }
    return $tables;
}

function bzj_ou_engine(mysqli $db, string $table): array
{
    $escaped = $db->real_escape_string($table);
    $result = bzj_ou_query($db, "SHOW TABLE STATUS LIKE '{$escaped}'");
    $row = $result instanceof mysqli_result ? $result->fetch_assoc() : [];
    $engine = (string)($row['Engine'] ?? '');
    return [
        'engine' => $engine,
        'transactional' => strcasecmp($engine, 'InnoDB') === 0,
    ];
}

function bzj_ou_normalize(mixed $value): string
{
    $value = trim((string)$value);
    return function_exists('mb_strtolower')
        ? mb_strtolower($value, 'UTF-8')
        : strtolower($value);
}

function bzj_ou_json(mixed $value): string
{
    $json = json_encode(
        $value,
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_INVALID_UTF8_SUBSTITUTE
    );
    return $json === false ? '{}' : $json;
}

function bzj_ou_audit(string $event, array $data = []): void
{
    /*
     * bzj_registration_kernel.php already provides bzj_log().
     * Do NOT redeclare bzj_log(): doing so can cause a fatal "Cannot redeclare"
     * error and would also discard the site's established logging format.
     */
    if (function_exists('bzj_log')) {
        try {
            /*
             * The existing kernel's signature is site-specific. The known
             * registration kernel accepts ($type, $data), so use that shape.
             */
            bzj_log($event, $data);
            return;
        } catch (Throwable $e) {
            error_log('BZJ orphan cleaner: bzj_log failed: ' . $e->getMessage());
        }
    }

    error_log(
        'BZJ orphan cleaner [' . $event . '] ' . bzj_ou_json($data)
    );
}

/* -------------------------------------------------------------------------
 * Security
 * ---------------------------------------------------------------------- */

function bzj_ou_configured_key(): string
{
    $key = getenv('BZJ_ORPHAN_CLEANER_KEY');
    if (!is_string($key) || trim($key) === '') {
        bzj_ou_fail('BZJ_ORPHAN_CLEANER_KEY is missing from the environment.');
    }
    return trim($key);
}

function bzj_ou_check_post_security(): void
{
    $nonce = isset($_POST['bzj_nonce']) ? (string)$_POST['bzj_nonce'] : '';
    if ($nonce === '' || !wp_verify_nonce($nonce, 'bzj_ou_action')) {
        bzj_ou_fail('Invalid security token. Please reload the page.');
    }

    $postedKey = isset($_POST['bzj_access_key'])
        ? trim((string)$_POST['bzj_access_key'])
        : '';

    if (
        $postedKey === '' ||
        !hash_equals(bzj_ou_configured_key(), $postedKey)
    ) {
        bzj_ou_fail('Invalid cleaner access key.');
    }
}

/* -------------------------------------------------------------------------
 * Database configuration
 * ---------------------------------------------------------------------- */

function bzj_ou_connections(): array
{
    $wp = get_wp_db_conn();
    $wo = get_wowonder_db();
    $qd = get_qd_db_conn();

    foreach (['wp' => $wp, 'wo' => $wo, 'qd' => $qd] as $name => $connection) {
        if (!$connection instanceof mysqli || $connection->connect_errno) {
            bzj_ou_fail(ucfirst($name) . ' database connection failed.');
        }
        $connection->set_charset('utf8mb4');
    }

    return ['wp' => $wp, 'wo' => $wo, 'qd' => $qd];
}

function bzj_ou_wp_table(string $name): string
{
    $prefix = defined('WP_TABLE_PREFIX') ? (string)WP_TABLE_PREFIX : 'wp_';

    /*
     * WordPress's actual runtime prefix is authoritative when available.
     * db_helpers.php's WP_TABLE_PREFIX is normally wp_, but a custom prefix
     * must not be silently ignored.
     */
    global $table_prefix;
    if (isset($table_prefix) && is_string($table_prefix) && $table_prefix !== '') {
        $prefix = $table_prefix;
    }

    if (!preg_match('/^[A-Za-z0-9_]+$/', $prefix)) {
        bzj_ou_fail('Unsafe WordPress table prefix.');
    }
    return $prefix . $name;
}

/* -------------------------------------------------------------------------
 * Authoritative platform configuration
 * ---------------------------------------------------------------------- */

function bzj_ou_platforms(): array
{
    return [
        'wowonder' => [
            'label' => 'WoWonder Streams',
            'db_key' => 'wo',
            'users_table' => 'Wo_Users',
            'id_column' => 'user_id',
            'username_column' => 'username',
            'email_column' => 'email',
            'meta_key' => 'wo_user_id',
            'references' => [
                'Wo_Users_Fields' => ['user_id'],
                'Wo_RecentSearches' => ['user_id', 'search_id'],
                'Wo_GamesPlayers' => ['user_id'],
                'Wo_UserProjects' => ['user_id'],
                'Wo_UserOpenTo' => ['user_id'],
                'Wo_Followers' => ['follower_id', 'following_id'],
                'Wo_Messages' => ['from_id', 'to_id'],
                'Wo_VideoCalls' => ['from_id', 'to_id'],
                'Wo_AudioCalls' => ['from_id', 'to_id'],
                'Wo_Agora' => ['from_id', 'from_id ', 'to_id'],
                'Wo_Notification' => ['notifier_id', 'recipient_id'],
                'Wo_Reports' => ['user_id'],
                'Wo_AppSessions' => ['user_id'],
                'Wo_AppsSessions' => ['user_id'],
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
        ],
        'quickdate' => [
            'label' => 'QuickDate Socials',
            'db_key' => 'qd',
            'users_table' => defined('QD_USERS_TABLE') ? QD_USERS_TABLE : 'users',
            'id_column' => 'id',
            'username_column' => 'username',
            'email_column' => 'email',
            'meta_key' => 'qd_user_id',
            'references' => [
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
        ],
    ];
}

function bzj_ou_aliases(): array
{
    return [
        'Wo_AppSessions' => ['Wo_AppSessions', 'Wo_AppsSessions'],
        'Wo_AppsSessions' => ['Wo_AppsSessions', 'Wo_AppSessions'],
        'Wo_EventsInterested' => ['Wo_EventsInterested', 'Wo_EventsInt'],
        'Wo_EventsInt' => ['Wo_EventsInt', 'Wo_EventsInterested'],
        'Wo_PagesInvites' => ['Wo_PagesInvites', 'Wo_PagesInvaites'],
        'Wo_PagesInvaites' => ['Wo_PagesInvaites', 'Wo_PagesInvites'],
        'Wo_LiveSubscriptions' => ['Wo_LiveSubscriptions', 'Wo_LiveSub'],
        'Wo_LiveSub' => ['Wo_LiveSub', 'Wo_LiveSubscriptions'],
        'Wo_Email' => ['Wo_Email', 'Wo_Emails'],
        'Wo_Emails' => ['Wo_Emails', 'Wo_Email'],
        'Wo_UserMonetization' => ['Wo_UserMonetization', 'Wo_UserMonetizations'],
        'Wo_UserMonetizations' => ['Wo_UserMonetizations', 'Wo_UserMonetization'],
        'Wo_MonetizationSubscription' => [
            'Wo_MonetizationSubscription',
            'Wo_MonetizationSubscribtion',
            'Wo_MonetizationSubscriptions',
        ],
        'Wo_MonetizationSubscribtion' => [
            'Wo_MonetizationSubscribtion',
            'Wo_MonetizationSubscription',
            'Wo_MonetizationSubscriptions',
        ],
        'Wo_MonetizationSubscriptions' => [
            'Wo_MonetizationSubscriptions',
            'Wo_MonetizationSubscription',
            'Wo_MonetizationSubscribtion',
        ],
    ];
}

function bzj_ou_resolve_table(mysqli $db, string $configured): ?string
{
    foreach (bzj_ou_aliases()[$configured] ?? [$configured] as $candidate) {
        if (bzj_ou_table_exists($db, $candidate)) {
            return $candidate;
        }
    }
    return null;
}

function bzj_ou_authoritative(mysqli $db, array $platform): array
{
    $table = $platform['users_table'];
    if (!bzj_ou_table_exists($db, $table)) {
        bzj_ou_fail('Authoritative table does not exist: ' . $table);
    }

    $columns = bzj_ou_columns($db, $table);
    foreach ([
        $platform['id_column'],
        $platform['username_column'],
        $platform['email_column'],
    ] as $required) {
        if (!isset($columns[strtolower($required)])) {
            bzj_ou_fail(
                'Required column missing from ' . $table . ': ' . $required
            );
        }
    }

    return [
        'table' => $table,
        'id' => $columns[strtolower($platform['id_column'])],
        'username' => $columns[strtolower($platform['username_column'])],
        'email' => $columns[strtolower($platform['email_column'])],
    ];
}

function bzj_ou_orphan_condition(
    string $referenceColumn,
    string $authTable,
    string $authId
): string {
    $ref = bzj_ou_identifier($referenceColumn);
    $users = bzj_ou_identifier($authTable);
    $id = bzj_ou_identifier($authId);

    /*
     * Only positive numeric IDs are treated as platform-user references.
     * NULL, empty, zero and non-numeric values are not automatically deleted.
     */
    return "
        {$ref} IS NOT NULL
        AND TRIM(CAST({$ref} AS CHAR)) REGEXP '^[0-9]+$'
        AND CAST({$ref} AS UNSIGNED) > 0
        AND NOT EXISTS (
            SELECT 1
            FROM {$users} AS authoritative_users
            WHERE CAST(authoritative_users.{$id} AS UNSIGNED)
                = CAST({$ref} AS UNSIGNED)
        )
    ";
}

function bzj_ou_count_users(
    mysqli $db,
    string $table,
    string $idColumn
): int {
    $result = bzj_ou_query(
        $db,
        'SELECT COUNT(*) AS total FROM ' .
        bzj_ou_identifier($table) .
        ' WHERE ' . bzj_ou_identifier($idColumn) . ' IS NOT NULL'
    );
    $row = $result->fetch_assoc();
    return (int)($row['total'] ?? 0);
}

/* -------------------------------------------------------------------------
 * Orphan scan
 * ---------------------------------------------------------------------- */

function bzj_ou_scan_platform(
    mysqli $db,
    string $platformKey,
    int $sampleLimit = BZJ_SAMPLE_LIMIT
): array {
    $platform = bzj_ou_platforms()[$platformKey];
    $auth = bzj_ou_authoritative($db, $platform);
    $sampleLimit = max(1, min($sampleLimit, BZJ_MAX_SAMPLE_LIMIT));

    $findings = [];
    $confirmedTables = [];

    foreach ($platform['references'] as $configuredTable => $configuredColumns) {
        $table = bzj_ou_resolve_table($db, $configuredTable);
        if ($table === null || strcasecmp($table, $auth['table']) === 0) {
            continue;
        }

        $actualColumns = bzj_ou_columns($db, $table);
        $conditions = [];
        $columnFindings = [];

        foreach ($configuredColumns as $configuredColumn) {
            $key = strtolower($configuredColumn);
            if (!isset($actualColumns[$key])) {
                continue;
            }

            $column = $actualColumns[$key];
            $condition = bzj_ou_orphan_condition(
                $column,
                $auth['table'],
                $auth['id']
            );

            $countResult = bzj_ou_query(
                $db,
                'SELECT COUNT(*) AS total FROM ' .
                bzj_ou_identifier($table) .
                ' WHERE ' . $condition
            );
            $countRow = $countResult->fetch_assoc();
            $count = (int)($countRow['total'] ?? 0);

            if ($count < 1) {
                continue;
            }

            $sampleResult = bzj_ou_query(
                $db,
                'SELECT DISTINCT ' . bzj_ou_identifier($column) .
                ' AS orphan_id FROM ' . bzj_ou_identifier($table) .
                ' WHERE ' . $condition .
                ' ORDER BY CAST(' . bzj_ou_identifier($column) .
                ' AS UNSIGNED) LIMIT ' . $sampleLimit
            );

            $sampleIds = [];
            while ($sample = $sampleResult->fetch_assoc()) {
                $sampleIds[] = (string)($sample['orphan_id'] ?? '');
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

        $confirmedTables[$table] = true;
        $combined = implode(' OR ', $conditions);

        $rowCountResult = bzj_ou_query(
            $db,
            'SELECT COUNT(*) AS total FROM ' .
            bzj_ou_identifier($table) .
            ' WHERE ' . $combined
        );
        $rowCount = $rowCountResult->fetch_assoc();
        $engine = bzj_ou_engine($db, $table);

        $findings[] = [
            'table' => $table,
            'columns' => $columnFindings,
            'orphan_rows' => (int)($rowCount['total'] ?? 0),
            'transactional' => $engine['transactional'],
            'engine' => $engine['engine'],
        ];
    }

    /*
     * Advisory discovery:
     * Search the actual schema for common user-reference column names that
     * are NOT already in the confirmed deletion map. This helps identify
     * custom/plugin tables without treating a column name alone as proof of
     * ownership. These findings are never automatically deleted.
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

    foreach (bzj_ou_tables($db) as $table) {
        if (
            strcasecmp($table, $auth['table']) === 0 ||
            isset($confirmedTables[$table])
        ) {
            continue;
        }

        $actualColumns = bzj_ou_columns($db, $table);

        foreach ($candidateColumns as $candidate) {
            if (!isset($actualColumns[strtolower($candidate)])) {
                continue;
            }

            $column = $actualColumns[strtolower($candidate)];
            $condition = bzj_ou_orphan_condition(
                $column,
                $auth['table'],
                $auth['id']
            );

            $countResult = bzj_ou_query(
                $db,
                'SELECT COUNT(*) AS total FROM ' .
                bzj_ou_identifier($table) .
                ' WHERE ' . $condition
            );
            $row = $countResult->fetch_assoc();
            $count = (int)($row['total'] ?? 0);

            if ($count < 1) {
                continue;
            }

            $sampleResult = bzj_ou_query(
                $db,
                'SELECT DISTINCT ' . bzj_ou_identifier($column) .
                ' AS orphan_id FROM ' . bzj_ou_identifier($table) .
                ' WHERE ' . $condition .
                ' ORDER BY CAST(' . bzj_ou_identifier($column) .
                ' AS UNSIGNED) LIMIT ' . BZJ_SAMPLE_LIMIT
            );

            $samples = [];
            while ($sample = $sampleResult->fetch_assoc()) {
                $samples[] = (string)($sample['orphan_id'] ?? '');
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
        'platform' => $platformKey,
        'label' => $platform['label'],
        'authoritative_table' => $auth['table'],
        'authoritative_id_column' => $auth['id'],
        'existing_user_count' => bzj_ou_count_users(
            $db,
            $auth['table'],
            $auth['id']
        ),
        'findings' => $findings,
        'discovery' => $discovery,
    ];
}

/* -------------------------------------------------------------------------
 * WordPress ↔ platform ID mapping scan
 * ---------------------------------------------------------------------- */

function bzj_ou_load_platform_users(mysqli $db, string $platformKey): array
{
    $platform = bzj_ou_platforms()[$platformKey];
    $auth = bzj_ou_authoritative($db, $platform);

    $result = bzj_ou_query(
        $db,
        'SELECT ' . bzj_ou_identifier($auth['id']) . ' AS platform_id,
                ' . bzj_ou_identifier($auth['username']) . ' AS username,
                ' . bzj_ou_identifier($auth['email']) . ' AS email
         FROM ' . bzj_ou_identifier($auth['table'])
    );

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $id = trim((string)($row['platform_id'] ?? ''));
        if (!ctype_digit($id) || (int)$id < 1) {
            continue;
        }

        $users[(string)((int)$id)] = [
            'id' => (int)$id,
            'username' => (string)($row['username'] ?? ''),
            'email' => (string)($row['email'] ?? ''),
        ];
    }

    return $users;
}

function bzj_ou_index_platform_users(array $users): array
{
    $index = ['username' => [], 'email' => []];

    foreach ($users as $user) {
        $username = bzj_ou_normalize($user['username']);
        $email = bzj_ou_normalize($user['email']);

        if ($username !== '') {
            $index['username'][$username][] = $user;
        }
        if ($email !== '') {
            $index['email'][$email][] = $user;
        }
    }

    return $index;
}

function bzj_ou_wp_meta_value(
    mysqli $wp,
    string $metaTable,
    int $wpUserId,
    string $metaKey
): string {
    $key = $wp->real_escape_string($metaKey);

    $result = bzj_ou_query(
        $wp,
        'SELECT meta_value
         FROM ' . bzj_ou_identifier($metaTable) . '
         WHERE user_id = ' . $wpUserId . '
           AND meta_key = \'' . $key . '\'
         ORDER BY umeta_id ASC
         LIMIT 1'
    );

    $row = $result->fetch_assoc();
    return (string)($row['meta_value'] ?? '');
}

function bzj_ou_mapping_scan(mysqli $wp, mysqli $wo, mysqli $qd): array
{
    $usersTable = bzj_ou_wp_table('users');
    $metaTable = bzj_ou_wp_table('usermeta');

    if (
        !bzj_ou_table_exists($wp, $usersTable) ||
        !bzj_ou_table_exists($wp, $metaTable)
    ) {
        bzj_ou_fail('WordPress users/usermeta tables were not found.');
    }

    $platformUsers = [
        'wowonder' => bzj_ou_load_platform_users($wo, 'wowonder'),
        'quickdate' => bzj_ou_load_platform_users($qd, 'quickdate'),
    ];

    $platformIndexes = [
        'wowonder' => bzj_ou_index_platform_users($platformUsers['wowonder']),
        'quickdate' => bzj_ou_index_platform_users($platformUsers['quickdate']),
    ];

    $wpResult = bzj_ou_query(
        $wp,
        'SELECT ID, user_login, user_email
         FROM ' . bzj_ou_identifier($usersTable) . '
         ORDER BY ID ASC'
    );

    $summary = [
        'wordpress_user_count' => 0,
        'wowonder_user_count' => count($platformUsers['wowonder']),
        'quickdate_user_count' => count($platformUsers['quickdate']),
        'ok' => 0,
        'repairable' => 0,
        'ambiguous' => 0,
        'unmatched' => 0,
    ];
    $findings = [];

    while ($wpUser = $wpResult->fetch_assoc()) {
        $summary['wordpress_user_count']++;
        $wpId = (int)$wpUser['ID'];
        $wpUsername = bzj_ou_normalize($wpUser['user_login'] ?? '');
        $wpEmail = bzj_ou_normalize($wpUser['user_email'] ?? '');

        foreach ([
            'wowonder' => 'wo_user_id',
            'quickdate' => 'qd_user_id',
        ] as $platform => $metaKey) {
            $users = $platformUsers[$platform];
            $index = $platformIndexes[$platform];

            $emailMatches = (
                $wpEmail !== '' && isset($index['email'][$wpEmail])
            ) ? $index['email'][$wpEmail] : [];

            $usernameMatches = (
                $wpUsername !== '' && isset($index['username'][$wpUsername])
            ) ? $index['username'][$wpUsername] : [];

            $emailIds = [];
            foreach ($emailMatches as $match) {
                $emailIds[(string)$match['id']] = true;
            }

            $usernameIds = [];
            foreach ($usernameMatches as $match) {
                $usernameIds[(string)$match['id']] = true;
            }

            $candidateIds = array_keys(
                array_replace($emailIds, $usernameIds)
            );

            $storedId = trim(
                bzj_ou_wp_meta_value(
                    $wp,
                    $metaTable,
                    $wpId,
                    $metaKey
                )
            );

            $storedValid = ctype_digit($storedId) && (int)$storedId > 0;
            $storedExists = $storedValid &&
                isset($users[(string)((int)$storedId)]);

            $status = 'ok';
            $repairable = false;
            $candidateId = null;

            /*
             * A repair is allowed only when identity evidence is unambiguous:
             * - exactly one candidate platform account;
             * - each populated WP identity field has exactly one platform match;
             * - when both username and email exist, they point to the same account.
             *
             * This intentionally does NOT guess from partial/fuzzy matches.
             */
            if (count($candidateIds) === 0) {
                $status = $storedId === ''
                    ? 'no_platform_match'
                    : (
                        $storedExists
                            ? 'stored_id_has_no_username_or_email_match'
                            : 'stored_id_missing_from_platform'
                    );
            } elseif (
                count($candidateIds) === 1 &&
                count($emailMatches) <= 1 &&
                count($usernameMatches) <= 1
            ) {
                $candidateId = (int)$candidateIds[0];

                $emailAgrees = (
                    $wpEmail === '' ||
                    (
                        count($emailMatches) === 1 &&
                        (int)$emailMatches[0]['id'] === $candidateId
                    )
                );

                $usernameAgrees = (
                    $wpUsername === '' ||
                    (
                        count($usernameMatches) === 1 &&
                        (int)$usernameMatches[0]['id'] === $candidateId
                    )
                );

                if ($emailAgrees && $usernameAgrees) {
                    if ($storedId === (string)$candidateId) {
                        $status = 'ok';
                    } else {
                        $status = 'repairable_mismatch';
                        $repairable = true;
                    }
                } else {
                    $status = 'conflicting_identity_match';
                }
            } elseif (
                count($candidateIds) > 1 ||
                count($emailMatches) > 1 ||
                count($usernameMatches) > 1
            ) {
                $status = 'ambiguous_username_or_email_match';
            } else {
                $status = 'unmatched';
            }

            if ($status === 'ok') {
                $summary['ok']++;
                continue;
            }

            if ($repairable) {
                $summary['repairable']++;
            } elseif (
                in_array(
                    $status,
                    [
                        'ambiguous_username_or_email_match',
                        'conflicting_identity_match',
                    ],
                    true
                )
            ) {
                $summary['ambiguous']++;
            } else {
                $summary['unmatched']++;
            }

            $findings[] = [
                'wp_user_id' => $wpId,
                'wp_username' => (string)($wpUser['user_login'] ?? ''),
                'wp_email' => (string)($wpUser['user_email'] ?? ''),
                'platform' => $platform,
                'meta_key' => $metaKey,
                'stored_id' => $storedId,
                'stored_id_exists' => $storedExists,
                'candidate_id' => $candidateId,
                'repairable' => $repairable,
                'status' => $status,
                'email_match_count' => count($emailMatches),
                'username_match_count' => count($usernameMatches),
            ];
        }
    }

    return ['summary' => $summary, 'findings' => $findings];
}

/* -------------------------------------------------------------------------
 * Full scan
 * ---------------------------------------------------------------------- */

function bzj_ou_full_scan(array $connections): array
{
    return [
        'version' => BZJ_ORPHAN_CLEANER_VERSION,
        'timestamp_utc' => gmdate('c'),
        'wowonder' => bzj_ou_scan_platform($connections['wo'], 'wowonder'),
        'quickdate' => bzj_ou_scan_platform($connections['qd'], 'quickdate'),
        'mapping' => bzj_ou_mapping_scan(
            $connections['wp'],
            $connections['wo'],
            $connections['qd']
        ),
    ];
}

/* -------------------------------------------------------------------------
 * Destructive operations
 * ---------------------------------------------------------------------- */

function bzj_ou_clean_platform(
    mysqli $db,
    string $platformKey,
    array $freshScan
): array {
    $results = [];

    foreach ($freshScan['findings'] as $finding) {
        $table = (string)$finding['table'];

        if (!bzj_ou_table_exists($db, $table)) {
            $results[] = [
                'platform' => $platformKey,
                'table' => $table,
                'deleted_rows' => 0,
                'status' => 'skipped_missing_table',
            ];
            continue;
        }

        /*
         * Rebuild the WHERE clause from the table's live schema. The displayed
         * dry-run result is not trusted as a destructive command.
         */
        $platform = bzj_ou_platforms()[$platformKey];
        $auth = bzj_ou_authoritative($db, $platform);
        $actualColumns = bzj_ou_columns($db, $table);
        $conditions = [];

        foreach ($platform['references'][$table] ?? [] as $configuredColumn) {
            $key = strtolower($configuredColumn);
            if (!isset($actualColumns[$key])) {
                continue;
            }

            $column = $actualColumns[$key];
            $conditions[] = '(' . bzj_ou_orphan_condition(
                $column,
                $auth['table'],
                $auth['id']
            ) . ')';
        }

        if (!$conditions) {
            $results[] = [
                'platform' => $platformKey,
                'table' => $table,
                'deleted_rows' => 0,
                'status' => 'skipped_no_confirmed_columns',
            ];
            continue;
        }

        $sql = 'DELETE FROM ' . bzj_ou_identifier($table) .
            ' WHERE ' . implode(' OR ', $conditions);

        $engine = bzj_ou_engine($db, $table);
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

            bzj_ou_query($db, $sql);
            $deleted = (int)$db->affected_rows;

            if ($transactionStarted && !$db->commit()) {
                throw new RuntimeException(
                    'Unable to commit transaction for ' . $table
                );
            }

            $results[] = [
                'platform' => $platformKey,
                'table' => $table,
                'deleted_rows' => $deleted,
                'status' => 'committed',
                'engine' => $engine['engine'],
                'transactional' => $engine['transactional'],
            ];
        } catch (Throwable $e) {
            if ($transactionStarted) {
                $db->rollback();
            }

            $results[] = [
                'platform' => $platformKey,
                'table' => $table,
                'deleted_rows' => 0,
                'status' => 'rolled_back_or_failed',
                'engine' => $engine['engine'],
                'transactional' => $engine['transactional'],
                'error' => $e->getMessage(),
            ];

            throw $e;
        }
    }

    return $results;
}

function bzj_ou_repair_mappings(
    mysqli $wp,
    array $freshMapping
): array {
    $metaTable = bzj_ou_wp_table('usermeta');

    if (!bzj_ou_table_exists($wp, $metaTable)) {
        bzj_ou_fail('WordPress usermeta table was not found.');
    }

    $results = [];

    foreach ($freshMapping['findings'] as $finding) {
        if (
            empty($finding['repairable']) ||
            $finding['candidate_id'] === null
        ) {
            continue;
        }

        $wpUserId = (int)$finding['wp_user_id'];
        $metaKey = (string)$finding['meta_key'];
        $newValue = (string)$finding['candidate_id'];
        $oldValue = (string)$finding['stored_id'];

        $key = $wp->real_escape_string($metaKey);
        $value = $wp->real_escape_string($newValue);

        $transactionStarted = false;

        try {
            if (!$wp->begin_transaction()) {
                throw new RuntimeException(
                    'Unable to begin WordPress metadata transaction.'
                );
            }
            $transactionStarted = true;

            /*
             * Update every existing row for this meta key, preventing stale
             * duplicate rows from retaining the old platform ID.
             */
            bzj_ou_query(
                $wp,
                'UPDATE ' . bzj_ou_identifier($metaTable) . '
                 SET meta_value = \'' . $value . '\'
                 WHERE user_id = ' . $wpUserId . '
                   AND meta_key = \'' . $key . '\''
            );
            $affected = (int)$wp->affected_rows;

            /*
             * If the key did not exist, insert it. If it existed but already
             * contained the candidate, affected_rows may be zero; do not create
             * a duplicate in that case.
             */
            if ($affected === 0) {
                $existing = bzj_ou_query(
                    $wp,
                    'SELECT umeta_id
                     FROM ' . bzj_ou_identifier($metaTable) . '
                     WHERE user_id = ' . $wpUserId . '
                       AND meta_key = \'' . $key . '\'
                     LIMIT 1'
                );

                if (!$existing->fetch_assoc()) {
                    bzj_ou_query(
                        $wp,
                        'INSERT INTO ' . bzj_ou_identifier($metaTable) .
                        ' (user_id, meta_key, meta_value)
                         VALUES (' . $wpUserId . ', \'' .
                        $key . '\', \'' . $value . '\')'
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
                'affected' => $affected,
                'status' => 'committed',
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
 * HTML rendering
 * ---------------------------------------------------------------------- */

function bzj_ou_render_orphans(array $scan): string
{
    $html = '';

    foreach (['wowonder', 'quickdate'] as $platformKey) {
        $platform = $scan[$platformKey];

        $html .= '<h3>' . bzj_ou_h($platform['label']) .
            ' — ' . (int)$platform['existing_user_count'] .
            ' authoritative users</h3>';

        if (!$platform['findings']) {
            $html .= '<p class="success">No confirmed orphan reference rows found.</p>';
        } else {
            $html .= '<table><thead><tr>' .
                '<th>Table</th><th>Reference columns</th>' .
                '<th>Rows to remove</th><th>Engine</th>' .
                '<th>Sample orphan IDs</th></tr></thead><tbody>';

            foreach ($platform['findings'] as $finding) {
                $html .= '<tr><td><code>' .
                    bzj_ou_h($finding['table']) .
                    '</code></td><td>';

                foreach ($finding['columns'] as $column) {
                    $html .= '<div><code>' .
                        bzj_ou_h($column['column']) .
                        '</code>: ' .
                        (int)$column['orphan_references'] .
                        '</div>';
                }

                $engine = $finding['engine'] !== ''
                    ? $finding['engine']
                    : 'unknown';
                $engine .= !empty($finding['transactional'])
                    ? ' / transactional'
                    : ' / non-transactional';

                $html .= '</td><td><strong>' .
                    (int)$finding['orphan_rows'] .
                    '</strong></td><td>' .
                    bzj_ou_h($engine) .
                    '</td><td>';

                foreach ($finding['columns'] as $column) {
                    $html .= '<div><code>' .
                        bzj_ou_h($column['column']) .
                        '</code>: ' .
                        bzj_ou_h(implode(', ', $column['sample_user_ids'])) .
                        '</div>';
                }

                $html .= '</td></tr>';
            }

            $html .= '</tbody></table>';
        }

        if (!empty($platform['discovery'])) {
            $html .= '<h4>Advisory schema discovery — NOT automatically deleted</h4>';
            $html .= '<p class="warning">These rows were found in tables/columns that are not part of the confirmed deletion map. Review them before adding a table to the approved map.</p>';
            $html .= '<table><thead><tr>' .
                '<th>Table</th><th>Column</th>' .
                '<th>Orphan references</th><th>Sample IDs</th>' .
                '</tr></thead><tbody>';

            foreach ($platform['discovery'] as $finding) {
                $html .= '<tr><td><code>' .
                    bzj_ou_h($finding['table']) .
                    '</code></td><td><code>' .
                    bzj_ou_h($finding['column']) .
                    '</code></td><td>' .
                    (int)$finding['orphan_references'] .
                    '</td><td>' .
                    bzj_ou_h(implode(', ', $finding['sample_user_ids'])) .
                    '</td></tr>';
            }

            $html .= '</tbody></table>';
        } else {
            $html .= '<p class="muted">No additional schema-discovered orphan references found.</p>';
        }
    }

    return $html;
}

function bzj_ou_render_mapping(array $mapping): string
{
    $summary = $mapping['summary'];

    $html = '<div class="stats">';
    foreach ([
        'wordpress_user_count' => 'WordPress users',
        'wowonder_user_count' => 'WoWonder users',
        'quickdate_user_count' => 'QuickDate users',
        'ok' => 'Aligned',
        'repairable' => 'Repairable',
        'ambiguous' => 'Ambiguous/conflicting',
        'unmatched' => 'Unmatched',
    ] as $key => $label) {
        $html .= '<div class="stat"><span>' .
            bzj_ou_h($label) . '</span><strong>' .
            (int)($summary[$key] ?? 0) .
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

    foreach ($mapping['findings'] as $finding) {
        $html .= '<tr>' .
            '<td>' . (int)$finding['wp_user_id'] . '</td>' .
            '<td>' . bzj_ou_h($finding['wp_username']) . '</td>' .
            '<td>' . bzj_ou_h($finding['wp_email']) . '</td>' .
            '<td>' . bzj_ou_h($finding['platform']) . '</td>' .
            '<td><code>' . bzj_ou_h($finding['meta_key']) . '</code></td>' .
            '<td>' . bzj_ou_h($finding['stored_id']) . '</td>' .
            '<td>' . bzj_ou_h($finding['candidate_id'] ?? '') . '</td>' .
            '<td><code>' . bzj_ou_h($finding['status']) . '</code></td>' .
            '<td>' .
            (!empty($finding['repairable'])
                ? '<strong class="good">YES</strong>'
                : '<strong class="bad">NO</strong>') .
            '</td></tr>';
    }

    return $html . '</tbody></table>';
}

function bzj_ou_render_results(array $results): string
{
    if (!$results) {
        return '<p class="muted">No rows or metadata required processing.</p>';
    }

    $html = '<table><thead><tr>' .
        '<th>Platform</th><th>Target</th><th>Action</th>' .
        '<th>Status</th><th>Details</th></tr></thead><tbody>';

    foreach ($results as $result) {
        $target = $result['table'] ??
            ('WP user ' . ($result['wp_user_id'] ?? ''));

        if (array_key_exists('deleted_rows', $result)) {
            $action = 'Deleted ' . (int)$result['deleted_rows'] . ' row(s)';
        } else {
            $action = 'Metadata update ' .
                bzj_ou_h($result['meta_key'] ?? '');
        }

        $details = (string)($result['error'] ?? '');

        if ($details === '' && isset($result['old_value'])) {
            $details = 'Old: ' . $result['old_value'] .
                ' → New: ' . $result['new_value'];
        }

        if ($details === '' && isset($result['engine'])) {
            $details = $result['engine'] .
                (!empty($result['transactional'])
                    ? ' / transaction'
                    : ' / non-transactional');
        }

        $html .= '<tr><td>' .
            bzj_ou_h($result['platform'] ?? 'WordPress') .
            '</td><td><code>' .
            bzj_ou_h($target) .
            '</code></td><td>' .
            $action .
            '</td><td><code>' .
            bzj_ou_h($result['status'] ?? '') .
            '</code></td><td>' .
            bzj_ou_h($details) .
            '</td></tr>';
    }

    return $html . '</tbody></table>';
}

/* -------------------------------------------------------------------------
 * Request handling
 * ---------------------------------------------------------------------- */

$action = isset($_POST['bzj_action']) ? (string)$_POST['bzj_action'] : '';
$scan = null;
$operationResults = [];
$message = '';
$error = '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        bzj_ou_check_post_security();
        $connections = bzj_ou_connections();

        if ($action === 'scan') {
            $scan = bzj_ou_full_scan($connections);

            bzj_ou_audit('orphan_cleaner_dry_run', [
                'version' => BZJ_ORPHAN_CLEANER_VERSION,
                'scan' => $scan,
            ]);

            $message = 'Dry run completed. No database rows were changed.';
        } elseif ($action === 'clean') {
            $phrase = trim((string)($_POST['confirmation_phrase'] ?? ''));

            if (!hash_equals(BZJ_CLEAN_PHRASE, $phrase)) {
                bzj_ou_fail('The orphan-cleanup confirmation phrase is incorrect.');
            }

            /*
             * The displayed dry-run is never reused for deletion. A new scan
             * protects against database changes between review and clean.
             */
            $fresh = bzj_ou_full_scan($connections);

            $operationResults = array_merge(
                bzj_ou_clean_platform(
                    $connections['wo'],
                    'wowonder',
                    $fresh['wowonder']
                ),
                bzj_ou_clean_platform(
                    $connections['qd'],
                    'quickdate',
                    $fresh['quickdate']
                )
            );

            $scan = bzj_ou_full_scan($connections);

            bzj_ou_audit('orphan_cleaner_cleanup', [
                'version' => BZJ_ORPHAN_CLEANER_VERSION,
                'before' => $fresh,
                'results' => $operationResults,
                'after' => $scan,
            ]);

            $message = 'Orphan cleanup completed and the databases were rescanned.';
        } elseif ($action === 'repair') {
            $phrase = trim((string)($_POST['confirmation_phrase'] ?? ''));

            if (!hash_equals(BZJ_REPAIR_PHRASE, $phrase)) {
                bzj_ou_fail('The metadata-repair confirmation phrase is incorrect.');
            }

            /*
             * Fresh identity scan immediately before repair. Only rows still
             * classified as unambiguous repairable mismatches are changed.
             */
            $freshMapping = bzj_ou_mapping_scan(
                $connections['wp'],
                $connections['wo'],
                $connections['qd']
            );

            $operationResults = bzj_ou_repair_mappings(
                $connections['wp'],
                $freshMapping
            );

            $scan = bzj_ou_full_scan($connections);

            bzj_ou_audit('orphan_cleaner_mapping_repair', [
                'version' => BZJ_ORPHAN_CLEANER_VERSION,
                'before' => $freshMapping,
                'results' => $operationResults,
                'after' => $scan['mapping'],
            ]);

            $message = 'WordPress platform-ID repair completed and the mappings were rescanned.';
        } else {
            bzj_ou_fail('Unknown operation.');
        }
    }
} catch (Throwable $e) {
    $error = $e->getMessage();

    bzj_ou_audit('orphan_cleaner_error', [
        'version' => BZJ_ORPHAN_CLEANER_VERSION,
        'action' => $action,
        'error' => $error,
    ]);
}

$nonce = wp_create_nonce('bzj_ou_action');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Buzzjuice WoWonder + QuickDate User Data Cleaner</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{margin:0;padding:24px;background:#f3f5f7;color:#202124;font:14px/1.5 Arial,sans-serif}
main{max-width:1500px;margin:0 auto;padding:28px;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(0,0,0,.08)}
h1,h2,h3,h4{color:#172b4d}h1{margin-top:0}
.panel{margin:18px 0;padding:18px;border:1px solid #d9e0e7;border-radius:8px}
.notice,.warning,.success,.danger{margin:12px 0;padding:12px 14px;border-radius:6px}
.notice{background:#eaf2ff;border-left:4px solid #2463eb}
.warning{background:#fff7df;border-left:4px solid #d99b00}
.success{background:#eaf8ee;border-left:4px solid #2e9d54}
.danger{background:#fff0f0;border-left:4px solid #d93025}
.muted{color:#687078}.good{color:#176b35}.bad{color:#9d1c1c}.small{font-size:12px}
table{width:100%;margin:12px 0 22px;border-collapse:collapse;font-size:13px}
th,td{padding:8px;border:1px solid #d9e0e7;text-align:left;vertical-align:top}
th{background:#eef2f6}code{font-family:Consolas,Monaco,monospace}
button{margin:4px 8px 4px 0;padding:10px 15px;color:#fff;background:#2463eb;border:0;border-radius:5px;cursor:pointer}
button.danger-button{background:#c62828}button.repair-button{background:#7c3aed}
input[type=text],input[type=password]{min-width:320px;padding:9px;border:1px solid #b9c1c9;border-radius:5px}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(145px,1fr));gap:10px;margin:12px 0}
.stat{padding:12px;border:1px solid #d9e0e7;border-radius:7px;background:#fafbfc}
.stat span{display:block;color:#687078;font-size:12px}.stat strong{font-size:22px}
form.inline{display:inline-block;margin-right:8px}
</style>
</head>
<body>
<main>
<h1>Buzzjuice WoWonder + QuickDate User Data Cleaner</h1>

<div class="notice">
<strong>Version:</strong> <?= bzj_ou_h(BZJ_ORPHAN_CLEANER_VERSION) ?><br>
This utility treats <code>Wo_Users</code> and QuickDate <code>users</code> as
authoritative. It never deletes those user rows and never deletes media/S3 files.
</div>

<?php if ($message !== ''): ?>
<div class="success"><?= bzj_ou_h($message) ?></div>
<?php endif; ?>

<?php if ($error !== ''): ?>
<div class="danger"><strong>Operation failed:</strong> <?= bzj_ou_h($error) ?></div>
<?php endif; ?>

<div class="panel">
<h2>1. Scan / Dry Run</h2>
<p>
The dry run checks confirmed WoWonder/QuickDate reference tables for positive
numeric user IDs that no longer exist in the authoritative user table. It also
performs WordPress <code>wo_user_id</code>/<code>qd_user_id</code> alignment checks.
</p>
<form method="post">
<input type="hidden" name="bzj_action" value="scan">
<input type="hidden" name="bzj_nonce" value="<?= bzj_ou_h($nonce) ?>">
<label>Cleaner access key:
<input type="password" name="bzj_access_key" autocomplete="off" required></label>
<button type="submit">Start Dry Run</button>
</form>
</div>

<?php if ($scan !== null): ?>
<div class="panel">
<h2>2. Dry-Run Results</h2>
<p class="small muted">
Scan timestamp: <?= bzj_ou_h($scan['timestamp_utc']) ?>.
Only confirmed reference-map findings are eligible for automatic deletion.
</p>
<?= bzj_ou_render_orphans($scan) ?>
</div>

<div class="panel">
<h2>3. WordPress Platform-ID Alignment</h2>
<p>
The scanner compares the WordPress username/email with the authoritative
WoWonder and QuickDate username/email. A repair is offered only where the
identity match is unique and non-conflicting.
</p>
<?= bzj_ou_render_mapping($scan['mapping']) ?>
</div>

<div class="panel">
<h2>4. Clean Confirmed Orphans</h2>
<div class="warning">
<strong>Destructive operation.</strong>
The cleaner performs a fresh scan immediately before deletion, so the results
shown above are not blindly reused.
</div>
<form method="post">
<input type="hidden" name="bzj_action" value="clean">
<input type="hidden" name="bzj_nonce" value="<?= bzj_ou_h($nonce) ?>">
<label>Cleaner access key:
<input type="password" name="bzj_access_key" autocomplete="off" required></label><br>
<label>Type <code><?= bzj_ou_h(BZJ_CLEAN_PHRASE) ?></code>:
<input type="text" name="confirmation_phrase" autocomplete="off" required></label>
<button class="danger-button" type="submit">Clean Confirmed Orphans</button>
</form>
</div>

<div class="panel">
<h2>5. Repair Confirmed ID Mismatches</h2>
<div class="warning">
Only <strong>repairable</strong> identity matches are changed. Ambiguous,
conflicting and unmatched identities are deliberately left untouched.
</div>
<form method="post">
<input type="hidden" name="bzj_action" value="repair">
<input type="hidden" name="bzj_nonce" value="<?= bzj_ou_h($nonce) ?>">
<label>Cleaner access key:
<input type="password" name="bzj_access_key" autocomplete="off" required></label><br>
<label>Type <code><?= bzj_ou_h(BZJ_REPAIR_PHRASE) ?></code>:
<input type="text" name="confirmation_phrase" autocomplete="off" required></label>
<button class="repair-button" type="submit">Repair Confirmed ID Mismatches</button>
</form>
</div>
<?php endif; ?>

<?php if ($operationResults): ?>
<div class="panel">
<h2>Operation Results</h2>
<?= bzj_ou_render_results($operationResults) ?>

<?php if ($scan !== null): ?>
<h3>Post-operation Verification</h3>
<?= bzj_ou_render_orphans($scan) ?>
<h3>Post-operation Mapping Verification</h3>
<?= bzj_ou_render_mapping($scan['mapping']) ?>
<?php endif; ?>
</div>
<?php endif; ?>

<div class="panel">
<h2>Operating Notes</h2>
<ul>
<li><strong>Dry Run:</strong> read-only; safe to repeat.</li>
<li><strong>Confirmed orphan cleanup:</strong> removes only rows from the explicit deletion maps derived from the supplied platform delete functions.</li>
<li><strong>Advisory discovery:</strong> identifies possible custom/plugin references but does not delete them automatically.</li>
<li><strong>ID repair:</strong> updates only WordPress user metadata; it does not change platform user IDs.</li>
<li><strong>Security:</strong> POST operations require WordPress administrator capability, a WordPress nonce, and <code>BZJ_ORPHAN_CLEANER_KEY</code>.</li>
<li><strong>Logging:</strong> the existing <code>bzj_log()</code> function is used when available; this file does not redeclare it.</li>
</ul>
</div>

<p class="small muted">
Keep this utility restricted to administrators and remove it when it is no longer
needed for maintenance.
</p>
</main>
</body>
</html>
