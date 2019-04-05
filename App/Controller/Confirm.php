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

			$posted_value = $this->responser->get( $name );
			$label = $control->get( 'label' );
			$label = ! is_null( $label ) && '' !== $label && ! is_null( $posted_value ) && '' !== $posted_value
							 ? $label
							 : $posted_value;

			if ( is_array( $posted_value ) ) {

				$labels  = [];
				$hiddens = [];

				/*
				@todo

				foreach ( (array) $control->get( 'children' ) as $child ) {
					$child_attributes = isset( $child['attributes'] ) ? $child['attributes'] : [];
					$child_value      = isset( $child_attributes['value'] ) ? $child_attributes['value'] : null;
					if ( ! is_null( $child_value ) && in_array( $child_value, $posted_value ) ) {
						$labels[]  = $child['label'];
						$hiddens[] = Helper::control( 'hidden', [ 'name'  => $name . '[]', 'value' => $child_value ] )->render();
					}
				}
				*/

				$label  = implode( ', ', $labels );
				$hidden = implode( '', $hiddens );

			} else {

				$hidden = Helper::control( 'hidden', [ 'name' => $name, 'value' => $posted_value ] )->render();

			}

			$controls[ $name ] = implode( '', [ esc_html( $label ), $hidden ] );
		}

		return $controls;
	}

	protected function set_action() {
		return [
			Helper::control( 'button', [ 'value' => '戻る', 'data-action' => 'back' ] )->render(),
			Helper::control( 'button', [ 'value' => '送信', 'data-action' => 'complete' ] )->render(),
			Helper::control( 'hidden', [ 'name' => '_method', 'value' => 'complete' ] )->render(),
		];
	}

	protected function set_message() {
		return $this->message;
	}
}
