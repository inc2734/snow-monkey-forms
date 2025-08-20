<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Service\Turnstile;

use Snow_Monkey\Plugin\Forms\App\Service\Turnstile\Controller\Controller;
use Snow_Monkey\Plugin\Forms\App\Helper;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;

class Turnstile {

	/**
	 * Site key.
	 *
	 * @var string
	 */
	protected $site_key = null;

	/**
	 * Secret key.
	 *
	 * @var string
	 */
	protected $secret_key = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Always initialize the controller for admin interface.
		new Controller();

		$this->site_key   = Controller::get_option( 'site-key' );
		$this->secret_key = Controller::get_option( 'secret-key' );

		// Only add frontend functionality if keys are configured.
		if ( $this->site_key && $this->secret_key ) {
			add_action( 'wp_enqueue_scripts', array( $this, '_wp_enqueue_scripts' ) );
			add_filter( 'snow_monkey_forms/spam/validate', array( $this, '_validate' ) );

			// Auto-add Turnstile widget if enabled.
			$auto_add = Controller::get_option( 'auto-add' );
			if ( $auto_add ) {
				add_action( 'snow_monkey_forms/form/append', array( $this, '_add_token_field' ) );
			}
		}
	}

	/**
	 * Validate Turnstile response.
	 *
	 * @param boolean $is_valid Return true if valid.
	 * @return boolean
	 */
	public function _validate( $is_valid ) {
		if ( ! $is_valid ) {
			return $is_valid;
		}

		$token = filter_input( INPUT_POST, 'cf-turnstile-response' );

		if ( ! $token ) {
			return false;
		}

		$endpoint = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

		$request_args = array(
			'body' => array(
				'secret'   => $this->secret_key,
				'response' => $token,
			),
		);

		$response = wp_remote_post( esc_url_raw( $endpoint ), $request_args );

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return false;
		}

		$response_body = wp_remote_retrieve_body( $response );
		$response_body = json_decode( $response_body, true );

		if ( ! is_array( $response_body ) ) {
			return false;
		}

		$success = isset( $response_body['success'] ) ? $response_body['success'] : false;

		return (bool) $success;
	}

	/**
	 * Enqueue Turnstile assets.
	 */
	public function _wp_enqueue_scripts() {
		// Get plugin version.
		$plugin_data = get_file_data( SNOW_MONKEY_FORMS_PATH . '/snow-monkey-forms.php', array( 'Version' => 'Version' ) );
		$version     = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '1.0.0';

		wp_enqueue_script(
			'cloudflare-turnstile',
			'https://challenges.cloudflare.com/turnstile/v0/api.js',
			array(),
			null, // No version parameter for external API.
			array(
				'in_footer' => true,
				'strategy'  => 'async',
			)
		);

		$asset = include SNOW_MONKEY_FORMS_PATH . '/dist/js/turnstile.asset.php';
		wp_enqueue_script(
			'snow-monkey-forms@turnstile',
			SNOW_MONKEY_FORMS_URL . '/dist/js/turnstile.js',
			array_merge( $asset['dependencies'], array( 'cloudflare-turnstile' ) ),
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/turnstile.js' ),
			array(
				'in_footer' => true,
			)
		);

		wp_add_inline_script(
			'snow-monkey-forms@turnstile',
			'var snowmonkeyforms_turnstile = ' . wp_json_encode(
				array(
					'siteKey' => $this->site_key,
					'theme'   => apply_filters( 'snow_monkey_forms/turnstile/theme', 'auto' ),
					'size'    => apply_filters( 'snow_monkey_forms/turnstile/size', 'normal' ),
				)
			),
			'after'
		);
	}

	/**
	 * Add hidden field for Turnstile response token into forms.
	 */
	public function _add_token_field() {
		// Prevent duplicate Turnstile widgets by checking if already added globally.
		static $turnstile_added = false;
		if ( $turnstile_added ) {
			return;
		}
		$turnstile_added = true;

		Helper::the_control(
			'hidden',
			array(
				'attributes' => array(
					'name'     => 'cf-turnstile-response',
					'disabled' => true,
				),
			)
		);

		// Add the Turnstile widget div safely.
		printf(
			'<div class="cf-turnstile" data-sitekey="%s"></div>',
			esc_attr( $this->site_key )
		);
	}
}
