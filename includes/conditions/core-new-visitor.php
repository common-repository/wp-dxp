<?php
class Wp_Dxp_Condition_Core_New_Visitor extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_new_visitor';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'New Visit', 'wp-dxp' );

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

		return $value == 'false' ? $GLOBALS['WP_DXP_PARAMS']['returning_visitor'] : !$GLOBALS['WP_DXP_PARAMS']['returning_visitor'];

	}
}