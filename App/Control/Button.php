<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Button extends Contract\Control {
	public $name = '';
	public $value = '';
	public $data = [];

	public function input() {
		return sprintf(
			'<button class="c-btn" type="submit" %2$s>%1$s</button>',
			esc_html( $this->value ),
			$this->generate_attributes( get_object_vars( $this ) )
		);
	}

	public function confirm() {
		return $this->input();
	}

	public function error( $error_message = '' ) {
		return $this->input();
	}
}
