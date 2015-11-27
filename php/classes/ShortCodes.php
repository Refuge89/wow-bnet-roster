<?php
/**
 * @author Stefan Herndler
 * @created 01.10.15 15:39
 * @since 1.0.0
 * @filesource
 */

/**
 * This class handles all available short codes of the WordPress plugin.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 * @class WPT_ShortCodes
 */
class WBR_ShortCodes {

	/**
	 * Collection of all short codes available and the associated class method which will be executed.
	 * The array key is the short code used and its value the class method being executed when the short code is found.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var array
	 */
	private $a_arr_ShortCodes = [
		'all' => 'GetCompleteRooster'
	];

	/**
	 * The case-sensitive prefix for each short code.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var string
	 */
	const C_STR_SHORT_CODE_PREFIX = "[[WBR:";

	/**
	 * The case-sensitive suffix for each short code.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var string
	 */
	const C_STR_SHORT_CODE_SUFFIX = "]]";

	/**
	 * Stores a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var null|WPT_ShortCodes
	 */
	protected static $a_obj_Singleton = null;

	/**
	 * Returns a singleton reference of this class.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @return WPT_ShortCodes
	 */
	public static function singleton() {
		// initialize singleton if first call
		if (!self::$a_obj_Singleton instanceof WBR_ShortCodes) {
			self::$a_obj_Singleton = new self();
		}
		// return the singleton of this class
		return self::$a_obj_Singleton;
	}

	/**
	 * Makes the class constructor protected to avoid using the class from anywhere.
	 * Registers all WordPress hooks to enable the lookup of short codes inside the content being printed.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	protected function __construct() {
		// add the WordPress filter for the content, excerpt and widgets
		add_filter('the_content', [$this, "execute"], PHP_INT_MAX);
		add_filter('the_excerpt', [$this, "execute"], PHP_INT_MAX);
		add_filter('widget_title', [$this, "execute"], PHP_INT_MAX);
		add_filter('widget_text', [$this, "execute"], PHP_INT_MAX);
	}

	/**
	 * Retrieves the whole content of a WordPress post / page / widget and replaces short codes with the associated content.
	 * The content with replaces short codes will be returned to the calling WordPress function.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_Content The whole content of the post / page / widget.
	 * @return string
	 */
	public function execute($p_str_Content) {
		// iterate through each short code and replace it
		foreach($this->a_arr_ShortCodes as $l_str_ShortCode => $l_str_ClassMethod) {
			// check if the class method exists before searching for the given short code
			if (!method_exists($this, $l_str_ClassMethod)) {
				continue;
			}
			// look for the short code (without prefix and suffix) inside the current content
			$p_str_Content = $this->lookup($p_str_Content, $l_str_ShortCode, $l_str_ClassMethod);
		}
		// return the content with replaced short codes
		return $p_str_Content;
	}

	/**
	 * Looks for a given short code inside a given content and executes a given class method if short code found.
	 * The short code will be replaced with the content returned by the class method.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param string $p_str_Content The whole content of the post / page / widget.
	 * @param string $p_str_ShortCode
	 * @param string $p_str_ClassMethod
	 * @return string
	 */
	protected function lookup($p_str_Content, $p_str_ShortCode, $p_str_ClassMethod) {
		// initialize the position where the script will begin to search for the short code
		$l_int_StartPosition = 0;
		// loop through the content until no short code is found anymore
		while(is_int($l_int_StartPosition) && $l_int_StartPosition >= 0) {
			// search for the short code including the prefix inside the content with the latest starting position as offset
			$l_int_StartPosition = stripos($p_str_Content, self::C_STR_SHORT_CODE_PREFIX . $p_str_ShortCode, $l_int_StartPosition);
			// check if short code found
			if (!is_int($l_int_StartPosition)) {
				break;
			}
			// search for the end of the short code (suffix) inside the content with the found starting position as offset
			$l_int_EndPosition = stripos($p_str_Content, self::C_STR_SHORT_CODE_SUFFIX, $l_int_StartPosition + 1);
			// check if the end of the short code found, otherwise the short code is corrupted
			if (!is_int($l_int_EndPosition)) {
				break;
			}
			// extract the short code out of the content without the prefix and suffix
			$l_str_ShortCodeFound = trim(substr(
				$p_str_Content,
				$l_int_StartPosition + strlen(self::C_STR_SHORT_CODE_PREFIX . $p_str_ShortCode),
				$l_int_EndPosition - $l_int_StartPosition - strlen(self::C_STR_SHORT_CODE_PREFIX . $p_str_ShortCode)
			));
			// build a collection of all parameters added to the short code
			$l_arr_Parameters = [];
			// iterate through each additional parameter
			foreach(explode(' ', $l_str_ShortCodeFound) as $l_str_Parameter) {
				// split the parameter into key and value
				list($l_str_Key, $l_str_Value) = explode(':', $l_str_Parameter);
				// append the key and value to the parameter collection
				$l_arr_Parameters[strtolower(trim($l_str_Key))] = trim($l_str_Value);
			}
			// call the given class method with the parameters attached to the short code and receive the short code template
			$l_str_Template = $this->$p_str_ClassMethod($l_arr_Parameters);
			// replace the short code inside the content with the received template
			$p_str_Content = substr_replace(
				$p_str_Content,
				$l_str_Template,
				$l_int_StartPosition,
				$l_int_EndPosition + strlen(self::C_STR_SHORT_CODE_SUFFIX) - $l_int_StartPosition
			);
			// short code replaced, try to find a new one
			$l_int_StartPosition++;
		}
		// return the content with replaced short codes
		return $p_str_Content;
	}

