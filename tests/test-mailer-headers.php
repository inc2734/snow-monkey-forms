<?php
class MailerHeadersTest extends WP_UnitTestCase
{

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

	public function test_administrator_mailer_default_headers()
	{
		$expected = [];

		$form_id   = $this->_create_form();
		$responser = new \Snow_Monkey\Plugin\Forms\App\Model\Responser();
		$setting   = new \Snow_Monkey\Plugin\Forms\App\Model\Setting($form_id);
		$mailer    = new \Snow_Monkey\Plugin\Forms\App\Model\AdministratorMailer($responser, $setting);

		$this->assertSame($expected, $mailer->_get_headers());
	}

	public function test_add_headers_to_administrator_mailer()
	{
		$expected = [
			'Cc: cc_test_1@example.com',
			'Cc: cc_test_2@example.com',
			'Bcc: bcc_test_1@example.com',
			'Bcc: bcc_test_2@example.com',
		];

		add_filter(
			'snow_monkey_forms/administrator_mailer/headers',
			function ($headers) {
				return [
					'Cc: cc_test_1@example.com',
					'Cc: cc_test_2@example.com',
					'Bcc: bcc_test_1@example.com',
					'Bcc: bcc_test_2@example.com',
				];
			},
			10,
			1
		);

		$form_id   = $this->_create_form();
		$responser = new \Snow_Monkey\Plugin\Forms\App\Model\Responser();
		$setting   = new \Snow_Monkey\Plugin\Forms\App\Model\Setting($form_id);
		$mailer    = new \Snow_Monkey\Plugin\Forms\App\Model\AdministratorMailer($responser, $setting);

		$this->assertSame($expected, $mailer->_get_headers());
	}

	public function test_auto_reply_mailer_default_headers()
	{
		$expected = [];

		$form_id   = $this->_create_form();
		$responser = new \Snow_Monkey\Plugin\Forms\App\Model\Responser();
		$setting   = new \Snow_Monkey\Plugin\Forms\App\Model\Setting($form_id);
		$mailer    = new \Snow_Monkey\Plugin\Forms\App\Model\AutoReplyMailer($responser, $setting);

		$this->assertSame($expected, $mailer->_get_headers());
	}

	public function test_add_headers_to_auto_reply_mailer()
	{
		$expected = [
			'Cc: cc_test_1@example.com',
			'Cc: cc_test_2@example.com',
			'Bcc: bcc_test_1@example.com',
			'Bcc: bcc_test_2@example.com',
		];

		add_filter(
			'snow_monkey_forms/auto_reply_mailer/headers',
			function ($headers) {
				return [
					'Cc: cc_test_1@example.com',
					'Cc: cc_test_2@example.com',
					'Bcc: bcc_test_1@example.com',
					'Bcc: bcc_test_2@example.com',
				];
			},
			10,
			1
		);

		$form_id   = $this->_create_form();
		$responser = new \Snow_Monkey\Plugin\Forms\App\Model\Responser();
		$setting   = new \Snow_Monkey\Plugin\Forms\App\Model\Setting($form_id);
		$mailer    = new \Snow_Monkey\Plugin\Forms\App\Model\AutoReplyMailer($responser, $setting);

		$this->assertSame($expected, $mailer->_get_headers());
	}

	public function test_default_headers()
	{
		$expected = [];

		$mailer = new \Snow_Monkey\Plugin\Forms\App\Model\Mailer([]);

		$this->assertSame($expected, $mailer->_get_headers());
	}

	public function test_add_headers()
	{
		$expected = [
			'Cc: cc_test_1@example.com',
			'Cc: cc_test_2@example.com',
			'Bcc: bcc_test_1@example.com',
			'Bcc: bcc_test_2@example.com',
		];

		add_filter(
			'snow_monkey_forms/mailer/headers',
			function ($headers) {
				return [
					'Cc: cc_test_1@example.com',
					'Cc: cc_test_2@example.com',
					'Bcc: bcc_test_1@example.com',
					'Bcc: bcc_test_2@example.com',
				];
			},
			10,
			1
		);

		$mailer = new \Snow_Monkey\Plugin\Forms\App\Model\Mailer([]);

		$this->assertSame($expected, $mailer->_get_headers());
	}
}
