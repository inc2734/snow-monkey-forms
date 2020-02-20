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
		$attributes = include( SNOW_MONKEY_FORMS_PATH . '/block/email/attributes.php' );

		register_block_type(
			'snow-monkey-forms/control-email',
			[
				'attributes'      => $attributes,
				'render_callback' => function( $attributes, $content ) {
					return Helper::dynamic_block( 'email', $attributes, $content );
				},
			]
		);
	}
);
