<?php
use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Mailer;
use Snow_Monkey\Plugin\Forms\App\Model\AdministratorMailer;
use Snow_Monkey\Plugin\Forms\App\Model\AutoReplyMailer;

class MailerHeadersTest extends WP_UnitTestCase {

	protected function _create_form() {
		return $this->factory->post->create(
			array(
				'post_type' => 'snow-monkey-forms',
			)
		);
	}

	public function tear_down() {
		parent::tear_down();
		_delete_all_data();
	}

	public function test_administrator_mailer_default_headers() {
		$expected  = array();
		$form_id   = $this->_create_form();
		$responser = new Responser();
		$setting   = DataStore::get( $form_id );
		$mailer    = new AdministratorMailer( $responser, $setting );

		$this->assertSame( $expected, $mailer->_get_headers() );
	}

	public function test_add_headers_to_administrator_mailer() {
		$expected = array(
			'Cc: cc_test_1@example.com',
			'Cc: cc_test_2@example.com',
			'Bcc: bcc_test_1@example.com',
			'Bcc: bcc_test_2@example.com',
		);

		add_filter(
			'snow_monkey_forms/administrator_mailer/headers',
			function ( $headers ) {
				return array(
					'Cc: cc_test_1@example.com',
					'Cc: cc_test_2@example.com',
					'Bcc: bcc_test_1@example.com',
					'Bcc: bcc_test_2@example.com',
				);
			},
			10,
			1
		);

		$form_id   = $this->_create_form();
		$responser = new Responser();
		$setting   = DataStore::get( $form_id );
		$mailer    = new AdministratorMailer( $responser, $setting );

		$this->assertSame( $expected, $mailer->_get_headers() );
	}

	public function test_auto_reply_mailer_default_headers() {
		$expected  = array();
		$form_id   = $this->_create_form();
		$responser = new Responser();
		$setting   = DataStore::get( $form_id );
		$mailer    = new AutoReplyMailer( $responser, $setting );

		$this->assertSame( $expected, $mailer->_get_headers() );
	}

	public function test_add_headers_to_auto_reply_mailer() {
		$expected = array(
			'Cc: cc_test_1@example.com',
			'Cc: cc_test_2@example.com',
			'Bcc: bcc_test_1@example.com',
			'Bcc: bcc_test_2@example.com',
		);

		add_filter(
			'snow_monkey_forms/auto_reply_mailer/headers',
			function ( $headers ) {
				return array(
					'Cc: cc_test_1@example.com',
					'Cc: cc_test_2@example.com',
					'Bcc: bcc_test_1@example.com',
					'Bcc: bcc_test_2@example.com',
				);
			},
			10,
			1
		);

		$form_id   = $this->_create_form();
		$responser = new Responser();
		$setting   = DataStore::get( $form_id );
		$mailer    = new AutoReplyMailer( $responser, $setting );

		$this->assertSame( $expected, $mailer->_get_headers() );
	}

	public function test_default_headers() {
		$expected = array();
		$mailer   = new \Snow_Monkey\Plugin\Forms\App\Model\Mailer( array() );

		$this->assertSame( $expected, $mailer->_get_headers() );
	}

	public function test_add_headers() {
		$expected = array(
			'Cc: cc_test_1@example.com',
			'Cc: cc_test_2@example.com',
			'Bcc: bcc_test_1@example.com',
			'Bcc: bcc_test_2@example.com',
		);

		add_filter(
			'snow_monkey_forms/mailer/headers',
			function ( $headers ) {
				return array(
					'Cc: cc_test_1@example.com',
					'Cc: cc_test_2@example.com',
					'Bcc: bcc_test_1@example.com',
					'Bcc: bcc_test_2@example.com',
				);
			},
			10,
			1
		);

		$mailer = new Mailer( array() );

		$this->assertSame( $expected, $mailer->_get_headers() );
	}
}
