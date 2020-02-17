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

				<p class="snow-monkey-form__action">
					<?php
					Helper::the_control(
						'button',
						[
							'value'       => '確認',
							'data-action' => 'confirm',
						]
					);
					?>
					<?php
					Helper::the_control(
						'hidden',
						[
							'name'  => '_method',
							'value' => 'confirm',
						]
					);
					?>
				</p>
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
