<?php
if (!defined('ABSPATH')) {
    exit;
}

class PGB_Manager {
    private $logger;
    private $db;
    private $booted = false;

    public function __construct() {
        $this->logger = new PGB_Logger();
        $this->db = null;
    }

    public function logger() {
        return $this->logger;
    }

    public function boot() {
        if ($this->booted) {
            return;
        }

        if (!class_exists('WooCommerce')) {
            $this->logger->warning('WooCommerce is not loaded yet.', array(), __FILE__);
            return;
        }

        try {
            $this->db = new PGB_Streams_DB($this->logger);
        } catch (Throwable $e) {
            $this->logger->critical('Unable to initialize PGB database', array('error' => $e->getMessage()), __FILE__);
        }

        $this->booted = true;
    }

    private function db() {
        if (!$this->db) {
            $this->db = new PGB_Streams_DB($this->logger);
        }
        return $this->db;
    }

    public function handle_checkout_handoff() {
        nocache_headers();

        $request_id = sanitize_text_field(wp_unslash($_GET['request_id'] ?? ''));
        $token = sanitize_text_field(wp_unslash($_GET['token'] ?? ''));
        $timestamp = absint($_GET['ts'] ?? 0);
        $signature = sanitize_text_field(wp_unslash($_GET['sig'] ?? ''));

        if (!PGB_Config::verify($request_id, $token, $timestamp, $signature)) {
            $this->logger->warning('Rejected invalid or expired payment handoff', array('request_id' => $request_id), __FILE__);
            status_header(403);
            wp_die('Invalid or expired payment session.', 'Payment Session', array('response' => 403));
        }

        $row = $this->db()->get_by_handoff($request_id, $token);
        if (!$row) {
            $this->logger->warning('Payment handoff row not found', array('request_id' => $request_id), __FILE__);
            status_header(404);
            wp_die('Payment session not found.', 'Payment Session', array('response' => 404));
        }

        $payload = json_decode($row['payload_json'], true);
        if (!is_array($payload)) {
            $this->db()->set_status((int) $row['id'], 'failed');
            wp_die('Payment session data is invalid.', 'Payment Session', array('response' => 500));
        }

        $woo_order_id = !empty($row['woo_order_id']) ? (int) $row['woo_order_id'] : 0;

        if ($woo_order_id) {
            $order = wc_get_order($woo_order_id);
            if ($order) {
                if ($order->is_paid()) {
                    $this->handle_payment_complete($woo_order_id, (string) $order->get_transaction_id());
                    $success = $this->success_redirect_for_order($order);
                    if ($success) {
                        wp_safe_redirect($success);
                        exit;
                    }
                }

                $payment_url = $order->get_checkout_payment_url();
                wp_safe_redirect($payment_url);
                exit;
            }
        }

        $claimed = $this->db()->claim_for_processing((int) $row['id']);
        if (!$claimed) {
            $row = $this->db()->get_by_handoff($request_id, $token);
            if ($row && !empty($row['woo_order_id'])) {
                $order = wc_get_order((int) $row['woo_order_id']);
                if ($order) {
                    wp_safe_redirect($order->get_checkout_payment_url());
                    exit;
                }
            }

            wp_die('This payment session is already being prepared. Please refresh in a moment.', 'Payment Session', array('response' => 409));
        }

        try {
            $order = $this->create_native_order($payload);

            $this->db()->attach_woo_order((int) $row['id'], $order->get_id());

            $this->logger->info('Native WooCommerce order created', array(
                'woo_order_id' => $order->get_id(),
                'wow_order_id' => $row['wow_order_id'],
            ), __FILE__);

            wp_safe_redirect($order->get_checkout_payment_url());
            exit;
        } catch (Throwable $e) {
            $this->db()->set_status((int) $row['id'], 'failed');
            $this->logger->critical('Native order creation failed', array(
                'request_id' => $request_id,
                'error' => $e->getMessage(),
            ), __FILE__);

            status_header(500);
            wp_die('The order could not be prepared. Please return to Buzzjuice and try again.', 'Payment Session', array('response' => 500));
        }
    }

