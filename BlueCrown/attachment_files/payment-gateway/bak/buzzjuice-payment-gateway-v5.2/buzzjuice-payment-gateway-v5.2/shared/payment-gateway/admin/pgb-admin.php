<?php
if (!defined('ABSPATH')) exit;
class BZJ_PGB_Admin {
    public static function register($logger,$ledger){
        add_action('admin_menu',function(){
            global $menu;
            $parent='buzzjuice';
            foreach((array)$menu as $m){if(!empty($m[0])&&stripos(wp_strip_all_tags($m[0]),'Buzzjuice')!==false){$parent=$m[2];break;}}
            if($parent==='buzzjuice' && !self::menu_exists('buzzjuice')) add_menu_page('Buzzjuice','Buzzjuice','manage_options','buzzjuice',function(){self::page();},'dashicons-admin-site',3);
            add_submenu_page($parent,'Payment Gateways','Payment Gateways','manage_options','buzzjuice-payment-gateway',function(){self::page();});
        });
        add_action('admin_init',function(){
            if(isset($_POST['bzj_pgb_save'])&&current_user_can('manage_options')&&check_admin_referer('bzj_pgb_save')){
                update_option('bzj_pgb_logging_enabled',isset($_POST['logging_enabled']));
                update_option('bzj_pgb_log_level',sanitize_key($_POST['log_level']??'info'));
            }
        });
    }
    private static function menu_exists($slug){global $menu;foreach((array)$menu as $m)if(isset($m[2])&&$m[2]===$slug)return true;return false;}
    public static function page(){
        if(!current_user_can('manage_options'))wp_die('Unauthorized');
        $enabled=(bool)get_option('bzj_pgb_logging_enabled',true);$level=get_option('bzj_pgb_log_level','info');$files=is_dir(BZJ_PGB_LOG_DIR)?glob(BZJ_PGB_LOG_DIR.'*.log'):array();
        ?>
        <div class="wrap"><h1>Buzzjuice → Payment Gateways</h1>
        <p><strong>PGB 5.2.0</strong> — WooCommerce is the payment-state authority; Streams is the fulfillment target.</p>
        <form method="post"><?php wp_nonce_field('bzj_pgb_save'); ?>
        <table class="form-table"><tr><th>Logging</th><td><label><input type="checkbox" name="logging_enabled" <?php checked($enabled); ?>> Enabled</label></td></tr>
        <tr><th>Minimum level</th><td><select name="log_level"><?php foreach(array('debug','info','warning','error','critical') as $x)printf('<option value="%s"%s>%s</option>',esc_attr($x),selected($level,$x,false),esc_html(ucfirst($x))); ?></select></td></tr></table>
        <p><button class="button button-primary" name="bzj_pgb_save" value="1">Save Payment Gateway Settings</button></p></form>
        <h2>Logs</h2><ul><?php foreach($files as $f)echo '<li>'.esc_html(basename($f)).'</li>'; ?></ul>
        <h2>Environment</h2><p>Shared secret: <?php echo BZJ_PGB_HTTP::secret()?'configured':'NOT CONFIGURED'; ?></p>
        </div><?php
    }
}
