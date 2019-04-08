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
		$class_name_array = array_map( function( $string ) {
			return ucfirst( strtolower( $string ) );
		}, explode( '-', $string ) );

		return implode( '', $class_name_array );
	}

	public static function control( $type, array $attributes = [] ) {
		$class_name = '\Snow_Monkey\Plugin\Forms\App\Control\\' . static::generate_class_name( $type );

		try {
			if ( class_exists( $class_name ) ) {
				return new $class_name( $attributes );
			}
			throw new \Exception( sprintf( 'The class %1$s is not found.', $class_name ) );
		} catch( \Exception $e ) {
			error_log( $e->getMessage() );
			return;
		}
	}

	public static function dynamic_block( $slug, $attributes, $content = null ) {
		ob_start();
		include( SNOW_MONKEY_FORMS_PATH . '/block/' . $slug . '/view.php' );
		return ob_get_clean();
	}

	public static function block_meta_normalization( array $attributes ) {
		if ( isset( $attributes['validations'] ) ) {
			$attributes['validations'] = json_decode( $attributes['validations'], true );
		}

		return $attributes;
	}
}
