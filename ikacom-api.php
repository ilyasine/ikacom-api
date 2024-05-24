<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              https://dev.ilyasine.com/
 * @since             1.1.0
 * @package           Ikacom_Api
 *
 * @wordpress-plugin
 * Plugin Name:       IKACOM API
 * Plugin URI:        https://sva.ikacom.fr/
 * Description:       Plugin that manages requests to IKACOM API
 * Version:           1.1.0
 * Author:            Yassine Idrissi
 * Author URI:        https://dev.ilyasine.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ikacom-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'IKACOM_API_VERSION', '1.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ikacom-api-activator.php
 */
function activate_ikacom_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ikacom-api-activator.php';
	Ikacom_Api_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ikacom-api-deactivator.php
 */
function deactivate_ikacom_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ikacom-api-deactivator.php';
	Ikacom_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ikacom_api' );
register_deactivation_hook( __FILE__, 'deactivate_ikacom_api' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ikacom-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ikacom_api() {

	$plugin = new Ikacom_Api();
	$plugin->run();

}
run_ikacom_api();
