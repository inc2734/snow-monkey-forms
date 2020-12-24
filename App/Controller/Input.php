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

	/**
	 * Set the form controls.
	 *
	 * @return array
	 */
	protected function set_controls() {
		return $this->controls;
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
