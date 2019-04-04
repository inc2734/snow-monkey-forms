<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Checkbox extends Contract\Control {
	protected $name = '';
	protected $value = '';
	protected $checked = null;

	public function render() {
		return sprintf(
			'<input type="checkbox" %1$s>',
			$this->generate_attributes()
		);
	}
}
