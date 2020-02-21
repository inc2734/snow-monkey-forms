import { TextControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

export default function() {
	const meta = useSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		return getEditedPostAttribute( 'meta' ).administrator_email_to;
	}, [] );

	const { editPost } = useDispatch( 'core/editor' );

	return (
		<TextControl
			label={ __( 'To', 'snow-monkey-forms' ) }
			value={ meta }
			onChange={ ( value ) =>
				editPost( {
					meta: { administrator_email_to: value },
				} )
			}
		/>
	);
}
