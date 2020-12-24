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

class Confirm extends Contract\Controller {

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
			$controls[ $name ] = $control->confirm();
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

		Meta::the_meta_button( 'back', $this->setting->get( 'back_button_label' ) );
		Meta::the_meta_button( 'complete', $this->setting->get( 'send_button_label' ) );
		Meta::the_method( 'complete' );
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
