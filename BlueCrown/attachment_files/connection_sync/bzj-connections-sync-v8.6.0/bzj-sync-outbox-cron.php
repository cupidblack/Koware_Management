<?php
/** Optional WP-CLI/cron runner. Normally WordPress cron is enough. */
if (defined('ABSPATH')) { return; }
$root=dirname(__DIR__,2);$wp=$root.'/wp-load.php';if(!is_file($wp)){fwrite(STDERR,"wp-load.php not found\n");exit(1);}require_once $wp;
if(class_exists('BZJ_Connections_Sync')){BZJ_Connections_Sync::instance()->recover();}
