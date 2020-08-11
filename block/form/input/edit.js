import classnames from 'classnames';

import {
	InspectorControls,
	InnerBlocks,
	__experimentalBlock as Block,
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

	const BlockWrapper = Block.div;
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

			<BlockWrapper className="components-panel snow-monkey-forms-setting-panel">
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
			</BlockWrapper>
		</>
	);
}
