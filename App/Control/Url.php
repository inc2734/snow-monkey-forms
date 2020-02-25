<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Url extends Contract\Control {

	/**
	 * @var array
	 *   @var string name
	 *   @var string value
	 *   @var string placeholder
	 *   @var boolean disabled
	 *   @var boolean data-invalid
	 */
	protected $attributes = [];

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var array
	 */
	protected $validations = [
		'url' => true,
	];

	public function input() {
		$description = $this->get( 'description' );
		if ( $description ) {
			$description = sprintf(
				'<div class="smf-control-description">%1$s</div>',
				wp_kses_post( $description )
			);
		}

		return sprintf(
			'<div class="smf-text-control">
				<input class="smf-text-control__control" type="url" %1$s>
			</div>
			%2$s',
			$this->generate_attributes( $this->attributes ),
			$description
		);
	}

	public function confirm() {
		return sprintf(
			'%1$s%2$s',
			esc_html( $this->get( 'value' ) ),
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
}
