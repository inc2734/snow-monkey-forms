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

class Invalid extends Contract\Controller {

	/**
	 * Set the form controls.
	 *
	 * @return array
	 */
	protected function set_controls() {
		$controls         = [];
		$setting_controls = $this->setting->get( 'controls' );

		foreach ( $setting_controls as $name => $control ) {
			$value = $this->responser->get( $name );
			$control->save( $value );
			$error_messages    = $this->validator->get_error_messages( $name );
			$controls[ $name ] = $error_messages
				? $control->invalid( implode( ' ', $error_messages ) )
				: $control->input();
		}

		return $controls;
	}

	/**
	 * Set the form action area HTML.
	 *
	 * @return string
	 */
	protected function set_action() {
		ob_start();

		if ( true === $this->setting->get( 'use_confirm_page' ) ) {
			Meta::the_meta_button( 'confirm', $this->setting->get( 'confirm_button_label' ) );
			Meta::the_method( 'confirm' );
		} else {
			Meta::the_meta_button( 'complete', $this->setting->get( 'send_button_label' ) );
			Meta::the_method( 'complete' );
		}

		Meta::the_saved_files();

		return ob_get_clean();
	}

	/**
	 * Set the content to be displayed.
	 *
	 * @return string
	 */
	protected function set_message() {
		return $this->message;
	}
}
