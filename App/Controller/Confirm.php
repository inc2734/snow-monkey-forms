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
			$label = $value;

			if ( is_array( $value ) ) {
				$labels = [];
				foreach ( $children as $child ) {
					$child_attributes = isset( $child['attributes'] ) ? $child['attributes'] : [];
					if ( isset( $child_attributes['value'] ) && in_array( $child_attributes['value'], $value ) ) {
						$labels[] = $child['label'];
					}
				}
				$label = implode( ', ', $labels );
			}

			$controls[ $name ] = implode(
				'',
				[
					$label,
					Helper::control( 'hidden', [ 'attributes' => [ 'name'  => $name, 'value' => $value ] ] ),
				]
			);
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
