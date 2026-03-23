<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Service\BlockedSender;

use Snow_Monkey\Plugin\Forms\App\Model\MailParser;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Setting;

class BlockedSender {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'snow_monkey_forms/spam/validate', array( $this, '_validate' ), 10, 3 );
	}

	/**
	 * Validate sender email by blocked sender list.
	 *
	 * @param boolean   $is_valid Return true if valid.
	 * @param Responser $responser Responser object.
	 * @param Setting   $setting Setting object.
	 * @return boolean|\WP_Error
	 */
	public function _validate( $is_valid, $responser, $setting ) {
		if ( ! $is_valid ) {
			return $is_valid;
		}

		$target_email_setting = trim( (string) $setting->get( 'blocked_sender_source' ) );
		if ( '' === $target_email_setting ) {
			return $is_valid;
		}

		if ( ! preg_match( '/^{[^{}]+}$/', $target_email_setting ) ) {
			return $is_valid;
		}

		$mail_parser      = new MailParser( $responser, $setting );
		$raw_sender_email = $mail_parser->parse( $target_email_setting );

		$sender_email = sanitize_email( $raw_sender_email );
		if ( '' === $sender_email || false === strpos( $sender_email, '@' ) ) {
			return $is_valid;
		}

		$sender_email  = strtolower( $sender_email );
		$sender_domain = strtolower( substr( strrchr( $sender_email, '@' ), 1 ) );
		if ( '' === $sender_domain ) {
			return $is_valid;
		}

		$blocked_sender_list = json_decode( (string) $setting->get( 'blocked_sender_list' ), true );
		if ( ! is_array( $blocked_sender_list ) || ! $blocked_sender_list ) {
			return $is_valid;
		}

		foreach ( $blocked_sender_list as $blocked_sender ) {
			if ( ! is_string( $blocked_sender ) ) {
				continue;
			}

			$blocked_sender = strtolower( trim( $blocked_sender ) );
			if ( ! $blocked_sender ) {
				continue;
			}

			if ( false !== strpos( $blocked_sender, '@' ) ) {
				if ( $blocked_sender === $sender_email ) {
					return false;
				}
				continue;
			}

			if ( $sender_domain === $blocked_sender ) {
				return false;
			}
		}

		return $is_valid;
	}
}
