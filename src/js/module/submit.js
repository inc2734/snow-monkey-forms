import addCustomEvent from '@inc2734/add-custom-event';

const closest = ( el, targetClass ) => {
	for ( let item = el; item; item = item.parentElement ) {
		if ( item.classList.contains( targetClass ) ) {
			return item;
		}
	}
};

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

	const form = event.target;
	const focusPoint = form.querySelector( '.smf-focus-point' );
	const contents = form.querySelector( '.smf-form' );
	const actionArea = form.querySelector( '.smf-action' );
	const formData = new FormData( form );
	const clickedButton = actionArea.querySelector( '[data-clicked="true"]' );
	const submitter = event.submitter || clickedButton;
	const icon = !! submitter
		? submitter.querySelector( '.smf-sending' )
		: undefined;
	if ( !! icon ) {
		icon.setAttribute( 'aria-hidden', 'false' );
	}
	if ( !! clickedButton ) {
		clickedButton.removeAttribute( 'data-clicked' );
	}

	const inputs = [].slice
		.call(
			form.querySelectorAll(
				'input[name]:not([disabled]), textarea[name]:not([disabled]), select[name]:not([disabled])'
			)
		)
		.map( ( element ) => {
			let value;
			if ( 'checkbox' === element.type || 'radio' === element.type ) {
				if ( element.checked ) {
					value = element.value;
				}
			} else {
				value = element.value;
			}

			if ( 'undefined' === typeof value ) {
				return false;
			}

			return {
				name: element.getAttribute( 'name' ),
				value,
			};
		} )
		.filter( ( element ) => element );

	const detail = {
		status: 'init',
		inputs,
		formData,
	};

	const replaceContent = ( content ) => {
		contents.innerHTML = content;
	};

	const replaceControls = ( controls ) => {
		for ( const key in controls ) {
			const control = controls[ key ];
			const placeholder = form.querySelector(
				`.smf-placeholder[data-name="${ key }"]`
			);
			placeholder.innerHTML = control;
		}
	};

	const replaceAction = ( contentType ) => {
		actionArea.innerHTML = contentType;
	};

	const focusFocusPoint = () => {
		if ( ! focusPoint ) {
			return;
		}

		window.scrollTo(
			0,
			window.pageYOffset + focusPoint.getBoundingClientRect().top
		);
	};

	const forcusToFirstErrorControl = ( errorMessages ) => {
		if ( 0 < errorMessages.length ) {
			const firstErrorMessage = errorMessages[ 0 ];
			const placeholder = closest( firstErrorMessage, 'smf-placeholder' );
			const firstErrorControl =
				!! placeholder &&
				placeholder.querySelector( 'input, select, textarea, button' );
			if ( !! firstErrorControl ) {
				firstErrorControl.focus();
			}
		}
	};

	const doneCallback = ( response ) => {
		if ( !! icon ) {
			icon.setAttribute( 'aria-hidden', 'true' );
		}

		response = JSON.parse( response );
		if ( 'undefined' === typeof response.method ) {
			failCallback();
			return;
		}

		const method = response.method;

		form.setAttribute( 'data-screen', method );

		replaceAction( response.action );
		[].slice
			.call( form.querySelectorAll( '.smf-placeholder' ) )
			.forEach( ( element ) => {
				element.innerHTML = '';
			} );

		if ( maybeHasControls( method ) ) {
			replaceControls( response.controls );

			const errorMessages = [].slice.call(
				form.querySelectorAll( '.smf-error-messages' )
			);
			if ( 0 < errorMessages.length ) {
				forcusToFirstErrorControl( errorMessages );
			} else {
				focusFocusPoint();
			}
		} else if ( maybeComplete( method ) ) {
			replaceContent( response.message );
			focusFocusPoint();
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

	const failCallback = ( statusText = null ) => {
		form.setAttribute( 'data-screen', 'systemerror' );

		const errorMessage = document.createElement( 'div' );
		errorMessage.classList.add( 'smf-system-error-content' );

		const errorMessageReady = form.querySelector(
			'.smf-system-error-content-ready'
		);
		errorMessage.textContent = errorMessageReady.textContent;
		if ( !! statusText ) {
			const brElement = document.createElement( 'br' );
			const statusTextElement = document.createElement( 'span' );
			statusTextElement.classList.add( 'smf-system-error-status-text' );
			statusTextElement.textContent = `(status: ${ statusText })`;
			errorMessage.appendChild( brElement );
			errorMessage.appendChild( statusTextElement );
		}

		replaceContent( errorMessage.outerHTML );
		replaceAction( '' );
		focusFocusPoint();

		detail.status = 'systemerror';
		addCustomEvent( event.target, 'smf.systemerror', detail );
	};

	addCustomEvent( event.target, 'smf.beforesubmit', detail );

	const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = () => {
		if ( 4 === xhr.readyState ) {
			if ( 200 === xhr.status && !! xhr.status ) {
				doneCallback( JSON.parse( xhr.response ) );
			} else {
				failCallback( xhr?.statusText );
			}
		}
	};
	xhr.open( 'POST', snowmonkeyforms.view_json_url, true );

	xhr.send( formData );
}
