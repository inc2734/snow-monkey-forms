<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Back extends Responser {
	public function get_response_data() {
		$controls = [];
		foreach ( $this->setting->get( 'controls' ) as $control ) {
			$attributes = isset( $control['attributes'] ) ? $control['attributes'] : [];
			$control['attributes'] = array_merge( $attributes, [ 'value' => $this->get( $control['attributes']['name'] ) ] );

			$controls[ $control['attributes']['name'] ] = Helper::control( $control['type'], $control );
		}

		return array_merge(
			parent::get_response_data(),
			[
				'controls' => $controls,
				'action'   => [
					Helper::control( 'button', [ 'attributes' =>[ 'value' => '確認', 'data-action' => 'confirm' ] ] ),
					Helper::control( 'hidden', [ 'attributes' =>[ 'name' => '_method', 'value' => 'confirm' ] ] ),
				],
			]
		);
	}
}
