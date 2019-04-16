<?php
/**
 * Plugin name: Snow Monkey Forms
 * Version: 0.0.1
 * Author: inc2734
 * Author URI: https://2inc.org
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: snow-monkey-blocks
 *
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms;

define( 'SNOW_MONKEY_FORMS_URL', plugin_dir_url( __FILE__ ) );
define( 'SNOW_MONKEY_FORMS_PATH', plugin_dir_path( __FILE__ ) );

require_once( SNOW_MONKEY_FORMS_PATH . '/vendor/autoload.php' );

class Bootstrap {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, '_plugins_loaded' ] );
	}

	public function _plugins_loaded() {
		foreach ( glob( SNOW_MONKEY_FORMS_PATH . '/shortcode/*.php' ) as $file ) {
			require_once( $file );
		}

		foreach ( glob( SNOW_MONKEY_FORMS_PATH . '/block/*/block.php' ) as $file ) {
			require_once( $file );
		}

		add_action( 'wp_enqueue_scripts', [ $this, '_enqueue_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, '_enqueue_block_editor_assets' ] );
		add_action( 'rest_api_init', [ $this, '_endpoint' ] );
		add_action( 'init', [ $this, '_register_post_type' ] );
		add_action( 'init', [ $this, '_register_meta' ] );
		add_filter( 'block_categories', [ $this, '_block_categories' ] );
	}

	public function _enqueue_assets() {
		wp_enqueue_script(
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_URL . '/dist/js/app.min.js',
			[ 'jquery' ],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/app.min.js' ),
			true
		);

		wp_add_inline_script(
			'snow-monkey-forms',
			'var snowmonkeyforms = ' . json_encode(
				[
					'view_json_url' => home_url() . '/wp-json/snow-monkey-form/v1/view',
				]
			),
			'before'
		);

		wp_enqueue_style(
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_URL . '/dist/css/app.min.css',
			[],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/css/app.min.css' )
		);
	}

	public function _enqueue_block_editor_assets() {
		if ( 'snow-monkey-forms' !== get_post_type() ) {
			return;
		}

		wp_enqueue_script(
			'snow-monkey-forms-blocks',
			SNOW_MONKEY_FORMS_URL . '/dist/js/blocks.min.js',
			[ 'wp-blocks', 'wp-element', 'wp-i18n' ],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/blocks.min.js' ),
			true
		);

		wp_set_script_translations(
			'snow-monkey-forms-blocks',
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_PATH . '/languages'
		);

		wp_enqueue_script(
			'snow-monkey-forms-editor',
			SNOW_MONKEY_FORMS_URL . '/dist/js/editor.min.js',
			[ 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-i18n' ],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/editor.min.js' ),
			true
		);

		wp_enqueue_style(
			'snow-monkey-forms-editor',
			SNOW_MONKEY_FORMS_URL . '/dist/css/editor.min.css',
			[],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/css/editor.min.css' )
		);
	}

	public function _endpoint() {
		register_rest_route(
			'snow-monkey-form/v1',
			'/view',
			[
				'methods'  => 'POST',
				'callback' => function() {
					ob_start();
					include( SNOW_MONKEY_FORMS_PATH . '/endpoint/view.php' );
					return ob_get_clean();
				},
			]
		);
	}

	public function _register_post_type() {
		register_post_type(
			'snow-monkey-forms',
			[
				'label'        => __( 'Snow Monkey Forms', 'snow-monkey-forms' ),
				'public'       => false,
				'show_ui'      => true,
				'show_in_rest' => true,
				'supports'     => [ 'title', 'editor', 'custom-fields' ],
				'template' => [
					[ 'snow-monkey-forms/form--input', [], [] ],
					[ 'snow-monkey-forms/form--complete', [], [] ],
				],
				'template_lock' => 'insert',
			]
		);
	}

	public function _register_meta() {
		register_meta(
			'post',
			'administrator_email_to',
			[
				'show_in_rest'   => true,
				'single'         => true,
				'type'           => 'string',
				'object_subtype' => 'snow-monkey-forms',
			]
		);

		register_meta(
			'post',
			'administrator_email_subject',
			[
				'show_in_rest'   => true,
				'single'         => true,
				'type'           => 'string',
				'object_subtype' => 'snow-monkey-forms',
			]
		);

		register_meta(
			'post',
			'administrator_email_body',
			[
				'show_in_rest'   => true,
				'single'         => true,
				'type'           => 'string',
				'object_subtype' => 'snow-monkey-forms',
			]
		);

		register_meta(
			'post',
			'auto_reply_email_to',
			[
				'show_in_rest'   => true,
				'single'         => true,
				'type'           => 'string',
				'object_subtype' => 'snow-monkey-forms',
			]
		);

		register_meta(
			'post',
			'auto_reply_email_subject',
			[
				'show_in_rest'   => true,
				'single'         => true,
				'type'           => 'string',
				'object_subtype' => 'snow-monkey-forms',
			]
		);

		register_meta(
			'post',
			'auto_reply_email_body',
			[
				'show_in_rest'   => true,
				'single'         => true,
				'type'           => 'string',
				'object_subtype' => 'snow-monkey-forms',
			]
		);
	}

	public function _block_categories( $categories ) {
		$categories[] = [
			'slug'  => 'snow-monkey-forms',
			'title' => __( 'Snow Monkey Forms', 'snow-monkey-blocks' ),
		];

		return $categories;
	}
}

new Bootstrap();
