<?php
/**
 * Buzzjuice / WoWonder orphan-user auditor and cleaner.
 *
 * File:
 *   streams/tools/bzj-orphan-user-cleaner.php
 *
 * Dry run:
 *   php bzj-orphan-user-cleaner.php
 *
 * Execute:
 *   php bzj-orphan-user-cleaner.php --execute --yes
 *
 * Useful options:
 *   --user-id=123
 *   --table=Wo_Followers
 *   --limit=50
 *   --report=/absolute/path/report.json
 *   --discover-only
 *   --no-discovery
 *   --help
 *
 * Safety:
 *   - CLI only.
 *   - Dry-run is the default.
 *   - Wo_Users is never modified.
 *   - Only references confirmed from the supplied Wo_DeleteUser() function
 *     are eligible for automatic deletion.
 *   - Schema-discovered references are advisory only.
 *   - A fresh authoritative Wo_Users snapshot is created immediately before
 *     execution.
 *   - Each table is deleted inside its own transaction.
 *   - A verification scan is performed after deletion.
 *   - Files/S3, WordPress, QuickDate and Palmier data are untouched.
 *
 * IMPORTANT:
 *   This utility cleans database rows. It intentionally does not emulate
 *   Wo_DeleteUser()'s media deletion because a generic orphan-row cleaner
 *   cannot safely prove that a media object is not shared or referenced
 *   elsewhere.
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("This utility must be executed from the command line.\n");
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 * Intended location:
 *   /streams/tools/bzj-orphan-user-cleaner.php
 *
 * db_helpers.php:
 *   /shared/db_helpers.php
 */
require_once dirname(__DIR__, 2) . '/shared/db_helpers.php';

const BZJ_USERS_TABLE = 'Wo_Users';
const BZJ_DEFAULT_LIMIT = 50;
const BZJ_MAX_LIMIT = 10000;

/*
 * Confirmed references derived from the supplied Wo_DeleteUser().
 *
 * Missing tables/columns are skipped because WoWonder installations can
 * differ by version and enabled modules.
 *
 * These are the ONLY references eligible for automatic deletion.
 */
