<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Service\ReCaptcha;

use Snow_Monkey\Plugin\Forms\App\Service\ReCaptcha\Controller\Controller;
use Snow_Monkey\Plugin\Forms\App\Helper;

class ReCaptcha {

	/**
	 * Constructor.
	 */
	public function __construct() {
		new Controller();

		$this->site_key   = Controller::get_option( 'site-key' );
		$this->secret_key = Controller::get_option( 'secret-key' );

		if ( ! $this->site_key || ! $this->secret_key ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', [ $this, '_wp_enqueue_scripts' ] );
		add_filter( 'snow_monkey_forms/spam/validate', [ $this, '_validate' ] );
		add_action( 'snow_monkey_forms/form/append', [ $this, '_add_token_field' ] );
	}

	/**
	 * Validate.
	 *
	 * @param boolean $is_valid Return true if valid.
	 * @return boolean
	 */
	public function _validate( $is_valid ) {
		if ( ! $is_valid ) {
			return $is_valid;
		}

		$token = filter_input( INPUT_POST, 'smf-recaptcha-response' );
		if ( ! $token ) {
			return false;
		}

		$endpoint = sprintf(
			'https://www.google.com/recaptcha/api/siteverify?secret=%1$s&response=%2$s',
			$this->secret_key,
			$token
		);

		$response      = wp_remote_get( esc_url_raw( $endpoint ) );
		$response_code = (int) wp_remote_retrieve_response_code( $response );
		if ( 200 !== $response_code ) {
			return false;
		}

		$response_body = wp_remote_retrieve_body( $response );
		$response_body = json_decode( $response_body, true );

		$score = isset( $response_body['score'] )
			? $response_body['score']
			: 0;

		$threshold = 0.50;
		return $threshold < $score;
	}

	/**
	 * Enqueue assets.
	 */
	public function _wp_enqueue_scripts() {
		wp_enqueue_script(
			'google-recaptcha',
			add_query_arg(
				[
					'render' => $this->site_key,
				],
				'https://www.google.com/recaptcha/api.js'
			),
			[],
			'3.0',
			true
		);

		wp_enqueue_script(
			'snow-monkey-forms@recaptcha',
			SNOW_MONKEY_FORMS_URL . '/dist/js/recaptcha.js',
			[ 'google-recaptcha' ],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/recaptcha.js' ),
			true
		);

		wp_add_inline_script(
			'snow-monkey-forms@recaptcha',
			'var snowmonkeyforms_recaptcha = ' . json_encode(
				[
					'siteKey' => $this->site_key,
				]
			),
			'after'
		);
	}

	/**
	 * Add text field for reCAPTCHA response token into forms.
	 */
	public function _add_token_field() {
		Helper::the_control(
			'hidden',
			[
				'attributes' => [
					'name'     => 'smf-recaptcha-response',
					'disabled' => true,
				],
			]
		);
	}
}
