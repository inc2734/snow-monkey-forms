<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Model\File;

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

	public function get_uploaded_files() {
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
