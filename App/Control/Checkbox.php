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
	 * @var array
	 *   @var string name
	 *   @var string value
	 *   @var string checked
	 *   @var boolean disabled
	 *   @var string id
	 *   @var string class
	 *   @var boolean data-invalid
	 */
	protected $attributes = [
		'name'         => '',
		'value'        => '',
		'checked'      => false,
		'disabled'     => false,
		'id'           => '',
		'class'        => 'smf-checkbox-control__control',
		'data-invalid' => false,
	];

	/**
	 * @var array
	 */
	protected $validations = [];

	/**
	 * @var string
	 */
	protected $label = '';

	public function save( $value ) {
		$this->set_attribute( 'checked', $this->get_attribute( 'value' ) === $value );
	}

	public function input() {
		$label = $this->get_property( 'label' );
		$label = '' === $label || is_null( $label ) ? $this->get_attribute( 'value' ) : $label;

		return sprintf(
			'<label class="smf-label">
				<span class="smf-checkbox-control">
					<input type="checkbox" %1$s>
					<span class="smf-checkbox-control__label">%2$s</span>
				</span>
			</label>',
			$this->_generate_attributes( $this->get_property( 'attributes' ) ),
			esc_html( $label )
		);
	}

	public function confirm() {
		if ( ! $this->get_attribute( 'checked' ) ) {
			return;
		}

		$label = $this->get_property( 'label' );
		$label = '' === $label || is_null( $label ) ? $this->get_attribute( 'value' ) : $label;

		return sprintf(
			'%1$s%2$s',
			esc_html( $label ),
			Helper::control(
				'hidden',
				[
					'attributes' => [
						'name'  => $this->get_attribute( 'name' ),
						'value' => $this->get_attribute( 'value' ),
					],
				]
			)->input()
		);
	}

	public function error( $error_message = '' ) {
		$this->set_attribute( 'data-invalid', true );

		return sprintf(
			'%1$s
			<div class="smf-error-messages">
				%2$s
			</div>',
			$this->input(),
			$error_message
		);
	}
}
