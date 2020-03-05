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
			label={ __( 'To', 'snow-monkey-forms' ) }
			value={
				! currentPost.title && ! meta.auto_reply_email_to
					? '{email}'
					: meta.auto_reply_email_to
			}
			onChange={ ( value ) => setMeta( { auto_reply_email_to: value } ) }
			help={ __(
				'Enter the name attribute value of the installed email form field in the following format: {name}',
				'snow-monkey-forms'
			) }
		/>
	);
}
