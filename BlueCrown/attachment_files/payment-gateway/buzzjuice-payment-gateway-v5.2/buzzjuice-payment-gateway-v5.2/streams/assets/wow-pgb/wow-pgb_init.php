<?php
require_once dirname(__DIR__,2).'/config.php';
require_once dirname(__DIR__,2).'/init.php';
require_once dirname(__DIR__,3).'/shared/payment-gateway/pgb-client-streams.php';
pgb_streams_client_handle();
