import { TextareaControl } from '@wordpress/components';
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
		<TextareaControl
			label={ __( 'Body', 'snow-monkey-forms' ) }
			value={
				! currentPost.title && ! meta.auto_reply_email_body
					? '{all-fields}'
					: meta.auto_reply_email_body
			}
			onChange={ ( value ) =>
				setMeta( { auto_reply_email_body: value } )
			}
		/>
	);
}
