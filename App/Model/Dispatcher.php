<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

class Dispatcher {
	protected $method;
	protected $responser;
	protected $setting;

	public static function dispatch( $method, Responser $responser, Setting $setting ) {
		$class_name = '\Snow_Monkey\Plugin\Forms\App\Controller\\' . ucfirst( strtolower( $method ) );

		try {
			$controller = new $class_name( $responser, $setting );
		} catch ( Exception $e ) {
			error_log( $e->getMessage() );
			$controller = new \Snow_Monkey\Plugin\Forms\App\Controller\Back( $responser, $setting );
		}

		return $controller;
	}
}
