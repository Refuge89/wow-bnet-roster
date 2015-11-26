<?php
/**
 * @author Stefan Herndler
 * @created 01.10.15 15:16
 * @since 1.0.0
 * @filesource
 */

/**
 * This class is the main entry point of the WordPress plugin and registers all classes and hooks.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 * @class WPT_WPTemplate
 */
class WBR_Wow_Bnet_Rooster {

	/**
	 * Stores a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var null|WPT_WPTemplate
	 */
	protected static $a_obj_Singleton = null;

	/**
	 * Returns a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @return WPT_WPTemplate
	 */
	public static function singleton() {
		// initialize singleton if first call
		if (!self::$a_obj_Singleton instanceof WBR_Wow_Bnet_Rooster) {
			self::$a_obj_Singleton = new self();
		}
		// return the singleton of this class
		return self::$a_obj_Singleton;
	}

	/**
	 * Makes the class constructor protected to avoid using the class from anywhere.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	protected function __construct() {}

	/**
	 * Runs the application and initializes all parts of the plugin.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	public function run() {
		$this->loadConfiguration();
		$this->loadGlobalFunctions();
		$this->loadInternalStorage();
		$this->loadLocalization();
		$this->loadShortCodes();
		$this->loadAjax();
		$this->enqueueStyling();
	}

	/**
	 * Loads the configuration file of this plugin.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	private function loadConfiguration() {
		// require the global configuration
		require_once(dirname(__FILE__) . '/../../config.php');
	}

	/**
	 * Loads all global functions of this plugin.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	private function loadGlobalFunctions() {
		// define the path to the functions directory
		$l_str_Directory = dirname(__FILE__) . '/../functions/';
		// iterate through each file inside the 'functions' directory
		foreach (scandir($l_str_Directory) as $l_str_FileName) {
			// require only *.php files
			if (strtolower(substr($l_str_FileName, -4)) === ".php") {
				/** @noinspection PhpIncludeInspection */
				require_once($l_str_Directory . $l_str_FileName);
			}
		}
	}

	/**
	 * Loads and initializes the internal storage.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	private function loadInternalStorage() {
		// require the class which handles the internal storage
		require_once(dirname(__FILE__) . '/Storage.php');
		// get the singleton once to call the constructor and initialize the class
		WBR_Storage::singleton();
	}

	/**
	 * Loads and registers the translation of strings.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	private function loadLocalization() {
		// require the class which handles the localization
		require_once(dirname(__FILE__) . '/Localization.php');
		// get the singleton once to call the constructor and initialize the class
		WBR_Localization::singleton();
	}

	/**
	 * Loads and registers short codes.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	private function loadShortCodes() {
		// require the class which handles all short codes
		require_once(dirname(__FILE__) . '/ShortCodes.php');
		// register all WordPress hooks
		WBR_ShortCodes::singleton();
	}

	/**
	 * Loads and registers all ajax requests.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	private function loadAjax() {
		// require the class which handles all ajax requests
		require_once(dirname(__FILE__) . '/Ajax.php');
		// get the singleton once to call the constructor and initialize the class
		WBR_Ajax::singleton();
	}

	/**
	 * Enqueue all plugin styles and scripts to WordPress.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	private function enqueueStyling() {
		// enqueue the bootstrap styling to all medias
		wp_enqueue_style('bootstrap-css', plugins_url('wow-bnet-rooster/css/bootstrap.min.css'));
		// enqueue the bootstrap script in the footer
		wp_enqueue_script('bootstrap-js', plugins_url('wow-bnet-rooster/js/bootstrap.min.js'), ['jquery'], false, true);
		// enqueue a custom style of the plugin to all medias
		wp_enqueue_style('wow-bnet-rooster-css', plugins_url('wow-bnet-rooster/css/wow-bnet-rooster.css'));
		// enqueue the custom script of the plugin
		wp_enqueue_script('wow-bnet-rooster-js', plugins_url('wow-bnet-rooster/js/wow-bnet-rooster.js'), ['jquery'], false, true);
	}

	/**
	 * Queries the battle net service for the guild rooster information
	 *
	 * @author: Sebastian Will
	 * @since 1.0.0
	 */
	public function queryBattleNet() {
		/*
		 * example query
		 * https://eu.api.battle.net/wow/guild/Thrall/Whispering%20Woods?fields=members&locale=de_DE&apikey=gh3navxrszbwgu6vzug54hgce775uwhg
		 */

		$query_url = WBR_CONFIG_BATTLE_NET_HOST . 'wow/guild/' . urlencode( WBR_CONFIG_QUERY_SERVER ) . '/' . rawurlencode( WBR_CONFIG_QUERY_GUILD ) .'?' . 'fields=members' . '&locale=' .WBR_CONFIG_QUERY_LOCALE . '&' . 'apikey=' . WBR_CONFIG_BATTLE_NET_API_KEY;

		$response = wp_remote_get( $query_url );

		if ( is_wp_error ( $response ) ) {
			return $response->get_error_message();
		}

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
		    return $response['body']; // use the content
		} 
	}
}