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
	 * @var array
	 *   @var string name
	 *   @var boolean disabled
	 *   @var string id
	 *   @var string class
	 *   @var boolean data-invalid
	 */
	protected $attributes = [
		'name'         => '',
		'disabled'     => false,
		'id'           => '',
		'class'        => 'smf-select-control__control',
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
	public $value = '';

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * @var array
	 */
	protected $children = [];

	protected function _init() {
		$children = [];
		foreach ( $this->get_property( 'options' ) as $option ) {
			$value = array_keys( $option )[0];
			$label = array_values( $option )[0];

			$children[] = Helper::control(
				'option',
				[
					'attributes' => [
						'value'    => $value,
						'selected' => $this->get_property( 'value' ) === $value,
					],
					'label' => $label,
					'name'  => $this->get_attribute( 'name' ),
				]
			);
		}
		$this->_set_children( $children );
	}

	public function save( $value ) {
		$this->set_property( 'value', ! is_array( $value ) ? $value : '' );
	}

	public function input() {
		$children = $this->_get_children();
		foreach ( $children as $key => $control ) {
			$selected = $control->get_attribute( 'value' ) === $this->get_property( 'value' );
			$control->set_attribute( 'selected', $selected );
			$children[ $key ] = $control;
		}
		$this->_set_children( $children );

		$description = $this->get_property( 'description' );
		if ( $description ) {
			$description = sprintf(
				'<div class="smf-control-description">%1$s</div>',
				wp_kses_post( $description )
			);
		}

		return sprintf(
			'<div class="smf-select-control">
				<select %1$s>%2$s</select>
				<span class="smf-select-control__toggle"></span>
			</div>
			%3$s',
			$this->_generate_attributes( $this->get_property( 'attributes' ) ),
			$this->_children( 'input' ),
			$description
		);
	}

	public function confirm() {
		$children = $this->_get_children();
		foreach ( $children as $key => $control ) {
			$checked = $control->get_attribute( 'value' ) === $this->get_property( 'value' );
			$control->set_attribute( 'selected', $checked );
			$children[ $key ] = $control;
		}
		$this->_set_children( $children );

		return $this->_children( 'confirm' );
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
