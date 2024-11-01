<?php
class Wp_Dxp_Condition_Core_Users_Device_Type extends Wp_Dxp_Base_Condition {

	public $description = "Device Type";

	public $identifier = 'core_users_device_type';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'Device Type', 'wp-dxp' );

		$this->comparators = [
			'equals' => _x( 'Equal To', 'Comparator', 'wp-dxp' ),
		];

		$this->comparisonValues = [
			'mobile'  => __( 'Mobile', 'wp-dxp' ),
			'tablet'  => __( 'Tablet', 'wp-dxp' ),
			'desktop' => __( 'Desktop', 'wp-dxp' ),
			'ios'     => __( 'iOS device', 'wp-dxp' ),
			'android' => __( 'Android device', 'wp-dxp' ),
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
		if (in_array($value, $GLOBALS['WP_DXP_PARAMS']['users_device_type'])) {
			return true;
		} else {
			return false;
		}
	}
}