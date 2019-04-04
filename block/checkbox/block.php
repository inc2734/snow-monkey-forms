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
		register_block_type(
			'snow-monkey-forms/checkbox',
			[
				'attributes' => [
					'label' => [
						'type'    => 'string',
						'default' => '',
					],
					'name' => [
						'type'    => 'string',
						'default' => '',
					],
					'value' => [
						'type'    => 'string',
						'default' => '',
					],
					'checked' => [
						'type'    => 'boolean',
						'default' => false,
					],
				],
				'render_callback' => function( $attributes, $content ) {
					return Helper::dynamic_block( 'checkbox', $attributes, $content );
				},
			]
		);
	}
);
