<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Validation;

class Validator {

	protected $responser = [];
	protected $setting;

	public function __construct( Responser $responser, Setting $setting ) {
		$this->responser = $responser;
		$this->setting   = $setting;
	}

	public function validate() {
		foreach ( $this->setting->get( 'controls' ) as $control ) {
			$attributes  = isset( $control['attributes'] ) ? $control['attributes'] : [];
			$name        = isset( $attributes['name'] ) ? $attributes['name'] : null;
			$validations = isset( $control['validations'] ) ? $control['validations'] : [];

			if ( '' === $name || is_null( $name ) ) {
				continue;
			}

			foreach ( $validations as $validation_name => $validation ) {
				if ( 'required' === $validation_name && $validation ) {
					if ( false === Validation\Required::validate( $this->responser->get( $name ) ) ) {
						return false;
					}
				}
			}
		}

		return true;
	}

	public function get_error_message( $target ) {
		$error_messages = [];

		foreach ( $this->setting->get( 'controls' ) as $control ) {
			$attributes  = isset( $control['attributes'] ) ? $control['attributes'] : [];
			$name        = isset( $attributes['name'] ) ? $attributes['name'] : null;
			$validations = isset( $control['validations'] ) ? $control['validations'] : [];

			if ( '' === $name || is_null( $name ) || $target !== $name ) {
				continue;
			}

			foreach ( $validations as $validation_name => $validation ) {
				if ( 'required' === $validation_name && $validation ) {
					if ( false === Validation\Required::validate( $this->responser->get( $name ) ) ) {
						$error_messages[] = Validation\Required::get_message();
					}
				}
			}

			return implode( ' ', $error_messages );
		}
	}
}
