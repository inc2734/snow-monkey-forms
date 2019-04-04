<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Complete extends Contract\Controller {
	protected function set_controls() {
		return $this->controls;
	}

	protected function set_action() {
		return $this->action;
	}

	protected function set_message() {
		return $this->setting->get( 'complete_message' );
	}
}
