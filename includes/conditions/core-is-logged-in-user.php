<?php
class Wp_Dxp_Condition_Core_Is_Logged_In_User extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_is_logged_in_user';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'User is logged in', 'wp-dxp' );

		$this->comparators = [
			'equals' => _x( 'Is', 'Comparator', 'wp-dxp' ),
		];
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
		}

		return false;
	}

	/**
	 * "Equal" test
	 * @return boolean
	 */
	public function comparatorEquals($value)
	{
		return $value == 'true' ? is_user_logged_in() : !is_user_logged_in();
	}
}