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

class Back extends Contract\Controller {

	/**
	 * Set the form controls.
	 *
	 * @return array
	 */
	protected function set_controls() {
		$controls         = array();
		$setting_controls = $this->setting->get_controls( false );

		foreach ( $setting_controls as $name => $_controls ) {
			$value = $this->responser->get( $name );

			foreach ( $_controls as $control ) {
				$control->save( $value );
				$controls[ $name ][] = $control->input();
			}
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

		Meta::the_token();

		Meta::the_meta_button( 'confirm', $this->setting->get( 'confirm_button_label' ) );
		Meta::the_method( 'confirm' );

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
