<?php
class Wp_Dxp_Admin_Dashboard_Page extends Wp_Dxp_Admin_Base_Page {

	/**
	 * Route to the correct action within the page
	 */
	public function route()
	{
		Wp_Dxp_Condition_Core_Users_Role::setComparisonValues();

		if (empty($this->request->form())) {
			$this->indexAction();
		}
	}

	/**
	 * Index action
	 */
	public function indexAction() {
		// Display once all sign complete.
		$is_complete = ( '1' === get_option( WP_DXP_DASHBOARD_MESSAGE_OPTION_KEY, '' ) );
		if ( $is_complete ) {
			// The option key ensures persistancy.
			$this->addFlashMessage(
				sprintf(
					'<h2>%1$s</h2><p>%2$s</p>',
					esc_html__( 'You’re all done!', 'wp-dxp' ),
					wp_kses_post(
						sprintf(
							/* translators: 1: %s expands to a website link to https://filter.agency/about/, 2: </a> closing tag. */
							__( 'Thanks, you’re now able to use the features below. If you get stuck or need help, please read our %1$shelp documentation%2$s, or if you are looking for an enterprise implementation talk to our %1$sperformance team%2$s.', 'wp-dxp' ),
							'<a href="' . esc_url( 'https://filter.agency/about/' ) . '" target="_blank">',
							'</a>'
						)
					)
				),
				'success',
				'dashboard'
			);
		}

		$items = array(
			1 => array(
				'title' => esc_html__( 'Personalization', 'wp-dxp' ),
				'content' => esc_html__( 'Show or hide blocks based on conditions and rules', 'wp-dxp' ),
				'img' => plugins_url('/img/personalizewp_tile_personalization.svg', __FILE__),
				'url' => esc_url(WP_DXP_ADMIN_RULES_INDEX_URL),
			),
			2 => array(
				'title' => esc_html__( 'Settings', 'wp-dxp' ),
				'content' => esc_html__( 'Add and edit your site and plugin preferences', 'wp-dxp' ),
				'img' => plugins_url('/img/personalizewp_tile_settings.svg', __FILE__),
				'url' => esc_url(WP_DXP_ADMIN_SETTINGS_INDEX_URL),
			),
		);

		$comingSoon = array();

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/dashboard/index.php';
	}

	/**
	 * Dismisses Dashboard message
	 * AJAX callback
	 *
	 * @return void
	 */
	public static function dismissSignedUpMessage() {
		check_ajax_referer('wp-dxp-ajax-nonce', 'nonce');

		delete_option( WP_DXP_DASHBOARD_MESSAGE_OPTION_KEY );
		wp_send_json_success(null, 200);
		exit;
	}

	/**
	 * Dismisses Onboarding message
	 * AJAX callback
	 *
	 * @return void
	 */
	public static function dismissOnboardingMessage() {
		check_ajax_referer('wp-dxp-ajax-nonce', 'nonce');

		delete_option( WP_DXP_ONBOARDING_MESSAGE_OPTION_KEY );
		wp_send_json_success(null, 200);
		exit;
	}

}
