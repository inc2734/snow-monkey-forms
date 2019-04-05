<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Error extends Contract\Controller {
	protected function set_controls() {
		$controls = [];
		$setting_controls = $this->setting->get( 'controls' );

		foreach ( $setting_controls as $control ) {
			$name = $control->get( 'name' );
			if ( is_null( $name ) || '' === $name ) {
				continue;
			}

			$posted_value = $this->responser->get( $name );
			$control->set( 'value', $posted_value );

			$error_message = $this->validator->get_error_message( $name );
			$controls[ $name ] = $control->render() . $error_message;
		}

		return $controls;
	}

	protected function set_action() {
		return [
			Helper::control( 'button', [ 'value' => '確認', 'data-action' => 'confirm' ] )->render(),
			Helper::control( 'hidden', [ 'name' => '_method', 'value' => 'confirm' ] )->render(),
		];
	}

	protected function set_message() {
		return $this->message;
	}
}
