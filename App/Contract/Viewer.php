<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Contract;

abstract class Viewer {

	protected $validations = [];

	public function __construct( array $new_properties ) {
		$properties = get_object_vars( $this );

		foreach ( $new_properties as $key => $value ) {
			$property  = $this->get_property( $key );
			$attribute = $this->get_attribute( $key );

			if ( is_null( $property ) && is_null( $attribute ) ) {
				continue;
			}

			if ( ! is_null( $property ) ) {
				$is_array_both  = is_array( $property ) && is_array( $value );
				$is_string_both = ! is_array( $property ) && ! is_array( $value );

				if ( $is_array_both || $is_string_both ) {
					// If "validations", merge. Otherwise, overwrite.
					if ( 'validations' === $key ) {
						$this->set_property( $key, array_merge( $property, $value ) );
					} else {
						$this->set_property( $key, $value );
					}
				}
			} elseif ( ! is_null( $attribute ) ) {
				$is_array_both  = is_array( $attribute ) && is_array( $value );
				$is_string_both = ! is_array( $attribute ) && ! is_array( $value );

				if ( $is_array_both || $is_string_both ) {
					$this->set_attribute( $key, $value );
				}
			}
		}

		$this->_init();
	}

	protected function _init() {
	}

	abstract public function save( $value );
	abstract public function input();
	abstract public function confirm();
	abstract public function error( $error_message = '' );

	protected function _generate_attributes( array $_attributes ) {
		$attributes = [];

		foreach ( $_attributes as $key => $value ) {
			if ( '' === $value || false === $value || is_null( $value ) || is_array( $value ) ) {
				continue;
			}

			if ( 'checked' === $key ) {
				$value = 'checked';
			} elseif ( 'disabled' === $key ) {
				$value = 'disabled';
			} elseif ( 'maxlength' === $key || 'size' === $key ) {
				if ( 0 === $value ) {
					continue;
				}
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

	public function get_property( $name ) {
		return isset( $this->$name ) ? $this->$name : null;
	}

	public function set_property( $name, $value ) {
		if ( isset( $this->$name ) ) {
			$this->$name = $value;
			return true;
		}
		return false;
	}

	public function get_attribute( $name ) {
		return in_array( $name, array_keys( $this->attributes ) ) ? $this->attributes[ $name ] : null;
	}

	public function set_attribute( $name, $value ) {
		if ( in_array( $name, array_keys( $this->attributes ) ) ) {
			$this->attributes[ $name ] = $value;
			return true;
		}
		return false;
	}
}
