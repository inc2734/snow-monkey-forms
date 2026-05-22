<?php
use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;
use Snow_Monkey\Plugin\Forms\App\Rest\Route\View;

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
		remove_all_filters( 'snow_monkey_forms/control/attributes' );
		remove_all_filters( 'snow_monkey_forms/select/options' );
		remove_all_filters( 'snow_monkey_forms/checkboxes/options' );
		remove_all_filters( 'snow_monkey_forms/radio_buttons/options' );
		$this->_reset_meta();
		wp_reset_postdata();
		_delete_all_data();
	}

	protected function _reset_meta() {
		$property_names = array(
			'singleton',
			'formid',
			'form_hash',
			'source_post_id',
			'token',
			'method',
			'sender',
		);

		foreach ( $property_names as $property_name ) {
			$property = new ReflectionProperty( Meta::class, $property_name );
			$property->setAccessible( true );
			$property->setValue( null, null );
		}
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

		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['text'][0], 'value="foo"' ) );
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
		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['text'][0], 'value="bar"' ) );
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

		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['select'][0], 'value="custom1"' ) );
		$this->assertTrue( false === strpos( json_decode( $controller->send(), true )['controls']['select'][0], 'value="value1"' ) );
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

		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['checkboxes'][0], 'value="custom1"' ) );
		$this->assertTrue( false === strpos( json_decode( $controller->send(), true )['controls']['checkboxes'][0], 'value="value1"' ) );
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

		$this->assertTrue( false !== strpos( json_decode( $controller->send(), true )['controls']['radio-buttons'][0], 'value="custom1"' ) );
		$this->assertTrue( false === strpos( json_decode( $controller->send(), true )['controls']['radio-buttons'][0], 'value="value1"' ) );
	}

	/**
	 * @test
	 */
	public function should_restore_source_post_context_in_rest_request() {
		$source_post_id = $this->factory->post->create();
		$original_post_id = $this->factory->post->create();

		global $post;

		$post = get_post( $original_post_id );
		setup_postdata( $post );

		add_filter(
			'snow_monkey_forms/select/options',
			function ( $options, $name ) {
				if ( 'select' === $name ) {
					return array(
						get_the_ID() => get_the_ID(),
					);
				}
				return $options;
			},
			10,
			2
		);

		$form_id = $this->_create_form();
		$route   = new View(
			array(
				Meta::get_key() => array(
					'method'           => 'input',
					'formid'           => $form_id,
					'form_hash'        => Meta::generate_form_hash( $form_id, $source_post_id ),
					'source_post_id'   => $source_post_id,
				),
			)
		);

		$response = json_decode( $route->send(), true );

		$this->assertTrue( false !== strpos( $response['controls']['select'][0], 'value="' . $source_post_id . '"' ) );
		$this->assertSame( $original_post_id, get_the_ID() );
	}

	/**
	 * @test
	 */
	public function should_not_restore_source_post_context_when_form_hash_is_invalid() {
		$source_post_id = $this->factory->post->create();

		add_filter(
			'snow_monkey_forms/select/options',
			function ( $options, $name ) {
				if ( 'select' === $name ) {
					return array(
						get_the_ID() => get_the_ID(),
					);
				}
				return $options;
			},
			10,
			2
		);

		$form_id = $this->_create_form();
		$route   = new View(
			array(
				Meta::get_key() => array(
					'method'           => 'input',
					'formid'           => $form_id,
					'form_hash'        => 'invalid',
					'source_post_id'   => $source_post_id,
				),
			)
		);

		$response = json_decode( $route->send(), true );

		$this->assertTrue( false === strpos( $response['controls']['select'][0], 'value="' . $source_post_id . '"' ) );
	}

	/**
	 * @test
	 */
	public function should_not_restore_private_source_post_context() {
		$source_post_id = $this->factory->post->create(
			array(
				'post_status' => 'private',
			)
		);

		add_filter(
			'snow_monkey_forms/select/options',
			function ( $options, $name ) {
				if ( 'select' === $name ) {
					return array(
						get_the_ID() => get_the_ID(),
					);
				}
				return $options;
			},
			10,
			2
		);

		$form_id = $this->_create_form();
		$route   = new View(
			array(
				Meta::get_key() => array(
					'method'           => 'input',
					'formid'           => $form_id,
					'form_hash'        => Meta::generate_form_hash( $form_id, $source_post_id ),
					'source_post_id'   => $source_post_id,
				),
			)
		);

		$response = json_decode( $route->send(), true );

		$this->assertTrue( false === strpos( $response['controls']['select'][0], 'value="' . $source_post_id . '"' ) );
	}
}
