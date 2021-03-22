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
	 *  - string  name
	 *  - string  value
	 *  - string  checked
	 *  - boolean disabled
	 *  - string  id
	 *  - string  class
	 *  - boolean data-invalid
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

	/**
	 * Save the value.
	 *
	 * @param mixed $value The value to be saved.
	 */
	public function save( $value ) {
		$checked = (string) $this->get_attribute( 'value' ) === (string) $value;
		$this->set_attribute( 'checked', $checked );
	}

	/**
	 * Return HTML for input page.
	 *
	 * @return string
	 */
	public function input() {
		$label = $this->get_property( 'label' );
		$label = '' === $label || is_null( $label ) ? $this->get_attribute( 'value' ) : $label;

		$attributes = $this->_generate_attributes( $this->get_property( 'attributes' ) );

		return sprintf(
			'<div class="smf-label">
				<label>
					<span class="smf-checkbox-control">
						<input type="checkbox" %1$s>
						<span class="smf-checkbox-control__label">%2$s</span>
					</span>
				</label>
			</div>',
			$this->_generate_attributes_string( $attributes ),
			esc_html( $label )
		);
	}

	/**
	 * Return HTML for confirm page.
	 *
	 * @return string
	 */
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

	/**
	 * Return invalid message.
	 *
	 * @param string $message The message to be displayed.
	 * @return string
	 */
	public function invalid( $message = '' ) {
		$this->set_attribute( 'data-invalid', true );

		return sprintf(
			'%1$s
			<div class="smf-error-messages">
				%2$s
			</div>',
			$this->input(),
			$message
		);
	}
}
