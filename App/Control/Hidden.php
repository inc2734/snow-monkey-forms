<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

class Hidden {
	public static function render( $name, $value ) {
		return sprintf(
			'<input type="hidden" name="%1$s" value="%2$s">',
			$name,
			$value
		);
	}
}