	/**
	 * Task of an example short code.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @param array $p_arr_Parameters Optional additional parameters of the short code.
	 * @return string
	 */
	protected function GetCompleteRooster(/** @noinspection PhpUnusedParameterInspection */array $p_arr_Parameters = []) {
		//$json = WBR_Wow_Bnet_Rooster::queryBattleNet();
		$rooster = WBR_Wow_Bnet_Rooster::queryRooster();
		//$rooster = json_decode($json);
		$ignore_ranks = explode(',', WBR_CONFIG_IGNORE_RANKS);
		
		// error codes from WBR_Wow_Bnet_Rooster::queryBattleNet() are not json responses, so we can check if everything is workout out smoothly
		if (!$rooster) {
			return $json;
		}

		$members = array();

		// traverse the rooster to clean up unneccessry information
		foreach ($rooster->members as $member) {
			if (!in_array($member->rank, $ignore_ranks)) {
				$char = array(
					'name' => $member->character->name,
					'race' => $member->character->race,
					'gender' => $member->character->gender,
					'level' => $member->character->level,
					'class' => $member->character->class,
					'thumbnail' => $member->character->thumbnail,
					'spec' => $member->character->spec->name,
					'role' => $member->character->spec->role,
					'rank' => $member->rank
				);
				array_push($members, $char);
			}
		}

		$members = $this->array_sort($members, 'rank');

		// traverse the rooster for output

		$output_per_rank = '<div class="wbr_rank"> <h1 class="wbr_rank_heading"> %s </h1> %s </div>';
		// thumbail, name, level, race, class, rank
		$output_per_character = '<div class="wbr_character"><img class="rooster-thumbnail icon-medium rounded" src="%s" /><a href="%s" target="_blank"><strong>%s</strong></a><br />%s, %s, %s<br />%s</div>';

		$t_rank = 0;
		$t_output = "";
		$t_rank_output = "";

		foreach ($members as $member) {

			// if we got a switch in rank...
			if ($member['rank'] > $t_rank) {
				$t_output .= sprintf($output_per_rank, WBR_get_rank($t_rank), $t_rank_output);

				// re-init
				$t_rank = $member["rank"];
				$t_rank_output = "";
			}
			$t_armory = WBR_CONFIG_ARMORY_HOST . strtolower(rawurlencode(WBR_CONFIG_QUERY_SERVER)) . '/' . rawurlencode($member["name"]) . '/advanced';
			$t_rank_output .= sprintf($output_per_character, WBR_CONFIG_BATTLE_NET_RENDER_PATH.$member["thumbnail"], $t_armory, $member["name"], $member["level"], WBR_get_race($member["race"]), WBR_get_class($member["class"]), WBR_get_role($member["role"]));
		}

		return $t_output;
		return var_dump($members);
	}

	/*
	 * Sorts an array by a given key
	 */
	private function array_sort($array, $on, $order=SORT_ASC) {
	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }

	    return $new_array;
	}
}