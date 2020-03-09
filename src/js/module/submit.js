import $ from 'jquery';

const maybeHasControls = ( method ) => {
	return (
		'' === method ||
		'back' === method ||
		'error' === method ||
		'confirm' === method
	);
};

const maybeComplete = ( method ) => {
	return 'complete' === method || 'systemerror' === method;
};

export default function submit( event ) {
	event.preventDefault();

	const form = $( event.target );
	const actionArea = form.find( '.smf-action' );

	const replaceContent = ( content ) => {
		form.html( content );
	};

	const replaceControls = ( controls ) => {
		$.each( controls, ( key, control ) => {
			const placeholder = form.find(
				`.smf-placeholder[data-name="${ key }"]`
			);
			placeholder.html( control );
		} );
	};

	const focusToFirstItem = () => {
		const firstItem = $( '.smf-item' ).eq( 0 );
		if ( 0 < firstItem.length ) {
			firstItem.focus();
		}
	};

	const forcusToFirstErrorControl = ( errorMessages ) => {
		if ( 0 < errorMessages.length ) {
			const firstErrorMessage = errorMessages.eq( 0 );
			const firstErrorControl = firstErrorMessage
				.closest( '.smf-placeholder' )
				.find( 'input, select, textarea, button' );
			if ( 0 < firstErrorControl.length ) {
				firstErrorControl.focus();
			}
		}
	};

	const focusToContent = () => {
		const content = $( '.smf-complete-content, .smf-system-error-content' );
		if ( 0 < content.length ) {
			content.eq( 0 ).focus();
		}
	};

	const icon = $( '.smf-sending' );

	const doneCallback = ( response ) => {
		icon.attr( 'aria-hidden', 'true' );

		response = JSON.parse( response );
		const method = response.method;

		actionArea.html( response.action );
		form.find( '.smf-placeholder' ).html( '' );

		if ( maybeHasControls( method ) ) {
			replaceControls( response.controls );

			const errorMessages = $( '.smf-error-messages' );
			if ( 0 < errorMessages.length ) {
				forcusToFirstErrorControl( errorMessages );
			} else {
				focusToFirstItem();
			}
		} else if ( maybeComplete( method ) ) {
			replaceContent( response.message );
			focusToContent();
		} else {
			replaceContent( '' );
		}
	};

	$.ajax( {
		type: 'POST',
		url: snowmonkeyforms.view_json_url,
		data: new FormData( form.get( 0 ) ),
		processData: false,
		contentType: false,
	} ).done( doneCallback );
}
