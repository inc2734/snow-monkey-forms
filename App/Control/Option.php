<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Option extends Contract\Control {

	/**
	 * @var array
	 *  - string  value
	 *  - boolean selected
	 */
	protected $attributes = [
		'value'    => '',
		'selected' => false,
	];

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * Save the value.
	 *
	 * @param mixed $value The value to be saved.
	 */
	public function save( $value ) {
		$selected = (string) $this->get_attribute( 'value' ) === (string) $value;
		$this->set_attribute( 'selected', $selected );
	}

	/**
	 * Return HTML for input page.
	 *
	 * @return string
	 */
	public function input() {
		$attributes = $this->_generate_attributes( $this->get_property( 'attributes' ) );

		$label = $this->get_property( 'label' );
		$label = '' === $label || is_null( $label ) ? $this->get_attribute( 'value' ) : $label;

		return sprintf(
			'<option %1$s>%2$s</option>',
			$this->_generate_attributes_string( $attributes ),
			esc_html( $label )
		);
	}

	/**
	 * Return HTML for confirm page.
	 *
	 * @return string
	 */
	public function confirm() {
		if ( ! $this->get_attribute( 'selected' ) ) {
			return;
		}

		$label = $this->get_property( 'label' );
		$label = '' === $label || is_null( $label ) ? $this->get_attribute( 'value' ) : $label;

		return sprintf(
			'%1$s%2$s',
			esc_html( $label ),
			Helper::control(
				'hidden',
				[
					'attributes' => [
						'name'  => $this->get_property( 'name' ),
						'value' => $this->get_attribute( 'value' ),
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
	public function invalid(
		// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$message = ''
		// phpcs:enable
	) {
		return $this->input();
	}
}
