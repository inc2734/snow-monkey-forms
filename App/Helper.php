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
	public static function control( $type, array $properties = array() ) {
		$class_name = '\Snow_Monkey\Plugin\Forms\App\Control\\' . static::_generate_control_class_name( $type );

		if ( ! class_exists( $class_name ) ) {
			throw new \LogicException( sprintf( '[Snow Monkey Forms] Not found the class: %1$s.', esc_html( $class_name ) ) );
		}

		return new $class_name( $properties );
	}

	/**
	 * Return class name.
	 *  - foo     => Foo
	 *  - foo_bar => FooBar
	 *  - FooBar  => Foobar
	 *
	 * @param string $value Control class name.
	 * @return string
	 */
	protected static function _generate_control_class_name( $value ) {
		$class_name_array = array_map(
			function ( $value ) {
				return ucfirst( strtolower( $value ) );
			},
			explode( '-', $value )
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
		$control = static::control( $type, $properties );
		echo $control->input(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
			$validations = is_array( $validations ) ? $validations : array();

			$attributes['validations'] = $attributes['validations'] ? $validations : array();
		}

		if ( isset( $attributes['options'] ) ) {
			$options = array();

			if ( ! empty( $attributes['options'] ) ) {
				$_options = str_replace( array( "\r\n", "\r", "\n" ), "\n", $attributes['options'] );
				$_options = explode( "\n", $_options );

				foreach ( $_options as $value ) {
					$decoded                    = json_decode( sprintf( '{%1$s}', $value ), true );
					$decoded                    = is_array( $decoded ) ? $decoded : array( $value => $value );
					$decoded                    = is_array( $decoded ) && ! $decoded ? array( '' => '' ) : $decoded;
					$options[ key( $decoded ) ] = current( $decoded );
				}
			}
			$attributes['options'] = $options ? $options : array();
		}

		if ( isset( $attributes['values'] ) ) {
			$values               = str_replace( array( "\r\n", "\r", "\n" ), "\n", $attributes['values'] );
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

	/**
	 * Return blocks with their inner blocks flattened.
	 *
	 * @copyright Automattic\WooCommerce
	 * @param array $blocks Array of blocks as returned by parse_blocks().
	 * @return array All blocks.
	 */
	public static function flatten_blocks( $blocks ) {
		return array_reduce(
			$blocks,
			function ( $carry, $block ) {
				array_push( $carry, array_diff_key( $block, array_flip( array( 'innerBlocks' ) ) ) );
				if ( isset( $block['innerBlocks'] ) ) {
					$inner_blocks = static::flatten_blocks( $block['innerBlocks'] );
					return array_merge( $carry, $inner_blocks );
				}

				return $carry;
			},
			array()
		);
	}

	/**
	 * Return true when form ID format is valid.
	 *
	 * @param mixed $form_id Form ID.
	 * @return boolean
	 */
	protected static function _is_valid_form_id_format( $form_id ) {
		if ( ! is_scalar( $form_id ) ) {
			return false;
		}

		$form_id = (string) $form_id;
		if ( '' === $form_id || ! preg_match( '/^[0-9]+$/', $form_id ) ) {
			return false;
		}

		$form_id = absint( $form_id );
		if ( 0 === $form_id ) {
			return false;
		}

		return true;
	}

	/**
	 * Validate and sanitize form ID.
	 *
	 * @param mixed $form_id Form ID.
	 * @return int|false
	 */
	public static function sanitize_form_id( $form_id ) {
		if ( false === static::_is_valid_form_id_format( $form_id ) ) {
			return false;
		}

		return absint( $form_id );
	}

	/**
	 * Return true when token format is valid.
	 *
	 * @param mixed $token Token value.
	 * @return boolean
	 */
	public static function is_valid_token_format( $token ) {
		if ( ! is_scalar( $token ) ) {
			return false;
		}

		$token = (string) $token;
		if ( '' === $token ) {
			return false;
		}

		return (bool) preg_match( '|^[a-z0-9]+$|', $token );
	}
}
