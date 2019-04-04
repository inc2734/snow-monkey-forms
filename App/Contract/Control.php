<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Contract;

abstract class Control {
	const GLUE = '@@@';

	public function __construct( array $attributes ) {
		foreach ( $attributes as $attribute => $value ) {
			if ( 0 === strpos( $attribute, 'data-' ) && isset( $this->data ) ) {
				$this->data[ $attribute ] = $value;
				continue;
			}

			if ( 0 === strpos( $attribute, 'aria-' ) && isset( $this->aria ) ) {
				$this->aria[ $attribute ] = $value;
				continue;
			}

			if ( array_key_exists( $attribute, get_object_vars( $this ) ) ) {
				$this->$attribute = $value;
			}
		}
	}

	abstract public function render();

	public function generate_attributes() {
		$attributes = [];

		foreach ( get_object_vars( $this ) as $key => $value ) {
			if ( 'data' === $key ) {
				foreach ( $value as $data_key => $data_value ) {
					$attributes[] = $this->_generate_attribute_string( $data_key, $data_value );
				}
				continue;
			}

			if ( 'area' === $key ) {
				foreach ( $value as $area_key => $area_value ) {
					$attributes[] = $tthis->_generate_attribute_string( $area_key, $area_value );
				}
				continue;
			}

			if ( 'checked' === $key && ! $value ) {
				continue;
			}

			if ( 'selected' === $key && ! $value ) {
				continue;
			}

			if ( is_null( $value ) || is_array( $value ) ) {
				continue;
			}

			$attributes[] = $this->_generate_attribute_string( $key, $value );
		}

		$attributes = implode( ' ', $attributes );
		return $attributes ? $attributes : null;
	}

	protected function _generate_attribute_string( $key, $value ) {
		return sprintf(
			'%s="%s"',
			esc_attr( $key ),
			esc_attr( $value )
		);
	}
}
