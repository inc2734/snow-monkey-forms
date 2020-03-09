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

<form class="snow-monkey-form" id="snow-monkey-form-<?php echo esc_attr( $form_id ); ?>" method="post" action=""  enctype="multipart/form-data">
	<?php echo apply_filters( 'the_content', $setting->get( 'input_content' ) ); // xss ok. ?>

	<div class="smf-action">
		<?php echo $response->action; // xss ok. ?>
	</div>

	<?php Meta::the_meta( '_formid', $form_id ); ?>
	<?php Meta::the_meta( '_token', Csrf::token() ); ?>
</form>
