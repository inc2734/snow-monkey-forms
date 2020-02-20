import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

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

	styles: [
		{
			name: 'smf-form-default',
			label: __( 'Default', 'block-style', 'snow-monkey-forms' ),
		},
		{
			name: 'smf-form-1',
			label: __( 'Form', 'block-style', 'snow-monkey-forms' ) + '1',
		},
		{
			name: 'smf-form-2',
			label: __( 'Form', 'block-style', 'snow-monkey-forms' ) + '2',
		},
	],

	edit( { className } ) {
		const newClassName = !! className ? className : '';

		return (
			<div className="components-panel snow-monkey-forms-setting-panel">
				<div className="components-panel__header edit-post-sidebar-header">
					{ __( 'Input', 'snow-monkey-forms' ) }
				</div>
				<div className="components-panel__body is-opened">
					<div className={ `smf-form ${ newClassName }` }>
						<InnerBlocks
							allowedBlocks={ [ 'snow-monkey-forms/item' ] }
							templateLock={ false }
						/>
					</div>
				</div>
			</div>
		);
	},

	save( { className } ) {
		const newClassName = !! className ? className : '';

		return (
			<div className={ `smf-form ${ newClassName }` }>
				<InnerBlocks.Content />
			</div>
		);
	},
} );
