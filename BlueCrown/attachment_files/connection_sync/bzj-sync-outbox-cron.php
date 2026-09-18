<?php
/**
 * BZJ external sync outbox retry runner.
 * Cron: every 5 minutes /usr/bin/php /FULL/PATH/shared/bzj-sync-outbox-cron.php >/dev/null 2>&1
 */
require_once __DIR__ . '/bzj-relationship-sync-client.php';
$streams = bzj_rel_sync_retry_outbox('streams', 50);
$socials = bzj_rel_sync_retry_outbox('socials', 50);
bzj_rel_sync_log('streams', 'Outbox retry completed', ['sent' => $streams]);
bzj_rel_sync_log('socials', 'Outbox retry completed', ['sent' => $socials]);
