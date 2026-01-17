<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use SplFileInfo;
use FilesystemIterator;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Directory {

	/**
	 * Return the path to the directory where the files are saved.
	 *
	 * @return string
	 * @throws \RuntimeException When can't create upload base directory.
	 */
	public static function get() {
		$upload_dir = wp_get_upload_dir();
		$save_dir   = path_join( $upload_dir['basedir'], 'smf-uploads' );

		$is_created = wp_mkdir_p( $save_dir ) ? $save_dir : false;
		$is_created = $is_created ? static::_create_htaccess( $save_dir ) : false;

		if ( ! $is_created ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Can\'t create upload base directory.' );
		}

		return $save_dir;
	}

	/**
	 * Return the path to the user directory.
	 *
	 * @param int $form_id Form ID.
	 * @param boolean $do_create_directory Create user directory if true.
	 * @return string
	 * @throws \RuntimeException When directory name is not token value.
	 */
	public static function generate_user_dirpath( $form_id, $do_create_directory = true ) {
		$saved_token = Csrf::saved_token();

		if ( ! Helper::is_valid_token_format( $saved_token ) ) {
			throw new \RuntimeException(
				sprintf(
					'[Snow Monkey Forms] Failed to generate user directory path. The directory name is "%1$s"',
					esc_html( $saved_token )
				)
			);
		}

		$form_id = Helper::sanitize_form_id( $form_id );
		if ( false === $form_id ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Invalid form ID.' );
		}

		$user_dir = path_join( static::get(), $saved_token );
		$user_dir = path_join( $user_dir, (string) $form_id );

		if ( $do_create_directory && ! wp_mkdir_p( $user_dir ) ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Can\'t create user directory.' );
		}

		return $user_dir;
	}

	/**
	 * Return the path to the user directory where the files are saved.
	 *
	 * @param string $name The name attribute value.
	 * @return string
	 * @throws \RuntimeException When directory name is not token value.
	 */
	public static function generate_user_file_dirpath( $name ) {
		$form_id       = Meta::get_formid();
		$user_dir      = static::generate_user_dirpath( $form_id );
		$user_file_dir = path_join( $user_dir, $name );

		if ( ! wp_mkdir_p( $user_file_dir ) ) {
			throw new \RuntimeException(
				sprintf(
					'[Snow Monkey Forms] Can\'t create user directory for %1$s.',
					esc_html( $name )
				)
			);
		}

		return $user_file_dir;
	}

	/**
	 * Returns true if the directory is empty.
	 *
	 * @param string $dir Target directory.
	 * @return boolean
	 */
	public static function is_empty( $dir ) {
		$iterator = new \FilesystemIterator( $dir );
		return ! $iterator->valid();
	}

	/**
	 * Empty the directory.
	 *
	 * @param string $dir Target directory.
	 * @param boolean $force Ignore the survival period.
	 * @return boolean
	 */
	public static function do_empty( $dir, $force = false ) {
		if ( false === $dir ) {
			return false;
		}

		if ( ! static::_is_within_expected_dir( $dir ) ) {
			return false;
		}

		return static::_remove_children( $dir, $force );
	}

	/**
	 * Remove child directories and files.
	 * Callers should ensure the path is within the upload base directory.
	 *
	 * @param string  $dir   Target directory.
	 * @param boolean $force Ignore the survival period.
	 * @return boolean
	 * @throws \Exception If deletion of the directory fails.
	 */
	protected static function _remove_children( $dir, $force = false ) {
		$fileinfo = new SplFileInfo( $dir );
		if ( ! $fileinfo->isDir() ) {
			return false;
		}

		$iterator = new FilesystemIterator( $dir );

		foreach ( $iterator as $fileinfo ) {
			$path = $fileinfo->getPathname();

			if ( $fileinfo->isDir() ) {
				if ( static::_remove_children( $path, $force ) && ( $force || static::_is_removable( $path ) ) ) {
					static::remove( $path );
				}
			} elseif ( $fileinfo->isFile() ) {
				if ( $force || static::_is_removable( $path ) ) {
					static::remove( $path );
				}
			}
		}

		return true;
	}

	/**
	 * Takes a file name and returns the file path.
	 * If the file name is invalid, the file path is not returned.
	 * The presence or absence of files is not determined.
	 *
	 * @param string $name The name attribute value.
	 * @param string $filename The filename.
	 * @return string
	 * @throws \RuntimeException When an invalid file reference is requested.
	 */
	public static function generate_user_filepath( $name, $filename ) {
		if ( ! $filename ) {
			return false;
		}

		$filepath = path_join( static::generate_user_file_dirpath( $name ), $filename );

		if ( str_contains( $filepath, '../' ) || str_contains( $filepath, '..' . DIRECTORY_SEPARATOR ) ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Invalid file reference requested.' );
		}

		if ( str_contains( $filepath, './' ) || str_contains( $filepath, '.' . DIRECTORY_SEPARATOR ) ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Invalid file reference requested.' );
		}

		if ( strstr( $filepath, "\0" ) ) {
			throw new \RuntimeException( '[Snow Monkey Forms] Invalid file reference requested.' );
		}

		return $filepath;
	}

	/**
	 * Returns a list of saved file paths.
	 *
	 * @param array $file_names The file control names list.
	 * @return array
	 */
	public static function get_saved_files( $file_names ) {
		$saved_files = array();

		foreach ( $file_names as $name ) {
			$iterator = new FilesystemIterator( static::generate_user_file_dirpath( $name ) );

			foreach ( $iterator as $file ) {
				$saved_files[ $name ] = $file->getPathname();
			}
		}

		return $saved_files;
	}

	/**
	 * Remove the file.
	 *
	 * @param string $file The file path.
	 * @return boolean
	 * @throws \RuntimeException If the deletion of a file fails.
	 */
	public static function remove( $file ) {
		if ( ! static::_is_within_expected_dir( $file ) ) {
			return false;
		}

		$fileinfo = new SplFileInfo( $file );

		if ( $fileinfo->isFile() && is_writable( $file ) ) {
			if ( ! unlink( $file ) ) {
				throw new \RuntimeException( sprintf( '[Snow Monkey Forms] Can\'t remove file: %1$s.', esc_html( $file ) ) );
			}
		} elseif ( $fileinfo->isDir() && is_writable( $file ) ) {
			if ( ! rmdir( $file ) ) {
				throw new \RuntimeException( sprintf( '[Snow Monkey Forms] Can\'t remove directory: %1$s.', esc_html( $file ) ) );
			}
		}

		return true;
	}

	/**
	 * Return true when file removable.
	 *
	 * @param string $file The file path.
	 * @return boolean
	 */
	protected static function _is_removable( $file ) {
		if ( ! file_exists( $file ) ) {
			return false;
		}

		if ( is_dir( $file ) ) {
			if ( ! static::is_empty( $file ) ) {
				return false;
			}
		}

		$mtime         = filemtime( $file );
		$survival_time = apply_filters( 'snow_monkey_forms/saved_files/survival_time', 60 * 15 );
		return ! $mtime || time() > $mtime + $survival_time;
	}

	/**
	 * Return true when path is inside upload base directory.
	 *
	 * @param string $path Target path.
	 * @return boolean
	 */
	protected static function _is_within_expected_dir( $path ) {
		$base_dir = realpath( static::get() );
		$realpath = realpath( $path );

		if ( false === $base_dir || false === $realpath ) {
			return false;
		}

		$token = Csrf::saved_token();
		if ( ! Helper::is_valid_token_format( $token ) ) {
			return false;
		}

		$form_id = Helper::sanitize_form_id( Meta::get_formid() );
		if ( false === $form_id ) {
			return false;
		}

		$base_dir = wp_normalize_path( $base_dir );
		$realpath = wp_normalize_path( $realpath );

		$user_dir       = wp_normalize_path(
			path_join(
				path_join( $base_dir, $token ),
				(string) $form_id
			)
		);
		$user_dir       = untrailingslashit( $user_dir );
		$user_dir_slash = trailingslashit( $user_dir );

		return $realpath === $user_dir || 0 === strpos( $realpath, $user_dir_slash );
	}

	/**
	 * Create .htaccess.
	 *
	 * @param string $save_dir The directory where .htaccess is created.
	 * @return true
	 * @throws \RuntimeException If the creation of .htaccess fails.
	 */
	protected static function _create_htaccess( $save_dir ) {
		$htaccess = path_join( $save_dir, '.htaccess' );
		if ( file_exists( $htaccess ) ) {
			return true;
		}

		$handle = fopen( $htaccess, 'w' );
		if ( ! $handle ) {
			throw new \RuntimeException( '[Snow Monkey Forms] .htaccess can\'t create.' );
		}

		if ( false === fwrite( $handle, "Deny from all\n" ) ) {
			throw new \RuntimeException( '[Snow Monkey Forms] .htaccess can\'t write.' );
		}

		if ( ! fclose( $handle ) ) {
			throw new \RuntimeException( '[Snow Monkey Forms] .htaccess can\'t close.' );
		}

		return true;
	}
}
