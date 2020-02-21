<?php
/**
 * Plugin name: Snow Monkey Forms
 * Version: 0.0.2
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

define( 'SNOW_MONKEY_FORMS_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'SNOW_MONKEY_FORMS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

require_once( SNOW_MONKEY_FORMS_PATH . '/vendor/autoload.php' );

class Bootstrap {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, '_plugins_loaded' ] );
	}

	public function _plugins_loaded() {
		load_plugin_textdomain( 'snow-monkey-forms', false, basename( __DIR__ ) . '/languages' );
		add_filter( 'load_textdomain_mofile', [ $this, '_load_textdomain_mofile' ], 10, 2 );
		load_plugin_textdomain( 'snow-monkey-forms', false, basename( __DIR__ ) . '/languages' );

		$theme = wp_get_theme();
		if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
			return;
		}

		Csrf::save_token();

		foreach ( glob( SNOW_MONKEY_FORMS_PATH . '/block/*/index.php' ) as $file ) {
			require_once( $file );
		}

		add_action( 'wp_enqueue_scripts', [ $this, '_enqueue_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, '_enqueue_block_editor_assets' ] );
		add_action( 'rest_api_init', [ $this, '_endpoint' ] );
		add_action( 'init', [ $this, '_activate_autoupdate' ] );
		add_action( 'init', [ $this, '_register_post_type' ] );
		add_action( 'init', [ $this, '_register_meta' ] );
		add_filter( 'block_categories', [ $this, '_block_categories' ] );
	}

	/**
	 * When local .mo file exists, load this.
	 *
	 * @param string $mofile
	 * @param string $domain
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

	public function _enqueue_assets() {
		$asset = include( SNOW_MONKEY_FORMS_PATH . '/dist/js/app.asset.php' );
		wp_enqueue_script(
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_URL . '/dist/js/app.js',
			$asset['dependencies'],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/app.js' ),
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
			SNOW_MONKEY_FORMS_URL . '/dist/css/app.css',
			[ \Framework\Helper::get_main_style_handle() ],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/css/app.css' )
		);
	}

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

		$asset = include( SNOW_MONKEY_FORMS_PATH . '/dist/js/plugin-sidebar.asset.php' );
		wp_enqueue_script(
			'snow-monkey-forms@plugin-sidebar',
			SNOW_MONKEY_FORMS_URL . '/dist/js/plugin-sidebar.js',
			$asset['dependencies'],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/plugin-sidebar.js' ),
			true
		);

		wp_set_script_translations(
			'snow-monkey-forms@plugin-sidebar',
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_PATH . '/languages'
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

	/**
	 * Activate auto update using GitHub
	 *
	 * @return void
	 */
	public function _activate_autoupdate() {
		new \Inc2734\WP_GitHub_Plugin_Updater\Bootstrap(
			plugin_basename( __FILE__ ),
			'inc2734',
			'snow-monkey-forms'
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
			'administrator_email_subject',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_body',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_to',
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
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_body',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
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
	}

	public function _block_categories( $categories ) {
		$categories[] = [
			'slug'  => 'snow-monkey-forms',
			'title' => __( 'Snow Monkey Forms', 'snow-monkey-forms' ),
		];

		return $categories;
	}
}

require_once( SNOW_MONKEY_FORMS_PATH . '/vendor/autoload.php' );
new Bootstrap();
