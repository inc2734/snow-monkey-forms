<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Model\Responser;

class MailParser {

	/**
	 * @var Responser
	 */
	protected $responser;

	/**
	 * @var Setting
	 */
	protected $setting;

	public function __construct( Responser $responser, Setting $setting ) {
		$this->responser = $responser;
		$this->setting   = $setting;
	}

	public function parse( $string ) {
		return preg_replace_callback(
			'@{([^}]*?)}@',
			function( $matches ) {
				if ( ! isset( $matches[1] ) ) {
					return $matches[0];
				}

				if ( 'all-fields' === $matches[1] ) {
					$return_value  = '';
					$values = $this->responser->get_all();
					foreach ( $values as $name => $value ) {
						$value = $this->_stringfy( $name, $value );
						$return_value .= $name . ": \n" . $value . "\n\n";
					}
					return trim( $return_value );
				}

				$value = $this->responser->get( $matches[1] );
				$value = $this->_stringfy( $matches[1], $value );
				return $value;
			},
			$string
		);
	}

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

	protected function _is_file( $name ) {
		$files = Meta::get( '_saved_files' );
		return isset( $files[ $name ] );
	}
}
