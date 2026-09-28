<?php
if (!defined('ABSPATH')) {
    exit;
}

class PGB_Streams_DB {
    private $db;
    private $wow_db;
    private $table;
    private $logger;

    public function __construct(PGB_Logger $logger) {
        $this->logger = $logger;

        $helpers = dirname(__DIR__, 2) . '/db_helpers.php';
        if (!function_exists('get_wowonder_db') && is_readable($helpers)) {
            require_once $helpers;
        }

        if (!function_exists('get_wp_db_conn')) {
            throw new RuntimeException('get_wp_db_conn() is unavailable.');
        }

        $this->db = get_wp_db_conn();
        $this->wow_db = function_exists('get_wowonder_db') ? get_wowonder_db() : null;
        if (!$this->db) {
            throw new RuntimeException('Unable to connect to the WordPress database.');
        }

        $prefix = defined('WP_TABLE_PREFIX') ? WP_TABLE_PREFIX : 'wp_';
        $db_name = defined('WP_DB_NAME') ? WP_DB_NAME : '';
        if (!$db_name || !$prefix) {
            throw new RuntimeException('WordPress database constants are not configured.');
        }

        $this->table = '`' . str_replace('`', '', $db_name) . '`.`' . str_replace('`', '', $prefix) . 'bzj_pgb_transactions`';
        $this->ensure_schema();
    }

    public function ensure_schema() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
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

