import { TextControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

export default function() {
	const meta = useSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		return getEditedPostAttribute( 'meta' ).auto_reply_email_to;
	}, [] );

	const { editPost } = useDispatch( 'core/editor' );

	const currentPost = useSelect( ( select ) => {
		return select( 'core/editor' ).getCurrentPost();
	}, [] );

	return (
		<TextControl
			label={ __( 'To', 'snow-monkey-forms' ) }
			value={ ! currentPost.title && ! meta ? '{email}' : meta }
			onChange={ ( value ) =>
				editPost( {
					meta: { auto_reply_email_to: value },
				} )
			}
			help={ __(
				'Enter the name attribute value of the installed email form field in the following format: {name}',
				'snow-monkey-forms'
			) }
		/>
	);
}
