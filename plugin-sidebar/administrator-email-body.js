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
				! currentPost.title && ! meta.administrator_email_body
					? '{all-fields}'
					: meta.administrator_email_body
			}
			onChange={ ( value ) =>
				setMeta( { administrator_email_body: value } )
			}
		/>
	);
}
