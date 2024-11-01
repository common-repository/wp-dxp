<?php
class Wp_Dxp_DB_Manager extends Wp_Dxp_Singleton {

	protected $migrationKey = 'wp_dxp_last_migration';

	/**
	 * Constructor
	 */
	public function __construct() { }

	/**
	 * Run migrations
	 */
	public function migrate()
	{
		global $wpdb;

		// Double check for user caps before any possible processing of data.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if(is_admin()) {
			$lastMigration = get_option($this->migrationKey);
			if(!is_numeric($lastMigration))
				$lastMigration = -1;

			$i = -1;

			require_once(plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-dxp-db-migrations.php');
			foreach($wpDxpDbMigrations as $i => $closure){
				if($i <= $lastMigration) {
					continue;
				}

				$closure($wpdb);
			}

			// Run through various upgrade functions, depending on current version.
			if ( $lastMigration < 8 ) {
				$this->upgrade_150( $lastMigration );
			}

			if($i > $lastMigration) {
				// Mark db updated.
				update_option($this->migrationKey, $i);
			}
		}
	}

	/**
	 * Create database table, if it doesn't already exist.
	 *
	 * Based on admin/install-helper.php maybe_create_table function.
	 *
	 * @since 1.5.0
	 *
	 * @param string $table_name Database table name.
	 * @param string $create_sql Create database table SQL.
	 * @return bool False on error, true if already exists or success.
	 */
	protected function maybe_create_table( $table_name, $create_sql ) {
		global $wpdb;

		if ( in_array( $table_name, $wpdb->get_col( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ), 0 ), true ) ) {
			return true;
		}

		$wpdb->query( $create_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		return in_array( $table_name, $wpdb->get_col( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ), 0 ), true );
	}

	/**
	 * Executes changes made in DXP 1.5.0
	 *
	 * @since 1.5.0
	 *
	 * @param int $current_db_version The old (current) database version.
	 */
	protected function upgrade_150( $current_db_version ) {
		global $wpdb;

		if ( $current_db_version < 8 ) {

			// Get all the posts with DXP blocks, process and populate the active_blocks table.
			// Search for format of `"wpDxpRule":"` within any post_content
			$sql = "SELECT ID, post_content
			FROM {$wpdb->posts}
			WHERE post_content LIKE %s
				AND post_status IN ('publish', 'future', 'draft', 'pending', 'private');";

			$posts = $wpdb->get_results( $wpdb->prepare( $sql, '%' . $wpdb->esc_like( '"wpDxpRule":"' ) . '%' ) );

			foreach ( $posts as $post ) {
				$blocks = parse_blocks( $post->post_content );

				foreach ( $blocks as $block ) {
					// Ensure the block is using a rule, and it's not 0.
					if ( ! empty( $block['attrs']['wpDxpId'] ) && ! empty( $block['attrs']['wpDxpRule']) ) {
						$active_block = new Wp_Dxp_Block( [
							'id'      => $block['attrs']['wpDxpId'],
							'rule_id' => $block['attrs']['wpDxpRule'],
							'name'    => $block['blockName'],
							'post_id' => $post->ID,
						] );

						// Store to the database.
						$active_block->save();
					}
				}
			}
		}
	}

	public function deactivate() {}

	/**
	 * Remove all the WP-DXP data when uninstalling
	 */
	public function uninstall() {
		global $wpdb;

		// Remove all custom tables.
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}" . WP_DXP_TABLE_RULES );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}" . WP_DXP_TABLE_CATEGORIES );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}" . WP_DXP_TABLE_ACTIVE_BLOCKS );

		// Remove all keys in options table.
		delete_option( $this->migrationKey );
		delete_option( WP_DXP_ADMIN_NOTICES_OPTION_KEY );
		delete_option( WP_DXP_FLASH_MESSAGES_OPTION_KEY );
		delete_option( WP_DXP_EDITOR_ROLE_VALUES_KEY );
		delete_option( WP_DXP_NEWSLETTER_SIGNUP_OPTION_KEY );
		delete_option( WP_DXP_DASHBOARD_MESSAGE_OPTION_KEY );
		delete_option( WP_DXP_ONBOARDING_MESSAGE_OPTION_KEY );
	}
}
