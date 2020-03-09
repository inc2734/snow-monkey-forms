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
	 *   @var string value
	 *   @var boolean selected
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

	public function save( $value ) {
		$this->set_attribute( 'selected', $this->get_attribute( 'value' ) === $value );
	}

	public function input() {
		$label = $this->get_property( 'label' );
		$label = '' === $label || is_null( $label ) ? $this->get_attribute( 'value' ) : $label;

		return sprintf(
			'<option %1$s>%2$s</option>',
			$this->_generate_attributes( $this->get_property( 'attributes' ) ),
			esc_html( $label )
		);
	}

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

	public function error( $error_message = '' ) {
		return $this->input();
	}
}
