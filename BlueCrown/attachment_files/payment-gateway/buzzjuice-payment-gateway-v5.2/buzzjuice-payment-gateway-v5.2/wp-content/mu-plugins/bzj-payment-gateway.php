<?php
/**
 * Plugin Name: Buzzjuice Payment Gateway Bridge
 * Description: WooCommerce-authoritative payment bridge for Buzzjuice Streams.
 * Version: 5.2.0
 */
if (!defined('ABSPATH')) { exit; }

define('BZJ_PGB_VERSION','5.2.0');
define('BZJ_PGB_DIR', ABSPATH . 'shared/payment-gateway/');
define('BZJ_PGB_LOG_DIR', BZJ_PGB_DIR . 'log/');

require_once BZJ_PGB_DIR . 'core/class-pgb-bootstrap.php';

add_action('plugins_loaded', array('BZJ_PGB_Bootstrap','init'), 30);

function bzj_pgb_get() {
    return class_exists('BZJ_PGB_Bootstrap') ? BZJ_PGB_Bootstrap::instance() : null;
}
