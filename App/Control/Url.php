<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Url extends Contract\Control {
	public $name = '';
	public $value = '';
	public $data = [];
	protected $validations = [
		'url' => true,
	];

	public function input() {
		return sprintf(
			'<input class="c-form-control" type="url" %1$s>',
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

		$this->data['invalid'] = true;

		return sprintf(
			'%1$s%2$s',
			$this->input(),
			$error_message
		);
	}
}
