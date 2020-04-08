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
		<PanelBody title={ __( 'Administrator email', 'snow-monkey-forms' ) }>
			<TextControl
				label={ __( 'To (Email address)', 'snow-monkey-forms' ) }
				value={ meta.administrator_email_to }
				onChange={ ( value ) =>
					setMeta( { administrator_email_to: value } )
				}
				style={
					! meta.administrator_email_to ? inputErrorStyles : undefined
				}
			/>

			<TextControl
				label={ __( 'Subject', 'snow-monkey-forms' ) }
				value={
					! currentPost.title && ! meta.administrator_email_subject
						? __( 'Admin notification', 'snow-monkey-forms' )
						: meta.administrator_email_subject
				}
				onChange={ ( value ) =>
					setMeta( {
						administrator_email_subject: value,
					} )
				}
				style={
					! meta.administrator_email_subject
						? inputErrorStyles
						: undefined
				}
			/>

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
				style={
					! meta.administrator_email_body
						? inputErrorStyles
						: undefined
				}
			/>

			<TextControl
				label={ __( 'From (Email address)', 'snow-monkey-forms' ) }
				help={ __( 'Optional', 'snow-monkey-forms' ) }
				value={ meta.administrator_email_from }
				onChange={ ( value ) =>
					setMeta( { administrator_email_from: value } )
				}
			/>

			<TextControl
				label={ __( 'Sender', 'snow-monkey-forms' ) }
				help={ __( 'Optional', 'snow-monkey-forms' ) }
				value={ meta.administrator_email_sender }
				onChange={ ( value ) =>
					setMeta( { administrator_email_sender: value } )
				}
			/>

			<HelpButton />
		</PanelBody>
	);
}
