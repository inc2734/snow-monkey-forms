<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App;

use Snow_Monkey\Plugin\Forms\App\Contract;

class Text extends Contract\Control {
	protected $name = '';
	protected $value = '';

	public function render() {
		return sprintf(
			'<input class="c-form-control" type="text" %1$s>',
			$this->generate_attributes()
		);
	}
}

class Hidden extends Contract\Control {
	protected $name = '';
	protected $value = '';

	public function __construct( array $attributes ) {
		parent::__construct( $attributes );

		$this->value = is_array( $this->value ) ? implode( static::GLUE, $this->value ) : $this->value;
	}

	public function render() {
		return sprintf(
			'<input type="hidden" %1$s>',
			$this->generate_attributes()
		);
	}
}

class Button extends Contract\Control {
	protected $name = '';
	protected $value = '';
	protected $data = [];

	public function render() {
		return sprintf(
			'<button class="c-btn" type="submit" %2$s>%1$s</button>',
			$this->value,
			$this->generate_attributes()
		);
	}
}

class Checkbox extends Contract\Control {
	protected $name = '';
	protected $value = '';
	protected $checked = null;

	public function render() {
		return sprintf(
			'<input type="checkbox" %1$s>',
			$this->generate_attributes()
		);
	}
}

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
				$label
			);
		}

		return implode( '', $controls );
	}
}

class Control {
	const GLUE = '@@@';

	public static function render( $type, array $options = [] ) {
		$attributes = isset( $options['attributes'] ) ? $options['attributes'] : [];

		if ( 'text' === $type ) {

			$control = new Text( $attributes );
			return $control->render();

		} elseif ( 'multi-checkbox' === $type ) {

			$control = new MultiCheckbox( $attributes );
			return $control->render();

		} elseif ( 'checkbox' === $type ) {

			$control = new Checkbox( $attributes );
			return $control->render();

		} elseif ( 'hidden' === $type ) {

			$control = new Hidden( $attributes );
			return $control->render();

		} elseif ( 'button' === $type ) {

			$control = new Button( $attributes );
			return $control->render();

		}
	}
}
