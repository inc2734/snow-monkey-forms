<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Helper;

class Dispatcher {

	/**
	 * Dispatch.
	 *
	 * @param string    $method    You will be given one of these.
	 *                             input|confirm|complete|invalid|systemerror.
	 * @param Responser $responser Responser object.
	 * @param Setting   $setting   Setting object.
	 * @param Validator $validator Validator object.
	 * @throws \LogicException If the Controller Class was not found.
	 */
	public static function dispatch( $method, Responser $responser, Setting $setting, Validator $validator ) {
		$class_name = '\Snow_Monkey\Plugin\Forms\App\Controller\\' . static::_generate_class_name( $method );

		if ( ! class_exists( $class_name ) ) {
			throw new \LogicException( sprintf( '[Snow Monkey Forms] Not found the class: %1$s.', $class_name ) );
		}

		return new $class_name( $responser, $setting, $validator );
	}

	/**
	 * Generate class name.
	 *
	 * @param string $string Controller name. input|confirm|complete|invalid|systemerror.
	 * @return string|false
	 */
	protected static function _generate_class_name( $string ) {
		$classes = [];
		foreach ( glob( SNOW_MONKEY_FORMS_PATH . '/App/Controller/*.php' ) as $file ) {
			$slug             = strtolower( basename( $file, '.php' ) );
			$classes[ $slug ] = $file;
		}

		return isset( $classes[ strtolower( $string ) ] )
			? basename( $classes[ strtolower( $string ) ], '.php' )
			: false;
	}
}
