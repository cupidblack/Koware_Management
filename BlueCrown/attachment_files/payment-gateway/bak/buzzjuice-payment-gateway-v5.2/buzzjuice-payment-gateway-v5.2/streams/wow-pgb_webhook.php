<?php
/**
 * Legacy webhook retired in PGB 5.2.
 * WooCommerce payment lifecycle is now handled inside WordPress.
 */
http_response_code(410);
header('Content-Type: application/json');
echo json_encode(array('status'=>'retired','message'=>'Buzzjuice Payment Gateway webhook retired. WooCommerce payment hooks now process this order.'));
exit;
