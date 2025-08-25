<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Service\Turnstile\Controller;

/**
 * Controller class for Cloudflare Turnstile settings.
 *
 * This class handles the admin interface for configuring Cloudflare Turnstile
 * integration, including site key adn secret key options.
 */
class Controller {

	/**
	 * Option group.
	 *
	 * @var string
	 */
	const OPTION_GROUP = 'smf-turnstile';

	/**
	 * Option name for Turnstile.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'smf-turnstile';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, '_init' ) );
		add_action( 'admin_menu', array( $this, '_add_submenu_page' ) );
	}

	/**
	 * Add submenu page.
	 */
	public function _add_submenu_page() {
		add_submenu_page(
			'edit.php?post_type=snow-monkey-forms',
			__( 'Cloudflare Turnstile', 'snow-monkey-forms' ),
			__( 'Cloudflare Turnstile', 'snow-monkey-forms' ),
			'manage_options',
			self::OPTION_GROUP,
			array( $this, '_content' )
		);
	}

	/**
	 * Display content.
	 */
	public function _content() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Cloudflare Turnstile', 'snow-monkey-forms' ); ?></h1>
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
			function ( $option ) {
				$default_option = array(
					'site-key'   => '',
					'secret-key' => '',
					'position'   => 'after',
				);

				return shortcode_atts(
					$default_option,
					$option
				);
			}
		);

		add_settings_section(
			self::OPTION_NAME,
			'',
			function () {
				$settings_updated = filter_input( INPUT_GET, 'settings-updated' );
				if ( $settings_updated ) {
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
						'Cloudflare Turnstile protects your contact form from fraud and abuse without slowing down web experiences for real users.',
						'snow-monkey-forms'
					);
					?>
					<?php
					echo wp_kses_post(
						sprintf(
							// translators: %1$s: <a> open tag, %2$s: </a> close tag.
							__( 'For detail see %1$sCloudflare Turnstile%2$s.', 'snow-monkey-forms' ),
							'<a href="https://www.cloudflare.com/application-services/products/turnstile/" target="_blank" rel="noopener">',
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
			'<label for="turnstile-site-key">' . esc_html__( 'Site Key', 'snow-monkey-forms' ) . '</label>',
			function () {
				?>
				<input
					type="text"
					id="turnstile-site-key"
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
			'<label for="turnstile-secret-key">' . esc_html__( 'Secret Key', 'snow-monkey-forms' ) . '</label>',
			function () {
				?>
				<input
					type="text"
					id="turnstile-secret-key"
					class="widefat"
					name="<?php echo esc_attr( self::OPTION_NAME ); ?>[secret-key]"
					value="<?php echo esc_attr( static::get_option( 'secret-key' ) ); ?>"
				/>
				<?php
			},
			self::OPTION_GROUP,
			self::OPTION_NAME
		);

		add_settings_field(
			'position',
			'<label for="turnstile-position">' . esc_html__( 'Widget Position', 'snow-monkey-forms' ) . '</label>',
			function () {
				$current_value = static::get_option( 'position' );
				?>
				<select id="turnstile-position" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[position]">
					<option value="before" <?php selected( $current_value, 'before' ); ?>>
						<?php esc_html_e( 'Before form', 'snow-monkey-forms' ); ?>
					</option>
					<option value="after" <?php selected( $current_value, 'after' ); ?>>
						<?php esc_html_e( 'After form', 'snow-monkey-forms' ); ?>
					</option>
				</select>
				<p class="description">
					<?php esc_html_e( 'Choose where to display the Turnstile widget relative to the form.', 'snow-monkey-forms' ); ?>
				</p>
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
			$defaults = array(
				'site-key'   => '',
				'secret-key' => '',
				'position'   => 'after',
			);
			return isset( $defaults[ $key ] ) ? $defaults[ $key ] : false;
		}

		return isset( $option[ $key ] ) ? $option[ $key ] : false;
	}
}
