<?php
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;

class CsrfTest extends WP_UnitTestCase {

	protected $http_referer;

	protected $home;

	public function set_up() {
		parent::set_up();
		$this->http_referer = $_SERVER['HTTP_REFERER'] ?? null;
		$this->home         = get_option( 'home' );
	}

	public function tear_down() {
		update_option( 'home', $this->home );

		if ( is_null( $this->http_referer ) ) {
			unset( $_SERVER['HTTP_REFERER'] );
		} else {
			$_SERVER['HTTP_REFERER'] = $this->http_referer;
		}

		parent::tear_down();
	}

	/**
	 * @test
	 */
	public function validate_referer_accepts_home_url() {
		$_SERVER['HTTP_REFERER'] = home_url( '/sample/' );

		$this->assertTrue( Csrf::validate_referer() );
	}

	/**
	 * @test
	 */
	public function validate_referer_rejects_prefixed_host() {
		$home_parts = wp_parse_url( home_url( '/' ) );

		$_SERVER['HTTP_REFERER'] = sprintf(
			'%1$s://%2$s.evil.test/',
			$home_parts['scheme'],
			$home_parts['host']
		);

		$this->assertFalse( Csrf::validate_referer() );
	}

	/**
	 * @test
	 */
	public function validate_referer_rejects_prefixed_path() {
		update_option( 'home', 'http://example.org/wp' );

		$_SERVER['HTTP_REFERER'] = 'http://example.org/wp-fake/';

		$this->assertFalse( Csrf::validate_referer() );
	}
}
