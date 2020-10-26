// import $ from 'jquery';
import submit from './module/submit';

document.addEventListener(
	'change',
	( event ) => {
		const control = event.target;
		if ( control.classList.contains( 'smf-file-control__control' ) ) {
			const filename = control.parentNode.querySelector(
				'.smf-file-control__filename'
			);
			const files = control.files;
			if ( 0 < files.length && !! filename ) {
				const file = files[ 0 ];
				if ( 'undefined' !== typeof file.name ) {
					filename.textContent = file.name;
				}
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
	form.addEventListener( 'submit', submit, false );
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
