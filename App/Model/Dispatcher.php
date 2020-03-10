<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Helper;

class Dispatcher {

	public static function dispatch( $method, Responser $responser, Setting $setting, Validator $validator ) {
		$class_name = '\Snow_Monkey\Plugin\Forms\App\Controller\\' . Helper::generate_class_name( $method );

		if ( ! class_exists( $class_name ) ) {
			throw new \LogicException( sprintf( '[Snow Monkey Forms] Not found the class: %1$s.', $class_name ) );
		}

		return new $class_name( $responser, $setting, $validator );
	}
}
