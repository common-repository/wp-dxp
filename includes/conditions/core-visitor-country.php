<?php
use GeoIp2\Database\Reader;

class Wp_Dxp_Condition_Core_Visitor_Country extends Wp_Dxp_Base_Condition {

	public $identifier = 'core_visitor_country';

	public $category = 'Core';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->description = __( 'User Location', 'wp-dxp' );

		$this->comparators = [
			'equals'         => _x( 'Equals', 'Comparator', 'wp-dxp' ),
			'does_not_equal' => _x( 'Does Not Equal', 'Comparator', 'wp-dxp' ),
		];

		$this->comparisonValues = [
			'AF' => _x( "Afghanistan", 'countries', 'wp-dxp' ),
			'AX' => _x( "Åland Islands", 'countries', 'wp-dxp' ),
			'AL' => _x( "Albania", 'countries', 'wp-dxp' ),
			'DZ' => _x( "Algeria", 'countries', 'wp-dxp' ),
			'AS' => _x( "American Samoa", 'countries', 'wp-dxp' ),
			'AD' => _x( "Andorra", 'countries', 'wp-dxp' ),
			'AO' => _x( "Angola", 'countries', 'wp-dxp' ),
			'AI' => _x( "Anguilla", 'countries', 'wp-dxp' ),
			'AQ' => _x( "Antarctica", 'countries', 'wp-dxp' ),
			'AG' => _x( "Antigua and Barbuda", 'countries', 'wp-dxp' ),
			'AR' => _x( "Argentina", 'countries', 'wp-dxp' ),
			'AM' => _x( "Armenia", 'countries', 'wp-dxp' ),
			'AW' => _x( "Aruba", 'countries', 'wp-dxp' ),
			'AU' => _x( "Australia", 'countries', 'wp-dxp' ),
			'AT' => _x( "Austria", 'countries', 'wp-dxp' ),
			'AZ' => _x( "Azerbaijan", 'countries', 'wp-dxp' ),
			'BS' => _x( "Bahamas", 'countries', 'wp-dxp' ),
			'BH' => _x( "Bahrain", 'countries', 'wp-dxp' ),
			'BD' => _x( "Bangladesh", 'countries', 'wp-dxp' ),
			'BB' => _x( "Barbados", 'countries', 'wp-dxp' ),
			'BY' => _x( "Belarus", 'countries', 'wp-dxp' ),
			'BE' => _x( "Belgium", 'countries', 'wp-dxp' ),
			'BZ' => _x( "Belize", 'countries', 'wp-dxp' ),
			'BJ' => _x( "Benin", 'countries', 'wp-dxp' ),
			'BM' => _x( "Bermuda", 'countries', 'wp-dxp' ),
			'BT' => _x( "Bhutan", 'countries', 'wp-dxp' ),
			'BO' => _x( "Bolivia, Plurinational State of", 'countries', 'wp-dxp' ),
			'BA' => _x( "Bosnia and Herzegovina", 'countries', 'wp-dxp' ),
			'BW' => _x( "Botswana", 'countries', 'wp-dxp' ),
			'BV' => _x( "Bouvet Island", 'countries', 'wp-dxp' ),
			'BR' => _x( "Brazil", 'countries', 'wp-dxp' ),
			'IO' => _x( "British Indian Ocean Territory", 'countries', 'wp-dxp' ),
			'BN' => _x( "Brunei Darussalam", 'countries', 'wp-dxp' ),
			'BG' => _x( "Bulgaria", 'countries', 'wp-dxp' ),
			'BF' => _x( "Burkina Faso", 'countries', 'wp-dxp' ),
			'BI' => _x( "Burundi", 'countries', 'wp-dxp' ),
			'KH' => _x( "Cambodia", 'countries', 'wp-dxp' ),
			'CM' => _x( "Cameroon", 'countries', 'wp-dxp' ),
			'CA' => _x( "Canada", 'countries', 'wp-dxp' ),
			'CV' => _x( "Cape Verde", 'countries', 'wp-dxp' ),
			'KY' => _x( "Cayman Islands", 'countries', 'wp-dxp' ),
			'CF' => _x( "Central African Republic", 'countries', 'wp-dxp' ),
			'TD' => _x( "Chad", 'countries', 'wp-dxp' ),
			'CL' => _x( "Chile", 'countries', 'wp-dxp' ),
			'CN' => _x( "China", 'countries', 'wp-dxp' ),
			'CX' => _x( "Christmas Island", 'countries', 'wp-dxp' ),
			'CC' => _x( "Cocos (Keeling) Islands", 'countries', 'wp-dxp' ),
			'CO' => _x( "Colombia", 'countries', 'wp-dxp' ),
			'KM' => _x( "Comoros", 'countries', 'wp-dxp' ),
			'CG' => _x( "Congo", 'countries', 'wp-dxp' ),
			'CD' => _x( "Congo, Democratic Republic", 'countries', 'wp-dxp' ),
			'CK' => _x( "Cook Islands", 'countries', 'wp-dxp' ),
			'CR' => _x( "Costa Rica", 'countries', 'wp-dxp' ),
			'CI' => _x( "Côte d'Ivoire", 'countries', 'wp-dxp' ),
			'HR' => _x( "Croatia", 'countries', 'wp-dxp' ),
			'CU' => _x( "Cuba", 'countries', 'wp-dxp' ),
			'CY' => _x( "Cyprus", 'countries', 'wp-dxp' ),
			'CZ' => _x( "Czech Republic", 'countries', 'wp-dxp' ),
			'DK' => _x( "Denmark", 'countries', 'wp-dxp' ),
			'DJ' => _x( "Djibouti", 'countries', 'wp-dxp' ),
			'DM' => _x( "Dominica", 'countries', 'wp-dxp' ),
			'DO' => _x( "Dominican Republic", 'countries', 'wp-dxp' ),
			'EC' => _x( "Ecuador", 'countries', 'wp-dxp' ),
			'EG' => _x( "Egypt", 'countries', 'wp-dxp' ),
			'SV' => _x( "El Salvador", 'countries', 'wp-dxp' ),
			'GQ' => _x( "Equatorial Guinea", 'countries', 'wp-dxp' ),
			'ER' => _x( "Eritrea", 'countries', 'wp-dxp' ),
			'EE' => _x( "Estonia", 'countries', 'wp-dxp' ),
			'ET' => _x( "Ethiopia", 'countries', 'wp-dxp' ),
			'FK' => _x( "Falkland Islands (Malvinas)", 'countries', 'wp-dxp' ),
			'FO' => _x( "Faroe Islands", 'countries', 'wp-dxp' ),
			'FJ' => _x( "Fiji", 'countries', 'wp-dxp' ),
			'FI' => _x( "Finland", 'countries', 'wp-dxp' ),
			'FR' => _x( "France", 'countries', 'wp-dxp' ),
			'GF' => _x( "French Guiana", 'countries', 'wp-dxp' ),
			'PF' => _x( "French Polynesia", 'countries', 'wp-dxp' ),
			'TF' => _x( "French Southern Territories", 'countries', 'wp-dxp' ),
			'GA' => _x( "Gabon", 'countries', 'wp-dxp' ),
			'GM' => _x( "Gambia", 'countries', 'wp-dxp' ),
			'GE' => _x( "Georgia", 'countries', 'wp-dxp' ),
			'DE' => _x( "Germany", 'countries', 'wp-dxp' ),
			'GH' => _x( "Ghana", 'countries', 'wp-dxp' ),
			'GI' => _x( "Gibraltar", 'countries', 'wp-dxp' ),
			'GR' => _x( "Greece", 'countries', 'wp-dxp' ),
			'GL' => _x( "Greenland", 'countries', 'wp-dxp' ),
			'GD' => _x( "Grenada", 'countries', 'wp-dxp' ),
			'GP' => _x( "Guadeloupe", 'countries', 'wp-dxp' ),
			'GU' => _x( "Guam", 'countries', 'wp-dxp' ),
			'GT' => _x( "Guatemala", 'countries', 'wp-dxp' ),
			'GG' => _x( "Guernsey", 'countries', 'wp-dxp' ),
			'GN' => _x( "Guinea", 'countries', 'wp-dxp' ),
			'GW' => _x( "Guinea-Bissau", 'countries', 'wp-dxp' ),
			'GY' => _x( "Guyana", 'countries', 'wp-dxp' ),
			'HT' => _x( "Haiti", 'countries', 'wp-dxp' ),
			'HM' => _x( "Heard Island & Mcdonald Islands", 'countries', 'wp-dxp' ),
			'VA' => _x( "Holy See (Vatican City State)", 'countries', 'wp-dxp' ),
			'HN' => _x( "Honduras", 'countries', 'wp-dxp' ),
			'HK' => _x( "Hong Kong", 'countries', 'wp-dxp' ),
			'HU' => _x( "Hungary", 'countries', 'wp-dxp' ),
			'IS' => _x( "Iceland", 'countries', 'wp-dxp' ),
			'IN' => _x( "India", 'countries', 'wp-dxp' ),
			'ID' => _x( "Indonesia", 'countries', 'wp-dxp' ),
			'IR' => _x( "Iran, Islamic Republic Of", 'countries', 'wp-dxp' ),
			'IQ' => _x( "Iraq", 'countries', 'wp-dxp' ),
			'IE' => _x( "Ireland", 'countries', 'wp-dxp' ),
			'IM' => _x( "Isle Of Man", 'countries', 'wp-dxp' ),
			'IL' => _x( "Israel", 'countries', 'wp-dxp' ),
			'IT' => _x( "Italy", 'countries', 'wp-dxp' ),
			'JM' => _x( "Jamaica", 'countries', 'wp-dxp' ),
			'JP' => _x( "Japan", 'countries', 'wp-dxp' ),
			'JE' => _x( "Jersey", 'countries', 'wp-dxp' ),
			'JO' => _x( "Jordan", 'countries', 'wp-dxp' ),
			'KZ' => _x( "Kazakhstan", 'countries', 'wp-dxp' ),
			'KE' => _x( "Kenya", 'countries', 'wp-dxp' ),
			'KI' => _x( "Kiribati", 'countries', 'wp-dxp' ),
			'KP' => _x( "Korea, Democratic People's Republic of", 'countries', 'wp-dxp' ),
			'KR' => _x( "Korea, Republic of", 'countries', 'wp-dxp' ),
			'KW' => _x( "Kuwait", 'countries', 'wp-dxp' ),
			'KG' => _x( "Kyrgyzstan", 'countries', 'wp-dxp' ),
			'LA' => _x( "Lao People's Democratic Republic", 'countries', 'wp-dxp' ),
			'LV' => _x( "Latvia", 'countries', 'wp-dxp' ),
			'LB' => _x( "Lebanon", 'countries', 'wp-dxp' ),
			'LS' => _x( "Lesotho", 'countries', 'wp-dxp' ),
			'LR' => _x( "Liberia", 'countries', 'wp-dxp' ),
			'LY' => _x( "Libyan Arab Jamahiriya", 'countries', 'wp-dxp' ),
			'LI' => _x( "Liechtenstein", 'countries', 'wp-dxp' ),
			'LT' => _x( "Lithuania", 'countries', 'wp-dxp' ),
			'LU' => _x( "Luxembourg", 'countries', 'wp-dxp' ),
			'MO' => _x( "Macao", 'countries', 'wp-dxp' ),
			'MK' => _x( "Macedonia, the former Yugoslav Republic of", 'countries', 'wp-dxp' ),
			'MG' => _x( "Madagascar", 'countries', 'wp-dxp' ),
			'MW' => _x( "Malawi", 'countries', 'wp-dxp' ),
			'MY' => _x( "Malaysia", 'countries', 'wp-dxp' ),
			'MV' => _x( "Maldives", 'countries', 'wp-dxp' ),
			'ML' => _x( "Mali", 'countries', 'wp-dxp' ),
			'MT' => _x( "Malta", 'countries', 'wp-dxp' ),
			'MH' => _x( "Marshall Islands", 'countries', 'wp-dxp' ),
			'MQ' => _x( "Martinique", 'countries', 'wp-dxp' ),
			'MR' => _x( "Mauritania", 'countries', 'wp-dxp' ),
			'MU' => _x( "Mauritius", 'countries', 'wp-dxp' ),
			'YT' => _x( "Mayotte", 'countries', 'wp-dxp' ),
			'MX' => _x( "Mexico", 'countries', 'wp-dxp' ),
			'FM' => _x( "Micronesia, Federated States Of", 'countries', 'wp-dxp' ),
			'MD' => _x( "Moldova, Republic of", 'countries', 'wp-dxp' ),
			'MC' => _x( "Monaco", 'countries', 'wp-dxp' ),
			'MN' => _x( "Mongolia", 'countries', 'wp-dxp' ),
			'ME' => _x( "Montenegro", 'countries', 'wp-dxp' ),
			'MS' => _x( "Montserrat", 'countries', 'wp-dxp' ),
			'MA' => _x( "Morocco", 'countries', 'wp-dxp' ),
			'MZ' => _x( "Mozambique", 'countries', 'wp-dxp' ),
			'MM' => _x( "Myanmar", 'countries', 'wp-dxp' ),
			'NA' => _x( "Namibia", 'countries', 'wp-dxp' ),
			'NR' => _x( "Nauru", 'countries', 'wp-dxp' ),
			'NP' => _x( "Nepal", 'countries', 'wp-dxp' ),
			'NL' => _x( "Netherlands", 'countries', 'wp-dxp' ),
			'AN' => _x( "Netherlands Antilles", 'countries', 'wp-dxp' ),
			'NC' => _x( "New Caledonia", 'countries', 'wp-dxp' ),
			'NZ' => _x( "New Zealand", 'countries', 'wp-dxp' ),
			'NI' => _x( "Nicaragua", 'countries', 'wp-dxp' ),
			'NE' => _x( "Niger", 'countries', 'wp-dxp' ),
			'NG' => _x( "Nigeria", 'countries', 'wp-dxp' ),
			'NU' => _x( "Niue", 'countries', 'wp-dxp' ),
			'NF' => _x( "Norfolk Island", 'countries', 'wp-dxp' ),
			'MP' => _x( "Northern Mariana Islands", 'countries', 'wp-dxp' ),
			'NO' => _x( "Norway", 'countries', 'wp-dxp' ),
			'OM' => _x( "Oman", 'countries', 'wp-dxp' ),
			'PK' => _x( "Pakistan", 'countries', 'wp-dxp' ),
			'PW' => _x( "Palau", 'countries', 'wp-dxp' ),
			'PS' => _x( "Palestinian Territory, Occupied", 'countries', 'wp-dxp' ),
			'PA' => _x( "Panama", 'countries', 'wp-dxp' ),
			'PG' => _x( "Papua New Guinea", 'countries', 'wp-dxp' ),
			'PY' => _x( "Paraguay", 'countries', 'wp-dxp' ),
			'PE' => _x( "Peru", 'countries', 'wp-dxp' ),
			'PH' => _x( "Philippines", 'countries', 'wp-dxp' ),
			'PN' => _x( "Pitcairn", 'countries', 'wp-dxp' ),
			'PL' => _x( "Poland", 'countries', 'wp-dxp' ),
			'PT' => _x( "Portugal", 'countries', 'wp-dxp' ),
			'PR' => _x( "Puerto Rico", 'countries', 'wp-dxp' ),
			'QA' => _x( "Qatar", 'countries', 'wp-dxp' ),
			'RE' => _x( "Réunion", 'countries', 'wp-dxp' ),
			'RO' => _x( "Romania", 'countries', 'wp-dxp' ),
			'RU' => _x( "Russian Federation", 'countries', 'wp-dxp' ),
			'RW' => _x( "Rwanda", 'countries', 'wp-dxp' ),
			'BL' => _x( "Saint Barthélemy", 'countries', 'wp-dxp' ),
			'SH' => _x( "Saint Helena, Ascension and Tristan da Cunha", 'countries', 'wp-dxp' ),
			'KN' => _x( "Saint Kitts and Nevis", 'countries', 'wp-dxp' ),
			'LC' => _x( "Saint Lucia", 'countries', 'wp-dxp' ),
			'MF' => _x( "Saint Martin (French part)", 'countries', 'wp-dxp' ),
			'PM' => _x( "Saint Pierre and Miquelon", 'countries', 'wp-dxp' ),
			'VC' => _x( "Saint Vincent and the Grenadines", 'countries', 'wp-dxp' ),
			'WS' => _x( "Samoa", 'countries', 'wp-dxp' ),
			'SM' => _x( "San Marino", 'countries', 'wp-dxp' ),
			'ST' => _x( "Sao Tome And Principe", 'countries', 'wp-dxp' ),
			'SA' => _x( "Saudi Arabia", 'countries', 'wp-dxp' ),
			'SN' => _x( "Senegal", 'countries', 'wp-dxp' ),
			'RS' => _x( "Serbia", 'countries', 'wp-dxp' ),
			'SC' => _x( "Seychelles", 'countries', 'wp-dxp' ),
			'SL' => _x( "Sierra Leone", 'countries', 'wp-dxp' ),
			'SG' => _x( "Singapore", 'countries', 'wp-dxp' ),
			'SK' => _x( "Slovakia", 'countries', 'wp-dxp' ),
			'SI' => _x( "Slovenia", 'countries', 'wp-dxp' ),
			'SB' => _x( "Solomon Islands", 'countries', 'wp-dxp' ),
			'SO' => _x( "Somalia", 'countries', 'wp-dxp' ),
			'ZA' => _x( "South Africa", 'countries', 'wp-dxp' ),
			'GS' => _x( "South Georgia And Sandwich Isl.", 'countries', 'wp-dxp' ),
			'ES' => _x( "Spain", 'countries', 'wp-dxp' ),
			'LK' => _x( "Sri Lanka", 'countries', 'wp-dxp' ),
			'SD' => _x( "Sudan", 'countries', 'wp-dxp' ),
			'SR' => _x( "Suriname", 'countries', 'wp-dxp' ),
			'SJ' => _x( "Svalbard And Jan Mayen", 'countries', 'wp-dxp' ),
			'SZ' => _x( "Swaziland", 'countries', 'wp-dxp' ),
			'SE' => _x( "Sweden", 'countries', 'wp-dxp' ),
			'CH' => _x( "Switzerland", 'countries', 'wp-dxp' ),
			'SY' => _x( "Syrian Arab Republic", 'countries', 'wp-dxp' ),
			'TW' => _x( "Taiwan", 'countries', 'wp-dxp' ),
			'TJ' => _x( "Tajikistan", 'countries', 'wp-dxp' ),
			'TZ' => _x( "Tanzania, United Republic of", 'countries', 'wp-dxp' ),
			'TH' => _x( "Thailand", 'countries', 'wp-dxp' ),
			'TL' => _x( "Timor-Leste", 'countries', 'wp-dxp' ),
			'TG' => _x( "Togo", 'countries', 'wp-dxp' ),
			'TK' => _x( "Tokelau", 'countries', 'wp-dxp' ),
			'TO' => _x( "Tonga", 'countries', 'wp-dxp' ),
			'TT' => _x( "Trinidad And Tobago", 'countries', 'wp-dxp' ),
			'TN' => _x( "Tunisia", 'countries', 'wp-dxp' ),
			'TR' => _x( "Turkey", 'countries', 'wp-dxp' ),
			'TM' => _x( "Turkmenistan", 'countries', 'wp-dxp' ),
			'TC' => _x( "Turks And Caicos Islands", 'countries', 'wp-dxp' ),
			'TV' => _x( "Tuvalu", 'countries', 'wp-dxp' ),
			'UG' => _x( "Uganda", 'countries', 'wp-dxp' ),
			'UA' => _x( "Ukraine", 'countries', 'wp-dxp' ),
			'AE' => _x( "United Arab Emirates", 'countries', 'wp-dxp' ),
			'GB' => _x( "United Kingdom", 'countries', 'wp-dxp' ),
			'US' => _x( "United States", 'countries', 'wp-dxp' ),
			'UM' => _x( "United States Outlying Islands", 'countries', 'wp-dxp' ),
			'UY' => _x( "Uruguay", 'countries', 'wp-dxp' ),
			'UZ' => _x( "Uzbekistan", 'countries', 'wp-dxp' ),
			'VU' => _x( "Vanuatu", 'countries', 'wp-dxp' ),
			'VE' => _x( "Venezuela, Bolivarian Republic of", 'countries', 'wp-dxp' ),
			'VN' => _x( "Viet Nam", 'countries', 'wp-dxp' ),
			'VG' => _x( "Virgin Islands, British", 'countries', 'wp-dxp' ),
			'VI' => _x( "Virgin Islands, U.S.", 'countries', 'wp-dxp' ),
			'WF' => _x( "Wallis and Futuna", 'countries', 'wp-dxp' ),
			'EH' => _x( "Western Sahara", 'countries', 'wp-dxp' ),
			'YE' => _x( "Yemen", 'countries', 'wp-dxp' ),
			'ZM' => _x( "Zambia", 'countries', 'wp-dxp' ),
			'ZW' => _x( "Zimbabwe", 'countries', 'wp-dxp' ),
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
			case 'does_not_equal':
				return $this->comparatorDoesNotEqual($value);
			case 'any':
				return $this->comparatorAny($value);
		}

