<?php

class Wp_Dxp_Admin_Blocks extends Wp_Dxp_Singleton {

	/**
	 * The loader for the plugin
	 *
	 * @since  1.5.0
	 * @access private
	 * @var    \Wp_Dxp_Loader $loader Object maintaining actions/filters.
	 */
	private $loader;

	/**
	 * @param string $loader
	 * @return self
	 */
	public static function init( $loader ) {

		static $instance = null;

		if ( ! $instance ) {
			$instance = new Wp_Dxp_Admin_Blocks( $loader );
		}

		return $instance;
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.5.0
	 * @param \Wp_Dxp_Loader $loader Object maintaining actions/filters.
	 */
	public function __construct( $loader ) {

		$this->loader = $loader;
	}

	/**
	 * Adds the class actions.
	 *
	 * @since 1.5.0
	 */
	public function set_up() {

		$this->loader->add_action( 'save_post', $this, 'block_update', 100, 3 );

		$this->loader->add_action( 'delete_post', $this, 'block_delete', 100, 1 );
	}

	/**
	 * Triggers on update of a post to update its' blocks.
	 *
	 * @param int      $post_id ID of Post
	 * @param \WP_Post $post    Post object
	 */
	public function block_update( $post_id, $post, $update ) {

		// Verify if this is an auto save routine.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Skip revisions.
		if ( 'revision' === $post->post_type ) {
			return;
		}

		// Post has been "inserted"/"updated", potentially already has "active blocks"
		// 1. Get all active blocks for the post
		// 2. Compare against new blocks via...
		// 3. different number, or different block key hash
		// 4. Delete all existing db entries and recreate.
		$existing = [];
		$_blocks  = Wp_Dxp_Block::in_post( $post_id );
		if ( ! empty( $_blocks ) ) {
			foreach ( $_blocks as $block ) {
				// Use key to allow checking on uniqueness.
				$key              = md5( $post_id . '|' . $block->rule_id . '|' . $block->id );
				$existing[ $key ] = $block;
			}
		}

		$process = [];
		if ( ! empty( $post->post_content ) ) {
			$_new_blocks = parse_blocks( $post->post_content );
			foreach ( $_new_blocks as $block ) {
				if ( ! empty( $block['attrs']['wpDxpRule'] ) ) {
					$rules = array_unique( array_filter( explode(',', $block['attrs']['wpDxpRule'] ) ) );

					foreach ( $rules as $rule ) {
						$key = md5( $post_id . '|' . $rule . '|' . $block['attrs']['wpDxpId'] );

						if ( ! empty( $process[ $key ] ) ) {
							return new WP_Error( 'block_key_collision', esc_html__( 'Duplicate blocks, collision detected.', 'wp-dxp' ), array( 'status' => 400 ) );
						}

						$process[ $key ] = new Wp_Dxp_Block( [
							'id'      => $block['attrs']['wpDxpId'],
							'rule_id' => $rule,
							'name'    => $block['blockName'],
							'post_id' => $post_id,
						] );
					}
				}
			}
		}

		$dirty = false;
		if ( count( $existing ) !== count( $process ) ) {
			$dirty = true;
		} elseif ( ! empty( $process ) ) {
			// Check if 'new' block already exists.
			foreach( $process as $key => $block ) {
				if ( empty( $existing[ $key ] ) ) {
					// Break on first block 'change'.
					$dirty = true;
					break;
				}
			}
		}

		// This should ensure we only update when required.
		if ( $dirty ) {
			// Delete all existing blocks.
			if ( ! empty( $existing ) ) {
				$this->block_delete( $post_id );
			}

			// And simply re-insert.
			foreach( $process as $block ) {
				$block->save();
			}
		}

	}

	/**
	 * Triggers on deletion of Post to delete its' active blocks.
	 *
	 * @param int $post_id ID of Post
	 */
	public function block_delete( $post_id ) {

		// Post has been fully deleted, delete all "active blocks" with same post_id.
		return Wp_Dxp_Block::delete_blocks( $post_id );
	}

}
