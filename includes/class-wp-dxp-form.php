<?php
class Wp_Dxp_Form {

	/**
	 * Wordpress Nonce field
	 * @param  string $action
	 * @return string
	 */
	public static function wpNonceField($action)
	{
		return wp_nonce_field($action, WP_DXP_FORM_NONCE_FIELD_NAME, true, false);
	}

	/**
	 * Hidden input field
	 * @param  string $name
	 * @param  mixed $value
	 * @param  string $attributes
	 * @return string
	 */
	public static function hidden($name, $value, $attributes = null)
	{
		return self::input('hidden', $name, $value, $attributes);
	}

	/**
	 * Text input field
	 * @param  string $name
	 * @param  mixed $value
	 * @param  string $attributes
	 * @return string
	 */
	public static function text($name, $value, $attributes = null)
	{
		return self::input('text', $name, $value, $attributes);
	}

	/**
	 * Datepicker input field
	 * @param  string $name
	 * @param  mixed $value
	 * @param  string $attributes
	 * @return string
	 */
	public static function datepicker($name, $value, $attributes = null)
	{
		return self::input('text', $name, $value, $attributes);
	}

	/**
	 * Select field
	 * @param  string $name
	 * @param  array $options
	 * @param  mixed $value
	 * @param  string $attributes
	 * @return string
	 */
	public static function select($name, $options, $value = null, $attributes = null)
	{
		$name = strpos($name, '[') === false ? WP_DXP_FORM_FIELD_PREFIX . "[{$name}]" : WP_DXP_FORM_FIELD_PREFIX . "{$name}";

		$html = "<select name='{$name}' {$attributes}>";

		foreach ($options as $k1 => $v1) {
			if (is_array($v1)) {
				$html .= "<optgroup label='{$k1}'>";

				foreach ($v1 as $k2 => $v2) {
					if (is_array($value)) {
						$selected = in_array($k2, $value) ? ' selected="selected"' : '';
					} else {
						$selected = $value == $k2 ? ' selected="selected"' : '';
					}

					$html .= "<option value='{$k2}'{$selected}>{$v2}</option>";
				}

				$html .= "</optgroup>";
			} else {

				if (is_array($value)) {
					$selected = in_array($k1, $value) ? ' selected="selected"' : '';
				} else {
					$selected = $value == $k1 ? ' selected="selected"' : '';
				}

				$html .= "<option value='{$k1}'{$selected}>{$v1}</option>";
			}

		}

		$html .= '</select>';

		return $html;
	}

	/**
	 * Input field
	 * @param  string $type
	 * @param  string $name
	 * @param  mixed $value
	 * @param  string $attributes
	 * @return string
	 */
	protected static function input($type, $name, $value = null, $attributes = null)
	{
		$name = strpos($name, '[') === false ? WP_DXP_FORM_FIELD_PREFIX . "[{$name}]" : WP_DXP_FORM_FIELD_PREFIX . "{$name}";

		return "<input type='{$type}' name='{$name}' value='{$value}' {$attributes} />";
	}

}