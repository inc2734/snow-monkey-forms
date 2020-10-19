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
	 * @param string $string Text.
	 * @return string
	 */
	public function parse( $string ) {
		return preg_replace_callback(
			'@{([^}]*?)}@',
			function( $matches ) {
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
			$string
		);
	}

	/**
	 * Return attachments data.
	 *
	 * @param string $body The e-mail body.
	 * @return array
	 */
	public function get_attachments( $body ) {
		$attachments = [];

		foreach ( Meta::get_saved_files() as $name ) {
			$saved_file = $this->responser->get( $name );
			if ( ! $saved_file ) {
				continue;
			}
			$attachments[ $name ] = Directory::fileurl_to_filepath( $saved_file );
		}

		return $this->_sanitize_attachments( $attachments, $body );
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
		$saved_files = Meta::get_saved_files();
		return in_array( $name, $saved_files, true );
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

		$new_attachments = [];
		foreach ( $attachments as $name => $file ) {
			if ( false !== strpos( $body, '{' . $name . '}' ) ) {
				$new_attachments[ $name ] = $file;
			}
		}
		return $new_attachments;
	}
}
