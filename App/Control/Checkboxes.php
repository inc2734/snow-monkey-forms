<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Checkboxes extends Contract\Control {

	/**
	 * @var array
	 *  - boolean data-invalid
	 */
	protected $attributes = array(
		'data-invalid' => false,
	);

	/**
	 * @var string
	 */
	protected $direction = '';

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
	protected $name = '';

	/**
	 * @var array
	 */
	protected $values = array();

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
	protected $options = array();

	/**
	 * @var array
	 */
	protected $children = array();

	/**
	 * Initialized.
	 */
	protected function _init() {
		$this->set_property( 'name', $this->get_property( 'name' ) . '[]' );

		$children = array();
		foreach ( $this->get_property( 'options' ) as $option ) {
			$value = array_keys( $option )[0];
			$label = array_values( $option )[0];

			$children[] = Helper::control(
				'checkbox',
				array(
					'attributes' => array(
						'name'         => $this->get_property( 'name' ),
						'value'        => $value,
						'disabled'     => $this->get_property( 'disabled' ),
						'checked'      => (string) $this->get_property( 'value' ) === (string) $value,
						'data-invalid' => $this->get_attribute( 'data-invalid' ),
					),
					'label'      => $label,
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
		$this->set_property( 'values', ! is_array( $value ) ? array() : $value );
	}

	/**
	 * Return HTML for input page.
	 *
	 * @return string
	 */
	public function input() {
		$attributes = $this->get_property( 'attributes' );
		$attributes = $this->_normalize_attributes( $attributes );

		$children = $this->_get_children();
		foreach ( $children as $key => $control ) {
			$checked = in_array(
				(string) $control->get_attribute( 'value' ),
				$this->get_property( 'values' ),
				true
			);

			$control->set_attribute( 'checked', $checked );
			$children[ $key ] = $control;
		}
		$this->_set_children( $children );

		$direction = $this->get_property( 'direction' );
		$classes   = array();
		$classes[] = 'smf-checkboxes-control';
		if ( $direction ) {
			$classes[] = 'smf-checkboxes-control--' . $direction;
		}

		$description = $this->get_property( 'description' );
		if ( $description ) {
			$description = sprintf(
				'<div class="smf-control-description">%1$s</div>',
				wp_kses_post( $description )
			);
		}

		return sprintf(
			'<div class="%1$s" %2$s>
				<div class="smf-checkboxes-control__control">%3$s</div>
			</div>
			%4$s',
			esc_attr( implode( ' ', $classes ) ),
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
			$checked = in_array(
				(string) $control->get_attribute( 'value' ),
				$this->get_property( 'values' ),
				true
			);

			$control->set_attribute( 'checked', $checked );
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

		$delimiter = $this->get_property( 'delimiter' );

		return sprintf(
			'%1$s%2$s',
			$this->_children( 'confirm', $delimiter ),
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

		$children = $this->_get_children();
		foreach ( $children as $key => $control ) {
			$control->set_attribute( 'data-invalid', $this->get_attribute( 'data-invalid' ) );
			$children[ $key ] = $control;
		}
		$this->_set_children( $children );

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
