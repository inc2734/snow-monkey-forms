<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App;

class Control {
	const GLUE = '@@@';

	public static function render( $type, array $options = [] ) {
		if ( 'text' === $type ) {

			return sprintf(
				'<input class="c-form-control" type="text" %1$s>',
				static::generate_attributes( $options )
			);

		} elseif ( 'checkbox' === $type ) {

			$children = isset( $options['children'] ) ? $options['children'] : [];
			$name     = isset( $options['name'] ) ? $options['name'] : null;
			$values   = isset( $options['value'] ) ? $options['value'] : [];
			$values   = is_array( $values ) ? $values : explode( static::GLUE, $values );

			if ( ! $name ) {
				return;
			}

			$controls = [
				static::render( 'hidden', [ 'name' => $name, 'value' => '' ] ),
			];
			foreach ( $children as $key => $value ) {
				$controls[] = sprintf(
					'<label><input type="checkbox" name="%1$s[]" value="%2$s" %4$s>%3$s</label>',
					$name,
					$key,
					$value,
					is_array( $values ) && in_array( $key, $values ) ? 'checked="checked"' : null
				);
			}

			return implode( '', $controls );

		} elseif ( 'hidden' === $type ) {

			$value = isset( $options['value'] ) ? $options['value'] : null;
			$value = is_array( $value ) ? implode( static::GLUE, $value ) : $value;
			$attributes = static::generate_attributes( array_merge( $options, [ 'value' => $value ] ) );

			return sprintf(
				'<input type="hidden" %1$s>',
				$attributes
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
			if ( is_null( $value ) || is_array( $value ) ) {
				continue;
			}

			$attributes[] = sprintf( '%s="%s"', $key, $value );
		}

		$attributes = implode( ' ', $attributes );
		return $attributes ? $attributes : null;
	}
}