    private function create_native_order(array $p) {
        $required = array('wp_user_id','customer_email','amount','currency_code','product_name','quantity','transaction_kind');
        foreach ($required as $key) {
            if (!isset($p[$key]) || $p[$key] === '') {
                throw new InvalidArgumentException('Missing payment field: ' . $key);
            }
        }

        $wp_user_id = absint($p['wp_user_id']);
        $user = $wp_user_id ? get_user_by('id', $wp_user_id) : false;
        if (!$user) {
            throw new RuntimeException('WordPress customer does not exist.');
        }

        $order = wc_create_order(array('customer_id' => $wp_user_id));
        if (is_wp_error($order)) {
            throw new RuntimeException($order->get_error_message());
        }

        $order->set_currency(strtoupper(sanitize_text_field($p['currency_code'])));
        $order->set_customer_id($wp_user_id);

        $product_id = !empty($p['variation_id']) ? absint($p['variation_id']) : absint($p['product_id']);
        $product = $product_id ? wc_get_product($product_id) : false;

        if (!$product) {
            throw new RuntimeException('Mapped WooCommerce product/variation could not be found.');
        }

        $quantity = max(1, absint($p['quantity']));
        $unit_price = (float) $p['product_price'];
        $line_total = (float) $p['amount'];

        $item_id = $order->add_product($product, $quantity, array(
            'subtotal' => $unit_price * $quantity,
            'total' => $line_total,
        ));

        if (!$item_id) {
            throw new RuntimeException('Unable to add the mapped product to the WooCommerce order.');
        }

        $billing = array(
            'first_name' => sanitize_text_field($p['billing']['first_name'] ?? $user->first_name ?: $user->display_name),
            'last_name' => sanitize_text_field($p['billing']['last_name'] ?? $user->last_name ?: $user->display_name),
            'email' => sanitize_email($p['customer_email']),
            'phone' => sanitize_text_field($p['billing']['phone'] ?? ''),
            'address_1' => sanitize_text_field($p['billing']['address_1'] ?? ''),
            'city' => sanitize_text_field($p['billing']['city'] ?? ''),
            'state' => sanitize_text_field($p['billing']['state'] ?? ''),
            'postcode' => sanitize_text_field($p['billing']['postcode'] ?? ''),
            'country' => strtoupper(sanitize_text_field($p['billing']['country'] ?? 'GH')),
        );
        $order->set_address($billing, 'billing');

        $meta = array(
            '_bzj_pgb_order' => '1',
            'wow_order_id' => sanitize_text_field($p['wow_order_id']),
            'wow_user_id' => absint($p['wow_user_id']),
            'userid' => absint($p['wow_user_id']),
            'bz_wow_user_id' => absint($p['wow_user_id']),
            'wow_post_id' => absint($p['wow_post_id'] ?? 0),
            'qdw_membershipType' => absint($p['wow_post_id'] ?? 0),
            'qdw_transaction_kind' => sanitize_text_field($p['transaction_kind']),
            'product_owner_id' => absint($p['product_owner_id'] ?? 0),
            'address_id' => absint($p['address_id'] ?? 0),
            '_bzj_pgb_return_path' => sanitize_text_field($p['return_path'] ?? ''),
            '_bzj_pgb_transaction_kind' => sanitize_text_field($p['transaction_kind']),
            '_bzj_pgb_currency' => strtoupper(sanitize_text_field($p['currency_code'])),
        );

        if (!empty($p['subscription_period'])) {
            $meta['_subscription_period'] = sanitize_key($p['subscription_period']);
        }
        if (!empty($p['subscription_interval'])) {
            $meta['_subscription_interval'] = absint($p['subscription_interval']);
            $meta['_subscription_period_interval'] = absint($p['subscription_interval']);
        }
        if (isset($p['subscription_length'])) {
            $meta['_subscription_length'] = absint($p['subscription_length']);
        }

        if (!empty($p['extra_meta']) && is_array($p['extra_meta'])) {
            foreach ($p['extra_meta'] as $key => $value) {
                $safe_key = sanitize_key($key);
                if ($safe_key !== '') {
                    $meta[$safe_key] = is_scalar($value) ? sanitize_text_field((string) $value) : wp_json_encode($value);
                }
            }
        }

        foreach ($meta as $key => $value) {
            $order->update_meta_data($key, $value);
        }

        $order->calculate_totals(false);
        $order->update_status('pending', 'Buzzjuice Payment Gateway order prepared; awaiting payment.');
        $order->save();

        return $order;
    }

