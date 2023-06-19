<?php
class CustomMailTagTest extends WP_UnitTestCase {

	protected function _create_form() {
		return $this->factory->post->create(
			array(
				'post_type' => 'snow-monkey-form',
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
	public function exist_custom_mail_tag() {
		$expected = 'Lorem ipsum';

		add_filter(
			'snow_monkey_forms/custom_mail_tag',
			function( $value, $name ) use ( $expected ) {
				if ( 'test' === $name ) {
					return $expected;
				}
				return $value;
			},
			10,
			2
		);

		$form_id     = $this->_create_form();
		$responser   = new \Snow_Monkey\Plugin\Forms\App\Model\Responser();
		$setting     = new \Snow_Monkey\Plugin\Forms\App\Model\Setting( $form_id );
		$mail_parser = new \Snow_Monkey\Plugin\Forms\App\Model\MailParser( $responser, $setting );

		$this->assertEquals( $expected, $mail_parser->parse( '{test}' ) );
	}

	/**
	 * @test
	 */
	public function no_exist_custom_mail_tag() {
		$form_id     = $this->_create_form();
		$responser   = new \Snow_Monkey\Plugin\Forms\App\Model\Responser();
		$setting     = new \Snow_Monkey\Plugin\Forms\App\Model\Setting( $form_id );
		$mail_parser = new \Snow_Monkey\Plugin\Forms\App\Model\MailParser( $responser, $setting );

		$this->assertEquals( '', $mail_parser->parse( '{test}' ) );
	}
}
