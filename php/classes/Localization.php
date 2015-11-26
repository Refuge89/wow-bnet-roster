<?php
/**
 * This file includes the localization of this plugin.
 *
 * @author Stefan Herndler
 * @created 28.10.15 10:14
 * @since 1.0.0
 * @filesource
 */

/**
 * This class handles the localization of this plugin.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 * @class WPT_Localization
 */
class WBR_Localization {

	/**
	 * Stores a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var null|WPT_Localization
	 */
	protected static $a_obj_Singleton = null;

	/**
	 * Returns a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @return WPT_Localization
	 */
	public static function singleton() {
		// initialize singleton if first call
		if (!self::$a_obj_Singleton instanceof WBR_Localization) {
			self::$a_obj_Singleton = new self();
		}
		// return the singleton of this class
		return self::$a_obj_Singleton;
	}

	/**
	 * Makes the class constructor protected to avoid using the class from anywhere.
	 * Registers the WordPress hooks to enable the translation of strings.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	protected function __construct() {
		add_action('plugins_loaded', [$this, 'load']);
	}

	/**
	 * Loads the translation for all strings of this WordPress plugin.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	public function load() {
		// first of all try to load the translation for the determined language
		if (!$this->loadTextDomain($this->determineLanguageCode())) {
			// if determined language cannot be loaded use the fallback language code instead
			$this->loadTextDomain(WBR_CONFIG_DEFAULT_LANGUAGE_CODE);
		}
	}

	/**
	 * Try to load the translation file for the given language code.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_LanguageCode The language code to load its translations.
	 * @return bool
	 */
	private function loadTextDomain($p_str_LanguageCode) {
		// get the absolute path to the translation file for the given language code
		$l_str_FileNameAndPath = sprintf("%s/../../languages/%s.mo", dirname(__FILE__), mb_strtolower(trim($p_str_LanguageCode)));
		// check if the translation file exists
		if (!file_exists($l_str_FileNameAndPath)) {
			return false;
		}
		// try to load the translation file using a pre-defined text domain
		return load_textdomain("wow-bnet-rooster_textdomain", $l_str_FileNameAndPath);
	}

	/**
	 * Returns the language code (2 characters) in all lowercase used for translations.
	 * The language code is determined using the following priority:
	 *  1) internal storage (manually set by the visitor itself)
	 *  2) specified by WPML plugin if installed and activated
	 *  3) using the browser's locale
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @return string
	 */
	private function determineLanguageCode() {
		// check if language code is specified in the internal storage (manually switched by the visitor)
		$l_str_LanguageCode = WBR_Storage::singleton()->readLanguageCode();
		if (strlen($l_str_LanguageCode) === 2) {
			return $l_str_LanguageCode;
		}
		// get the language code of the optional WordPress plugin 'WPML' if plugin is activated
		if (defined('ICL_LANGUAGE_CODE') && strlen(ICL_LANGUAGE_CODE) === 2) {
			return strtolower(strval(ICL_LANGUAGE_CODE));
		}
		// extract the locale specified by the http header (browser)
		$l_arr_Locale = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		// check if locale is specified in the browser
		if (count($l_arr_Locale) === 0) {
			// no locale specified, return the default value
			return WPT_CONFIG_DEFAULT_LANGUAGE_CODE;
		}
		// extract the locale from the browser variable
		$l_str_Locale = $l_arr_Locale[0];
		// check if locale is split by an underscore
		if (strlen($l_str_Locale) === 5 && strpos($l_str_Locale, "_") !== false) {
			// extract the language code out of the locale
			return strtolower(explode('_', $l_str_Locale)[0]);
		}
		// check if locale is split by a minus
		if (strlen($l_str_Locale) === 5 && strpos($l_str_Locale, "-") !== false) {
			// extract the language code out of the locale
			return strtolower(explode('-', $l_str_Locale)[0]);
		}
		// unknown separator of language code and country code inside the locale
		// either the browser's locale is already the language code (only 2 characters) otherwise the default language will be used
		return strlen($l_str_Locale) === 2 ? strtolower($l_str_Locale) : WBR_CONFIG_DEFAULT_LANGUAGE_CODE;
	}
}