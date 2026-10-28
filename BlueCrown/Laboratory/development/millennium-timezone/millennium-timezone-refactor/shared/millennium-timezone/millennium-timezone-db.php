<?php
declare(strict_types=1);

/** Database adapter for optional Millennium Timezone snapshots. */
if (!defined('MILLENNIUM_TIMEZONE_DB_NAME')) define('MILLENNIUM_TIMEZONE_DB_NAME', 'koware_iapd');
if (!defined('MILLENNIUM_TIMEZONE_TABLE_PREFIX')) define('MILLENNIUM_TIMEZONE_TABLE_PREFIX', 'millennium_timezone_');

function millennium_timezone_table_name(string $suffix): string {
    if (!preg_match('/^[A-Za-z0-9_]+$/', $suffix)) {
        throw new InvalidArgumentException('Invalid Millennium Timezone table suffix.');
    }
    return '`' . MILLENNIUM_TIMEZONE_DB_NAME . '`.`' . MILLENNIUM_TIMEZONE_TABLE_PREFIX . $suffix . '`';
}

function millennium_timezone_db_connection(): mysqli|false {
    require_once dirname(__DIR__) . '/db_helpers.php';
    if (!function_exists('get_iapd_db_conn')) {
        error_log('Millennium Timezone: get_iapd_db_conn() is unavailable.');
        return false;
    }
    $db = get_iapd_db_conn();
    if (!$db instanceof mysqli || $db->connect_errno) {
        error_log('Millennium Timezone: IAPD database connection unavailable.');
        return false;
    }
    $db->set_charset('utf8mb4');
    return $db;
}

function millennium_timezone_save_snapshot(array $snapshot): bool {
    $db = millennium_timezone_db_connection();
    if (!$db) return false;
    $table = millennium_timezone_table_name('snapshots');
    $json = json_encode($snapshot, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($json === false) return false;
    $stmt = $db->prepare("INSERT INTO {$table} (snapshot_at, payload_json) VALUES (UTC_TIMESTAMP(), ?)");
    if (!$stmt) {
        error_log('Millennium Timezone snapshot prepare failed: ' . $db->error);
        return false;
    }
    $stmt->bind_param('s', $json);
    $ok = $stmt->execute();
    if (!$ok) error_log('Millennium Timezone snapshot insert failed: ' . $stmt->error);
    $stmt->close();
    return $ok;
}

function millennium_timezone_latest_snapshot(): ?array {
    $db = millennium_timezone_db_connection();
    if (!$db) return null;
    $table = millennium_timezone_table_name('snapshots');
    $result = $db->query("SELECT payload_json FROM {$table} ORDER BY snapshot_at DESC, id DESC LIMIT 1");
    if (!$result) return null;
    $row = $result->fetch_assoc();
    $result->free();
    if (!$row) return null;
    $decoded = json_decode((string) $row['payload_json'], true);
    return is_array($decoded) ? $decoded : null;
}
