<?php
/**
 * Plugin name: Snow Monkey Forms
 * Version: 12.1.4
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

use WP_REST_Response;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Model\Directory;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;
use Snow_Monkey\Plugin\Forms\App\Helper;
use Snow_Monkey\Plugin\Forms\App\Rest;
use Snow_Monkey\Plugin\Forms\App\Service\Admin\Admin;
use Snow_Monkey\Plugin\Forms\App\Service\BlockedSender\BlockedSender;
use Snow_Monkey\Plugin\Forms\App\Service\ReCaptcha\ReCaptcha;
use Snow_Monkey\Plugin\Forms\App\Service\Turnstile\Turnstile;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SNOW_MONKEY_FORMS_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'SNOW_MONKEY_FORMS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

require_once SNOW_MONKEY_FORMS_PATH . '/vendor/autoload.php';

/**
 * Whether pro edition.
 *
 * @return boolean
 */
function is_pro() {
	$is_pro = 'snow-monkey' === get_template() || 'snow-monkey/resources' === get_template();
	return apply_filters( 'snow_monkey_forms_pro', $is_pro );
}

class Bootstrap {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, '_plugins_loaded' ) );
	}

	/**
	 * Plugins loaded.
	 */
	public function _plugins_loaded() {
		add_filter( 'load_textdomain_mofile', array( $this, '_load_textdomain_mofile' ), 10, 2 );

		add_action( 'rest_api_init', array( $this, '_endpoint' ) );
		add_action( 'init', array( $this, '_load_textdomain' ) );
		add_action( 'init', array( $this, '_register_blocks' ) );
		add_action( 'init', array( $this, '_register_post_type' ) );
		add_action( 'init', array( $this, '_register_meta' ) );
		add_action( 'wp_enqueue_scripts', array( $this, '_enqueue_assets' ) );
		add_action( 'enqueue_block_assets', array( $this, '_enqueue_block_assets' ) );
		add_filter( 'block_categories_all', array( $this, '_block_categories' ) );

		add_action( 'template_redirect', array( $this, '_do_empty_save_dir' ) );

		new BlockedSender();
		new ReCaptcha();
		new Turnstile();
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
	 * Load textdomain.
	 */
	public function _load_textdomain() {
		load_plugin_textdomain( 'snow-monkey-forms', false, basename( SNOW_MONKEY_FORMS_PATH ) . '/languages' );
	}

	/**
	 * Enqueue assets.
	 */
	public function _enqueue_assets() {
		$asset = include SNOW_MONKEY_FORMS_PATH . '/dist/js/app.asset.php';
		wp_enqueue_script(
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_URL . '/dist/js/app.js',
			$asset['dependencies'],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/app.js' ),
			array(
				'in_footer' => true,
			)
		);

		wp_add_inline_script(
			'snow-monkey-forms',
			'var snowmonkeyforms = ' . wp_json_encode(
				array(
					'view_json_url' => rest_url( '/snow-monkey-form/v1/view?ver=' . time() ), // Static URLs may return browser cache when browsing back.
					'nonce'         => wp_create_nonce( 'wp_rest' ),
				)
			),
			'before'
		);
	}

	/**
	 * Enqueue block assets.
	 */
	public function _enqueue_block_assets() {
		if ( apply_filters( 'snow_monkey_forms/enqueue/fallback_style', ! is_pro() ) ) {
			if ( ! wp_style_is( 'sass-basis-core' ) && ! wp_style_is( 'sass-basis' ) ) {
				wp_enqueue_style(
					'sass-basis-core',
					SNOW_MONKEY_FORMS_URL . '/dist/css/fallback.css',
					array(),
					filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/css/fallback.css' )
				);
			}
		}

		wp_enqueue_style(
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_URL . '/dist/css/app.css',
			array(),
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/css/app.css' )
		);

		if ( is_admin() ) {
			foreach ( \WP_Block_Type_Registry::get_instance()->get_all_registered() as $block_type => $block ) {
				if ( 0 === strpos( $block_type, 'snow-monkey-forms/' ) ) {
					$handle = str_replace( '/', '-', $block_type ) . '-editor-script';
					wp_set_script_translations( $handle, 'snow-monkey-forms', SNOW_MONKEY_FORMS_PATH . '/languages' );
				}
			}

			wp_enqueue_style(
				'snow-monkey-forms@editor',
				SNOW_MONKEY_FORMS_URL . '/dist/css/editor.css',
				array( 'snow-monkey-forms' ),
				filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/css/editor.css' )
			);
		}
	}

	/**
	 * Return REST response that must not be cached.
	 *
	 * @param mixed   $data   Response data.
	 * @param integer $status HTTP status code.
	 * @return WP_REST_Response
	 */
	protected function _rest_response( $data, $status = 200 ) {
		$response = new WP_REST_Response( $data, $status );

		foreach ( wp_get_nocache_headers() as $name => $value ) {
			if ( false === $value ) {
				continue;
			}
			$response->header( $name, $value );
		}

		$response->header(
			'Cache-Control',
			'no-store, no-cache, must-revalidate, max-age=0, s-maxage=0, private'
		);
		$response->header( 'Pragma', 'no-cache' );
		$response->header( 'Expires', 'Wed, 11 Jan 1984 05:00:00 GMT' );

		return $response;
	}

	/**
	 * Return REST response for system error.
	 *
	 * @param string $message Error message.
	 * @return WP_REST_Response
	 */
	protected function _rest_system_error_response( $message = '' ) {
		$message = $message
			? $message
			: __( 'An unexpected problem has occurred.', 'snow-monkey-forms' ) .
				' ' .
				__( 'Please try again later or contact your administrator by other means.', 'snow-monkey-forms' );

		$message = sprintf(
			'<div class="smf-system-error-content">%1$s</div>',
			wp_kses_post( $message )
		);

		return $this->_rest_response(
			wp_json_encode(
				array(
					'method'   => 'systemerror',
					'data'     => array(),
					'controls' => array(),
					'action'   => '',
					'message'  => apply_filters( 'the_content', $message ),
				),
				JSON_UNESCAPED_UNICODE
			)
		);
	}

	/**
	 * Return sanitized form meta.
	 *
	 * @param array $meta Form meta.
	 * @return array|false
	 */
	protected function _sanitize_posted_form_meta( $meta ) {
		$form_id = isset( $meta['formid'] )
			? Helper::sanitize_form_id( $meta['formid'] )
			: false;

		if ( false === $form_id ) {
			return false;
		}

		$source_post_id = isset( $meta['source_post_id'] )
			? $this->_sanitize_source_post_id( $meta['source_post_id'] )
			: false;

		if ( false === $source_post_id ) {
			return false;
		}

		$form_hash = isset( $meta['form_hash'] ) && is_scalar( $meta['form_hash'] )
			? sanitize_text_field( (string) $meta['form_hash'] )
			: '';

		if ( ! $this->_verify_form_meta( $form_id, $form_hash, $source_post_id ) ) {
			return false;
		}

		$meta['formid']         = $form_id;
		$meta['source_post_id'] = $source_post_id;
		$meta['form_hash']      = $form_hash;

		return $meta;
	}

	/**
	 * Sanitize source post ID.
	 *
	 * @param mixed $source_post_id Source post ID.
	 * @return int|false
	 */
	protected function _sanitize_source_post_id( $source_post_id ) {
		if ( ! is_scalar( $source_post_id ) ) {
			return false;
		}

		$source_post_id = (string) $source_post_id;
		if ( '' === $source_post_id || ! preg_match( '/^[0-9]+$/', $source_post_id ) ) {
			return false;
		}

		return absint( $source_post_id );
	}

	/**
	 * Verify form meta.
	 *
	 * @param int    $form_id          Form ID.
	 * @param string $form_hash        Form hash.
	 * @param int    $source_post_id   Source post ID.
	 * @return boolean
	 */
	protected function _verify_form_meta( $form_id, $form_hash, $source_post_id ) {
		return $form_hash &&
			hash_equals( Meta::generate_form_hash( $form_id, $source_post_id ), $form_hash );
	}

	/**
	 * Register endpoint. This endpoint returns the form view.
	 */
	public function _endpoint() {
		register_rest_route(
			'snow-monkey-form/v1',
			'/view',
			array(
				'methods'             => 'GET',
				'callback'            => function () {
					if ( ! Csrf::validate_referer() ) {
						return $this->_rest_response( 'Invalid access.', 403 );
					}

					// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					$form_id = isset( $_SERVER['HTTP_X_SMF_FORMID'] )
						? wp_unslash( $_SERVER['HTTP_X_SMF_FORMID'] )
						: '';
					// phpcs:enable

					$form_id = Helper::sanitize_form_id( $form_id );
					if ( false === $form_id ) {
						return $this->_rest_system_error_response( __( 'Invalid access.', 'snow-monkey-forms' ) );
					}

					// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					$form_hash = isset( $_SERVER['HTTP_X_SMF_FORM_HASH'] )
						? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_SMF_FORM_HASH'] ) )
						: '';

					$source_post_id = isset( $_SERVER['HTTP_X_SMF_SOURCE_POST_ID'] )
						? $this->_sanitize_source_post_id( wp_unslash( $_SERVER['HTTP_X_SMF_SOURCE_POST_ID'] ) )
						: false;

					// phpcs:enable

					if (
						false === $source_post_id ||
						! $this->_verify_form_meta( $form_id, $form_hash, $source_post_id )
					) {
						return $this->_rest_system_error_response( __( 'Invalid access.', 'snow-monkey-forms' ) );
					}

					$route = new Rest\Route\View(
						array(
							Meta::get_key() => array(
								'method'         => 'input',
								'formid'         => $form_id,
								'form_hash'      => $form_hash,
								'source_post_id' => $source_post_id,
							),
						)
					);
					return $this->_rest_response( $route->send() );
				},
				'permission_callback' => function () {
					return true;
				},
			)
		);

		register_rest_route(
			'snow-monkey-form/v1',
			'/view',
			array(
				'methods'             => 'POST',
				'callback'            => function () {
					if ( ! Csrf::validate_referer() ) {
						return $this->_rest_response( 'Invalid access.', 403 );
					}

					$data = filter_input_array( INPUT_POST );
					$data = $data ? $data : array();

					if (
						! isset( $data[ Meta::get_key() ] ) ||
						! is_array( $data[ Meta::get_key() ] )
					) {
						return $this->_rest_system_error_response( __( 'Invalid access.', 'snow-monkey-forms' ) );
					}

					$form_meta = $this->_sanitize_posted_form_meta( $data[ Meta::get_key() ] );
					if ( false === $form_meta ) {
						return $this->_rest_system_error_response( __( 'Invalid access.', 'snow-monkey-forms' ) );
					}

					$form_meta['sender']     = wp_get_current_user();
					$data[ Meta::get_key() ] = $form_meta;

					$route = new Rest\Route\View( $data );

					return $this->_rest_response( $route->send() );
				},
				'permission_callback' => function () {
					return true;
				},
			)
		);
	}

	/**
	 * Register blocks.
	 */
	public function _register_blocks() {
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/checkboxes' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/date' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/email' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/file' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/form/input' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/form/complete' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/item' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/month' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/radio-buttons' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/select' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/snow-monkey-form' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/tel' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/text' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/textarea' );
		register_block_type( SNOW_MONKEY_FORMS_PATH . '/dist/blocks/url' );
	}

	/**
	 * Register post type.
	 */
	public function _register_post_type() {
		register_post_type(
			'snow-monkey-forms',
			array(
				'label'         => __( 'Snow Monkey Forms', 'snow-monkey-forms' ),
				'public'        => false,
				'show_ui'       => true,
				'show_in_rest'  => true,
				'supports'      => array( 'title', 'editor', 'custom-fields' ),
				'template_lock' => 'insert',
				'template'      => array(
					array(
						'snow-monkey-forms/form--input',
						array(
							'templateLock' => false,
						),
						array(
							array(
								'snow-monkey-forms/item',
								array(
									'label' => _x( 'Name', 'form-field-label', 'snow-monkey-forms' ),
								),
								array(
									array(
										'snow-monkey-forms/control-text',
										array(
											'name'        => 'fullname',
											'validations' => wp_json_encode(
												array(
													'required' => true,
												),
												JSON_UNESCAPED_UNICODE
											),
										),
									),
								),
							),
							array(
								'snow-monkey-forms/item',
								array(
									'label' => _x( 'Email', 'form-field-label', 'snow-monkey-forms' ),
								),
								array(
									array(
										'snow-monkey-forms/control-email',
										array(
											'name'        => 'email',
											'validations' => wp_json_encode(
												array(
													'required' => true,
												),
												JSON_UNESCAPED_UNICODE
											),
										),
									),
								),
							),
							array(
								'snow-monkey-forms/item',
								array(
									'label' => _x( 'Message', 'form-field-label', 'snow-monkey-forms' ),
								),
								array(
									array(
										'snow-monkey-forms/control-textarea',
										array(
											'name'        => 'message',
											'validations' => wp_json_encode(
												array(
													'required' => true,
												),
												JSON_UNESCAPED_UNICODE
											),
										),
									),
								),
							),
						),
					),
					array(
						'snow-monkey-forms/form--complete',
						array(
							'templateLock' => false,
						),
						array(
							array(
								'core/paragraph',
								array(
									'content' => __( 'Complete !', 'snow-monkey-forms' ),
								),
							),
						),
					),
				),
			)
		);
	}

	/**
	 * Register meta.
	 */
	public function _register_meta() {
		register_post_meta(
			'snow-monkey-forms',
			'blocked_sender_source',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'blocked_sender_list',
			array(
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => array(
					'schema' => array(
						'type'    => 'string',
						'default' => '[]',
					),
				),
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_to',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_replyto',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_from',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_sender',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_subject',
			array(
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => array(
					'schema' => array(
						'type'    => 'string',
						'default' => __( 'Admin notification', 'snow-monkey-forms' ),
					),
				),
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'administrator_email_body',
			array(
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => array(
					'schema' => array(
						'type'    => 'string',
						'default' => '{all-fields}',
					),
				),
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_to',
			array(
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => array(
					'schema' => array(
						'type'    => 'string',
						'default' => '{email}',
					),
				),
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_replyto',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_from',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_sender',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_subject',
			array(
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => array(
					'schema' => array(
						'type'    => 'string',
						'default' => __( 'Automatic reply notification', 'snow-monkey-forms' ),
					),
				),
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'auto_reply_email_body',
			array(
				'single'       => true,
				'type'         => 'string',
				'show_in_rest' => array(
					'schema' => array(
						'type'    => 'string',
						'default' => '{all-fields}',
					),
				),
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'use_confirm_page',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'boolean',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'use_progress_tracker',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'boolean',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'confirm_button_label',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'back_button_label',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'send_button_label',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'recaptcha_site_key',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		register_post_meta(
			'snow-monkey-forms',
			'recaptcha_secret_key',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);
	}

	/**
	 * Register block categories.
	 *
	 * @param array $categories array Array of block categories.
	 * @return array
	 */
	public function _block_categories( $categories ) {
		$categories[] = array(
			'slug'  => 'snow-monkey-forms',
			'title' => __( 'Snow Monkey Forms', 'snow-monkey-forms' ),
		);

		return $categories;
	}

	/**
	 * Empty the Save directory.
	 */
	public function _do_empty_save_dir() {
		try {
			Directory::do_empty( Directory::get() );
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
		}
	}
}

require_once SNOW_MONKEY_FORMS_PATH . '/vendor/autoload.php';
new Bootstrap();

/**
 * Uninstall
 */
function snow_monkey_forms_uninstall() {
	$posts = get_posts(
		array(
			'post_type'      => 'snow-monkey-forms',
			'posts_per_page' => -1,
		)
	);

	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
	}

	try {
		$base_direcotry = Directory::get();

		Directory::do_empty( $base_direcotry, true );
		Directory::remove( $base_direcotry );
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
