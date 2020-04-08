import classnames from 'classnames';

import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { dispatch } from '@wordpress/data';
import { PanelBody, SelectControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import UseConfirmPage from './use-confirm-page';
import UseProgressTracker from './use-progress-tracker';

registerBlockType( 'snow-monkey-forms/form--input', {
	title: __( 'Input page', 'snow-monkey-forms' ),
	icon: 'editor-ul',
	category: 'snow-monkey-forms',
	parent: [ false ],
	supports: {
		customClassName: true,
		className: false,
		inserter: false,
		multiple: false,
		reusable: false,
	},

	attributes: {
		formStyle: {
			type: 'string',
			default: '',
		},
	},

	edit( { attributes, setAttributes, className } ) {
		const { formStyle } = attributes;

		const classes = classnames( 'smf-form', className, {
			[ formStyle ]: !! formStyle,
		} );

		return (
			<>
				<InspectorControls>
					<PanelBody title={ __( 'Settings', 'snow-monkey-forms' ) }>
						<UseConfirmPage />
						<UseProgressTracker />

						<SelectControl
							label={ __( 'Form style', 'snow-monkey-forms' ) }
							value={ formStyle }
							options={ [
								{
									value: '',
									label: __( 'Default', 'snow-monkey-forms' ),
								},
								{
									value: 'smf-form--simple-table',
									label: __(
										'Simple table',
										'snow-monkey-forms'
									),
								},
								{
									value: 'smf-form--letter',
									label: __( 'Letter', 'snow-monkey-forms' ),
								},
								{
									value: 'smf-form--business',
									label: __(
										'Business',
										'snow-monkey-forms'
									),
								},
							] }
							onChange={ ( value ) =>
								setAttributes( { formStyle: value } )
							}
						/>
					</PanelBody>
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
							{ __(
								'Open input page settings',
								'snow-monkey-forms'
							) }
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
	},

	save( { attributes, className } ) {
		const { formStyle } = attributes;

		const classes = classnames( 'smf-form', className, {
			[ formStyle ]: !! formStyle,
		} );

		return (
			<div className={ classes }>
				<InnerBlocks.Content />
			</div>
		);
	},
} );
