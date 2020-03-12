import { TextControl, TextareaControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import { __ } from '@wordpress/i18n';

import HelpButton from '../../plugin-sidebar/help-button';

const Component = () => {
	const [ meta, setMeta ] = useEntityProp(
		'postType',
		'snow-monkey-forms',
		'meta'
	);

	const currentPost = useSelect( ( select ) => {
		return select( 'core/editor' ).getCurrentPost();
	}, [] );

	return (
		<>
			<PluginDocumentSettingPanel
				name="snow-monkey-form/administrator-email"
				title={ __( 'Administrator email', 'snow-monkey-forms' ) }
				opened={ true }
			>
				<TextControl
					label={ __( 'To (Email address)', 'snow-monkey-forms' ) }
					value={ meta.administrator_email_to }
					onChange={ ( value ) =>
						setMeta( { administrator_email_to: value } )
					}
				/>

				<TextControl
					label={ __( 'Subject', 'snow-monkey-forms' ) }
					value={
						! currentPost.title &&
						! meta.administrator_email_subject
							? __( 'Admin notification', 'snow-monkey-forms' )
							: meta.administrator_email_subject
					}
					onChange={ ( value ) =>
						setMeta( { administrator_email_subject: value } )
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
				/>

				<HelpButton />
			</PluginDocumentSettingPanel>

			<PluginDocumentSettingPanel
				name="snow-monkey-form/auto-reply-email"
				title={ __( 'Auto reply email', 'snow-monkey-forms' ) }
				opened={ false }
			>
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
			</PluginDocumentSettingPanel>
		</>
	);
};

registerPlugin( 'plugin-snow-monkey-form-sidebar', {
	render: Component,
} );
