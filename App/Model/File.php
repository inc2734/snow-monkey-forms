<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Model\Directory;

class File {

	/**
	 * @var array $_FILE
	 */
	protected $file;

	/**
	 * Constructor.
	 *
	 * @param array $file $_FILE.
	 */
	public function __construct( array $file ) {
		$this->file = $file;
	}

	/**
	 * Return $_FILE error.
	 *
	 * @return false|int
	 */
	public function get_error() {
		return isset( $this->file['error'] ) && is_int( $this->file['error'] ) ? $this->file['error'] : false;
	}

	/**
	 * Return $_FILE name.
	 *
	 * @return false|string
	 */
	public function get_filename() {
		return isset( $this->file['name'] ) ? $this->_sanitized_file_name( $this->file['name'] ) : false;
	}

	/**
	 * Return $_FILE tmp_name.
	 *
	 * @return false|string
	 */
	protected function _get_tmp_name() {
		return isset( $this->file['tmp_name'] ) ? $this->file['tmp_name'] : false;
	}

	/**
	 * Moves an uploaded file to a new location.
	 *
	 * @param array $destination The destination of the moved file.
	 * @return boolean
	 */
	protected function _move_to( $destination ) {
		$tmp_name = $this->_get_tmp_name();

		return false === $tmp_name
			? false
			: move_uploaded_file( $tmp_name, $destination );
	}

	/**
	 * Return sanitized file name.
	 *
	 * @param string $filename The file name.
	 * @return false|string
	 */
	protected function _sanitized_file_name( $filename ) {
		if ( false === $filename ) {
			return false;
		}

		return sanitize_file_name( basename( $filename ) );
	}

	/**
	 * Save the file.
	 *
	 * @param string $filename Posted file name.
	 * @return string
	 * @throws \RuntimeException When the file upload fails.
	 */
	public function save( $filename ) {
		$filename = $this->_sanitized_file_name( $filename );
		$error    = $this->get_error();

		if ( false === $error || false === $filename ) {
			throw new \RuntimeException( '[Snow Monkey Forms] An error occurred during file upload.' );
		}

		if ( UPLOAD_ERR_OK !== $error && UPLOAD_ERR_NO_FILE !== $error ) {
			if ( UPLOAD_ERR_INI_SIZE === $error || UPLOAD_ERR_FORM_SIZE === $error ) {
				throw new \RuntimeException( '[Snow Monkey Forms] File size of the uploaded file is too large.' );
			}
			throw new \RuntimeException( '[Snow Monkey Forms] An error occurred during file upload.' );
		}

		$save_dir = Directory::get();
		if ( ! $save_dir ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Creation of a temporary directory for file upload failed.' );
		}

		do {
			$rand_max = mt_getrandmax();
			$rand     = zeroise( mt_rand( 0, $rand_max ), strlen( $rand_max ) );
			$uniqid   = md5( uniqid( $rand, true ) );
			$save_dir = path_join( $save_dir, $uniqid . $rand );

			if ( ! file_exists( $save_dir ) ) {
				break;
			}
		} while ( 0 );

		if ( ! wp_mkdir_p( $save_dir ) ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Creation of a temporary directory for file upload failed.' );
		}

		$new_filepath = path_join( $save_dir, $filename );

		if ( ! $this->_move_to( $new_filepath ) ) {
			throw new \RuntimeException( '[Snow Monkey Forms] There was an error saving the uploaded file.' );
		}

		return Directory::filepath_to_fileurl( $new_filepath );
	}
}
