import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import HelpButton from '../../../plugin-sidebar/help-button';

export default function() {
	const [ meta, setMeta ] = useEntityProp(
		'postType',
		'snow-monkey-forms',
		'meta'
	);

	const currentPost = useSelect( ( select ) => {
		return select( 'core/editor' ).getCurrentPost();
	}, [] );

	const inputErrorStyles = {
		borderColor: '#d94f4f',
	};

	return (
		<PanelBody title={ __( 'Auto reply email', 'snow-monkey-forms' ) }>
			<TextControl
				label={ __( 'To (Email address)', 'snow-monkey-forms' ) }
				help={ __(
					'Enter the name attribute value of the installed email form field in the following format: {name}',
					'snow-monkey-forms'
				) }
				value={
					! currentPost.title && ! meta.auto_reply_email_to
						? '{email}'
						: meta.auto_reply_email_to
				}
				onChange={ ( value ) =>
					setMeta( { auto_reply_email_to: value } )
				}
				style={
					! meta.auto_reply_email_to &&
					( !! meta.auto_reply_email_subject ||
						!! meta.auto_reply_email_body )
						? inputErrorStyles
						: undefined
				}
			/>

			<TextControl
				label={ __( 'Subject', 'snow-monkey-forms' ) }
				value={
					! currentPost.title && ! meta.auto_reply_email_subject
						? __(
								'Automatic reply notification',
								'snow-monkey-forms'
						  )
						: meta.auto_reply_email_subject
				}
				onChange={ ( value ) =>
					setMeta( { auto_reply_email_subject: value } )
				}
				style={
					!! meta.auto_reply_email_to &&
					! meta.auto_reply_email_subject
						? inputErrorStyles
						: undefined
				}
			/>

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
				style={
					!! meta.auto_reply_email_to && ! meta.auto_reply_email_body
						? inputErrorStyles
						: undefined
				}
			/>

			<TextControl
				label={ __( 'From (Email address)', 'snow-monkey-forms' ) }
				help={ __( 'Optional', 'snow-monkey-forms' ) }
				value={ meta.auto_reply_email_from }
				onChange={ ( value ) =>
					setMeta( { auto_reply_email_from: value } )
				}
			/>

			<TextControl
				label={ __( 'Sender', 'snow-monkey-forms' ) }
				help={ __( 'Optional', 'snow-monkey-forms' ) }
				value={ meta.auto_reply_email_sender }
				onChange={ ( value ) =>
					setMeta( { auto_reply_email_sender: value } )
				}
			/>

			<HelpButton />
		</PanelBody>
	);
}
