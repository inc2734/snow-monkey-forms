<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Checkbox extends Contract\Control {
	protected $name    = '';
	protected $value   = '';
	protected $label   = ''; // @todo HTML にはこんな属性は無いんだけど、コンポーネント的には持ちたい
	protected $checked = false;

	public function render() {
		$label = '' === $this->label || is_null( $this->label ) ? $this->value : $this->label;

		return sprintf(
			'<label><input type="checkbox" %1$s>%2$s</label>',
			$this->generate_attributes(),
			esc_html( $label )
		);
	}
}
