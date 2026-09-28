<?php
if (!defined('ABSPATH')) exit;
class BZJ_PGB_Manager {
    private $logger,$ledger;
    public function __construct($logger,$ledger){$this->logger=$logger;$this->ledger=$ledger;}
    public function register_hooks($streams,$affiliate,$subscriptions,$jewel){
        add_action('rest_api_init',array($this,'register_rest'));
        add_action('woocommerce_payment_complete',array($this,'payment_complete'),20);
        add_action('woocommerce_order_status_completed',array($this,'status_completed'),20);
    }
    public function register_rest(){
        register_rest_route('bzj-pgb/v1','/order',array('methods'=>'POST','callback'=>array($this,'rest_create'),'permission_callback'=>'__return_true'));
    }
    public function rest_create(WP_REST_Request $r){
        $body=$r->get_body();$ts=$r->get_header('X-BZJ-PGB-Timestamp');$rid=$r->get_header('X-BZJ-PGB-Request-ID');$sig=$r->get_header('X-BZJ-PGB-Signature');
        if(!BZJ_PGB_HTTP::verify($ts,$rid,$body,$sig)) return new WP_Error('pgb_auth','Invalid payment bridge signature.',array('status'=>401));
        $data=json_decode($body,true); if(!is_array($data)) return new WP_Error('pgb_json','Invalid JSON.',array('status'=>400));
        if(empty($rid)||empty($data['request_id'])||!hash_equals((string)$rid,(string)$data['request_id'])) return new WP_Error('pgb_request_id','Request ID mismatch.',array('status'=>400));
        $existing=$this->ledger->find_request($rid); if($existing && !empty($existing['woo_order_id'])){
            $o=wc_get_order($existing['woo_order_id']); return $this->response_for_order($o,$existing['wow_order_id']);
        }
        $v=$this->validate_request($data); if(is_wp_error($v)) return $v;
        $wow=(string)$data['wow_order_id'];
        $old=$this->ledger->find_by_wow($wow); if($old && !empty($old['woo_order_id'])){
            $o=wc_get_order($old['woo_order_id']); return $this->response_for_order($o,$wow);
        }
        $user=get_user_by('id',(int)$data['wp_user_id']);
        if(!$user) return new WP_Error('pgb_user','WordPress customer not found.',array('status'=>404));
        $data['amount']=(float)apply_filters('bzj_pgb_amount',(float)$data['amount'],strtoupper($data['currency']),strtoupper($data['currency']));
        $order=wc_create_order(array('customer_id'=>$user->ID));
        if(is_wp_error($order)) return $order;
        $order->set_currency(strtoupper($data['currency']));
        $product_id=(int)($data['product_id']??0);$variation_id=(int)($data['variation_id']??0);$qty=max(1,(int)($data['quantity']??1));
        $product=$variation_id?wc_get_product($variation_id):wc_get_product($product_id);
        if(!$product){$order->delete(true);return new WP_Error('pgb_product','WooCommerce product/variation not found.',array('status'=>404));}
        $line=$order->add_product($product,$qty,array('subtotal'=>(float)$data['unit_price']*$qty,'total'=>(float)$data['amount']));
        if(!$line){$order->delete(true);return new WP_Error('pgb_line','Could not add WooCommerce line item.',array('status'=>500));}
        $meta=array('wow_order_id'=>$wow,'wow_user_id'=>(int)$data['wow_user_id'],'wow_post_id'=>(int)($data['wow_post_id']??0),'transaction_kind'=>strtoupper($data['transaction_kind']),'pgb_request_id'=>$rid,'pgb_origin'=>'streams');
        foreach($meta as $k=>$v)$order->update_meta_data('_'.$k,$v);
        $order->update_meta_data('_bzj_pgb_payload',wp_json_encode($data));
        $order->calculate_totals(false);
        $order->set_total((float)$data['amount']);
        $order->set_status('pending');
        $order->save();
        $this->ledger->create(array('request_id'=>$rid,'wow_order_id'=>$wow,'woo_order_id'=>$order->get_id(),'wp_user_id'=>$user->ID,'wow_user_id'=>(int)$data['wow_user_id'],'transaction_kind'=>strtoupper($data['transaction_kind']),'amount'=>(float)$data['amount'],'currency'=>strtoupper($data['currency']),'payload'=>wp_json_encode($data)));
        $this->logger->info('WooCommerce order created',array('woo_order_id'=>$order->get_id(),'wow_order_id'=>$wow,'kind'=>$data['transaction_kind']),'class-pgb-manager.php');
        return $this->response_for_order($order,$wow);
    }
    private function response_for_order($order,$wow){
        if(!$order)return new WP_Error('pgb_order','WooCommerce order unavailable.',array('status'=>500));
        return array('success'=>true,'order_id'=>$order->get_id(),'wow_order_id'=>$wow,'payment_url'=>$order->get_checkout_payment_url(true));
    }
    private function validate_request($d){
        foreach(array('request_id','wow_order_id','wp_user_id','wow_user_id','transaction_kind','amount','currency','product_id','unit_price','quantity') as $k)if(!array_key_exists($k,$d))return new WP_Error('pgb_missing','Missing '.$k,array('status'=>400));
        if((int)$d['wp_user_id']<1||(int)$d['wow_user_id']<1||(float)$d['amount']<=0||(float)$d['unit_price']<=0)return new WP_Error('pgb_invalid','Invalid customer or amount.',array('status'=>400));
        if(!preg_match('/^[A-Z]{3}$/',strtoupper($d['currency'])))return new WP_Error('pgb_currency','Invalid currency.',array('status'=>400));
        if(!in_array(strtoupper($d['transaction_kind']),array('PRODUCT','PURCHASE','PRO','WALLET','DONATE','SUBSCRIPTION'),true))return new WP_Error('pgb_kind','Unsupported transaction type.',array('status'=>400));
        return true;
    }
    public function payment_complete($order_id){$this->fulfill((int)$order_id,'payment_complete');}
    public function status_completed($order_id){$this->fulfill((int)$order_id,'status_completed');}
    private function fulfill($order_id,$source){
        $order=wc_get_order($order_id);if(!$order)return;
        $wow=(string)$order->get_meta('_wow_order_id',true);if(!$wow)return;
        $row=$this->ledger->find_by_woo($order_id);if(!$row){$this->logger->error('Paid order has no PGB ledger row',array('woo_order_id'=>$order_id),'class-pgb-manager.php');return;}
        if($row['fulfillment_status']==='fulfilled' || $row['fulfillment_status']==='processing'){return;}
        if(!$order->is_paid()){return;}
        if(!$this->ledger->claim($order_id)) return;
        $this->ledger->update_by_woo($order_id,array('status'=>'paid','paid_at'=>current_time('mysql',true)));
        $this->logger->info('Starting fulfillment',array('woo_order_id'=>$order_id,'source'=>$source),'class-pgb-manager.php');
        $results=array();
        try{
            $results['streams']=bzj_pgb_get()->streams->fulfill($order);
            $results['affiliate_wp']=bzj_pgb_get()->affiliate->process($order);
            $results['subscriptions']=bzj_pgb_get()->subscriptions->process($order);
            $results['jewel']=bzj_pgb_get()->jewel->process($order);
            $failed=array();
            foreach($results as $k=>$v)if($v===false)$failed[]=$k;
            if($failed){$this->ledger->update_by_woo($order_id,array('status'=>'paid','fulfillment_status'=>'failed','last_error'=>implode(',',$failed),'result'=>wp_json_encode($results)));$this->logger->error('Fulfillment partially failed',array('woo_order_id'=>$order_id,'failed'=>$failed),'class-pgb-manager.php');return;}
            $this->ledger->update_by_woo($order_id,array('status'=>'fulfilled','fulfillment_status'=>'fulfilled','fulfilled_at'=>current_time('mysql',true),'result'=>wp_json_encode($results),'last_error'=>null));
            $order->update_meta_data('_bzj_pgb_fulfilled_at',current_time('mysql',true));$order->save();
            $this->logger->info('Fulfillment complete',array('woo_order_id'=>$order_id),'class-pgb-manager.php');
        }catch(Throwable $e){$this->ledger->update_by_woo($order_id,array('fulfillment_status'=>'failed','last_error'=>$e->getMessage()));$this->logger->critical('Fulfillment exception',array('woo_order_id'=>$order_id,'error'=>$e->getMessage()),'class-pgb-manager.php');}
    }
}
