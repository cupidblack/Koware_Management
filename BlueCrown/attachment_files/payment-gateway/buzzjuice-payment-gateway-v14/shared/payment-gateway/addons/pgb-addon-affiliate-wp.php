<?php
if (!defined('ABSPATH')) {
    exit;
}

function pgb_addon_affiliate_wp_fulfill($order, PGB_Logger $logger) {
    if (!function_exists('affwp_add_referral')) {
        $logger->warning('AffiliateWP public referral API unavailable; skipping affiliate processing', array('order_id' => $order->get_id()), __FILE__);
        return true;
    }

    $reference = 'woo_order_' . $order->get_id();

    global $wpdb;

    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT referral_id FROM {$wpdb->prefix}affiliate_wp_referrals WHERE reference=%s LIMIT 1",
        $reference
    ));

    if ($existing) {
        return true;
    }

    $affiliate_id = 0;

    if (function_exists('affwp_get_affiliate_id')) {
        $affiliate_id = absint(affwp_get_affiliate_id());
    }

    if (!$affiliate_id && function_exists('affiliate_wp')) {
        try {
            $tracking = affiliate_wp()->tracking;
            if (is_object($tracking) && method_exists($tracking, 'get_affiliate_id')) {
                $affiliate_id = absint($tracking->get_affiliate_id());
            }
        } catch (Throwable $e) {
            $logger->warning('AffiliateWP tracking context lookup failed', array('error' => $e->getMessage()), __FILE__);
        }
    }

    $customer_id = 0;
    $email = $order->get_billing_email();

    if (function_exists('affwp_get_customer')) {
        try {
            $customer = affwp_get_customer(array('email' => $email));
            if ($customer && !is_wp_error($customer)) {
                $customer_id = absint($customer->customer_id ?? 0);
            }
        } catch (Throwable $e) {
            $logger->warning('AffiliateWP customer lookup failed', array('error' => $e->getMessage()), __FILE__);
        }
    }

    if (!$customer_id) {
        $customer_id = absint($wpdb->get_var($wpdb->prepare(
            "SELECT customer_id FROM {$wpdb->prefix}affiliate_wp_customers WHERE (user_id=%d OR email=%s) ORDER BY customer_id DESC LIMIT 1",
            $order->get_customer_id(),
            $email
        )));
    }

    if (!$affiliate_id && $customer_id) {
        $affiliate_id = absint($wpdb->get_var($wpdb->prepare(
            "SELECT affiliate_id FROM {$wpdb->prefix}affiliate_wp_lifetime_customers WHERE affwp_customer_id=%d ORDER BY lifetime_customer_id DESC LIMIT 1",
            $customer_id
        )));
    }

    if (!$affiliate_id && $customer_id) {
        $affiliate_id = absint($wpdb->get_var($wpdb->prepare(
            "SELECT affiliate_id FROM {$wpdb->prefix}affiliate_wp_referrals WHERE customer_id=%d ORDER BY referral_id DESC LIMIT 1",
            $customer_id
        )));
    }

    if (!$affiliate_id) {
        $logger->info('No AffiliateWP affiliate could be resolved; no referral created', array(
            'order_id' => $order->get_id(),
            'customer_id' => $customer_id,
        ), __FILE__);
        return true;
    }

    if (function_exists('affwp_get_affiliate')) {
        $affiliate = affwp_get_affiliate($affiliate_id);
        if (!$affiliate) {
            $logger->warning('Resolved AffiliateWP ID is invalid', array('affiliate_id' => $affiliate_id), __FILE__);
            return true;
        }
    }

    $amount = pgb_affiliate_commission_amount($order, $affiliate_id);
    if ($amount <= 0) {
        $logger->info('Affiliate commission calculated as zero', array('order_id' => $order->get_id()), __FILE__);
        return true;
    }

    $args = array(
        'affiliate_id' => $affiliate_id,
        'amount' => $amount,
        'reference' => $reference,
        'description' => 'Buzzjuice WooCommerce order #' . $order->get_id(),
        'context' => 'buzzjuice_pgb',
        'status' => 'unpaid',
        'type' => 'sale',
        'visit_id' => function_exists('affwp_get_visit_id') ? absint(affwp_get_visit_id()) : 0,
        'customer_id' => $customer_id,
        'currency' => $order->get_currency(),
    );

    $referral_id = affwp_add_referral($args);

    if (!$referral_id || is_wp_error($referral_id)) {
        $logger->error('AffiliateWP referral creation failed', array(
            'order_id' => $order->get_id(),
            'affiliate_id' => $affiliate_id,
            'error' => is_wp_error($referral_id) ? $referral_id->get_error_message() : 'unknown',
        ), __FILE__);
        return false;
    }

    $verify = $wpdb->get_var($wpdb->prepare(
        "SELECT referral_id FROM {$wpdb->prefix}affiliate_wp_referrals WHERE referral_id=%d AND reference=%s LIMIT 1",
        absint($referral_id),
        $reference
    ));

    if (!$verify) {
        $logger->error('AffiliateWP referral was returned but could not be verified', array(
            'referral_id' => $referral_id,
            'order_id' => $order->get_id(),
        ), __FILE__);
        return false;
    }

    $order->update_meta_data('_bzj_pgb_affiliate_id', $affiliate_id);
    $order->update_meta_data('_bzj_pgb_affiliate_referral_id', absint($referral_id));
    $order->save();

    $logger->info('AffiliateWP referral created and verified', array(
        'order_id' => $order->get_id(),
        'affiliate_id' => $affiliate_id,
        'referral_id' => $referral_id,
        'amount' => $amount,
    ), __FILE__);

    return true;
}

function pgb_affiliate_commission_amount($order, $affiliate_id) {
    global $wpdb;

    $settings = function_exists('affiliate_wp') ? affiliate_wp()->settings->get_all() : array();
    $rate_type = $settings['referral_rate_type'] ?? 'percentage';
    $rate = isset($settings['referral_rate']) ? (float) $settings['referral_rate'] : 0.0;

    if (function_exists('affwp_get_affiliate')) {
        $affiliate = affwp_get_affiliate($affiliate_id);
        if ($affiliate) {
            $affiliate_rate = isset($affiliate->rate) ? (float) $affiliate->rate : null;
            $affiliate_rate_type = isset($affiliate->rate_type) ? $affiliate->rate_type : null;
            if ($affiliate_rate !== null && $affiliate_rate > 0) {
                $rate = $affiliate_rate;
            }
            if ($affiliate_rate_type) {
                $rate_type = $affiliate_rate_type;
            }
        }
    }

    $base = (float) $order->get_subtotal();
    $base -= abs((float) $order->get_discount_total());

    if ($base <= 0 || $rate <= 0) {
        return 0.0;
    }

    if ($rate_type === 'flat') {
        return round($rate, 2);
    }

    return round($base * ($rate / 100), 2);
}
