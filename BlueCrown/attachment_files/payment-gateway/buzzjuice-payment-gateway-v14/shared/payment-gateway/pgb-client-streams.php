<?php
/**
 * Streams-side PGB client.
 *
 * This file does not call WordPress over HTTP.
 * It writes a durable pending intent to the WordPress database using the
 * existing shared DB credentials, then returns a signed browser handoff URL.
 */

if (!function_exists('pgb_streams_create_payment_intent')) {

    function pgb_streams_log($message, $context = array()) {
        $enabled = getenv('PGB_CLIENT_LOGGING');
        if ($enabled === false || $enabled === '' || $enabled === '1' || strtolower((string) $enabled) === 'true') {
            $dir = __DIR__ . '/log';
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $line = '[' . gmdate('Y-m-d H:i:s') . '] ' . $message;
            if ($context) {
                $line .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES);
            }
            @file_put_contents($dir . '/pgb-client-streams.log', $line . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }

    function pgb_streams_secret() {
        $secret = getenv('BZJ_PGB_HANDOFF_SECRET');
        if (!$secret) {
            throw new RuntimeException('BZJ_PGB_HANDOFF_SECRET is not configured.');
        }
        return $secret;
    }

    function pgb_streams_sign($request_id, $token, $timestamp) {
        return hash_hmac('sha256', $request_id . "\n" . $token . "\n" . $timestamp, pgb_streams_secret());
    }

    function pgb_streams_create_payment_intent(array $data) {
        $helpers = dirname(__DIR__) . '/db_helpers.php';
        if (!is_readable($helpers)) {
            throw new RuntimeException('shared/db_helpers.php not found.');
        }

        require_once $helpers;

        if (!function_exists('get_wp_db_conn')) {
            throw new RuntimeException('get_wp_db_conn() is unavailable.');
        }

        $db = get_wp_db_conn();
        if (!$db) {
            throw new RuntimeException('Unable to connect to WordPress database.');
        }

        $wp_db_name = defined('WP_DB_NAME') ? WP_DB_NAME : getenv('WORDPRESS_DB_NAME');
        $wp_prefix = defined('WP_TABLE_PREFIX') ? WP_TABLE_PREFIX : 'wp_';

        if (!$wp_db_name) {
            throw new RuntimeException('WordPress database name is not configured.');
        }

        $table = '`' . str_replace('`', '', $wp_db_name) . '`.`' . str_replace('`', '', $wp_prefix) . 'bzj_pgb_transactions`';

        $create = "CREATE TABLE IF NOT EXISTS {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            request_id VARCHAR(80) NOT NULL,
            handoff_token VARCHAR(128) NOT NULL,
            idempotency_key VARCHAR(128) NOT NULL,
            handoff_timestamp BIGINT UNSIGNED NOT NULL,
            status VARCHAR(30) NOT NULL DEFAULT 'pending',
            processing_started_at DATETIME NULL,
            wow_order_id VARCHAR(100) NOT NULL,
            wow_user_id BIGINT UNSIGNED NOT NULL,
            wp_user_id BIGINT UNSIGNED NOT NULL,
            customer_email VARCHAR(190) NOT NULL,
            transaction_kind VARCHAR(30) NOT NULL,
            amount DECIMAL(20,6) NOT NULL DEFAULT 0,
            product_price DECIMAL(20,6) NOT NULL DEFAULT 0,
            currency_code VARCHAR(10) NOT NULL,
            product_name TEXT NULL,
            product_id BIGINT UNSIGNED NULL,
            variation_id BIGINT UNSIGNED NULL,
            quantity INT UNSIGNED NOT NULL DEFAULT 1,
            product_owner_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            wow_post_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            address_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            subscription_period VARCHAR(20) NULL,
            subscription_interval INT UNSIGNED NULL,
            subscription_length INT UNSIGNED NULL,
            return_path VARCHAR(500) NULL,
            payload_json LONGTEXT NOT NULL,
            woo_order_id BIGINT UNSIGNED NULL,
            attempts INT UNSIGNED NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uq_request_id (request_id),
            UNIQUE KEY uq_handoff_token (handoff_token),
            UNIQUE KEY uq_idempotency_key (idempotency_key),
            UNIQUE KEY uq_wow_order_id (wow_order_id),
            KEY idx_status (status),
            KEY idx_woo_order_id (woo_order_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if (!$db->query($create)) {
            throw new RuntimeException('Unable to create PGB table: ' . $db->error);
        }
        $columns = array();
        $check = $db->query("SHOW COLUMNS FROM {$table}");
        if ($check) {
            while ($column = $check->fetch_assoc()) {
                $columns[$column['Field']] = true;
            }
        }
        if (!isset($columns['idempotency_key'])) {
            $db->query("ALTER TABLE {$table} ADD COLUMN idempotency_key VARCHAR(128) NULL AFTER handoff_token");
            $db->query("ALTER TABLE {$table} ADD UNIQUE KEY uq_idempotency_key (idempotency_key)");
        }

        $request_id = 'pgb_' . gmdate('YmdHis') . '_' . bin2hex(random_bytes(8));
        $idempotency_key = !empty($data['idempotency_key']) ? (string) $data['idempotency_key'] : hash('sha256',
            $data['wow_user_id'] . '|' . $data['transaction_kind'] . '|' . $data['amount'] . '|' .
            ($data['product_id'] ?? 0) . '|' . ($data['variation_id'] ?? 0) . '|' . ($data['wow_post_id'] ?? 0) . '|' .
            gmdate('YmdHi')
        );
        $token = bin2hex(random_bytes(32));
        $timestamp = time();
        $now = gmdate('Y-m-d H:i:s');

        $payload_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $sql = "INSERT INTO {$table}
            (request_id,handoff_token,idempotency_key,handoff_timestamp,status,wow_order_id,wow_user_id,wp_user_id,customer_email,
             transaction_kind,amount,product_price,currency_code,product_name,product_id,variation_id,quantity,
             product_owner_id,wow_post_id,address_id,subscription_period,subscription_interval,subscription_length,
             return_path,payload_json,created_at,updated_at)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('PGB insert prepare failed: ' . $db->error);
        }

        $status = 'pending';
        $product_id = !empty($data['product_id']) ? (int) $data['product_id'] : null;
        $variation_id = !empty($data['variation_id']) ? (int) $data['variation_id'] : null;
        $sub_interval = isset($data['subscription_interval']) ? (int) $data['subscription_interval'] : null;
        $sub_length = isset($data['subscription_length']) ? (int) $data['subscription_length'] : null;

        $stmt->bind_param(
            'sssissiiissddsiisiiiisssiiss',
            $request_id,
            $token,
            $idempotency_key,
            $timestamp,
            $status,
            $data['wow_order_id'],
            $data['wow_user_id'],
            $data['wp_user_id'],
            $data['customer_email'],
            $data['transaction_kind'],
            $data['amount'],
            $data['product_price'],
            $data['currency_code'],
            $data['product_name'],
            $product_id,
            $variation_id,
            $data['quantity'],
            $data['product_owner_id'],
            $data['wow_post_id'],
            $data['address_id'],
            $data['subscription_period'],
            $sub_interval,
            $sub_length,
            $data['return_path'],
            $payload_json,
            $now,
            $now
        );

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();

            if (strpos(strtolower($error), 'duplicate') !== false) {
                $lookup = $db->prepare("SELECT request_id, handoff_token, handoff_timestamp, status, wow_order_id FROM {$table} WHERE idempotency_key=? LIMIT 1");
                if ($lookup) {
                    $lookup->bind_param('s', $idempotency_key);
                    $lookup->execute();
                    $result = $lookup->get_result();
                    $existing = $result ? $result->fetch_assoc() : null;
                    $lookup->close();

                    if ($existing) {
                        $signature = pgb_streams_sign($existing['request_id'], $existing['handoff_token'], (int) $existing['handoff_timestamp']);
                        $base = rtrim((string) getenv('WP_BASE_SITE_URL'), '/');
                        return array(
                            'status' => 200,
                            'url' => $base . '/pgb-checkout/?' . http_build_query(array(
                                'request_id' => $existing['request_id'],
                                'token' => $existing['handoff_token'],
                                'ts' => $existing['handoff_timestamp'],
                                'sig' => $signature,
                            )),
                            'request_id' => $existing['request_id'],
                            'wow_order_id' => $existing['wow_order_id'],
                            'duplicate' => true,
                        );
                    }
                }
            }

            throw new RuntimeException('PGB insert failed: ' . $error);
        }
        $stmt->close();

        // Preserve the existing WoWonder payment transaction ledger, but do not
        // create paid fulfillment records until WooCommerce confirms payment.
        $wow_db = function_exists('get_wowonder_db') ? get_wowonder_db() : null;
        if ($wow_db) {
            $kind = $data['transaction_kind'] === 'PRODUCT' ? 'PURCHASE' : $data['transaction_kind'];
            $stmt2 = $wow_db->prepare(
                "INSERT INTO Wo_Payment_Transactions (userid, amount, order_id, kind, currency_code)
                 VALUES (?, ?, ?, ?, ?)"
            );

            if ($stmt2) {
                $stmt2->bind_param(
                    'idsss',
                    $data['wow_user_id'],
                    $data['amount'],
                    $data['wow_order_id'],
                    $kind,
                    $data['currency_code']
                );
                if (!$stmt2->execute()) {
                    pgb_streams_log('WoWonder pending transaction insert failed', array('error' => $stmt2->error));
                    $stmt2->close();
                    throw new RuntimeException('Unable to create the payment transaction ledger.');
                }
                $stmt2->close();
            } else {
                pgb_streams_log('WoWonder pending transaction prepare failed', array('error' => $wow_db->error));
                throw new RuntimeException('Unable to create the payment transaction ledger.');
            }
        } else {
            throw new RuntimeException('WoWonder database connection is unavailable.');
        }

        $signature = pgb_streams_sign($request_id, $token, $timestamp);
        $base = rtrim((string) getenv('WP_BASE_SITE_URL'), '/');
        if (!$base) {
            throw new RuntimeException('WP_BASE_SITE_URL is not configured.');
        }

        $handoff_path = '/pgb-checkout/';
        $url = $base . $handoff_path . '?' . http_build_query(array(
            'request_id' => $request_id,
            'token' => $token,
            'ts' => $timestamp,
            'sig' => $signature,
        ));

        pgb_streams_log('Payment intent created', array(
            'request_id' => $request_id,
            'wow_order_id' => $data['wow_order_id'],
            'wow_user_id' => $data['wow_user_id'],
        ));

        return array(
            'status' => 200,
            'url' => $url,
            'request_id' => $request_id,
            'wow_order_id' => $data['wow_order_id'],
        );
    }
}
