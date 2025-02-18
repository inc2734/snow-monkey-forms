<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
use Snow_Monkey\Plugin\Forms\App\Model\Directory;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;
use Snow_Monkey\Plugin\Forms\App\Model\Meta;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;

if ( empty( $attributes['formId'] ) ) {
	return;
}

$form_id = $attributes['formId'];

$setting = DataStore::get( $form_id );
if ( ! $setting->get( 'input_content' ) ) {
	return;
}

$responser  = new Responser( array() );
$validator  = new Validator( $responser, $setting );
$controller = Dispatcher::dispatch( 'input', $responser, $setting, $validator );

// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
// The $response is used in views.
$response = json_decode( $controller->send() );
// phpcs:enable

$input_content = apply_filters( 'the_content', $setting->get( 'input_content' ) );
foreach ( $response->controls as $name => $_controls ) {
	foreach ( $_controls as $control ) {
		$input_content = preg_replace(
			'|(<div[^>]*? class="smf-placeholder"[^>]*? data-name="' . $name . '"[^>]*?>)(</div>)|ms',
			'$1' . $control . '$2',
			$input_content,
			1
		);
	}
}
?>

<form class="snow-monkey-form" id="snow-monkey-form-<?php echo esc_attr( $form_id ); ?>" method="post" action=""  enctype="multipart/form-data" data-screen="loading">
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

			<?php if ( $setting->get( 'use_confirm_page' ) ) : ?>
				<li class="smf-progress-tracker__item smf-progress-tracker__item--confirm">
					<div class="smf-progress-tracker__item__number">
						<?php echo esc_html_x( '2', 'progress-tracker', 'snow-monkey-forms' ); ?>
					</div>
					<div class="smf-progress-tracker__item__text">
						<?php echo esc_html_x( 'Confirm', 'progress-tracker', 'snow-monkey-forms' ); ?>
					</div>
				</li>
			<?php endif; ?>

			<li class="smf-progress-tracker__item smf-progress-tracker__item--complete">
				<div class="smf-progress-tracker__item__number">
					<?php if ( $setting->get( 'use_confirm_page' ) ) : ?>
						<?php echo esc_html_x( '3', 'progress-tracker', 'snow-monkey-forms' ); ?>
					<?php else : ?>
						<?php echo esc_html_x( '2', 'progress-tracker', 'snow-monkey-forms' ); ?>
					<?php endif; ?>
				</div>
				<div class="smf-progress-tracker__item__text">
					<?php echo esc_html_x( 'Complete', 'progress-tracker', 'snow-monkey-forms' ); ?>
				</div>
			</li>
		</ol>
	<?php endif; ?>

	<?php echo $input_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<div class="smf-action">
		<?php
		// For ServerSideRender.
		$request_uri = wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( $request_uri && false !== strpos( $request_uri, 'block-renderer/snow-monkey-forms/snow-monkey-form' ) ) {
			echo $response->action; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>

	<div class="smf-system-error-content-ready">
		<?php
		esc_html_e( 'An unexpected problem has occurred.', 'snow-monkey-forms' );
		echo ' ';
		esc_html_e( 'Please try again later or contact your administrator by other means.', 'snow-monkey-forms' );
		?>
	</div>

	<?php Meta::the_formid( $form_id ); ?>
	<?php do_action( 'snow_monkey_forms/form/append' ); ?>
</form>
