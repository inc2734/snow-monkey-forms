<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Validation;

class Mailer {

	/**
	 * @var string
	 */
	protected $to = '';

	/**
	 * @var string
	 */
	protected $subject = '';

	/**
	 * @var string
	 */
	protected $body = '';

	/**
	 * @var string
	 */
	protected $from = '';

	/**
	 * @var string
	 */
	protected $sender = '';

	/**
	 * @var array Array of filepath.
	 */
	protected $attachments = [];

	/**
	 * Constructor.
	 *
	 * @param array $args Array of properties.
	 */
	public function __construct( $args ) {
		$properties = array_keys( get_object_vars( $this ) );
		foreach ( $args as $key => $value ) {
			if ( ! in_array( $key, $properties, true ) ) {
				continue;
			}

			if ( is_array( $this->$key ) && is_array( $value ) ) {
				$this->$key = $value;
			} elseif ( ! is_array( $this->$key ) && ! is_array( $value ) ) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * Set from.
	 *
	 * @param string $from_email From.
	 * @return string
	 */
	public function _wp_mail_from( $from_email ) {
		if ( empty( $this->from ) ) {
			return $from_email;
		}

		$is_valid = Validation\Email::validate( $this->from );
		if ( ! $is_valid ) {
			return $from_email;
		}

		return $this->from;
	}

	/**
	 * Set from name.
	 *
	 * @param string $from_name From name.
	 * @return string
	 */
	public function _wp_mail_from_name( $from_name ) {
		return empty( $this->sender ) ? $from_name : $this->sender;
	}

	/**
	 * Send e-mail.
	 *
	 * @return boolean
	 */
	public function send() {
		if ( ! $this->to ) {
			return false;
		}

		add_filter( 'wp_mail_from', [ $this, '_wp_mail_from' ] );
		add_filter( 'wp_mail_from_name', [ $this, '_wp_mail_from_name' ] );

		$is_sended = wp_mail(
			$this->to,
			$this->subject,
			$this->body,
			[],
			$this->attachments
		);

		remove_filter( 'wp_mail_from', [ $this, '_wp_mail_from' ] );
		remove_filter( 'wp_mail_from_name', [ $this, '_wp_mail_from_name' ] );

		return $is_sended;
	}
}