$confirmedReferences = [
    'Wo_Users_Fields'          => ['user_id'],
    'Wo_RecentSearches'        => ['user_id', 'search_id'],
    'Wo_GamesPlayers'          => ['user_id'],
    'Wo_UserProjects'          => ['user_id'],
    'Wo_UserOpenTo'            => ['user_id'],

    'Wo_Followers'             => ['follower_id', 'following_id'],
    'Wo_Messages'              => ['from_id', 'to_id'],
    'Wo_VideoCalls'            => ['from_id', 'to_id'],
    'Wo_AudioCalls'            => ['from_id', 'to_id'],
    'Wo_Agora'                 => ['from_id', 'to_id'],
    'Wo_Notification'          => ['notifier_id', 'recipient_id'],
    'Wo_Reports'               => ['user_id'],
    'Wo_AppSessions'           => ['user_id'],
    'Wo_Comments'              => ['user_id'],
    'Wo_AnnouncementViews'     => ['user_id'],
    'Wo_Likes'                 => ['user_id'],
    'Wo_Wonders'               => ['user_id'],
    'Wo_CommentRepliesLikes'   => ['user_id'],
    'Wo_CommentRepliesWonders' => ['user_id'],
    'Wo_SavedPosts'            => ['user_id'],
    'Wo_CommentLikes'          => ['user_id'],
    'Wo_CommentWonders'        => ['user_id'],
    'Wo_CommentsReplies'       => ['user_id'],
    'Wo_EventsGoing'           => ['user_id'],
    'Wo_EventsInterested'      => ['user_id'],
    'Wo_BmLikes'               => ['user_id'],
    'Wo_BmDislikes'            => ['user_id'],
    'Wo_UserAdsData'           => ['user_id'],
    'Wo_PaymentTransactions'   => ['userid'],
    'Wo_Activities'            => ['user_id', 'follow_id'],
    'Wo_EventsInv'             => ['inviter_id', 'invited_id'],
    'Wo_GroupMembers'          => ['user_id'],
    'Wo_PagesInvites'          => ['inviter_id', 'invited_id'],
    'Wo_PinnedPosts'           => ['user_id'],
    'Wo_Apps'                 => ['app_user_id'],
    'Wo_AppsPermission'       => ['user_id'],
    'Wo_Codes'                => ['user_id'],
    'Wo_Tokens'               => ['user_id'],
    'Wo_BlogReaction'         => ['user_id'],
    'Wo_PagesLikes'           => ['user_id'],
    'Wo_VerificationRequests'  => ['user_id'],
    'Wo_ARequests'            => ['user_id'],
    'Wo_Blocks'               => ['blocker', 'blocked'],
    'Wo_UChats'               => ['conversation_user_id', 'user_id'],
    'Wo_Blog'                 => ['user'],
    'Wo_BlogCommentsReplies'  => ['user_id'],
    'Wo_BlogComments'         => ['user_id'],
    'Wo_MovieCommentsReplies' => ['user_id'],
    'Wo_MovieComments'        => ['user_id'],
    'Wo_AppsHash'             => ['user_id'],
    'Wo_ForumThreads'         => ['user'],
    'Wo_ForumThreadReplies'   => ['poster_id'],
    'Wo_Events'               => ['poster_id'],
    'Wo_UserAds'              => ['user_id'],
    'Wo_UserStory'             => ['user_id'],
    'Wo_HiddenPosts'          => ['user_id'],
    'Wo_GroupChat'             => ['user_id'],
    'Wo_GroupChatUsers'        => ['user_id'],
    'Wo_PageRating'            => ['user_id'],
    'Wo_Family'                => ['user_id', 'member_id'],
    'Wo_Relationship'          => ['from_id', 'to_id'],
    'Wo_PageAdmins'            => ['user_id'],
    'Wo_GroupAdmins'           => ['user_id'],
    'Wo_Reactions'             => ['user_id'],
    'Wo_Job'                  => ['user_id'],
    'Wo_JobApply'             => ['user_id'],
    'Wo_Pokes'                => ['received_user_id', 'send_user_id'],
    'Wo_UserGifts'            => ['from', 'to'],
    'Wo_StorySeen'            => ['user_id'],
    'Wo_Refund'               => ['user_id'],
    'Wo_InvitationLinks'      => ['user_id', 'invited_id'],
    'Wo_Mute'                 => ['user_id'],
    'Wo_MuteStory'            => ['user_id', 'story_user_id'],
    'Wo_Cast'                => ['user_id'],
    'Wo_CastUsers'           => ['user_id'],
    'Wo_LiveSubscriptions'   => ['user_id'],
    'Wo_Votes'               => ['user_id'],
    'Wo_BankTransfer'         => ['user_id'],
    'Wo_UserCard'             => ['user_id'],
    'Wo_UserAddress'          => ['user_id'],
    'Wo_UserOrders'           => ['user_id', 'product_owner_id'],
    'Wo_Purchases'            => ['user_id', 'owner_id'],
    'Wo_Email'               => ['user_id'],

    /*
     * Ownership/reference tables explicitly touched by Wo_DeleteUser().
     */
    'Wo_Posts'                => ['user_id', 'recipient_id'],
    'Wo_Pages'                => ['user_id'],
    'Wo_Groups'               => ['user_id'],
    'Wo_Funding'              => ['user_id'],
    'Wo_Offer'                => ['user_id'],
    'Wo_UserExperience'       => ['user_id'],
    'Wo_UserCertification'   => ['user_id'],

    /*
     * These are included only when a user_id column actually exists.
     * The supplied deletion function handles user monetization explicitly.
     */
    'Wo_UserMonetization'         => ['user_id'],
    'Wo_MonetizationSubscription' => ['user_id'],
];

