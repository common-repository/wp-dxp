<?php
class Wp_Dxp_Base_Condition extends Wp_Dxp_Singleton {

	public $description = '';

	public $category = '';

	public $measure_key;

	/**
	 * Standard comparators for a condition
	 * @var array
	 */
	protected $comparators;

	/**
	 * Potential values used for a comparison
	 * @var array
	 */
	protected $comparisonValues;

	/**
	 * Potential values used for a comparison Type
	 * @var array
	 */
	protected $comparisonType = 'select'; // select / text / datepicker

	/**
	 * Condition identifier - must be unique
	 * @var string
	 */
	public $identifier;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->comparators = [
			'equals'         => _x( 'Equals', 'Comparator', 'wp-dxp' ),
			'does_not_equal' => _x( 'Does Not Equal', 'Comparator', 'wp-dxp' ),
			'any'            => _x( 'Is any of', 'Comparator', 'wp-dxp' ),
		];
		$this->comparisonValues = [
			'true'  => _x( 'True', 'Comparison value', 'wp-dxp' ),
			'false' => _x( 'False', 'Comparison value', 'wp-dxp' ),
		];
	}

	/**
	 * Test data against condition
	 * @param  string $comparator
	 * @param  mixed $value
	 * @param  array $meta
	 * @return boolean
	 */
	public function matchesCriteria($comparator, $value, $action, $meta = []) { }

	/**
	 * Retrieve potential comparator values
	 * @return array
	 */
	public function getComparators()
	{
		return $this->comparators;
	}

	/**
	 * Retrieve potential comparison values
	 * @return array
	 */
	public function getComparisonValues()
	{
		return $this->comparisonValues;
	}

	/**
	 * Retrieve potential comparison types
	 * @return array
	 */
	public function getComparisonType()
	{
		return $this->comparisonType;
	}

	/**
	 * Convert to object (usually for json on page)
	 * @return stdClass
	 */
	public function toObject()
	{
		return (object) [
			'identifier' => $this->identifier,
			'comparators' => $this->comparators,
			'measureKey' => $this->measure_key,
			'comparisonValues' => $this->getComparisonValues(),
			'comparisonType' => $this->getComparisonType(),
		];
	}

	/**
	 * Check that the dependencies are met for the condition to be used
	 * @return boolean
	 */
	public function isUsable()
	{
		foreach ($this->dependencies() as $dependency) {
			if (!$dependency->verify()) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Array of dependencies for the condition to be used
	 * @return array
	 */
	public function dependencies()
	{
		return [];
	}
	
	/**
	 * Array of tag attributes to be included in WP DXP tag given the chosen condition
	 * @param  StdClass $condition
	 * @param  string $action
	 * @return array
	 */
	public function tagAttributes($condition, $action)
	{
		return [];
	}

}