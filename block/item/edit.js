import { InnerBlocks, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function( { attributes, setAttributes } ) {
	const { label } = attributes;

	return (
		<div className="smf-item" tabIndex="-1">
			<div className="smf-item__col smf-item__col--label">
				<span className="smf-item__label">
					<RichText
						value={ label }
						onChange={ ( value ) =>
							setAttributes( { label: value } )
						}
						placeholder={ __( 'Label', 'snow-monkey-forms' ) }
					/>
				</span>
			</div>
			<div className="smf-item__col smf-item__col--controls">
				<div className="smf-item__controls">
					<InnerBlocks
						templateLock={ false }
						renderAppender={ () => (
							<InnerBlocks.ButtonBlockAppender />
						) }
					/>
				</div>
			</div>
		</div>
	);
}
