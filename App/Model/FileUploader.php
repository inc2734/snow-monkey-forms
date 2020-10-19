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

		$saved_files = [];
		foreach ( $files as $name => $file ) {
			$fileurl = $file->save( sprintf( '%1$s-%2$s', $name, $file->get_filename() ) );
			if ( $fileurl ) {
				$saved_files[ $name ] = $fileurl;
			}
		}

		Meta::set_saved_files( array_merge( Meta::get_saved_files(), array_keys( $saved_files ) ) );
		return $saved_files;
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
}
