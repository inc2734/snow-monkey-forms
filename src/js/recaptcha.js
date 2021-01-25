let preventDefault = false;

document.addEventListener( 'DOMContentLoaded', () => {
	const onClick = ( event, form ) => {
		if ( preventDefault ) {
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
				.execute( snowmonkeyforms_recaptcha.siteKey, {
					action: 'homepage',
				} )
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
