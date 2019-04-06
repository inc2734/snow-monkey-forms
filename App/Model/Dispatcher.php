<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

class Dispatcher {
	public static function dispatch( $method, Responser $responser, Setting $setting, Validator $validator ) {
		$class_name = '\Snow_Monkey\Plugin\Forms\App\Controller\\' . ucfirst( strtolower( $method ) );

		try {
			if ( class_exists( $class_name ) ) {
				$controller = new $class_name( $responser, $setting, $validator );
			} else {
				throw new \Exception( sprintf( 'The class %1$s is not found.', $class_name ) );
			}
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
			$controller = new \Snow_Monkey\Plugin\Forms\App\Controller\Back( $responser, $setting, $validator );
		}

		return $controller;
	}
}
