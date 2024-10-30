<?php
/*
Plugin Name: 	Boobook
Version:       1.21
Date:          2016/10/16
Plugin URI:		http://xuxu.fr/2013/12/26/boobook-un-autre-facebook-connect-minimal-pour-wordpress/
Description:	Boobook, Another Facebook Connect for WordPress
Author:			xuxu.fr
Text Domain:   boobook
Domain Path:   /languages/
Author URI:    https://xuxu.fr
*/

/* +---------------------------------------------------------------------------------------------------+
   | 
   +---------------------------------------------------------------------------------------------------+ */
session_start();

/* +---------------------------------------------------------------------------------------------------+
   | CONSTANTES
   +---------------------------------------------------------------------------------------------------+ */
if (!defined('WP_PLUGIN_DIR')) {
	$plugin_dir = str_replace('boobook/', '', dirname(__FILE__));
	define('WP_PLUGIN_DIR', $plugin_dir);
}
define('BOOBOOK_PLUGIN_DIR', WP_PLUGIN_DIR."/boobook/");

/* +---------------------------------------------------------------------------------------------------+
   | INCLUDES
   +---------------------------------------------------------------------------------------------------+ */
require_once(WP_PLUGIN_DIR."/boobook/library/includes/compat.php");
require_once(WP_PLUGIN_DIR."/boobook/library/install.php");
require_once(WP_PLUGIN_DIR."/boobook/library/functions.php");
require_once(WP_PLUGIN_DIR."/boobook/library/functions.image.php");

/* +---------------------------------------------------------------------------------------------------+
   | INCLUDES & Widgets
   +---------------------------------------------------------------------------------------------------+ */
require_once(WP_PLUGIN_DIR."/boobook/widgets/connect.php");
add_action("widgets_init", create_function("", "return register_widget('WP_Boobook_Connect');"));

/* +---------------------------------------------------------------------------------------------------+
   | REGISTER ACTIVATION
   +---------------------------------------------------------------------------------------------------+ */
//
register_activation_hook(__FILE__, 'boobook_install');
register_deactivation_hook(__FILE__, 'boobook_uninstall');

/* +---------------------------------------------------------------------------------------------------+
   | TEXT DOMAIN
   +---------------------------------------------------------------------------------------------------+ */
function boobook_load_textdomain() {
   load_plugin_textdomain('boobook', false, dirname(plugin_basename(__FILE__)).'/languages'); 
}
add_action('init', 'boobook_load_textdomain');

/* +---------------------------------------------------------------------------------------------------+
   | PARAMS
   +---------------------------------------------------------------------------------------------------+ */
// Gestion thumbs
// 
if (function_exists('add_theme_support')) {
	//
	add_theme_support('post-thumbnails');

	// ajoute des nouvelles dimensions aux thumbs
	boobook_set_thumbsize();
}