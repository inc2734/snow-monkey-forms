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

		foreach ( $setting_controls as $name => $control ) {
			$value = $this->responser->get( $name );
			$control->save( $value );
			$controls[ $name ] = $control->confirm();
		}

		return $controls;
	}

	protected function set_action() {
		ob_start();

		Helper::the_control(
			'button',
			[
				'attributes' => [
					'data-action' => 'back',
				],
				'label' => __( 'Back', 'snow-monkey-forms' ) . '<span class="smf-sending" aria-hidden="true"></span>',
			]
		);

		Helper::the_control(
			'button',
			[
				'attributes' => [
					'data-action' => 'complete',
				],
				'label' => __( 'Send', 'snow-monkey-forms' ) . '<span class="smf-sending" aria-hidden="true"></span>',
			]
		);

		Helper::the_control(
			'hidden',
			[
				'attributes' => [
					'name'  => '_method',
					'value' => 'complete',
				],
			]
		);

		return ob_get_clean();
	}

	protected function set_message() {
		return $this->message;
	}
}
