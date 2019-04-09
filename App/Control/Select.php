<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Select extends Contract\Control {
	public    $name     = '';
	public    $data     = [];
	public    $value    = '';
	protected $options  = [];
	protected $validations = [];

	public function input() {
		$attributes = get_object_vars( $this );
		unset( $attributes['value'] );

		$options = [];
		foreach ( $this->options as $value => $label ) {
			$options[] = sprintf(
				'<option value="%1$s" %3$s>%2$s</option>',
				esc_attr( $value ),
				esc_html( $label ),
				selected( $value, $this->value, false )
			);
		}

		return sprintf(
			'<select %1$s>%2$s</select>',
			$this->generate_attributes( $attributes ),
			implode( '', $options )
		);
	}

	public function confirm() {
		if ( ! isset( $this->options[ $this->value ] ) ) {
			return;
		}

		return sprintf(
			'%1$s%2$s',
			esc_html( $this->options[ $this->value ] ),
			Helper::control( 'hidden', [ 'name' => $this->name, 'value' => $this->value ] )->input()
		);
	}

	public function error( $error_message = '' ) {
		if ( ! $error_message ) {
			return $this->input();
		}

		$this->data['invalid'] = true;

		return sprintf(
			'%1$s%2$s',
			$this->input(),
			$error_message
		);
	}
}
