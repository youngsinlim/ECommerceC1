<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.xcloner.com
 * @since             1.0.0
 * @package           Xcloner_Google_Drive
 *
 * @wordpress-plugin
 * Plugin Name:       XCloner Google Drive
 * Plugin URI:        http://www.xcloner.com
 * Description:       Google Drive Remote Storage Implementation for XCloner Backup and Restore plugin. Requires PHP 5.5.
 * Version:           1.0.1
 * Author:            Liuta Ovidiu
 * Author URI:        http://www.xcloner.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       xcloner-google-drive
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-xcloner-google-drive-activator.php
 */
function activate_xcloner_google_drive() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-xcloner-google-drive-activator.php';
	Xcloner_Google_Drive_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-xcloner-google-drive-deactivator.php
 */
function deactivate_xcloner_google_drive() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-xcloner-google-drive-deactivator.php';
	Xcloner_Google_Drive_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_xcloner_google_drive' );
register_deactivation_hook( __FILE__, 'deactivate_xcloner_google_drive' );


require_once plugin_dir_path( __FILE__ ) . 'includes/class-xcloner-google-drive-activator.php';	

if(version_compare(phpversion(), Xcloner_Google_Drive_Activator::xcloner_gdrive_minimum_version, '<'))
{
	?>
	<div class="error notice">
		<p><?php echo sprintf(__("XCloner Google Drive requires minimum PHP version %s in order to run correctly. We have detected your version as %s. Plugin is now deactivated."),Xcloner_Google_Drive_Activator::xcloner_gdrive_minimum_version, phpversion())?></p>
	</div>
	<?php	
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins( plugin_basename( __FILE__ ) );
	return;
}	

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-xcloner-google-drive.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_xcloner_google_drive() {

	require_once(plugin_dir_path( __FILE__ )  . '/vendor/autoload.php');
	$plugin = new Xcloner_Google_Drive();
	$plugin->run();

}
run_xcloner_google_drive();
