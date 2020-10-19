<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App;

use Snow_Monkey\Plugin\Forms\App\Model\Setting;

class DataStore {

	/**
	 * Return Setting.
	 *
	 * @param int $form_id The form ID.
	 * @return Setting
	 */
	public static function get( $form_id ) {
		return new Setting( $form_id );
	}
}
