<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control\Checkbox;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class View extends Contract\View {

	/**
	 * @var array
	 *   @var string name
	 *   @var string value
	 *   @var string checked
	 *   @var string placeholder
	 *   @var boolean disabled
	 */
	protected $attributes = [];

	/**
	 * @var array
	 */
	protected $validations = [];

	/**
	 * @var string
	 */
	protected $label = '';

	public function input() {
		$label = $this->get( 'label' );
		$label = '' === $label || is_null( $label ) ? $this->get( 'value' ) : $label;

		return sprintf(
			'<label class="smf-label">
				<span class="smf-checkbox-control">
					<input class="smf-checkbox-control__control" type="checkbox" %1$s>
					<span class="smf-checkbox-control__label">%2$s</span>
				</span>
			</label>',
			$this->generate_attributes( $this->attributes ),
			esc_html( $label )
		);
	}

	public function confirm() {
		if ( ! $this->get( 'checked' ) ) {
			return;
		}

		$label = $this->get( 'label' );
		$label = '' === $label || is_null( $label ) ? $this->get( 'value' ) : $label;

		return sprintf(
			'%1$s%2$s',
			esc_html( $label ),
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
