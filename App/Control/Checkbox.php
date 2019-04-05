<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Checkbox extends Contract\Control {
	public    $name    = '';
	public    $checked = false;
	protected $value   = '';
	protected $label   = ''; // @todo HTML にはこんな属性は無いんだけど、コンポーネント的には持ちたい

	public function render() {
		$attributes = get_object_vars( $this );
		unset( $attributes['label'] );

		$label = '' === $this->label || is_null( $this->label ) ? $this->value : $this->label;

		return sprintf(
			'<label><input type="checkbox" %1$s>%2$s</label>',
			$this->generate_attributes( $attributes ),
			esc_html( $label )
		);
	}

	public function set( $attribute, $value ) {
		if ( 'value' === $attribute ) {
			$this->checked = $this->value === $value;
			return true;
		}

		return parent::set( $attribute, $value );
	}
}
