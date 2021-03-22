<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Contract;

abstract class Control {

	/**
	 * @var array
	 */
	protected $validations = [];

	/**
	 * Constructor.
	 *
	 * @param array $new_properties Array of property.
	 */
	public function __construct( array $new_properties ) {
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
					// If "validations" and "attributes", merge. Otherwise, overwrite.
					if ( 'validations' === $key || 'attributes' === $key ) {
						$this->set_property( $key, array_merge( $property, $value ) );
					} else {
						$this->set_property( $key, $value );
					}
				}
			} elseif ( ! is_null( $attribute ) ) {
				$is_array_both  = is_array( $attribute ) && is_array( $value );
				$is_string_both = ! is_array( $attribute ) && ! is_array( $value );

				if ( $is_array_both || $is_string_both ) {
					// If "class", merge. Otherwise, overwrite.
					if ( 'class' === $key ) {
						$this->set_attribute( $key, trim( $attribute . ' ' . $value ) );
					} else {
						$this->set_attribute( $key, $value );
					}
				}
			}
		}

		$this->_init();
	}

	/**
	 * Initialize.
	 */
	protected function _init() {
	}

	/**
	 * Save the value.
	 *
	 * @param mixed $value The value to be saved.
	 */
	abstract public function save( $value );

	/**
	 * Return HTML for input page.
	 *
	 * @return string
	 */
	abstract public function input();

	/**
	 * Return HTML for confirm page.
	 *
	 * @return string
	 */
	abstract public function confirm();

	/**
	 * Return invalid message.
	 *
	 * @param string $message The message to be displayed.
	 * @return string
	 */
	abstract public function invalid( $message = '' );

	/**
	 * Generate attributes array.
	 *
	 * @param array $_attributes Array of attribute.
	 * @return array
	 */
	protected function _generate_attributes( array $_attributes ) {
		$_attributes = apply_filters( 'snow_monkey_forms/control/attributes', $_attributes );

		$attributes = [];

		foreach ( $_attributes as $key => $value ) {
			if (
				'' === $value && 'value' !== $key
				|| false === $value
				|| is_null( $value )
				|| is_array( $value )
			) {
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

			$attributes[ $key ] = $value;
		}

		return $attributes;
	}

	/**
	 * Generate attributes. <attribute>="<value>" <attribute>="<value>" ...
	 *
	 * @param array $_attributes Array of attribute.
	 * @return string|null
	 */
	protected function _generate_attributes_string( array $_attributes ) {
		$_attributes = $this->_generate_attributes( $_attributes );

		$attributes = [];

		foreach ( $_attributes as $key => $value ) {
			$attribute_string = $this->_generate_attribute_string( $key, $value );
			if ( ! $attribute_string ) {
				continue;
			}

			$attributes[] = $attribute_string;
		}

		$attributes = implode( ' ', $attributes );
		return $attributes ? $attributes : null;
	}

	/**
	 * Generate attribute string. <attribute>="<value>".
	 *
	 * @param string $name  The attribute name.
	 * @param string $value The attribute value.
	 * @return string
	 */
	protected function _generate_attribute_string( $name, $value ) {
		$value = trim( $value );
		if ( '' === $value && 'value' !== $name ) {
			return;
		}

		return sprintf(
			'%s="%s"',
			esc_attr( $name ),
			esc_attr( $value )
		);
	}

	/**
	 * Return property.
	 *
	 * @param string $name The property name.
	 * @return string
	 */
	public function get_property( $name ) {
		return isset( $this->$name ) ? $this->$name : null;
	}

	/**
	 * Set property.
	 *
	 * @param string $name  The property name.
	 * @param string $value The property value.
	 * @return boolean
	 */
	public function set_property( $name, $value ) {
		if ( isset( $this->$name ) ) {
			$this->$name = $value;
			return true;
		}
		return false;
	}

	/**
	 * Return attribute.
	 *
	 * @param string $name the attribute name.
	 * @return string
	 */
	public function get_attribute( $name ) {
		return in_array(
			$name,
			array_keys( $this->attributes ),
			true
		)
			? $this->attributes[ $name ]
			: null;
	}

	/**
	 * Set attribute.
	 *
	 * @param string $name  The attribute name.
	 * @param string $value The attribute value.
	 * @return boolean
	 */
	public function set_attribute( $name, $value ) {
		if ( in_array( $name, array_keys( $this->attributes ), true ) ) {
			$this->attributes[ $name ] = $value;
			return true;
		}
		return false;
	}

	/**
	 * Return children. Array of Control object.
	 *
	 * @return array
	 */
	protected function _get_children() {
		return array_filter( $this->get_property( 'children' ) );
	}

	/**
	 * Set children property.
	 *
	 * @param array $children Array of Control object.
	 */
	protected function _set_children( $children ) {
		$this->set_property( 'children', $children );
	}

	/**
	 * Return children HTML.
	 *
	 * @param string $method    input|confirm|complete|invalid|systemerror.
	 * @param string $delimiter Delimiter.
	 * @return string
	 */
	protected function _children( $method, $delimiter = '' ) {
		return implode(
			$delimiter,
			array_filter(
				array_map(
					function( $control ) use ( $method ) {
						if ( method_exists( $control, $method ) ) {
							return $control->$method();
						}
						return false;
					},
					$this->_get_children()
				)
			)
		);
	}
}
