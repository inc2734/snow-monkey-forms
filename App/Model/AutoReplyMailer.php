<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Model\Mailer;
use Snow_Monkey\Plugin\Forms\App\Model\MailParser;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Setting;

class AutoReplyMailer {

	/**
	 * @var Responser
	 */
	protected $responser;

	/**
	 * @var Setting
	 */
	protected $setting;

	/**
	 * Constructor.
	 *
	 * @param Responser $responser Responser object.
	 * @param Setting   $setting   Setting object.
	 */
	public function __construct( Responser $responser, Setting $setting ) {
		$this->responser = $responser;
		$this->setting   = $setting;
	}

	/**
	 * Return true when should send.
	 *
	 * @return boolean
	 */
	public function should_send() {
		$mail_parser = new MailParser( $this->responser, $this->setting );
		$to          = $mail_parser->parse( $this->setting->get( 'auto_reply_email_to' ) );
		return ! is_null( $to ) && '' !== $to;
	}

	/**
	 * Send e-mail.
	 *
	 * @return boolean
	 */
	public function send() {
		$mail_parser = new MailParser( $this->responser, $this->setting );

		$skip = apply_filters(
			'snow_monkey_forms/auto_reply_mailer/skip',
			false,
			$this->responser,
			$this->setting
		);

		$is_sended = $skip
			? $this->_process_skip()
			: $this->_process_sending( $mail_parser );

		$is_sended = apply_filters(
			'snow_monkey_forms/auto_reply_mailer/is_sended',
			$is_sended,
			$this->responser,
			$this->setting
		);

		do_action(
			'snow_monkey_forms/auto_reply_mailer/after_send',
			$is_sended,
			$this->responser,
			$this->setting,
			$mail_parser
		);

		return $is_sended;
	}

	/**
	 * Skip send e-mail.
	 *
	 * @return boolean
	 */
	protected function _process_skip() {
		return true;
	}

	/**
	 * Get headers for wp_mail.
	 *
	 * @return array
	 */
	public function _get_headers() {
		return apply_filters(
			'snow_monkey_forms/auto_reply_mailer/headers',
			array(),
			$this->responser,
			$this->setting
		);
	}

	/**
	 * Send e-mail.
	 *
	 * @param MailParser $mail_parser MailParser object.
	 * @return true
	 * @throws \RuntimeException When sending an e-mail fails.
	 */
	protected function _process_sending( MailParser $mail_parser ) {
		$args = array(
			'to'          => $mail_parser->parse( $this->setting->get( 'auto_reply_email_to' ) ),
			'subject'     => $mail_parser->parse( $this->setting->get( 'auto_reply_email_subject' ) ),
			'body'        => $mail_parser->parse( $this->setting->get( 'auto_reply_email_body' ) ),
			'attachments' => array_values( $mail_parser->get_attachments( $this->setting->get( 'auto_reply_email_body' ) ) ),
			'replyto'     => $this->setting->get( 'auto_reply_email_replyto' ),
			'from'        => $this->setting->get( 'auto_reply_email_from' ),
			'sender'      => $this->setting->get( 'auto_reply_email_sender' ),
			'headers'     => $this->_get_headers(),
		);

		$args = apply_filters(
			'snow_monkey_forms/auto_reply_mailer/args',
			$args,
			$this->responser,
			$this->setting
		);

		$mailer = new Mailer( $args );

		$is_sended = $mailer->send();
		if ( ! $is_sended ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Failed to send auto reply email.' );
		}

		return $is_sended;
	}
}
