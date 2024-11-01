<?php
class Wp_Dxp_Conditions {

	protected static $conditions = [
		'core_visitor_country',
		'core_is_logged_in_user',
		'core_time_elapsed',
		'core_new_visitor',
		'core_users_last_visit',
		'core_users_visiting_time',
		'core_users_specific_visiting_time',
		'core_users_device_type',
		'core_users_role',
		'core_visiting_date',
		'core_query_string',
		'core_referrer',
		'core_cookie',
	];

	/**
	 * Get Rule class for specified identifier
	 * @param  string $identifier
	 * @return Wp_Dxp_Base_Condition
	 */
	public static function getClass($identifier)
	{
		switch ($identifier) {
			case 'core_visitor_country':
				return Wp_Dxp_Condition_Core_Visitor_Country::getInstance();
			case 'core_is_logged_in_user':
				return Wp_Dxp_Condition_Core_Is_Logged_In_User::getInstance();
			case 'core_time_elapsed':
				return Wp_Dxp_Condition_Core_Time_Elapsed::getInstance();
			case 'core_new_visitor':
				return Wp_Dxp_Condition_Core_New_Visitor::getInstance();
			case 'core_users_last_visit':
				return Wp_Dxp_Condition_Core_Users_Last_Visit::getInstance();
			case 'core_users_visiting_time':
				return Wp_Dxp_Condition_Core_Users_Visiting_Time::getInstance();
			case 'core_users_specific_visiting_time':
				return Wp_Dxp_Condition_Core_Users_Specific_Visiting_Time::getInstance();
			case 'core_users_device_type':
				return Wp_Dxp_Condition_Core_Users_Device_Type::getInstance();
			case 'core_users_role':
				return Wp_Dxp_Condition_Core_Users_Role::getInstance();
			case 'core_visiting_date':
				return Wp_Dxp_Condition_Core_Visiting_Date::getInstance();
			case 'core_query_string':
				return Wp_Dxp_Condition_Core_Query_String::getInstance();
			case 'core_referrer':
				return Wp_Dxp_Condition_Core_Referrer::getInstance();
			case 'core_cookie':
				return Wp_Dxp_Condition_Core_Cookie::getInstance();
		}

		return null;
	}

	/**
	 * List
	 * @return array
	 */
	public static function groupedList()
	{
		$return = [];

		foreach (self::$conditions as $identifier) {

			$class = self::getClass($identifier);

			if (count($class->dependencies()) > 0) {
				foreach ($class->dependencies() as $dependency) {
					if (!$dependency->verify()) {
						continue 2;
					}
				}
			}

			$category = self::getClassCategory($identifier);
			$description = self::getClassDescription($identifier);

			$return[$category][$identifier] = $description;
		}

		return $return;
	}

	/**
	 * List
	 * @return array
	 */
	public static function list()
	{
		$return = [];

		foreach (self::$conditions as $identifier) {

			$class = self::getClass($identifier);

			if (count($class->dependencies()) > 0) {
				foreach ($class->dependencies() as $dependency) {
					if (!$dependency->verify()) {
						continue;
					}
				}
			}

			$description = self::getClassDescription($identifier);

			$return[$identifier] = $description;
		}

		return $return;
	}

	/**
	 * Get Rule class description for specified identifier
	 * @param  string $identifier
	 * @return string
	 */
	public static function getClassDescription($identifier)
	{
		$class = self::getClass($identifier);

		return $class->description ?? _x( 'Unknown', 'Class description', 'wp-dxp' );
	}

	/**
	 * Get Rule class category for specified identifier
	 * @param  string $identifier
	 * @return string
	 */
	public static function getClassCategory($identifier)
	{
		$class = self::getClass($identifier);

		return $class->category ?? _x( 'Misc', 'Class category', 'wp-dxp' );
	}

	/**
	 * Get list of comparators for specified condition identifier
	 * @param  string $identifier
	 * @return array
	 */
	public static function getComparatorList($identifier)
	{
		$ruleClass = self::getClass($identifier);

		if ($ruleClass) {
			return $ruleClass->getComparators();
		}

		return [];
	}

	/**
	 * Get list of comparison values for specified condition identifier
	 * @param  string $identifier
	 * @return array
	 */
	public static function getComparisonValues($identifier)
	{
		$ruleClass = self::getClass($identifier);

		if ($ruleClass) {
			return $ruleClass->getComparisonValues();
		}

		return [];
	}

	/**
	 * Get list of comparators for default condition
	 * @return array
	 */
	public static function getDefaultComparatorList()
	{
		$list = self::list();

		$firstIdentifier = array_key_first($list);

		return self::getComparatorList($firstIdentifier);
	}

	/**
	 * Get list of comparison values for default condition
	 * @return array
	 */
	public static function getDefaultComparisonValues()
	{
		$list = self::list();

		$firstIdentifier = array_key_first($list);

		return self::getComparisonValues($firstIdentifier);
	}

	/**
	 * Return JSON string with all conditions and their data, used for populating form select fields in WP-Admin
	 * @return string
	 */
	public static function toJson()
	{
		$conditions = self::list();

		$return = [];
		foreach ($conditions as $identifier => $condition) {
			$conditionClass = self::getClass($identifier);

			$return[$identifier] = $conditionClass->toObject();
		}

		return json_encode($return);
	}
}
