<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Helper;

class Csrf {

	const KEY = '_snow-monkey-forms-token';

	/**
	 * @var string
	 */
	private static $token = '';

	/**
	 * Validate.
	 *
	 * @param string $posted_token Posted token.
	 * @return boolean
	 */
	public static function validate( $posted_token ) {
		if ( ! $posted_token ) {
			return false;
		}

		if ( ! Helper::is_valid_token_format( $posted_token ) ) {
			return false;
		}

		$cookie_token = static::saved_token();
		return ! is_null( $cookie_token ) && ! is_null( $posted_token ) && hash_equals( $cookie_token, $posted_token );
	}

	/**
	 * Save token to the cookie.
	 */
	public static function save_token() {
		$saved_token   = static::saved_token();
		static::$token = $saved_token ? $saved_token : static::generate_token();

		if ( ! $saved_token && ! headers_sent() ) {
			static::_setcookie( static::$token, 0 );
		}
	}

	/**
	 * Remove token.
	 */
	public static function remove_token() {
		static::$token = '';

		if ( ! headers_sent() ) {
			static::_setcookie( static::$token, time() - 3600 );
		}
	}

	/**
	 * Set cookie.
	 *
	 * Once the cookies have been set, they can be accessed on the next page load with the $_COOKIE array.
	 *
	 * @param string $value The value.
	 * @param int $expires_or_options The time the cookie expires.
	 */
	protected static function _setcookie( $value, $expires_or_options ) {
		setcookie( static::KEY, $value, $expires_or_options, '/', parse_url( home_url(), PHP_URL_HOST ), false, true );
	}

	/**
	 * Return token saved in the cookie.
	 *
	 * Once the cookies have been set, they can be accessed on the next page load with the $_COOKIE array.
	 *
	 * @return string
	 */
	public static function saved_token() {
		$token_in_cookie = filter_input( INPUT_COOKIE, static::KEY );

		return $token_in_cookie
			? $token_in_cookie
			: static::$token;
	}

	/**
	 * Generate token.
	 * Only alphanumeric values are allowed for token values.
	 *
	 * @return string
	 */
	public static function generate_token() {
		if ( function_exists( 'random_bytes' ) ) {
			// phpcs:disable PHPCompatibility.FunctionUse.NewFunctions.random_bytesFound
			return bin2hex( random_bytes( 32 ) );
			// phpcs:enable
		}

		if ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
			return bin2hex( openssl_random_pseudo_bytes( 32 ) );
		}

		return bin2hex( uniqid( mt_rand(), true ) );
	}

	/**
	 * If valid referer (Same origin), return true.
	 *
	 * @return boolean
	 */
	public static function validate_referer() {
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$referer = isset( $_SERVER['HTTP_REFERER'] ) ? wp_unslash( $_SERVER['HTTP_REFERER'] ) : false;
		// phpcs:enable
		$homeurl = untrailingslashit( home_url( '/' ) );

		return 0 === strpos( $referer, $homeurl );
	}
}
