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

	public function __construct( Responser $responser, Setting $setting ) {
		$this->responser = $responser;
		$this->setting   = $setting;
	}

	public function should_send() {
		$mail_parser = new MailParser( $this->responser, $this->setting );
		$to = $mail_parser->parse( $this->setting->get( 'auto_reply_email_to' ) );
		return ! is_null( $to ) && '' !== $to;
	}

	public function send() {
		$mail_parser = new MailParser( $this->responser, $this->setting );

		$mailer = new Mailer(
			[
				'to'          => $mail_parser->parse( $this->setting->get( 'auto_reply_email_to' ) ),
				'subject'     => $mail_parser->parse( $this->setting->get( 'auto_reply_email_subject' ) ),
				'body'        => $mail_parser->parse( $this->setting->get( 'auto_reply_email_body' ) ),
				'attachments' => $mail_parser->get_attachments( $this->setting->get( 'auto_reply_email_body' ) ),
				'from'        => $this->setting->get( 'auto_reply_email_from' ),
				'sender'      => $this->setting->get( 'auto_reply_email_sender' ),
			]
		);

		$is_sended = $mailer->send();

		if ( ! $is_sended ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Failed to send auto reply email.' );
		}

		do_action( 'snow_monkey_forms/auto_reply_mailer/after_send', $is_sended, $this->responser, $this->setting, $mail_parser );

		return $is_sended;
	}
}
