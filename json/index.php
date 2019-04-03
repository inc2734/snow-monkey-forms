<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

require_once( __DIR__ . '/../vendor/autoload.php' );

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;

$data    = filter_input_array( INPUT_POST );
$form_id = $data['_formid'];
$setting = DataStore::get( $form_id );

$response = new Responser( $data, $setting );

foreach ( $setting->get( 'controls' ) as $control ) {
	if ( ! empty( $control['require'] ) && '' === $response->get( $control['attributes']['name'] ) ) {
		$data['_method'] = 'error';
		break;
	}
}

$response = Dispatcher::dispatch( $data['_method'], $data, $setting );
$response->send( $response->get_response_data() );
