<?php
class Wp_Dxp_Condition_Core_Query_String extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_query_string';

	public $category = 'Core';

	public $measure_key = 'key_name';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'Query String', 'wp-dxp' );

		$this->comparators = [
			'equals'           => _x( 'Equals', 'Comparator', 'wp-dxp' ),
			'does_not_equal'   => _x( 'Does Not Equal', 'Comparator', 'wp-dxp' ),
			'contains'         => _x( 'Contains', 'Comparator', 'wp-dxp' ),
			'does_not_contain' => _x( 'Does Not Contain', 'Comparator', 'wp-dxp' ),
			'key_value' => _x( 'Key/Value', 'Comparator', 'wp-dxp' )
		];

		$this->comparisonValues = [];

		$this->comparisonType = 'text';
	}

	/**
	 * Test data against condition
	 * @param  string $comparator
	 * @return booleanfield-value-select
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
            case 'key_value':
                return $this->comparatorKeyValue($value, $meta->key_name);
		}

		return false;
	}

	/**
	 * "Equal" test
	 * @return boolean
	 */
	public function comparatorEquals($value)
	{
        $the_query_string = $this->getURLQueryString();

		if ($the_query_string === $value) {
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

        $the_query_string = $this->getURLQueryString();

		if ($the_query_string !== $value) {
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
		$the_query_string = $this->getURLQueryString();

		return (str_contains($the_query_string, $value));
	}

	/**
	 * "Does not contain" test
	 * @return boolean
	 */
	public function comparatorDoesNotContain($value)
	{
		$the_query_string = $this->getURLQueryString();

		return (!str_contains($the_query_string, $value));
	}

    /**
     * "Key/Value" test
     * @return boolean
     */
    public function comparatorKeyValue($value, $key)
    {
	    $queryString = parse_url($this->getURLQueryString(), PHP_URL_QUERY);
	    parse_str($queryString, $params);

	    if ( !empty($params) && isset($params[$key]) && $params[$key] == $value ) {
		    return true;
	    }

		return false;
    }

    /**
	 * Get the current query string from the url
	 * @return array
	 */
	public function getURLQueryString()
	{
        return $GLOBALS['WP_DXP_PARAMS']['url_query_string'];
	}
}