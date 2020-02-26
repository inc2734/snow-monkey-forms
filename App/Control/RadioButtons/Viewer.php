<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control\RadioButtons;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Viewer extends Contract\Viewer {

	/**
	 * @var array
	 *   @var boolean data-invalid
	 */
	protected $attributes = [
		'data-invalid' => false,
	];

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

	public function save( $value ) {
		$this->set_property( 'value', ! is_array( $value ) ? $value : '' );
	}

	public function input() {
		$radio_buttons = [];
		$radio_buttons_properties = $this->_generate_radio_buttons_properties();
		foreach ( $radio_buttons_properties as $radio_properties ) {
			$radio_buttons[] = Helper::control( 'radio-button', $radio_properties )->input();
		}

		$description = $this->get_property( 'description' );
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
			$this->_generate_attributes( $this->get_property( 'attributes' ) ),
			implode( '', $radio_buttons ),
			$description
		);
	}

	public function confirm() {
		$value = $this->get_property( 'value' );
		if ( ! $value ) {
			return;
		}

		$checked = isset( $this->options[ $value ] );
		$label   = isset( $this->options[ $value ] ) ? $this->options[ $value ] : $value;

		return Helper::control(
			'radio-button',
			[
				'attributes' => [
					'name'    => $this->get_property( 'name' ),
					'value'   => $value,
					'checked' => $checked,
				],
				'label' => $label,
			]
		)->confirm();
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

	private function _generate_radio_buttons_properties() {
		$radio_buttons_properties = [];

		foreach ( $this->get_property( 'options' ) as $value => $label ) {
			$radio_buttons_properties[] = [
				'attributes' => array_merge(
					$this->get_property( 'attributes' ),
					[
						'name'     => $this->get_property( 'name' ),
						'value'    => $value,
						'disabled' => $this->get_property( 'disabled' ),
						'checked'  => $value === $this->get_property( 'value' ),
					]
				),
				'label' => $label,
			];
		}

		return $radio_buttons_properties;
	}
}
