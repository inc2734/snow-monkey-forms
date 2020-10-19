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
	private static $token;

	/**
	 * Validate.
	 *
	 * @param string $posted_token Posted token.
	 * @return boolean
	 */
	public static function validate( $posted_token ) {
		if ( empty( $posted_token ) ) {
			return false;
		}

		$cookie_token = static::saved_token();
		return ! is_null( $cookie_token ) && ! is_null( $posted_token ) && hash_equals( $cookie_token, $posted_token );
	}

	/**
	 * Save token to the cookie.
	 */
	public static function save_token() {
		static::$token = ! static::saved_token() ? static::generate_token() : static::saved_token();
		if ( ! static::saved_token() && ! headers_sent() ) {
			setcookie( static::KEY, static::token(), 0, '/' );
		}
	}

	/**
	 * Return set token.
	 *
	 * @return string
	 */
	public static function token() {
		return static::$token;
	}

	/**
	 * Return token saved in the cookie.
	 *
	 * @return string
	 */
	public static function saved_token() {
		return filter_input( INPUT_COOKIE, static::KEY );
	}

	/**
	 * Generate token.
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

		return uniqid( mt_rand(), true );
	}
}