/*
 * Common version/module table-name variations.
 */
$tableAliases = [
    'Wo_EventsInterested' => [
        'Wo_EventsInterested',
        'Wo_EventsInt',
    ],
    'Wo_PagesInvites' => [
        'Wo_PagesInvites',
        'Wo_PagesInvaites',
    ],
    'Wo_LiveSubscriptions' => [
        'Wo_LiveSubscriptions',
        'Wo_LiveSub',
    ],
    'Wo_Email' => [
        'Wo_Email',
        'Wo_Emails',
    ],
    'Wo_UserMonetization' => [
        'Wo_UserMonetization',
        'Wo_UserMonetizations',
    ],
    'Wo_MonetizationSubscription' => [
        'Wo_MonetizationSubscription',
        'Wo_MonetizationSubscribtion',
        'Wo_MonetizationSubscriptions',
    ],
];

/*
 * Advisory-only schema discovery.
 *
 * A column matching one of these names is NOT enough to prove that the
 * column semantically identifies a WoWonder user. Discovery therefore never
 * deletes automatically.
 */
$discoveryColumns = [
    'user_id',
    'userid',
    'user',
    'owner_id',
    'creator_id',
    'member_id',
    'poster_id',
    'author_id',
    'follower_id',
    'following_id',
    'from_id',
    'to_id',
    'recipient_id',
    'inviter_id',
    'invited_id',
    'blocked',
    'blocker',
    'send_user_id',
    'received_user_id',
    'story_user_id',
    'product_owner_id',
    'conversation_user_id',
    'app_user_id',
    'search_id',
    'follow_id',
];

function quoteIdentifier(string $identifier): string
{
    if (!preg_match('/^[A-Za-z0-9_]+$/', $identifier)) {
        throw new RuntimeException("Unsafe SQL identifier: {$identifier}");
    }

    return '`' . $identifier . '`';
}

function queryOrFail(mysqli $conn, string $sql): mysqli_result|bool
{
    $result = $conn->query($sql);

    if ($result === false) {
        throw new RuntimeException($conn->error . "\nSQL: " . $sql);
    }

    return $result;
}

function tableExists(mysqli $conn, string $table): bool
{
    $escaped = $conn->real_escape_string($table);

    $result = queryOrFail(
        $conn,
        "SHOW TABLES LIKE '{$escaped}'"
    );

    return $result instanceof mysqli_result && $result->num_rows > 0;
}

function getTables(mysqli $conn): array
{
    $result = queryOrFail($conn, 'SHOW TABLES');
    $tables = [];

    while ($row = $result->fetch_row()) {
        if (isset($row[0])) {
            $tables[] = (string)$row[0];
        }
    }

    return $tables;
}

function getColumns(mysqli $conn, string $table): array
{
    $result = queryOrFail(
        $conn,
        'SHOW COLUMNS FROM ' . quoteIdentifier($table)
    );

    $columns = [];

    while ($row = $result->fetch_assoc()) {
        if (!isset($row['Field'])) {
            continue;
        }

        $name = (string)$row['Field'];
        $columns[strtolower($name)] = $name;
    }

    return $columns;
}

function resolveTableName(
    mysqli $conn,
    string $configuredTable,
    array $tableAliases
): ?string {
    $candidates = $tableAliases[$configuredTable] ?? [$configuredTable];

    foreach ($candidates as $candidate) {
        if (tableExists($conn, $candidate)) {
            return $candidate;
        }
    }

    return null;
}

/*
 * Snapshot authoritative Wo_Users IDs into an indexed temporary table.
 *
 * This is preferable to repeating NOT EXISTS against Wo_Users for every
 * reference scan and gives the audit a consistent ID set on this connection.
 */
