<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\Helper;

if ( ! isset( $attributes['name'] ) ) {
	return;
}
?>

<div class="snow-monkey-form__placeholder" data-name="<?php echo esc_attr( $attributes['name'] ); ?>">
	<?php Helper::the_control( 'textarea', $attributes ); ?>
</div>