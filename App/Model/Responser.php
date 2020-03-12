<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

class Responser {

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var array
	 */
	protected $controls = [];

	/**
	 * @var array
	 */
	protected $action = [];

	/**
	 * @var string
	 */
	protected $message = '';

	public function __construct( array $data = [] ) {
		$this->data = $data;
	}

	public function send( $method, array $controls = [], $action = '', $message = '' ) {
		return json_encode(
			[
				'method'   => $method,
				'data'     => $this->data,
				'controls' => $controls,
				'action'   => $action,
				'message'  => apply_filters( 'the_content', $message ),
			],
			JSON_UNESCAPED_UNICODE
		);
	}

	public function get( $name ) {
		return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
	}

	public function get_all() {
		return $this->data;
	}
}
