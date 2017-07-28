<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.xcloner.com
 * @since      1.0.0
 *
 * @package    Xcloner_Google_Drive
 * @subpackage Xcloner_Google_Drive/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Xcloner_Google_Drive
 * @subpackage Xcloner_Google_Drive/includes
 * @author     Liuta Ovidiu <info@thinkovi.com>
 */
class Xcloner_Google_Drive_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'xcloner-google-drive',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
