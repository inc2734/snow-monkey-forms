import classnames from 'classnames';

import { InnerBlocks, RichText, useBlockProps } from '@wordpress/block-editor';

export default function ( { attributes, className } ) {
	const { label, description, labelFor, isDisplayLabelColumn } = attributes;

	const classes = classnames( 'smf-item', className, {
		'smf-item--divider': ! isDisplayLabelColumn,
	} );

	return (
		<div { ...useBlockProps.save( { className: classes } ) }>
			{ isDisplayLabelColumn && (
				<div className="smf-item__col smf-item__col--label">
					<div className="smf-item__label">
						{ !! labelFor ? (
							<label htmlFor={ labelFor }>
								<RichText.Content
									tagName="span"
									className="smf-item__label__text"
									value={ label }
								/>
							</label>
						) : (
							<RichText.Content
								tagName="span"
								className="smf-item__label__text"
								value={ label }
							/>
						) }
					</div>
					{ ! RichText.isEmpty( description ) && (
						<div className="smf-item__description">
							<RichText.Content value={ description } />
						</div>
					) }
				</div>
			) }

			<div className="smf-item__col smf-item__col--controls">
				<div className="smf-item__controls">
					<InnerBlocks.Content />
				</div>
			</div>
		</div>
	);
}
