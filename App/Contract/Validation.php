<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Contract;

abstract class Validation {

	/**
	 * Validate.
	 *
	 * @param string $value The posted value.
	 * @return boolean True when correct.
	 */
	abstract public static function validate( $value );

	/**
	 * Get validate error message.
	 *
	 * @return string
	 */
	abstract public static function get_message();
}
