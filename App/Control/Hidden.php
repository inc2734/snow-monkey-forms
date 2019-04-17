<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Hidden extends Contract\Control {
	public $name = '';
	public $value = '';

	public function input() {
		return sprintf(
			'<input type="hidden" %1$s>',
			$this->generate_attributes( get_object_vars( $this ) )
		);
	}

	public function confirm() {
		return Helper::control( 'hidden', get_object_vars( $this ) )->input();
	}

	public function error( $error_message = '' ) {
		return Helper::control( 'hidden', get_object_vars( $this ) )->input();
	}
}
