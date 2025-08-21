import '@wordpress/i18n';

document.addEventListener( 'DOMContentLoaded', () => {
	// Global Turnstile configuration
	// window.snowmonkeyforms_turnstile = {
	// 	...( window.snowmonkeyforms_turnstile ?? {} ),
	// };

	/*
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
			// Always reset any existing widget first, regardless of state
			const existingWidgetId = widget.getAttribute( 'data-widget-id' );
			if ( existingWidgetId ) {
				try {
					turnstile.reset( existingWidgetId );
				} catch ( error ) {
					console.warn( error );
				}
			}

			// Force clear the container and reset all attributes
			widget.innerHTML = '';
			widget.removeAttribute( 'data-rendered' );
			widget.removeAttribute( 'data-widget-id' );

			const sitekey =
				widget.getAttribute( 'data-sitekey' ) ||
				window.snowmonkeyforms_turnstile.siteKey;
			const theme =
				widget.getAttribute( 'data-theme' ) ||
				window.snowmonkeyforms_turnstile.theme;
			const size =
				widget.getAttribute( 'data-size' ) ||
				window.snowmonkeyforms_turnstile.size;

			try {
				const widgetId = turnstile.render( widget, {
					sitekey,
					theme,
					size,
					callback: 'turnstileCallback',
				} );

				// Store the widget ID and mark as rendered
				widget.setAttribute( 'data-rendered', 'true' );
				widget.setAttribute( 'data-widget-id', widgetId );
			} catch ( error ) {
				console.warn( error );
			}
		} );
	};

	// Wait for Turnstile script to load
	const checkTurnstile = ( retries = 50 ) => {
		if ( typeof turnstile !== 'undefined' ) {
			// Use turnstile.ready() if available
			if ( typeof turnstile.ready === 'function' ) {
				turnstile.ready( () => {
					initTurnstile();
				} );
			} else {
				initTurnstile();
			}
		} else if ( retries > 0 ) {
			setTimeout( () => checkTurnstile( retries - 1 ), 100 );
		}
	};

	checkTurnstile();
	*/

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
