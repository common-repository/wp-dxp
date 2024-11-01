<?php
class Wp_Dxp_Admin_Settings_Page extends Wp_Dxp_Admin_Base_Page {

	/**
	 * Route to the correct action within the page
	 */
	public function route()
	{
		$this->indexAction();
	}

	/**
	 * Index action
	 */
	public function indexAction() {

		$is_onboarding = ( '1' === get_option( WP_DXP_ONBOARDING_MESSAGE_OPTION_KEY, '' ) );
		$is_signed_up  = ( 'signed' === get_option( WP_DXP_NEWSLETTER_SIGNUP_OPTION_KEY, '' ) );
		// Only show when still onboarding, but not after form completed.
		if ( $is_onboarding && ! $is_signed_up ) {
			// The option keys ensures persistancy.
			$this->addFlashMessage(
				sprintf(
					'<h2>%1$s</h2><p>%2$s</p>',
					esc_html__( 'First time configuration', 'wp-dxp' ),
					esc_html__( 'Thanks for installing and activating WP-DXP so that you can start to use WordPress as a digital experience platform. We recommend that if it is your first time with the plugin, that you sign up for our email list. Please note that this plugin is no longer being actively developed or supported. We recommend using PersonalizeWP https://wordpress.org/plugins/personalizewp/ instead of WP-DXP.', 'wp-dxp' ),
				),
				'info',
				'onboarding'
			);
		}

		$items = array(
			1 => array(
				'title' => esc_html__('Learn more', 'wp-dxp'),
				'content' => esc_html__('Find out what more you can do with PersonalizeWP on our website', 'wp-dxp'),
				'button' => esc_html__('Learn more', 'wp-dxp'),
				'img' => plugins_url('/img/personalizewp_tile_website.jpg', __FILE__),
				'url' => esc_url('https://personalizewp.com/'),
			),
			2 => array(
				'title' => esc_html__('Need help?', 'wp-dxp'),
				'content' => esc_html__('Check out our knowledge base', 'wp-dxp'),
				'button' => esc_html__('View Help Docs', 'wp-dxp'),
				'img' => plugins_url('/img/personalizewp_tile_knowledge_base.svg', __FILE__),
				'url' => esc_url(WP_DXP_ADMIN_KNOWLEDGE_BASE_URL),
			),
		);

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/settings/index.php';
	}

	/**
	 * Sign up for newsletter
	 * AJAX callback
	 *
	 * @return void
	 */
	public static function newsletterSignup() {
		$messages = array();

		check_ajax_referer('wp-dxp-ajax-nonce', 'nonce');

		$first_name = ( !empty($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : null );
		$last_name = ( !empty($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : null );
		$email_address = ( !empty($_POST['email_address']) ? sanitize_email($_POST['email_address']) : null );
		$terms_acceptance = ( !empty($_POST['terms_acceptance']) ? sanitize_text_field($_POST['terms_acceptance']) : '' );
		// Can't use `wp_hash` as that uses a local salt. This has to be saltless
		$verify = hash_hmac( 'md5', wp_nonce_tick('PWP-sub') . '|' . 'PWP-sub' . '|' . implode( '|', array(
			$first_name,
			$last_name,
			$email_address,
		) ), '' );

		if ( empty($first_name) ) {
			$messages[] = array(
				'input' => 'first_name',
				'type' => 'error',
				'message' => esc_html__('Please provide your first name', 'wp-dxp'),
			);
		}

		if ( empty($last_name) ) {
			$messages[] = array(
				'input' => 'last_name',
				'type' => 'error',
				'message' => esc_html__('Please provide your last name', 'wp-dxp'),
			);
		}

		if ( empty($email_address) || !filter_var($email_address, FILTER_VALIDATE_EMAIL) ) {
			$messages[] = array(
				'input' => 'email_address',
				'type' => 'error',
				'message' => esc_html__('Please provide your email address', 'wp-dxp'),
			);
		}

		if ( $terms_acceptance != '1' ) {
			$messages[] = array(
				'input' => 'terms_acceptance',
				'type' => 'error',
				'message' => esc_html__('Please accept Terms and Conditions', 'wp-dxp'),
			);
		}

		if ( !empty($messages) ) {
			wp_send_json_error($messages, 200);
			exit;
		}

		$api_response = wp_remote_post(
			esc_url( WP_DXP_API ),
			array(
				'body' => array(
					'first_name' => $first_name,
					'last_name' => $last_name,
					'email_address' => $email_address,
					'terms_acceptance' => 1,
					'verify' => $verify,
				)
			)
		);

		if ( !empty($api_response['response']['code']) && $api_response['response']['code'] == 200 ) {
			// Mark that the user has signed up
			add_option(WP_DXP_NEWSLETTER_SIGNUP_OPTION_KEY, 'signed');
			// Track that we should show a message
			add_option( WP_DXP_DASHBOARD_MESSAGE_OPTION_KEY, '1' );

			$messages = array(
				array(
					'type' => 'redirect',
					'url' => esc_url(WP_DXP_ADMIN_DASHBOARD_INDEX_URL)
				),
			);

			wp_send_json_success($messages, 200);
			exit;
		}
		else {
			$messages[] = array(
				'input' => false,
				'type' => 'error',
				'message' => esc_html__('Something went wrong, please try again', 'wp-dxp'),
			);

			wp_send_json_error($messages, 200);
			exit;
		}
	}
}
