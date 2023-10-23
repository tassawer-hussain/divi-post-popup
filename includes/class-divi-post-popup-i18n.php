<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://fiverr.com/tassawer
 * @since      1.0.0
 *
 * @package    Divi_Post_Popup
 * @subpackage Divi_Post_Popup/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Divi_Post_Popup
 * @subpackage Divi_Post_Popup/includes
 * @author     Tassawer Hussain <th.tassawer@gmail.com>
 */
class Divi_Post_Popup_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'divi-post-popup',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
