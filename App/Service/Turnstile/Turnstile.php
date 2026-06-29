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
			null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			array(
				'strategy' => 'async',
			)
		);

		wp_add_inline_script(
			'cloudflare-turnstile',
			'function onloadTurnstileCallback() {
				const initializedForms = new WeakSet();
				const widgetIds = new WeakMap();
				const sitekey = "' . esc_js( $this->site_key ) . '";
				const theme = "' . esc_js( apply_filters( 'snow_monkey_forms/turnstile/theme', 'auto' ) ) . '";
				const size = "' . esc_js( apply_filters( 'snow_monkey_forms/turnstile/size', 'normal' ) ) . '";

				const getContainer = ( form ) => form.querySelector( ".snow-monkey-forms-turnstile" );
				const getTokenField = ( form ) => form.querySelector( "input[name=\"cf-turnstile-response\"]" );

				const toggleSubmittersDisabled = ( form, disabled ) => {
					const actionArea = form.querySelector( ".smf-action" );
					if ( ! actionArea ) {
						return;
					}

					const submitters = actionArea.querySelectorAll( \'[type="submit"]\' );
					submitters.forEach( ( submitter ) => {
						if ( disabled ) {
							submitter.setAttribute( "disabled", "disabled" );
						} else {
							submitter.removeAttribute( "disabled" );
						}
					} );
				};

				const render = ( form ) => {
					const container = getContainer( form );
					const tokenField = getTokenField( form );

					if ( ! container || ! tokenField ) {
						return;
					}

					if ( container.querySelector( "iframe" ) ) {
						return;
					}

					const oldWidgetId = widgetIds.get( container );
					if ( oldWidgetId ) {
						toggleSubmittersDisabled( form, true );

						if ( "function" !== typeof turnstile.remove ) {
							return;
						}

						try {
							turnstile.remove( oldWidgetId );
						} catch ( e ) {
							return;
						}
						widgetIds.delete( container );
					}

					toggleSubmittersDisabled( form, true );

					const widgetId = turnstile.render( container, {
						sitekey,
						theme,
						size,
						callback: function() {
							toggleSubmittersDisabled( form, false );
						},
						"expired-callback": function() {
							toggleSubmittersDisabled( form, true );
						},
						"error-callback": function() {
							toggleSubmittersDisabled( form, true );
						},
					} );
					widgetIds.set( container, widgetId );
				};

				const reset = ( form ) => {
					const container = getContainer( form );
					if ( ! container ) {
						return;
					}

					const widgetId = widgetIds.get( container );
					if ( widgetId && container.querySelector( "iframe" ) ) {
						toggleSubmittersDisabled( form, true );
						try {
							turnstile.reset( widgetId );
						} catch ( e ) {
							render( form );
						}
						return;
					}

					render( form );
				};

				const initialize = ( form ) => {
					render( form );

					if ( initializedForms.has( form ) ) {
						return;
					}
					initializedForms.add( form );

					form.addEventListener( "smf.input", () => {
						reset( form );
					} );
					form.addEventListener( "smf.confirm", () => {
						reset( form );
					} );
					form.addEventListener( "smf.back", () => {
						reset( form );
					} );
					form.addEventListener( "smf.invalid", () => {
						reset( form );
					} );
					form.addEventListener( "smf.systemerror", () => {
						reset( form );
					} );
				};

				const initializeElement = ( element ) => {
					if ( ! element || ! element.matches ) {
						return;
					}

					if ( element.matches( ".snow-monkey-form" ) ) {
						initialize( element );
						return;
					}

					if ( element.closest( ".snow-monkey-forms-turnstile" ) ) {
						return;
					}

					const form = element.closest( ".snow-monkey-form" );
					if ( form ) {
						initialize( form );
					}

					const forms = element.querySelectorAll( ".snow-monkey-form" );
					[].slice.call( forms ).forEach( initialize );
				};

				const initializeForms = () => {
					const forms = document.querySelectorAll( ".snow-monkey-form" );
					[].slice.call( forms ).forEach( initialize );
				};

				initializeForms();

				const observer = new MutationObserver( ( mutations ) => {
					mutations.forEach( ( mutation ) => {
						[].slice.call( mutation.addedNodes ).forEach( initializeElement );
					} );
				} );
				observer.observe( document.documentElement, {
					childList: true,
					subtree: true,
				} );
			}',
			'before'
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
