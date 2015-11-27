<?php
/**
 * @author Stefan Herndler
 * @created 01.10.15 13:29
 * @since 1.0.0
 * @filesource
 */

/**
 * This class opens a connection to an external server.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 * @class WPT_Request
 */
class WBR_Request {

	/**
	 * Stores the response of the remote server as plain text.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var string
	 */
	private $a_str_Response = "";

	/**
	 * Stores the http status code of the remote server.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var int
	 */
	private $a_int_StatusCode = 500;

	/**
	 * Makes the class constructor protected to avoid using the class from anywhere.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	protected function __construct() {}

	/**
	 * Returns the http status code of latest response.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @return int
	 */
	public function getStatusCode() {
		return intval($this->a_int_StatusCode);
	}

	/**
	 * Returns the response using the given data type.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_DataType Data type of the response (default as an array).
	 * @return array|object|string|null
	 */
	public function getResponse($p_str_DataType = "array") {
		// return the response according to the given data type
		switch(strtolower(trim($p_str_DataType))) {
			case "array":
				$l_arr_Response = json_decode($this->a_str_Response, true);
				return is_array($l_arr_Response) ? $l_arr_Response : [];
			case "object":
				$l_obj_Response = json_decode($this->a_str_Response, false);
				return is_object($l_obj_Response) ? $l_obj_Response : null;
			case "string":
			case "json":
			default:
				return strval($this->a_str_Response);
		}
	}

	/**
	 * Sends a request to the Groot remote server with given request method and relative path.
	 * The response is stored in this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_RequestMethod The request method used for the request.
	 * @param string $p_str_RelativePath The relative path to the remote server url for the request.
	 * @param array $p_arr_Parameter Optional collection of parameters sent to the request.
	 * @return WPT_Request
	 */
	public function send($p_str_RequestMethod, $p_str_RelativePath, array $p_arr_Parameter = []) {
		// initialize the connection to the remote server
		$l_obj_Curl = curl_init();
		// set the target fully qualified url including the relative path of the request
		curl_setopt($l_obj_Curl, CURLOPT_URL, rtrim(WBR_CONFIG_REQUEST_SERVER , '/') . '/' . trim($p_str_RelativePath, '/'));
		// set the request method of the request
		curl_setopt($l_obj_Curl, CURLOPT_CUSTOMREQUEST, strtoupper(trim($p_str_RequestMethod)));
		// define the number of parameter being sent
		curl_setopt($l_obj_Curl, CURLOPT_POST, count($p_arr_Parameter));
		// set all parameter imploded as string
		curl_setopt($l_obj_Curl, CURLOPT_POSTFIELDS, build_query($p_arr_Parameter));
		// disable ssl verification
		curl_setopt($l_obj_Curl, CURLOPT_SSL_VERIFYPEER, false);
		// set a connection timeout
		curl_setopt($l_obj_Curl, CURLOPT_CONNECTTIMEOUT, intval(WBR_CONFIG_GROOT_TIMEOUT_SEC));
		curl_setopt($l_obj_Curl, CURLOPT_TIMEOUT, intval(WBR_CONFIG_GROOT_TIMEOUT_SEC));
		// yes we want to receive the response :-)
		curl_setopt($l_obj_Curl, CURLOPT_RETURNTRANSFER, true);
		// execute the request and read the response
		$this->a_str_Response = curl_exec($l_obj_Curl);
		// get the http status code
		$this->a_int_StatusCode = intval(curl_getinfo($l_obj_Curl, CURLINFO_HTTP_CODE));
		// close the connection
		curl_close($l_obj_Curl);
		// check if response received or if timeout reached
		if ($this->a_str_Response === false && $this->a_int_StatusCode === 0) {
			$this->a_str_Response = json_encode(["timeout" => [RT_trans('Timeout reached.')]]);
			$this->a_int_StatusCode = 408;
		}
		// return this class
		return $this;
	}
}