function createExistingUserSet(mysqli $conn): void
{
    queryOrFail(
        $conn,
        'DROP TEMPORARY TABLE IF EXISTS `bzj_existing_user_ids`'
    );

    queryOrFail(
        $conn,
        '
        CREATE TEMPORARY TABLE `bzj_existing_user_ids` (
            `user_id` BIGINT UNSIGNED NOT NULL,
            PRIMARY KEY (`user_id`)
        ) ENGINE=InnoDB
        '
    );

    queryOrFail(
        $conn,
        '
        INSERT INTO `bzj_existing_user_ids` (`user_id`)
        SELECT DISTINCT CAST(`user_id` AS UNSIGNED)
        FROM `Wo_Users`
        WHERE `user_id` IS NOT NULL
          AND CAST(`user_id` AS UNSIGNED) > 0
        '
    );
}

function getExistingUserCount(mysqli $conn): int
{
    $result = queryOrFail(
        $conn,
        'SELECT COUNT(*) AS total FROM `bzj_existing_user_ids`'
    );

    $row = $result->fetch_assoc();

    return (int)($row['total'] ?? 0);
}

/*
 * Treat only positive numeric IDs absent from the authoritative set as
 * orphan references.
 *
 * Empty/NULL/non-positive values are ignored.
 */
function orphanCondition(
    string $column,
    ?int $filterUserId = null
): string {
    $quotedColumn = quoteIdentifier($column);

    $condition = "
        {$quotedColumn} IS NOT NULL
        AND {$quotedColumn} <> ''
        AND CAST({$quotedColumn} AS UNSIGNED) > 0
        AND NOT EXISTS (
            SELECT 1
            FROM `bzj_existing_user_ids` AS existing_users
            WHERE existing_users.`user_id` =
                  CAST({$quotedColumn} AS UNSIGNED)
        )
    ";

    if ($filterUserId !== null) {
        $condition .=
            ' AND CAST(' .
            $quotedColumn .
            ' AS UNSIGNED) = ' .
            (int)$filterUserId;
    }

    return $condition;
}

function getColumnCount(
    mysqli $conn,
    string $table,
    string $condition
): int {
    $result = queryOrFail(
        $conn,
        'SELECT COUNT(*) AS total FROM ' .
        quoteIdentifier($table) .
        ' WHERE ' .
        $condition
    );

    $row = $result->fetch_assoc();

    return (int)($row['total'] ?? 0);
}

function getDistinctSampleIds(
    mysqli $conn,
    string $table,
    string $column,
    string $condition,
    int $limit
): array {
    $limit = max(1, min($limit, BZJ_MAX_LIMIT));

    $result = queryOrFail(
        $conn,
        'SELECT DISTINCT ' .
        quoteIdentifier($column) .
        ' AS orphan_user_id FROM ' .
        quoteIdentifier($table) .
        ' WHERE ' .
        $condition .
        ' ORDER BY ' .
        quoteIdentifier($column) .
        ' LIMIT ' .
        $limit
    );

    $ids = [];

    while ($row = $result->fetch_assoc()) {
        $ids[] = (string)$row['orphan_user_id'];
    }

    return $ids;
}

/*
 * Build one combined condition per table.
 *
 * If one row contains orphan IDs in two reference columns, it is deleted only
 * once.
 */
function buildTableFinding(
    mysqli $conn,
    string $table,
    array $configuredColumns,
    ?int $filterUserId,
    int $sampleLimit
): ?array {
    if ($table === BZJ_USERS_TABLE || !tableExists($conn, $table)) {
        return null;
    }

    $actualColumns = getColumns($conn, $table);
    $conditions = [];
    $columnFindings = [];

    foreach ($configuredColumns as $configuredColumn) {
        $key = strtolower($configuredColumn);

        if (!isset($actualColumns[$key])) {
            continue;
        }

        $column = $actualColumns[$key];
        $condition = orphanCondition($column, $filterUserId);
        $count = getColumnCount($conn, $table, $condition);

        if ($count === 0) {
            continue;
        }

        $columnFindings[] = [
            'column' => $column,
            'count' => $count,
            'sample_user_ids' => getDistinctSampleIds(
                $conn,
                $table,
                $column,
                $condition,
                $sampleLimit
            ),
        ];

        $conditions[] = '(' . $condition . ')';
    }

    if (!$conditions) {
        return null;
    }

    $combinedCondition = implode(' OR ', $conditions);

    return [
        'table' => $table,
        'columns' => $columnFindings,
        'condition' => $combinedCondition,
        'orphan_row_count' => getColumnCount(
            $conn,
            $table,
            $combinedCondition
        ),
    ];
}

