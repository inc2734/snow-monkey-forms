import '@wordpress/i18n';

document.addEventListener( 'DOMContentLoaded', () => {
	// Turnstile callback function
	window.turnstileCallback = function ( token ) {
		const field = document.querySelector(
			'[name="cf-turnstile-response"]'
		);
		if ( field ) {
			field.removeAttribute( 'disabled' );
			field.value = token;
		}
	};

	// Initialize Turnstile widgets
	const initTurnstile = () => {
		if ( typeof turnstile === 'undefined' ) {
			return;
		}

		const widgets = document.querySelectorAll( '.cf-turnstile' );
		widgets.forEach( ( widget ) => {
			if ( widget.hasAttribute( 'data-rendered' ) ) {
				return;
			}

			const sitekey =
				widget.getAttribute( 'data-sitekey' ) ||
				snowmonkeyforms_turnstile.siteKey;
			const theme =
				widget.getAttribute( 'data-theme' ) ||
				snowmonkeyforms_turnstile.theme;
			const size =
				widget.getAttribute( 'data-size' ) ||
				snowmonkeyforms_turnstile.size;

			turnstile.render( widget, {
				sitekey: sitekey,
				theme: theme,
				size: size,
				callback: 'turnstileCallback',
			} );

			widget.setAttribute( 'data-rendered', 'true' );
		} );
	};

	// Wait for Turnstile script to load
	const checkTurnstile = ( retries = 50 ) => {
		if ( typeof turnstile !== 'undefined' ) {
			initTurnstile();
		} else if ( retries > 0 ) {
			setTimeout( () => checkTurnstile( retries - 1 ), 100 );
		} else {
			console.error( 'Turnstile script failed to load after 5 seconds' );
		}
	};

	checkTurnstile();

	// Reset Turnstile on form submission (for cases where form doesn't redirect)
	const forms = document.querySelectorAll( '.snow-monkey-form' );
	forms.forEach( ( form ) => {
		form.addEventListener( 'submit', () => {
			setTimeout( () => {
				if ( typeof turnstile !== 'undefined' ) {
					turnstile.reset();
				}
			}, 1000 );
		} );
	} );
} );
