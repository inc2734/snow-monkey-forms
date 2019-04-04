<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Controller;

use Snow_Monkey\Plugin\Forms\App\Model\Responser;

class Complete extends Responser {
	public function get_response_data() {
		return array_merge(
			parent::get_response_data(),
			[
				'message' => $this->setting->get( 'complete_message' ),
			]
		);
	}
}
