<?php
if (!defined('ABSPATH')) exit;

class BZJ_PGB_Bootstrap {
    private static $instance;
    public $logger,$ledger,$manager,$streams,$affiliate,$subscriptions,$jewel,$admin;

    public static function instance(){ return self::$instance ?: (self::$instance=new self); }

    public static function init() {
        if (!function_exists('wc_get_order')) return;
        self::instance()->boot();
    }

    private function boot() {
        if (!is_dir(BZJ_PGB_LOG_DIR)) wp_mkdir_p(BZJ_PGB_LOG_DIR);
        require_once BZJ_PGB_DIR.'core/class-pgb-logger.php';
        require_once BZJ_PGB_DIR.'core/class-pgb-ledger.php';
        require_once BZJ_PGB_DIR.'core/class-pgb-http.php';
        require_once BZJ_PGB_DIR.'core/class-pgb-manager.php';
        require_once BZJ_PGB_DIR.'integrations/class-pgb-streams.php';
        require_once BZJ_PGB_DIR.'addons/pgb-addon-affiliate-wp.php';
        require_once BZJ_PGB_DIR.'addons/pgb-addon-subscriptions.php';
        require_once BZJ_PGB_DIR.'addons/pgb-addon-currency.php';
        require_once BZJ_PGB_DIR.'integrations/pgb-int-jewel-affiliate.php';
        require_once BZJ_PGB_DIR.'admin/pgb-admin.php';

        $this->logger=new BZJ_PGB_Logger();
        $this->ledger=new BZJ_PGB_Ledger($this->logger);
        $this->manager=new BZJ_PGB_Manager($this->logger,$this->ledger);

        $this->streams=new BZJ_PGB_Streams($this->logger,$this->ledger);
        $this->affiliate=new BZJ_PGB_Affiliate($this->logger,$this->ledger);
        $this->subscriptions=new BZJ_PGB_Subscriptions($this->logger,$this->ledger);
        $this->jewel=new BZJ_PGB_Jewel($this->logger,$this->ledger);

        $this->manager->register_hooks($this->streams,$this->affiliate,$this->subscriptions,$this->jewel);
        BZJ_PGB_Admin::register($this->logger,$this->ledger);
        $this->logger->info('Bootstrap complete',array('version'=>BZJ_PGB_VERSION),'class-pgb-bootstrap.php');
    }
}
