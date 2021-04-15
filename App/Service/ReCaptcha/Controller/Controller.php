<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Service\ReCaptcha\Controller;

class Controller {

	/**
	 * Option group.
	 *
	 * @var string
	 */
	const OPTION_GROUP = 'smf-recaptcha';

	/**
	 * Option name for reCAPTCHA.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'smf-recaptcha';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, '_init' ] );
		add_action( 'admin_menu', [ $this, '_add_submenu_page' ] );
	}

	/**
	 * Add submenu page.
	 */
	public function _add_submenu_page() {
		add_submenu_page(
			'edit.php?post_type=snow-monkey-forms',
			__( 'reCAPTCHA', 'snow-monkey-forms' ),
			__( 'reCAPTCHA', 'snow-monkey-forms' ),
			'manage_options',
			self::OPTION_GROUP,
			[ $this, '_content' ]
		);
	}

	/**
	 * Display content.
	 */
	public function _content() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'reCAPTCHA', 'snow-monkey-forms' ); ?></h1>
			<form method="post" action="options.php">
					<?php
					settings_fields( self::OPTION_GROUP );
					do_settings_sections( self::OPTION_GROUP );
					submit_button();
					?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register settings.
	 */
	public function _init() {
		register_setting(
			self::OPTION_GROUP,
			self::OPTION_NAME,
			function( $option ) {
				$default_option = [
					'site-key'   => '',
					'secret-key' => '',
				];

				return shortcode_atts(
					$default_option,
					$option
				);
			}
		);

		add_settings_section(
			self::OPTION_NAME,
			'',
			function() {
				if ( ! empty( $_GET['settings-updated'] ) ) {
					?>
					<div class="updated settings-error notice is-dismissible">
						<p>
							<strong><?php esc_html_e( 'Settings saved.', 'snow-monkey-forms' ); ?></strong>
						</p>
					</div>
					<?php
				}
				?>
				<p>
					<?php
					esc_html_e(
						'reCAPTCHA protects your contact form from fraud and abuse.',
						'snow-monkey-forms'
					);
					?>
					<?php
					echo wp_kses_post(
						sprintf(
							// translators: %1$s: <a> open tag, %2$s: </a> close tag.
							__( 'For detail see %1$sreCAPTCHA%2$s.', 'snow-monkey-forms' ),
							'<a href="https://www.google.com/recaptcha/about/" target="_blank" rel="noopener">',
							'</a>'
						)
					);
					?>
				</p>
				<?php
			},
			self::OPTION_GROUP
		);

		add_settings_field(
			'site-key',
			'<label for="site-key">' . esc_html__( 'Site Key', 'snow-monkey-forms' ) . '</label>',
			function() {
				?>
				<input
					type="text"
					id="site-key"
					class="widefat"
					name="<?php echo esc_attr( self::OPTION_NAME ); ?>[site-key]"
					value="<?php echo esc_attr( static::get_option( 'site-key' ) ); ?>"
				/>
				<?php
			},
			self::OPTION_GROUP,
			self::OPTION_NAME
		);

		add_settings_field(
			'secret-key',
			'<label for="secret-key">' . esc_html__( 'Secret Key', 'snow-monkey-forms' ) . '</label>',
			function() {
				?>
				<input
					type="text"
					id="secret-key"
					class="widefat"
					name="<?php echo esc_attr( self::OPTION_NAME ); ?>[secret-key]"
					value="<?php echo esc_attr( static::get_option( 'secret-key' ) ); ?>"
				/>
				<?php
			},
			self::OPTION_GROUP,
			self::OPTION_NAME
		);
	}

	/**
	 * Return option.
	 *
	 * @param string $key The option key name.
	 * @return mixed
	 */
	public static function get_option( $key ) {
		$option = get_option( self::OPTION_NAME );
		if ( ! $option ) {
			return false;
		}

		return isset( $option[ $key ] ) ? $option[ $key ] : false;
	}
}
