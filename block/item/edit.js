import classnames from 'classnames';
import { compact } from 'lodash';

import { getBlockTypes } from '@wordpress/blocks';

import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import {
	InspectorControls,
	InnerBlocks,
	RichText,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';

import { useMemo } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function ( {
	attributes,
	setAttributes,
	isSelected,
	className,
} ) {
	const { label, description, labelFor, isDisplayLabelColumn } = attributes;

	const allowedBlocks = useMemo( () => {
		const blocks = getBlockTypes();
		const blacklist = [
			'snow-monkey-forms/snow-monkey-form',
			'snow-monkey-forms/item',
		];

		return compact(
			blocks.map( ( block ) => {
				return ! blacklist.includes( block.name ) &&
					( ! block.parent ||
						block.parent.includes( 'snow-monkey-forms/noparent' ) )
					? block.name
					: null;
			} )
		);
	}, [] );

	const classes = classnames( 'smf-item', className, {
		'smf-item--divider': ! isDisplayLabelColumn,
	} );

	const blockProps = useBlockProps( {
		className: classes,
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'smf-item__controls',
		},
		{
			allowedBlocks,
			templateLock: false,
			renderAppender: InnerBlocks.ButtonBlockAppender,
		}
	);

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

					{ !! isDisplayLabelColumn && (
						<TextControl
							label={ __( 'label for', 'snow-monkey-forms' ) }
							help={ __(
								'Add a label element and link it with a form field of your choice. Enter the id of the form field you want to link to.',
								'snow-monkey-forms'
							) }
							value={ labelFor }
							onChange={ ( attribute ) =>
								setAttributes( { labelFor: attribute } )
							}
						/>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ isDisplayLabelColumn && (
					<div className="smf-item__col smf-item__col--label">
						<div className="smf-item__label">
							{ !! labelFor ? (
								<label htmlFor={ labelFor }>
									<RichText
										tagName="span"
										className="smf-item__label__text"
										value={ label }
										onChange={ ( value ) =>
											setAttributes( { label: value } )
										}
										placeholder={ __(
											'Label',
											'snow-monkey-forms'
										) }
									/>
								</label>
							) : (
								<RichText
									tagName="span"
									className="smf-item__label__text"
									value={ label }
									onChange={ ( value ) =>
										setAttributes( { label: value } )
									}
									placeholder={ __(
										'Label',
										'snow-monkey-forms'
									) }
								/>
							) }
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
					<div { ...innerBlocksProps } />
				</div>
			</div>
		</>
	);
}
