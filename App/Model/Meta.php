<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Helper;

class Meta {

	const KEY = '_snow-monkey-forms-meta';

	protected static $data = [];

	public static function get_key() {
		return static::KEY;
	}

	public static function save( array $data ) {
		static::$data = $data;
	}

	public static function the_meta_multiple( $name, array $values ) {
		foreach ( $values as $key => $value ) {
			Helper::the_control(
				'hidden',
				[
					'attributes' => [
						'name'  => static::get_key() . '[' . $name . '][' . $key . ']',
						'value' => $value,
					],
				]
			);
		}
	}

	public static function the_meta( $name, $value ) {
		Helper::the_control(
			'hidden',
			[
				'attributes' => [
					'name'  => static::get_key() . '[' . $name . ']',
					'value' => $value,
				],
			]
		);
	}

	public static function the_meta_button( $action, $label ) {
		Helper::the_control(
			'button',
			[
				'attributes' => [
					'data-action' => $action,
				],
				'label' => $label . '<span class="smf-sending" aria-hidden="true"></span>',
			]
		);
	}

	public static function get( $name ) {
		return isset( static::$data[ $name ] ) ? static::$data[ $name ] : false;
	}

	public static function get_all() {
		return static::$data;
	}
}
