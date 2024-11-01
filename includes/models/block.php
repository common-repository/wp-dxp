<?php
class Wp_Dxp_Block extends Wp_Dxp_Base_Model {

	/**
	 * Active Block table
	 *
	 * @since 1.5.0
	 */
	protected static $table = WP_DXP_TABLE_ACTIVE_BLOCKS;

	/**
	 * Matches to database columns
	 *
	 * The block ID is randomly generated from the date on every change of a DXP rule.
	 */
	protected $attributes = [
		'id',
		'rule_id',
		'name',
		'post_id',
	];

	/**
	 * Constructor
	 *
	 * @param array $attributes
	 */
	public function __construct( $attributes = [] ){
		parent::__construct( $attributes );
	}

	/**
	 * Save Model
	 *
	 * @return null
	 */
    public function save() {
    	global $wpdb;

    	$table = self::getTableName();

		// We only ever insert a block, select or delete, never update.
		// Because the ID changes on every edit of the block.
    	$wpdb->insert( $wpdb->prefix . $table, $this->data );

		return null;
	}

	/**
	 * Clone is diabled
	 *
	 * @return null
	 */
    public function clone() {
		_doing_it_wrong( 'Block::clone', __( 'Block cannot be cloned'), '1.5' );
		return null;
    }

    /**
     * Delete is disabled
	 *
     * @param  integer $id Block ID
     * @return boolean
     */
    public static function delete( $id ) {
        _doing_it_wrong( 'Block::delete', __( 'Need to use function `Block::delete_blocks()`'), '1.5' );
		return null;
    }

    /**
     * Delete all blocks for specified Post ID
	 *
     * @param  integer $post_id Post ID
     * @return boolean
     */
    public static function delete_blocks( $post_id ) {
        global $wpdb;

        $table = self::getTableName();

        $sql = $wpdb->prepare("DELETE FROM {$wpdb->prefix}{$table} WHERE post_id = %d;", $post_id );
        return $wpdb->query( $sql );
    }

    /**
     * Get all blocks for specified Post ID
	 *
     * @param  integer $post_id Post ID
     * @return array
     */
    public static function in_post( $post_id ) {
        global $wpdb;

        $table = self::getTableName();

        $sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$table} WHERE post_id = %d;", $post_id );
        $wpdb->query( $sql );
        $rows = $wpdb->get_results( $sql, ARRAY_A );

        $return = [];
        foreach ( $rows as $row ) {
            $return[] = new static( $row );
        }

        return $return;
    }

	/**
	 * Return array of post ids that use a particular rule
	 *
     * @param  integer $rule_id
	 * @return array
	 */
	public static function getUsagePosts( $rule_id ) {

		global $wpdb;

        $table = self::getTableName();

		$sql = "
SELECT post_id
FROM {$wpdb->prefix}{$table}
WHERE rule_id = %d
GROUP BY post_id";

		$post_ids = $wpdb->get_results( $wpdb->prepare( $sql, $rule_id ), ARRAY_N );

		if ( empty( $post_ids ) ) {
			return [];
		}

		$args = [
			'post__in'            => $post_ids,
			'posts_per_page'      => count( $post_ids ),
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		];

		$get_posts = new WP_Query;
		return $get_posts->query( $args );
	}

	/**
	 * Return count of posts that use a particular rule
	 *
     * @param  integer $rule_id
	 * @return int
	 */
	public static function getUsagePostsCount( $rule_id ) {

		global $wpdb;

        $table = self::getTableName();

		$sql = "
SELECT count(post_id)
FROM {$wpdb->prefix}{$table}
WHERE rule_id = %d
GROUP BY post_id";

		return (int) $wpdb->get_var( $wpdb->prepare( $sql, $rule_id ) );
	}

	/**
	 * Return array of blocks from posts that use a particular rule
	 *
     * @param  integer $rule_id
	 * @return array
	 */
	public static function getUsageBlocks( $rule_id ) {

		global $wpdb;

		$table = self::getTableName();

		$sql = "
SELECT {$wpdb->prefix}{$table}.*, {$wpdb->posts}.post_title
FROM {$wpdb->prefix}{$table} LEFT JOIN {$wpdb->posts} ON {$wpdb->prefix}{$table}.post_id = {$wpdb->posts}.ID
WHERE {$wpdb->prefix}{$table}.rule_id = %d";

		return $wpdb->get_results( $wpdb->prepare( $sql, $rule_id ) );
	}

	/**
	 * Return count of blocks that use a particular rule
	 *
     * @param  integer $rule_id
	 * @return array
	 */
	public static function getUsageBlocksCount( $rule_id ) {

		global $wpdb;

        $table = self::getTableName();

		$sql = "
SELECT count(id)
FROM {$wpdb->prefix}{$table}
WHERE rule_id = %d";

		return $wpdb->get_var( $wpdb->prepare( $sql, $rule_id ) );
	}

}
