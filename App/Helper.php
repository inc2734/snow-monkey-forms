<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App;

use Snow_Monkey\Plugin\Forms\App\Control;

class Helper {
	public static function control( $type, array $options = [] ) {
		$attributes = isset( $options['attributes'] ) ? $options['attributes'] : [];

		if ( 'text' === $type ) {

			$control = new Control\Text( $attributes );
			return $control->render();

		} elseif ( 'multi-checkbox' === $type ) {

			$control = new Control\MultiCheckbox( $attributes );
			return $control->render();

		} elseif ( 'checkbox' === $type ) {

			$control = new Control\Checkbox( $attributes );
			return $control->render();

		} elseif ( 'hidden' === $type ) {

			$control = new Control\Hidden( $attributes );
			return $control->render();

		} elseif ( 'button' === $type ) {

			$control = new Control\Button( $attributes );
			return $control->render();

		}
	}
}
