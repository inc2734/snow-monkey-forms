<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Control;

class ConfirmResponser extends Responser {
	public function get_response_data() {
		$controls = [];
		foreach ( $this->setting->get( 'controls' ) as $control ) {
			$controls[ $control['name'] ] = Control::render(
				'hidden',
				[
					'name'  => $control['name'],
					'value' => $this->get( $control['name'] ),
				]
			);
		}

		return array_merge(
			parent::get_response_data(),
			[
				'controls' => $controls,
				'action' => [
					Control::render( 'button', [ 'value' => '戻る', 'data-action' => 'back' ] ),
					Control::render( 'button', [ 'value' => '送信', 'data-action' => 'complete' ] ),
					Control::render( 'hidden', [ 'name' => '_method', 'value' => 'complete' ] ),
				],
			]
		);
	}
}
