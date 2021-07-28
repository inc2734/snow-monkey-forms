<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;

register_block_type(
	__DIR__,
	[
		'render_callback' => function( $attributes ) {
			if ( empty( $attributes['formId'] ) ) {
				return;
			}

			$form_id = $attributes['formId'];
			$setting = DataStore::get( $form_id );
			if ( ! $setting->get( 'input_content' ) ) {
				return;
			}

			$responser  = new Responser( [] );
			$validator  = new Validator( $responser, $setting );
			$controller = Dispatcher::dispatch( 'input', $responser, $setting, $validator );

			// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
			// The $response is used in views.
			$response = json_decode( $controller->send() );
			// phpcs:enable

			ob_start();
			include( __DIR__ . '/view.php' );
			return ob_get_clean();
		},
	]
);
