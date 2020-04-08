import classnames from 'classnames';

import { InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';
import { dispatch, useSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import FormSettingsPanel from './form-settings-panel';
import AdministratorEmailSettingsPanel from './administrator-email-settings-panel';
import AutoReplyEmailSettingsPanel from './auto-reply-email-settings-panel';

export default function( props ) {
	const { attributes, setAttributes, className } = props;
	const { formStyle } = attributes;

	const [ meta, setMeta ] = useEntityProp(
		'postType',
		'snow-monkey-forms',
		'meta'
	);

	const currentPost = useSelect( ( select ) => {
		return select( 'core/editor' ).getCurrentPost();
	}, [] );

	// Save initial meta values
	useEffect( () => {
		if ( 'auto-draft' === currentPost.status ) {
			setMeta( {
				administrator_email_subject: ! meta.administrator_email_subject
					? __( 'Admin notification', 'snow-monkey-forms' )
					: meta.administrator_email_subject,
				administrator_email_body: ! meta.administrator_email_body
					? '{all-fields}'
					: meta.administrator_email_body,
				auto_reply_email_to: ! meta.auto_reply_email_to
					? '{email}'
					: meta.auto_reply_email_to,
				auto_reply_email_subject: ! meta.auto_reply_email_subject
					? __( 'Automatic reply notification', 'snow-monkey-forms' )
					: meta.auto_reply_email_subject,
				auto_reply_email_body: ! meta.auto_reply_email_body
					? '{all-fields}'
					: meta.auto_reply_email_body,
			} );
		}
	}, [] );

	const classes = classnames( 'smf-form', className, {
		[ formStyle ]: !! formStyle,
	} );

	return (
		<>
			<InspectorControls>
				<FormSettingsPanel
					{ ...props }
					onChangeFormStyle={ ( value ) =>
						setAttributes( { formStyle: value } )
					}
				/>

				<AdministratorEmailSettingsPanel />
				<AutoReplyEmailSettingsPanel />
			</InspectorControls>

			<div className="components-panel snow-monkey-forms-setting-panel">
				<div className="components-panel__header edit-post-sidebar-header">
					{ __( 'Input', 'snow-monkey-forms' ) }

					<Button
						isDefault
						onClick={ () =>
							dispatch( 'core/edit-post' ).openGeneralSidebar(
								'edit-post/block'
							)
						}
					>
						{ __( 'Open the form settings', 'snow-monkey-forms' ) }
					</Button>
				</div>
				<div className="components-panel__body is-opened">
					<div className={ classes }>
						<InnerBlocks
							allowedBlocks={ [ 'snow-monkey-forms/item' ] }
							templateLock={ false }
						/>
					</div>
				</div>
			</div>
		</>
	);
}
