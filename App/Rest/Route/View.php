<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Rest\Route;

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

class View {

	public function __construct() {
	}

	public function send( array $data ) {
		// Set form meta data and remove from post data.
		if ( isset( $data[ Meta::get_key() ] ) ) {
			Meta::init( $data[ Meta::get_key() ] );
			unset( $data[ Meta::get_key() ] );
		}

		// Files upload
		$saved_files = $this->_save_uploaded_files();
		if ( is_array( $saved_files ) ) {
			$data = array_merge( $data, $saved_files );
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
			return $this->_send( $responser, $setting, $validator );
		}

		// CSRF token check.
		if ( ! Csrf::validate( Meta::get( '_token' ) ) ) {
			Meta::set( '_method', 'systemerror' );
			$error_message = __( 'Invalid access.', 'snow-monkey-forms' );
			$setting->set_system_error_message( $error_message );
			return $this->_send( $responser, $setting, $validator );
		}

		// Complete process.
		if ( 'complete' === Meta::get( '_method' ) ) {
			// Administrator email sending.
			$administrator_mailer = new AdministratorMailer( $responser, $setting );
			try {
				$administrator_mailer->send();
			} catch ( \Exception $e ) {
				error_log( $e->getMessage() );
				Meta::set( '_method', 'systemerror' );
				$error_message  = __( 'Failed to send administrator email.', 'snow-monkey-forms' );
				$error_message .= __( 'Please try again later or contact your administrator by other means.', 'snow-monkey-forms' );
				$setting->set_system_error_message( $error_message );
				return $this->_send( $responser, $setting, $validator );
			}

			// Auto reply email sending.
			$auto_reply_mailer = new AutoReplyMailer( $responser, $setting );
			if ( $auto_reply_mailer->should_send() ) {
				try {
					$auto_reply_mailer->send();
				} catch ( \Exception $e ) {
					error_log( $e->getMessage() );
					Meta::set( '_method', 'systemerror' );
					$error_message = __( 'Failed to send auto reply email.', 'snow-monkey-forms' );
					$setting->set_system_error_message( $error_message );
					return $this->_send( $responser, $setting, $validator );
				}
			}
		}

		return $this->_send( $responser, $setting, $validator );
	}

	protected function _send( $responser, $setting, $validator ) {
		$method = Meta::get( '_method' );

		if ( 'complete' === $method || 'systemerror' === $method ) {
			$this->_remove_saved_files( $responser );
		}

		try {
			$controller = Dispatcher::dispatch( $method, $responser, $setting, $validator );
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
			$error_message  = __( 'An unexpected problem has occurred.', 'snow-monkey-forms' );
			$error_message .= __( 'Please try again later or contact your administrator by other means.', 'snow-monkey-forms' );
			$setting->set_system_error_message( $error_message );
			$controller = Dispatcher::dispatch( 'systemerror', $responser, $setting, $validator );
		}

		return $controller->send();
	}

	protected function _save_uploaded_files() {
		$uploader = new FileUploader();

		if ( ! $uploader->exist_file_controls() ) {
			return false;
		}

		return $uploader->save_uploaded_files();
	}

	protected function _remove_saved_files( Responser $responser ) {
		foreach ( (array) Meta::get( '_saved_files' ) as $name ) {
			$saved_file = $responser->get( $name );
			if ( ! $saved_file ) {
				continue;
			}

			$file = Directory::fileurl_to_filepath( $saved_file );
			if ( ! file_exists( $file ) ) {
				continue;
			}

			try {
				Directory::remove( $file );
			} catch ( \Exception $e ) {
				error_log( $e->getMessage() );
			}
		}
	}
}
