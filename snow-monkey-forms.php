<?php
/**
 * Plugin name: Snow Monkey Forms
 * Version: 0.0.1
 *
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms;

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Helper;

define( 'SNOW_MONKEY_FORMS_URL', plugin_dir_url( __FILE__ ) );
define( 'SNOW_MONKEY_FORMS_PATH', plugin_dir_path( __FILE__ ) );

require_once( SNOW_MONKEY_FORMS_PATH . '/vendor/autoload.php' );

class Bootstrap {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, '_plugins_loaded' ] );
	}

	public function _plugins_loaded() {
		add_shortcode( 'snow_monkey_form', [ $this, '_shortcode_form' ] );
		add_action( 'wp_footer', [ $this, '_script' ], 999999 );
	}

	public function _shortcode_form( $attributes ) {
		$attributes = shortcode_atts(
			[
				'id' => null,
			],
			$attributes
		);

		if ( ! $attributes['id'] ) {
			return;
		}

		$form_id = $attributes['id'];
		$setting = DataStore::get( $form_id );

		if ( ! $setting->get( 'controls' ) ) {
			return;
		}

		ob_start();
		?>
		<form class="snow-monkey-form" id="snow-monkey-form-<?php echo esc_attr( $form_id ); ?>" method="post" action="">
			<div class="p-entry-content">
				<?php foreach ( $setting->get( 'controls' ) as $control ) : ?>
					<p>
						<?php echo esc_html( $control['label'] ); ?><br>
						<span class="snow-monkey-form__placeholder" data-name="<?php echo esc_attr( $control['attributes']['name'] ); ?>">
							<?php echo Helper::control( $control['type'], $control ); ?>
						</span>
					</p>
				<?php endforeach; ?>

				<p class="snow-monkey-form__action">
					<?php echo Helper::control( 'button', [ 'attributes' => [ 'value' => '確認', 'data-action' => 'confirm' ] ] ); ?>
					<?php echo Helper::control( 'hidden', [ 'attributes' => [ 'name' => '_method', 'value' => 'confirm' ] ] ); ?>
				</p>
			</div>
			<?php echo Helper::control( 'hidden', [ 'attributes' => [ 'name' => '_formid', 'value' => $form_id ] ] ); ?>
		</form>
		<?php
		return ob_get_clean();
	}

	public function _script() {
		?>
<script>
jQuery(
	function( $ ) {
		$( document ).on(
			'click',
			'[data-action="back"]',
			function( event ) {
				$( event.currentTarget ).parent().find( 'input[type="hidden"]' ).attr( 'value', 'back' );
			}
		);

		var send = function( form ) {
			var actionArea = form.find( '.snow-monkey-form__action' );

			form.on(
				'submit',
				function( event ) {
					event.preventDefault();

					$.post(
						'<?php echo SNOW_MONKEY_FORMS_URL; ?>/json/',
						form.serialize()
					).done(
						function( response ) {
							response = JSON.parse( response );
							var method = response.data._method;
							console.log( response );

							actionArea.html( response.action );

							$.each(
								response.controls,
								function( key, control ) {
									var placeholder = form.find( '.snow-monkey-form__placeholder[data-name="' + key + '"]' );
									placeholder.html( '' );
								}
							);

							if ( '' === method || 'back' === method || 'error' === method || 'confirm' === method ) {
								$.each(
									response.controls,
									function( key, control ) {
										var placeholder = form.find( '.snow-monkey-form__placeholder[data-name="' + key + '"]' );
										placeholder.append( control );
									}
								);
							} else if ( 'complete' === method ) {
								form.html( '' ).append( response.message );
							}
						}
					);
				}
			);
		};

		$( '.snow-monkey-form' ).each(
			function( i, e ) {
				send( $( e ) );
			}
		);
	}
);
</script>
		<?php
	}
}

new Bootstrap();
