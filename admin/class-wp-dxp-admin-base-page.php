<?php
class Wp_Dxp_Admin_Base_Page extends Wp_Dxp_Singleton {

	protected $validationErrors = [];

	protected $request;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->request = Wp_Dxp_Request::getInstance();

		$this->validationErrors = [];
	}

	/**
	 * Process - typically used for processing POST data
	 */
	public function process() {}

	/**
	 * Route to the correct action within the page
	 */
	public function route() {}

	/**
	 * Add a admin notice to {prefix}options table until a full page refresh is done
	 *
	 * @param string $notice our notice message
	 * @param string $type This can be "info", "warning", "error" or "success", "warning" as default
	 * @param boolean $dismissible set this to TRUE to add is-dismissible functionality to your notice
	 * @return void
	 */
	protected function addAdminNotice($notice = "", $type = "warning", $dismissible = true)
	{
		$notices = get_option(WP_DXP_ADMIN_NOTICES_OPTION_KEY, []);

		$dismissible_text = ($dismissible) ? "is-dismissible" : "";

		array_push($notices, [
			"notice" => $notice,
			"type" => $type,
			"dismissible" => $dismissible_text
		]);

		// Then we update the option with our notices array
		update_option(WP_DXP_ADMIN_NOTICES_OPTION_KEY, $notices);
	}

	/**
	 * Add a flash message to {prefix}options table until a full page refresh is done
	 *
	 * @param string $notice our notice message
	 * @param string $type This can be "info", "warning", "error" or "success", "warning" as default
	 * @param string $dismissible set this to allow dismissing that type of persistant notice
	 * @return void
	 */
	protected function addFlashMessage($message = "", $type = "warning", $dismissible = '')
	{
		$messages = get_option(WP_DXP_FLASH_MESSAGES_OPTION_KEY, []);

		array_push($messages, [
			"message" => $message,
			"type" => $type,
			'dismissible' => $dismissible
		]);

		// Then we update the option with our messages array
		update_option(WP_DXP_FLASH_MESSAGES_OPTION_KEY, $messages);
	}

	/**
	 * Function executed when the 'admin_notices' action is called, here we check if there are notices on
	 * our database and display them, after that, we remove the option to prevent notices being displayed forever.
	 * @return void
	 */
	public function displayFlashMessages() {
		$messages = get_option(WP_DXP_FLASH_MESSAGES_OPTION_KEY, []);

		if ($messages) {
			echo '<div class="container-fluid wp-dxp-messages"><div class="row"><div class="col-12">';
		}

		// Iterate through our messages to be displayed and print them.
		foreach ( $messages as $message ) {
			switch( $message['type'] ) {
				case 'success' :
					$icon = 'check';
					break;
				case 'info' :
					$icon = 'info';
					break;
				case 'error' :
				case 'warning' :
				default:
					$icon = 'exclamation';
					break;
			}
			if ( ! empty( $message['dismissible'] ) ) {
				printf('<div class="alert alert-%1$s alert-dismissible fade show" role="alert">
							<i class="bi bi-%3$s-circle-fill" aria-hidden="true"></i>
							%2$s
							<button type="button" class="close" data-dismiss="alert" data-dismiss-type="%5$s" aria-label="%4$s">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>',
					esc_attr( $message['type'] ),
					wp_kses_post($message['message']),
					esc_attr( $icon ),
					esc_attr__( 'Close', 'wp-dxp' ),
					esc_attr( sanitize_key( $message['dismissible'] ) )
				);
			} else {
				printf('<div class="alert alert-%1$s fade show" role="alert">
							<i class="bi bi-%3$s-circle-fill" aria-hidden="true"></i>
							%2$s
						</div>',
					esc_attr( $message['type'] ),
					wp_kses_post($message['message']),
					esc_attr( $icon )
				);
			}
		}

		if ($messages) {
			echo '</div></div></div>';
		}

		// Now we reset our options to prevent messages being displayed forever.
		if(!empty($messages)) {
			delete_option(WP_DXP_FLASH_MESSAGES_OPTION_KEY, []);
		}
	}

	public function showError($error, $die = true)
	{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/error.php';

		if ($die) {
			die();
		}
	}

	/**
	 * Add validation error
	 * @param string $field
	 * @param string $error
	 */
	protected function addValidationError($field, $error) {
		if (empty($this->validationErrors[$field])) {
			$this->validationErrors[$field] = [];
		}

		$this->validationErrors[$field][] = $error;
	}

	/**
	 * Retrieve validation error
	 * @param  string $field
	 * @return array
	 */
	protected function getError($field = null)
	{
		if (empty($field)) {
			return $this->validationErrors;
		}

		return empty($this->validationErrors[$field]) ? null : $this->validationErrors[$field];
	}
}
