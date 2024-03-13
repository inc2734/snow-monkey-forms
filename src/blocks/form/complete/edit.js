import { getBlockTypes } from '@wordpress/blocks';

import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

import { useMemo } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function ( { attributes } ) {
	const { templateLock } = attributes;

	const allowedBlocks = useMemo( () => {
		const blocks = getBlockTypes();
		return blocks
			.map( ( block ) => {
				return ! block.name.match( /^snow-monkey-forms\// ) &&
					! block.parent
					? block.name
					: null;
			} )
			.filter( ( block ) => block );
	}, [] );

	const blockProps = useBlockProps( {
		className: [ 'components-panel', 'snow-monkey-forms-setting-panel' ],
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{},
		{
			allowedBlocks,
			templateLock,
		}
	);

	return (
		<div { ...blockProps }>
			<div className="components-panel__header edit-post-sidebar-header snow-monkey-forms-setting-panel__header">
				{ __( 'Complete', 'snow-monkey-forms' ) }
			</div>
			<div className="components-panel__body is-opened snow-monkey-forms-setting-panel__body">
				<div { ...innerBlocksProps } />
			</div>
		</div>
	);
}
