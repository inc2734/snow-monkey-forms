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

	public function __construct( Responser $responser ) {
		$this->responser = $responser;
	}

	public function parse( $string ) {
		return preg_replace_callback(
			'@{([^}]*?)}@',
			function( $matches ) {
				if ( ! isset( $matches[1] ) ) {
					return $matches[0];
				}

				if ( 'all-fields' === $matches[1] ) {
					$value  = '';
					$values = $this->responser->get_all();
					foreach ( $values as $name => $data ) {
						$data = $this->_stringfy( $data );
						$value .= $name . ": \n" . $data . "\n\n";
					}
					return trim( $value );
				}

				$value = $this->responser->get( $matches[1] );
				return $this->_stringfy( $value );
			},
			$string
		);
	}

	protected function _stringfy( $value ) {
		return is_array( $value ) ? implode( ', ', $value ) : $value;
	}
}
