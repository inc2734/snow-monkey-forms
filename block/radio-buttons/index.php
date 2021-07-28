<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\Helper;

register_block_type(
	__DIR__,
	[
		'render_callback' => function( $attributes ) {
			if ( ! isset( $attributes['name'] ) ) {
				return;
			}

			ob_start();
			include( __DIR__ . '/view.php' );
			return ob_get_clean();
		},
	]
);
