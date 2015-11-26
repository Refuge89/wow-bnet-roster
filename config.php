<?php
/**
 * This file contains the whole configuration of this WordPress plugin.
 * Each configuration key name has to start with the prefix 'WBR_CONFIG_' to avoid multiple declarations with other plugins.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 * @created 21.10.15 08:56
 * @filesource
 */

/**
 * The default language code used if either the specified language code has no translation file or if no language code found/determined.
 * The language code must be written according to RFC 4647 standard.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 */
define('WBR_CONFIG_DEFAULT_LANGUAGE_CODE', "de");


/** 
 * Defines which battle net host is used for querying.
 * US: https://us.api.battle.net/
 * EU: https://eu.api.battle.net/
 * KR: https://kr.api.battle.net/
 * TW: https://tw.api.battle.net/
 * 
 * Other regions are not available at the moment (oceanic servers, chinese servers, and possibly others).
 * @author: Sebastian Will
 * @since: 1.0.0
 */
define('WBR_CONFIG_BATTLE_NET_HOST', "https://eu.api.battle.net/");
define('WBR_CONFIG_ARMORY_HOST', "http://eu.battle.net/wow/de/character/");


/** 
 * Each query needs an api key issued by Blizzard. Register your api key on 
 * https://dev.battle.net
 * and insert the complete key here.
 *
 * @author: Sebastian Will
 * @since: 1.0.0
 */
define('WBR_CONFIG_BATTLE_NET_API_KEY', "gh3navxrszbwgu6vzug54hgce775uwhg");

/**
 * Provide server, guild name and desired locale 
 * Server: Your servername
 * Guild name: The guild to query
 * Locale: the desired locale.
 *
 * @author: Sebastian Will
 * @since: 1.0.0
 */
define('WBR_CONFIG_QUERY_SERVER', "Thrall");
define('WBR_CONFIG_QUERY_GUILD', "Whispering Woods");
define('WBR_CONFIG_QUERY_LOCALE', "de_DE");

/**
 * Define ranks to ignore when displaying the results
 */
define('WBR_CONFIG_IGNORE_RANKS', "2,6,7");

/** 
 * Define the render path of the static images for characters 
 */
define('WBR_CONFIG_BATTLE_NET_RENDER_PATH', "http://render-api-eu.worldofwarcraft.com/static-render/eu/");