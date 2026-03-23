<?php
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Setting;
use Snow_Monkey\Plugin\Forms\App\Service\BlockedSender\BlockedSender;

class BlockedSenderTest extends WP_UnitTestCase {

	/**
	 * @return \PHPUnit\Framework\MockObject\MockObject
	 */
	protected function _create_setting_mock( $blocked_sender_source, $blocked_sender_list ) {
		$setting = $this->getMockBuilder( Setting::class )
			->disableOriginalConstructor()
			->onlyMethods( array( 'get' ) )
			->getMock();

		$setting->method( 'get' )
			->willReturnCallback(
				function ( $key ) use ( $blocked_sender_source, $blocked_sender_list ) {
					if ( 'blocked_sender_source' === $key ) {
						return $blocked_sender_source;
					}

					if ( 'blocked_sender_list' === $key ) {
						return $blocked_sender_list;
					}

					return null;
				}
			);

		return $setting;
	}

	public function tear_down() {
		remove_all_filters( 'snow_monkey_forms/spam/validate' );
		parent::tear_down();
	}

	/**
	 * @test
	 */
	public function should_block_when_email_is_exact_match() {
		$service   = new BlockedSender();
		$responser = new Responser(
			array(
				'email' => 'blocked@example.com',
			)
		);
		$setting   = $this->_create_setting_mock( '{email}', wp_json_encode( array( 'blocked@example.com' ) ) );

		$this->assertFalse( $service->_validate( true, $responser, $setting ) );
	}

	/**
	 * @test
	 */
	public function should_block_when_domain_is_exact_match() {
		$service   = new BlockedSender();
		$responser = new Responser(
			array(
				'email' => 'test@example.com',
			)
		);
		$setting   = $this->_create_setting_mock( '{email}', wp_json_encode( array( 'example.com' ) ) );

		$this->assertFalse( $service->_validate( true, $responser, $setting ) );
	}

	/**
	 * @test
	 */
	public function should_allow_when_domain_is_subdomain_of_blocked_domain() {
		$service   = new BlockedSender();
		$responser = new Responser(
			array(
				'email' => 'test@sub.example.com',
			)
		);
		$setting   = $this->_create_setting_mock( '{email}', wp_json_encode( array( 'example.com' ) ) );

		$this->assertTrue( $service->_validate( true, $responser, $setting ) );
	}

	/**
	 * @test
	 */
	public function should_block_when_domain_is_exact_subdomain_match() {
		$service   = new BlockedSender();
		$responser = new Responser(
			array(
				'email' => 'test@sub.example.com',
			)
		);
		$setting   = $this->_create_setting_mock( '{email}', wp_json_encode( array( 'sub.example.com' ) ) );

		$this->assertFalse( $service->_validate( true, $responser, $setting ) );
	}

	/**
	 * @test
	 */
	public function should_allow_when_not_matched() {
		$service   = new BlockedSender();
		$responser = new Responser(
			array(
				'email' => 'test@example.net',
			)
		);
		$setting   = $this->_create_setting_mock( '{email}', wp_json_encode( array( 'example.com', 'blocked@example.com' ) ) );

		$this->assertTrue( $service->_validate( true, $responser, $setting ) );
	}

	/**
	 * @test
	 */
	public function should_allow_when_blocked_sender_source_is_not_mail_tag_format() {
		$service   = new BlockedSender();
		$responser = new Responser(
			array(
				'email' => 'blocked@example.com',
			)
		);
		$setting   = $this->_create_setting_mock( 'email', wp_json_encode( array( 'blocked@example.com' ) ) );

		$this->assertTrue( $service->_validate( true, $responser, $setting ) );
	}
}
