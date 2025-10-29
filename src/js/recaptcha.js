import '@wordpress/i18n';

let preventDefault = false;

document.addEventListener( 'DOMContentLoaded', () => {
	const onClick = ( event, form ) => {
		if ( preventDefault ) {
			if ( event.isTrusted ) {
				event.preventDefault();
			}
			return;
		}

		const button = event.target;

		if ( ! button.classList.contains( 'smf-button-control__control' ) ) {
			return;
		}

		if ( ! button.getAttribute( 'data-action' ) ) {
			return;
		}

		if ( 'submit' !== button.getAttribute( 'type' ) ) {
			return;
		}

		event.preventDefault();
		preventDefault = true;

		grecaptcha.ready( () => {
			grecaptcha
				/* eslint-disable camelcase */
				.execute( snowmonkeyforms_recaptcha.siteKey, {
					action: 'homepage',
				} )
				/* eslint-enable */
				.then( ( token ) => {
					const field = form.querySelector(
						'[name="smf-recaptcha-response"]'
					);
					if ( !! field ) {
						field.removeAttribute( 'disabled' );
						field.value = token;
					}
					button.click();
					preventDefault = false;
					if ( !! field ) {
						field.setAttribute( 'disabled', 'disabled' );
					}
				} );
		} );
	};

	const forms = [].slice.call(
		document.querySelectorAll( '.snow-monkey-form' )
	);
	forms.forEach( ( form ) => {
		form.addEventListener(
			'click',
			( event ) => onClick( event, form ),
			false
		);
	} );
} );
