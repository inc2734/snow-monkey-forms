<?php
/**
 * Plugin name: Snow Monkey Forms
 * Version: 2.1.0
 * Description: The Snow Monkey Forms is a mail form plugin for the block editor.
 * Author: inc2734
 * Author URI: https://2inc.org
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: snow-monkey-forms
 *
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms;

use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Model\Directory;
use Snow_Monkey\Plugin\Forms\App\Rest;
use Snow_Monkey\Plugin\Forms\App\Service\Admin\Admin;
use Snow_Monkey\Plugin\Forms\App\Service\ReCaptcha\ReCaptcha;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SNOW_MONKEY_FORMS_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'SNOW_MONKEY_FORMS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

require_once( SNOW_MONKEY_FORMS_PATH . '/vendor/autoload.php' );

class Bootstrap {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, '_plugins_loaded' ] );
	}

	/**
	 * Plugins loaded.
	 */
	public function _plugins_loaded() {
		load_plugin_textdomain( 'snow-monkey-forms', false, SNOW_MONKEY_FORMS_PATH . '/languages' );
		add_filter( 'load_textdomain_mofile', [ $this, '_load_textdomain_mofile' ], 10, 2 );

		Csrf::save_token();

		add_action( 'wp_enqueue_scripts', [ $this, '_enqueue_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, '_enqueue_block_editor_assets' ] );
		add_action( 'rest_api_init', [ $this, '_endpoint' ] );
		add_action( 'init', [ $this, '_register_blocks' ] );
		add_action( 'init', [ $this, '_register_post_type' ] );
		add_action( 'init', [ $this, '_register_meta' ] );
		add_filter( 'block_categories_all', [ $this, '_block_categories' ] );

		add_action( 'template_redirect', [ $this, '_do_empty_save_dir' ] );

		new ReCaptcha();
	}

	/**
	 * When local .mo file exists, load this.
	 *
	 * @param string $mofile Path to the MO file.
	 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
	 * @return string
	 */
	public function _load_textdomain_mofile( $mofile, $domain ) {
		if ( 'snow-monkey-forms' !== $domain ) {
			return $mofile;
		}

		$mofilename   = basename( $mofile );
		$local_mofile = SNOW_MONKEY_FORMS_PATH . '/languages/' . $mofilename;
		if ( ! file_exists( $local_mofile ) ) {
			return $mofile;
		}

		return $local_mofile;
	}

	/**
	 * Enqueue assets.
	 */
	public function _enqueue_assets() {
		wp_enqueue_script(
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_URL . '/dist/js/app.js',
			[],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/app.js' ),
			true
		);

		wp_add_inline_script(
			'snow-monkey-forms',
			'var snowmonkeyforms = ' . json_encode(
				[
					'view_json_url' => rest_url( '/snow-monkey-form/v1/view' ),
				]
			),
			'before'
		);

		wp_enqueue_style(
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_URL . '/dist/css/app.css',
			[],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/css/app.css' )
		);
	}

	/**
	 * Enqueue block editor assets.
	 */
	public function _enqueue_block_editor_assets() {
		$asset = include( SNOW_MONKEY_FORMS_PATH . '/dist/js/blocks.asset.php' );
		wp_enqueue_script(
			'snow-monkey-forms@blocks',
			SNOW_MONKEY_FORMS_URL . '/dist/js/blocks.js',
			$asset['dependencies'],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/blocks.js' ),
			true
		);

		wp_set_script_translations(
			'snow-monkey-forms@blocks',
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_PATH . '/languages'
		);

		wp_enqueue_style(
			'snow-monkey-forms@editor',
			SNOW_MONKEY_FORMS_URL . '/dist/css/editor.css',
			[],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/css/editor.css' )
		);

		if ( 'snow-monkey-forms' !== get_post_type() ) {
			return;
		}
	}

	/**
	 * Register endpoint. This endpoint returns the form view.
	 */
	public function _endpoint() {
		register_rest_route(
			'snow-monkey-form/v1',
			'/view',
			[
				'methods'             => 'POST',
				'callback'            => function() {
					$referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : false;
					$homeurl = untrailingslashit( home_url( '/' ) );
					if ( 0 !== strpos( $referer, $homeurl ) ) {
						exit;
					}

					$data = filter_input_array( INPUT_POST );
					$data = $data ? $data : [];

					$route = new Rest\Route\View( $data );
					return $route->send();
				},
				'permission_callback' => function() {
					return true;
				},
			]
		);
	}

	/**
	 * Register blocks.
	 */
	public function _register_blocks() {
		foreach ( glob( SNOW_MONKEY_FORMS_PATH . '/block/*/index.php' ) as $file ) {
			require_once( $file );
		}
		foreach ( glob( SNOW_MONKEY_FORMS_PATH . '/block/*/*/index.php' ) as $file ) {
			require_once( $file );
		}
	}

	/**
	 * Register post type.
	 */
	public function _register_post_type() {
		register_post_type(
			'snow-monkey-forms',
			[
				'label'         => __( 'Snow Monkey Forms', 'snow-monkey-forms' ),
				'public'        => false,
				'show_ui'       => true,
				'show_in_rest'  => true,
				'supports'      => [ 'title', 'editor', 'custom-fields' ],
				'template'      => [
					[
						'snow-monkey-forms/form--input',
						[],
						[
							[
								'snow-monkey-forms/item',
								[
									'label' => _x( 'Name', 'form-field-label', 'snow-monkey-forms' ),
								],
								[
									[
										'snow-monkey-forms/control-text',
										[
											'name'        => 'fullname',
											'validations' => json_encode(
												[
													'required' => true,
												],
												JSON_UNESCAPED_UNICODE
											),
										],
									],
								],
							],
							[
								'snow-monkey-forms/item',
								[
									'label' => _x( 'Email', 'form-field-label', 'snow-monkey-forms' ),
								],
								[
									[
										'snow-monkey-forms/control-email',
										[
											'name'        => 'email',
											'validations' => json_encode(
												[
													'required' => true,
												],
												JSON_UNESCAPED_UNICODE
											),
										],
									],
								],
							],
							[
								'snow-monkey-forms/item',
								[
									'label' => _x( 'Message', 'form-field-label', 'snow-monkey-forms' ),
								],
								[
									[
										'snow-monkey-forms/control-textarea',
										[
											'name'        => 'message',
											'validations' => json_encode(
												[
													'required' => true,
												],
												JSON_UNESCAPED_UNICODE
											),
										],
									],
								],
							],
						],
					],
					[
						'snow-monkey-forms/form--complete',
						[],
						[
							[
								'core/paragraph',
								[
									'content' => __( 'Complete !', 'snow-monkey-forms' ),
								],
							],
						],
					],
				],
				'template_lock' => 'insert',
			]
		);
	}

	/**
	 * Register meta.
	 */
	public function _register_meta() {
		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_to',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_from',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_sender',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_subject',
			[
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => [
					'schema' => [
						'type'    => 'string',
						'default' => __( 'Admin notification', 'snow-monkey-forms' ),
					],
				],
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_body',
			[
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => [
					'schema' => [
						'type'    => 'string',
						'default' => '{all-fields}',
					],
				],
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_to',
			[
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => [
					'schema' => [
						'type'    => 'string',
						'default' => '{email}',
					],
				],
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_from',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_sender',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_subject',
			[
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => [
					'schema' => [
						'type'    => 'string',
						'default' => __( 'Automatic reply notification', 'snow-monkey-forms' ),
					],
				],
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_body',
			[
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => [
					'schema' => [
						'type'    => 'string',
						'default' => '{all-fields}',
					],
				],
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'use_confirm_page',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'boolean',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'use_progress_tracker',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'boolean',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'confirm_button_label',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'back_button_label',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'send_button_label',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'recaptcha_site_key',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'recaptcha_secret_key',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);
	}

	/**
	 * Register block categories.
	 *
	 * @param array $categories array Array of block categories.
	 * @return array
	 */
	public function _block_categories( $categories ) {
		$categories[] = [
			'slug'  => 'snow-monkey-forms',
			'title' => __( 'Snow Monkey Forms', 'snow-monkey-forms' ),
		];

		return $categories;
	}

	/**
	 * Empty the Save directory.
	 */
	public function _do_empty_save_dir() {
		try {
			Directory::do_empty();
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
		}
	}
}

require_once( SNOW_MONKEY_FORMS_PATH . '/vendor/autoload.php' );
new Bootstrap();

/**
 * Uninstall
 */
function snow_monkey_forms_uninstall() {
	$posts = get_posts(
		[
			'post_type'      => 'snow-monkey-forms',
			'posts_per_page' => -1,
		]
	);

	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
	}

	try {
		Directory::do_empty( true );
	} catch ( \Exception $e ) {
		error_log( $e->getMessage() );
	}
}

/**
 * Register uninstall hook
 */
function snow_monkey_forms_activate() {
	register_uninstall_hook( __FILE__, '\Snow_Monkey\Plugin\Forms\snow_monkey_forms_uninstall' );
}
register_activation_hook( __FILE__, '\Snow_Monkey\Plugin\Forms\snow_monkey_forms_activate' );
