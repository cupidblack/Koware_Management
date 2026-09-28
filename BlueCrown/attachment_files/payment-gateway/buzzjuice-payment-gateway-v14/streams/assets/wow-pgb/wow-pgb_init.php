<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/init.php';

if ($wo['loggedin'] == false) {
    $redirect_url = $wo['config']['site_url'] . '/login?redirect_url=' . urlencode($_SERVER['REQUEST_URI']);
    if (!headers_sent()) {
        header('Location: ' . $redirect_url);
    } else {
        echo '<script>window.location.href=' . json_encode($redirect_url) . ';</script>';
    }
    exit;
}

header('Content-Type: application/json; charset=utf-8');

function bzj_pgb_json_error($message, $code = 400, $extra = array()) {
    http_response_code($code);
    echo json_encode(array_merge(array(
        'status' => 'error',
        'message' => $message,
    ), $extra));
    exit;
}

function bzj_pgb_clean_string($value, $fallback = '') {
    $value = is_scalar($value) ? trim((string) $value) : $fallback;
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$userid = (int) ($wo['user']['user_id'] ?? 0);
$email = filter_var($wo['user']['email'] ?? '', FILTER_VALIDATE_EMAIL);
$username = trim((string) ($wo['user']['username'] ?? ''));

if (!$userid || !$email || !$username) {
    bzj_pgb_json_error('Unable to resolve the logged-in Buzzjuice user.', 401);
}

$required = array(
    'amount',
    'product_name',
    'product_price',
    'product_units',
    'product_owner_id',
    'transaction_kind',
    'wow_currency_code',
);

foreach ($required as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === '') {
        bzj_pgb_json_error('Missing required parameter: ' . $field);
    }
}

$amount = (float) $_POST['amount'];
$product_price = (float) $_POST['product_price'];
$quantity = max(1, (int) $_POST['product_units']);
$product_owner_id = max(0, (int) $_POST['product_owner_id']);
$transaction_kind = strtoupper(preg_replace('/[^A-Z0-9_-]/i', '', (string) $_POST['transaction_kind']));
$currency = strtoupper(preg_replace('/[^A-Z]/', '', (string) $_POST['wow_currency_code']));
$product_name = bzj_pgb_clean_string($_POST['product_name']);
$wow_post_id = isset($_POST['wow_post_id']) ? max(0, (int) $_POST['wow_post_id']) : 0;
$address_id = isset($_POST['address_id']) ? max(0, (int) $_POST['address_id']) : 0;

if ($amount <= 0 || $product_price < 0 || $quantity < 1) {
    bzj_pgb_json_error('Invalid payment amount or quantity.');
}

if (!preg_match('/^[A-Z]{3}$/', $currency)) {
    bzj_pgb_json_error('Invalid currency code.');
}

$allowed_kinds = array('PRODUCT', 'PRO', 'WALLET', 'DONATE', 'MARKET', 'PURCHASE');
if (!in_array($transaction_kind, $allowed_kinds, true)) {
    bzj_pgb_json_error('Invalid transaction type.');
}

if ($transaction_kind === 'PRODUCT') {
    $transaction_kind = 'PRODUCT';
}

if (!isset($sqlConnect) || !$sqlConnect) {
    bzj_pgb_json_error('Buzzjuice database connection is unavailable.', 500);
}

$wp_user_id = 0;
$stmt = $sqlConnect->prepare(
    'SELECT wp_user_id FROM ' . T_USERS . ' WHERE user_id = ? LIMIT 1'
);

if (!$stmt) {
    bzj_pgb_json_error('Unable to resolve the WordPress customer.', 500);
}

$stmt->bind_param('i', $userid);
$stmt->execute();
$stmt->bind_result($wp_user_id);
$stmt->fetch();
$stmt->close();

$wp_user_id = (int) $wp_user_id;

if (!$wp_user_id) {
    bzj_pgb_json_error('Your Buzzjuice account is not linked to a WordPress customer account.', 409);
}

$wow_order_id = 'wow_' . gmdate('YmdHis') . '_' . bin2hex(random_bytes(8));
$idempotency_key = !empty($_POST['pgb_idempotency_key']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $_POST['pgb_idempotency_key']) : hash('sha256', $userid . '|' . $transaction_kind . '|' . $amount . '|' . ($wow_post_id ?? 0) . '|' . gmdate('YmdHi'));

$wow_product_kind = 0;
$variation_id = 0;
$product_id = 0;

