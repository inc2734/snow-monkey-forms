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
	 *  - string  name
	 *  - boolean disabled
	 *  - string  id
	 *  - string  class
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

	/**
	 * Save the value.
	 *
	 * @param mixed $value The value to be saved.
	 */
	public function save(
		// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$value
		// phpcs:enable
	) {
	}

	/**
	 * Return HTML for input page.
	 *
	 * @return string
	 */
	public function input() {
		$attributes = $this->_generate_attributes( $this->get_property( 'attributes' ) );

		return sprintf(
			'<span class="smf-button-control">
				<button type="submit" %1$s>%2$s</button>
			</span>',
			$this->_generate_attributes_string( $attributes ),
			wp_kses_post( $this->get_property( 'label' ) )
		);
	}

	/**
	 * Return HTML for confirm page.
	 */
	public function confirm() {
		$this->input();
	}

	/**
	 * Return invalid message.
	 *
	 * @param string $message The message to be displayed.
	 */
	public function invalid(
		// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$message = ''
		// phpcs:enable
	) {
		$this->input();
	}
}
