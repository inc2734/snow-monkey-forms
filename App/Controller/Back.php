<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Back extends Contract\Controller {
	protected function set_controls() {
		$controls = [];
		$setting_controls = $this->setting->get( 'controls' );

		foreach ( $setting_controls as $control ) {
			$name = $control->get( 'name' );
			if ( is_null( $name ) || '' === $name ) {
				continue;
			}

			$value = $control->get( 'value' );
			$posted_value = $this->responser->get( $name );

			if ( ! is_null( $control->get( 'checked' ) ) && $value === $posted_value ) {
				$control->set( 'checked', true );
			} elseif ( ! is_null( $control->get( 'selected' ) ) && $value === $posted_value ) {
				$control->set( 'selected', true );
			} else {
				$control->set( 'value', $posted_value );
			}

			$controls[ $name ] = $control->render();
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
