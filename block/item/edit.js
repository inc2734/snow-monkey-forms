import classnames from 'classnames';
import { compact } from 'lodash';

import { PanelBody, ToggleControl } from '@wordpress/components';
import {
	InspectorControls,
	InnerBlocks,
	RichText,
} from '@wordpress/block-editor';
import { getBlockTypes } from '@wordpress/blocks';
import { useMemo } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function( {
	attributes,
	setAttributes,
	isSelected,
	className,
} ) {
	const { label, description, isDisplayLabelColumn } = attributes;

	const ALLOWED_BLOCKS = useMemo( () => {
		const blocks = getBlockTypes();
		return compact(
			blocks.map( ( block ) => {
				const blacklist = [
					'snow-monkey-forms/snow-monkey-form',
					'snow-monkey-forms/item',
				];
				return ! blacklist.includes( block.name ) && ! block.parent
					? block.name
					: null;
			} )
		);
	}, [] );

	const classes = classnames( 'smf-item', className, {
		'smf-item--divider': ! isDisplayLabelColumn,
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Block settings', 'snow-monkey-forms' ) }
				>
					<ToggleControl
						label={ __(
							'Display label column',
							'snow-monkey-forms'
						) }
						checked={ isDisplayLabelColumn }
						onChange={ ( attribute ) =>
							setAttributes( { isDisplayLabelColumn: attribute } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div className={ classes } tabIndex="-1">
				{ isDisplayLabelColumn && (
					<div className="smf-item__col smf-item__col--label">
						<div className="smf-item__label">
							<RichText
								value={ label }
								onChange={ ( value ) =>
									setAttributes( { label: value } )
								}
								placeholder={ __(
									'Label',
									'snow-monkey-forms'
								) }
							/>
						</div>
						{ ( ! RichText.isEmpty( description ) ||
							isSelected ) && (
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
				) }

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
		</>
	);
}
