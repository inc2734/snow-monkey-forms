import classnames from 'classnames';

import {
	InspectorControls,
	InnerBlocks,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';

import { dispatch } from '@wordpress/data';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import FormSettingsPanel from './form-settings-panel';
import AdministratorEmailSettingsPanel from './administrator-email-settings-panel';
import AutoReplyEmailSettingsPanel from './auto-reply-email-settings-panel';

export default function ( props ) {
	const { attributes, setAttributes, className } = props;
	const { formStyle } = attributes;

	const allowedBlocks = [ 'snow-monkey-forms/item' ];

	const classes = classnames( 'smf-form', className, {
		[ formStyle ]: !! formStyle,
	} );

	const blockProps = useBlockProps( {
		className: [ 'components-panel', 'snow-monkey-forms-setting-panel' ],
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: classes,
		},
		{
			allowedBlocks,
			templateLock: false,
			renderAppender: InnerBlocks.ButtonBlockAppender,
		}
	);

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

			<div { ...blockProps }>
				<div className="components-panel__header edit-post-sidebar-header">
					{ __( 'Input', 'snow-monkey-forms' ) }

					<Button
						isSecondary
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
					<div { ...innerBlocksProps } />
				</div>
			</div>
		</>
	);
}
