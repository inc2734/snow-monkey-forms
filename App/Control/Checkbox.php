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

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var boolean
	 */
	public $checked = false;

	/**
	 * @var boolean
	 */
	public $disabled = false;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var string
	 */
	protected $value = '';

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var array
	 */
	protected $validations = [];

	public function input() {
		$attributes = get_object_vars( $this );
		unset( $attributes['label'] );
		unset( $attributes['data'] );

		$label = '' === $this->label || is_null( $this->label ) ? $this->value : $this->label;

		return sprintf(
			'<label class="c-label">
				<span class="c-checkbox" aria-checked="false" %1$s>
					<input type="checkbox" %2$s>
					<span class="c-checkbox__control"></span>
				</span>
				%3$s
			</label>',
			$this->generate_attributes( [ 'data' => $this->data ] ),
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
			Helper::control(
				'hidden',
				[
					'name'  => $this->name,
					'value' => $this->value,
				]
			)->input()
		);
	}

	public function error( $error_message = '' ) {
		$this->data['data-invalid'] = true;
		$attributes = get_object_vars( $this );

		return sprintf(
			'%1$s
			<div class="smf-error-messages">
				%2$s
			</div>',
			Helper::control( 'checkbox', $attributes )->input(),
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
