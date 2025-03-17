<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Textarea extends Contract\Control {

	/**
	 * @var array
	 *  - string  name
	 *  - int     rows
	 *  - string  placeholder
	 *  - boolean disabled
	 *  - int     maxlength
	 *  - string  autocomplete
	 *  - string  id
	 *  - string  class
	 *  - string  data-validations
	 *  - boolean data-invalid
	 */
	protected $attributes = array(
		'name'             => '',
		'rows'             => 5,
		'placeholder'      => '',
		'disabled'         => false,
		'maxlength'        => 0,
		'autocomplete'     => '',
		'id'               => '',
		'class'            => 'smf-textarea-control__control',
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
			'<div class="smf-textarea-control">
				<textarea %1$s>%2$s</textarea>
			</div>
			%3$s',
			$this->_generate_attributes_string( $attributes ),
			esc_html( $this->get_property( 'value' ) ),
			$description
		);
	}

	/**
	 * Return HTML for confirm page.
	 *
	 * @return string
	 */
	public function confirm() {
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
			'%1$s%2$s%3$s',
			nl2br( esc_html( $this->get_property( 'value' ) ) ),
			$description,
			Helper::control(
				'hidden',
				array(
					'attributes'  => array(
						'name'  => $this->get_attribute( 'name' ),
						'value' => $this->get_property( 'value' ),
					),
					'validations' => $this->get_property( 'validations' ),
				)
			)->confirm()
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
