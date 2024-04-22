<?php
/*
Plugin Name: Better Bounce Rate
Description: Fast and effective way to reduce your bounce rate. Just 2 steps, set up and forget it. 
Author: BunkerLATAM
Author URI: https://codecanyon.net/user/bunkerlatam
Version: 1.0
*/

if (!defined("ABSPATH")) die();

define("BBR_PATH", plugin_dir_path(__FILE__));
define("BBR_URI", plugin_dir_url(__FILE__));

function panel_better_bounce_rate() {
	add_submenu_page(
		"options-general.php",
		"Better Bounce Rate",
		"Better Bounce Rate",
		"manage_options",
		"better_bounce_rate",
		"better_bounce_rate_html"
	);
}
add_action("admin_menu", "panel_better_bounce_rate");

function better_bounce_rate_html() {
	$settings = get_option('bbr_settings');
	include BBR_PATH . '/inc/admin-html.php';
}

function bbr_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=better_bounce_rate" title="Settings">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin_bbr = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_bbr", "bbr_settings_link");

function guardar_bbr_ajax() {
	if(empty($_POST['trid']) || empty($_POST['toe'])) wp_die('false');

	$ajax_settings = array();

	$ajax_settings['id'] = sanitize_text_field($_POST['trid']);
	$ajax_settings['time'] = intval($_POST['toe']);
	$ajax_settings['save'] = time();

	if(isset($_POST['mcode']) && $_POST['mcode'] === 'on' && !empty($_POST['ccode'])) {
		$ajax_settings['ccode'] = sanitize_textarea_field($_POST['ccode']);
		$ajax_settings['mcode'] = 'on';
	}

	update_option("bbr_configured", "on");
	if(update_option('bbr_settings', $ajax_settings)) wp_die('true');
}
add_action("wp_ajax_guardar_bbr_AJAX", "guardar_bbr_AJAX");

register_activation_hook(__FILE__, "on_bbr_activate");
register_deactivation_hook(__FILE__, "on_bbr_deactivate");
function on_bbr_activate() {
	$settings = array(
		'id'    => '',
		'time'  => '',
		'mcode' => 'off'
	);
	add_option("bbr_settings", $settings);
	add_option("bbr_configured", "off");
}
function on_bbr_deactivate() {
	delete_option("bbr_settings");
	delete_option("bbr_configured");
}

function show_bbr_admin_notice(){
	$configured = get_option('bbr_configured');
	if($configured !== 'on' && @$_GET['page'] !== 'better_bounce_rate') {
    	echo '<div class="notice notice-error">
             <p>Hello, please finish setting up <strong>Better Bounce Rate</strong> by <a href="options-general.php?page=better_bounce_rate" title="Settings">clicking here</a>.</p>
         </div>';
     }
}
add_action('admin_notices', 'show_bbr_admin_notice');

// Agregar el código al head del sitio
function add_analytics_code() {
	$configured = get_option('bbr_configured');
	$settings = get_option('bbr_settings');
	if($configured === 'on') include BBR_PATH . '/inc/header-html.php';
}
add_action('wp_head', 'add_analytics_code');