import classnames from 'classnames';

import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function ( { attributes, className } ) {
	const { formStyle } = attributes;

	const classes = classnames( 'smf-form', className, {
		[ formStyle ]: !! formStyle,
	} );

	return (
		<div { ...useBlockProps.save( { className: classes } ) }>
			<InnerBlocks.Content />
		</div>
	);
}
