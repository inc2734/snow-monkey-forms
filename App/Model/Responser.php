<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

class Responser {
	protected $data = [];
	protected $controls = [];
	protected $action = [];
	protected $message = '';

	public function __construct( array $data = [] ) {
		$this->data = $data;
	}

	public function send( array $controls = [], array $action = [], $message = '' ) {
		echo json_encode(
			[
				'data'     => $this->data,
				'controls' => $controls,
				'action'   => implode( '', $action ),
				'message'  => wp_kses_post( $message ),
			],
			JSON_UNESCAPED_UNICODE
		);
	}

	public function get( $name ) {
		return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
	}
}
