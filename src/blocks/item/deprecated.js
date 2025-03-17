import classnames from 'classnames';

import {
	InnerBlocks,
	RichText,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';

import metadata from './block.json';

export default [
	{
		attributes: {
			...metadata.attributes,
		},

		save( { attributes, className } ) {
			const { label, description, labelFor, isDisplayLabelColumn } =
				attributes;

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
						<div
							{ ...useInnerBlocksProps.save( {
								className: 'smf-item__controls',
							} ) }
						/>
					</div>
				</div>
			);
		},
	},
	{
		attributes: {
			label: {
				type: 'string',
				source: 'html',
				selector: '.smf-item__label',
				default: '',
			},
			description: {
				type: 'string',
				source: 'html',
				selector: '.smf-item__description',
				default: '',
			},
			labelFor: {
				type: 'string',
				source: 'attribute',
				selector: '.smf-label',
				attribute: 'for',
				default: '',
			},
			isDisplayLabelColumn: {
				type: 'boolean',
				default: true,
			},
		},

		save( { attributes, className } ) {
			const { label, description, isDisplayLabelColumn } = attributes;

			const classes = classnames( 'smf-item', className, {
				'smf-item--divider': ! isDisplayLabelColumn,
			} );

			return (
				<div { ...useBlockProps.save( { className: classes } ) }>
					{ isDisplayLabelColumn && (
						<div className="smf-item__col smf-item__col--label">
							<div className="smf-item__label">
								<RichText.Content value={ label } />
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
		},
	},
	{
		attributes: {
			label: {
				type: 'string',
				source: 'html',
				selector: '.smf-item__label',
				default: '',
			},
			description: {
				type: 'string',
				source: 'html',
				selector: '.smf-item__description',
				default: '',
			},
			isDisplayLabelColumn: {
				type: 'boolean',
				default: true,
			},
		},

		save( { attributes, className } ) {
			const { label, description, isDisplayLabelColumn } = attributes;

			const classes = classnames( 'smf-item', className, {
				'smf-item--divider': ! isDisplayLabelColumn,
			} );

			return (
				<div
					{ ...useBlockProps.save( { className: classes } ) }
					tabIndex="-1"
				>
					{ isDisplayLabelColumn && (
						<div className="smf-item__col smf-item__col--label">
							<div className="smf-item__label">
								<RichText.Content value={ label } />
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
		},
	},
	{
		attributes: {
			label: {
				type: 'string',
				default: '',
			},
		},

		save( { attributes } ) {
			const { label } = attributes;

			return (
				<div className="smf-item">
					<div className="smf-item__col smf-item__col--label">
						<span className="smf-item__label">
							<RichText.Content value={ label } />
						</span>
					</div>
					<div className="smf-item__col smf-item__col--controls">
						<div className="smf-item__controls">
							<InnerBlocks.Content />
						</div>
					</div>
				</div>
			);
		},
	},
];
