<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

require_once( __DIR__ . '/../vendor/autoload.php' );

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;

$data    = filter_input_array( INPUT_POST );
$form_id = $data['_formid'];
$setting = DataStore::get( $form_id );

$responser = new Responser( $data );
$validator = new Validator( $responser, $setting );

if ( ! $validator->validate() ) {
	$data['_method'] = 'error';
}

$controller = Dispatcher::dispatch( $data['_method'], $responser, $setting, $validator );
$controller->send();
