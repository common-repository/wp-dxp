<?php
class Wp_Dxp_Condition_Core_Users_Role extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_users_role';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'User Role', 'wp-dxp' );

		$this->comparators = [
			'equals'         => _x( 'Equals', 'Comparator', 'wp-dxp' ),
			'does_not_equal' => _x( 'Does Not Equal', 'Comparator', 'wp-dxp' )
		];

		$this->comparisonValues = get_option(WP_DXP_EDITOR_ROLE_VALUES_KEY, []);
	}

	/**
	 * Test data against condition
	 * @param  string $comparator
	 * @return boolean
	 */
	public function matchesCriteria($comparator, $value, $action, $meta = [])
	{
		switch ($comparator) {
			case 'equals':
				return $this->comparatorEquals($value);
			case 'does_not_equal':
				return $this->comparatorDoesNotEqual($value);
		}

		return false;
	}

	/**
	 * "Equal" test
	 * @return boolean
	 */
	public function comparatorEquals($value)
	{
		$currentUsersRoles = $this->dxp_get_current_users_roles();

		if (in_array($value, $currentUsersRoles)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * "Does not equal" test
	 * @return boolean
	 */
	public function comparatorDoesNotEqual($value)
	{

		$currentUsersRoles = $this->dxp_get_current_users_roles();

		if (!in_array($value, $currentUsersRoles)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Fetch, prep and store editor roles in wp_options table
	 */
	public static function setComparisonValues()
	{
		$roles = [];
		$role_names = get_editable_roles();

		foreach ($role_names as $role_info):
			$roleName = $role_info['name'];
			$currentRoleArray = array(strtolower($roleName) => $roleName);
			$roles = $roles + $currentRoleArray;
		endforeach;

		update_option(WP_DXP_EDITOR_ROLE_VALUES_KEY, $roles);
	}

	/**
	 * Get current users roles
	 * @return array
	 */
	public function dxp_get_current_users_roles()
	{
		if( is_user_logged_in() ) {
			$user = wp_get_current_user();
			$roles = ( array ) $user->roles;
			return $roles; // This will returns an array
		} else {
			return array();
		}
	}

}