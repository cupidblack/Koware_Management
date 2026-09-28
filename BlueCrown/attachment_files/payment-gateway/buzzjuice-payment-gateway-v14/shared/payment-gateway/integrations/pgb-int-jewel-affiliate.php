<?php
if (!defined('ABSPATH')) {
    exit;
}

function pgb_int_jewel_affiliate_fulfill($order, PGB_Logger $logger) {
    if (!$order || !$order->is_paid()) {
        return false;
    }

    $helpers = dirname(__DIR__, 2) . '/db_helpers.php';
    if (!function_exists('get_wowonder_db') && is_readable($helpers)) {
        require_once $helpers;
    }

    if (!function_exists('get_wowonder_db')) {
        $logger->warning('Jewel integration skipped: WoWonder DB helper unavailable', array(), __FILE__);
        return true;
    }

    $db = get_wowonder_db();
    if (!$db) {
        $logger->warning('Jewel integration skipped: WoWonder DB unavailable', array(), __FILE__);
        return true;
    }

    $mapping = pgb_jewel_load_mapping();
    if (!$mapping) {
        return true;
    }

    $wow_user_id = absint($order->get_meta('bz_wow_user_id'));
    if (!$wow_user_id) {
        return true;
    }

    $activated = false;

    foreach ($order->get_items('line_item') as $item) {
        $variation_id = absint($item->get_variation_id());
        if (!$variation_id) {
            $variation_id = absint($item->get_product_id());
        }

        if (!$variation_id || empty($mapping[$variation_id])) {
            continue;
        }

        $map = $mapping[$variation_id];
        $pro_type = absint($map['wow_pro_type'] ?? 0);

        if (!$pro_type) {
            continue;
        }

        $columns = array();
        $result = $db->query("SHOW COLUMNS FROM Wo_Users");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $columns[$row['Field']] = true;
            }
        }

        $sets = array();
        $types = '';
        $values = array();

        if (isset($columns['pro_time'])) {
            $sets[] = 'pro_time=?';
            $types .= 'i';
            $values[] = time();
        }
        if (isset($columns['is_pro'])) {
            $sets[] = 'is_pro=?';
            $types .= 'i';
            $values[] = 1;
        }
        if (isset($columns['pro_type'])) {
            $sets[] = 'pro_type=?';
            $types .= 'i';
            $values[] = $pro_type;
        }

        if ($sets) {
            $types .= 'i';
            $values[] = $wow_user_id;
            $stmt = $db->prepare("UPDATE Wo_Users SET " . implode(',', $sets) . " WHERE user_id=? LIMIT 1");
            if ($stmt) {
                pgb_jewel_bind($stmt, $types, $values);
                if ($stmt->execute()) {
                    $activated = true;
                }
                $stmt->close();
            }
        }

        pgb_jewel_sync_role($db, $wow_user_id, $map, $mapping, $logger);
    }

    $credit_cents = absint($order->get_meta('bz_rebate_credit_cents'));
    if (!$credit_cents) {
        $credit_amount = (float) $order->get_meta('bz_rebate_credit_amount');
        if ($credit_amount > 0) {
            $credit_cents = (int) round($credit_amount * 100);
        }
    }

    if ($credit_cents > 0) {
        pgb_jewel_apply_rebate($db, $wow_user_id, $credit_cents, $order, $logger);
    }

    $logger->info('Jewel Affiliate integration completed', array(
        'order_id' => $order->get_id(),
        'wow_user_id' => $wow_user_id,
        'activated' => $activated,
    ), __FILE__);

    return true;
}

function pgb_jewel_load_mapping() {
    global $wpdb;

    $json = $wpdb->get_var($wpdb->prepare(
        "SELECT option_value FROM {$wpdb->options} WHERE option_name=%s LIMIT 1",
        'bz_rebate_mapping_json'
    ));

    if (!$json) {
        return array();
    }

    $rows = json_decode($json, true);
    if (!is_array($rows)) {
        return array();
    }

    $map = array();
    foreach ($rows as $row) {
        $id = absint($row['variation_id'] ?? 0);
        if ($id) {
            $map[$id] = array(
                'wow_pro_type' => absint($row['wow_pro_type'] ?? 0),
                'wp_role' => sanitize_key($row['wp_role'] ?? ''),
                'wow_label' => sanitize_text_field($row['wow_label'] ?? ''),
            );
        }
    }
    return $map;
}

