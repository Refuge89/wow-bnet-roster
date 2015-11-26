<?php
/*
Plugin Name: WoW Bnet Rooster
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Sebastian Will
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
Text Domain: wp_template_textdomain
Domain Path: /languages
*/

// check if WordPress core already loaded (security feature to avoid accessing the plugin directly)
if (!defined('ABSPATH')) {
	die('No script kiddies please!');
}

// require the main entry point of this WordPress plugin
require_once(dirname(__FILE__) . '/php/classes/wow-bnet-rooster.php');
// runs the application
WBR_Wow_Bnet_Rooster::singleton()->run();