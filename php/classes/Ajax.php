<?php
/**
 * This file includes the ajax request handler of the WordPress plugin.
 *
 * @author Stefan Herndler
 * @created 02.10.15 09:53
 * @since 1.0.0
 * @filesource
 */

/**
 * This class handles all incoming ajax requests from the plugins frontend.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 * @class WPT_Ajax
 */
class WBR_Ajax {

	/**
	 * Defines the required request method for each ajax task (= class method) being called from the incoming ajax request.
	 * Available request methods are GET, POST, PUT and DELETE. Simply write the class method name as array value to the according request method you want to be
	 * used.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var array
	 */
	protected $a_arr_RequestMethods = [
		'GET' => [
			'doSomething'
		],
		'POST' => [

		],
		'PUT' => [

		],
		'DELETE' => [

		]
	];

	/**
	 * Stores a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var null|WPT_Ajax
	 */
	protected static $a_obj_Singleton = null;

	/**
	 * Returns a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @return WPT_Ajax
	 */
	public static function singleton() {
		// initialize singleton if first call
		if (!self::$a_obj_Singleton instanceof WBR_Ajax) {
			self::$a_obj_Singleton = new self();
		}
		// return the singleton of this class
		return self::$a_obj_Singleton;
	}

	/**
	 * Make the class constructor protected to avoid using the class from anywhere.
	 * Registers all WordPress hooks to enable ajax requests for logged in WordPress users and anonymous visitors.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	protected function __construct() {
		add_action("wp_ajax_wow_bnet_rooster", [$this, "run"]); // ajax calls for authenticated WP users
		add_action("wp_ajax_nopriv_wow_bnet_rooster", [$this, "run"]); // ajax calls for anonymous ppl
	}

	/**
	 * Builds the fully qualified url for an ajax call including the given task and optional further GET parameters.
	 * This method can be used to easily create the requesting url in the plugins frontend.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_Task The ajax task to be executed.
	 * @param string $p_str_GETParams Optional further GET parameters attached to the url.
	 * @return string
	 */
	public static function buildUrl($p_str_Task, $p_str_GETParams = "") {
		return sprintf("/wp-admin/admin-ajax.php?action=WP_Template&task=%s%s", $p_str_Task, strlen($p_str_GETParams) > 0 ? "&" . $p_str_GETParams : "");
	}

	/**
	 * Returns a raw string as response to the frontend and stops the script.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_Content The content to be printed.
	 * @param int $p_int_HttpStatus The http status code sent to the frontend.
	 */
	private function RawResponse($p_str_Content, $p_int_HttpStatus = 200) {
		// change the http status code for non-successful responses
		if (intval($p_int_HttpStatus) !== 200) {
			// set the http header for the error response
			http_response_code(intval($p_int_HttpStatus));
		}
		// check if content can be printed without being converted to a string first
		if (is_object($p_str_Content) || is_array($p_str_Content)) {
			// convert objects and arrays to a json string to be able to print them
			$p_str_Content = json_encode($p_str_Content);
		}
		// print the given/converted content
		echo strval($p_str_Content);
		// stop the script
		exit;
	}

	/**
	 * Returns a standard json formatted string as response to the frontend and stops the script.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param array $p_arr_Data Collection of data to be used in the frontend.
	 * @param int $p_int_HttpStatus The http status code sent to the frontend.
	 */
	private function JsonResponse(array $p_arr_Data, $p_int_HttpStatus = 200) {
		// prints the given array as json string
		$this->RawResponse(json_encode($p_arr_Data), $p_int_HttpStatus);
	}

	/**
	 * Main entry point for all incoming ajax requests.
	 * Each request must contain the key 'task' which has to match a method of this class. This method will be executed to handle the ajax request.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	public function run() {
		// check if requested task exists in the request (GET param)
		if (!array_key_exists('task', $_GET)) {
			// missing GET param 'task'
			$this->JsonResponse([WPT_trans("Missing Ajax task.")], 500);
		}
		// extract the given task name ( = class method name)
		$l_str_Task = trim($_GET['task']);
		// check if the requested task exists as class method
		if (!method_exists($this, $l_str_Task)) {
			$this->JsonResponse([WPT_trans("No method found that matches the given task.")], 500);
		}
		// check if the given task can be accessed using the current request method
		if (!in_array($l_str_Task, $this->a_arr_RequestMethods[strtoupper($_SERVER['REQUEST_METHOD'])])) {
			$this->JsonResponse([WPT_trans("Request method is not allowed for the given task.")], 500);
		}
		// task exists and the request method is valid- execute the requested task
		$this->$l_str_Task();
		// each ajax task should print its own response and stop the script
		// therefore if the code reaches this line there is an error in the executed task
		$this->JsonResponse([WPT_trans("Internal Ajax error.")], 500);
	}

	/**
	 * Example method.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	protected function doSomething() {
		$this->RawResponse('Hello, World!');
	}
}