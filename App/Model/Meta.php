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

	/**
	 * @var int
	 */
	protected static $formid;

	/**
	 * @var string
	 */
	protected static $token;

	/**
	 * @var string input|confirm|complete|invalid|systemerror
	 */
	protected static $method;

	/**
	 * @var WP_User
	 */
	protected static $sender;

	/**
	 * Constructor.
	 *
	 * @param array $data Posted meta data.
	 */
	private function __construct( $data ) {
		$properties = array_keys( get_class_vars( get_class( $this ) ) );
		foreach ( $data as $key => $value ) {
			if ( ! in_array( $key, $properties, true ) ) {
				continue;
			}

			if ( is_array( static::$$key ) && is_array( $value ) ) {
				static::$$key = $value;
			} elseif ( ! is_array( static::$$key ) && ! is_array( $value ) ) {
				static::$$key = $value;
			}
		}
	}

	/**
	 * Initialize.
	 *
	 * @param array $data Posted meta data.
	 */
	public static function init( $data ) {
		if ( is_null( static::$singleton ) ) {
			static::$singleton = new Meta( $data );
		}
		return static::$singleton;
	}

	/**
	 * Return static meta key.
	 *
	 * @return string
	 */
	public static function get_key() {
		return static::KEY;
	}

	/**
	 * Display hidden field for multiple data.
	 *
	 * @param string $name   The meta name.
	 * @param array  $values The meta values.
	 */
	protected static function _the_meta_multiple( $name, array $values ) {
		foreach ( $values as $key => $value ) {
			Helper::the_control(
				'hidden',
				array(
					'attributes' => array(
						'name'  => static::get_key() . '[' . $name . '][' . $key . ']',
						'value' => $value,
					),
				)
			);
		}
	}

	/**
	 * Display hidden field.
	 *
	 * @param string $name  The meta name.
	 * @param string $value The meta value.
	 */
	protected static function _the_meta( $name, $value ) {
		Helper::the_control(
			'hidden',
			array(
				'attributes' => array(
					'name'  => static::get_key() . '[' . $name . ']',
					'value' => $value,
				),
			)
		);
	}

	/**
	 * Display action button.
	 *
	 * @param string $action You will be given one of these.
	 *                       input|confirm|complete|invalid|systemerror.
	 * @param string $label  The button label.
	 */
	public static function the_meta_button( $action, $label ) {
		Helper::the_control(
			'button',
			array(
				'attributes' => array(
					'data-action' => $action,
				),
				'label'      => $label . '<span class="smf-sending" aria-hidden="true"></span>',
			)
		);
	}

	/**
	 * Return form ID.
	 *
	 * @return int
	 */
	public static function get_formid() {
		return static::$formid;
	}

	/**
	 * Display hidden field for form ID.
	 *
	 * @param int $form_id The form ID.
	 */
	public static function the_formid( $form_id ) {
		static::_the_meta( 'formid', $form_id );
	}

	/**
	 * Return token.
	 *
	 * @return string
	 */
	public static function get_token() {
		return static::$token;
	}

	/**
	 * Display hidden field for token.
	 */
	public static function the_token() {
		static::_the_meta( 'token', Csrf::token() );
	}

	/**
	 * Return method.
	 *
	 * @return string
	 */
	public static function get_method() {
		return static::$method;
	}

	/**
	 * Set method.
	 *
	 * @param string $value You will be given one of these.
	 *                      input|confirm|complete|invalid|systemerror.
	 */
	public static function set_method( $value ) {
		static::$method = $value;
	}

	/**
	 * Display hidden field for method.
	 *
	 * @param string $value You will be given one of these.
	 *                      input|confirm|complete|invalid|systemerror.
	 */
	public static function the_method( $value ) {
		static::_the_meta( 'method', $value );
	}

	/**
	 * Return sender.
	 *
	 * @return WP_User
	 */
	public static function get_sender() {
		return static::$sender;
	}
}
