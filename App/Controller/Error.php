<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;

class Error extends Contract\Controller {

	protected function set_controls() {
		$controls = [];
		$setting_controls = $this->setting->get( 'controls' );

		foreach ( $setting_controls as $name => $control ) {
			$value = $this->responser->get( $name );
			$control->save( $value );
			$error_message = $this->validator->get_error_message( $name );
			$controls[ $name ] = $error_message ? $control->error( $error_message ) : $control->input();
		}

		return $controls;
	}

	protected function set_action() {
		ob_start();

		if ( true === $this->setting->get( 'use_confirm_page' ) ) {
			Meta::the_meta_button( 'confirm', __( 'Confirm', 'snow-monkey-forms' ) );
			Meta::the_method( 'confirm' );
		} else {
			Meta::the_meta_button( 'complete', __( 'Send', 'snow-monkey-forms' ) );
			Meta::the_method( 'complete' );
		}

		Meta::the_saved_files();

		return ob_get_clean();
	}

	protected function set_message() {
		return $this->message;
	}
}
