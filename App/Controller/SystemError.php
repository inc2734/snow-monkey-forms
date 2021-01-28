<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Contract;

class SystemError extends Contract\Controller {

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
		$message = implode( '<br>', $this->setting->get( 'system_error_messages' ) );
		$message = apply_filters(
			'snow_monkey_forms/system_error/message',
			$message,
			$this->responser,
			$this->setting
		);

		return sprintf(
			'<div class="smf-system-error-content">%1$s</div>',
			wp_kses_post( $message )
		);
	}
}
