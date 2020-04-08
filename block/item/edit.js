import { compact } from 'lodash';

import { InnerBlocks, RichText } from '@wordpress/block-editor';
import { getBlockTypes } from '@wordpress/blocks';
import { useMemo } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function( { attributes, setAttributes, isSelected } ) {
	const { label, description } = attributes;

	const blocks = getBlockTypes();
	const ALLOWED_BLOCKS = useMemo( () => {
		return compact(
			blocks.map( ( blockType ) => {
				const blacklist = [
					'snow-monkey-forms/snow-monkey-form',
					'snow-monkey-forms/item',
				];
				return ! blacklist.includes( blockType.name )
					? blockType.name
					: null;
			} )
		);
	}, [ blocks ] );

	return (
		<div className="smf-item" tabIndex="-1">
			<div className="smf-item__col smf-item__col--label">
				<div className="smf-item__label">
					<RichText
						value={ label }
						onChange={ ( value ) =>
							setAttributes( { label: value } )
						}
						placeholder={ __( 'Label', 'snow-monkey-forms' ) }
					/>
				</div>
				{ ( ! RichText.isEmpty( description ) || isSelected ) && (
					<div className="smf-item__description">
						<RichText
							value={ description }
							onChange={ ( value ) =>
								setAttributes( { description: value } )
							}
							placeholder={ __(
								'Description',
								'snow-monkey-forms'
							) }
						/>
					</div>
				) }
			</div>
			<div className="smf-item__col smf-item__col--controls">
				<div className="smf-item__controls">
					<InnerBlocks
						allowedBlocks={ ALLOWED_BLOCKS }
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
