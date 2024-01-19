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
use Snow_Monkey\Plugin\Forms\App\Helper;

class View {

	/**
	 * @var Setting
	 */
	protected $setting;

	/**
	 * @var Responser
	 */
	protected $responser;

	/**
	 * @var Validator
	 */
	protected $validator;

	/**
	 * Constructor.
	 *
	 * @param array $data Posted data.
	 */
	public function __construct( array $data ) {
		// Set form meta data and remove from post data.
		if ( isset( $data[ Meta::get_key() ] ) ) {
			$data[ Meta::get_key() ] = Meta::init( $data[ Meta::get_key() ] );
		}

		$this->setting   = DataStore::get( Meta::get_formid() );
		$this->responser = new Responser( $data );
		$this->validator = new Validator( $this->responser, $this->setting );
	}

	/**
	 * Return json for a form rendering.
	 *
	 * @return json
	 */
	public function send() {
		// CSRF token check.
		if ( ! Csrf::validate( Meta::get_token() ) ) {
			return $this->_send_systemerror( __( 'Invalid access.', 'snow-monkey-forms' ) );
		}

		$spam_validate = apply_filters( 'snow_monkey_forms/spam/validate', true );
		if ( ! $spam_validate ) {
			return $this->_send_systemerror( __( 'There is a possibility of spamming.', 'snow-monkey-forms' ) );
		}

		// File upload.
		try {
			$files = $this->_sanitize_files( $_FILES );
			if ( $files ) {
				$uploader = new FileUploader( $files );
				if ( $uploader->exist_file_controls() ) {
					$saved_files = $uploader->save_uploaded_files();
					foreach ( $saved_files as $name => $value ) {
						$this->responser->update( $name, $value );
					}
				}
			}
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
			return $this->_send_systemerror( __( 'An error occurred during file upload.', 'snow-monkey-forms' ) );
		}

		// If a file was removed, post data remove too.
		try {
			$file_names = $this->setting->get_file_names();
			$data       = $this->responser->get_all();
			foreach ( $file_names as $name ) {
				$user_file_dir = Directory::generate_user_file_dirpath( $name );
				if ( is_dir( $user_file_dir ) && ! empty( $data[ $name ] ) ) {
					$filepath = Directory::generate_user_filepath( $name, $data[ $name ] );
					if ( ! file_exists( $filepath ) ) {
						Directory::do_empty( $user_file_dir, true );
						$this->responser->update( $name, '' );
						throw new \RuntimeException( '[Snow Monkey Forms] File does not exist.' );
					}
				}
			}
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
			return $this->_send_systemerror( __( 'Attachment of file failed.', 'snow-monkey-forms' ) );
		}

		// Validate check.
		if ( ! $this->validator->validate() ) {
			Meta::set_method( 'invalid' );
			return $this->_send();
		}

		// Complete process.
		if ( 'complete' === Meta::get_method() ) {
			// Administrator email sending.
			$administrator_mailer = new AdministratorMailer( $this->responser, $this->setting );
			try {
				$administrator_mailer->send();
			} catch ( \Exception $e ) {
				error_log( $e->getMessage() );
				return $this->_send_systemerror(
					__( 'Failed to send administrator email.', 'snow-monkey-forms' ) .
					__( 'Please try again later or contact your administrator by other means.', 'snow-monkey-forms' )
				);
			}

			// Auto reply email sending.
			$auto_reply_mailer = new AutoReplyMailer( $this->responser, $this->setting );
			if ( $auto_reply_mailer->should_send() ) {
				try {
					$auto_reply_mailer->send();
				} catch ( \Exception $e ) {
					error_log( $e->getMessage() );
					return $this->_send_systemerror( __( 'Failed to send auto reply email.', 'snow-monkey-forms' ) );
				}
			}
		}

		return $this->_send();
	}

	/**
	 * Return json for a form rendering with systemerror.
	 *
	 * @param string $error_message System error message.
	 * @return json
	 */
	protected function _send_systemerror( $error_message = '' ) {
		Meta::set_method( 'systemerror' );
		$this->setting->set_system_error_message( $error_message );
		return $this->_send();
	}

	/**
	 * Return json for a form rendering.
	 *
	 * @return json
	 */
	protected function _send() {
		$method = Meta::get_method();
		if ( 'complete' === $method || 'systemerror' === $method ) {
			try {
				$form_id = Meta::get_formid();
				Directory::do_empty( Directory::generate_user_dirpath( $form_id ), true );
			} catch ( \Exception $e ) {
				error_log( $e->getMessage() );
				$this->setting->set_system_error_message(
					__( 'An unexpected problem has occurred.', 'snow-monkey-forms' ) .
					__( 'Please try again later or contact your administrator by other means.', 'snow-monkey-forms' )
				);
				$controller = Dispatcher::dispatch( 'systemerror', $this->responser, $this->setting, $this->validator );

				return $controller->send();
			}
		}

		try {
			$controller = Dispatcher::dispatch( $method, $this->responser, $this->setting, $this->validator );
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
			$this->setting->set_system_error_message(
				__( 'An unexpected problem has occurred.', 'snow-monkey-forms' ) .
				__( 'Please try again later or contact your administrator by other means.', 'snow-monkey-forms' )
			);
			$controller = Dispatcher::dispatch( 'systemerror', $this->responser, $this->setting, $this->validator );
		}

		return $controller->send();
	}

	/**
	 * Sanitize $_FILES for FileUploader.
	 *
	 * @param array $files $_FILES
	 * @return array
	 */
	protected function _sanitize_files( $files ) {
		$permitted_file_names = $this->setting->get_file_names();
		$new_files            = array();

		foreach ( $files as $name => $file ) {
			if ( in_array( $name, $permitted_file_names, true ) ) {
				$new_files[ $name ] = $file;
			}
		}

		return $new_files;
	}
}
