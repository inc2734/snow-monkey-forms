<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Checkbox extends Contract\Control {
	public    $name    = '';
	public    $checked = false;
	public    $data    = [];
	protected $value   = '';
	protected $label   = '';
	protected $validations = [];

	public function input() {
		$attributes = get_object_vars( $this );
		unset( $attributes['label'] );

		$label = '' === $this->label || is_null( $this->label ) ? $this->value : $this->label;

		return sprintf(
			'<label>
				<span class="c-checkbox" aria-checked="false">
					<input type="checkbox" %1$s>
					<span class="c-checkbox__control"></span>
				</span>
				%2$s
			</label>',
			$this->generate_attributes( $attributes ),
			esc_html( $label )
		);
	}

	public function confirm() {
		if ( ! $this->checked ) {
			return;
		}

		$label = ! is_null( $this->label ) && '' !== $this->label ? $this->label : $this->value;

		return sprintf(
			'%1$s%2$s',
			esc_html( $label ),
			Helper::control( 'hidden', [ 'name' => $this->name, 'value' => $this->value ] )->input()
		);
	}

	public function error( $error_message = '' ) {
		$this->data['invalid'] = true;

		return sprintf(
			'%1$s
			<div class="snow-monkey-form-error-messages">
				%2$s
			</div>',
			$this->input(),
			$error_message
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
