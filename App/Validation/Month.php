<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Validation;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Month extends Contract\Validation {

	/**
	 * Validate.
	 *
	 * @param string $value The posted value.
	 * @return boolean True when correct.
	 */
	public static function validate( $value ) {
		if ( is_null( $value ) || '' === $value ) {
			return true;
		}

		return false !== filter_var(
			$value,
			FILTER_VALIDATE_REGEXP,
			array(
				'options' => array(
					'regexp' => '/^\d{4}-?\d{2}$/',
				),
			)
		);
	}

	/**
	 * Get validate error message.
	 *
	 * @return string
	 */
	public static function get_message() {
		return __( 'Please enter a valid month.', 'snow-monkey-forms' );
	}
}
