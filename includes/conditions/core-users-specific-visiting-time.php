<?php
class Wp_Dxp_Condition_Core_Users_Specific_Visiting_Time extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_users_specific_visiting_time';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'Visit Time', 'wp-dxp' );

		$this->comparators = [
			'before' => _x( 'Before', 'Comparator', 'wp-dxp' ),
			'after'  => _x( 'After', 'Comparator', 'wp-dxp' ),
		];

		$this->comparisonValues = [
			'00:00:00' => '00:00',
			'00:30:00' => '00:30',
			'01:00:00' => '01:00',
			'01:30:00' => '01:30',
			'02:00:00' => '02:00',
			'02:30:00' => '02:30',
			'03:00:00' => '03:00',
			'03:30:00' => '03:30',
			'04:00:00' => '04:00',
			'04:30:00' => '04:30',
			'05:00:00' => '05:00',
			'05:30:00' => '05:30',
			'06:00:00' => '06:00',
			'06:30:00' => '06:30',
			'07:00:00' => '07:00',
			'07:30:00' => '07:30',
			'08:00:00' => '08:00',
			'08:30:00' => '08:30',
			'09:00:00' => '09:00',
			'09:30:00' => '09:30',
			'10:00:00' => '10:00',
			'10:30:00' => '10:30',
			'11:00:00' => '11:00',
			'11:30:00' => '11:30',
			'12:00:00' => '12:00',
			'12:30:00' => '12:30',
			'13:00:00' => '13:00',
			'13:30:00' => '13:30',
			'14:00:00' => '14:00',
			'14:30:00' => '14:30',
			'15:00:00' => '15:00',
			'15:30:00' => '15:30',
			'16:00:00' => '16:00',
			'16:30:00' => '16:30',
			'17:00:00' => '17:00',
			'17:30:00' => '17:30',
			'18:00:00' => '18:00',
			'18:30:00' => '18:30',
			'19:00:00' => '19:00',
			'19:30:00' => '19:30',
			'20:00:00' => '20:00',
			'20:30:00' => '20:30',
			'21:00:00' => '21:00',
			'21:30:00' => '21:30',
			'22:00:00' => '22:00',
			'22:30:00' => '22:30',
			'23:00:00' => '23:00',
			'23:30:00' => '23:30',
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
			case 'before':
				return $this->comparatorBefore($value);
			case 'after':
				return $this->comparatorAfter($value);
		}

		return false;
	}

	/**
	 * "Before" test
	 * @return boolean
	 */
	public function comparatorBefore($value)
	{
		$ruleStrToTime = strtotime($value);
		$usersStrToTime = strtotime($GLOBALS['WP_DXP_PARAMS']['users_current_time']);

		if ($usersStrToTime < $ruleStrToTime) {
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

		$ruleStrToTime = strtotime($value);
		$usersStrToTime = strtotime($GLOBALS['WP_DXP_PARAMS']['users_current_time']);

		if ($usersStrToTime > $ruleStrToTime) {
			return true;
		} else {
			return false;
		}
	}
}