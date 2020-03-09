<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Helper;

class File {

	/**
	 * @var array
	 *   @var string name
	 *   @var string type
	 *   @var string tmp_name
	 *   @var int error
	 *   @var int size
	 */
	protected $file;

	public function __construct( $file ) {
		$this->file = $file;
	}

	/**
	 * @return false|int
	 */
	public function get_error() {
		return isset( $this->file['error'] ) && is_int( $this->file['error'] ) ? $this->file['error'] : false;
	}

	/**
	 * @return false|string
	 */
	protected function _get_name() {
		return isset( $this->file['name'] ) ? $this->file['name'] : false;
	}

	/**
	 * @return false|string
	 */
	protected function _get_tmp_name() {
		return isset( $this->file['tmp_name'] ) ? $this->file['tmp_name'] : false;
	}

	/**
	 * @return boolean
	 */
	protected function _move_to( $save_file_path ) {
		$tmp_name = $this->_get_tmp_name();
		if ( false === $tmp_name ) {
			return false;
		}
		return move_uploaded_file( $tmp_name, $save_file_path );
	}

	/**
	 * @todo このままだと、別人が同じファイル名でアップデートしたら上書きされてしまう。
	 *
	 * @return false|string
	 */
	protected function _get_filename() {
		$name = $this->_get_name();
		if ( false === $name ) {
			return false;
		}

		$basename  = basename( $name );
		$filename  = pathinfo( $basename, PATHINFO_FILENAME );
		$extension = pathinfo( $basename, PATHINFO_EXTENSION );
		$extension = preg_replace( '/[^0-9a-zA-Z]/', '', $extension );
		$filename_noext = urlencode( $filename );
		return sprintf( '%1$s.%2$s', $filename_noext, $extension );
	}

	public function save() {
		try {
			$filename = $this->_get_filename();
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

			$save_dir = Helper::get_save_dir();
			if ( ! $save_dir ) {
				throw new \RuntimeException( '[Snow Monkey Forms] Creation of a temporary directory for file upload failed.' );
			}

			$new_filepath = path_join( $save_dir, $filename );

			if ( ! $this->_move_to( $new_filepath ) ) {
				throw new \RuntimeException( '[Snow Monkey Forms] There was an error saving the uploaded file.' );
			}

			return path_join( Helper::get_save_dir_url(), $filename );
		} catch ( \RuntimeException $e ) {
			error_log( $e->getMessage() );
			return false;
		}
	}
}
