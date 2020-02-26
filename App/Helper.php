<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App;

use Snow_Monkey\Plugin\Forms\App\Control;

class Helper {
	public static function generate_class_name( $string ) {
		$class_name_array = array_map(
			function( $string ) {
				return ucfirst( strtolower( $string ) );
			},
			explode( '-', $string )
		);

		return implode( '', $class_name_array );
	}

	public static function control( $type, array $attributes = [] ) {
		$class_name = '\Snow_Monkey\Plugin\Forms\App\Control\\' . static::generate_class_name( $type ) . '\\Viewer';

		try {
			if ( class_exists( $class_name ) ) {
				return new $class_name( $attributes );
			}
			throw new \Exception( sprintf( '[Snow Monkey Forms] The class %1$s is not found.', $class_name ) );
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
			return;
		}
	}

	public static function the_control( $type, $attributes ) {
		echo static::control( $type, $attributes )->input(); // xss ok.
	}

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
					$decoded = json_decode( sprintf( '{%1$s}', $value ), true );
					$decoded = is_array( $decoded ) ? $decoded : [ $value => $value ];
					$decoded = is_array( $decoded ) && ! $decoded ? [ '' => '' ] : $decoded;
					$options = array_merge( $options, $decoded );
				}
			}
			$attributes['options'] = $options ? $options : [];
		}

		if ( isset( $attributes['values'] ) ) {
			$values = str_replace( [ "\r\n", "\r", "\n" ], "\n", $attributes['values'] );
			$values = explode( "\n", $values );
			$values = array_filter( $values );
			$attributes['values'] = $values;
		}

		return $attributes;
	}
}
