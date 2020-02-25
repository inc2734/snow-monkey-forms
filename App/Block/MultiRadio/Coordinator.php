<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Block\MultiRadio;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Coordinator extends Contract\Coordinator {

	public function coordinate( $attributes ) {
		$attributes = array_merge(
			$this->attributes,
			Helper::block_meta_normalization( $attributes )
		);

		return [
			'attributes' => [
				'data-invalid' => false,
			],
			'description' => $attributes['description'],
			'validations' => $attributes['validations'],
			'name'        => $attributes['name'],
			'value'       => $attributes['value'],
			'disabled'    => $attributes['disabled'],
			'options'     => $attributes['options'],
		];
	}
}
