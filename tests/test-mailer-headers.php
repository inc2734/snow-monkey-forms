<?php
class MailerHeadersTest extends WP_UnitTestCase
{

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
