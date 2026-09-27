<?php
if (!defined('ABSPATH')) exit;
class BZJ_PGB_Affiliate {
    private $logger,$ledger;
    public function __construct($logger,$ledger){$this->logger=$logger;$this->ledger=$ledger;}
    public function process($order){
        if(!class_exists('Affiliate_WP') || !function_exists('affwp_add_referral'))return true;
        if($order->get_meta('_bzj_pgb_affwp_referral_id',true))return true;
        global $wpdb;
        $customer_id=function_exists('affwp_get_customer_by')?0:0;
        if(function_exists('affwp_get_customer_by')){
            $c=affwp_get_customer_by('email',$order->get_billing_email());
            if($c)$customer_id=(int)$c->customer_id;
        }
        if(!$customer_id && function_exists('affwp_get_customer')) {
            $c=affwp_get_customer($order->get_user_id()); if($c)$customer_id=(int)$c->customer_id;
        }
        if(!$customer_id)return true;
        $affiliate_id=0;
        $table=$wpdb->prefix.'affiliate_wp_lifetime_customers';
        if($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s",$table))===$table){
            $affiliate_id=(int)$wpdb->get_var($wpdb->prepare("SELECT affiliate_id FROM {$table} WHERE affwp_customer_id=%d ORDER BY lifetime_customer_id DESC LIMIT 1",$customer_id));
        }
        if(!$affiliate_id){
            $cm=$wpdb->prefix.'affiliate_wp_customermeta';
            if($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s",$cm))===$cm){
                foreach(array('affiliate_id','referring_affiliate_id') as $key){$affiliate_id=(int)$wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$cm} WHERE affwp_customer_id=%d AND meta_key=%s LIMIT 1",$customer_id,$key));if($affiliate_id)break;}
            }
        }
        if(!$affiliate_id)return true;
        $existing=(int)$wpdb->get_var($wpdb->prepare("SELECT referral_id FROM {$wpdb->prefix}affiliate_wp_referrals WHERE reference=%s AND context=%s LIMIT 1",(string)$order->get_id(),'woocommerce'));
        if($existing){$order->update_meta_data('_bzj_pgb_affwp_referral_id',$existing);$order->save();return true;}
        $rate=function_exists('affwp_get_affiliate_rate')?(float)affwp_get_affiliate_rate($affiliate_id):0;
        $rate_type=function_exists('affwp_get_affiliate_rate_type')?affwp_get_affiliate_rate_type($affiliate_id):'percentage';
        $base=(float)$order->get_total();$commission=$rate_type==='percentage'?$base*$rate/100:$rate;
        if($commission<=0)return true;
        $rid=affwp_add_referral(array('affiliate_id'=>$affiliate_id,'amount'=>$commission,'reference'=>(string)$order->get_id(),'description'=>'Buzzjuice WooCommerce Order #'.$order->get_id(),'status'=>'unpaid','context'=>'woocommerce','customer_id'=>$customer_id,'products'=>wp_json_encode(array())));
        if(!$rid){$this->logger->error('AffiliateWP referral creation failed',array('woo_order_id'=>$order->get_id(),'affiliate_id'=>$affiliate_id),'pgb-addon-affiliate-wp.php');return false;}
        $order->update_meta_data('_bzj_pgb_affwp_referral_id',(int)$rid);$order->save();
        $this->logger->info('AffiliateWP referral created',array('woo_order_id'=>$order->get_id(),'referral_id'=>(int)$rid,'affiliate_id'=>$affiliate_id,'amount'=>$commission),'pgb-addon-affiliate-wp.php');
        return true;
    }
}
