<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App;

class Control {
	public static function render( $type, array $options = [] ) {
		if ( 'text' === $type ) {

			return sprintf(
				'<input class="c-form-control" type="text" %1$s>',
				static::generate_attributes( $options )
			);

		} elseif ( 'hidden' === $type ) {

			return sprintf(
				'<input type="hidden" %1$s>',
				static::generate_attributes( $options )
			);

		} elseif ( 'button' === $type ) {

			$attributes = static::generate_attributes( $options );
			$value      = isset( $options['value'] ) ? $options['value'] : null;

			return sprintf(
				'<button class="c-btn" %1$s>%2$s</button>',
				$attributes,
				$value
			);

		}
	}

	public static function generate_attributes( array $_attributes ) {
		$attributes = [];

		foreach ( $_attributes as $key => $value ) {
			if ( is_null( $value ) ) {
				continue;
			}

			$attributes[] = sprintf( '%s="%s"', $key, $value );
		}

		$attributes = implode( ' ', $attributes );
		return $attributes ? $attributes : null;
	}
}
