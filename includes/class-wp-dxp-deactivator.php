<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/includes
 * @author     Your Name <email@example.com>
 */
class Wp_Dxp_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( WP_DXP_NEWSLETTER_SIGNUP_OPTION_KEY );
		delete_option( WP_DXP_DASHBOARD_MESSAGE_OPTION_KEY );
		delete_option( WP_DXP_ONBOARDING_MESSAGE_OPTION_KEY );
	}

}