switch ($transaction_kind) {
    case 'PRO':
        $valid_pro_ids = array(
            1 => (int) ($wo['config']['wow_pro_package_id'] ?? 0),
            2 => (int) ($wo['config']['wow_pro_package_id_2'] ?? 0),
            3 => (int) ($wo['config']['wow_pro_package_id_3'] ?? 0),
            4 => (int) ($wo['config']['wow_pro_package_id_4'] ?? 0),
        );

        if (!isset($valid_pro_ids[$wow_post_id]) || !$valid_pro_ids[$wow_post_id]) {
            bzj_pgb_json_error('Invalid PRO package.');
        }

        $variation_id = $valid_pro_ids[$wow_post_id];
        $product_id = $variation_id;

        $subscription_period = 'month';
        $subscription_interval = 1;
        $subscription_length = 0;

        if (!empty($wo['pro_packages']) && is_array($wo['pro_packages'])) {
            foreach ($wo['pro_packages'] as $package) {
                if ((int) ($package['id'] ?? 0) === $wow_post_id) {
                    $subscription_period = sanitize_key($package['time'] ?? 'month') ?: 'month';
                    $subscription_interval = max(1, (int) ($package['count'] ?? 1));
                    $subscription_length = max(0, (int) ($package['time_count'] ?? 0));
                    break;
                }
            }
        }
        break;

    case 'WALLET':
        $product_id = (int) ($wo['config']['wow_wallet_topup_id'] ?? 0);
        if (!$product_id) {
            bzj_pgb_json_error('Wallet payment product is not configured.', 500);
        }
        $subscription_period = '';
        $subscription_interval = 0;
        $subscription_length = 0;
        break;

    case 'DONATE':
        $product_id = (int) ($wo['config']['wow_crowdfund_id'] ?? 0);
        if (!$product_id) {
            bzj_pgb_json_error('Crowdfund payment product is not configured.', 500);
        }
        $subscription_period = '';
        $subscription_interval = 0;
        $subscription_length = 0;
        break;

    case 'MARKET':
    case 'PRODUCT':
    case 'PURCHASE':
        $product_id = (int) ($_POST['woo_product_id'] ?? ($wo['config']['wow_market_id'] ?? 0));
        if (!$product_id) {
            bzj_pgb_json_error('WooCommerce product mapping is not configured.', 500);
        }
        $subscription_period = '';
        $subscription_interval = 0;
        $subscription_length = 0;
        break;
}

$wow_store_url = trim((string) ($wo['config']['wow_store_url'] ?? ''));

$billing = array(
    'first_name' => (string) ($wo['user']['first_name'] ?? $username),
    'last_name' => (string) ($wo['user']['last_name'] ?? ''),
    'phone' => (string) ($wo['user']['phone_number'] ?? ''),
    'address_1' => (string) ($wo['user']['address'] ?? ''),
    'city' => (string) ($wo['user']['city'] ?? ''),
    'state' => (string) ($wo['user']['state'] ?? ''),
    'postcode' => (string) ($wo['user']['zip'] ?? ''),
    'country' => strtoupper((string) ($wo['user']['country_id'] ?? 'GH')),
);

$payload = array(
    'wow_order_id' => $wow_order_id,
    'idempotency_key' => substr($idempotency_key, 0, 128),
    'wow_user_id' => $userid,
    'wp_user_id' => $wp_user_id,
    'customer_email' => $email,
    'customer_username' => $username,
    'transaction_kind' => $transaction_kind,
    'amount' => round($amount, 6),
    'product_price' => round($product_price, 6),
    'currency_code' => $currency,
    'product_name' => $product_name,
    'product_id' => $product_id,
    'variation_id' => $variation_id,
    'quantity' => $quantity,
    'product_owner_id' => $product_owner_id,
    'wow_post_id' => $wow_post_id,
    'address_id' => $address_id,
    'subscription_period' => $subscription_period,
    'subscription_interval' => $subscription_interval,
    'subscription_length' => $subscription_length,
    'return_path' => '',
    'billing' => $billing,
    'store_url' => $wow_store_url,
    'extra_meta' => array(
        'qdw_order_id' => isset($_POST['qdw_order_id']) ? (string) $_POST['qdw_order_id'] : '',
        'bz_rebate_credit_cents' => isset($_POST['bz_rebate_credit_cents']) ? (string) $_POST['bz_rebate_credit_cents'] : '',
        'bz_rebate_credit_amount' => isset($_POST['bz_rebate_credit_amount']) ? (string) $_POST['bz_rebate_credit_amount'] : '',
        'wow_username' => $username,
        '_bzj_pgb_origin' => 'streams',
    ),
);

try {
    require_once dirname(__DIR__, 3) . '/shared/payment-gateway/pgb-client-streams.php';

    if (!function_exists('pgb_streams_create_payment_intent')) {
        throw new RuntimeException('Streams Payment Gateway client is unavailable.');
    }

    $result = pgb_streams_create_payment_intent($payload);

    if (empty($result['url'])) {
        throw new RuntimeException('Payment handoff URL was not generated.');
    }

    echo json_encode(array(
        'status' => 200,
        'url' => $result['url'],
        'request_id' => $result['request_id'],
        'wow_order_id' => $result['wow_order_id'],
    ));
    exit;
} catch (Throwable $e) {
    error_log('[BZJ-PGB-STREAMS] ' . $e->getMessage());
    bzj_pgb_json_error('Unable to start the payment session. Please try again.', 500);
}
