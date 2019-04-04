<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Button extends Contract\Control {
	protected $name = '';
	protected $value = '';
	protected $data = [];

	public function render() {
		return sprintf(
			'<button class="c-btn" type="submit" %2$s>%1$s</button>',
			esc_html( $this->value ),
			$this->generate_attributes()
		);
	}
}
