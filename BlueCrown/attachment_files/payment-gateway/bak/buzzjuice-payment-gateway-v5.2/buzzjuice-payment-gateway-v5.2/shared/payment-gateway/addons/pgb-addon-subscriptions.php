<?php
if (!defined('ABSPATH')) exit;
class BZJ_PGB_Subscriptions {
    private $logger,$ledger;
    public function __construct($logger,$ledger){$this->logger=$logger;$this->ledger=$ledger;}
    public function process($order){
        if(!function_exists('wcs_create_subscription'))return true;
        if($order->get_meta('_bzj_pgb_subscription_id',true))return true;
        $has=false;foreach($order->get_items() as $item){$p=$item->get_product();if($p&&function_exists('WC_Subscriptions_Product')&&WC_Subscriptions_Product::is_subscription($p)){$has=true;break;}}
        if(!$has && strtoupper((string)$order->get_meta('_transaction_kind',true))!=='PRO')return true;
        if($has===false && !class_exists('WC_Subscriptions'))return true;
        $period=sanitize_key((string)$order->get_meta('_subscription_period',true)?:'month');$interval=max(1,(int)$order->get_meta('_subscription_interval',true)?:1);
        $length=max(0,(int)$order->get_meta('_subscription_length',true));$start=current_time('mysql',true);
        $args=array('order_id'=>$order->get_id(),'customer_id'=>$order->get_customer_id(),'status'=>'active','billing_period'=>$period,'billing_interval'=>$interval,'start_date'=>$start);
        if($length>0)$args['end_date']=gmdate('Y-m-d H:i:s',strtotime("+{$length} {$period}",strtotime($start)));
        $sub=wcs_create_subscription($args);if(is_wp_error($sub)){ $this->logger->error('Subscription creation failed',array('woo_order_id'=>$order->get_id(),'error'=>$sub->get_error_message()),'pgb-addon-subscriptions.php');return false;}
        foreach($order->get_items() as $item){$p=$item->get_product();if($p)$sub->add_product($p,$item->get_quantity(),array('subtotal'=>$item->get_subtotal(),'total'=>$item->get_total()));}
        $sub->calculate_totals(false);$sub->update_dates(array('start'=>$start));$sub->save();
        $order->update_meta_data('_bzj_pgb_subscription_id',$sub->get_id());$order->save();
        $this->logger->info('Subscription created',array('woo_order_id'=>$order->get_id(),'subscription_id'=>$sub->get_id()),'pgb-addon-subscriptions.php');
        return true;
    }
}
