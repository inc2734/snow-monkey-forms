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

	protected $validation_map = [];

	public function __construct( Responser $responser, Setting $setting ) {
		$this->responser      = $responser;
		$this->setting        = $setting;
		$this->validation_map = $this->_set_validation_map( $setting );
	}

	public function validate() {
		foreach ( $this->validation_map as $name => $validations ) {
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

	public function get_error_message( $name ) {
		$error_messages = [];

		if ( ! isset( $this->validation_map[ $name ] ) ) {
			return;
		}

		foreach ( $this->validation_map[ $name ] as $validation_name => $validation ) {
			if ( 'required' === $validation_name && $validation ) {
				if ( false === Validation\Required::validate( $this->responser->get( $name ) ) ) {
					$error_messages[] = Validation\Required::get_message();
				}
			}
		}

		return implode( ' ', $error_messages );
	}

	protected function _set_validation_map( Setting $setting ) {
		$validation_map = [];

		foreach ( $setting->get( 'controls' ) as $control ) {
			if ( is_null( $control->get( 'name' ) ) || ! $control->get( 'validations' ) ) {
				continue;
			}

			$validation_map[ $name ] = (array) $control->get( 'validations' );
		}

		return $validation_map;
	}
}
