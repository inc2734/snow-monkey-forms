<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control\Textarea;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Viewer extends Contract\Viewer {

	/**
	 * @var array
	 *   @var string name
	 *   @var boolean disabled
	 *   @var boolean data-invalid
	 */
	protected $attributes = [
		'name'         => '',
		'disabled'     => false,
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

	public function save( $value ) {
		$this->set_property( 'value', ! is_array( $value ) ? $value : '' );
	}

	public function input() {
		$description = $this->get_property( 'description' );
		if ( $description ) {
			$description = sprintf(
				'<div class="smf-control-description">%1$s</div>',
				wp_kses_post( $description )
			);
		}

		return sprintf(
			'<div class="smf-textarea-control">
				<textarea class="smf-textarea-control__control" type="text" %1$s>%2$s</textarea>
			</div>
			%3$s',
			$this->_generate_attributes( $this->get_property( 'attributes' ) ),
			esc_html( $this->get_property( 'value' ) ),
			$description
		);
	}

	public function confirm() {
		return sprintf(
			'%1$s%2$s',
			nl2br( esc_html( $this->get_property( 'value' ) ) ),
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
