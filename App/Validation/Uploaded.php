<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Validation;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Model\FileUploader;

class Uploaded extends Contract\Validation {

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

		return ! FileUploader::has_error_code( $value );
	}

	/**
	 * Get validate error message.
	 *
	 * @return string
	 */
	public static function get_message() {
		return __( 'Upload failed.', 'snow-monkey-forms' );
	}
}
