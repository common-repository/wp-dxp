<?php
class Wp_Dxp_Base_Dependency extends Wp_Dxp_Singleton {

	protected $description;

	protected $successMessage;

	protected $failureMessage;

	/**
	 * Constructor
	 */
	public function __construct($description, $successMessage, $failureMessage)
	{
		$this->description = $description;
		$this->successMessage = $successMessage;
		$this->failureMessage = $failureMessage;
	}

	/**
	 * Execute test to check if dependency is met
	 * @return boolean
	 */
	public function verify()
	{
		return false;
	}

	public function getFailureMessage()
	{
		return $this->failureMessage;
	}

}