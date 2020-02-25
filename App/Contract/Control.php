<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Contract;

abstract class Control {

	protected $attributes = [];

	public function __construct( array $properties ) {
		$has_properties = get_object_vars( $this );

		foreach ( $properties as $key => $value ) {
			if ( 'validations' === $key && isset( $this->validations ) && is_array( $value ) ) {
				$value = array_merge( $this->validations, $value );
			}

			if ( in_array( $key, array_keys( $has_properties ) ) ) {
				if ( is_array( $this->$key ) ) {
					$this->$key = ! is_array( $value ) ? $this->$key : $value;
				} else {
					$this->$key = is_array( $value ) ? $this->$key : $value;
				}
			}
		}

		$this->_init();
	}

	protected function _init() {
	}

	abstract public function input();
	abstract public function confirm();
	abstract public function error( $error_message = '' );

	public function generate_attributes( array $_attributes ) {
		$attributes = [];

		foreach ( $_attributes as $key => $value ) {
			if ( 'checked' === $key ) {
				if ( ! $value ) {
					continue;
				} else {
					$value = 'checked';
				}
			} elseif ( 'disabled' === $key ) {
				if ( ! $value ) {
					continue;
				} else {
					$value = 'disabled';
				}
			} elseif ( 'maxlength' === $key || 'size' === $key ) {
				if ( ! $value ) {
					continue;
				}
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

	protected function _get_no_attributes_keys() {
		return [
			'label',
			'values',
			'options',
			'validations',
		];
	}

	public function get( $attribute ) {
		if ( in_array( $attribute, $this->_get_no_attributes_keys() ) ) {
			return isset( $this->$attribute ) ? $this->$attribute : null;
		}

		return in_array( $attribute, array_keys( $this->attributes ) ) ? $this->attributes[ $attribute ] : null;
	}

	public function set( $attribute, $value ) {
		if ( in_array( $attribute, $this->_get_no_attributes_keys() ) ) {
			if ( isset( $this->$attribute ) ) {
				$this->$attribute = $value;
				return true;
			}
			return false;
		}

		if ( in_array( $attribute, array_keys( $this->attributes ) ) ) {
			$this->attributes[ $attribute ] = $value;
			return true;
		}
		return false;
	}
}
