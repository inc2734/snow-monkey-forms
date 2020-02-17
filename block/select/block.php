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
			'snow-monkey-forms/select',
			[
				'attributes' => [
					'name' => [
						'type'    => 'string',
						'default' => '',
					],
					'value' => [
						'type'    => 'string',
						'default' => '',
					],
					'options' => [
						'type'    => 'string',
						'default' => '',
					],
					'validations' => [
						'type'    => 'string',
						'default' => '{}',
					],
				],
				'render_callback' => function( $attributes, $content ) {
					return Helper::dynamic_block( 'select', $attributes, $content );
				},
			]
		);
	}
);
