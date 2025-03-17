<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;
use Snow_Monkey\Plugin\Forms\App\Model\FileUploader;

class File extends Contract\Control {

	/**
	 * @var array
	 *  - string  name
	 *  - boolean disabled
	 *  - string  id
	 *  - string  class
	 *  - string  data-validations
	 *  - boolean data-invalid
	 */
	protected $attributes = array(
		'name'             => '',
		'disabled'         => false,
		'id'               => '',
		'class'            => 'smf-file-control__control',
		'data-validations' => '',
		'data-invalid'     => false,
	);

	/**
	 * @var string
	 */
	protected $value = '';

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
	protected $validations = array(
		'uploaded' => true,
	);

	/**
	 * Save the value.
	 *
	 * @param mixed $value The value to be saved.
	 */
	public function save( $value ) {
		$this->set_property( 'value', $value );
	}

	/**
	 * Return HTML for input page.
	 *
	 * @return string
	 */
	public function input() {
		$attributes = $this->get_property( 'attributes' );
		$attributes = $this->_normalize_attributes( $attributes );

		$value   = $this->get_property( 'value' );
		$invalid = FileUploader::has_error_code( $value );
		if ( $value ) {
			if ( ! $invalid ) {
				$value = sprintf(
					'<div class="smf-file-control__value">%1$s: %2$s</div>',
					__( 'Uploaded file', 'snow-monkey-forms' ),
					$this->confirm()
				);
			} else {
				$value = Helper::control(
					'hidden',
					array(
						'attributes' => array(
							'name'  => $this->get_attribute( 'name' ),
							'value' => $this->get_property( 'value' ),
						),
					)
				)->input();
			}
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
			'<div class="smf-file-control %1$s">
				<label tabindex="0">
					<input type="file" %2$s>
					<span class="smf-file-control__label">%3$s</span>
					<span class="smf-file-control__filename smf-file-control__filename--no-file">%4$s</span>
					<span class="smf-file-control__filename smf-file-control__filename--has-file"></span>
				</label>
				<span class="smf-file-control__clear">%5$s</span>
			</div>
			%6$s
			%7$s',
			$value ? 'smf-file-control--uploaded' : '',
			$this->_generate_attributes_string( $attributes ),
			__( 'Choose file', 'snow-monkey-forms' ),
			__( 'No file chosen', 'snow-monkey-forms' ),
			__( 'Clear', 'snow-monkey-forms' ),
			$value,
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
			esc_html( $this->get_property( 'value' ) ? basename( $this->get_property( 'value' ) ) : '' ),
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
