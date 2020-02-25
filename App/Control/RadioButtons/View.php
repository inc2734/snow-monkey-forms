<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control\RadioButtons;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class View extends Contract\View {

	/**
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var array
	 */
	protected $validations = [];

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $value = '';

	/**
	 * @var boolean
	 */
	protected $disabled = false;

	/**
	 * @var array
	 */
	protected $options = [];

	public function input() {
		$radio_buttons = [];
		$radio_buttons_properties = $this->_generate_radio_buttons_properties();
		foreach ( $radio_buttons_properties as $radio_properties ) {
			$radio_buttons[] = Helper::control( 'radio-button', $radio_properties )->input();
		}

		$description = $this->get( 'description' );
		if ( $description ) {
			$description = sprintf(
				'<div class="smf-control-description">%1$s</div>',
				wp_kses_post( $description )
			);
		}

		return sprintf(
			'<div class="smf-radio-buttons-control" %1$s>
				<div class="smf-radio-buttons-control__control">%2$s</div>
			</div>
			%3$s',
			$this->generate_attributes( $this->attributes ),
			implode( '', $radio_buttons ),
			$description
		);
	}

	public function confirm() {
		$value = $this->get( 'value' );
		if ( ! $value ) {
			return;
		}

		$checked = isset( $this->options[ $value ] );
		$label   = isset( $this->options[ $value ] ) ? $this->options[ $value ] : $value;

		return Helper::control(
			'radio-button',
			[
				'attributes' => [
					'name'    => $this->get( 'name' ),
					'value'   => $value,
					'checked' => $checked,
				],
				'label' => $label,
			]
		)->confirm();
	}

	public function error( $error_message = '' ) {
		$this->set( 'data-invalid', true );

		return sprintf(
			'%1$s
			<div class="smf-error-messages">
				%2$s
			</div>',
			$this->input(),
			$error_message
		);
	}

	public function get( $attribute ) {
		if ( 'name' === $attribute || 'value' === $attribute || 'disabled' === $attribute ) {
			return $this->$attribute;
		}

		return parent::get( $attribute );
	}

	public function set( $attribute, $value ) {
		if ( 'value' === $attribute || 'disabled' === $attribute ) {
			$this->$attribute = $value;
			return true;
		}

		return parent::set( $attribute, $value );
	}

	private function _generate_radio_buttons_properties() {
		$radio_buttons_properties = [];

		foreach ( $this->options as $value => $label ) {
			$radio_buttons_properties[] = [
				'attributes' => array_merge(
					$this->attributes,
					[
						'name'     => $this->get( 'name' ),
						'value'    => $value,
						'disabled' => $this->get( 'disabled' ),
						'checked'  => $value === $this->get( 'value' ),
					]
				),
				'label' => $label,
			];
		}

		return $radio_buttons_properties;
	}
}
