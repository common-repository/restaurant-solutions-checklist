<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              stpetedesign.com
 * @since             1.0.0
 * @package           Stp_Srtc
 *
 * @wordpress-plugin
 * Plugin Name:       Restaurant Solutions – Checklist
 * Plugin URI:        stpetedesign.com
 * Description:       Create an interactive checklist for your managers to use that saves and stores a report when they click submit. Then you can recall any list from any day at anytime.
 * Version:           1.0.0
 * Author:            Joseph LoPreste, StPetDesign.com 
 * Author URI:        https://www.stpetedesign.com/joseph-lopreste/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       stp-srtc
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
define( 'STP_SRTC_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-stp-srtc-activator.php
 */
function activate_stp_srtc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-stp-srtc-activator.php';
	Stp_Srtc_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-stp-srtc-deactivator.php
 */
function deactivate_stp_srtc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-stp-srtc-deactivator.php';
	Stp_Srtc_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_stp_srtc' );
register_deactivation_hook( __FILE__, 'deactivate_stp_srtc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-stp-srtc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_stp_srtc() {

	$plugin = new Stp_Srtc();
	$plugin->run();

}
run_stp_srtc();
