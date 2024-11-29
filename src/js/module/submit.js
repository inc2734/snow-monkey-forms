import addCustomEvent from '@inc2734/add-custom-event';

const closest = ( el, targetClass ) => {
	for ( let item = el; item; item = item.parentElement ) {
		if ( item.classList.contains( targetClass ) ) {
			return item;
		}
	}
};

const maybeHasControls = ( method ) => {
	return 'back' === method || 'invalid' === method || 'confirm' === method;
};

const maybeComplete = ( method ) => {
	return 'complete' === method || 'systemerror' === method;
};

async function fetchView( form, options ) {
	const focusPoint = form.querySelector( '.smf-focus-point' );
	const contents = form.querySelector( '.smf-form' );
	const actionArea = form.querySelector( '.smf-action' );
	const formData = new FormData( form );
	const clickedButton = actionArea.querySelector( '[data-clicked="true"]' );
	const submitter =
		clickedButton || actionArea.querySelector( '[type="submit"]' );
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
				placeholder.querySelector(
					'input, select, textarea, button, .smf-file-control'
				);
			if ( !! firstErrorControl ) {
				firstErrorControl.focus();
			}
		}
	};

	const doneCallback = ( response ) => {
		if ( !! icon ) {
			icon.setAttribute( 'aria-hidden', 'true' );
		}

		const method = response?.method;

		if ( ! method ) {
			failCallback();
			return;
		}

		form.setAttribute( 'data-screen', method );

		replaceAction( response.action );
		if ( method === 'input' ) {
			return;
		}

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
				addCustomEvent( form, 'smf.back', detail );
				break;
			case 'confirm':
				addCustomEvent( form, 'smf.confirm', detail );
				break;
			case 'complete':
				addCustomEvent( form, 'smf.complete', detail );
				break;
			case 'invalid':
				addCustomEvent( form, 'smf.invalid', detail );
				break;
			case 'systemerror':
				addCustomEvent( form, 'smf.systemerror', detail );
				break;
		}

		addCustomEvent( form, 'smf.submit', detail );
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
		addCustomEvent( form, 'smf.systemerror', detail );
	};

	addCustomEvent( form, 'smf.beforesubmit', detail );

	try {
		const response = await fetch( snowmonkeyforms.view_json_url, options );
		if ( response.ok ) {
			const json = await response.json();
			doneCallback( JSON.parse( json ) );
		} else {
			throw new Error( response.statusText );
		}
	} catch ( error ) {
		failCallback( error );
	}
}

export function init( form ) {
	fetchView( form, {
		method: 'GET',
		headers: {
			'x-smf-formid':
				form.querySelector( '[name="snow-monkey-forms-meta[formid]"]' )
					?.value ?? false,
		},
	} );
}

export function submit( form ) {
	fetchView( form, {
		method: 'POST',
		body: new FormData( form ),
		headers: {
			'X-WP-Nonce': snowmonkeyforms?.nonce,
		},
	} );
}
