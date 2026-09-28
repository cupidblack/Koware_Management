<?php
if (!defined('ABSPATH')) {
    exit;
}

function pgb_addon_wowonder_fulfill($order, PGB_Logger $logger) {
    $helpers = dirname(__DIR__, 2) . '/db_helpers.php';
    if (!function_exists('get_wowonder_db') && is_readable($helpers)) {
        require_once $helpers;
    }

    if (!function_exists('get_wowonder_db')) {
        $logger->error('WoWonder DB helper unavailable', array(), __FILE__);
        return false;
    }

    $db = get_wowonder_db();
    if (!$db) {
        $logger->error('WoWonder DB connection unavailable', array(), __FILE__);
        return false;
    }

    $wow_user_id = absint($order->get_meta('bz_wow_user_id'));
    $wow_order_id = (string) $order->get_meta('wow_order_id');
    $kind = strtoupper((string) $order->get_meta('_bzj_pgb_transaction_kind'));
    $amount = (float) $order->get_total();
    $currency = strtoupper((string) $order->get_meta('_bzj_pgb_currency'));
    $owner_id = absint($order->get_meta('product_owner_id'));
    $post_id = absint($order->get_meta('wow_post_id'));
    $address_id = absint($order->get_meta('address_id'));

    if (!$wow_user_id || !$wow_order_id) {
        $logger->error('WoWonder fulfillment missing user/order identifiers', array('woo_order_id' => $order->get_id()), __FILE__);
        return false;
    }

    $status = $order->get_status();
    pgb_addon_wowonder_update_payment_transaction(
        $db,
        $wow_order_id,
        $order->get_id(),
        'completed',
        $order->get_payment_method(),
        $amount,
        $logger
    );

    if ($kind === 'PRODUCT' || $kind === 'PURCHASE' || $kind === 'MARKET') {
        if (!pgb_addon_wowonder_market_purchase($db, $order, $wow_user_id, $wow_order_id, $owner_id, $post_id, $address_id, $amount, $currency, $logger)) {
            return false;
        }
    } elseif ($kind === 'WALLET') {
        if (!pgb_addon_wowonder_wallet_credit($db, $wow_user_id, $amount, $currency, $wow_order_id, $order->get_id(), $logger)) {
            return false;
        }
    } elseif ($kind === 'PRO') {
        pgb_addon_wowonder_optional_pro_sync($db, $wow_user_id, $post_id, $logger);
    }

    return true;
}

function pgb_addon_wowonder_update_payment_transaction($db, $wow_order_id, $woo_order_id, $status, $method, $amount, PGB_Logger $logger) {
    $columns = array();
    $result = $db->query("SHOW COLUMNS FROM Wo_Payment_Transactions");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[$row['Field']] = true;
        }
    }

    $set = array('payment_status=?');
    $types = 's';
    $values = array($status);

    if (isset($columns['payment_method'])) {
        $set[] = 'payment_method=?';
        $types .= 's';
        $values[] = $method;
    }
    if (isset($columns['woo_order_id'])) {
        $set[] = 'woo_order_id=?';
        $types .= 'i';
        $values[] = $woo_order_id;
    }
    if (isset($columns['amount'])) {
        $set[] = 'amount=?';
        $types .= 'd';
        $values[] = $amount;
    }
    if (isset($columns['transaction_dt'])) {
        $set[] = 'transaction_dt=NOW()';
    }

    $types .= 's';
    $values[] = $wow_order_id;

    $stmt = $db->prepare("UPDATE Wo_Payment_Transactions SET " . implode(',', $set) . " WHERE order_id=?");
    if (!$stmt) {
        $logger->error('Wo_Payment_Transactions update prepare failed', array('error' => $db->error), __FILE__);
        return false;
    }

    pgb_bind_values($stmt, $types, $values);
    $ok = $stmt->execute();
    if (!$ok) {
        $logger->error('Wo_Payment_Transactions update failed', array('error' => $stmt->error), __FILE__);
    }
    $stmt->close();
    return $ok;
}

