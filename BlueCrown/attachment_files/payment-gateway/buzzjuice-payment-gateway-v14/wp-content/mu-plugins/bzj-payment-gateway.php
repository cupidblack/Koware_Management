<?php
/**
 * Plugin Name: Buzzjuice Payment Gateway Engine
 * Description: Database-backed, signed browser handoff and native WooCommerce payment bridge for Buzzjuice Streams.
 * Version: 14.0.0
 * Author: Buzzjuice
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BZJ_PGB_VERSION', '14.0.0');
define('BZJ_PGB_ROOT', dirname(__DIR__, 2) . '/shared/payment-gateway');

$pgb_files = array(
    'core/class-pgb-logger.php',
    'core/class-pgb-config.php',
    'core/class-pgb-streams-db.php',
    'core/class-pgb-manager.php',
    'addons/pgb-addon-wowonder.php',
    'addons/pgb-addon-subscriptions.php',
    'addons/pgb-addon-affiliate-wp.php',
    'integrations/pgb-int-jewel-affiliate.php',
);

foreach ($pgb_files as $pgb_file) {
    $pgb_path = BZJ_PGB_ROOT . '/' . $pgb_file;
    if (is_readable($pgb_path)) {
        require_once $pgb_path;
    } else {
        error_log('[BZJ-PGB] Missing bridge file: ' . $pgb_path);
    }
}

if (!function_exists('bzj_pgb_engine')) {
    function bzj_pgb_engine() {
        static $engine = null;
        if ($engine === null) {
            $engine = new PGB_Manager();
        }
        return $engine;
    }
}

add_action('plugins_loaded', function () {
    try {
        bzj_pgb_engine()->boot();
    } catch (Throwable $e) {
        error_log('[BZJ-PGB] Boot exception: ' . $e->getMessage());
    }
}, 1);

add_action('template_redirect', function () {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $target = parse_url(home_url('/pgb-checkout/'), PHP_URL_PATH);

    if (rtrim((string) $path, '/') !== rtrim((string) $target, '/')) {
        return;
    }

    try {
        bzj_pgb_engine()->handle_checkout_handoff();
    } catch (Throwable $e) {
        bzj_pgb_engine()->logger()->critical(
            'Checkout handoff fatal exception',
            array('exception' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()),
            __FILE__
        );
        status_header(500);
        wp_die(
            esc_html__('The payment session could not be opened. Please return to Buzzjuice and try again.', 'buzzjuice-pgb'),
            esc_html__('Payment session error', 'buzzjuice-pgb'),
            array('response' => 500)
        );
    }
}, 1);

add_action('woocommerce_payment_complete', function ($order_id, $transaction_id = '') {
    try {
        bzj_pgb_engine()->handle_payment_complete((int) $order_id, (string) $transaction_id);
    } catch (Throwable $e) {
        bzj_pgb_engine()->logger()->critical(
            'Payment completion exception',
            array('order_id' => $order_id, 'exception' => $e->getMessage()),
            __FILE__
        );
    }
}, 20, 2);

add_action('woocommerce_order_status_processing', function ($order_id) {
    try {
        bzj_pgb_engine()->handle_paid_status_fallback((int) $order_id);
    } catch (Throwable $e) {
        bzj_pgb_engine()->logger()->critical(
            'Processing-status exception',
            array('order_id' => $order_id, 'exception' => $e->getMessage()),
            __FILE__
        );
    }
}, 20);

add_action('woocommerce_order_status_completed', function ($order_id) {
    try {
        bzj_pgb_engine()->handle_paid_status_fallback((int) $order_id);
    } catch (Throwable $e) {
        bzj_pgb_engine()->logger()->critical(
            'Completed-status exception',
            array('order_id' => $order_id, 'exception' => $e->getMessage()),
            __FILE__
        );
    }
}, 20);

add_action('template_redirect', function () {
    if (!function_exists('is_wc_endpoint_url') || !is_wc_endpoint_url('order-received')) {
        return;
    }

    $order_id = absint(get_query_var('order-received'));
    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);
    if (!$order || !$order->is_paid()) {
        return;
    }

    if (!$order->get_meta('_bzj_pgb_order')) {
        return;
    }

    try {
        bzj_pgb_engine()->handle_paid_status_fallback($order_id);

        $url = bzj_pgb_engine()->success_redirect_for_order($order);
        if ($url) {
            wp_safe_redirect($url);
            exit;
        }
    } catch (Throwable $e) {
        bzj_pgb_engine()->logger()->error(
            'Post-payment redirect exception',
            array('order_id' => $order_id, 'exception' => $e->getMessage()),
            __FILE__
        );
    }
}, 5);

/**
 * Optional Buzzjuice admin page.
 * It attaches to an existing "Buzzjuice" menu if one exists; otherwise it creates it.
 */
