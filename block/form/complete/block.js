import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

registerBlockType( 'snow-monkey-forms/form--complete', {
	title: __( 'Complete', 'snow-monkey-forms' ),
	icon: 'editor-ul',
	category: 'snow-monkey-forms',
	parent: [ false ],
	supports: {
		customClassName: false,
	},

	edit() {
		return (
			<div className="components-panel snow-monkey-forms-setting-panel">
				<div className="components-panel__header edit-post-sidebar-header">
					{ __( 'Complete', 'snow-monkey-forms' ) }
				</div>
				<div className="components-panel__body is-opened">
					<InnerBlocks templateLock={ false } />
				</div>
			</div>
		);
	},

	save() {
		return <InnerBlocks.Content />;
	},
} );
