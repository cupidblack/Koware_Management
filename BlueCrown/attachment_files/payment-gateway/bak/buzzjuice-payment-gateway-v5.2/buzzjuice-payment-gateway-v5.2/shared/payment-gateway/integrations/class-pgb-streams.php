<?php
if (!defined('ABSPATH')) exit;
class BZJ_PGB_Streams {
    private $logger,$ledger,$db;
    public function __construct($logger,$ledger){$this->logger=$logger;$this->ledger=$ledger;}
    private function db(){
        if($this->db)return $this->db;
        require_once ABSPATH.'shared/db_helpers.php';
        if(function_exists('get_wowonder_db')){$this->db=get_wowonder_db();return $this->db;}
        return false;
    }
    private function table($name){return '`'.$name.'`';}
    private function has_column($table,$column){
        $db=$this->db();if(!$db)return false;
        $t=$db->real_escape_string($table);$c=$db->real_escape_string($column);
        $r=$db->query("SHOW COLUMNS FROM `{$t}` LIKE '{$c}'");return $r&&$r->num_rows>0;
    }
    private function exec($sql,$types=array(),$args=array()){
        $db=$this->db();if(!$db)return false;
        $s=$db->prepare($sql);if(!$s){$this->logger->error('Streams SQL prepare failed',array('error'=>$db->error),'class-pgb-streams.php');return false;}
        if($types)$s->bind_param($types,...$args);
        $ok=$s->execute();if(!$ok)$this->logger->error('Streams SQL failed',array('error'=>$s->error),'class-pgb-streams.php');$s->close();return $ok;
    }
    private function ensure_ledger(){
        $db=$this->db();if(!$db)return false;
        $sql="CREATE TABLE IF NOT EXISTS `Wo_PGB_Fulfillment`(
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `woo_order_id` BIGINT UNSIGNED NOT NULL,
            `wow_order_id` VARCHAR(120) NOT NULL,
            `stage` VARCHAR(32) NOT NULL DEFAULT 'started',
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            PRIMARY KEY(`id`), UNIQUE KEY `woo_order_id`(`woo_order_id`), UNIQUE KEY `wow_order_id`(`wow_order_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        return (bool)$db->query($sql);
    }
    private function begin($order){
        $this->ensure_ledger();$db=$this->db();if(!$db)return false;
        $woo=(int)$order->get_id();$wow=(string)$order->get_meta('_wow_order_id',true);$now=gmdate('Y-m-d H:i:s');
        $q=$db->prepare("SELECT stage FROM `Wo_PGB_Fulfillment` WHERE woo_order_id=? LIMIT 1");$q->bind_param('i',$woo);$q->execute();$r=$q->get_result()->fetch_assoc();$q->close();
        if($r && $r['stage']==='completed')return false;
        if(!$r){$q=$db->prepare("INSERT INTO `Wo_PGB_Fulfillment`(woo_order_id,wow_order_id,stage,created_at,updated_at) VALUES(?,?,'started',?,?)");$q->bind_param('isss',$woo,$wow,$now,$now);$q->execute();$q->close();}
        return true;
    }
    private function stage($order,$stage){$db=$this->db();if(!$db)return;$now=gmdate('Y-m-d H:i:s');$q=$db->prepare("UPDATE `Wo_PGB_Fulfillment` SET stage=?,updated_at=? WHERE woo_order_id=?");$q->bind_param('ssi',$stage,$now,$order->get_id());$q->execute();$q->close();}
    public function fulfill($order){
        if(!$this->begin($order))return true;
        $kind=strtoupper((string)$order->get_meta('_transaction_kind',true));$wow=(string)$order->get_meta('_wow_order_id',true);$wow_user=(int)$order->get_meta('_wow_user_id',true);
        if(!$wow||!$wow_user){$this->logger->error('Streams fulfillment missing identity',array('woo_order_id'=>$order->get_id()),'class-pgb-streams.php');return false;}
        $db=$this->db();if(!$db)return false;
        $this->update_payment_transaction($order,$wow);
        $this->stage($order,'payment_recorded');
        switch($kind){
            case 'PRODUCT':case 'PURCHASE':$ok=$this->fulfill_product($order,$wow_user,$wow);break;
            case 'PRO':$ok=$this->fulfill_pro($order,$wow_user);break;
            case 'WALLET':$ok=$this->fulfill_wallet($order,$wow_user);break;
            case 'DONATE':$ok=$this->fulfill_donate($order,$wow_user);break;
            default:$ok=true;
        }
        if(!$ok)return false;
        $this->stage($order,'completed');return true;
    }
    private function update_payment_transaction($order,$wow){
        $db=$this->db();$id=$order->get_id();$amount=(float)$order->get_total();$status='completed';$method=(string)$order->get_payment_method();$currency=$order->get_currency();
        $sets=array('payment_status'=>'?','woo_order_id'=>'?','amount'=>'?');$vals=array($status,$id,$amount);$types='sis';
        if($this->has_column('Wo_Payment_Transactions','payment_method')){$sets['payment_method']='?';$vals[]=$method;$types.='s';}
        if($this->has_column('Wo_Payment_Transactions','currency_code')){$sets['currency_code']='?';$vals[]=$currency;$types.='s';}
        if($this->has_column('Wo_Payment_Transactions','transaction_dt')){$sets['transaction_dt']='NOW()';}
        $sql="UPDATE `Wo_Payment_Transactions` SET ".implode(',',array_map(fn($k,$v)=>"`$k`=$v",array_keys($sets),$sets))." WHERE `order_id`=?";
        $vals[]=$wow;$types.='s';$this->exec($sql,$types,$vals);
    }
    private function fulfill_product($order,$uid,$wow){
        $db=$this->db();$item=current($order->get_items());if(!$item)return false;$owner=(int)$order->get_meta('_product_owner_id',true);$post=(int)$order->get_meta('_wow_post_id',true);$qty=(int)$item->get_quantity();$amount=(float)$order->get_total();$price=(float)$item->get_total();$data=wp_json_encode(array('name'=>$item->get_name(),'product_id'=>$post,'units'=>$qty,'currency'=>$order->get_currency(),'woo_order_id'=>$order->get_id()));
        $exists=$db->prepare("SELECT 1 FROM `Wo_Purchases` WHERE order_hash_id=? LIMIT 1");$exists->bind_param('s',$wow);$exists->execute();$already=$exists->get_result()->num_rows>0;$exists->close();
        if(!$already){
            $sql="INSERT INTO `Wo_Purchases`(user_id,order_hash_id,owner_id,data,final_price,commission,price,timestamp,time) VALUES(?,?,?,?,?,?,?, ?, ?)";
            $now=time();$commission=0.0;$this->exec($sql,'isssddddd',array($uid,$wow,(string)$owner,$data,$amount,$commission,$price,$now,$now));
        }
        if($this->has_column('Wo_UserOrders','hash_id')){
            $q=$db->prepare("SELECT 1 FROM `Wo_UserOrders` WHERE hash_id=? LIMIT 1");$q->bind_param('s',$wow);$q->execute();$u=$q->get_result()->num_rows>0;$q->close();
            if(!$u){$address=(int)$order->get_meta('_address_id',true);$this->exec("INSERT INTO `Wo_UserOrders`(hash_id,user_id,product_owner_id,wow_post_id,address_id,price,commission,timestamp,time) VALUES(?,?,?,?,?,?,?,?,?)",'siiiidddd',array($wow,$uid,$owner,$post,$address,$amount,0.0,time(),time()));}
        }
        if($owner>0){$this->exec("INSERT INTO `Wo_Notifications`(notifier_id,recipient_id,type,url,time) VALUES(?,?,?,?,?)",'iissi',array($uid,$owner,'new_orders','index.php?link1=orders',time()));}
        return true;
    }
    private function fulfill_pro($order,$uid){
        $db=$this->db();$type=(int)$order->get_meta('_wow_post_id',true);if(!in_array($type,array(1,2,3,4),true))return false;$time=time();
        if(!$this->has_column('Wo_Users','pro_time')||!$this->has_column('Wo_Users','is_pro')||!$this->has_column('Wo_Users','pro_type'))return false;
        return $this->exec("UPDATE `Wo_Users` SET pro_time=?,is_pro='1',pro_type=? WHERE user_id=?",'iii',array($time,$type,$uid));
    }
    private function fulfill_wallet($order,$uid){
        $db=$this->db();$amount=(float)$order->get_total();
        foreach(array('wallet','balance') as $col)if($this->has_column('Wo_Users',$col)){return $this->exec("UPDATE `Wo_Users` SET `{$col}`=`{$col}`+? WHERE user_id=?",'di',array($amount,$uid));}
        $this->logger->error('No wallet/balance column found in Wo_Users',array(),'class-pgb-streams.php');return false;
    }
    private function fulfill_donate($order,$uid){
        $db=$this->db();$fund=(int)$order->get_meta('_wow_post_id',true);if($fund<1)return false;$amount=(float)$order->get_total();return $this->exec("INSERT INTO `Wo_Funding_Raise`(funding_id,user_id,amount,time) VALUES(?,?,?,?)",'iidi',array($fund,$uid,$amount,time()));
    }
}
