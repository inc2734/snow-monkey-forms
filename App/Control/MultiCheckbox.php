<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class MultiCheckbox extends Contract\Control {

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
	 * @var array
	 */
	protected $values = [];

	/**
	 * @var boolean
	 */
	protected $disabled = false;

	/**
	 * @var boolean
	 */
	protected $delimiter = ', ';

	/**
	 * @var array
	 */
	protected $options = [];

	public function _init() {
		$this->name = $this->get( 'name' ) . '[]';
	}

	public function input() {
		$checkboxes = [];
		foreach ( $this->options as $value => $label ) {
			$checked = in_array( $value, $this->get( 'values' ) );

			$checkbox_attributes = [
				'attributes' => array_merge(
					$this->attributes,
					[
						'name'     => $this->name,
						'value'    => $value,
						'disabled' => $this->disabled,
						'checked'  => $checked,
					]
				),
				'label' => $label,
			];

			$checkboxes[] = Helper::control( 'checkbox', $checkbox_attributes )->input();
		}

		return sprintf(
			'<span class="smf-multi-checkbox-control" %1$s>
				<span class="smf-multi-checkbox-control__control">%2$s</span>
			</span>',
			$this->generate_attributes( $this->attributes ),
			implode( '', $checkboxes )
		);
	}

	public function confirm() {
		if ( ! $this->values ) {
			return;
		}

		$checkboxes = [];
		foreach ( $this->options as $value => $label ) {
			$checked = in_array( $value, $this->get( 'values' ) );
			if ( ! $checked ) {
				continue;
			}

			$checkbox_attributes = [
				'attributes' => array_merge(
					$this->attributes,
					[
						'name'    => $this->name,
						'value'   => $value,
						'checked' => $checked,
					]
				),
				'label' => $label,
			];

			$checkboxes[] = Helper::control( 'checkbox', $checkbox_attributes )->confirm();
		}

		$delimiter = $this->get( 'delimiter' );
		return implode( $delimiter, $checkboxes );
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
		if ( 'name' === $attribute ) {
			return str_replace( '[]', '', $this->$attribute );
		}

		if ( 'disabled' === $attribute ) {
			return $this->$attribute;
		}

		return parent::get( $attribute );
	}

	public function set( $attribute, $value ) {
		if ( 'disabled' === $attribute ) {
			$this->$attribute = $value;
			return true;
		}

		return parent::set( $attribute, $value );
	}
}
