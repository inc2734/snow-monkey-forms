import $ from 'jquery';
import addCustomEvent from '@inc2734/add-custom-event';

const maybeHasControls = ( method ) => {
	return (
		'' === method ||
		'back' === method ||
		'invalid' === method ||
		'confirm' === method
	);
};

const maybeComplete = ( method ) => {
	return 'complete' === method || 'systemerror' === method;
};

export default function submit( event ) {
	event.preventDefault();

	const form = $( event.target );
	const contents = form.find( '.smf-form' );
	const actionArea = form.find( '.smf-action' );
	const formData = new FormData( form.get( 0 ) );

	const detail = {
		status: 'init',
		inputs: form.serializeArray(),
		formData,
	};

	const replaceContent = ( content ) => {
		contents.html( content );
	};

	const replaceControls = ( controls ) => {
		$.each( controls, ( key, control ) => {
			const placeholder = form.find(
				`.smf-placeholder[data-name="${ key }"]`
			);
			placeholder.html( control );
		} );
	};

	const replaceAction = ( contentType ) => {
		actionArea.html( contentType );
	};

	const focusToFirstItem = () => {
		const firstItem = form.find( '.smf-item' ).eq( 0 );
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
		const content = form.find(
			'.smf-complete-content, .smf-system-error-content'
		);
		if ( 0 < content.length ) {
			content.eq( 0 ).focus();
		}
	};

	const icon = form.find( '.smf-sending' );

	const doneCallback = ( response ) => {
		icon.attr( 'aria-hidden', 'true' );

		response = JSON.parse( response );
		if ( 'undefined' === typeof response.method ) {
			failCallback();
			return;
		}

		const method = response.method;

		form.attr( 'data-screen', method );

		replaceAction( response.action );
		form.find( '.smf-placeholder' ).html( '' );

		if ( maybeHasControls( method ) ) {
			replaceControls( response.controls );

			const errorMessages = form.find( '.smf-error-messages' );
			if ( 0 < errorMessages.length ) {
				forcusToFirstErrorControl( errorMessages );
			} else {
				focusToFirstItem();
			}
		} else if ( maybeComplete( method ) ) {
			replaceContent( response.message );
			focusToContent();
			replaceAction( response.action );
		} else {
			replaceContent( '' );
			replaceAction( '' );
		}

		detail.status = method;
		switch ( detail.status ) {
			case 'back':
				addCustomEvent( event.target, 'smf.back', detail );
				break;
			case 'confirm':
				addCustomEvent( event.target, 'smf.confirm', detail );
				break;
			case 'complete':
				addCustomEvent( event.target, 'smf.complete', detail );
				break;
			case 'invalid':
				addCustomEvent( event.target, 'smf.invalid', detail );
				break;
			case 'systemerror':
				addCustomEvent( event.target, 'smf.systemerror', detail );
				break;
		}

		addCustomEvent( event.target, 'smf.submit', detail );
	};

	const failCallback = () => {
		form.attr( 'data-screen', 'systemerror' );

		const errorMessage = $(
			'<div class="smf-system-error-content" tabindex="-1" />'
		);
		const errorMessageReady = form.find(
			'.smf-system-error-content-ready'
		);
		errorMessage.text( errorMessageReady.text() );
		replaceContent( errorMessage );
		replaceAction( '' );
		focusToContent();

		detail.status = 'systemerror';
		addCustomEvent( event.target, 'smf.systemerror', detail );
	};

	addCustomEvent( event.target, 'smf.beforesubmit', detail );

	$.ajax( {
		type: 'POST',
		url: snowmonkeyforms.view_json_url,
		data: formData,
		processData: false,
		contentType: false,
	} )
		.done( doneCallback )
		.fail( failCallback );
}
