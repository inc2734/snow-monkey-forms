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

	/**
	 * Constructor.
	 *
	 * @param array $data Posted data.
	 */
	public function __construct( array $data = [] ) {
		$this->data = $data;
	}

	/**
	 * Send json.
	 *
	 * @param string $method   You will be given one of these.
	 *                         input|confirm|complete|invalid|systemerror.
	 * @param array  $controls The form controls.
	 * @param string $action   The form action area HTML.
	 * @param string $message  The message to be displayed on the screen.
	 * @return json
	 */
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

	/**
	 * Return posted data.
	 *
	 * @param string $name The form field name.
	 * @return mixed
	 */
	public function get( $name ) {
		return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
	}

	/**
	 * Return all posted data.
	 *
	 * @return array
	 */
	public function get_all() {
		return $this->data;
	}
}
