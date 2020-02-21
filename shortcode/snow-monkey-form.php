<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Helper;
use Snow_Monkey\Plugin\Forms\App\Model\Csrf;

use Snow_Monkey\Plugin\Forms\App\Controller\Input;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;

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

		$responser  = new Responser( [] );
		$validator  = new Validator( $responser, $setting );
		$controller = Dispatcher::dispatch( 'input', $responser, $setting, $validator );
		if ( ! $controller ) {
			return;
		}

		ob_start();
		$controller->send();
		$response = ob_get_clean();
		$response = json_decode( $response );

		ob_start();
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
