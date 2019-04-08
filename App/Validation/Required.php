<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Validation;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Required extends Contract\Validation {
	public static function validate( $value ) {
		if ( is_array( $value ) && empty( $value ) ) {
			return false;
		}

		return ! is_null( $value ) && '' !== $value;
	}

	public static function get_message() {
		return __( 'Please enter.', 'snow-monkey-forms' );
	}
}
