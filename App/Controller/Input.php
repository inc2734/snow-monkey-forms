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

class Input extends Contract\Controller {

	protected function set_controls() {
		return $this->controls;
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

		return ob_get_clean();
	}

	protected function set_message() {
		return $this->message;
	}
}
