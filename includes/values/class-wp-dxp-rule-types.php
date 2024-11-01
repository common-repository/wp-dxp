<?php
class Wp_Dxp_Rule_Types {

	public static $STANDARD = 'standard';
	public static $CUSTOM = 'custom';

	public static function getAll()
	{
		return [
			[
				'id' => self::$STANDARD,
				'name' => esc_html_x( 'Standard', 'Rule type', 'wp-dxp' ),
			],
			[
				'id' => self::$CUSTOM,
				'name' => esc_html_x( 'Custom', 'Rule type', 'wp-dxp' ),
			]
		];
	}

	public static function getName($id)
	{
		foreach (self::getAll() as $item) {
			if ($item['id'] == $id) {
				return $item['name'];
			}
		}

		return 'Unknown';
	}
}