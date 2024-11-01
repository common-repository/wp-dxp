<?php
class Wp_Dxp_Condition_Core_Users_Last_Visit extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_users_last_visit';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'Last Visit', 'wp-dxp' );

		$this->comparators = [
			'more_than' => _x( 'More Than', 'Comparator', 'wp-dxp' ),
			'less_than' => _x( 'Less Than', 'Comparator', 'wp-dxp' ),
			'equals'    => _x( 'Equal To', 'Comparator', 'wp-dxp' ),
		];

		// Create an array of keys with empty values.
		$this->comparisonValues = array_fill_keys( range(1, 30), '' );
		// Loop over array and modify values to set translatable string.
		array_walk( $this->comparisonValues, function( &$item, $key ) {
			/* translators: 1: %d number of days. */
			$item = sprintf( _n( '%d Day ago', '%d Days ago', $key, 'wp-dxp' ), $key );
		} );
	}

	/**
	 * Test data against condition
	 * @param  string $comparator
	 * @return boolean
	 */
	public function matchesCriteria($comparator, $value, $action, $meta = [])
	{
		switch ($comparator) {
			case 'more_than':
				return $this->comparatorMoreThan($value);
			case 'less_than':
				return $this->comparatorLessThan($value);
			case 'equals':
				return $this->comparatorEquals($value);
		}

		return false;
	}

	/**
	 * "More than" test
	 * @return boolean
	 */
	public function comparatorMoreThan($value)
	{
		if ($GLOBALS['WP_DXP_PARAMS']['daysSinceLastVisit'] > $value) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * "Less than" test
	 * @return boolean
	 */
	public function comparatorLessThan($value)
	{
		if ($GLOBALS['WP_DXP_PARAMS']['daysSinceLastVisit'] < $value) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * "Equal" test
	 * @return boolean
	 */
	public function comparatorEquals($value)
	{
		if ($GLOBALS['WP_DXP_PARAMS']['daysSinceLastVisit'] == $value) {
			return true;
		} else {
			return false;
		}
	}
}