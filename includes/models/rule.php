<?php
class Wp_Dxp_Rule extends Wp_Dxp_Base_Model {

	protected static $table = WP_DXP_TABLE_RULES;

	/**
	 * Matches to database columns
	 */
	protected $attributes = [
		'id',
		'name',
		'category_id',
		'type',
		'conditions_json',
		'created_by',
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
	 * Retrieve friendly name of category the rule is associated with
	 * @return string
	 */
	public function getCategoryNameAttribute()
	{
		$category = Wp_Dxp_Category::find($this->category_id);

		return $category ? $category->name : esc_html_x( 'Unknown', 'Category name', 'wp-dxp' );
	}

	/**
	 * Conditions in array, decoded from JSON
	 * @return array
	 */
	public function getConditionsAttribute()
	{
		$conditions = $this->conditions_json ? json_decode($this->conditions_json) : [];

		$return = [];
		foreach ($conditions as $condition) {
			$condition->raw_value = $condition->comparator == 'any' ? json_encode($condition->value) : $condition->value;

			// Ensure existance of required data
			if ( ! isset( $condition->meta ) ) {
				$condition->meta = [];
			}

			$return[] = $condition;
		}

		return $return;
	}

	/**
	 * Retrieve friendly name of rule type
	 * @return string
	 */
	public function getTypeFriendlyAttribute()
	{
		return Wp_Dxp_Rule_Types::getName($this->type);
	}

	/**
	 * Retrieve name of use (or Plugin) that created the rule
	 * @return string
	 */
	public function getCreatedByFriendlyAttribute()
	{
		if (!$this->created_by) {
			return esc_html_x( 'Plugin', 'Created by user name', 'wp-dxp' );
		}

		$user = get_userdata($this->created_by);

		return empty($user->display_name) ? esc_html_x( 'Unknown', 'Created by user name', 'wp-dxp' ) : $user->display_name;
	}

	/**
	 * Retrieve Edit URL for admin
	 * @return string
	 */
	public function getEditUrlAttribute()
	{
		return WP_DXP_ADMIN_RULES_EDIT_URL . $this->id;
	}

	/**
	 * Retrieve Delete URL for admin
	 * @return string
	 */
	public function getDeleteUrlAttribute()
	{
		return WP_DXP_ADMIN_RULES_DELETE_URL . $this->id;
	}

	/**
	 * Retrieve Duplicate URL for admin
	 * @return string
	 */
	public function getDuplicateUrlAttribute()
	{
		return WP_DXP_ADMIN_RULES_DUPLICATE_URL . $this->id;
	}

	/**
	 * Retrieve Show URL for admin
	 * @return string
	 */
	public function getShowUrlAttribute()
	{
		return WP_DXP_ADMIN_RULES_SHOW_URL . $this->id;
	}

	/**
	 * Return whether rule can be edited
	 * @return boolean
	 */
	public function getCanEditAttribute()
	{
		return $this->type != Wp_Dxp_Rule_Types::$STANDARD;
	}

	/**
	 * Return whether rule can be deleted
	 * @return boolean
	 */
	public function getCanDeleteAttribute()
	{
		return $this->type !== Wp_Dxp_Rule_Types::$STANDARD && 0 === $this->usage_posts_count;
	}

	/**
	 * Return whether rule can be duplicated
	 * @return boolean
	 */
	public function getCanDuplicateAttribute()
	{
		return $this->is_usable;
	}

	/**
	 * Retrieve posts (with details) where this rule is used
	 * @return string
	 */
	public function getUsagePostsAttribute()
	{
		return Wp_Dxp_Block::getUsagePosts( $this->id );
	}

	/**
	 * Retrieve posts (with details) where this rule is used
	 * @return int
	 */
	public function getUsagePostsCountAttribute()
	{
		return Wp_Dxp_Block::getUsagePostsCount( $this->id );
	}

	/**
	 * Retrieve blocks (with details) where this rule is used
	 * @return string
	 */
	public function getUsageBlocksAttribute()
	{
		return Wp_Dxp_Block::getUsageBlocks( $this->id );
	}

	/**
	 * Retrieve number of blocks where this rule is used
	 * @return string
	 */
	public function getUsageBlocksCountAttribute()
	{
		return Wp_Dxp_Block::getUsageBlocksCount( $this->id );
	}

	/**
	 * Is the rule usable with the conditions' current dependencies?
	 * @return boolean
	 */
	public function getIsUsableAttribute()
	{
		// $dependencies = $this->getConditionDependencies();

		foreach ($this->conditions as $condition) {
			$classInstance = Wp_Dxp_Conditions::getClass($condition->measure);
			if ( $classInstance ) {
				$isUsable = $classInstance->isUsable();

				if (!$isUsable) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Retrieve all dependencies from the conditions associated with the rule
	 * @return array
	 */
	protected function getConditionDependencies()
	{
		$return = [];
		foreach ($this->conditions as $condition) {
			$classInstance = Wp_Dxp_Conditions::getClass($condition->measure);
			if ( $classInstance ) {
				$dependencies = $classInstance->dependencies();

				return array_merge($return, $dependencies);
			}
		}

		return $return;
	}

	public function getConditionDependencyIssues()
	{
		$issues = [];

		$dependencies = $this->getConditionDependencies();

		foreach ($dependencies as $dependency) {
			if (!$dependency->verify()) {
				$issues[] = $dependency->getFailureMessage();
			}
		}

		return $issues;
	}

	/**
	 * Clone this rule and save
	 * @return Wp_Dxp_Rule
	 */
    public function clone()
    {
        $this->type = Wp_Dxp_Rule_Types::$CUSTOM;
		$this->created_by = get_current_user_id();

        return parent::clone();
    }

	/**
	 * Conditions match criteria - used when deciding whether to render a block
	 * @param  string $action
	 * @return boolean
	 */
	public function conditionsMatched($action)
	{
		foreach ($this->conditions as $condition) {
			$classInstance = Wp_Dxp_Conditions::getClass($condition->measure);
			if ( $classInstance ) {
				$criteriaMatched = $classInstance->matchesCriteria($condition->comparator, $condition->value, $action, $condition->meta);

				if (!$criteriaMatched) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Populate model from an array of data
	 * @param  array $data
	 */
	public function populateFromArray($data)
	{
		$this->name = $data['name'];
		$this->category_id = $data['category_id'];

		$conditionCount = ! empty( $data['conditions']['measure'] ) ? count($data['conditions']['measure']) : 0;

		$conditions = [];
		for ($i = 0; $i < $conditionCount; $i++) {

			$metaArray = [];
			$measure = $data['conditions']['measure'][$i];
			$metaValue = $data['conditions']['meta_value'][$i];
			$comparator = $data['conditions']['comparator'][$i];
			$value = $data['conditions']['value'][$i];
			$rawValue = $data['conditions']['raw_value'][$i];

			if (!empty($metaValue)) :
				$conditionClass = Wp_Dxp_Conditions::getClass($measure);
				if ( $conditionClass ) :
            		$measureKey = $conditionClass->measure_key;

					$metaArray[$measureKey] = $metaValue;
				endif;
			endif;

			$conditions[] = (object) [
				'measure' => $measure,
				'meta' => $metaArray,
				'comparator' => $comparator,
				'value' => $comparator == 'any' ? json_decode(stripslashes($rawValue)) : $rawValue,
			];
		}

		$this->conditions_json = json_encode($conditions);
	}

	/**
	 * Array of tag attributes to be included in WP DXP tag
	 * @param string $action the resulting action of the rule
	 * @return array
	 */
	public function tagAttributes($action)
	{

		$attributes = [];

		foreach ($this->conditions as $condition) {
			$classInstance = Wp_Dxp_Conditions::getClass($condition->measure);
			if ( $classInstance ) {
				$attributes = $classInstance->tagAttributes($condition, $action) + $attributes;
			}
		}

		return $attributes;
	}

}
