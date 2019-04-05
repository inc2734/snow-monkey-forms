<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Hidden extends Contract\Control {
	public $name = '';
	public $value = '';

	public function __construct( array $attributes ) {
		parent::__construct( $attributes );

		$this->value = is_array( $this->value ) ? implode( static::GLUE, $this->value ) : $this->value;
	}

	public function render() {
		return sprintf(
			'<input type="hidden" %1$s>',
			$this->generate_attributes( get_object_vars( $this ) )
		);
	}
}
