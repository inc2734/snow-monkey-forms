import { submit, init } from './module/submit';

document.addEventListener(
	'change',
	( event ) => {
		const control = event.target;
		if ( control.classList.contains( 'smf-file-control__control' ) ) {
			const controlWrapper = control.closest( '.smf-file-control' );
			const filename = controlWrapper.querySelector(
				'.smf-file-control__filename--has-file'
			);
			const files = control.files;
			if ( 0 < files.length && !! filename ) {
				const file = files[ 0 ];
				if ( 'undefined' !== typeof file.name ) {
					filename.textContent = file.name;
					controlWrapper.classList.add( 'smf-file-control--set' );
				}
			}
		}
	},
	false
);

document.addEventListener(
	'click',
	( event ) => {
		const clear = event.target;
		if ( clear.classList.contains( 'smf-file-control__clear' ) ) {
			const controlWrapper = clear.closest( '.smf-file-control' );
			controlWrapper.classList.remove( 'smf-file-control--set' );
			controlWrapper.classList.remove( 'smf-file-control--uploaded' );

			const control = controlWrapper.querySelector(
				'.smf-file-control__control'
			);
			control.value = '';

			const placeholder = clear.closest( '.smf-placeholder' );
			const value = placeholder.querySelector(
				'.smf-file-control__value'
			);
			if ( !! value ) {
				value.remove();
			}
		}
	},
	false
);

const closest = ( el, targetClass ) => {
	for ( let item = el; item; item = item.parentElement ) {
		if ( item.classList.contains( targetClass ) ) {
			return item;
		}
	}
};

document.addEventListener(
	'click',
	( event ) => {
		const control = event.target;
		if ( !! control.getAttribute( 'data-action' ) ) {
			control.setAttribute( 'data-clicked', 'true' );
		}

		if ( 'back' === control.getAttribute( 'data-action' ) ) {
			const action = closest( control, 'smf-action' );
			if ( !! action ) {
				const method = action.querySelector(
					'[type="hidden"][name="snow-monkey-forms-meta[method]"]'
				);
				if ( !! method ) {
					method.setAttribute( 'value', 'back' );
				}
			}
		}
	},
	false
);

const forms = [].slice.call( document.querySelectorAll( '.snow-monkey-form' ) );
forms.forEach( ( form ) => {
	form.addEventListener( 'submit', ( event ) => {
		event.preventDefault();
		submit( form );
	} );

	init( form );
} );

[ 'change', 'keyup' ].forEach( ( eventName ) => {
	document.addEventListener(
		eventName,
		( event ) => {
			const control = event.target;
			if ( '1' === control.getAttribute( 'data-invalid' ) ) {
				control.removeAttribute( 'data-invalid' );

				[].slice
					.call( control.querySelectorAll( '[data-invalid="1"]' ) )
					.forEach( ( element ) =>
						element.removeAttribute( 'data-invalid' )
					);

				const placeholder = closest( control, 'smf-placeholder' );
				if ( !! placeholder ) {
					const errorMessage = placeholder.querySelector(
						'.smf-error-messages'
					);
					if ( !! errorMessage ) {
						errorMessage.parentNode.removeChild( errorMessage );
					}
				}
			}
		},
		false
	);
} );
