<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control\Checkboxes;

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
	 * @var array
	 */
	protected $values = [];

	/**
	 * @var boolean
	 */
	protected $disabled = false;

	/**
	 * @var boolean
	 */
	protected $delimiter = ', ';

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * @var array
	 */
	protected $children = [];

	protected function _init() {
		$this->set_property( 'name', $this->get_property( 'name' ) . '[]' );

		$children = [];
		foreach ( $this->get_property( 'options' ) as $option ) {
			$value = array_keys( $option )[0];
			$label = array_values( $option )[0];

			$children[] = Helper::control(
				'checkbox',
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

	public function save( $value ) {
		$this->set_property( 'values', ! is_array( $value ) ? [] : $value );
	}

	public function input() {
		$this->set_property(
			'children',
			$this->_get_updated_chlidren(
				function( $control ) {
					$checked = in_array( $control->get_attribute( 'value' ), $this->get_property( 'values' ) );
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
			'<div class="smf-checkboxes-control" %1$s>
				<div class="smf-checkboxes-control__control">%2$s</div>
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
					$checked = in_array( $control->get_attribute( 'value' ), $this->get_property( 'values' ) );
					$control->set_attribute( 'checked', $checked );
					return $control;
				}
			)
		);

		$delimiter = $this->get_property( 'delimiter' );
		return implode( $delimiter, $this->_children( 'confirm' ) );
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
