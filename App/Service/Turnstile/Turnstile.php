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
		new Controller();

		$this->site_key   = Controller::get_option( 'site-key' );
		$this->secret_key = Controller::get_option( 'secret-key' );

		// Only add frontend functionality if keys are configured.
		if ( $this->site_key && $this->secret_key ) {
			add_action( 'wp_enqueue_scripts', array( $this, '_wp_enqueue_scripts' ) );
			add_filter( 'snow_monkey_forms/spam/validate', array( $this, '_validate' ) );

			$position = Controller::get_option( 'position' );

			if ( 'before' === $position ) {
				add_action( 'snow_monkey_forms/form/prepend', array( $this, '_add_token_field' ) );
			} else {
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
		wp_enqueue_script(
			'cloudflare-turnstile',
			'https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback',
			array(),
			1,
			array(
				'strategy' => 'async',
			)
		);

		wp_add_inline_script(
			'cloudflare-turnstile',
			'function onloadTurnstileCallback() {
				const forms = document.querySelectorAll( ".snow-monkey-form" );
				[].slice.call( forms ).forEach( ( form ) => {
					const turnstileWidgetId = turnstile.render( form.querySelector( ".snow-monkey-forms-turnstile" ), {
						sitekey: "' . esc_js( $this->site_key ) . '",
						theme: "' . esc_js( apply_filters( 'snow_monkey_forms/turnstile/theme', 'auto' ) ) . '",
						size: "' . esc_js( apply_filters( 'snow_monkey_forms/turnstile/size', 'normal' ) ) . '",
						callback: function( token ) {
							// Silence is golden.
						},
					} );

					form.addEventListener( "smf.submit", () => turnstile.reset( turnstileWidgetId ) );
				} );
			}',
			'after'
		);
	}

	/**
	 * Add hidden field for Turnstile response token into forms.
	 */
	public function _add_token_field() {
		Helper::the_control(
			'hidden',
			array(
				'attributes' => array(
					'name'     => 'cf-turnstile-response',
					'disabled' => true,
				),
			)
		);

		$position = Controller::get_option( 'position' );

		echo '<div class="snow-monkey-forms-turnstile snow-monkey-forms-turnstile--position:' . esc_attr( $position ) . '"></div>';
	}
}
