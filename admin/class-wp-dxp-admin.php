<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/admin
 * @author     Your Name <email@example.com>
 */
class Wp_Dxp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wp_dxp    The ID of this plugin.
	 */
	private $wp_dxp;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wp_dxp       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wp_dxp, $version ) {
		$this->wp_dxp = $wp_dxp;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Dxp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Dxp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ($this->isWpDxpPage()) {
			wp_enqueue_style( $this->wp_dxp . '_bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->wp_dxp . '_chosen', plugin_dir_url( __FILE__ ) . 'css/wp-dxp-chosen.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->wp_dxp . '_datatables', plugin_dir_url( __FILE__ ) . 'css/wp-dxp-dataTables.bootstrap4.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->wp_dxp . '_jquery_ui', plugin_dir_url( __FILE__ ) . 'css/wp-dxp-jquery-ui.css', array(), '1.5', 'all' );

			wp_enqueue_style( $this->wp_dxp, plugin_dir_url( __FILE__ ) . 'css/wp-dxp-admin.css', array(), $this->version, 'all' );

			// Ensures focused CSS styles
			add_filter( 'admin_body_class', [ $this, 'admin_body_class' ] );
		}

		if ( $this->isPostEditPage() ) {
			wp_enqueue_style( $this->wp_dxp . '_bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->wp_dxp, plugin_dir_url( __FILE__ ) . 'css/wp-dxp-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Dxp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Dxp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ($this->isWpDxpPage()) {
			wp_enqueue_script( $this->wp_dxp . '_bootstrap', plugin_dir_url( __FILE__ ) . 'js/wp-dxp-bootstrap.min.js', array('jquery'), $this->version, false );
			wp_enqueue_script( $this->wp_dxp . '_chosen', plugin_dir_url( __FILE__ ) . 'js/wp-dxp-admin.chosen.jquery.js', array('jquery'), $this->version, false );
			wp_enqueue_script( $this->wp_dxp . '_datatables', plugin_dir_url( __FILE__ ) . 'js/wp-dxp-jquery.dataTables.min.js', array('jquery'), $this->version, false );
			wp_enqueue_script( $this->wp_dxp . '_bootstrap_datatables', plugin_dir_url( __FILE__ ) . 'js/wp-dxp-dataTables.bootstrap4.min.js', array($this->wp_dxp . '_bootstrap', $this->wp_dxp . '_datatables'), $this->version, false );
			wp_enqueue_script( $this->wp_dxp . '_bootstrap-datepicker', plugin_dir_url( __FILE__ ) . 'js/wp-dxp-datepicker.min.js', array('jquery'), $this->version, false );

			wp_enqueue_script( $this->wp_dxp, plugin_dir_url( __FILE__ ) . 'js/wp-dxp-admin.js', array( 'jquery' ), $this->version, false );
			// Localisation for scripts.
			wp_localize_script(
				$this->wp_dxp,
				'wpDXPL10n',
				array(
					'active'              => esc_html__( 'Active', 'wp-dxp' ),
					// DataTable.js Localisation.
					'DTsearchPlaceholder' => esc_html__( 'Search', 'wp-dxp' ),
					'DTlengthMenu'        => esc_html(
						sprintf(
						/* translators: 1: %s placeholder for select dropdown of numbers. */
							__( 'Rows per page: %s', 'wp-dxp' ),
							'_MENU_'
						)
					),
					'DTinfo'              => esc_html(
						sprintf(
						/* translators: 1: %s placeholder for index of the first record on the current page, 2: %s placeholder for index of the last record on the current page, 3: %s placeholder for number of records in the table after filtering */
							__( '%1$s-%2$s of %3$s items', 'wp-dxp' ),
							'_START_',
							'_END_',
							'_TOTAL_'
						)
					),
					'DTinfoEmpty'         => esc_html__( '0 results', 'wp-dxp' ),
					'DTzeroRecords'       => esc_html__( 'No matching records found', 'wp-dxp' ),
					'DTpaginatePrevious'  => esc_html__( 'Previous', 'wp-dxp' ),
					'DTpaginateNext'      => esc_html__( 'Next', 'wp-dxp' ),
				)
			);

			// Various AJAX callbacks etc
			wp_localize_script(
				$this->wp_dxp,
				'wpDXP',
				[
					'kb' => untrailingslashit( WP_DXP_ADMIN_KNOWLEDGE_BASE_URL ),
					'url' => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce('wp-dxp-ajax-nonce'),
				]
			);
		} else {
			// Fake register to allow variable below.
			wp_register_script( $this->wp_dxp, false, [], false, true );
			wp_enqueue_script( $this->wp_dxp );
			wp_add_inline_script(
				$this->wp_dxp,
				'
					( function() {
						if ( window.wpDXP ) {
							document.querySelector(`ul#adminmenu a[href=\'${window.wpDXP.kb}\']`).setAttribute("target", "_blank");
						}
					}() );
				',
			);
			wp_localize_script(
				$this->wp_dxp,
				'wpDXP',
				[
					'kb' => untrailingslashit( WP_DXP_ADMIN_KNOWLEDGE_BASE_URL ),
				]
			);
		}
	}

	/**
	 * Add a "wp-dxp-pag" class to body element of wp-admin.
	 *
	 * @since 1.6.0
	 *
	 * @param string $classes CSS classes for the body tag in the admin, a space separated string.
	 *
	 * @return string
	 */
	public function admin_body_class( $classes ) {
		$classes .= ' wp-dxp-page';
		return $classes;
	}

	public function enqueue_block_editor_scripts()
	{
		$properties = require(plugin_dir_path( dirname( __FILE__ ) ). 'build/index.asset.php');

		wp_enqueue_script( $this->wp_dxp.'-blocks', plugin_dir_url( __FILE__ ) . '../build/index.js', array( 'wp-blocks', 'wp-editor', 'wp-i18n' ) + $properties['dependencies'], $properties['version'], false );
		// Add translations for the DXP block in the editor.
		wp_set_script_translations( $this->wp_dxp . '-blocks', 'wp-dxp', plugin_dir_path( __FILE__ ) . '../languages' );
	}

	/**
	 * Dashboard page
	 * @return null
	 */
	public function dashboardMenu() {
		Wp_Dxp_Admin_Dashboard_Page::getInstance()->route();
	}

	/**
	 * Dashboard page
	 * @return null
	 */
	public function settingsMenu() {
		Wp_Dxp_Admin_Settings_Page::getInstance()->route();
	}

	/**
	 * Personalisation page
	 * @return null
	 */
	public function personalisationMenu() {
		Wp_Dxp_Admin_Rules_Page::getInstance()->route();
	}

	/**
	 * Knowledge base page
	 * @return null
	 */
	public function categoriesMenu() {
		Wp_Dxp_Admin_Categories_Page::getInstance()->route();
	}

	/**
	 * Create menu items for WP-DXP
	 * @return null
	 */
	public function createAdminMenu() {
		add_menu_page(
			esc_html__('WP-DXP', 'wp-dxp'),
			esc_html__('WP-DXP', 'wp-dxp'),
			'manage_options',
			WP_DXP_ADMIN_SLUG,
			[$this, 'dashboardMenu'],
			'dashicons-layout',
			21
		);

		add_submenu_page(
			WP_DXP_ADMIN_SLUG,
			esc_html__( 'Dashboard', 'wp-dxp' ),
			esc_html__( 'Dashboard', 'wp-dxp' ),
			'manage_options',
			WP_DXP_ADMIN_SLUG,
			[$this, 'dashboardMenu']
		);

		add_submenu_page(
			WP_DXP_ADMIN_SLUG,
			esc_html__( 'Personalization', 'wp-dxp' ),
			esc_html__( 'Personalization', 'wp-dxp' ),
			'manage_options',
			WP_DXP_ADMIN_RULES_SLUG,
			[$this, 'personalisationMenu']
		);

		// Not directly shown in the main admin menu, but required for permission checks
		add_submenu_page(
			WP_DXP_ADMIN_RULES_SLUG, // Use a different parent slug so it's not visible
			esc_html__( 'Categories', 'wp-dxp' ),
			esc_html__( 'Categories', 'wp-dxp' ),
			'manage_options',
			WP_DXP_ADMIN_CATEGORIES_SLUG,
			[$this, 'categoriesMenu']
		);

		add_submenu_page(
			WP_DXP_ADMIN_SLUG,
			esc_html__( 'Settings', 'wp-dxp' ),
			esc_html__( 'Settings', 'wp-dxp' ),
			'manage_options',
			WP_DXP_ADMIN_SETTINGS_SLUG,
			[$this, 'settingsMenu']
		);

		// Directly add KB item to menu.
		$icon = ' <span class="dashicons dashicons-external" style="transform:translateY(-2px)"></span>'; // Previously was ' <span class="bi bi-box-arrow-up-right"></span>',
		add_submenu_page(
			WP_DXP_ADMIN_SLUG,
			'<span style="white-space:nowrap">' . esc_html__( 'Knowledge Base', 'wp-dxp' ) . $icon . '</span>',
			'<span style="white-space:nowrap">' . esc_html__( 'Knowledge Base', 'wp-dxp' ) . $icon . '</span>',
			'edit_pages',
			WP_DXP_ADMIN_KNOWLEDGE_BASE_URL // Will appear without trailing slash
		);
	}

	/**
	 * Modifies admin menu of WP-DXP
	 * Sets active page for sections which are invisible in the menu
	 *
	 * @param $parent_file
	 * @return string
	 */
	public function setActiveAdminMenu($parent_file) {
		global $plugin_page;

		if ( $plugin_page == WP_DXP_ADMIN_CATEGORIES_SLUG ) {
			$plugin_page = WP_DXP_ADMIN_RULES_SLUG;
		}

		return $parent_file;
	}

	/**
	 * Call process function on page class (usually to process POST data)
	 */
	public function process()
	{
		// Double check for user caps before any possible processing of data.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$request = Wp_Dxp_Request::getInstance();
		$page = $request->get('page', false);

		switch ($page) {
			case WP_DXP_ADMIN_SLUG:
				Wp_Dxp_Admin_Dashboard_Page::getInstance()->process();
				break;
			case WP_DXP_ADMIN_CATEGORIES_SLUG:
				Wp_Dxp_Admin_Categories_Page::getInstance()->process();
				break;
			case WP_DXP_ADMIN_RULES_SLUG:
				Wp_Dxp_Admin_Rules_Page::getInstance()->process();
				break;
		}
	}

	/**
	 * Register API routes for the plugin
	 */
	public function registerApiRoutes()
	{
		register_rest_route( 'wp-dxp/v1', '/rules', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'getRulesApi' ],
			'permission_callback' => [ $this, 'get_private_data_permissions_check' ],
		]);
	}

	/**
	 * This is our callback function to check on permissions
	 */
	public function get_private_data_permissions_check() {
		// Restrict endpoint to only users who have the edit_posts capability.
		if ( current_user_can( 'edit_posts' ) ) {
			return true;
		}

		// This is an allow-list approach.
		return new WP_Error( 'rest_forbidden', esc_html__( 'You can not view private data.', 'wp-dxp' ), array( 'status' => 401 ) );
	}

	/**
	 * Return all rules to be used in gutenberg block setting
	 * @return object|array
	 */
	public function getRulesApi()
	{
		$rules = Wp_Dxp_Rule::all();

		$data = [];
		foreach ($rules as $rule) {
			$data[] = [
				'id' => $rule->id,
				'name' => $rule->name,
				'is_usable' => $rule->is_usable,
			];
		}
		return [
			'data' => $data
		];
	}

	/**
	 * Function executed when the 'admin_notices' action is called, here we check if there are notices on
	 * our database and display them, after that, we remove the option to prevent notices being displayed forever.
	 * @return void
	 */
	public function displayAdminNotices() {
		$notices = get_option(WP_DXP_ADMIN_NOTICES_OPTION_KEY, []);

		return; // We don't use this now

		// Iterate through our notices to be displayed and print them.
		foreach ( $notices as $notice ) {
			printf(
				'<div class="notice notice-%1$s %2$s"><p>%3$s</p></div>',
				esc_attr( $notice['type'] ),
				esc_attr( $notice['dismissible'] ),
				esc_html( $notice['notice' ] )
			);
		}

		// Now we reset our options to prevent notices being displayed forever.
		if(!empty($notices)) {
			delete_option(WP_DXP_ADMIN_NOTICES_OPTION_KEY, []);
		}
	}

	/**
	 * Run DB migrations
	 */
	public function migrateDb()
	{
		// Double check for user caps before any possible processing of data.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		Wp_Dxp_DB_Manager::getInstance()->migrate();
	}

	/**
	 * Is the current page an admin page?
	 * @return boolean
	 */
	protected function isWpDxpPage()
	{
		$request = Wp_Dxp_Request::getInstance();
		$page = $request->get('page');

		$pages = [
			WP_DXP_ADMIN_SLUG,
			WP_DXP_ADMIN_CATEGORIES_SLUG,
			WP_DXP_ADMIN_RULES_SLUG,
			WP_DXP_ADMIN_SETTINGS_SLUG,
		];

		return is_admin() && !empty($page) && in_array($page, $pages);
	}

	/**
	 * Detects if current screen is post edit
	 * @return void
	 */
	protected function isPostEditPage() {
		$screen = get_current_screen();

		if ( is_admin() && $screen->is_block_editor ) {
			return true;
		}

		return false;
	}

}
