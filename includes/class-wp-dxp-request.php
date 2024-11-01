<?php
class Wp_Dxp_Request extends Wp_Dxp_Singleton {

	/**
	 * Constructor
	 */
	public function __construct() { }

	/**
	 * Validate WP's Nonce field
	 * @param  string $action
	 * @return boolean
	 */
	public function validateNonce($action)
	{
		$value = $this->post(WP_DXP_FORM_NONCE_FIELD_NAME);

		return $value && wp_verify_nonce($value, WP_DXP_FORM_NONCE_FIELD_ACTION);
	}

	/**
	 * Return value from $_GET variable, otherwise return default
	 * @param  string $key
	 * @param  mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return isset($_GET[$key]) ? sanitize_text_field($_GET[$key]) : $default;
	}

	/**
	 * Return value from $_POST variable, otherwise return default
	 * @param  string $key
	 * @param  mixed $default
	 * @return mixed
	 */
	public function post($key, $default = null)
	{
		if (isset($_POST[$key])) {
			if (is_array($_POST[$key])) {
				return $this->sanitizeArray($_POST[$key]);
			}

			return sanitize_text_field($_POST[$key]);
		}

		return $default;
	}

	/**
	 * Return value from submitted form, otherwise return default
	 * @param  string $key
	 * @param  mixed $default
	 * @return mixed
	 */
	public function form($key = null, $default = null)
	{
		$form = $this->post(WP_DXP_FORM_FIELD_PREFIX);

		if (is_null($key)) {
			return empty($form) ? $default : $form;
		}

		return isset($form[$key]) ? $form[$key] : $default;
	}

	/**
	 * Sanitise array
	 * @param  array  $array
	 * @return array
	 */
	protected function sanitizeArray(array $array) {
	    return filter_var($array, \FILTER_CALLBACK, ['options' => 'sanitize_text_field']);
	}

}