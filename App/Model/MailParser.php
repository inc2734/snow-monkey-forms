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

				$value = $this->responser->get( $matches[1] );
				return is_array( $value ) ? implode( ', ', $value ) : $value;
			},
			$string
		);
	}
}
