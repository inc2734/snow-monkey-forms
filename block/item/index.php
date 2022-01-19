<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

wp_register_style(
	'snow-monkey-forms/item',
	SNOW_MONKEY_FORMS_URL . '/dist/block/item/style.css',
	[],
	filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/block/item/style.css' )
);

wp_register_style(
	'snow-monkey-forms/item/editor',
	SNOW_MONKEY_FORMS_URL . '/dist/block/item/editor.css',
	[ 'snow-monkey-forms/item' ],
	filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/block/item/editor.css' )
);

register_block_type(
	__DIR__,
	[]
);
