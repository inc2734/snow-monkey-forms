<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control\Button;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Viewer extends Contract\Viewer {

	/**
	 * @var array
	 *   @var string name
	 *   @var boolean disabled
	 */
	protected $attributes = [
		'name'     => '',
		'disabled' => false,
	];

	/**
	 * @var string
	 */
	protected $label = '';

	public function save( $value ) {
	}

	public function input() {
		return sprintf(
			'<span class="smf-button-control">
				<button class="smf-button-control__control" type="submit" %2$s>%1$s</button>
			</span>',
			wp_kses_post( $this->get_property( 'label' ) ),
			$this->_generate_attributes( $this->get_property( 'attributes' ) )
		);
	}

	public function confirm() {
		$this->input();
	}

	public function error( $error_message = '' ) {
		$this->input();
	}
}
