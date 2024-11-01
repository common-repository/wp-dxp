<?php
class Wp_Dxp_Condition_Core_Users_Visiting_Time extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_users_visiting_time';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'Visit Period', 'wp-dxp' );

		$this->comparators = [
			'equals' => _x( 'Equal To', 'Comparator', 'wp-dxp' ),
		];

		$this->comparisonValues = [
			'morning'   => _x( 'Morning', 'Comparison value', 'wp-dxp' ),
			'afternoon' => _x( 'Afternoon', 'Comparison value', 'wp-dxp' ),
			'evening'   => _x( 'Evening', 'Comparison value', 'wp-dxp' ),
			'nighttime' => _x( 'Nighttime', 'Comparison value', 'wp-dxp' ),
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
		if ($GLOBALS['WP_DXP_PARAMS']['time_of_day'] == $value) {
			return true;
		} else {
			return false;
		}
	}
}