<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Model\File;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;

class FileUploader {

	/**
	 * $_FILES
	 *
	 * @var array
	 */
	protected $files = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->files = $_FILES;
	}

	/**
	 * Uploader error codes.
	 *
	 * @return array
	 */
	public static function get_error_codes() {
		return [
			1 => 'Disallowed file type.',
		];
	}

	/**
	 * Return ture if has error code.
	 *
	 * @param mixed $value Posted value.
	 * @return boolean
	 */
	public static function has_error_code( $value ) {
		return preg_match( '|^\d+$|', $value ) && array_key_exists( intval( $value ), static::get_error_codes() );
	}

	/**
	 * Set error code.
	 *
	 * @see static::get_error_codes()
	 *
	 * @param array $saved_files Array of saved files.
	 * @return array
	 */
	public function set_error_code( $saved_files ) {
		$files = $this->_get_uploaded_files();
		if ( ! $files ) {
			return $saved_files;
		}

		foreach ( $files as $name => $file ) {
			if ( ! $this->_check_file_type( $file->get_tmp_name(), $file->get_filename() ) ) {
				$saved_files[ $name ] = 1;
			}
		}

		return $saved_files;
	}

	/**
	 * Return true if exist input[type="file"].
	 *
	 * @return boolean
	 */
	public function exist_file_controls() {
		return ! ! array_keys( $this->files );
	}

	/**
	 * Save uploaded files.
	 *
	 * @return false|array Array of file url.
	 */
	public function save_uploaded_files() {
		$files = $this->_get_uploaded_files();
		if ( ! $files ) {
			return false;
		}

		$failed_saved_files = $this->set_error_code( [] );

		$saved_files = [];
		foreach ( $files as $name => $file ) {
			if ( array_key_exists( $name, $failed_saved_files ) ) {
				continue;
			}

			$fileurl = $file->save( sprintf( '%1$s-%2$s', $name, $file->get_filename() ) );
			if ( $fileurl ) {
				$saved_files[ $name ] = $fileurl;
			}
		}

		Meta::set_saved_files(
			array_unique(
				array_merge(
					Meta::get_saved_files(),
					array_keys( $saved_files )
				)
			)
		);

		return array_merge( $saved_files, $failed_saved_files );
	}

	/**
	 * Return array of File.
	 *
	 * @return array Array of File object.
	 */
	protected function _get_uploaded_files() {
		$files = [];

		foreach ( $this->files as $name => $file_array ) {
			$file = new File( $file_array );
			if ( UPLOAD_ERR_NO_FILE === $file->get_error() ) {
				continue;
			}

			$files[ $name ] = $file;
		}

		return $files;
	}

	/**
	 * Return true if the file is allowed.
	 *
	 * @param string $filepath $_FILE tmp_name.
	 * @param string $filename $_FILE name.
	 * @return boolean
	 */
	protected function _check_file_type( $filepath, $filename ) {
		$wp_check_filetype = $filename
			? wp_check_filetype( $filename )
			: wp_check_filetype( $filepath );

		if ( ! $wp_check_filetype['type'] ) {
			return false;
		}

		if ( ! file_exists( $filepath ) ) {
			return false;
		}

		switch ( $wp_check_filetype['ext'] ) {
			case 'avi':
				$wp_check_filetype['type'] = [
					'application/x-troff-msvideo',
					'video/avi',
					'video/msvideo',
					'video/x-msvideo',
				];
				break;
			case 'mp3':
				$wp_check_filetype['type'] = [
					'audio/mpeg3',
					'audio/x-mpeg3',
					'video/mpeg',
					'video/x-mpeg',
					'audio/mpeg',
				];
				break;
			case 'mpg':
				$wp_check_filetype['type'] = [
					'audio/mpeg',
					'video/mpeg',
				];
				break;
			case 'docx':
				$wp_check_filetype['type'] = [
					$wp_check_filetype['type'],
					'application/zip',
					'application/msword',
				];
				break;
			case 'xlsx':
				$wp_check_filetype['type'] = [
					$wp_check_filetype['type'],
					'application/zip',
					'application/excel',
					'application/msexcel',
					'application/vnd.ms-excel',
				];
				break;
			case 'pptx':
				$wp_check_filetype['type'] = [
					$wp_check_filetype['type'],
					'application/zip',
					'application/mspowerpoint',
					'application/powerpoint',
					'application/ppt',
				];
				break;
		}

		$finfo = new \finfo( FILEINFO_MIME_TYPE );
		$type  = $finfo->file( $filepath );
		if ( false === $finfo ) {
			return false;
		}

		if ( is_array( $wp_check_filetype['type'] ) ) {
			if ( ! in_array( $type, $wp_check_filetype['type'], true ) ) {
				return false;
			}
		} else {
			if ( $type !== $wp_check_filetype['type'] ) {
				return false;
			}
		}

		return true;
	}
}
