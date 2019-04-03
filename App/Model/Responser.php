<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

class Responser {
	protected $data = [];
	protected $setting;

	public function __construct( array $data = [], Setting $setting ) {
		$this->data    = $data;
		$this->setting = $setting;
	}

	public function get_response_data() {
		return [
			'data'     => $this->data,
			'controls' => [],
			'action'   => [],
			'message'  => '',
		];
	}

	public function send( $response_data ) {
		if ( isset( $response_data['action'] ) ) {
			$response_data['action'] = implode( '', $response_data['action'] );
		}

		echo json_encode(
			$response_data,
			JSON_UNESCAPED_UNICODE
		);
	}

	public function get( $name ) {
		return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
	}
}
