import $ from 'jquery';

export default function send( form ) {
	const actionArea = form.find( '.smf-action' );

	form.on( 'submit', ( event ) => {
		event.preventDefault();

		const icon = actionArea.find( '.smf-sending' );

		$.post( snowmonkeyforms.view_json_url, form.serialize() ).done(
			( response ) => {
				icon.attr( 'aria-hidden', 'true' );

				response = JSON.parse( response );
				const method = response.method;

				actionArea.html( response.action );
				form.find( '.smf-placeholder' ).html( '' );

				if (
					'' === method ||
					'back' === method ||
					'error' === method ||
					'confirm' === method
				) {
					$.each( response.controls, ( key, control ) => {
						const placeholder = form.find(
							`.smf-placeholder[data-name="${ key }"]`
						);
						placeholder.html( control );
					} );
				} else if (
					'complete' === method ||
					'system-error' === method
				) {
					form.html( response.message );
				} else {
					form.html( '' );
				}
			}
		);
	} );
}