/*
 * Advisory schema discovery.
 *
 * IMPORTANT CORRECTION:
 * The earlier GitHub/ChatGPT version used isset($knownTables[$table]) after
 * converting the table list to an associative lookup. That made the test
 * true for every table and suppressed discovery. This version keeps the real
 * table list separate from the confirmed-table lookup.
 */
function discoverPossibleReferences(
    mysqli $conn,
    array $allTables,
    array $confirmedLookup,
    array $candidateColumns,
    ?int $filterUserId,
    int $sampleLimit
): array {
    $results = [];

    foreach ($allTables as $table) {
        if ($table === BZJ_USERS_TABLE) {
            continue;
        }

        if (isset($confirmedLookup[$table])) {
            continue;
        }

        $actualColumns = getColumns($conn, $table);

        foreach ($candidateColumns as $candidateColumn) {
            $key = strtolower($candidateColumn);

            if (!isset($actualColumns[$key])) {
                continue;
            }

            $column = $actualColumns[$key];
            $condition = orphanCondition($column, $filterUserId);
            $count = getColumnCount($conn, $table, $condition);

            if ($count === 0) {
                continue;
            }

            $results[] = [
                'table' => $table,
                'column' => $column,
                'count' => $count,
                'sample_user_ids' => getDistinctSampleIds(
                    $conn,
                    $table,
                    $column,
                    $condition,
                    $sampleLimit
                ),
                'automatic_cleanup' => false,
                'reason' =>
                    'Schema-discovered reference; not in confirmed map',
            ];
        }
    }

    return $results;
}

function writeReport(string $path, array $report): void
{
    $directory = dirname($path);

    if (!is_dir($directory)) {
        if (!mkdir($directory, 0750, true) && !is_dir($directory)) {
            throw new RuntimeException(
                "Unable to create report directory: {$directory}"
            );
        }
    }

    $json = json_encode(
        $report,
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE
    );

    if ($json === false) {
        throw new RuntimeException(
            'Unable to JSON-encode cleanup report.'
        );
    }

    if (file_put_contents($path, $json . PHP_EOL) === false) {
        throw new RuntimeException(
            "Unable to write report: {$path}"
        );
    }
}

function printUsage(): void
{
    echo <<<'USAGE'
Buzzjuice WoWonder orphan-user auditor / cleaner

Dry run:
  php bzj-orphan-user-cleaner.php

Execute after reviewing the dry-run report:
  php bzj-orphan-user-cleaner.php --execute --yes

Options:
  --execute
      Delete confirmed orphan rows.

  --yes
      Required together with --execute.

  --user-id=ID
      Restrict the scan/deletion to one missing user ID.

  --table=TABLE
      Restrict the scan/deletion to one confirmed table.

  --limit=N
      Maximum sample IDs reported per reference column.
      Default: 50. Maximum: 10000.

  --report=/absolute/path/report.json
      Write the JSON report to the supplied absolute path.

  --discover-only
      Run confirmed and advisory discovery but never delete.

  --no-discovery
      Skip advisory schema discovery.

  --help
      Show this help.

Examples:
  php bzj-orphan-user-cleaner.php
  php bzj-orphan-user-cleaner.php --limit=100
  php bzj-orphan-user-cleaner.php --user-id=123
  php bzj-orphan-user-cleaner.php --table=Wo_Followers
  php bzj-orphan-user-cleaner.php --discover-only
  php bzj-orphan-user-cleaner.php --execute --yes

USAGE;
}