		return false;
	}

	/**
	 * "Equal" test
	 * @return boolean
	 */
	public function comparatorEquals($value)
	{
		$reader = new Reader(WP_DXP_MAXMIND_DB);

		try {
			$record = $reader->country($_SERVER['REMOTE_ADDR']);
		} catch (Exception $e) {
		    return false;
		}

		return $record->country->isoCode == $value;
	}

	/**
	 * "Does not equal" test
	 * @return boolean
	 */
	public function comparatorDoesNotEqual($value)
	{
		$reader = new Reader(WP_DXP_MAXMIND_DB);

		try {
			//$record = $reader->city($_SERVER['REMOTE_ADDR']);
			$record = $reader->country($_SERVER['REMOTE_ADDR']);
		} catch (Exception $e) {
		    //echo 'Caught exception: ',  $e->getMessage(), "\n";
		    return false;
		}

		return $record->country->isoCode != $value;
	}

	/**
	 * "Any" test
	 * @return boolean
	 */
	public function comparatorAny($value)
	{
		$reader = new Reader(WP_DXP_MAXMIND_DB);

		try {
			$record = $reader->city($_SERVER['REMOTE_ADDR']);
		} catch (Exception $e) {
		    //echo 'Caught exception: ',  $e->getMessage(), "\n";
		    return false;
		}

		return in_array($record->country->isoCode, $value);
	}
}