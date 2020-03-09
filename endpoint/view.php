<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\AdministratorMailer;
use Snow_Monkey\Plugin\Forms\App\Model\AutoReplyMailer;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Model\Directory;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;
use Snow_Monkey\Plugin\Forms\App\Model\FileUploader;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;

$data = filter_input_array( INPUT_POST );

// Set form meta data and remove from post data.
if ( isset( $data[ Meta::get_key() ] ) ) {
	Meta::init( $data[ Meta::get_key() ] );
	unset( $data[ Meta::get_key() ] );
}

// Files upload
$uploader = new FileUploader();
if ( $uploader->exist_file_controls() ) {
	$files = $uploader->get_uploaded_files();
	if ( $files ) {
		$saved_files = [];

		foreach ( $files as $name => $file ) {
			$file_url = $file->save();
			if ( $file_url ) {
				$saved_files[ $name ] = $file_url;
			}
		}

		$data = array_merge( $data, $saved_files );
		Meta::set( '_saved_files', array_keys( $saved_files ) );
	}
}

// If a file was removed, post data remove too.
foreach ( (array) Meta::get( '_saved_files' ) as $name ) {
	if ( isset( $data[ $name ] ) ) {
		$saved_file = $data[ $name ];
		$file = Directory::fileurl_to_filepath( $saved_file );
		if ( ! file_exists( $file ) ) {
			$data[ $name ] = null;
		}
	}
}

$setting   = DataStore::get( Meta::get( '_formid' ) );
$responser = new Responser( $data );
$validator = new Validator( $responser, $setting );

// Validate check.
if ( ! $validator->validate() ) {
	Meta::set( '_method', 'error' );
}

// CSRF token check.
if ( ! Csrf::validate( Meta::get( '_token' ) ) ) {
	Meta::set( '_method', 'systemerror' );
	$setting->set_system_error_message( __( 'Invalid access.', 'snow-monkey-forms' ) );
}

// Complete process.
if ( 'complete' === Meta::get( '_method' ) ) {
	// Administrator email sending.
	$administrator_mailer = new AdministratorMailer( $responser, $setting );
	$is_administrator_mail_sended = $administrator_mailer->send();
	if ( ! $is_administrator_mail_sended ) {
		Meta::set( '_method', 'systemerror' );
		$setting->set_system_error_message( __( 'Failed to send administrator email.', 'snow-monkey-forms' ) );
	}

	// Auto reply email sending.
	$auto_reply_mailer = new AutoReplyMailer( $responser, $setting );
	if ( $auto_reply_mailer->should_send() ) {
		$is_auto_reply_mail_sended = $auto_reply_mailer->send();
		if ( ! $is_auto_reply_mail_sended ) {
			Meta::set( '_method', 'systemerror' );
			$setting->set_system_error_message( __( 'Failed to send auto reply email.', 'snow-monkey-forms' ) );
		}
	}

	// Remove attachments.
	foreach ( (array) Meta::get( '_saved_files' ) as $name ) {
		$saved_file = $responser->get( $name );
		if ( ! $saved_file ) {
			continue;
		}

		$file = Directory::fileurl_to_filepath( $saved_file );
		if ( ! file_exists( $file ) ) {
			continue;
		}

		Directory::remove( $file );
	}
}

$controller = Dispatcher::dispatch( Meta::get( '_method' ), $responser, $setting, $validator );
$controller->send();
