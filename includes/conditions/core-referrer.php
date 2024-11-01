<?php
class Wp_Dxp_Condition_Core_Referrer extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_referrer';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'Referer', 'wp-dxp' );

		$this->comparators = [
			'equals'           => _x( 'Equals', 'Comparator', 'wp-dxp' ),
			'does_not_equal'   => _x( 'Does Not Equal', 'Comparator', 'wp-dxp' ),
			'contains'         => _x( 'Contains', 'Comparator', 'wp-dxp' ),
			'does_not_contain' => _x( 'Does Not Contain', 'Comparator', 'wp-dxp' )
		];

		$this->comparisonValues = [];

		$this->comparisonType = 'text';
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
			case 'contains':
				return $this->comparatorContains($value);
			case 'does_not_contain':
				return $this->comparatorDoesNotContain($value);
		}

		return false;
	}

	/**
	 * "Equal" test
	 * @return boolean
	 */
	public function comparatorEquals($value)
	{
		if ($GLOBALS['WP_DXP_PARAMS']['referrer_url'] === $value) {
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
		if ($GLOBALS['WP_DXP_PARAMS']['referrer_url'] !== $value) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * "Contains" test
	 * @return boolean
	 */
	public function comparatorContains($value)
	{
		return (str_contains($GLOBALS['WP_DXP_PARAMS']['referrer_url'], $value));
	}

	/**
	 * "Does not contain" test
	 * @return boolean
	 */
	public function comparatorDoesNotContain($value)
	{
		return (!str_contains($GLOBALS['WP_DXP_PARAMS']['referrer_url'], $value));
	}
}