import { getBlockTypes } from '@wordpress/blocks';

import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

import { useMemo } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function () {
	const ALLOWED_BLOCKS = useMemo( () => {
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

	return (
		<div { ...blockProps }>
			<div className="components-panel__header edit-post-sidebar-header">
				{ __( 'Complete', 'snow-monkey-forms' ) }
			</div>
			<div className="components-panel__body is-opened">
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					templateLock={ false }
				/>
			</div>
		</div>
	);
}
