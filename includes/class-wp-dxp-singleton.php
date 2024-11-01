<?php
class Wp_Dxp_Singleton {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Singleton getInstance class
	 * @return Wp_Dxp_Singleton
	 */
	public static function getInstance()
	{
        static $instances = array();

        $calledClass = get_called_class();

        if (!isset($instances[$calledClass]))
        {
            $instances[$calledClass] = new $calledClass();
        }

        return $instances[$calledClass];
	}
}