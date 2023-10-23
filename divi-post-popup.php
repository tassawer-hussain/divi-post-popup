<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://fiverr.com/tassawer
 * @since             1.0.0
 * @package           Divi_Post_Popup
 *
 * @wordpress-plugin
 * Plugin Name:       Divi Post Popup
 * Plugin URI:        https://tassawer.com/
 * Description:       Display the post content in popup instead of redirecting to the single page for selective posts.
 * Version:           1.0.0
 * Author:            Tassawer Hussain
 * Author URI:        https://fiverr.com/tassawer/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       divi-post-popup
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
define( 'DIVI_POST_POPUP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-divi-post-popup-activator.php
 */
function activate_divi_post_popup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-divi-post-popup-activator.php';
	Divi_Post_Popup_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-divi-post-popup-deactivator.php
 */
function deactivate_divi_post_popup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-divi-post-popup-deactivator.php';
	Divi_Post_Popup_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_divi_post_popup' );
register_deactivation_hook( __FILE__, 'deactivate_divi_post_popup' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-divi-post-popup.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_divi_post_popup() {

	$plugin = new Divi_Post_Popup();
	$plugin->run();

}
run_divi_post_popup();
