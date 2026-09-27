<?php
/**
 * Legacy compatibility shim.
 *
 * PGB 5.2 fulfillment is owned by the Buzzjuice MU bridge.
 * This file deliberately registers no WooCommerce fulfillment hooks.
 */
if(!defined('ABSPATH'))exit;
if(function_exists('error_log'))error_log('[BZJ-PGB] Legacy wow-pgb_sync.php loaded as compatibility shim; active fulfillment is MU PGB 5.2.');
