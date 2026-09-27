<?php
if (!defined('ABSPATH')) exit;
class BZJ_PGB_Jewel {
    private $logger,$ledger;
    public function __construct($logger,$ledger){$this->logger=$logger;$this->ledger=$ledger;}
    public function process($order){
        if($order->get_meta('_bzj_pgb_jewel_processed',true))return true;
        $url=getenv('BZJ_JEWEL_AFFILIATE_WEBHOOK_URL');
        if(!$url)$url=home_url('/streams/jewel-affiliate-webhook.php');
        $secret=getenv('BZJ_JEWEL_AFFILIATE_SECRET');
        $payload=array('woo_order_id'=>$order->get_id(),'wow_order_id'=>$order->get_meta('_wow_order_id',true),'wp_user_id'=>$order->get_user_id(),'wow_user_id'=>$order->get_meta('_wow_user_id',true),'amount'=>(float)$order->get_total(),'currency'=>$order->get_currency(),'status'=>$order->get_status(),'transaction_kind'=>$order->get_meta('_transaction_kind',true));
        $body=wp_json_encode($payload);$headers=array('Content-Type'=>'application/json','X-BZJ-PGB-Order'=>(string)$order->get_id());
        if($secret)$headers['X-BZJ-Jewel-Signature']=base64_encode(hash_hmac('sha256',$body,$secret,true));
        $r=wp_remote_post($url,array('timeout'=>15,'blocking'=>true,'headers'=>$headers,'body'=>$body));
        if(is_wp_error($r)){ $this->logger->error('Jewel integration HTTP failure',array('error'=>$r->get_error_message()),'pgb-int-jewel-affiliate.php');return false;}
        $code=(int)wp_remote_retrieve_response_code($r);if($code<200||$code>=300){$this->logger->error('Jewel integration rejected order',array('http_code'=>$code),'pgb-int-jewel-affiliate.php');return false;}
        $order->update_meta_data('_bzj_pgb_jewel_processed',current_time('mysql',true));$order->save();return true;
    }
}
