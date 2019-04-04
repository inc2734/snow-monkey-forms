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
			$attributes = isset( $control['attributes'] ) ? $control['attributes'] : [];
			$name       = isset( $attributes['name'] ) ? $attributes['name'] : null;
			$children   = isset( $attributes['children'] ) ? $attributes['children'] : [];

			if ( '' === $name || is_null( $name ) ) {
				continue;
			}

			$value = $this->responser->get( $name );

			if ( is_array( $value ) ) {
				$labels  = [];
				$hiddens = [];

				foreach ( $children as $child ) {
					$child_attributes = isset( $child['attributes'] ) ? $child['attributes'] : [];
					$child_value      = isset( $child_attributes['value'] ) ? $child_attributes['value'] : null;
					if ( ! is_null( $child_value ) && in_array( $child_value, $value ) ) {
						$labels[]  = $child['label'];
						$hiddens[] = Helper::control( 'hidden', [ 'attributes' => [ 'name'  => $name . '[]', 'value' => $child_value ] ] );
					}
				}

				$label  = implode( ', ', $labels );
				$hidden = implode( '', $hiddens );

			} else {

				$label = $value;
				$hidden = Helper::control( 'hidden', [ 'attributes' => [ 'name'  => $name, 'value' => $value ] ] );

			}

			$controls[ $name ] = implode( '', [ $label, $hidden ] );
		}

		return $controls;
	}

	protected function set_action() {
		return [
			Helper::control( 'button', [ 'attributes' => [ 'value' => '戻る', 'data-action' => 'back' ] ] ),
			Helper::control( 'button', [ 'attributes' => [ 'value' => '送信', 'data-action' => 'complete' ] ] ),
			Helper::control( 'hidden', [ 'attributes' => [ 'name' => '_method', 'value' => 'complete' ] ] ),
		];
	}

	protected function set_message() {
		return $this->message;
	}
}
