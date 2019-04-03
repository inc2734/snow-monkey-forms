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
			$class_name = 'Snow_Monkey\Plugin\Forms\App\Control\\' . ucfirst( strtolower( $control['type'] ) );
			$controls[ $control['name'] ] = $class_name::render( $control['name'], $this->get( $control['name'] ) );
		}

		return array_merge(
			parent::get_response_data(),
			[
				'controls' => $controls,
				'action' => [
					Control\Button::render( '確認', [ 'data-action' => 'confirm' ] ),
					Control\Hidden::render( '_method', 'confirm' ),
				],
			]
		);
	}
}
