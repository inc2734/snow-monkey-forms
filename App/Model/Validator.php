<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

class Validator {

	protected $responser = [];
	protected $setting;

	public function __construct( Responser $responser, Setting $setting ) {
		$this->responser = $responser;
		$this->setting   = $setting;
	}

	public function is_valid() {
		foreach ( $this->setting->get( 'controls' ) as $control ) {
			if ( ! empty( $control['require'] ) && '' === $this->responser->get( $control['attributes']['name'] ) ) {
				return false;
			}
		}
		return true;
	}

	public function get_error_message( $target ) {
		foreach ( $this->setting->get( 'controls' ) as $control ) {
			$attributes = isset( $control['attributes'] ) ? $control['attributes'] : [];
			$name       = isset( $attributes['name'] ) ? $attributes['name'] : null;
			$require    = isset( $control['require'] ) ? $control['require'] : false;

			if ( '' === $name || is_null( $name ) || $target !== $name ) {
				continue;
			}

			if ( ! $require ) {
				continue;
			}

			if ( '' !== $this->responser->get( $name ) && null !== $this->responser->get( $name ) ) {
				continue;
			}

			return '未入力です';
		}
	}
}
