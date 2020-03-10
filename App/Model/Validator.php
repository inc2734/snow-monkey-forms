<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Helper;

class Validator {

	/**
	 * @var Responser
	 */
	protected $responser;

	/**
	 * @var Setting
	 */
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
				if ( ! $validation ) {
					continue;
				}

				$validation_class = $this->_get_validation_class( $validation_name );
				if ( ! $validation_class ) {
					continue;
				}

				if ( false === $validation_class::validate( $this->responser->get( $name ) ) ) {
					return false;
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
			if ( ! $validation ) {
				continue;
			}

			$validation_class = $this->_get_validation_class( $validation_name );
			if ( ! $validation_class ) {
				continue;
			}

			if ( false === $validation_class::validate( $this->responser->get( $name ) ) ) {
				$error_messages[] = $validation_class::get_message();
			}
		}

		return implode( ' ', $error_messages );
	}

	protected function _set_validation_map( Setting $setting ) {
		$validation_map = [];

		foreach ( $setting->get( 'controls' ) as $name => $control ) {
			$validations = $control->get_property( 'validations' );

			if ( ! $validations ) {
				continue;
			}

			$validation_map[ $name ] = (array) $validations;
		}

		return $validation_map;
	}

	protected function _get_validation_class( $validation_name ) {
		$class_name = '\Snow_Monkey\Plugin\Forms\App\Validation\\' . Helper::generate_class_name( $validation_name );

		if ( ! class_exists( $class_name ) ) {
			throw new \LogicException( sprintf( '[Snow Monkey Forms] Not found the class: %1$s.', $class_name ) );
		}

		return $class_name;
	}
}
