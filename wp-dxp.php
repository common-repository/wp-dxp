<?php

/**
 * Plugin Name:       WP-DXP
 * Plugin URI:        https://filter.agency/about/wp-dxp/
 * Description:       Use WordPress as a digital experience platform, adding personalisation and conditional rules to the content that your users see and can interact with. This version is being replaced by PersonalizeWP. 
 * Version:           1.6.8
 * Author:            Filter
 * Author URI:        https://filter.agency
 * Requires at least: 6.0.0
 * Tested up to:      6.4
 * Requires PHP:      7.3
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-dxp
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
define( 'WP_DXP_VERSION', '1.6.8' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-dxp-activator.php
 */
function activate_wp_dxp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-dxp-activator.php';
	Wp_Dxp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-dxp-deactivator.php
 */
function deactivate_wp_dxp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-dxp-deactivator.php';
	Wp_Dxp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_dxp' );
register_deactivation_hook( __FILE__, 'deactivate_wp_dxp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-dxp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_dxp() {

	$plugin = new Wp_Dxp();
	$plugin->run();

}
run_wp_dxp();
