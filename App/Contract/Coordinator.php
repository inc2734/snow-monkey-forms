<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Contract;

use Snow_Monkey\Plugin\Forms\App\Helper;

abstract class Coordinator {

	/**
	 * @var array
	 */
	protected $attributes = [];

	public function __construct( $type ) {
		$path = SNOW_MONKEY_FORMS_PATH . '/block/' . $type . '/attributes.php';

		$block_attributes = file_exists( $path )
			? include( SNOW_MONKEY_FORMS_PATH . '/block/' . $type . '/attributes.php' )
			: [];

		$attributes = [];
		foreach ( $block_attributes as $key => $value ) {
			$attributes[ $key ] = isset( $value['default'] ) ? $value['default'] : null;
		}

		$this->attributes = Helper::block_meta_normalization( $attributes );
	}

	abstract public function coordinate( $attributes );
}
