<?php
class Wp_Dxp_Condition_Core_Time_Elapsed extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_time_elapsed';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'Time on current page', 'wp-dxp' );

		$this->comparators = [
			'equals' => _x( 'Is', 'Comparator', 'wp-dxp' ),
		];
		
		$now = time();
		// Create an array of keys as set number of seconds with empty values.
		$this->comparisonValues = array_fill_keys( [ 5, 10, 15, 30, 60, 120, 180, 240, 300, 600, 900, 1200, 1800, 2700, 3600 ], '' );
		// Loop over array and modify values to set translatable string.
		array_walk( $this->comparisonValues, function( &$item, $key ) use ( $now ) {
			// Use WP to set the secs/mins/hours/days.
			$item = human_time_diff( $now, $now + $key );
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
			case 'equals':
				return $this->comparatorEquals($value, $action);
		}

		return false;
	}

	/**
	 * "Equal" test
	 * @return boolean
	 */
	public function comparatorEquals($value, $action)
	{
		return $action === 'show';
	}

	/**
	 * Array of tag attributes to be included in WP DXP tag given the chosen condition
	 * @param  StdClass $condition
	 * @param  string $action
	 * @return array
	 */
	public function tagAttributes($condition, $action)
	{
		return [($action === 'show' ? 'delayed' : 'lifetime') => $condition->value];
	}
}