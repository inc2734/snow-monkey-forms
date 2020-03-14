<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Meta {

	const KEY = 'snow-monkey-forms-meta';

	/**
	 * @var Meta
	 */
	private static $singleton;

	protected static $saved_files = [];
	protected static $formid;
	protected static $token;
	protected static $method;

	private function __construct( $data ) {
		$properties = array_keys( get_class_vars( get_class( $this ) ) );
		foreach ( $data as $key => $value ) {
			if ( ! in_array( $key, $properties ) ) {
				continue;
			}

			if ( is_array( static::$$key ) && is_array( $value ) ) {
				static::$$key = $value;
			} elseif ( ! is_array( static::$$key ) && ! is_array( $value ) ) {
				static::$$key = $value;
			}
		}
	}

	public static function init( $data ) {
		if ( is_null( static::$singleton ) ) {
			static::$singleton = new Meta( $data );
		}
		return static::$singleton;
	}

	public static function get_key() {
		return static::KEY;
	}

	protected static function _the_meta_multiple( $name, array $values ) {
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

	protected static function _the_meta( $name, $value ) {
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

	public static function get_saved_files() {
		return static::$saved_files;
	}

	public static function set_saved_files( $saved_files ) {
		static::$saved_files = is_array( $saved_files ) ? $saved_files : [];
	}

	public static function the_saved_files() {
		static::_the_meta_multiple( 'saved_files', static::get_saved_files() );
	}

	public static function get_formid() {
		return static::$formid;
	}

	public static function the_formid( $form_id ) {
		static::_the_meta( 'formid', $form_id );
	}

	public static function get_token() {
		return static::$token;
	}

	public static function the_token() {
		static::_the_meta( 'token', Csrf::token() );
	}

	public static function get_method() {
		return static::$method;
	}

	public static function set_method( $value ) {
		static::$method = $value;
	}

	public static function the_method( $value ) {
		static::_the_meta( 'method', $value );
	}
}
