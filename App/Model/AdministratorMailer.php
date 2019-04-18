<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Setting;
use Snow_Monkey\Plugin\Forms\App\Model\MailParser;
use Snow_Monkey\Plugin\Forms\App\Model\Mailer;

class AdministratorMailer {

	protected $responser;
	protected $setting;

	public function __construct( Responser $responser, Setting $setting ) {
		$this->responser = $responser;
		$this->setting = $setting;
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
		$mail_parser = new MailParser( $this->responser );

		$mailer = new Mailer(
			[
				'to'      => $this->setting->get( 'administrator_email_to' ),
				'subject' => $mail_parser->parse( $this->setting->get( 'administrator_email_subject' ) ),
				'body'    => $mail_parser->parse( $this->setting->get( 'administrator_email_body' ) ),
			]
		);

		$is_sended = $mailer->send();

		if ( ! $is_sended ) {
			throw new \Exception( '[Snow Monkey Forms] Failed to send administrator email.' );
		}

		return $is_sended;
	}
}
