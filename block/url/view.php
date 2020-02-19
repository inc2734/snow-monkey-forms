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

<div class="smf-item" tabindex="-1">
	<div class="smf-item__label">
		<?php echo esc_html( $attributes['label'] ); ?>
	</div>
	<div class="smf-item__control">
		<div class="smf-placeholder" data-name="<?php echo esc_attr( $attributes['name'] ); ?>">
			<?php Helper::the_control( 'url', $attributes ); ?>
		</div>
	</div>
</div>
