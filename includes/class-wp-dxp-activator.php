<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/includes
 * @author     Your Name <email@example.com>
 */
class Wp_Dxp_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_action( 'activated_plugin', function() {
			// Persist the onboarding messages
			add_option( WP_DXP_ONBOARDING_MESSAGE_OPTION_KEY, '1' );

			$url = add_query_arg(
				array(
					'status' => 'activated',
				),
				WP_DXP_ADMIN_SETTINGS_INDEX_URL
			);
			wp_redirect( $url );
			exit;
		} );
	}

}
