<?php
use Snow_Monkey\Plugin\Forms\App\Model\Directory;

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
		$directory = Directory::get();
		$bytes     = file_put_contents( path_join( $directory, 'test.txt' ), 'test' );

		Directory::do_empty( $directory );
		$this->assertFalse( Directory::is_empty( $directory ) );

		Directory::do_empty( $directory, true );
		$this->assertTrue( Directory::is_empty( $directory ) );
	}
}
