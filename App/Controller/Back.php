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

			$value = $this->responser->get( $name );

			if ( 'checkbox' === $type || 'radio' === $type ) {
				if ( isset( $attributes['value'] ) && $attributes['value'] === $value ) {
					$control['attributes'] = array_merge(
						$attributes,
						[
							'checked' => 'checked',
						]
					);
				}
			} elseif ( 'select' === $type ) {
				if ( isset( $attributes['value'] ) && $attributes['value'] === $value ) {
					$control['attributes'] = array_merge(
						$attributes,
						[
							'selected' => 'selected',
						]
					);
				}
			} else {
				$control['attributes'] = array_merge(
					$attributes,
					[
						'value' => $value,
					]
				);
			}

			$controls[ $name ] = Helper::control( $type, $control['attributes'] );
		}

		return $controls;
	}

	protected function set_action() {
		return [
			Helper::control( 'button', [ 'value' => '確認', 'data-action' => 'confirm' ] ),
			Helper::control( 'hidden', [ 'name' => '_method', 'value' => 'confirm' ] ),
		];
	}

	protected function set_message() {
		return $this->message;
	}
}
