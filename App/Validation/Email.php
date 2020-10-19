<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Validation;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Email extends Contract\Validation {

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

		return filter_var( $value, FILTER_VALIDATE_EMAIL )
			&& preg_match( '/@([\w.-]++)\z/', $value, $matches )
			&& (
				checkdnsrr( $matches[1], 'MX' )
				|| checkdnsrr( $matches[1], 'A' )
				|| checkdnsrr( $matches[1], 'AAAA' )
			);
	}

	/**
	 * Get validate error message.
	 *
	 * @return string
	 */
	public static function get_message() {
		return __( 'Please enter a valid email address.', 'snow-monkey-forms' );
	}
}
