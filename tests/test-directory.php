<?php
use Snow_Monkey\Plugin\Forms\App\Model\Directory;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;

class DirectoryTest extends WP_UnitTestCase {
	public function tear_down() {
		parent::tear_down();
		_delete_all_data();
	}

	/**
	 * @test
	 */
	public function get() {
		$this->assertEquals(
			path_join( wp_upload_dir()['basedir'], 'smf-uploads' ),
			Directory::get()
		);

		$this->assertTrue( is_dir( Directory::get() ) );
	}

	/**
	 * @test
	 */
	public function do_empty() {
		$form_id = 123;
		Csrf::save_token();
		$token = Csrf::saved_token();
		Meta::init(
			array(
				'formid' => $form_id,
				'token'  => $token,
				'method' => 'input',
			)
		);

		$directory = Directory::generate_user_dirpath( $form_id );
		$bytes     = file_put_contents( path_join( $directory, 'test.txt' ), 'test' );

		Directory::do_empty( $directory );
		$this->assertFalse( Directory::is_empty( $directory ) );

		Directory::do_empty( $directory, true );
		$this->assertTrue( Directory::is_empty( $directory ) );
	}

	/**
	 * @test
	 */
	public function do_empty_rejects_unexpected_dir() {
		$form_id = 123;
		Csrf::save_token();
		$token = Csrf::saved_token();
		Meta::init(
			array(
				'formid' => $form_id,
				'token'  => $token,
				'method' => 'input',
			)
		);

		$base_dir = Directory::get();
		$filepath = path_join( $base_dir, 'reject.txt' );
		$bytes    = file_put_contents( $filepath, 'test' );

		$this->assertFalse( Directory::do_empty( $base_dir, true ) );
		$this->assertFileExists( $filepath );

		unlink( $filepath );
	}
}
