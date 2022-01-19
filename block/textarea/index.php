<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

wp_register_style(
	'snow-monkey-forms/textarea',
	SNOW_MONKEY_FORMS_URL . '/dist/block/textarea/style.css',
	[],
	filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/block/textarea/style.css' )
);

wp_register_style(
	'snow-monkey-forms/textarea/editor',
	SNOW_MONKEY_FORMS_URL . '/dist/block/textarea/editor.css',
	[ 'snow-monkey-forms/textarea' ],
	filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/block/textarea/editor.css' )
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
