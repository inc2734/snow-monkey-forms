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
						$value = $this->_stringfy( $value );
						$return_value .= $name . ": \n" . $value . "\n\n";
					}
					return trim( $return_value );
				}

				$value = $this->responser->get( $matches[1] );
				return $this->_stringfy( $value );
			},
			$string
		);
	}

	protected function _stringfy( $value ) {
		$delimiter = $this->setting->get_control( 'delimiter' );
		return is_array( $value ) ? implode( $delimiter, $value ) : $value;
	}
}
