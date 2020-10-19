<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Validation;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Url extends Contract\Validation {

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

		return false !== filter_var( $value, FILTER_VALIDATE_URL ) && preg_match( '@^https?+://@i', $value );
	}

	/**
	 * Get validate error message.
	 *
	 * @return string
	 */
	public static function get_message() {
		return __( 'Please enter a valid URL.', 'snow-monkey-forms' );
	}
}
