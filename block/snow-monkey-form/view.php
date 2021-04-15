<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\Helper;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;
?>

<form class="snow-monkey-form" id="snow-monkey-form-<?php echo esc_attr( $form_id ); ?>" method="post" action=""  enctype="multipart/form-data" data-screen="input">
	<div class="smf-focus-point" aria-hidden="true"></div>

	<?php if ( $setting->get( 'use_progress_tracker' ) ) : ?>
		<ol class="smf-progress-tracker">
			<li class="smf-progress-tracker__item smf-progress-tracker__item--input">
				<div class="smf-progress-tracker__item__number">
					<?php echo esc_html_x( '1', 'progress-tracker', 'snow-monkey-forms' ); ?>
				</div>
				<div class="smf-progress-tracker__item__text">
					<?php echo esc_html_x( 'Input', 'progress-tracker', 'snow-monkey-forms' ); ?>
				</div>
			</li>
			<li class="smf-progress-tracker__item smf-progress-tracker__item--confirm">
				<div class="smf-progress-tracker__item__number">
					<?php echo esc_html_x( '2', 'progress-tracker', 'snow-monkey-forms' ); ?>
				</div>
				<div class="smf-progress-tracker__item__text">
					<?php echo esc_html_x( 'Confirm', 'progress-tracker', 'snow-monkey-forms' ); ?>
				</div>
			</li>
			<li class="smf-progress-tracker__item smf-progress-tracker__item--complete">
				<div class="smf-progress-tracker__item__number">
					<?php echo esc_html_x( '3', 'progress-tracker', 'snow-monkey-forms' ); ?>
				</div>
				<div class="smf-progress-tracker__item__text">
					<?php echo esc_html_x( 'Complete', 'progress-tracker', 'snow-monkey-forms' ); ?>
				</div>
			</li>
		</ol>
	<?php endif; ?>

	<?php echo apply_filters( 'the_content', $setting->get( 'input_content' ) ); // xss ok. ?>

	<div class="smf-action">
		<?php echo $response->action; // xss ok. ?>
	</div>

	<div class="smf-system-error-content-ready">
		<?php
		esc_html_e( 'An unexpected problem has occurred.', 'snow-monkey-forms' );
		esc_html_e( 'Please try again later or contact your administrator by other means.', 'snow-monkey-forms' );
		?>
	</div>

	<?php Meta::the_formid( $form_id ); ?>
	<?php Meta::the_token(); ?>
	<?php do_action( 'snow_monkey_forms/form/append' ); ?>
</form>