$options = getopt('', [
    'execute',
    'yes',
    'user-id:',
    'table:',
    'limit:',
    'report:',
    'discover-only',
    'no-discovery',
    'help',
]);

if (isset($options['help'])) {
    printUsage();
    exit(0);
}

$execute = isset($options['execute']);
$confirmed = isset($options['yes']);
$discoverOnly = isset($options['discover-only']);
$noDiscovery = isset($options['no-discovery']);

if ($execute && !$confirmed) {
    fwrite(
        STDERR,
        "Deletion requires BOTH --execute and --yes.\n"
    );
    exit(1);
}

if ($discoverOnly && $execute) {
    fwrite(
        STDERR,
        "--discover-only cannot be combined with --execute.\n"
    );
    exit(1);
}

$filterUserId = null;

if (isset($options['user-id'])) {
    $value = (string)$options['user-id'];

    if (!ctype_digit($value) || (int)$value < 1) {
        fwrite(STDERR, "--user-id must be a positive integer.\n");
        exit(1);
    }

    $filterUserId = (int)$value;
}

$filterTable = null;

if (isset($options['table'])) {
    $filterTable = trim((string)$options['table']);

    if (
        $filterTable === '' ||
        !preg_match('/^[A-Za-z0-9_]+$/', $filterTable)
    ) {
        fwrite(
            STDERR,
            "--table must contain only letters, numbers and underscores.\n"
        );
        exit(1);
    }
}

$limit = BZJ_DEFAULT_LIMIT;

if (isset($options['limit'])) {
    $value = (string)$options['limit'];

    if (!ctype_digit($value) || (int)$value < 1) {
        fwrite(STDERR, "--limit must be a positive integer.\n");
        exit(1);
    }

    $limit = min((int)$value, BZJ_MAX_LIMIT);
}

$conn = get_wowonder_db();

if (!$conn instanceof mysqli || $conn->connect_errno) {
    fwrite(
        STDERR,
        "Unable to connect to the WoWonder database.\n" .
        (
            $conn instanceof mysqli
                ? $conn->connect_error . "\n"
                : ''
        )
    );
    exit(1);
}

$conn->set_charset('utf8mb4');

if (!tableExists($conn, BZJ_USERS_TABLE)) {
    fwrite(
        STDERR,
        'Required authoritative table `' .
        BZJ_USERS_TABLE .
        "` does not exist.\n"
    );
    exit(1);
}

if (isset($options['report'])) {
    $reportPath = (string)$options['report'];

    if ($reportPath === '' || $reportPath[0] !== '/') {
        fwrite(
            STDERR,
            "--report must be an absolute path.\n"
        );
        exit(1);
    }
} else {
    $reportPath =
        dirname(__DIR__) .
        '/data/bzj-orphan-user-report-' .
        date('Ymd-His') .
        '.json';
}

echo $execute
    ? "EXECUTION MODE: confirmed orphan rows may be deleted.\n"
    : "DRY-RUN MODE: no database rows will be modified.\n";

if ($filterUserId !== null) {
    echo "User-ID filter: {$filterUserId}\n";
}

if ($filterTable !== null) {
    echo "Table filter: {$filterTable}\n";
}

echo "\n";

