<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

class Mailer {

	protected $to;
	protected $subject;
	protected $body;

	public function __construct( $args ) {
		$properties = array_keys( get_object_vars( $this ) );
		foreach ( $args as $key => $value ) {
			if ( ! in_array( $key, $properties ) ) {
				continue;
			}

			$this->$key = $value;
		}
	}

	public function send() {
		if ( ! $this->to ) {
			return false;
		}

		return wp_mail( $this->to, $this->subject, $this->body );
	}
}
