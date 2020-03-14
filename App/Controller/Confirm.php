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

	protected function set_controls() {
		$controls = [];
		$setting_controls = $this->setting->get( 'controls' );

		foreach ( $setting_controls as $name => $control ) {
			$value = $this->responser->get( $name );
			$control->save( $value );
			$controls[ $name ] = $control->confirm();
		}

		return $controls;
	}

	protected function set_action() {
		ob_start();

		Meta::the_meta_button( 'back', __( 'Back', 'snow-monkey-forms' ) );
		Meta::the_meta_button( 'complete', __( 'Send', 'snow-monkey-forms' ) );
		Meta::the_method( 'complete' );
		Meta::the_saved_files();

		return ob_get_clean();
	}

	protected function set_message() {
		return $this->message;
	}
}
