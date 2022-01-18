<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

wp_register_style(
	'snow-monkey-forms-control-text-style',
	SNOW_MONKEY_FORMS_URL . '/dist/block/text/style.css',
	[],
	filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/block/text/style.css' )
);

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