function pgb_jewel_apply_rebate($db, $wow_user_id, $credit_cents, $order, PGB_Logger $logger) {
    $amount = round($credit_cents / 100, 2);
    $woo_order_id = $order->get_id();

    $stmt = $db->prepare("SELECT id FROM Wo_Payment_Transactions WHERE (woo_order_id=? OR order_id=?) AND userid=? AND notes LIKE '%Rebate%' LIMIT 1");
    if ($stmt) {
        $woo = (string) $woo_order_id;
        $wow_order = (string) $order->get_meta('wow_order_id');
        $stmt->bind_param('ssi', $woo, $wow_order, $wow_user_id);
        $stmt->execute();
        $stmt->store_result();
        $already = $stmt->num_rows > 0;
        $stmt->close();
        if ($already) {
            return;
        }
    }

    $db->begin_transaction();
    try {
        $stmt = $db->prepare("UPDATE Wo_Users SET wallet=wallet+? WHERE user_id=? LIMIT 1");
        if (!$stmt) {
            throw new RuntimeException('wallet update prepare failed: ' . $db->error);
        }
        $stmt->bind_param('di', $amount, $wow_user_id);
        if (!$stmt->execute()) {
            throw new RuntimeException($stmt->error);
        }
        $stmt->close();

        $kind = 'RECEIVED';
        $note = 'Rebate - Subscription Rebate credit';
        $order_ref = (string) $order->get_meta('wow_order_id');
        $method = 'rebate';

        $stmt = $db->prepare("INSERT INTO Wo_Payment_Transactions
            (userid,kind,amount,notes,order_id,payment_status,payment_method,woo_order_id,transaction_dt)
            VALUES (?, ?, ?, ?, ?, 'completed', ?, ?, NOW())");
        if (!$stmt) {
            throw new RuntimeException('rebate transaction prepare failed: ' . $db->error);
        }
        $stmt->bind_param('isdsssi', $wow_user_id, $kind, $amount, $note, $order_ref, $method, $woo_order_id);
        if (!$stmt->execute()) {
            throw new RuntimeException($stmt->error);
        }
        $stmt->close();

        $db->commit();
    } catch (Throwable $e) {
        $db->rollback();
        $logger->error('Jewel rebate failed', array('error' => $e->getMessage(), 'order_id' => $woo_order_id), __FILE__);
    }
}

function pgb_jewel_sync_role($wow_db, $wow_user_id, array $map_row, array $full_mapping, PGB_Logger $logger) {
    $helpers = dirname(__DIR__, 2) . '/db_helpers.php';
    if (!function_exists('get_wp_db_conn') && is_readable($helpers)) {
        require_once $helpers;
    }

    if (!function_exists('get_wp_db_conn')) {
        return;
    }

    $wpdb = get_wp_db_conn();
    if (!$wpdb) {
        return;
    }

    $stmt = $wow_db->prepare("SELECT wp_user_id FROM Wo_Users WHERE user_id=? LIMIT 1");
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('i', $wow_user_id);
    $stmt->execute();
    $stmt->bind_result($wp_user_id);
    $stmt->fetch();
    $stmt->close();

    $wp_user_id = absint($wp_user_id);
    if (!$wp_user_id) {
        return;
    }

    $prefix = defined('WP_TABLE_PREFIX') ? WP_TABLE_PREFIX : 'wp_';
    $meta_table = $prefix . 'usermeta';
    $cap_key = $prefix . 'capabilities';

    $stmt = $wpdb->prepare("SELECT meta_value FROM `{$meta_table}` WHERE user_id=? AND meta_key=? LIMIT 1");
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('is', $wp_user_id, $cap_key);
    $stmt->execute();
    $stmt->bind_result($meta_value);
    $found = $stmt->fetch();
    $stmt->close();

    $caps = array();
    if ($found && $meta_value) {
        $decoded = @unserialize($meta_value);
        if (is_array($decoded)) {
            $caps = $decoded;
        }
    }

    $caps['jewel-affiliate'] = true;

    $mapped = sanitize_key($map_row['wp_role'] ?? '');
    if ($mapped) {
        $caps[$mapped] = true;
    }

    foreach ($full_mapping as $row) {
        $role = sanitize_key($row['wp_role'] ?? '');
        if ($role && $role !== $mapped) {
            unset($caps[$role]);
        }
    }

    $serialized = serialize($caps);

    $stmt = $wpdb->prepare("UPDATE `{$meta_table}` SET meta_value=? WHERE user_id=? AND meta_key=?");
    if ($stmt) {
        $stmt->bind_param('sis', $serialized, $wp_user_id, $cap_key);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        if (!$affected) {
            $stmt = $wpdb->prepare("INSERT INTO `{$meta_table}` (user_id,meta_key,meta_value) VALUES (?,?,?)");
            if ($stmt) {
                $stmt->bind_param('iss', $wp_user_id, $cap_key, $serialized);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

function pgb_jewel_bind($stmt, $types, array $values) {
    $refs = array($types);
    foreach ($values as $key => $value) {
        $refs[] = &$values[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $refs);
}
