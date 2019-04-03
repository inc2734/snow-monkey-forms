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
			$controls[ $control['name'] ] = Control\Hidden::render( $control['name'], $this->get( $control['name'] ) );
		}

		return array_merge(
			parent::get_response_data(),
			[
				'controls' => $controls,
				'action' => [
					Control\Button::render( '戻る', [ 'data-action' => 'back' ] ),
					Control\Button::render( '送信', [ 'data-action' => 'complete' ] ),
					Control\Hidden::render( '_method', 'complete' ),
				],
			]
		);
	}
}
