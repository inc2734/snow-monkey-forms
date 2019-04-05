<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App;

use Snow_Monkey\Plugin\Forms\App\Control;

class Helper {
	public static function control( $type, array $attributes = [] ) {
		if ( 'text' === $type ) {

			return new Control\Text( $attributes );

		} elseif ( 'multi-checkbox' === $type ) {

			return new Control\MultiCheckbox( $attributes );

		} elseif ( 'checkbox' === $type ) {

			return new Control\Checkbox( $attributes );

		} elseif ( 'hidden' === $type ) {

			return new Control\Hidden( $attributes );

		} elseif ( 'button' === $type ) {

			return new Control\Button( $attributes );

		}
	}

	public static function dynamic_block( $slug, $attributes, $content = null ) {
		ob_start();
		include( SNOW_MONKEY_FORMS_PATH . '/block/' . $slug . '/view.php' );
		return ob_get_clean();
	}

	public static function block_meta_normalization( array $attributes ) {
		if ( isset( $attributes['validations'] ) ) {
			$attributes['validations'] = json_decode( $attributes['validations'], true );
		}

		return $attributes;
	}
}
