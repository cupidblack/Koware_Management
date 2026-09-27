<?php
if (!defined('ABSPATH')) exit;
class BZJ_PGB_Ledger {
    private $wpdb,$table,$logger;
    public function __construct($logger){
        global $wpdb;$this->wpdb=$wpdb;$this->table=$wpdb->prefix.'bzj_pgb_transactions';$this->logger=$logger;
        $this->ensure_schema();
    }
    public function table(){return $this->table;}
    public function ensure_schema(){
        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        $sql="CREATE TABLE IF NOT EXISTS {$this->table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            request_id VARCHAR(80) NOT NULL,
            wow_order_id VARCHAR(120) NOT NULL,
            woo_order_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            wp_user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            wow_user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            transaction_kind VARCHAR(32) NOT NULL,
            status VARCHAR(32) NOT NULL DEFAULT 'initiated',
            fulfillment_status VARCHAR(32) NOT NULL DEFAULT 'pending',
            amount DECIMAL(20,6) NOT NULL DEFAULT 0,
            currency CHAR(3) NOT NULL DEFAULT 'GHS',
            attempts INT UNSIGNED NOT NULL DEFAULT 0,
            last_error TEXT NULL,
            payload LONGTEXT NULL,
            result LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            paid_at DATETIME NULL,
            fulfilled_at DATETIME NULL,
            PRIMARY KEY (id),
            UNIQUE KEY request_id (request_id),
            UNIQUE KEY wow_order_id (wow_order_id),
            UNIQUE KEY woo_order_id (woo_order_id),
            KEY status (status),
            KEY fulfillment_status (fulfillment_status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $ok=$this->wpdb->query($sql);
        if($ok===false) $this->logger->error('Ledger schema creation failed',array('db_error'=>$this->wpdb->last_error),'class-pgb-ledger.php');
    }
    public function find_request($request){return $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM {$this->table} WHERE request_id=%s",$request),ARRAY_A);}
    public function find_by_woo($id){return $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM {$this->table} WHERE woo_order_id=%d",$id),ARRAY_A);}
    public function find_by_wow($id){return $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM {$this->table} WHERE wow_order_id=%s",$id),ARRAY_A);}
    public function create($d){
        $now=current_time('mysql',true);
        $d=array_merge(array('request_id'=>'','wow_order_id'=>'','woo_order_id'=>0,'wp_user_id'=>0,'wow_user_id'=>0,'transaction_kind'=>'UNKNOWN','status'=>'initiated','fulfillment_status'=>'pending','amount'=>0,'currency'=>'GHS','attempts'=>0,'last_error'=>null,'payload'=>null,'result'=>null,'created_at'=>$now,'updated_at'=>$now,'paid_at'=>null,'fulfilled_at'=>null),$d);
        $ok=$this->wpdb->insert($this->table,$d);
        if(!$ok) $this->logger->error('Ledger insert failed',array('db_error'=>$this->wpdb->last_error,'request_id'=>$d['request_id']),'class-pgb-ledger.php');
        return $ok?(int)$this->wpdb->insert_id:0;
    }
    public function update_by_woo($id,$d){
        $d['updated_at']=current_time('mysql',true);
        return $this->wpdb->update($this->table,$d,array('woo_order_id'=>(int)$id));
    }
    public function claim($id){
        $now=current_time('mysql',true);
        $sql=$this->wpdb->prepare("UPDATE {$this->table} SET fulfillment_status='processing', attempts=attempts+1, updated_at=%s WHERE woo_order_id=%d AND (fulfillment_status IN ('pending','failed') OR (fulfillment_status='processing' AND updated_at < (UTC_TIMESTAMP() - INTERVAL 15 MINUTE)))",$now,(int)$id);
        return $this->wpdb->query($sql)===1;
    }
}
