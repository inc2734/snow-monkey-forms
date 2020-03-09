<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Hidden extends Contract\Control {

	/**
	 * @var array
	 *   @var string name
	 *   @var string value
	 *   @var boolean disabled
	 */
	protected $attributes = [
		'name'     => '',
		'value'    => '',
		'disabled' => false,
	];

	public function save( $value ) {
		$this->set_attribute( 'value', ! is_array( $value ) ? $value : '' );
	}

	public function input() {
		return sprintf(
			'<input type="hidden" %1$s>',
			$this->_generate_attributes( $this->get_property( 'attributes' ) )
		);
	}

	public function confirm() {
		return $this->input();
	}

	public function error( $error_message = '' ) {
		return $this->input();
	}
}
