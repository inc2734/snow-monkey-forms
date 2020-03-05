import { TextControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

export default function() {
	const [ meta, setMeta ] = useEntityProp(
		'postType',
		'snow-monkey-forms',
		'meta'
	);

	const currentPost = useSelect( ( select ) => {
		return select( 'core/editor' ).getCurrentPost();
	}, [] );

	return (
		<TextControl
			label={ __( 'Subject', 'snow-monkey-forms' ) }
			value={
				! currentPost.title && ! meta.auto_reply_email_subject
					? __( 'Automatic reply notification', 'snow-monkey-forms' )
					: meta.auto_reply_email_subject
			}
			onChange={ ( value ) =>
				setMeta( { auto_reply_email_subject: value } )
			}
		/>
	);
}
