<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\Helper;

if ( ! isset( $attributes['formId'] ) ) {
	return;
}

echo do_shortcode( '[snow_monkey_form id="' . $attributes['formId'] . '"]' );
