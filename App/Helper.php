<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App;

use Snow_Monkey\Plugin\Forms\App\Control;

class Helper {

	/**
	 * Return Control
	 *
	 * @param string $type       The Control type.
	 * @param array  $properties Array of the Control properties.
	 * @return Control
	 * @throws \LogicException If the Control Class was not found.
	 */
	public static function control( $type, array $properties = [] ) {
		$class_name = '\Snow_Monkey\Plugin\Forms\App\Control\\' . static::_generate_control_class_name( $type );

		if ( ! class_exists( $class_name ) ) {
			throw new \LogicException( sprintf( '[Snow Monkey Forms] Not found the class: %1$s.', $class_name ) );
		}

		return new $class_name( $properties );
	}

	/**
	 * Return class name.
	 *  - foo     => Foo
	 *  - foo_bar => FooBar
	 *  - FooBar  => Foobar
	 *
	 * @param string $string Control class name.
	 * @return string
	 */
	protected static function _generate_control_class_name( $string ) {
		$class_name_array = array_map(
			function( $string ) {
				return ucfirst( strtolower( $string ) );
			},
			explode( '-', $string )
		);

		return implode( '', $class_name_array );
	}

	/**
	 * Display input HTML of Control.
	 *
	 * @param string $type       The Control type.
	 * @param array  $properties Array of the Control properties.
	 */
	public static function the_control( $type, $properties ) {
		echo static::control( $type, $properties )->input(); // xss ok.
	}

	/**
	 * Convert attributes of js block to properties of php Control.
	 *
	 * @param array $attributes The Control attributes.
	 * @return array
	 */
	public static function block_meta_normalization( array $attributes ) {
		if ( isset( $attributes['validations'] ) ) {
			$validations = json_decode( $attributes['validations'], true );
			$validations = is_array( $validations ) ? $validations : [];

			$attributes['validations'] = $attributes['validations'] ? $validations : [];
		}

		if ( isset( $attributes['options'] ) ) {
			$options = [];

			if ( ! empty( $attributes['options'] ) ) {
				$_options = str_replace( [ "\r\n", "\r", "\n" ], "\n", $attributes['options'] );
				$_options = explode( "\n", $_options );

				foreach ( $_options as $value ) {
					$decoded                    = json_decode( sprintf( '{%1$s}', $value ), true );
					$decoded                    = is_array( $decoded ) ? $decoded : [ $value => $value ];
					$decoded                    = is_array( $decoded ) && ! $decoded ? [ '' => '' ] : $decoded;
					$options[ key( $decoded ) ] = $decoded;
				}
			}
			$attributes['options'] = $options ? $options : [];
		}

		if ( isset( $attributes['values'] ) ) {
			$values               = str_replace( [ "\r\n", "\r", "\n" ], "\n", $attributes['values'] );
			$values               = explode( "\n", $values );
			$values               = array_unique( $values );
			$attributes['values'] = $values;
		}

		if ( isset( $attributes['controlClass'] ) ) {
			$attributes['class'] = $attributes['controlClass'];
			unset( $attributes['controlClass'] );
		}

		return $attributes;
	}
}
