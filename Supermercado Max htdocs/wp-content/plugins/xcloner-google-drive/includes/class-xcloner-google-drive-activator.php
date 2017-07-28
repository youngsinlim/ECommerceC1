<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.xcloner.com
 * @since      1.0.0
 *
 * @package    Xcloner_Google_Drive
 * @subpackage Xcloner_Google_Drive/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Xcloner_Google_Drive
 * @subpackage Xcloner_Google_Drive/includes
 * @author     Liuta Ovidiu <info@thinkovi.com>
 */
class Xcloner_Google_Drive_Activator {

	const xcloner_gdrive_minimum_version = '5.5.0';
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		if(version_compare(phpversion(), Xcloner_Google_Drive_Activator::xcloner_gdrive_minimum_version, '<'))
		{
			wp_die('<p>'.sprintf(__("XCloner Google Drive requires minimum PHP version %s in order to run correctly. We have detected your version as %s"),Xcloner_Google_Drive_Activator::xcloner_gdrive_minimum_version, phpversion()).'</p>',  __("XCloner Activation Error"), array( 'response'=>500, 'back_link'=>TRUE ) );
		}

	}

}
