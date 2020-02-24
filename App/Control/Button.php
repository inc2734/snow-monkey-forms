<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Button extends Contract\Control {

	/**
	 * @var array
	 *   @var string name
	 *   @var boolean disabled
	 */
	protected $attributes = [];

	/**
	 * @var string
	 */
	protected $label = '';

	public function input() {
		return sprintf(
			'<span class="smf-button-control">
				<button class="smf-button-control__control" type="submit" %2$s>%1$s</button>
			</span>',
			wp_kses_post( $this->get( 'label' ) ),
			$this->generate_attributes( $this->attributes )
		);
	}

	public function confirm() {
		$this->input();
	}

	public function error( $error_message = '' ) {
		$this->input();
	}
}
