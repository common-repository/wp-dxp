<?php
class Wp_Dxp_Category extends Wp_Dxp_Base_Model {

	protected static $table = WP_DXP_TABLE_CATEGORIES;

	protected $attributes = [
		'id',
		'name',
		'created_at',
		'modified_at',
	];

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes = [])
	{
		parent::__construct($attributes);
	}

	/**
	 * Retrieve Edit URL for admin
	 * @return string
	 */
	public function getEditUrlAttribute()
	{
		return WP_DXP_ADMIN_CATEGORIES_EDIT_URL . $this->id;
	}

	/**
	 * Retrieve Delete URL for admin
	 * @return string
	 */
	public function getDeleteUrlAttribute()
	{
		return WP_DXP_ADMIN_CATEGORIES_DELETE_URL . $this->id;
	}

	/**
	 * Retrieve View Rules URL for admin
	 * @return string
	 */
	public function getRulesUrlAttribute()
	{
		return WP_DXP_ADMIN_CATEGORIES_RULES_URL . $this->id;
	}

	/**
	 * Retrieve Show URL for admin
	 * @return string
	 */
	public function getShowUrlAttribute()
	{
		return WP_DXP_ADMIN_CATEGORIES_SHOW_URL . $this->id;
	}

	/**
	 * Return whether category can be edited
	 * @return boolean
	 */
	public function getCanEditAttribute()
	{
		return true;
	}

	/**
	 * Return whether category can be deleted
	 * @return boolean
	 */
	public function getCanDeleteAttribute()
	{
		return empty( $this->getRulesCountAttribute() );
	}

	/**
	 * Return all rules related to category
	 * @return array
	 */
	public function getRulesAttribute()
	{
		return $this->getRules();
	}

	/**
	 * Return array of rules that use this category
	 * @return array
	 */
	public function getRules()
	{
		global $wpdb;

        $table = Wp_Dxp_Rule::getTableName();

		$sql = "
SELECT *
FROM {$wpdb->prefix}{$table}
WHERE category_id = %d";

		$rows = $wpdb->get_results( $wpdb->prepare( $sql, $this->id ) );
		$return = [];
		foreach ($rows as $row) {
			$return[] = new Wp_Dxp_Rule( $row );
		}

		return $return;
	}

	/**
	 * Return count of rules that use this category
	 *
	 * @return int
	 */
	public function getRulesCountAttribute() {

		global $wpdb;

        $table = Wp_Dxp_Rule::getTableName();

		$sql = "
SELECT count(id)
FROM {$wpdb->prefix}{$table}
WHERE category_id = %d";

		return $wpdb->get_var( $wpdb->prepare( $sql, $this->id ) );
	}

	/**
	 * Populate model from an array of data
	 * @param  array $data
	 */
	public function populateFromArray($data)
	{
		$this->name = $data['name'];
	}
}
