<?php
/**
 * Plugin Name: HopefulWay Oxford House Form Redirect
 * Description: Routes the approved public path directly to a configured Google Forms responder URL.
 * Version: 1.0.0
 */
if (!defined('ABSPATH')) { exit; }
function hw_oh_valid_url($value) {
    if (!is_string($value)) return '';
    $value = trim($value);
    $parts = wp_parse_url($value);
    if (!$parts || ($parts['scheme'] ?? '') !== 'https' || isset($parts['user']) || isset($parts['pass']) || isset($parts['port']) || isset($parts['query']) || isset($parts['fragment'])) return '';
    $host = strtolower($parts['host'] ?? ''); $path = $parts['path'] ?? '';
    if ($host === 'docs.google.com' && preg_match('~^/forms/d/(?:e/)?[A-Za-z0-9_-]+/viewform/?$~D', $path)) return esc_url_raw($value);
    if ($host === 'forms.gle' && preg_match('~^/[A-Za-z0-9_-]+/?$~D', $path)) return esc_url_raw($value);
    return '';
}
add_action('admin_init', function() {
    register_setting('hw_oh_settings', 'hw_oh_form_url', ['type'=>'string','sanitize_callback'=>function($value){
        if (trim((string)$value)==='') return '';
        $valid=hw_oh_valid_url($value);
        if (!$valid) { add_settings_error('hw_oh_form_url','invalid','Enter a Google Forms responder URL ending in /viewform or a forms.gle link. Edit links are not accepted.'); return get_option('hw_oh_form_url',''); }
        return $valid;
    },'default'=>'']);
    register_setting('hw_oh_settings', 'hw_oh_enabled', ['type'=>'boolean','sanitize_callback'=>function($v){return !empty($v)?1:0;},'default'=>0]);
});
add_action('admin_menu', function(){add_options_page('HopefulWay Oxford House','HopefulWay Oxford House','manage_options','hw-oh',function(){
    if (!current_user_can('manage_options')) return;
    echo '<div class="wrap"><h1>HopefulWay Oxford House</h1><p>Enable only after privacy, house facts, form publication and external testing are approved.</p>';
    settings_errors(); echo '<form method="post" action="options.php">'; settings_fields('hw_oh_settings');
    echo '<p><label for="hw_oh_form_url">Google Forms responder URL</label><br><input type="url" class="large-text" id="hw_oh_form_url" name="hw_oh_form_url" value="'.esc_attr(get_option('hw_oh_form_url','')).'"></p>';
    echo '<p><input type="hidden" name="hw_oh_enabled" value="0"><label><input type="checkbox" name="hw_oh_enabled" value="1" '.checked(1,get_option('hw_oh_enabled',0),false).'> Enable public redirect</label></p>';
    submit_button(); echo '</form></div>';
});});
add_action('template_redirect',function(){
    $path=wp_parse_url($_SERVER['REQUEST_URI'] ?? '/',PHP_URL_PATH);
    $base=wp_parse_url(home_url('/hopefulway-oxfordhouse'),PHP_URL_PATH);
    if (untrailingslashit((string)$path)!==untrailingslashit((string)$base)) return;
    nocache_headers(); header('Referrer-Policy: no-referrer');
    if (!in_array($_SERVER['REQUEST_METHOD'] ?? 'GET',['GET','HEAD'],true)){status_header(405);header('Allow: GET, HEAD');exit;}
    $url=hw_oh_valid_url(get_option('hw_oh_form_url',''));
    if (!get_option('hw_oh_enabled',0)||!$url){status_header(503);header('Retry-After: 3600');wp_die('Interest registration is not available at the moment. Please contact HopefulWay Foundation through its official website for information.','Registration unavailable',['response'=>503]);}
    // Strict host/path allowlist above; do not forward query string or user data.
    wp_redirect($url,302,'HopefulWay Oxford House');exit;
},0);
