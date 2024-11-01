<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/public
 * @author     Your Name <email@example.com>
 */
class Wp_Dxp_Public {

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
	 * @param      string    $wp_dxp       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wp_dxp, $version ) {

		$this->wp_dxp = $wp_dxp;
		$this->version = $version;
	}

	/**
	 * Runtime post object cache
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      array    $posts    Array of post objects
	 */
	private $posts = [];

	/**
	 * Runtime WP DXP rule object cache
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      array    $rules    Array of rules objects
	 */
	private $rules = [];

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->wp_dxp, plugin_dir_url( __FILE__ ) . 'css/wp-dxp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( 'wp-api' );
		wp_enqueue_script( 'luxon-js', plugin_dir_url( __FILE__ ) . 'js/luxon.min.js', array( 'jquery', 'wp-api' ), $this->version, false );
		wp_enqueue_script( $this->wp_dxp, plugin_dir_url( __FILE__ ) . 'js/wp-dxp-public.js', array( 'jquery', 'wp-api' ), $this->version, false );

	}

	public function create_visitor_cookie()
	{
		// php7.3+ only
		// $options = [
		// 	// Always update
		// 	'expires' => time() + WP_DXP_VISITOR_COOKIE_EXPIRY,
		// 	// Allow across whole site
		// 	'path'    => '/',
		// ];
		// if ( is_ssl() ) {
		// 	$options['secure']   = true;
		// 	$options['httponly'] = true;
		// }
		if(isset($_COOKIE[WP_DXP_VISITOR_COOKIE_NAME])){
			setcookie(
				WP_DXP_VISITOR_COOKIE_NAME,
				$_COOKIE[WP_DXP_VISITOR_COOKIE_NAME] + 1,
				time() + WP_DXP_VISITOR_COOKIE_EXPIRY,
				'/'
			);
		} else {
			setcookie(
				WP_DXP_VISITOR_COOKIE_NAME,
				1,
				time() + WP_DXP_VISITOR_COOKIE_EXPIRY,
				'/'
			);
		}
	}

	/**
	 * Register API routes for the plugin
	 */
	public function registerApiRoutes()
	{
		register_rest_route( 'wp-dxp/v1', '/blocks', [
		    'methods'             => 'POST',
		    'callback'            => [ $this, 'getBlocksApi' ],
			'permission_callback' => '__return_true',
		]);
	}

	/**
	 * Return relevant gutenberg blocks
	 * @return object|array
	 */
	public function getBlocksApi($req)
	{
		global $post;

		$posts = [];
		$data = [];

		$GLOBALS['WP_DXP_BLOCKS'] = [];
		$GLOBALS['WP_DXP_POST_ID'] = null;
		$GLOBALS['WP_DXP_PARAMS'] = null;

		$api_request_post_data = $req->get_json_params();

		$GLOBALS['WP_DXP_PARAMS']['time_of_day'] = $api_request_post_data["timeOfDay"];
		$GLOBALS['WP_DXP_PARAMS']['users_current_time'] = $api_request_post_data["usersCurrentTime"];
		$GLOBALS['WP_DXP_PARAMS']['users_current_timestamp'] = $api_request_post_data["usersCurrentTimestamp"];
		$GLOBALS['WP_DXP_PARAMS']['returning_visitor'] = $api_request_post_data["returningVisitor"];
		$GLOBALS['WP_DXP_PARAMS']['daysSinceLastVisit'] = $api_request_post_data["daysSinceLastVisit"];
		$GLOBALS['WP_DXP_PARAMS']['users_device_type'] = $api_request_post_data["usersDeviceType"];
		$GLOBALS['WP_DXP_PARAMS']['url_query_string'] = $api_request_post_data["urlQueryString"];
		$GLOBALS['WP_DXP_PARAMS']['referrer_url'] = $api_request_post_data["referrerURL"];

		// do we have an array of blocks?
		if(is_array($blocks = $api_request_post_data["blocks"])){

			// collate post_id/block_id data to determine what needs to be rendered
			// this allows us to selectively render parent blocks and then allow child WP DXP
			// rules to be parsed independantly when the parent block is rendered on the page

			foreach($blocks as $b){
				if(!empty($b['post_id'])){
					$GLOBALS['WP_DXP_BLOCKS'][] = $b['post_id'].'-'.$b['block_id'];
				}
				else if(!empty($b['slots'])){
					foreach($b['slots'] as $slot){
						$GLOBALS['WP_DXP_BLOCKS'][] = $slot[0].'-'.$slot[1];
					}
				}
			}

			// loop through blocks and fetch data
			foreach($blocks as $i => $b){

				$data[$i] = null;

				// do we have a post ID?
				if(!empty($b['post_id'])){

					$dxp_post = $this->getPost($b['post_id']);
					$GLOBALS['WP_DXP_POST_ID'] = $b['post_id'];
					$post = $dxp_post['post'];

					// do we have blocks for this post?
					if($dxp_post['blocks']){

						// do we have a matching block?
						if($block = $this->findBlock($b['block_id'],$dxp_post['blocks'])){
							$data[$i] = $this->renderBlock($block);
						}

					}
				}

				// do we have a group of slots?
				else if(!empty($b['slots']) && !empty($b['template'])){

					$content = $b['template'];
					$filters = empty($b['filters']) ? null : explode(',',$b['filters']);

					foreach($b['slots'] as $slotIndex => $slot){

						$slotContent = '';

						// do we have a valid post with blocks?
						$dxp_post = $this->getPost($slot[0]);
						$GLOBALS['WP_DXP_POST_ID'] = $slot[0];
						$post = $dxp_post['post'];

						if($dxp_post['blocks']){

							// do we have a matchig block?
							if($block = $this->findBlock($slot[1],$dxp_post['blocks'])){
								$slotContent = $this->renderBlock($block);
							}
						}

						// do we need to apply any filters?
						if($slotContent && $filters){
							foreach($filters as $filter){

								switch($filter){
									case 'trim_words':
										$slotContent = wp_trim_words($slotContent);
										break;
								}

							}
						}


						// swap tags for content
						$content = str_replace(
							'<'.WP_DXP_TAG.' slot="'.$slotIndex.'"></'.WP_DXP_TAG.'>',
							$slotContent,
							$content
						);

					}

					// strip out empty p tags
					$content = str_replace('<p></p>', '', $content);

					$data[$i] = trim($content);

				}

				// Ensure normal filters are run for embeds etc.
				$data[$i] = apply_filters( 'the_content', $data[$i]);
			}
		}

		return [
			'data' => $data,
		];
	}

	private function findBlock($id, $blocks){

		// loop through block array to identify block
		foreach($blocks as $b){

			// is THIS block the one we want?
			if(!empty($b['attrs']['wpDxpId']) && $b['attrs']['wpDxpId'] == $id){
				return $b;
			}

			// check inner blocks
			if($b = $this->findBlock($id, $b['innerBlocks'])){
				return $b;
			}
		}

		// block not found
		return null;
	}

	private function renderBlock($block) {
		$action = empty($block['attrs']['wpDxpAction']) ? 'show' : $block['attrs']['wpDxpAction'];

		if ( !empty($block['attrs']['wpDxpRule']) ) {
			$rules = Wp_Dxp_Rule::findAll($block['attrs']['wpDxpRule']);

			foreach ( $rules as $rule ) {
				// If Rule's not found or is not usable due to missing dependencies then don't show content
				if (!$rule || !$rule->is_usable) {
					return '';
				}

				$conditionsMatched = $rule->conditionsMatched($action);

				switch (true) {
					case $action == 'show' && $conditionsMatched:
					case $action == 'hide' && !$conditionsMatched:
						break;
					case $action == 'hide' && $conditionsMatched:
					case $action == 'show' && !$conditionsMatched:
						return null;
				}
			}
		}

		return trim(render_block($block));
	}

	private function getPost($id){
		global $post;

		// fetch and cache the post and parse
		if(!array_key_exists($id, $this->posts)){
			$this->posts[$id] = [
				'post' => get_post($id),
				'blocks' => null,
			];

			if($this->posts[$id]['post']){
				// Set the global, so blocks render correctly.
				$post = $this->posts[$id]['post'];
				$this->posts[$id]['blocks'] = parse_blocks($this->posts[$id]['post']->post_content);
			}
		}

		return $this->posts[$id];
	}

	private function getRule($id){

		// fetch and cache the rule and parse
		if(!array_key_exists($id, $this->rules)){
			$this->rules[$id] = Wp_Dxp_Rule::find($id);
		}

		return $this->rules[$id];

	}

}
