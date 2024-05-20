<?php
use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;

class ControlTest extends WP_UnitTestCase {

	protected function _create_form() {
		kses_remove_filters();
		return $this->factory->post->create(
			array(
				'post_type'    => 'snow-monkey-forms',
				'post_content' => '<!-- wp:snow-monkey-forms/form--input -->
<!-- wp:snow-monkey-forms/control-text {"name":"text"} /-->
<!-- wp:snow-monkey-forms/control-select {"name":"select","options":"value1\\\\nvalue2\\\\nvalue3"} /-->
<!-- wp:snow-monkey-forms/control-checkboxes {"name":"checkboxes","options":"value1\\\\nvalue2\\\\nvalue3"} /-->
<!-- wp:snow-monkey-forms/control-radio-buttons {"name":"radio-buttons","options":"value1\\\\nvalue2\\\\nvalue3"} /-->
<!-- /wp:snow-monkey-forms/form--input -->',
			)
		);
	}

	public function tear_down() {
		parent::tear_down();
		_delete_all_data();
	}

	/**
	 * @test
	 */
	public function should_set_default_value() {
		add_filter(
			'snow_monkey_forms/control/attributes',
			function ( $attributes ) {
				if ( isset( $attributes['name'] ) && 'text' === $attributes['name'] ) {
					$attributes['value'] = 'foo';
				}
				return $attributes;
			}
		);

		$form_id    = $this->_create_form();
		$responser  = new Responser();
		$setting    = DataStore::get( $form_id );
		$validator  = new Validator( $responser, $setting );
		$controller = Dispatcher::dispatch( 'input', $responser, $setting, $validator );

		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['text'], 'value="foo"' ) );
	}

	/**
	 * @test
	 */
	public function should_not_be_set_default_value() {
		add_filter(
			'snow_monkey_forms/control/attributes',
			function ( $attributes ) {
				if ( isset( $attributes['name'] ) && 'text' === $attributes['name'] ) {
					$attributes['value'] = 'foo';
				}
				return $attributes;
			}
		);

		$form_id    = $this->_create_form();
		$responser  = new Responser( array( 'text' => 'bar' ) );
		$setting    = DataStore::get( $form_id );
		$validator  = new Validator( $responser, $setting );
		$controller = Dispatcher::dispatch( 'confirm', $responser, $setting, $validator );

		// When data is posted (when the Responder has the data), it is used.
		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['text'], 'value="bar"' ) );
	}

	/**
	 * @test
	 */
	public function set_selectbox_options() {
		add_filter(
			'snow_monkey_forms/select/options',
			function ( $options, $name ) {
				if ( 'select' === $name ) {
					return array(
						'custom1' => 'custom1',
						'custom2' => 'custom2',
						'custom3' => 'custom3',
					);
				}
				return $options;
			},
			10,
			2
		);

		$form_id    = $this->_create_form();
		$responser  = new Responser();
		$setting    = DataStore::get( $form_id );
		$validator  = new Validator( $responser, $setting );
		$controller = Dispatcher::dispatch( 'input', $responser, $setting, $validator );

		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['select'], 'value="custom1"' ) );
		$this->assertTrue( false === strpos( json_decode( $controller->send(), true )['controls']['select'], 'value="value1"' ) );
	}

	/**
	 * @test
	 */
	public function set_checkboxes_options() {
		add_filter(
			'snow_monkey_forms/checkboxes/options',
			function ( $options, $name ) {
				if ( 'checkboxes' === $name ) {
					return array(
						'custom1' => 'custom1',
						'custom2' => 'custom2',
						'custom3' => 'custom3',
					);
				}
				return $options;
			},
			10,
			2
		);

		$form_id    = $this->_create_form();
		$responser  = new Responser();
		$setting    = DataStore::get( $form_id );
		$validator  = new Validator( $responser, $setting );
		$controller = Dispatcher::dispatch( 'input', $responser, $setting, $validator );

		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['checkboxes'], 'value="custom1"' ) );
		$this->assertTrue( false === strpos( json_decode( $controller->send(), true )['controls']['checkboxes'], 'value="value1"' ) );
	}

	/**
	 * @test
	 */
	public function set_radio_buttons_options() {
		add_filter(
			'snow_monkey_forms/radio_buttons/options',
			function ( $options, $name ) {
				if ( 'radio-buttons' === $name ) {
					return array(
						'custom1' => 'custom1',
						'custom2' => 'custom2',
						'custom3' => 'custom3',
					);
				}
				return $options;
			},
			10,
			2
		);

		$form_id    = $this->_create_form();
		$responser  = new Responser();
		$setting    = DataStore::get( $form_id );
		$validator  = new Validator( $responser, $setting );
		$controller = Dispatcher::dispatch( 'input', $responser, $setting, $validator );

		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['radio-buttons'], 'value="custom1"' ) );
		$this->assertTrue( false === strpos( json_decode( $controller->send(), true )['controls']['radio-buttons'], 'value="value1"' ) );
	}
}
