<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Helper;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;

add_shortcode(
	'snow_monkey_form',
	function( $attributes ) {
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

		if ( ! $setting->get( 'input_content' ) ) {
			return;
		}

		ob_start();
		?>
		<form class="snow-monkey-form" id="snow-monkey-form-<?php echo esc_attr( $form_id ); ?>" method="post" action="">
			<div class="p-entry-content">
				<?php echo apply_filters( 'the_content', $setting->get( 'input_content' ) ); // xss ok. ?>

				<div class="smf-action">
					<?php
					if ( true === $setting->get( 'use_confirm_page' ) ) {
						Helper::the_control(
							'button',
							[
								'value'       => __( 'Confirm', 'snow-monkey-forms' ) . '<span class="smf-sending" aria-hidden="true"></span>',
								'data-action' => 'confirm',
							]
						);

						Helper::the_control(
							'hidden',
							[
								'name'  => '_method',
								'value' => 'confirm',
							]
						);
					} else {
						Helper::the_control(
							'button',
							[
								'value'       => __( 'Send', 'snow-monkey-forms' ) . '<span class="smf-sending" aria-hidden="true"></span>',
								'data-action' => 'complete',
							]
						);

						Helper::the_control(
							'hidden',
							[
								'name'  => '_method',
								'value' => 'complete',
							]
						);
					}
					?>
				</div>
			</div>

			<?php
			Helper::the_control(
				'hidden',
				[
					'name'  => '_formid',
					'value' => $form_id,
				]
			);
			?>

			<?php Csrf::the_control(); ?>
		</form>
		<?php
		return ob_get_clean();
	}
);
