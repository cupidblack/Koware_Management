<?php
if (!defined('ABSPATH')) exit;
function bzj_pgb_config($key=null,$default=null){
    $cfg=array(
        'version'=>BZJ_PGB_VERSION,
        'endpoint'=>rest_url('bzj-pgb/v1/order'),
        'logging_enabled'=>(bool)get_option('bzj_pgb_logging_enabled',true),
        'log_level'=>get_option('bzj_pgb_log_level','info'),
        'streams_log_enabled'=>(bool)(int)(getenv('BZJ_PGB_STREAMS_LOGGING')?:0)
    );
    return $key===null?$cfg:($cfg[$key]??$default);
}
