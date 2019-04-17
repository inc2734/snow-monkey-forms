<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;
use Snow_Monkey\Plugin\Forms\App\Model\AdministratorMailer;
use Snow_Monkey\Plugin\Forms\App\Model\AutoReplyMailer;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;

$data    = filter_input_array( INPUT_POST );
$form_id = $data['_formid'];
$setting = DataStore::get( $form_id );

$responser = new Responser( $data );
$validator = new Validator( $responser, $setting );

if ( ! $validator->validate() ) {
	$data['_method'] = 'error';
}

if ( ! Csrf::validate() ) {
	$data['_method'] = 'system-error';
	$setting->set_system_error_message( __( 'Invalid access.', 'snow-monkey-forms' ) );
}

if ( 'complete' === $data['_method'] ) {
	$administrator_mailer = new AdministratorMailer( $responser, $setting );
	$is_administrator_mail_sended = $administrator_mailer->send();
	if ( ! $is_administrator_mail_sended ) {
		$data['_method'] = 'system-error';
		$setting->set_system_error_message( __( 'Failed to send administrator email.', 'snow-monkey-forms' ) );
	}

	$auto_reply_mailer = new AutoReplyMailer( $responser, $setting );
	if ( $auto_reply_mailer->should_send() ) {
		$is_auto_reply_mail_sended = $auto_reply_mailer->send();
		if ( ! $is_auto_reply_mail_sended ) {
			$data['_method'] = 'system-error';
			$setting->set_system_error_message( __( 'Failed to send auto reply email.', 'snow-monkey-forms' ) );
		}
	}
}

$controller = Dispatcher::dispatch( $data['_method'], $responser, $setting, $validator );
$controller->send();
