<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Email extends Contract\Control {
	public $name = '';
	public $value = '';
	protected $validations = [
		'email' => true,
	];

	public function input() {
		return sprintf(
			'<input class="c-form-control" type="email" %1$s>',
			$this->generate_attributes( get_object_vars( $this ) )
		);
	}

	public function confirm() {
		return sprintf(
			'%1$s%2$s',
			esc_html( $this->value ),
			Helper::control( 'hidden', [ 'name' => $this->name, 'value' => $this->value ] )->input()
		);
	}

	public function error( $error_message = '' ) {
		if ( ! $error_message ) {
			return $this->input();
		}

		return sprintf(
			'%1$s%2$s',
			sprintf(
				'<input class="c-form-control c-form-control--error" type="email" %1$s>',
				$this->generate_attributes( get_object_vars( $this ) )
			),
			$error_message
		);
	}
}
