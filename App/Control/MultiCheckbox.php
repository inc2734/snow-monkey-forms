<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;

class MultiCheckbox extends Contract\Control {
	protected $name = '';
	protected $value = '';
	protected $children = [];

	public function render() {
		$values = is_array( $this->value ) ? $this->value : explode( static::GLUE, $this->value );

		if ( ! $this->name ) {
			return;
		}

		$controls = [];

		$hidden = new Hidden( [ 'name' => $this->name, 'value' => '' ] );
		$controls[] = $hidden->render();

		foreach ( $this->children as $child_option ) {
			$label = isset( $child_option['label'] ) ? $child_option['label'] : null;

			$child_attributes = isset( $child_option['attributes'] ) ? $child_option['attributes'] : [];
			$value = isset( $child_attributes['value'] ) ? $child_attributes['value'] : null;
			$child_attributes['name'] = $this->name . '[]';
			$child_attributes = is_array( $values ) && ! empty( $values ) && in_array( $value, $values )
				? array_merge( $child_attributes, [ 'checked' => 'checked' ] )
				: $child_attributes;

			$control = new Checkbox( $child_attributes );

			$controls[] = sprintf(
				'<label>%1$s%2$s</label>',
				$control->render(),
				esc_html( $label )
			);
		}

		return implode( '', $controls );
	}
}
