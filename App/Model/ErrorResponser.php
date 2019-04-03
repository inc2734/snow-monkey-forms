<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Control;

class ErrorResponser extends Responser {
	public function get_response_data() {
		$controls = [];
		foreach ( $this->setting->get( 'controls' ) as $control ) {
			$form_control = Control::render(
				$control['type'],
				[
					'name'  => $control['name'],
					'value' => $this->get( $control['name'] ),
				]
			);

			$error_message = ! empty( $control['require'] ) && '' === $this->get( $control['name'] )
				? '未入力です'
				: '';

			$controls[ $control['name'] ] = $form_control . $error_message;
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
