<?php
if (!defined('ABSPATH')) {
    exit;
}

function pgb_addon_subscriptions_fulfill($order, PGB_Logger $logger) {
    $kind = strtoupper((string) $order->get_meta('_bzj_pgb_transaction_kind'));
    if ($kind !== 'PRO') {
        return true;
    }

    if (!function_exists('wcs_create_subscription')) {
        $logger->error('WooCommerce Subscriptions is not available for PRO order', array('order_id' => $order->get_id()), __FILE__);
        return false;
    }

    if (function_exists('wcs_get_subscriptions_for_order')) {
        $existing = wcs_get_subscriptions_for_order($order->get_id(), array('order_type' => 'parent'));
        if (!empty($existing)) {
            return true;
        }
    }

    $period = sanitize_key($order->get_meta('_subscription_period') ?: 'month');
    $interval = max(1, absint($order->get_meta('_subscription_interval') ?: 1));
    $length = absint($order->get_meta('_subscription_length') ?: 0);

    $args = array(
        'parent_id' => $order->get_id(),
        'customer_id' => $order->get_customer_id(),
        'billing_period' => $period,
        'billing_interval' => $interval,
        'start_date' => gmdate('Y-m-d H:i:s'),
    );

    $subscription = wcs_create_subscription($args);
    if (is_wp_error($subscription)) {
        $logger->error('Subscription creation failed', array('order_id' => $order->get_id(), 'error' => $subscription->get_error_message()), __FILE__);
        return false;
    }

    if (!$subscription) {
        $logger->error('Subscription creation returned no object', array('order_id' => $order->get_id()), __FILE__);
        return false;
    }

    foreach ($order->get_items('line_item') as $item) {
        $product = $item->get_product();
        if (!$product) {
            continue;
        }

        $quantity = max(1, (int) $item->get_quantity());
        $subscription->add_product($product, $quantity, array(
            'subtotal' => (float) $item->get_subtotal(),
            'total' => (float) $item->get_total(),
        ));
    }

    $subscription->set_currency($order->get_currency());
    $subscription->set_parent_id($order->get_id());

    if ($length > 0) {
        $end = strtotime('+' . $length . ' ' . $period);
        if ($end) {
            $subscription->update_dates(array('end' => gmdate('Y-m-d H:i:s', $end)));
        }
    }

    $subscription->calculate_totals(false);
    $subscription->save();

    try {
        $subscription->update_status('active', 'Activated by Buzzjuice Payment Gateway after successful parent payment.');
    } catch (Throwable $e) {
        $logger->error('Subscription activation failed', array('subscription_id' => $subscription->get_id(), 'error' => $e->getMessage()), __FILE__);
        return false;
    }

    $order->update_meta_data('_bzj_pgb_subscription_id', $subscription->get_id());
    $order->save();

    $logger->info('Subscription created and activated', array(
        'order_id' => $order->get_id(),
        'subscription_id' => $subscription->get_id(),
        'billing_period' => $period,
        'billing_interval' => $interval,
    ), __FILE__);

    return true;
}
