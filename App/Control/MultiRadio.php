<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class MultiRadio extends Contract\Control {

	/**
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * @var array
	 */
	protected $validations = [];

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $value = '';

	/**
	 * @var boolean
	 */
	protected $disabled = false;

	/**
	 * @var array
	 */
	protected $options = [];

	public function input() {
		$radios = [];
		foreach ( $this->options as $value => $label ) {
			$radio_attributes = [
				'attributes' => array_merge(
					$this->attributes,
					[
						'name'     => $this->get( 'name' ),
						'value'    => $value,
						'disabled' => $this->get( 'disabled' ),
						'checked'  => $value === $this->get( 'value' ),
					]
				),
				'label' => $label,
			];

			$radios[] = Helper::control( 'radio', $radio_attributes )->input();
		}

		return sprintf(
			'<span class="smf-multi-radio-control" %1$s>
				<span class="smf-multi-radio-control__control">%2$s</span>
			</span>',
			$this->generate_attributes( $this->attributes ),
			implode( '', $radios )
		);
	}

	public function confirm() {
		$value   = $this->get( 'value' );
		$checked = isset( $this->options[ $value ] );
		$label   = isset( $this->options[ $value ] ) ? $this->options[ $value ] : $value;

		return Helper::control(
			'radio',
			[
				'attributes' => [
					'name'    => $this->get( 'name' ),
					'value'   => $value,
					'checked' => $checked,
				],
				'label' => $label,
			]
		)->confirm();
	}

	public function error( $error_message = '' ) {
		$this->set( 'data-invalid', true );

		return sprintf(
			'%1$s
			<div class="smf-error-messages">
				%2$s
			</div>',
			$this->input(),
			$error_message
		);
	}

	public function get( $attribute ) {
		if ( 'name' === $attribute || 'value' === $attribute || 'disabled' === $attribute ) {
			return $this->$attribute;
		}

		return parent::get( $attribute );
	}

	public function set( $attribute, $value ) {
		if ( 'value' === $attribute || 'disabled' === $attribute ) {
			$this->$attribute = $value;
			return true;
		}

		return parent::set( $attribute, $value );
	}
}
