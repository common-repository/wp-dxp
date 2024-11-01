<?php
class Wp_Dxp_Condition_Core_Visiting_Date extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_visiting_date';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'Date', 'wp-dxp' );

		$this->comparators = [
			'before' => _x( 'Before', 'Comparator', 'wp-dxp' ),
			'after'  => _x( 'After', 'Comparator', 'wp-dxp' ),
			'equals' => _x( 'Is', 'Comparator', 'wp-dxp' ),
		];

		$this->comparisonValues = [];

        $this->comparisonType = 'datepicker';
	}

	/**
	 * Test data against condition
	 * @param  string $comparator
	 * @return boolean
	 */
	public function matchesCriteria($comparator, $value, $action, $meta = [])
	{
		switch ($comparator) {
			case 'before':
				return $this->comparatorBefore($value);
			case 'after':
				return $this->comparatorAfter($value);
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
		$ruleStrToTime = date("Y-m-d", strtotime($value));
		$usersStrToTime = date("Y-m-d", strtotime($GLOBALS['WP_DXP_PARAMS']['users_current_timestamp']));

		if ($ruleStrToTime === $usersStrToTime) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * "Before" test
	 * @return boolean
	 */
	public function comparatorBefore($value)
	{

		$ruleStrToTime = date("Y-m-d", strtotime($value));
		$usersStrToTime = date("Y-m-d", strtotime($GLOBALS['WP_DXP_PARAMS']['users_current_timestamp']));

		if ($ruleStrToTime > $usersStrToTime) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * "After" test
	 * @return boolean
	 */
	public function comparatorAfter($value)
	{

		$ruleStrToTime = date("Y-m-d", strtotime($value));
		$usersStrToTime = date("Y-m-d", strtotime($GLOBALS['WP_DXP_PARAMS']['users_current_timestamp']));

		if ($ruleStrToTime < $usersStrToTime) {
			return true;
		} else {
			return false;
		}
	}
}