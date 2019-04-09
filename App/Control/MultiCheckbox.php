<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class MultiCheckbox extends Contract\Control {
	public    $name        = '';
	public    $data        = [];
	public    $values      = [];
	protected $options     = [];
	protected $validations = [];

	public function _init() {
		$this->name = $this->name . '[]';
	}

	public function input() {
		$attributes = get_object_vars( $this );
		unset( $attributes['name'] );
		unset( $attributes['values'] );

		$options = [];
		foreach ( $this->options as $value => $label ) {
			$option_attributes = [
				'name'    => $this->name,
				'value'   => $value,
				'label'   => $label,
				'checked' => in_array( $value, $this->values ),
			];

			$options[] = Helper::control( 'checkbox', $option_attributes )->input();
		}

		return sprintf(
			'<span class="c-multi-checkbox" %1$s>%2$s</span>',
			$this->generate_attributes( $attributes ),
			implode( '', $options )
		);
	}

	public function confirm() {
		if ( ! $this->values ) {
			return;
		}

		$options = [];
		foreach ( $this->options as $value => $label ) {
			$checked = in_array( $value, $this->values );
			if ( ! $checked ) {
				continue;
			}

			$option_attributes = [
				'name'    => $this->name,
				'value'   => $value,
				'label'   => $label,
				'checked' => $checked,
			];

			$options[] = Helper::control( 'radio', $option_attributes )->confirm();
		}

		return implode( ', ', $options );
	}

	public function error( $error_message = '' ) {
		$this->data['invalid'] = true;

		return sprintf(
			'%1$s%2$s',
			$this->input(),
			$error_message
		);
	}

	public function get( $attribute ) {
		if ( 'name' === $attribute ) {
			return str_replace( '[]', '', $this->$attribute );
		}

		return parent::get( $attribute );
	}
}
