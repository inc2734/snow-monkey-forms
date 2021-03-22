<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class File extends Contract\Control {

	/**
	 * @var array
	 *  - string  name
	 *  - boolean disabled
	 *  - string  id
	 *  - string  class
	 *  - boolean data-invalid
	 */
	protected $attributes = [
		'name'         => '',
		'disabled'     => false,
		'id'           => '',
		'class'        => 'smf-file-control__control',
		'data-invalid' => false,
	];

	/**
	 * @var string
	 */
	protected $value = '';

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var array
	 */
	protected $validations = [];

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
		$attributes = $this->_generate_attributes( $this->get_property( 'attributes' ) );

		$value = $this->get_property( 'value' );
		if ( $value ) {
			$value = sprintf(
				'<div class="smf-file-control__value">%1$s: %2$s</div>',
				__( 'Uploaded file', 'snow-monkey-forms' ),
				$this->confirm()
			);
		}

		$description = $this->get_property( 'description' );
		if ( $description ) {
			$description = sprintf(
				'<div class="smf-control-description">%1$s</div>',
				wp_kses_post( $description )
			);
		}

		return sprintf(
			'<div class="smf-file-control">
				<label>
					<input type="file" %1$s>
					<span class="smf-file-control__label">%2$s</span>
					<span class="smf-file-control__filename">%3$s</span>
				</label>
				%4$s
			</div>
			%5$s',
			$this->_generate_attributes_string( $attributes ),
			__( 'Choose file', 'snow-monkey-forms' ),
			__( 'No file chosen', 'snow-monkey-forms' ),
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
		return sprintf(
			'%1$s%2$s',
			esc_html( basename( $this->get_property( 'value' ) ) ),
			Helper::control(
				'hidden',
				[
					'attributes' => [
						'name'  => $this->get_attribute( 'name' ),
						'value' => $this->get_property( 'value' ),
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
