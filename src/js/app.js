'use strict';

import $ from 'jquery';

$( document ).on(
	'click',
	'[data-action="back"]',
	function( event ) {
		$( event.currentTarget ).parent().find( 'input[type="hidden"]' ).attr( 'value', 'back' );
	}
);

var send = function( form ) {
	var actionArea = form.find( '.snow-monkey-form__action' );

	form.on(
		'submit',
		function( event ) {
			event.preventDefault();

			$.post(
				snow_monkey_forms.view_json_url,
				form.serialize()
			).done(
				function( response ) {
					response = JSON.parse( response );
					var method = response.data._method;
					console.log( response );

					actionArea.html( response.action );

					$.each(
						response.controls,
						function( key, control ) {
							var placeholder = form.find( '.snow-monkey-form__placeholder[data-name="' + key + '"]' );
							placeholder.html( '' );
						}
					);

					if ( '' === method || 'back' === method || 'error' === method || 'confirm' === method ) {
						$.each(
							response.controls,
							function( key, control ) {
								var placeholder = form.find( '.snow-monkey-form__placeholder[data-name="' + key + '"]' );
								placeholder.append( control );
							}
						);
					} else if ( 'complete' === method ) {
						form.html( '' ).append( response.message );
					}
				}
			);
		}
	);
};

$( '.snow-monkey-form' ).each(
	function( i, e ) {
		send( $( e ) );
	}
);
