import $ from 'jquery';
import send from './module/send';

$( document ).on( 'click', '[data-action="back"]', ( event ) =>
	$( event.currentTarget )
		.parent()
		.find( 'input[type="hidden"]' )
		.attr( 'value', 'back' )
);

$( document ).on(
	'click',
	'.snow-monkey-form__action [type="submit"]',
	( event ) => {
		$( event.currentTarget )
			.find( '.smf-sending' )
			.attr( 'aria-hidden', 'false' );
	}
);

$( '.snow-monkey-form' ).each( ( i, e ) => send( $( e ) ) );

$( document ).on( 'change keyup', '[data-invalid="1"]', ( event ) => {
	$( event.currentTarget ).removeAttr( 'data-invalid' );
	$( event.currentTarget )
		.find( '[data-invalid="1"]' )
		.removeAttr( 'data-invalid' );
	$( event.currentTarget )
		.closest( '.snow-monkey-form__placeholder' )
		.find( '.snow-monkey-form-error-messages' )
		.remove();
} );
