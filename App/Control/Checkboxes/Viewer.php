<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control\Checkboxes;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Viewer extends Contract\Viewer {

	/**
	 * @var array
	 *   @var boolean data-invalid
	 */
	protected $attributes = [
		'data-invalid' => false,
	];

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

	protected function _init() {
		$this->set_property( 'name', $this->get_property( 'name' ) . '[]' );
	}

	public function save( $value ) {
		$this->set_property( 'values', ! is_array( $value ) ? [] : $value );
	}

	public function input() {
		$checkboxes = [];
		$checkboxes_properties = $this->_generate_checkboxes_properties();
		foreach ( $checkboxes_properties as $checkbox_properties ) {
			$checkboxes[] = Helper::control( 'checkbox', $checkbox_properties )->input();
		}

		$description = $this->get_property( 'description' );
		if ( $description ) {
			$description = sprintf(
				'<div class="smf-control-description">%1$s</div>',
				wp_kses_post( $description )
			);
		}

		return sprintf(
			'<div class="smf-checkboxes-control" %1$s>
				<div class="smf-checkboxes-control__control">%2$s</div>
			</div>
			%3$s',
			$this->_generate_attributes( $this->get_property( 'attributes' ) ),
			implode( '', $checkboxes ),
			$description
		);
	}

	public function confirm() {
		if ( ! $this->get_property( 'values' ) ) {
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

		$delimiter = $this->get_property( 'delimiter' );
		return implode( $delimiter, $checkboxes );
	}

	public function error( $error_message = '' ) {
		$this->set_attribute( 'data-invalid', true );

		return sprintf(
			'%1$s
			<div class="smf-error-messages">
				%2$s
			</div>',
			$this->input(),
			$error_message
		);
	}

	private function _generate_checkboxes_properties() {
		$checkboxes_properties = [];

		foreach ( $this->get_property( 'options' ) as $value => $label ) {
			$checked = in_array( $value, $this->get_property( 'values' ) );

			$checkboxes_properties[] = [
				'attributes' => array_merge(
					$this->get_property( 'attributes' ),
					[
						'name'     => $this->get_property( 'name' ),
						'value'    => $value,
						'disabled' => $this->get_property( 'disabled' ),
						'checked'  => $checked,
					]
				),
				'label' => $label,
			];
		}

		return $checkboxes_properties;
	}
}