try {
    /*
     * Initial authoritative snapshot.
     */
    createExistingUserSet($conn);

    $existingUserCount = getExistingUserCount($conn);
    $allTables = getTables($conn);

    /*
     * Resolve version-dependent table aliases.
     */
    $resolvedReferences = [];

    foreach ($confirmedReferences as $configuredTable => $columns) {
        $resolved = resolveTableName(
            $conn,
            $configuredTable,
            $tableAliases
        );

        if ($resolved === null) {
            continue;
        }

        $resolvedReferences[$resolved] = $columns;
    }

    /*
     * Optional table filter.
     */
    if ($filterTable !== null) {
        $filtered = [];

        foreach ($confirmedReferences as $configuredTable => $columns) {
            $resolved = resolveTableName(
                $conn,
                $configuredTable,
                $tableAliases
            );

            if (
                $configuredTable === $filterTable ||
                $resolved === $filterTable
            ) {
                if ($resolved !== null) {
                    $filtered[$resolved] = $columns;
                }
            }
        }

        $resolvedReferences = $filtered;
    }

    $findings = [];

    foreach ($resolvedReferences as $table => $columns) {
        $finding = buildTableFinding(
            $conn,
            $table,
            $columns,
            $filterUserId,
            $limit
        );

        if ($finding !== null) {
            $findings[] = $finding;
        }
    }

    $confirmedLookup = array_fill_keys(
        array_keys($resolvedReferences),
        true
    );

    $discovered = [];

    if (!$noDiscovery && $filterTable === null) {
        $discovered = discoverPossibleReferences(
            $conn,
            $allTables,
            $confirmedLookup,
            $discoveryColumns,
            $filterUserId,
            $limit
        );
    }

    $report = [
        'tool' => 'Buzzjuice WoWonder Orphan User Auditor/Cleaner',
        'version' => '2026-09-17',
        'generated_at_utc' => gmdate('c'),
        'mode' => $discoverOnly
            ? 'discover-only'
            : ($execute ? 'execute' : 'dry-run'),
        'database' => defined('WOWONDER_DB_NAME')
            ? WOWONDER_DB_NAME
            : null,
        'users_table' => BZJ_USERS_TABLE,
        'existing_user_count' => $existingUserCount,
        'filter_user_id' => $filterUserId,
        'filter_table' => $filterTable,
        'sample_limit' => $limit,
        'confirmed_findings' => $findings,
        'discovered_findings' => $discovered,
        'deleted_rows' => [],
        'verification' => null,
    ];

    /*
     * Always write the scan report before any destructive operation.
     */
    writeReport($reportPath, $report);

    echo "Existing users: {$existingUserCount}\n";
    echo "Report: {$reportPath}\n\n";

    if ($findings) {
        echo "CONFIRMED ORPHAN REFERENCES\n";
        echo "===========================\n";

        foreach ($findings as $finding) {
            echo sprintf(
                "%s: %d orphan row(s)\n",
                $finding['table'],
                $finding['orphan_row_count']
            );

            foreach ($finding['columns'] as $columnFinding) {
                echo sprintf(
                    "  - %s: %d reference(s); sample IDs: %s\n",
                    $columnFinding['column'],
                    $columnFinding['count'],
                    $columnFinding['sample_user_ids']
                        ? implode(
                            ', ',
                            $columnFinding['sample_user_ids']
                        )
                        : '(none)'
                );
            }
        }
    } else {
        echo "No confirmed orphan references found.\n";
    }

    if ($discovered) {
        echo "\nADVISORY SCHEMA DISCOVERY\n";
        echo "=========================\n";

        foreach ($discovered as $finding) {
            echo sprintf(
                "%s.%s: %d row(s); sample IDs: %s\n",
                $finding['table'],
                $finding['column'],
                $finding['count'],
                $finding['sample_user_ids']
                    ? implode(', ', $finding['sample_user_ids'])
                    : '(none)'
            );
        }

        echo "\nAdvisory findings are NOT eligible for automatic deletion.\n";
    }

    if (!$execute || $discoverOnly) {
        echo "\nDry run complete. No database rows were changed.\n";
        echo "Review the JSON report before execution.\n";
        exit(0);
    }

    if (!$findings) {
        echo "\nNothing is eligible for deletion.\n";
        exit(0);
    }

    /*
     * IMPORTANT:
     * Refresh the authoritative ID set immediately before deletion.
     *
     * The first scan may have occurred seconds/minutes before execution.
     * A new user could have been created in the meantime. Refreshing avoids
     * deleting a row merely because it was orphaned in the earlier snapshot.
     */
    createExistingUserSet($conn);

    $deletedRows = [];

    foreach ($findings as $finding) {
        $table = $finding['table'];

        if (!tableExists($conn, $table)) {
            continue;
        }

        /*
         * Rebuild the condition against the fresh snapshot rather than
         * reusing the previous SQL condition.
         */
        $actualColumns = getColumns($conn, $table);
        $conditions = [];

        foreach ($finding['columns'] as $columnFinding) {
            $column = $columnFinding['column'];
            $key = strtolower($column);

            if (!isset($actualColumns[$key])) {
                continue;
            }

            $conditions[] = '(' .
                orphanCondition(
                    $actualColumns[$key],
                    $filterUserId
                ) .
                ')';
        }

        if (!$conditions) {
            continue;
        }

        $deleteCondition = implode(' OR ', $conditions);

        echo "\nPurging {$table}...\n";

        if (!$conn->begin_transaction()) {
            throw new RuntimeException(
                "Unable to start transaction for {$table}: {$conn->error}"
            );
        }

        try {
            $sql =
                'DELETE FROM ' .
                quoteIdentifier($table) .
                ' WHERE ' .
                $deleteCondition;

            queryOrFail($conn, $sql);

            $affectedRows = $conn->affected_rows;

            if (!$conn->commit()) {
                throw new RuntimeException(
                    "Unable to commit {$table}: {$conn->error}"
                );
            }

            $deletedRows[] = [
                'table' => $table,
                'deleted_rows' => $affectedRows,
                'status' => 'committed',
            ];

            echo "  Deleted: {$affectedRows}\n";
        } catch (Throwable $e) {
            $conn->rollback();

            $deletedRows[] = [
                'table' => $table,
                'deleted_rows' => 0,
                'status' => 'rolled_back',
                'error' => $e->getMessage(),
            ];

            throw $e;
        }
    }

    /*
     * Final verification against a fresh authoritative user-ID snapshot.
     */
    createExistingUserSet($conn);

    $verification = [];

    foreach ($resolvedReferences as $table => $columns) {
        if (
            $filterTable !== null &&
            $table !== $filterTable
        ) {
            continue;
        }

        if (!tableExists($conn, $table)) {
            continue;
        }

        $actualColumns = getColumns($conn, $table);

        foreach ($columns as $configuredColumn) {
            $key = strtolower($configuredColumn);

            if (!isset($actualColumns[$key])) {
                continue;
            }

            $column = $actualColumns[$key];

            $count = getColumnCount(
                $conn,
                $table,
                orphanCondition($column, $filterUserId)
            );

            if ($count > 0) {
                $verification[] = [
                    'table' => $table,
                    'column' => $column,
                    'remaining_orphan_rows' => $count,
                ];
            }
        }
    }

    $report['deleted_rows'] = $deletedRows;
    $report['verification'] = [
        'remaining_confirmed_orphans' => $verification,
        'verified_at_utc' => gmdate('c'),
        'clean' => empty($verification),
    ];
    $report['completed_at_utc'] = gmdate('c');

    writeReport($reportPath, $report);

    echo "\nVERIFICATION\n";
    echo "============\n";

    if (!$verification) {
        echo "Confirmed orphan references remaining: 0\n";
        echo "Cleanup completed successfully.\n";
    } else {
        echo "WARNING: confirmed orphan references remain:\n";

        foreach ($verification as $item) {
            echo sprintf(
                "  %s.%s: %d row(s)\n",
                $item['table'],
                $item['column'],
                $item['remaining_orphan_rows']
            );
        }

        echo "Review the final JSON report.\n";
    }

    echo "\nFinal report: {$reportPath}\n";

    exit(empty($verification) ? 0 : 2);

} catch (Throwable $e) {
    fwrite(
        STDERR,
        "\nCLEANUP FAILED\n" .
        $e->getMessage() .
        "\n"
    );

    exit(1);
}
