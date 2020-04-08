import { InnerBlocks, RichText } from '@wordpress/block-editor';

export default function( { attributes } ) {
	const { label, description } = attributes;

	return (
		<div className="smf-item" tabIndex="-1">
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
			<div className="smf-item__col smf-item__col--controls">
				<div className="smf-item__controls">
					<InnerBlocks.Content />
				</div>
			</div>
		</div>
	);
}
