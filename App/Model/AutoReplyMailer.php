<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Helper;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Setting;
use Snow_Monkey\Plugin\Forms\App\Model\MailParser;
use Snow_Monkey\Plugin\Forms\App\Model\Mailer;

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
		$this->setting = $setting;
	}

	public function should_send() {
		$mail_parser = new MailParser( $this->responser, $this->setting );
		$to = $mail_parser->parse( $this->setting->get( 'auto_reply_email_to' ) );
		return ! is_null( $to ) && '' !== $to;
	}

	public function send() {
		try {
			$is_sended = $this->_send();
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
			$is_sended = false;
		}

		return $is_sended;
	}

	protected function _send() {
		$mail_parser = new MailParser( $this->responser, $this->setting );

		$attachments = [];
		foreach ( (array) Meta::get( '_saved_files' ) as $saved_file ) {
			$attachments[] = Helper::saved_fileurl_to_filepath( $saved_file );
		}

		$mailer = new Mailer(
			[
				'to'          => $mail_parser->parse( $this->setting->get( 'auto_reply_email_to' ) ),
				'subject'     => $mail_parser->parse( $this->setting->get( 'auto_reply_email_subject' ) ),
				'body'        => $mail_parser->parse( $this->setting->get( 'auto_reply_email_body' ) ),
				'attachments' => $attachments,
			]
		);

		$is_sended = $mailer->send();

		if ( ! $is_sended ) {
			throw new \Exception( '[Snow Monkey Forms] Failed to send auto reply email.' );
		}

		return $is_sended;
	}
}
