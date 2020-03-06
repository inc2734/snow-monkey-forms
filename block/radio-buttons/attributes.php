<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

return [
	'name' => [
		'type'    => 'string',
		'default' => '',
	],
	'value' => [
		'type'    => 'string',
		'default' => '',
	],
	'disabled' => [
		'type'    => 'boolean',
		'default' => false,
	],
	'options' => [
		'type'    => 'string',
		'default' => "value1\n\"value2\" : \"label2\"\n\"value3\" : \"label3\"",
	],
	'description' => [
		'type'    => 'string',
		'default' => '',
	],
	'validations' => [
		'type'    => 'string',
		'default' => '{}',
	],
];
