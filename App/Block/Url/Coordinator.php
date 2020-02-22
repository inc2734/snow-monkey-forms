<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Block\Url;

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
				'name'         => $attributes['name'],
				'value'        => $attributes['value'],
				'placeholder'  => $attributes['placeholder'],
				'disabled'     => $attributes['disabled'],
				'data-invalid' => false,
			],
			'validations' => $attributes['validations'],
		];
	}
}
