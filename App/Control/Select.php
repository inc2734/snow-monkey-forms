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
	 *  - string  name
	 *  - boolean disabled
	 *  - string  autocomplete
	 *  - string  id
	 *  - string  class
	 *  - string  data-validations
	 *  - boolean data-invalid
	 */
	protected $attributes = array(
		'name'             => '',
		'disabled'         => false,
		'autocomplete'     => '',
		'id'               => '',
		'class'            => 'smf-select-control__control',
		'data-validations' => '',
		'data-invalid'     => false,
	);

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var boolean
	 */
	protected $is_display_description_confirm = false;

	/**
	 * @var array
	 */
	protected $validations = array();

	/**
	 * @var string
	 */
	public $value = '';

	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 * @var array
	 */
	protected $children = array();

	/**
	 * Initialize.
	 */
	protected function _init() {
		$children = array();
		foreach ( $this->get_property( 'options' ) as $value => $label ) {
			$children[] = Helper::control(
				'option',
				array(
					'attributes' => array(
						'value'    => $value,
						'selected' => (string) $this->get_property( 'value' ) === (string) $value,
					),
					'label'      => $label,
					'name'       => $this->get_attribute( 'name' ),
				)
			);
		}
		$this->_set_children( $children );
	}

	/**
	 * Save the value.
	 *
	 * @param mixed $value The value to be saved.
	 */
	public function save( $value ) {
		$this->set_property( 'value', ! is_array( $value ) ? $value : '' );
	}

	/**
	 * Return HTML for input page.
	 *
	 * @return string
	 */
	public function input() {
		$attributes = $this->get_property( 'attributes' );
		$attributes = $this->_normalize_attributes( $attributes );
		if ( isset( $attributes['value'] ) ) {
			$this->set_property( 'value', $attributes['value'] );
			unset( $attributes['value'] );
		}

		$children = $this->_get_children();
		foreach ( $children as $key => $control ) {
			$selected = (string) $control->get_attribute( 'value' ) === (string) $this->get_property( 'value' );
			$control->set_attribute( 'selected', $selected );
			$children[ $key ] = $control;
		}
		$this->_set_children( $children );

		$aria_describedby = array();

		$id = $this->get_attribute( 'id' );
		if ( $id ) {
			$aria_describedby[] = $id . '--description';
		}

		$description = $this->get_property( 'description' );
		if ( $description ) {
			$item_description_id = $this->get_attribute( 'name' ) . '--input-description';

			$description = sprintf(
				'<div class="smf-control-description" id="%1$s">%2$s</div>',
				esc_attr( $item_description_id ),
				wp_kses_post( $description )
			);

			$aria_describedby[] = $item_description_id;
		}

		if ( $aria_describedby ) {
			$attributes['aria-describedby'] = join( ' ', $aria_describedby );
		}

		return sprintf(
			'<div class="smf-select-control">
				<select %1$s>%2$s</select>
				<span class="smf-select-control__toggle"></span>
			</div>
			%3$s',
			$this->_generate_attributes_string( $attributes ),
			$this->_children( 'input' ),
			$description
		);
	}

	/**
	 * Return HTML for confirm page.
	 *
	 * @return string
	 */
	public function confirm() {
		$children = $this->_get_children();
		foreach ( $children as $key => $control ) {
			$selected = (string) $control->get_attribute( 'value' ) === (string) $this->get_property( 'value' );
			$control->set_attribute( 'selected', $selected );
			$children[ $key ] = $control;
		}
		$this->_set_children( $children );

		$description                    = '';
		$is_display_description_confirm = $this->get_property( 'is_display_description_confirm' );
		if ( $is_display_description_confirm ) {
			$description = $this->get_property( 'description' );
			if ( $description ) {
				$description = sprintf(
					'<div class="smf-control-description">%1$s</div>',
					wp_kses_post( $description )
				);
			}
		}

		return sprintf(
			'<div data-validations="%1$s">
				%2$s%3$s
			</div>',
			$this->get_attribute( 'data-validations' ),
			$this->_children( 'confirm' ),
			$description
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
