import { TextareaControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

export default function() {
	const meta = useSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		return getEditedPostAttribute( 'meta' ).auto_reply_email_body;
	}, [] );

	const { editPost } = useDispatch( 'core/editor' );

	return (
		<TextareaControl
			label={ __( 'Body', 'snow-monkey-forms' ) }
			value={ meta }
			onChange={ ( value ) =>
				editPost( {
					meta: { auto_reply_email_body: value },
				} )
			}
		/>
	);
}
