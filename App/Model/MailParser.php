<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Model\Directory;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;

class MailParser {

	/**
	 * @var Responser
	 */
	protected $responser;

	/**
	 * @var Setting
	 */
	protected $setting;

	/**
	 * Constructor.
	 *
	 * @param Responser $responser Responser object.
	 * @param Setting   $setting   Setting object.
	 */
	public function __construct( Responser $responser, Setting $setting ) {
		$this->responser = $responser;
		$this->setting   = $setting;
	}

	/**
	 * Convert {...} to string.
	 *
	 * @param string $value Text.
	 * @return string
	 */
	public function parse( $value ) {
		return preg_replace_callback(
			'@{([^}]*?)}@',
			function ( $matches ) {
				if ( ! isset( $matches[1] ) ) {
					return $matches[0];
				}

				if ( 'all-fields' === $matches[1] ) {
					$return_value = '';
					$controls     = $this->setting->get_controls();
					foreach ( $controls as $name => $control ) {
						$value         = $this->_stringfy( $name, $this->responser->get( $name ) );
						$return_value .= $name . ": \n" . $value . "\n\n";
					}
					return trim( $return_value );
				}

				$value = $this->responser->get( $matches[1] );

				$value = apply_filters(
					'snow_monkey_forms/custom_mail_tag',
					$value,
					$matches[1],
					$this->responser,
					$this->setting
				);

				return $this->_stringfy( $matches[1], $value );
			},
			$value
		);
	}

	/**
	 * Return attachments data.
	 *
	 * @param string $body The e-mail body.
	 * @return array
	 */
	public function get_attachments( $body ) {
		$file_names       = $this->setting->get_file_names();
		$saved_files      = Directory::get_saved_files( $file_names );
		$data             = $this->responser->get_all();
		$attachment_files = array();

		foreach ( $saved_files as $name => $filepath ) {
			if ( basename( $filepath ) === $data[ $name ] ) {
				$attachment_files[ $name ] = $filepath;
			}
		}

		return $this->_sanitize_attachments( $attachment_files, $body );
	}

	/**
	 * Stringfy.
	 * Array text conversion.
	 * File to be converted to file basename.
	 *
	 * @param string $name  The form field name.
	 * @param string $value Posted value.
	 * @return string
	 */
	protected function _stringfy( $name, $value ) {
		if ( is_array( $value ) ) {
			$control   = $this->setting->get_control( $name );
			$delimiter = $control->get_property( 'delimiter' );
			return implode( $delimiter, $value );
		}

		if ( $this->_is_file( $name ) ) {
			return basename( $value );
		}

		return $value;
	}

	/**
	 * Return true when the name in saved files.
	 *
	 * @param string $name The form field name.
	 * @return boolean
	 */
	protected function _is_file( $name ) {
		$file_names = $this->setting->get_file_names();

		return in_array( $name, $file_names, true );
	}

	/**
	 * Sanitize attachments.
	 *
	 * @param array  $attachments Array of attachment file path.
	 * @param string $body        The e-mail body.
	 * @return array
	 */
	protected function _sanitize_attachments( array $attachments, $body ) {
		if ( false !== strpos( $body, '{all-fields}' ) ) {
			return $attachments;
		}

		$new_attachments = array();
		foreach ( $attachments as $name => $file ) {
			if ( false !== strpos( $body, '{' . $name . '}' ) ) {
				$new_attachments[ $name ] = $file;
			}
		}
		return $new_attachments;
	}
}
