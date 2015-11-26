<?php
/**
 * This file is a collection of miscellaneous functions.
 *
 * @author Stefan Herndler
 * @created 01.10.15 18:04
 * @since 1.0.0
 * @filesource
 */

/**
 * Translates a given text using a pre-defined text domain.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 * @param string $p_str_Text The text being translated with the pre-defined text domain of this plugin.
 * @return string
 */
function WBR_trans($p_str_Text) {
	return strval(__($p_str_Text, "wow-bnet-rooster_textdomain"));
}

/**
 * Checks if a given key exists in the POST or GET data and returns its latest value.
 * If the key does not exist the given fallback value is returned.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 * @param string $p_str_Key The key name to look for in POST and GET.
 * @param mixed $p_mixed_Fallback The fallback value if given key is not present in POST or GET (default empty string).
 * @return mixed
 */
function WBR_old($p_str_Key, $p_mixed_Fallback = "") {
	// check if key exists in POST - if not found check GET parameters - if not found return the given fallback value
	return array_key_exists(trim($p_str_Key), $_POST) ? $_POST[trim($p_str_Key)] : array_key_exists(trim($p_str_Key), $_GET) ? $_GET[trim($p_str_Key)] : $p_mixed_Fallback;
}

function WBR_get_class($s_class) {
	switch ($s_class) {
		case 1: return WBR_trans('Warrior'); break;
		case 2: return WBR_trans('Paladin'); break;
		case 3: return WBR_trans('Hunter'); break;
		case 4: return WBR_trans('Rogue'); break;
		case 5: return WBR_trans('Priest'); break;
		case 6: return WBR_trans('Deathknight'); break;
		case 7: return WBR_trans('Shaman'); break;
		case 8: return WBR_trans('Mage'); break;
		case 9: return WBR_trans('Warlock'); break;
		case 10: return WBR_trans('Monk'); break;
		case 11: return WBR_trans('Druid'); break;
        default: return WBR_trans("Unknown class"); break;	
	}
}

function WBR_get_race($s_race) {
	switch ($s_race) {
		case 1: return WBR_trans('Human'); break;
		case 2: return WBR_trans('Orc'); break;
		case 3: return WBR_trans('Dwarf'); break;
		case 4: return WBR_trans('Nightelf'); break;
		case 5: return WBR_trans('Undead'); break;
		case 6: return WBR_trans('Tauren'); break;
		case 7: return WBR_trans('Gnome'); break;
		case 8: return WBR_trans('Troll'); break;
		case 9: return WBR_trans('Goblin'); break;
		case 10: return WBR_trans('Bloodelf'); break;
		case 11: return WBR_trans('Draenei'); break;
		case 24:
		case 25:
		case 26: return WBR_trans('Draenei'); break;
        default: return WBR_trans("Unknown race"); break;	
	}
}

function WBR_get_rank($s_rank) {
	switch ($s_rank) {
		case 0: return "Gildenleitung"; break;
		case 1: return "Offiziere"; break;

		case 2: return "Gildenleitung (Twinks)"; break;

		case 3: return "Raider"; break;

		case 4: return "??"; break; 

		case 5: return "Initiant"; break;

		case 7: return "Family & Friends"; break;

	}
}

function WBR_get_role($s_role) {
	switch ($s_role) {
		case "HEALING": return WBR_trans('HEALING'); break;
		case "TANK": return WBR_trans('TANK'); break;
		case "DPS": return WBR_trans('DPS'); break;

        default: return WBR_trans("Unknown role"); break;	
	}
}