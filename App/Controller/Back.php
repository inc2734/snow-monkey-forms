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
			$type       = $control['type'];
			$attributes = isset( $control['attributes'] ) ? $control['attributes'] : [];
			$name       = isset( $attributes['name'] ) ? $attributes['name'] : null;

			if ( '' === $name || is_null( $name ) ) {
				continue;
			}

			$control['attributes'] = array_merge(
				$attributes,
				[
					'value' => $this->responser->get( $name ),
				]
			);

			$controls[ $name ] = Helper::control( $type, $control );
		}

		return $controls;
	}

	protected function set_action() {
		return [
			Helper::control( 'button', [ 'attributes' => [ 'value' => '確認', 'data-action' => 'confirm' ] ] ),
			Helper::control( 'hidden', [ 'attributes' => [ 'name' => '_method', 'value' => 'confirm' ] ] ),
		];
	}

	protected function set_message() {
		return $this->message;
	}
}
