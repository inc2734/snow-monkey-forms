<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Select extends Contract\Control {

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var string
	 */
	public $value = '';

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * @var array
	 */
	protected $validations = [];

	public function input() {
		$attributes = get_object_vars( $this );
		unset( $attributes['value'] );
		unset( $attributes['data'] );

		$options = [];
		foreach ( $this->options as $value => $label ) {
			$options[] = sprintf(
				'<option value="%1$s" %3$s>%2$s</option>',
				esc_attr( $value ),
				esc_html( $label ),
				selected( $value, $this->value, false )
			);
		}

		return sprintf(
			'<span class="c-select" aria-selected="false" %1$s>
				<select %2$s>%3$s</select>
				<span class="c-select__label"></span>
			</span>',
			$this->generate_attributes( [ 'data' => $this->data ] ),
			$this->generate_attributes( $attributes ),
			implode( '', $options )
		);
	}

	public function confirm() {
		if ( ! isset( $this->options[ $this->value ] ) ) {
			return;
		}

		return sprintf(
			'%1$s%2$s',
			esc_html( $this->options[ $this->value ] ),
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
			<div class="snow-monkey-form-error-messages">
				%2$s
			</div>',
			Helper::control( 'select', $attributes )->input(),
			$error_message
		);
	}
}