        if (!$this->db->query($sql)) {
            throw new RuntimeException('Unable to create/read PGB table: ' . $this->db->error);
        }
        $columns = array();
        $check = $this->db->query("SHOW COLUMNS FROM {$this->table}");
        if ($check) {
            while ($column = $check->fetch_assoc()) {
                $columns[$column['Field']] = true;
            }
        }
        if (!isset($columns['idempotency_key'])) {
            $this->db->query("ALTER TABLE {$this->table} ADD COLUMN idempotency_key VARCHAR(128) NULL AFTER handoff_token");
            $this->db->query("ALTER TABLE {$this->table} ADD UNIQUE KEY uq_idempotency_key (idempotency_key)");
        }
    }

    public function create_intent(array $payload) {
        $request_id = 'pgb_' . gmdate('YmdHis') . '_' . bin2hex(random_bytes(8));
        $token = bin2hex(random_bytes(32));
        $timestamp = time();
        $now = gmdate('Y-m-d H:i:s');

        $sql = "INSERT INTO {$this->table}
            (request_id,handoff_token,idempotency_key,handoff_timestamp,status,wow_order_id,wow_user_id,wp_user_id,customer_email,
             transaction_kind,amount,product_price,currency_code,product_name,product_id,variation_id,quantity,
             product_owner_id,wow_post_id,address_id,subscription_period,subscription_interval,subscription_length,
             return_path,payload_json,created_at,updated_at)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('PGB intent prepare failed: ' . $this->db->error);
        }

        $status = 'pending';
        $variation_id = !empty($payload['variation_id']) ? (int) $payload['variation_id'] : null;
        $product_id = !empty($payload['product_id']) ? (int) $payload['product_id'] : null;
        $subscription_interval = isset($payload['subscription_interval']) ? (int) $payload['subscription_interval'] : null;
        $subscription_length = isset($payload['subscription_length']) ? (int) $payload['subscription_length'] : null;

        $payload_json = wp_json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $stmt->bind_param(
            'sssissiiissddsiisiiiisssiiss',
            $request_id,
            $token,
            $payload['idempotency_key'],
            $timestamp,
            $status,
            $payload['wow_order_id'],
            $payload['wow_user_id'],
            $payload['wp_user_id'],
            $payload['customer_email'],
            $payload['transaction_kind'],
            $payload['amount'],
            $payload['product_price'],
            $payload['currency_code'],
            $payload['product_name'],
            $product_id,
            $variation_id,
            $payload['quantity'],
            $payload['product_owner_id'],
            $payload['wow_post_id'],
            $payload['address_id'],
            $payload['subscription_period'],
            $subscription_interval,
            $subscription_length,
            $payload['return_path'],
            $payload_json,
            $now,
            $now
        );

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new RuntimeException('PGB intent insert failed: ' . $error);
        }

        $stmt->close();

        return array(
            'request_id' => $request_id,
            'token' => $token,
            'timestamp' => $timestamp,
            'signature' => PGB_Config::sign($request_id, $token, $timestamp),
        );
    }

    public function get_by_handoff($request_id, $token) {
        $sql = "SELECT * FROM {$this->table} WHERE request_id = ? AND handoff_token = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('PGB handoff lookup failed: ' . $this->db->error);
        }
        $stmt->bind_param('ss', $request_id, $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row ?: null;
    }

    public function get_by_woo_order($woo_order_id) {
        $sql = "SELECT * FROM {$this->table} WHERE woo_order_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('i', $woo_order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row ?: null;
    }

    public function claim_for_processing($id) {
        $timeout = max(60, (int) PGB_Config::get('processing_timeout', 600));
        $stale = gmdate('Y-m-d H:i:s', time() - $timeout);

        $sql = "UPDATE {$this->table}
                SET status='processing', processing_started_at=UTC_TIMESTAMP(), attempts=attempts+1, updated_at=UTC_TIMESTAMP()
                WHERE id=? AND (
                    status='pending'
                    OR (status='processing' AND processing_started_at IS NOT NULL AND processing_started_at < ?)
                )";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('PGB processing claim failed: ' . $this->db->error);
        }
        $stmt->bind_param('is', $id, $stale);
        $stmt->execute();
        $changed = $stmt->affected_rows > 0;
        $stmt->close();
        return $changed;
    }

    public function attach_woo_order($id, $woo_order_id) {
        $sql = "UPDATE {$this->table}
                SET woo_order_id=?, status='order_created', updated_at=UTC_TIMESTAMP()
                WHERE id=? AND status IN ('processing','order_created')";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('PGB order attach prepare failed: ' . $this->db->error);
        }
        $stmt->bind_param('ii', $woo_order_id, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function set_status($id, $status) {
        $sql = "UPDATE {$this->table} SET status=?, updated_at=UTC_TIMESTAMP() WHERE id=?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('si', $status, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function update_payment_state($wow_order_id, $woo_order_id, $status, $payment_method, $amount) {
        if (!$this->wow_db) {
            $this->logger->error('WoWonder DB connection unavailable for payment transaction update', array(), __FILE__);
            return false;
        }

        $columns = array();
        $res = $this->wow_db->query("SHOW COLUMNS FROM Wo_Payment_Transactions");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $columns[$row['Field']] = true;
            }
        }

        if (!isset($columns['payment_status'])) {
            $this->logger->warning('Wo_Payment_Transactions has no payment_status column', array(), __FILE__);
            return false;
        }

        $sets = array('payment_status=?');
        $types = 's';
        $values = array($status);

        if (isset($columns['payment_method'])) {
            $sets[] = 'payment_method=?';
            $types .= 's';
            $values[] = $payment_method;
        }
        if (isset($columns['woo_order_id'])) {
            $sets[] = 'woo_order_id=?';
            $types .= 'i';
            $values[] = (int) $woo_order_id;
        }
        if (isset($columns['amount'])) {
            $sets[] = 'amount=?';
            $types .= 'd';
            $values[] = (float) $amount;
        }
        if (isset($columns['transaction_dt'])) {
            $sets[] = 'transaction_dt=NOW()';
        }

        $sql = "UPDATE Wo_Payment_Transactions SET " . implode(',', $sets) . " WHERE order_id=?";
        $types .= 's';
        $values[] = $wow_order_id;

        $stmt = $this->wow_db->prepare($sql);
        if (!$stmt) {
            $this->logger->error('Wo_Payment_Transactions update prepare failed', array('error' => $this->wow_db->error), __FILE__);
            return false;
        }

        $this->bind_dynamic($stmt, $types, $values);
        $ok = $stmt->execute();
        if (!$ok) {
            $this->logger->error('Wo_Payment_Transactions update failed', array('error' => $stmt->error), __FILE__);
        }
        $stmt->close();
        return $ok;
    }

    public function connection() {
        return $this->db;
    }

    private function bind_dynamic($stmt, $types, array $values) {
        $refs = array($types);
        foreach ($values as $key => $value) {
            $refs[] = &$values[$key];
        }
        call_user_func_array(array($stmt, 'bind_param'), $refs);
    }
}
