<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Control;

class BackResponser extends Responser {
	public function get_response_data() {
		$controls = [];
		foreach ( $this->setting->get( 'controls' ) as $control ) {
			$controls[ $control['name'] ] = Control::render(
				$control['type'],
				array_merge( $control, [ 'value' => $this->get( $control['name'] ) ] )
			);
		}

		return array_merge(
			parent::get_response_data(),
			[
				'controls' => $controls,
				'action' => [
					Control::render( 'button', [ 'value' => '確認', 'data-action' => 'confirm' ] ),
					Control::render( 'hidden', [ 'name' => '_method', 'value' => 'confirm' ] ),
				],
			]
		);
	}
}
