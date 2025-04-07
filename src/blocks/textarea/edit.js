import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import {
	NameControl,
	ValueControl,
	PlaceholderControl,
	MaxLengthControl,
	AutocompleteControl,
	IdControl,
	ClassControl,
} from '../components';

import { stringToNumber, uniqId } from '../helper';
import withValidations from '../../../hoc/with-validations';

import metadata from './block.json';

const Edit = ( { attributes, setAttributes } ) => {
	const {
		name,
		rows,
		value,
		placeholder,
		maxlength,
		autocomplete,
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
			setAttributes( { name: `textarea-${ uniqId() }` } );
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

					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'rows', 'snow-monkey-forms' ) }
						help={ __( 'Number of lines', 'snow-monkey-forms' ) }
						type="number"
						value={ rows }
						onChange={ ( attribute ) => {
							attribute = stringToNumber( attribute, rows );
							setAttributes( {
								rows: 0 < attribute ? attribute : 1,
							} );
						} }
					/>

					<ValueControl
						multiple={ true }
						value={ value }
						onChange={ ( attribute ) =>
							setAttributes( { value: attribute } )
						}
					/>

					<PlaceholderControl
						value={ placeholder }
						onChange={ ( attribute ) =>
							setAttributes( { placeholder: attribute } )
						}
					/>

					<MaxLengthControl
						value={ maxlength }
						onChange={ ( attribute ) => {
							setAttributes( {
								maxlength: stringToNumber(
									attribute,
									maxlength
								),
							} );
						} }
					/>

					<AutocompleteControl
						value={ autocomplete }
						onChange={ ( attribute ) => {
							setAttributes( { autocomplete: attribute } );
						} }
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
				<div className="smf-textarea-control">
					<textarea
						name={ name }
						value={ value }
						rows={ rows }
						placeholder={ placeholder }
						maxLength={ maxlength || undefined }
						disabled="disabled"
						id={ id || undefined }
						className={ `smf-textarea-control__control ${ controlClass }` }
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
