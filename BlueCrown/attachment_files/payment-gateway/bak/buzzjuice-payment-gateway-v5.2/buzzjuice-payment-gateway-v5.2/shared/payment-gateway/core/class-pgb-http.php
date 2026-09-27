<?php
if (!defined('ABSPATH')) exit;
class BZJ_PGB_HTTP {
    public static function secret(){
        $s=getenv('BZJ_PGB_SECRET');
        return $s?: (defined('BZJ_PGB_SECRET')?BZJ_PGB_SECRET:'');
    }
    public static function sign($ts,$rid,$body){
        $s=self::secret(); return $s?base64_encode(hash_hmac('sha256',$ts."\n".$rid."\n".$body,$s,true)):'';
    }
    public static function verify($ts,$rid,$body,$sig){
        if(!$s=self::secret()) return false;
        if(!ctype_digit((string)$ts) || abs(time()-(int)$ts)>300) return false;
        return $sig && hash_equals(base64_encode(hash_hmac('sha256',$ts."\n".$rid."\n".$body,$s,true)),$sig);
    }
}
