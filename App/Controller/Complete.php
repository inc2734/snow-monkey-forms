<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Complete extends Contract\Controller {

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
		return $this->action;
	}

	/**
	 * Set the content to be displayed.
	 *
	 * @return string
	 */
	protected function set_message() {
		$message = $this->setting->get( 'complete_content' );
		$message = apply_filters(
			'snow_monkey_forms/complete/message',
			$message,
			$this->responser,
			$this->setting
		);

		return sprintf(
			'<div class="smf-complete-content">%1$s</div>',
			wp_kses_post( $message )
		);
	}
}
