import classnames from 'classnames';

import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function ( { attributes, className } ) {
	const { formStyle } = attributes;

	const classes = classnames( 'smf-form', className, {
		[ formStyle ]: !! formStyle,
	} );

	return (
		<div
			{ ...useInnerBlocksProps.save(
				useBlockProps.save( { className: classes } )
			) }
		/>
	);
}
