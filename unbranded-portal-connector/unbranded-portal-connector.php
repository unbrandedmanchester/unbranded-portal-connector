<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.unbrandedmanchester.com
 * @since             1.0.0
 * @package           UNBPC_Activity_Log_Api
 *
 * @wordpress-plugin
 * Plugin Name:       Unbranded Portal Connector
 * Plugin URI:        https://www.unbrandedmanchester.com/activity-log-api/
 * Description:       Record all website admin activity and report to Unbranded Support Portal API
 * Version:           1.0.0
 * Author:            Unbranded Manchester
 * Author URI:        https://www.unbrandedmanchester.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       unbranded-portal-connector
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
define( 'UNBPC_PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activity-log-api-activator.php
 */
function unbpc_activate_activity_log_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activity-log-api-activator.php';
	UNBPC_Activity_Log_Api_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-activity-log-api-deactivator.php
 */
function unbpc_deactivate_activity_log_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activity-log-api-deactivator.php';
	UNBPC_UNBPC_Activity_Log_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'unbpc_activate_activity_log_api' );
register_deactivation_hook( __FILE__, 'unbpc_deactivate_activity_log_api' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-activity-log-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function unbpc_run_activity_log_api() {

	$plugin = new UNBPC_Activity_Log_Api();
	$plugin->run();

}
unbpc_run_activity_log_api();
