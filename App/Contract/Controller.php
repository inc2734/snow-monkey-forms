<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Contract;

use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Setting;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;

abstract class Controller {

	/**
	 * @var Responser
	 */
	protected $responser;

	/**
	 * @var Setting
	 */
	protected $setting;

	/**
	 * @var Validator
	 */
	protected $validator;

	protected $controls = [];
	protected $action   = '';
	protected $message  = '';

	public function __construct( Responser $responser, Setting $setting, Validator $validator ) {
		$this->responser = $responser;
		$this->setting   = $setting;
		$this->validator = $validator;

		$this->controls = $this->set_controls();
		$this->action   = $this->set_action();
		$this->message  = $this->set_message();
	}

	abstract protected function set_controls();
	abstract protected function set_action();
	abstract protected function set_message();

	public function send() {
		$class_name = get_class( $this );
		$class_name_paths = explode( '\\', $class_name );
		$method = strtolower( array_pop( $class_name_paths ) );
		return $this->responser->send( $method, $this->controls, $this->action, $this->message );
	}
}
