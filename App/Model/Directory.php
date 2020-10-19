<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use SplFileInfo;
use DirectoryIterator;

class Directory {

	/**
	 * Return the path to the directory where the files are saved.
	 *
	 * @return false|string
	 */
	public static function get() {
		$upload_dir = wp_get_upload_dir();
		$save_dir   = path_join( $upload_dir['basedir'], 'smf-uploads' );

		$is_created = wp_mkdir_p( $save_dir ) ? $save_dir : false;
		if ( $is_created ) {
			static::_create_htaccess( $save_dir );
		}

		return $is_created;
	}

	/**
	 * Return the url to the directory where the files are saved.
	 *
	 * @return false|string
	 */
	public static function get_url() {
		$upload_dir = wp_get_upload_dir();

		return static::get()
			? path_join( $upload_dir['baseurl'], 'smf-uploads' )
			: false;
	}

	/**
	 * Return the file path from the file url.
	 *
	 * @param string $fileurl The file url.
	 * @return string
	 */
	public static function fileurl_to_filepath( $fileurl ) {
		return str_replace( static::get_url(), static::get(), $fileurl );
	}

	/**
	 * Return the file url from the file path.
	 *
	 * @param string $filepath The file path.
	 * @return string
	 */
	public static function filepath_to_fileurl( $filepath ) {
		return str_replace( static::get(), static::get_url(), $filepath );
	}

	/**
	 * Empty the directory.

	 * @param boolean $force Ignore the survival period.
	 * @return boolean
	 */
	public static function do_empty( $force = false ) {
		$dir = static::get();
		if ( false === $dir ) {
			return false;
		}

		return static::_remove_children( $dir, $force );
	}

	/**
	 * Remove child directories.
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

		$iterator = new DirectoryIterator( $dir );

		try {
			foreach ( $iterator as $fileinfo ) {
				$path = $fileinfo->getPathname();

				if ( $fileinfo->isDot() ) {
					continue;
				} elseif ( $fileinfo->isDir() ) {
					if ( static::_remove_children( $path, $force ) && ( $force || static::_is_removable( $path ) ) ) {
						static::remove( $path );
					}
				} elseif ( $fileinfo->isFile() ) {
					if ( $force || static::_is_removable( $path ) ) {
						static::remove( $path );
					}
				}
			}
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
			return false;
		}

		return true;
	}

	/**
	 * Remove the file.
	 *
	 * @param string $file The file path.
	 * @return boolean
	 * @throws \RuntimeException If the deletion of a file fails.
	 */
	public static function remove( $file ) {
		$fileinfo = new SplFileInfo( $file );

		if ( $fileinfo->isFile() && is_writable( $file ) ) {
			if ( ! unlink( $file ) ) {
				throw new \RuntimeException( sprintf( '[Snow Monkey Forms] Can\'t remove file: %1$s.', $file ) );
			}
		} elseif ( $fileinfo->isDir() && is_writable( $file ) ) {
			if ( ! rmdir( $file ) ) {
				throw new \RuntimeException( sprintf( '[Snow Monkey Forms] Can\'t remove directory: %1$s.', $file ) );
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

		$mtime         = filemtime( $file );
		$survival_time = apply_filters( 'snow_monkey_forms/saved_files/survival_time', 60 * 5 );
		return ! $mtime || time() > $mtime + $survival_time;
	}

	/**
	 * Create .htaccess.
	 *
	 * @param string $save_dir The directory where .htaccess is created.
	 * @return boolean
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
