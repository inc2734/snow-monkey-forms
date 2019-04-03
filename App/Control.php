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
				static::generate_attributes( $options['attributes'] )
			);

		} elseif ( 'checkbox' === $type ) {

			$children = isset( $options['children'] ) ? $options['children'] : [];
			$options_attributes = isset( $options['attributes'] ) ? $options['attributes'] : [];
			$name   = isset( $options_attributes['name'] ) ? $options_attributes['name'] : null;
			$values = isset( $options_attributes['value'] ) ? $options_attributes['value'] : [];
			$values = is_array( $values ) ? $values : explode( static::GLUE, $values );

			if ( ! $name ) {
				return;
			}

			$controls = [
				static::render( 'hidden', [ 'attributes' => [ 'name' => $name, 'value' => '' ] ] ),
			];
			foreach ( $children as $child_option ) {
				$label = isset( $child_option['label'] ) ? $child_option['label'] : null;
				$child_attributes = isset( $child_option['attributes'] ) ? $child_option['attributes'] : [];
				$value = isset( $child_attributes['value'] ) ? $child_attributes['value'] : null;
				$child_attributes = is_array( $values ) && ! empty( $values ) && in_array( $value, $values )
					? array_merge( $child_attributes, [ 'checked' => 'checked' ] )
					: $child_attributes;

				$controls[] = sprintf(
					'<label><input type="checkbox" name="%1$s[]" %2$s>%3$s</label>',
					$name,
					static::generate_attributes( $child_attributes ),
					$label
				);
			}

			return implode( '', $controls );

		} elseif ( 'hidden' === $type ) {

			$options_attributes = isset( $options['attributes'] ) ? $options['attributes'] : [];
			$value = isset( $options_attributes['value'] ) ? $options_attributes['value'] : null;
			$value = is_array( $value ) ? implode( static::GLUE, $value ) : $value;
			$attributes = static::generate_attributes( array_merge( $options_attributes, [ 'value' => $value ] ) );

			return sprintf(
				'<input type="hidden" %1$s>',
				$attributes
			);

		} elseif ( 'button' === $type ) {

			$options_attributes = isset( $options['attributes'] ) ? $options['attributes'] : [];
			$attributes = static::generate_attributes( $options_attributes );
			$value      = isset( $options_attributes['value'] ) ? $options_attributes['value'] : null;

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
