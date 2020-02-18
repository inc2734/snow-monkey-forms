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
		$attributes = include( SNOW_MONKEY_FORMS_PATH . '/block/multi-checkbox/attributes.php' );

		register_block_type(
			'snow-monkey-forms/multi-checkbox',
			[
				'attributes'      => $attributes,
				'render_callback' => function( $attributes, $content ) {
					return Helper::dynamic_block( 'multi-checkbox', $attributes, $content );
				},
			]
		);
	}
);
