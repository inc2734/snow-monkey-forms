import $ from 'jquery';
import submit from './module/submit';

$( document ).on( 'change', '.smf-file-control__control', ( event ) => {
	const control = $( event.currentTarget );
	const filename = control.parent().find( '.smf-file-control__filename' );
	const files = control.prop( 'files' );
	if ( 0 < files.length && 0 < filename.length ) {
		const file = files[ 0 ];
		if ( 'undefined' !== typeof file.name ) {
			filename.text( file.name );
		}
	}
} );

$( document ).on( 'click', '[data-action="back"]', ( event ) =>
	$( event.currentTarget )
		.closest( '.smf-action' )
		.find( '[type="hidden"][name="snow-monkey-forms-meta[method]"]' )
		.attr( 'value', 'back' )
);

$( document ).on( 'click', '.smf-action [type="submit"]', ( event ) => {
	$( event.currentTarget )
		.find( '.smf-sending' )
		.attr( 'aria-hidden', 'false' );
} );

$( '.snow-monkey-form' ).each( ( i, e ) => {
	const form = $( e );
	form.on( 'submit', submit );
} );

$( document ).on( 'change keyup', '[data-invalid="1"]', ( event ) => {
	$( event.currentTarget ).removeAttr( 'data-invalid' );
	$( event.currentTarget )
		.find( '[data-invalid="1"]' )
		.removeAttr( 'data-invalid' );
	$( event.currentTarget )
		.closest( '.smf-placeholder' )
		.find( '.smf-error-messages' )
		.remove();
} );
