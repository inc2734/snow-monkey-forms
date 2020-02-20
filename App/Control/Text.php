<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Text extends Contract\Control {

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var string
	 */
	public $value = '';

	/**
	 * @var string
	 */
	public $placeholder = '';

	/**
	 * @var boolean
	 */
	public $disabled = false;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var array
	 */
	protected $validations = [];

	public function input() {
		return sprintf(
			'<input class="c-form-control" type="text" %1$s>',
			$this->generate_attributes( get_object_vars( $this ) )
		);
	}

	public function confirm() {
		return sprintf(
			'%1$s%2$s',
			esc_html( $this->value ),
			Helper::control(
				'hidden',
				[
					'name'  => $this->name,
					'value' => $this->value,
				]
			)->input()
		);
	}

	public function error( $error_message = '' ) {
		$this->data['data-invalid'] = true;
		$attributes = get_object_vars( $this );

		return sprintf(
			'%1$s%
			<div class="smf-error-messages">
				%2$s
			</div>',
			Helper::control( 'text', $attributes )->input(),
			$error_message
		);
	}
}
