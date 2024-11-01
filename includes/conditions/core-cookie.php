<?php
class Wp_Dxp_Condition_Core_Cookie extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_cookie';

	public $category = 'Core';

    public $measure_key = 'cookie_name';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'Cookie', 'wp-dxp' );

		$this->comparators = [
			'any_value'        => _x( 'Has any Value (Exists)', 'Comparator', 'wp-dxp' ),
			'no_value'         => _x( 'Has no Value (Does not exist)', 'Comparator', 'wp-dxp' ),
			'equals'           => _x( 'Equal to', 'Comparator', 'wp-dxp' ),
			'does_not_equal'   => _x( 'Not Equal to', 'Comparator', 'wp-dxp' ),
			'contains'         => _x( 'Contains', 'Comparator', 'wp-dxp' ),
			'does_not_contain' => _x( 'Does Not Contain', 'Comparator', 'wp-dxp' )
		];

		$this->comparisonValues = [];

		$this->preComparisonType = 'text';

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
			case 'any_value':
				return $this->comparatorAnyValue($value, $meta->cookie_name);
			case 'no_value':
				return $this->comparatorNoValue($value, $meta->cookie_name);
			case 'equals':
				return $this->comparatorEquals($value, $meta->cookie_name);
			case 'does_not_equal':
				return $this->comparatorDoesNotEqual($value, $meta->cookie_name);
			case 'contains':
				return $this->comparatorContains($value, $meta->cookie_name);
			case 'does_not_contain':
				return $this->comparatorDoesNotContain($value, $meta->cookie_name);
		}

		return false;
	}

	/**
	 * "Any value" and exists test
	 * @return boolean
	 */
	public function comparatorAnyValue($value, $cookieName)
	{
        if (isset($_COOKIE[$cookieName]) && !empty($_COOKIE[$cookieName])) {
		    return true;
        } else {
            return false;
        }
	}

	/**
	 * "No value" or doesn't exist test
	 * @return boolean
	 */
	public function comparatorNoValue($value, $cookieName)
	{
        if (!isset($_COOKIE[$cookieName]) || isset($_COOKIE[$cookieName]) && empty($_COOKIE[$cookieName])) {
		    return true;
        } else {
            return false;
        }
	}

	/**
	 * "Equal" test
	 * @return boolean
	 */
	public function comparatorEquals($value, $cookieName)
	{
		return $_COOKIE[$cookieName] == $value;
	}

	/**
	 * "Does not equal" test
	 * @return boolean
	 */
	public function comparatorDoesNotEqual($value, $cookieName)
	{
		return $_COOKIE[$cookieName] != $value;
	}

	/**
	 * "Contains" test
	 * @return boolean
	 */
	public function comparatorContains($value, $cookieName)
	{
		return (str_contains($_COOKIE[$cookieName], $value));
	}

	/**
	 * "Does not contain" test
	 * @return boolean
	 */
	public function comparatorDoesNotContain($value, $cookieName)
	{
		return (!str_contains($_COOKIE[$cookieName], $value));
	}
}