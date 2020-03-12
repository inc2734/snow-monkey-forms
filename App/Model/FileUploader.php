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

	public function __construct() {
		$this->files = $_FILES;
	}

	public function exist_file_controls() {
		return ! ! array_keys( $this->files );
	}

	public function save_uploaded_files() {
		$files = $this->_get_uploaded_files();
		if ( ! $files ) {
			return false;
		}

		$saved_files = [];

		foreach ( $files as $name => $file ) {
			$file_url = $file->save();
			if ( $file_url ) {
				$saved_files[ $name ] = $file_url;
			}
		}

		Meta::set( '_saved_files', array_keys( $saved_files ) );
		return $saved_files;
	}

	protected function _get_uploaded_files() {
		$files = [];

		foreach ( $this->files as $name => $file ) {
			$uploaded_file = new File( $file );
			if ( UPLOAD_ERR_NO_FILE === $uploaded_file->get_error() ) {
				continue;
			}

			$files[ $name ] = $uploaded_file;
		}

		return $files;
	}
}
