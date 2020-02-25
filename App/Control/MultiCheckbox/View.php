<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control\MultiCheckbox;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class View extends Contract\View {

	/**
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * @var string
	 */
	protected $description = '';

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
		$checkboxes_properties = $this->_generate_checkboxes_properties();
		foreach ( $checkboxes_properties as $checkbox_properties ) {
			$checkboxes[] = Helper::control( 'checkbox', $checkbox_properties )->input();
		}

		$description = $this->get( 'description' );
		if ( $description ) {
			$description = sprintf(
				'<div class="smf-control-description">%1$s</div>',
				wp_kses_post( $description )
			);
		}

		return sprintf(
			'<div class="smf-multi-checkbox-control" %1$s>
				<div class="smf-multi-checkbox-control__control">%2$s</div>
			</div>
			%3$s',
			$this->generate_attributes( $this->attributes ),
			implode( '', $checkboxes ),
			$description
		);
	}

	public function confirm() {
		if ( ! $this->values ) {
			return;
		}

		$checkboxes = [];
		$checkboxes_properties = $this->_generate_checkboxes_properties();
		foreach ( $checkboxes_properties as $checkbox_properties ) {
			if ( ! $checkbox_properties['attributes']['checked'] ) {
				continue;
			}
			$checkboxes[] = Helper::control( 'checkbox', $checkbox_properties )->confirm();
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

	private function _generate_checkboxes_properties() {
		$checkboxes_properties = [];

		foreach ( $this->options as $value => $label ) {
			$checked = in_array( $value, $this->get( 'values' ) );

			$checkboxes_properties[] = [
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
		}

		return $checkboxes_properties;
	}
}
