<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Confirm extends Responser {
	public function get_response_data() {
		$controls = [];
		foreach ( $this->setting->get( 'controls' ) as $control ) {
			$value = $this->get( $control['attributes']['name'] );

			$label = $value;
			if ( is_array( $value ) ) {
				$labels = [];
				$children = isset( $control['attributes']['children'] ) ? $control['attributes']['children'] : [];
				foreach ( $children as $child ) {
					$child_attributes = isset( $child['attributes'] ) ? $child['attributes'] : [];
					if ( isset( $child_attributes['value'] ) && in_array( $child_attributes['value'], $value ) ) {
						$labels[] = $child['label'];
					}
				}
				$label = implode( ', ', $labels );
			}

			$controls[ $control['attributes']['name'] ] = implode(
				'',
				[
					$label,
					Helper::control(
						'hidden',
						[
							'attributes' => [
								'name'  => $control['attributes']['name'],
								'value' => $value,
							],
						]
					),
				]
			);
		}

		return array_merge(
			parent::get_response_data(),
			[
				'controls' => $controls,
				'action' => [
					Helper::control( 'button', [ 'attributes' => [ 'value' => '戻る', 'data-action' => 'back' ] ] ),
					Helper::control( 'button', [ 'attributes' => [ 'value' => '送信', 'data-action' => 'complete' ] ] ),
					Helper::control( 'hidden', [ 'attributes' => [ 'name' => '_method', 'value' => 'complete' ] ] ),
				],
			]
		);
	}
}
