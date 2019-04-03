<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

class Dispatcher {
	protected $method;
	protected $response;

	public static function dispatch( $method, array $data = [], Setting $setting ) {
		$class_name = 'Snow_Monkey\Plugin\Forms\App\Model\\' . ucfirst( strtolower( $method ) ) . 'Responser';
		return new $class_name( $data, $setting );
	}
}
