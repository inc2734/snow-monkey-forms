import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { NameControl, IdControl, ClassControl } from '../components';
import { uniqId } from '../helper';
import withValidations from '../../../hoc/with-validations';

import metadata from './block.json';

const Edit = ( { attributes, setAttributes } ) => {
	const {
		name,
		id,
		controlClass,
		description,
		isDisplayDescriptionConfirm,
		validations,
	} = attributes;

	useEffect( () => {
		setAttributes( {
			validations: JSON.stringify( {
				...JSON.parse( metadata.attributes.validations.default ),
				...JSON.parse( validations ),
			} ),
		} );
	}, [] );

	useEffect( () => {
		if ( '' === name ) {
			setAttributes( { name: `file-${ uniqId() }` } );
		}
	}, [ name ] );

	const blockProps = useBlockProps( {
		className: 'smf-placeholder',
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Attributes', 'snow-monkey-forms' ) }>
					<NameControl
						value={ name }
						onChange={ ( attribute ) =>
							setAttributes( { name: attribute } )
						}
					/>

					<IdControl
						value={ id }
						onChange={ ( attribute ) =>
							setAttributes( { id: attribute } )
						}
					/>

					<ClassControl
						value={ controlClass }
						onChange={ ( attribute ) =>
							setAttributes( { controlClass: attribute } )
						}
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Block settings', 'snow-monkey-forms' ) }
				>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Description', 'snow-monkey-forms' ) }
						value={ description }
						onChange={ ( attribute ) =>
							setAttributes( { description: attribute } )
						}
					/>

					<ToggleControl
						__nextHasNoMarginBottom
						label={ __(
							'Description is also displayed on the confirmation screen',
							'snow-monkey-forms'
						) }
						checked={ isDisplayDescriptionConfirm }
						onChange={ ( attribute ) => {
							setAttributes( {
								isDisplayDescriptionConfirm: attribute,
							} );
						} }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps } data-name={ name }>
				<div className="smf-file-control">
					<label htmlFor={ id || undefined }>
						<input
							type="file"
							name={ name }
							disabled="disabled"
							id={ id || undefined }
							className={ `smf-file-control__control ${ controlClass }` }
							data-validations={
								Object.keys(
									Object.fromEntries(
										Object.entries(
											JSON.parse( validations )
										).filter( ( [ , v ] ) => !! v )
									)
								).join( ' ' ) || undefined
							}
						/>
						<span className="smf-file-control__label">
							{ __( 'Choose file', 'snow-monkey-forms' ) }
						</span>
						<span className="smf-file-control__filename">
							{ __( 'No file chosen', 'snow-monkey-forms' ) }
						</span>
					</label>
				</div>
				{ description && (
					<div className="smf-control-description">
						{ description }
					</div>
				) }
			</div>
		</>
	);
};

export default compose( withValidations )( Edit );
