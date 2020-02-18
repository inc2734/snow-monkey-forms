<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Confirm extends Contract\Controller {

	protected function set_controls() {
		$controls = [];
		$setting_controls = $this->setting->get( 'controls' );

		foreach ( $setting_controls as $control ) {
			$name = $control->get( 'name' );
			if ( is_null( $name ) || '' === $name ) {
				continue;
			}

			$value = $this->responser->get( $name );
			$control->set( 'value', is_null( $value ) || is_array( $value ) ? '' : $value );
			$control->set( 'values', is_null( $value ) || ! is_array( $value ) ? [] : $value );

			$controls[ $name ] = $control->confirm();
		}

		return $controls;
	}

	protected function set_action() {
		return [
			Helper::control(
				'button',
				[
					'value'       => __( 'Back', 'snow-monkey-forms' ) . '<span class="smf-sending" aria-hidden="true"></span>',
					'data-action' => 'back',
				]
			)->input(),
			Helper::control(
				'button',
				[
					'value'       => __( 'Send', 'snow-monkey-forms' ) . '<span class="smf-sending" aria-hidden="true"></span>',
					'data-action' => 'complete',
				]
			)->input(),
			Helper::control(
				'hidden',
				[
					'name'  => '_method',
					'value' => 'complete',
				]
			)->input(),
		];
	}

	protected function set_message() {
		return $this->message;
	}
}
