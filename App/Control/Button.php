<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Button extends Contract\Control {

	/**
	 * @var array
	 *   @var string name
	 *   @var boolean disabled
	 *   @var string id
	 *   @var string class
	 */
	protected $attributes = [
		'name'     => '',
		'disabled' => false,
		'id'       => '',
		'class'    => 'smf-button-control__control',
	];

	/**
	 * @var string
	 */
	protected $label = '';

	public function save( $value ) {
	}

	public function input() {
		return sprintf(
			'<span class="smf-button-control">
				<button type="submit" %1$s>%2$s</button>
			</span>',
			$this->_generate_attributes( $this->get_property( 'attributes' ) ),
			wp_kses_post( $this->get_property( 'label' ) )
		);
	}

	public function confirm() {
		$this->input();
	}

	public function error( $error_message = '' ) {
		$this->input();
	}
}
