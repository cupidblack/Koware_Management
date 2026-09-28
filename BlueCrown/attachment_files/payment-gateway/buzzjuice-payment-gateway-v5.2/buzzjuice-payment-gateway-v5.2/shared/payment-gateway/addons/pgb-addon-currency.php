<?php
if (!defined('ABSPATH')) exit;
/**
 * Currency compatibility layer.
 *
 * The bridge accepts the currency/amount calculated by Streams. If WOOCS exposes
 * its public exchange helper, it is available through the `bzj_pgb_amount` filter.
 * No guessed exchange rate is ever applied.
 */
add_filter('bzj_pgb_amount',function($amount,$from,$to){
    if(strtoupper($from)===strtoupper($to))return $amount;
    if(function_exists('woocs_exchange_value')){
        try{return (float)woocs_exchange_value((float)$amount,$from,$to);}catch(Throwable $e){return $amount;}
    }
    return $amount;
},10,3);
