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
	 *   @var string name
	 *   @var string value
	 *   @var string placeholder
	 *   @var boolean disabled
	 */
	protected $attributes = [];

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

	public function input() {
		$description = $this->get( 'description' );
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
			$this->generate_attributes( $this->attributes ),
			esc_html( $this->value ),
			$description
		);
	}

	public function confirm() {
		return sprintf(
			'%1$s%2$s',
			nl2br( esc_html( $this->get( 'value' ) ) ),
			Helper::control(
				'hidden',
				[
					'attributes' => [
						'name'  => $this->get( 'name' ),
						'value' => $this->get( 'value' ),
					],
				]
			)->input()
		);
	}

	public function error( $error_message = '' ) {
		$this->set( 'data-invalid', true );

		return sprintf(
			'%1$s
			<div class="smf-error-messages">
				%2$s
			</div>',
			$this->input(),
			$error_message
		);
	}

	public function get( $attribute ) {
		if ( 'value' === $attribute ) {
			return $this->value;
		}

		return parent::get( $attribute );
	}

	public function set( $attribute, $value ) {
		if ( 'value' === $attribute ) {
			$this->value = $value;
			return true;
		}

		return parent::set( $attribute, $value );
	}
}