    public function handle_payment_complete($order_id, $transaction_id = '') {
        $order = wc_get_order($order_id);
        if (!$order || !$order->get_meta('_bzj_pgb_order')) {
            return;
        }

        $this->logger->info('Payment completion entered', array('woo_order_id' => $order_id), __FILE__);

        $row = $this->db()->get_by_woo_order($order_id);
        if ($row) {
            $this->db()->update_payment_state(
                $row['wow_order_id'],
                $order_id,
                'completed',
                $order->get_payment_method(),
                $order->get_total()
            );
        }

        $wow_ok = (bool) $order->get_meta('_bzj_pgb_wowonder_done');
        if (!$wow_ok) {
            $wow_ok = pgb_addon_wowonder_fulfill($order, $this->logger);
            if ($wow_ok) {
                $order->update_meta_data('_bzj_pgb_wowonder_done', gmdate('c'));
                $order->save();
            }
        }

        $sub_ok = true;
        if (PGB_Config::get('enable_subscriptions', true)) {
            $sub_ok = (bool) $order->get_meta('_bzj_pgb_subscription_done');
            if (!$sub_ok) {
                $sub_ok = pgb_addon_subscriptions_fulfill($order, $this->logger);
                if ($sub_ok) {
                    $order->update_meta_data('_bzj_pgb_subscription_done', gmdate('c'));
                    $order->save();
                }
            }
        }

        $aff_ok = true;
        if (PGB_Config::get('enable_affiliate_wp', true)) {
            $aff_ok = (bool) $order->get_meta('_bzj_pgb_affiliate_done');
            if (!$aff_ok) {
                $aff_ok = pgb_addon_affiliate_wp_fulfill($order, $this->logger);
                if ($aff_ok) {
                    $order->update_meta_data('_bzj_pgb_affiliate_done', gmdate('c'));
                    $order->save();
                }
            }
        }

        $jewel_ok = true;
        if (PGB_Config::get('enable_jewel_affiliate', true)) {
            $jewel_ok = (bool) $order->get_meta('_bzj_pgb_jewel_done');
            if (!$jewel_ok) {
                $jewel_ok = pgb_int_jewel_affiliate_fulfill($order, $this->logger);
                if ($jewel_ok) {
                    $order->update_meta_data('_bzj_pgb_jewel_done', gmdate('c'));
                    $order->save();
                }
            }
        }

        $all_ok = $wow_ok && $sub_ok && $aff_ok && $jewel_ok;

        if ($all_ok) {
            $order->update_meta_data('_bzj_pgb_fulfillment_status', 'complete');
            $order->save();
            if ($row) {
                $this->db()->set_status((int) $row['id'], 'paid');
            }
        } else {
            $order->update_meta_data('_bzj_pgb_fulfillment_status', 'partial');
            $order->save();

            if (function_exists('as_schedule_single_action')) {
                as_schedule_single_action(time() + (int) PGB_Config::get('retry_delay', 60), 'bzj_pgb_retry_fulfillment', array('order_id' => $order_id), 'buzzjuice-pgb');
            } else {
                wp_schedule_single_event(time() + (int) PGB_Config::get('retry_delay', 60), 'bzj_pgb_retry_fulfillment', array($order_id));
            }
        }
    }

    public function handle_paid_status_fallback($order_id) {
        $order = wc_get_order($order_id);
        if (!$order || !$order->get_meta('_bzj_pgb_order') || !$order->is_paid()) {
            return;
        }
        $this->handle_payment_complete($order_id, (string) $order->get_transaction_id());
    }

    public function success_redirect_for_order($order) {
        if (!$order || !$order->is_paid()) {
            return '';
        }

        $wowonder_url = rtrim((string) PGB_Config::get('wowonder_url', ''), '/');
        $buzzsocial_url = rtrim((string) PGB_Config::get('buzzsocial_url', ''), '/');

        if (!$wowonder_url) {
            return '';
        }

        $kind = (string) $order->get_meta('_bzj_pgb_transaction_kind');
        $wow_post_id = absint($order->get_meta('wow_post_id'));

        switch (strtoupper($kind)) {
            case 'DONATE':
                return $wow_post_id ? $wowonder_url . '/show_fund/' . rawurlencode((string) $wow_post_id) . '?nocache=' . time() : $wowonder_url;
            case 'PRO':
                return $buzzsocial_url && $order->get_meta('qdw_order_id')
                    ? $buzzsocial_url . '/ProSuccess?paymode=pro'
                    : $wowonder_url . '/upgraded';
            case 'WALLET':
                return $order->get_meta('qdw_order_id')
                    ? ($buzzsocial_url ? $buzzsocial_url . '/ProSuccess' : $wowonder_url . '/wallet/')
                    : $wowonder_url . '/wallet/?nocache=' . time();
            case 'MARKET':
                return $wowonder_url . '/purchased';
            case 'PRODUCT':
            case 'PURCHASE':
            case 'SALE':
            default:
                return $wowonder_url . '/purchased';
        }
    }
}

add_action('bzj_pgb_retry_fulfillment', function ($order_id) {
    try {
        bzj_pgb_engine()->handle_paid_status_fallback((int) $order_id);
    } catch (Throwable $e) {
        bzj_pgb_engine()->logger()->error('Scheduled fulfillment retry failed', array(
            'order_id' => $order_id,
            'error' => $e->getMessage(),
        ), __FILE__);
    }
}, 10, 1);
