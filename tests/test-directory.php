<?php
use Snow_Monkey\Plugin\Forms\App\Model\Directory;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;

class DirectoryTest extends WP_UnitTestCase {
	public function tear_down() {
		parent::tear_down();
		_delete_all_data();
	}

	private function _setup_context() {
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

		return $form_id;
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
		$form_id = $this->_setup_context();

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
		$this->_setup_context();

		$base_dir = Directory::get();
		$filepath = path_join( $base_dir, 'reject.txt' );
		$bytes    = file_put_contents( $filepath, 'test' );

		$this->assertFalse( Directory::do_empty( $base_dir, true ) );
		$this->assertFileExists( $filepath );

		unlink( $filepath );
	}

	/**
	 * @test
	 */
	public function generate_user_file_dirpath_rejects_invalid_name() {
		$this->_setup_context();

		$invalid_names = array(
			'.',
			'..',
			'/tmp',
			'foo/bar',
			'foo\\bar',
			'C:\\temp',
		);

		foreach ( $invalid_names as $invalid_name ) {
			try {
				Directory::generate_user_file_dirpath( $invalid_name );
				$this->fail( sprintf( 'Expected invalid name to be rejected: %s', $invalid_name ) );
			} catch ( RuntimeException $e ) {
				$this->assertSame( '[Snow Monkey Forms] Invalid file reference requested.', $e->getMessage() );
			}
		}
	}

	/**
	 * @test
	 */
	public function generate_user_filepath_rejects_invalid_filename() {
		$this->_setup_context();

		$invalid_filenames = array(
			'.',
			'..',
			'/tmp/test.txt',
			'foo/bar.txt',
			'foo\\bar.txt',
			'C:\\temp\\test.txt',
		);

		foreach ( $invalid_filenames as $invalid_filename ) {
			try {
				Directory::generate_user_filepath( 'file', $invalid_filename );
				$this->fail( sprintf( 'Expected invalid filename to be rejected: %s', $invalid_filename ) );
			} catch ( RuntimeException $e ) {
				$this->assertSame( '[Snow Monkey Forms] Invalid file reference requested.', $e->getMessage() );
			}
		}
	}

	/**
	 * @test
	 */
	public function generate_user_filepath_allows_multibyte_segments() {
		$form_id  = $this->_setup_context();
		$filepath = Directory::generate_user_filepath( '添付ファイル', 'テスト.png' );

		$this->assertSame(
			path_join(
				Directory::generate_user_dirpath( $form_id ),
				'添付ファイル/テスト.png'
			),
			$filepath
		);
	}
}
