<?php
/**
 * @author Stefan Herndler
 * @created 02.10.15 09:53
 * @since 1.0.0
 * @filesource
 */

/**
 * This class contains wrapper functions for the internal plugin storage used for any data.
 * Currently the server session is used as internal storage.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 * @class WPT_Storage
 */
class WBR_Storage {

	/**
	 * Stores a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var null|WPT_Storage
	 */
	protected static $a_obj_Singleton = null;

	/**
	 * Returns a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @return WPT_Storage
	 */
	public static function singleton() {
		// initialize singleton if first call
		if (!self::$a_obj_Singleton instanceof WPT_Storage) {
			self::$a_obj_Singleton = new self();
		}
		// return the singleton of this class
		return self::$a_obj_Singleton;
	}

	/**
	 * Makes the class constructor protected to avoid using the class from anywhere.
	 * Initializes the internal storage for any data.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	protected function __construct() {
		// start the php session if enabled but not active yet
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}

	/**
	 * Stores a given key-value pair in the internal storage.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_Key The case-sensitive key name used in the storage.
	 * @param string $p_mixed_Value The value being stored.
	 * @return bool
	 */
	public function store($p_str_Key, $p_mixed_Value) {
		// store the key-value pair in the php session
		$_SESSION[trim($p_str_Key)] = $p_mixed_Value;
		return true;
	}

	/**
	 * Reads the value of a given key from the internal storage.
	 * If the key does not exist the given default value is returned instead.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_Key The case-sensitive key name being read.
	 * @param mixed $p_mixed_Default Optional the default value if key not found (default NULL).
	 * @return mixed
	 */
	public function read($p_str_Key, $p_mixed_Default = null) {
		return array_key_exists(trim($p_str_Key), $_SESSION) ? $_SESSION[trim($p_str_Key)] : $p_mixed_Default;
	}

	/**
	 * Removes a given key from the internal storage.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_Key The case-sensitive key name used in the storage.
	 * @return bool
	 */
	public function clear($p_str_Key) {
		// remove the given key from the session if found
		if (array_key_exists(trim($p_str_Key), $_SESSION)) {
			unset($_SESSION[trim($p_str_Key)]);
		}
		return true;
	}

	/**
	 * Stores the language code used for translations in the internal storage.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_LanguageCode The language code used for further translations.
	 * @return bool
	 */
	public function storeLanguageCode($p_str_LanguageCode) {
		return $this->store("_language_code", strtolower(trim($p_str_LanguageCode)));
	}

	/**
	 * Reads the language code used for translations from the internal storage.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @return string
	 */
	public function readLanguageCode() {
		return strtolower(strval($this->read("_language_code", "")));
	}
}