function pgb_addon_wowonder_market_purchase($db, $order, $wow_user_id, $wow_order_id, $owner_id, $post_id, $address_id, $amount, $currency, PGB_Logger $logger) {
    $exists = false;
    $stmt = $db->prepare("SELECT id FROM Wo_Purchases WHERE order_hash_id=? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('s', $wow_order_id);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
    }

    if ($exists) {
        return true;
    }

    $product_price = 0.0;
    $quantity = 1;
    $product_name = '';

    foreach ($order->get_items('line_item') as $item) {
        $product_name = $item->get_name();
        $quantity = max(1, (int) $item->get_quantity());
        $product_price = (float) $item->get_total() / $quantity;
        break;
    }

    $now = time();
    $data = wp_json_encode(array(
        'name' => $product_name,
        'product_id' => $post_id,
        'units' => $quantity,
        'currency' => $currency,
        'timestamp' => $now,
        'woo_order_id' => $order->get_id(),
    ));

    $commission = 0.0;

    $db->begin_transaction();
    try {
        $stmt = $db->prepare("INSERT INTO Wo_Payment_Transactions
            (userid,amount,order_id,kind,currency_code,notes,payment_status,payment_method,woo_order_id,transaction_dt)
            VALUES (?,?,?,?,?,?, 'completed', ?, ?, NOW())");

        if (!$stmt) {
            throw new RuntimeException('SALE transaction prepare failed: ' . $db->error);
        }

        $kind = 'SALE';
        $notes = 'Product Sale';
        $method = $order->get_payment_method();
        $woo_id = $order->get_id();

        $stmt->bind_param('idsssssi', $owner_id, $amount, $wow_order_id, $kind, $currency, $notes, $method, $woo_id);
        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new RuntimeException($error);
        }
        $stmt->close();

        $stmt = $db->prepare("INSERT INTO Wo_Purchases
            (user_id,order_hash_id,owner_id,data,final_price,commission,price,timestamp,time)
            VALUES (?,?,?,?,?,?,?,?,?)");
        if (!$stmt) {
            throw new RuntimeException('Purchase insert prepare failed: ' . $db->error);
        }
        $stmt->bind_param('isssddddd', $wow_user_id, $wow_order_id, $owner_id, $data, $amount, $commission, $product_price, $now, $now);
        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new RuntimeException($error);
        }
        $stmt->close();

        $status = 'placed';
        $stmt = $db->prepare("INSERT INTO Wo_UserOrders
            (hash_id,user_id,product_owner_id,product_id,address_id,price,commission,final_price,units,status,time)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        if (!$stmt) {
            throw new RuntimeException('User order insert prepare failed: ' . $db->error);
        }
        $stmt->bind_param('siiiiiddssi', $wow_order_id, $wow_user_id, $owner_id, $post_id, $address_id, $amount, $commission, $amount, $quantity, $status, $now);
        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new RuntimeException($error);
        }
        $stmt->close();

        $notification_type = 'new_orders';
        $notification_url = 'index.php?link1=orders';
        $stmt = $db->prepare("INSERT INTO Wo_Notifications
            (notifier_id,recipient_id,type,url,time) VALUES (?,?,?,?,?)");
        if (!$stmt) {
            throw new RuntimeException('Notification insert prepare failed: ' . $db->error);
        }
        $stmt->bind_param('iissi', $wow_user_id, $owner_id, $notification_type, $notification_url, $now);
        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new RuntimeException($error);
        }
        $stmt->close();

        $db->commit();
        return true;
    } catch (Throwable $e) {
        $db->rollback();
        $logger->error('WoWonder market fulfillment failed; transaction rolled back', array(
            'wow_order_id' => $wow_order_id,
            'error' => $e->getMessage(),
        ), __FILE__);
        return false;
    }
}

