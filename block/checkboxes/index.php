<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\Helper;

add_action(
	'init',
	function() {
		$attributes = file_get_contents( __DIR__ . '/attributes.json' );
		$attributes = json_decode( $attributes, true );

		register_block_type(
			'snow-monkey-forms/control-checkboxes',
			[
				'attributes'      => $attributes,
				'render_callback' => function( $attributes, $content ) {
					if ( ! isset( $attributes['name'] ) ) {
						return;
					}

					ob_start();
					include( __DIR__ . '/view.php' );
					return ob_get_clean();
				},
			]
		);
	}
);
