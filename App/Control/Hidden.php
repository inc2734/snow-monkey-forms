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
	 *  - string  name
	 *  - string  value
	 *  - string  data-validations
	 *  - boolean disabled
	 */
	protected $attributes = array(
		'name'             => '',
		'value'            => '',
		'data-validations' => '',
		'disabled'         => false,
	);

	/**
	 * Save the value.
	 *
	 * @param mixed $value The value to be saved.
	 */
	public function save( $value ) {
		$this->set_attribute( 'value', ! is_array( $value ) ? $value : '' );
	}

	/**
	 * Return HTML for input page.
	 *
	 * @return string
	 */
	public function input() {
		$attributes = $this->get_property( 'attributes' );
		$attributes = $this->_normalize_attributes( $attributes );

		return sprintf(
			'<input type="hidden" %1$s>',
			$this->_generate_attributes_string( $attributes )
		);
	}

	/**
	 * Return HTML for confirm page.
	 *
	 * @return string
	 */
	public function confirm() {
		return $this->input();
	}

	/**
	 * Return invalid message.
	 *
	 * @param string $message The message to be displayed.
	 * @return string
	 */
	public function invalid(
		// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$message = ''
		// phpcs:enable
	) {
		return $this->input();
	}
}
