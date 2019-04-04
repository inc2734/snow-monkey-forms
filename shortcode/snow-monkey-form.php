<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Helper;

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

		//////////////////////////////////////////////////////////////////////////////
		$_posts = get_posts(
			[
				'post_type'        => 'snow-monkey-forms',
				'post__in'         => [ $form_id ],
				'posts_per_page'   => 1,
				'suppress_filters' => false,
				'no_found_rows'    => true,
			]
		);

		if ( $_posts ) {
			ob_start();
			echo '<pre>';
			var_dump( esc_html( $_posts[0]->post_content ) );
			echo '</pre>';
			?>
			<form class="snow-monkey-form" id="snow-monkey-form-<?php echo esc_attr( $form_id ); ?>" method="post" action="">
				<div class="p-entry-content">
					<?php echo apply_filters( 'the_content', $_posts[0]->post_content ); ?>

					<p class="snow-monkey-form__action">
						<?php echo Helper::control( 'button', [ 'value' => '確認', 'data-action' => 'confirm' ] ); ?>
						<?php echo Helper::control( 'hidden', [ 'name' => '_method', 'value' => 'confirm' ] ); ?>
					</p>
				</div>
				<?php echo Helper::control( 'hidden', [ 'name' => '_formid', 'value' => $form_id ] ); ?>
			</form>
			<?php
			echo ob_get_clean();
		}
		return;
		//////////////////////////////////////////////////////////////////////////////

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
							<?php echo Helper::control( $control['type'], $control['attributes'] ); ?>
						</span>
					</p>
				<?php endforeach; ?>

				<p class="snow-monkey-form__action">
					<?php echo Helper::control( 'button', [ 'value' => '確認', 'data-action' => 'confirm' ] ); ?>
					<?php echo Helper::control( 'hidden', [ 'name' => '_method', 'value' => 'confirm' ] ); ?>
				</p>
			</div>
			<?php echo Helper::control( 'hidden', [ 'name' => '_formid', 'value' => $form_id ] ); ?>
		</form>
		<?php
		return ob_get_clean();
	}
);
