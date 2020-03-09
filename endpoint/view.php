<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\FileUploader;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;
use Snow_Monkey\Plugin\Forms\App\Model\AdministratorMailer;
use Snow_Monkey\Plugin\Forms\App\Model\AutoReplyMailer;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;

$data = filter_input_array( INPUT_POST );
if ( isset( $data[ Meta::get_key() ] ) ) {
	Meta::save( $data[ Meta::get_key() ] );
	unset( $data[ Meta::get_key() ] );
}

$setting = DataStore::get( Meta::get( '_formid' ) );

$uploader = new FileUploader();
$uploaded_files = [];
if ( $uploader->is_uploading() ) {
	$files = $uploader->get_uploaded_files();
	foreach ( $files as $name => $file ) {
		$file_url = $file->save();
		if ( ! $file_url ) {
			continue;
		}

		$data[ $name ] = $file_url;

		Meta::save(
			array_merge(
				Meta::get_all(),
				[
					'_saved_files' => [ $name => $file_url ],
				]
			)
		);
	}
}

$responser = new Responser( $data );
$validator = new Validator( $responser, $setting );

if ( ! $validator->validate() ) {
	Meta::save( array_merge( Meta::get_all(), [ '_method' => 'error' ] ) );
}

if ( ! Csrf::validate( Meta::get( '_token' ) ) ) {
	Meta::save( array_merge( Meta::get_all(), [ '_method' => 'systemerror' ] ) );
	$setting->set_system_error_message( __( 'Invalid access.', 'snow-monkey-forms' ) );
}

if ( 'complete' === Meta::get( '_method' ) ) {
	$administrator_mailer = new AdministratorMailer( $responser, $setting );
	$is_administrator_mail_sended = $administrator_mailer->send();
	if ( ! $is_administrator_mail_sended ) {
		Meta::save( array_merge( Meta::get_all(), [ '_method' => 'systemerror' ] ) );
		$setting->set_system_error_message( __( 'Failed to send administrator email.', 'snow-monkey-forms' ) );
	}

	$auto_reply_mailer = new AutoReplyMailer( $responser, $setting );
	if ( $auto_reply_mailer->should_send() ) {
		$is_auto_reply_mail_sended = $auto_reply_mailer->send();
		if ( ! $is_auto_reply_mail_sended ) {
			Meta::save( array_merge( Meta::get_all(), [ '_method' => 'systemerror' ] ) );
			$setting->set_system_error_message( __( 'Failed to send auto reply email.', 'snow-monkey-forms' ) );
		}
	}
}

$controller = Dispatcher::dispatch( Meta::get( '_method' ), $responser, $setting, $validator );
$controller->send();
