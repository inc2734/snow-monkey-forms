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
	 * @var boolean
	 */
	protected $disabled = false;

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * @var string
	 */
	protected $value = '';

	/**
	 * @var array
	 */
	protected $children = [];

	public function save( $value ) {
		$this->set_property( 'value', ! is_array( $value ) ? $value : '' );
	}

	protected function _init() {
		$children = [];
		foreach ( $this->get_property( 'options' ) as $value => $label ) {
			$children[] = Helper::control(
				'radio-button',
				[
					'attributes' => [
						'name'         => $this->get_property( 'name' ),
						'value'        => $value,
						'disabled'     => $this->get_property( 'disabled' ),
						'checked'      => $this->get_property( 'value' ) === $value,
						'data-invalid' => $this->get_attribute( 'data-invalid' ),
					],
					'label' => $label,
				]
			);
		}
		$this->set_property( 'children', $children );
	}

	public function input() {
		$this->set_property(
			'children',
			$this->_get_updated_chlidren(
				function( $control ) {
					$checked = $control->get_attribute( 'value' ) === $this->get_property( 'value' );
					$control->set_attribute( 'checked', $checked );
					return $control;
				}
			)
		);

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
			implode( '', $this->_children( 'input' ) ),
			$description
		);
	}

	public function confirm() {
		$this->set_property(
			'children',
			$this->_get_updated_chlidren(
				function( $control ) {
					$checked = $control->get_attribute( 'value' ) === $this->get_property( 'value' );
					$control->set_attribute( 'checked', $checked );
					return $control;
				}
			)
		);

		return implode( '', $this->_children( 'confirm' ) );
	}

	public function error( $error_message = '' ) {
		$this->set_attribute( 'data-invalid', true );

		$this->set_property(
			'children',
			$this->_get_updated_chlidren(
				function( $control ) {
					$control->set_attribute( 'data-invalid', $this->get_attribute( 'data-invalid' ) );
					return $control;
				}
			)
		);

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
