<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/includes
 * @author     Your Name <email@example.com>
 */
class Wp_Dxp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Dxp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $wp_dxp    The string used to uniquely identify this plugin.
	 */
	protected $wp_dxp;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_DXP_VERSION' ) ) {
			$this->version = WP_DXP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->wp_dxp = 'wp-dxp';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Dxp_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Dxp_i18n. Defines internationalization functionality.
	 * - Wp_Dxp_Admin. Defines all hooks for the admin area.
	 * - Wp_Dxp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dxp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dxp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dxp-singleton.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-dxp-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-dxp-admin-base-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-dxp-admin-categories-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-dxp-admin-dashboard-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-dxp-admin-settings-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-dxp-admin-rules-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-dxp-admin-blocks.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-dxp-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-dxp-constants.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dxp-block-renderer.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dxp-conditions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dxp-db-manager.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dxp-form.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dxp-request.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/dependencies/base.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/base.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-visitor-country.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-is-logged-in-user.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-time-elapsed.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-new-visitor.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-users-last-visit.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-users-visiting-time.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-users-specific-visiting-time.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-users-device-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-users-role.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-visiting-date.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-query-string.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-referrer.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/conditions/core-cookie.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/models/base.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/models/category.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/models/rule.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/models/block.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/values/class-wp-dxp-rule-types.php';

		$this->loader = new Wp_Dxp_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Dxp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Dxp_i18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Dxp_Admin( $this->get_wp_dxp(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin, 'enqueue_block_editor_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'createAdminMenu' );
		$this->loader->add_filter( 'parent_file', $plugin_admin, 'setActiveAdminMenu' );

        // Restrict database changes to only occur within the admin.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'migrateDb' );

        // Restrict admin processing to running only in the admin.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'process' );

		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'registerApiRoutes');

		// $this->loader->add_action( 'admin_notices', $plugin_admin, 'displayAdminNotices' , 12);

		// These wp_ajax actions requiring being logged in.
		$this->loader->add_action( 'wp_ajax_dxp_newsletter_signup', 'Wp_Dxp_Admin_Settings_Page', 'newsletterSignup' );
		$this->loader->add_action( 'wp_ajax_dxp_dismiss_dashboard_message', 'Wp_Dxp_Admin_Dashboard_Page', 'dismissSignedUpMessage' );
		$this->loader->add_action( 'wp_ajax_dxp_dismiss_onboarding_message', 'Wp_Dxp_Admin_Dashboard_Page', 'dismissOnboardingMessage' );

		// Class handles its own hooks.
		Wp_Dxp_Admin_Blocks::init( $this->loader )->set_up();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Dxp_Public( $this->get_wp_dxp(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'create_visitor_cookie' );

		$this->loader->add_filter('render_block', 'Wp_Dxp_Block_Renderer', 'render', 10, 3);
		$this->loader->add_filter('wp_trim_words', 'Wp_Dxp_Block_Renderer', 'trim_words', 10, 4);

		add_filter( 'register_block_type_args', [ $this, 'add_dxp_block_args' ], 10, 2 );

		$this->loader->add_action( 'rest_api_init', $plugin_public, 'registerApiRoutes');
	}

	/**
	 * Filters the arguments for registering a block type, adding the DXP args.
	 *
	 * @since 1.6.2
	 *
	 * @param array  $args       Array of arguments for registering a block type.
	 * @param string $block_type Block type name including namespace.
	 */
	function add_dxp_block_args( $args, $block_type ) {

		if ( ! isset( $args['attributes'] ) || ! is_array( $args['attributes'] ) ) {
			$args['attributes'] = [];
		}

		/** This is necessary for `/wp-json/wp/v2/block-renderer` REST endpoint to not throw `rest_additional_properties_forbidden`. */
		$args['attributes']['wpDxpRule'] = [
			'type' => 'string',
			'default' => '',
		];
		$args['attributes']['wpDxpAction'] = [
			'type' => 'string',
			'default' => '',
		];
		$args['attributes']['wpDxpId'] = [
			'type' => 'string',
			'default' => '',
		];

		return $args;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_wp_dxp() {
		return $this->wp_dxp;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Dxp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
