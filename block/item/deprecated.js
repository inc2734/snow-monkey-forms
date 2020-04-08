import { InnerBlocks, RichText } from '@wordpress/block-editor';

export default [
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
				<div className="smf-item" tabIndex="-1">
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
