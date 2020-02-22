<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\Helper;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;
?>

<form class="snow-monkey-form" id="snow-monkey-form-<?php echo esc_attr( $form_id ); ?>" method="post" action="">
	<?php echo apply_filters( 'the_content', $setting->get( 'input_content' ) ); // xss ok. ?>

	<div class="smf-action">
		<?php echo $response->action; // xss ok. ?>
	</div>

	<?php
	Helper::the_control(
		'hidden',
		[
			'attributes' => [
				'name'  => '_formid',
				'value' => $form_id,
			],
		]
	);
	?>

	<?php Csrf::the_control(); ?>
</form>