function pgb_addon_wowonder_wallet_credit($db, $wow_user_id, $amount, $currency, $wow_order_id, $woo_order_id, PGB_Logger $logger) {
    $columns = array();
    $result = $db->query("SHOW COLUMNS FROM Wo_Users");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[$row['Field']] = true;
        }
    }

    $wallet_column = isset($columns['wallet']) ? 'wallet' : (isset($columns['balance']) ? 'balance' : '');
    if (!$wallet_column) {
        $logger->error('Wallet payment received but no wallet/balance column exists', array('wow_user_id' => $wow_user_id), __FILE__);
        return false;
    }

    $stmt = $db->prepare("SELECT id FROM Wo_Payment_Transactions WHERE (woo_order_id=? OR order_id=?) AND userid=? AND kind IN ('RECEIVED','WALLET') LIMIT 1");
    if ($stmt) {
        $woo = (string) $woo_order_id;
        $stmt->bind_param('ssi', $woo, $wow_order_id, $wow_user_id);
        $stmt->execute();
        $stmt->store_result();
        $already = $stmt->num_rows > 0;
        $stmt->close();
        if ($already) {
            return true;
        }
    }

    $db->begin_transaction();
    try {
        $stmt = $db->prepare("UPDATE Wo_Users SET {$wallet_column}={$wallet_column}+? WHERE user_id=? LIMIT 1");
        if (!$stmt) {
            throw new RuntimeException('Wallet update prepare failed: ' . $db->error);
        }
        $stmt->bind_param('di', $amount, $wow_user_id);
        if (!$stmt->execute()) {
            throw new RuntimeException($stmt->error);
        }
        $stmt->close();

        $kind = 'RECEIVED';
        $notes = 'Wallet top-up via Buzzjuice Payment Gateway';
        $currency = strtoupper((string) $currency);
        $method = 'woocommerce';
        $stmt = $db->prepare("INSERT INTO Wo_Payment_Transactions
            (userid,amount,order_id,kind,currency_code,notes,payment_status,payment_method,woo_order_id,transaction_dt)
            VALUES (?,?,?,?,?,?, 'completed', ?, ?, NOW())");
        if (!$stmt) {
            throw new RuntimeException('Wallet transaction prepare failed: ' . $db->error);
        }
        $stmt->bind_param('idsssssi', $wow_user_id, $amount, $wow_order_id, $kind, $currency, $notes, $method, $woo_order_id);
        if (!$stmt->execute()) {
            throw new RuntimeException($stmt->error);
        }
        $stmt->close();

        $db->commit();
        return true;
    } catch (Throwable $e) {
        $db->rollback();
        $logger->error('Wallet credit failed', array('wow_user_id' => $wow_user_id, 'error' => $e->getMessage()), __FILE__);
        return false;
    }
}

function pgb_addon_wowonder_optional_pro_sync($db, $wow_user_id, $pro_type, PGB_Logger $logger) {
    if (!$pro_type) {
        return true;
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

    if (isset($columns['is_pro'])) {
        $sets[] = "is_pro=?";
        $types .= 'i';
        $values[] = 1;
    }
    if (isset($columns['pro_type'])) {
        $sets[] = "pro_type=?";
        $types .= 'i';
        $values[] = (int) $pro_type;
    }
    if (isset($columns['pro_time'])) {
        $sets[] = "pro_time=?";
        $types .= 'i';
        $values[] = time();
    }

    if (!$sets) {
        return true;
    }

    $types .= 'i';
    $values[] = $wow_user_id;

    $stmt = $db->prepare("UPDATE Wo_Users SET " . implode(',', $sets) . " WHERE user_id=? LIMIT 1");
    if (!$stmt) {
        $logger->error('PRO WoWonder sync prepare failed', array('error' => $db->error), __FILE__);
        return false;
    }

    pgb_bind_values($stmt, $types, $values);
    $ok = $stmt->execute();
    if (!$ok) {
        $logger->error('PRO WoWonder sync failed', array('error' => $stmt->error), __FILE__);
    }
    $stmt->close();
    return $ok;
}

function pgb_bind_values($stmt, $types, array $values) {
    $refs = array($types);
    foreach ($values as $key => $value) {
        $refs[] = &$values[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $refs);
}
