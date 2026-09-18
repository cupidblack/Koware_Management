<?php
/**
 * Buzzjuice relationship synchronization outbox runner.
 *
 * Run from server cron every 1-5 minutes:
 * php /home/koware/public_html/buzzjuice.net/shared/bzj-sync-outbox-cron.php
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$client_file = $root . '/shared/bzj-connection-client.php';

if (!is_file($client_file)) {
    fwrite(STDERR, "Missing shared/bzj-connection-client.php\n");
    exit(1);
}

require_once $client_file;

$results = array();

foreach (array('streams','socials') as $origin) {
    try {
        $client = new BZJ_Connection_Client($origin);
        $count = $client->flush(50);
        $results[$origin] = $count;
    } catch (Throwable $e) {
        $results[$origin] = 'error: ' . $e->getMessage();
    }
}

$log_dir = $root . '/data/logs';
if (!is_dir($log_dir)) {
    @mkdir($log_dir, 0750, true);
}
@file_put_contents(
    $log_dir . '/bzj-sync-outbox-cron.log',
    json_encode(array('time'=>gmdate('c'),'results'=>$results), JSON_UNESCAPED_SLASHES) . PHP_EOL,
    FILE_APPEND | LOCK_EX
);

exit(0);