add_action('admin_menu', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    global $menu;
    $parent_slug = 'buzzjuice';
    $has_parent = false;

    foreach ((array) $menu as $entry) {
        if (isset($entry[2]) && $entry[2] === $parent_slug) {
            $has_parent = true;
            break;
        }
    }

    if (!$has_parent) {
        add_menu_page(
            'Buzzjuice',
            'Buzzjuice',
            'manage_options',
            $parent_slug,
            function () {
                echo '<div class="wrap"><h1>Buzzjuice</h1><p>Use the Payment Gateways submenu to configure the payment bridge.</p></div>';
            },
            'dashicons-cart',
            58
        );
    }

    add_submenu_page(
        $parent_slug,
        'Payment Gateways',
        'Payment Gateways',
        'manage_options',
        'bzj-payment-gateways',
        'bzj_pgb_render_admin_page'
    );
}, 99);

add_action('admin_init', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['bzj_pgb_save']) && check_admin_referer('bzj_pgb_save_settings')) {
        $values = array(
            'logging_enabled' => !empty($_POST['logging_enabled']),
            'log_level' => sanitize_key($_POST['log_level'] ?? 'info'),
            'wowonder_url' => esc_url_raw($_POST['wowonder_url'] ?? ''),
            'buzzsocial_url' => esc_url_raw($_POST['buzzsocial_url'] ?? ''),
            'enable_subscriptions' => !empty($_POST['enable_subscriptions']),
            'enable_affiliate_wp' => !empty($_POST['enable_affiliate_wp']),
            'enable_jewel_affiliate' => !empty($_POST['enable_jewel_affiliate']),
            'handoff_path' => sanitize_text_field($_POST['handoff_path'] ?? '/pgb-checkout/'),
        );

        foreach ($values as $key => $value) {
            update_option('bzj_pgb_' . $key, $value, false);
        }

        add_settings_error('bzj_pgb', 'saved', 'Payment Gateway settings saved.', 'updated');
    }
});

function bzj_pgb_render_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    settings_errors('bzj_pgb');

    $defaults = PGB_Config::defaults();
    $get = function ($key) use ($defaults) {
        return get_option('bzj_pgb_' . $key, $defaults[$key] ?? '');
    };

    $secret_configured = (bool) getenv('BZJ_PGB_HANDOFF_SECRET');
    ?>
    <div class="wrap">
        <h1>Buzzjuice → Payment Gateways</h1>
        <p>
            This bridge no longer requires Streams to call the WooCommerce REST API.
            Streams stores a signed pending payment intent, then the browser hands the intent
            to WordPress, where a native <code>WC_Order</code> is created.
        </p>

        <form method="post">
            <?php wp_nonce_field('bzj_pgb_save_settings'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Logging</th>
                    <td><label><input type="checkbox" name="logging_enabled" value="1" <?php checked($get('logging_enabled'), true); ?>> Enable bridge logging</label></td>
                </tr>
                <tr>
                    <th scope="row">Log level</th>
                    <td>
                        <select name="log_level">
                            <?php foreach (array('debug','info','warning','error','critical','off') as $level) : ?>
                                <option value="<?php echo esc_attr($level); ?>" <?php selected($get('log_level'), $level); ?>><?php echo esc_html(ucfirst($level)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">WoWonder URL</th>
                    <td><input type="url" class="regular-text" name="wowonder_url" value="<?php echo esc_attr($get('wowonder_url')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">BuzzSocial URL</th>
                    <td><input type="url" class="regular-text" name="buzzsocial_url" value="<?php echo esc_attr($get('buzzsocial_url')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Handoff path</th>
                    <td><input type="text" class="regular-text code" name="handoff_path" value="<?php echo esc_attr($get('handoff_path')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Subscriptions</th>
                    <td><label><input type="checkbox" name="enable_subscriptions" value="1" <?php checked($get('enable_subscriptions'), true); ?>> Enable native WooCommerce Subscriptions processing</label></td>
                </tr>
                <tr>
                    <th scope="row">AffiliateWP</th>
                    <td><label><input type="checkbox" name="enable_affiliate_wp" value="1" <?php checked($get('enable_affiliate_wp'), true); ?>> Enable AffiliateWP processing</label></td>
                </tr>
                <tr>
                    <th scope="row">Jewel Affiliate</th>
                    <td><label><input type="checkbox" name="enable_jewel_affiliate" value="1" <?php checked($get('enable_jewel_affiliate'), true); ?>> Enable Jewel Affiliate integration</label></td>
                </tr>
                <tr>
                    <th scope="row">Handoff secret</th>
                    <td>
                        <strong><?php echo $secret_configured ? 'Configured' : 'Missing'; ?></strong>
                        <p class="description">Set <code>BZJ_PGB_HANDOFF_SECRET</code> in the server environment/.env shared by Streams and WordPress. The secret is never displayed here.</p>
                    </td>
                </tr>
            </table>
            <p><button type="submit" class="button button-primary" name="bzj_pgb_save" value="1">Save Payment Gateway Settings</button></p>
        </form>
    </div>
    <?php
}
