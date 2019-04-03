<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App;

use Snow_Monkey\Plugin\Forms\App\Model;

class DataStore {
	public static function get( $form_id ) {
		return new Model\Setting( $form_id );
	}
}
