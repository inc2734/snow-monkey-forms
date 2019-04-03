<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

class Button {
	public static function render( $value, $options ) {
		return sprintf(
			'<button class="c-btn" data-action="%2$s" type="submit">%1$s</button>',
			$value,
			$options['data-action']
		);
	}
}
