import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import {
	NameControl,
	OptionsControl,
	ValueControl,
	AutocompleteControl,
	IdControl,
	ClassControl,
} from '../components';

import { uniqId, optionsToJsonArray } from '../helper';
import withValidations from '../../../hoc/with-validations';

import metadata from './block.json';

const Edit = ( { attributes, setAttributes } ) => {
	const {
		name,
		value,
		options,
		id,
		controlClass,
		description,
		isDisplayDescriptionConfirm,
		validations,
		autocomplete,
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
			setAttributes( { name: `select-${ uniqId() }` } );
		}

		if ( '' === options ) {
			setAttributes( {
				options: 'value1\n"value2" : "label2"\n"value3" : "label3"',
			} );
		}
	}, [ name, options ] );

	const arrayedOptions = optionsToJsonArray( options );

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

					<OptionsControl
						value={ options }
						onChange={ ( attribute ) =>
							setAttributes( { options: attribute } )
						}
					/>

					<ValueControl
						value={ value }
						onChange={ ( attribute ) =>
							setAttributes( { value: attribute } )
						}
					/>

					<AutocompleteControl
						value={ autocomplete }
						onChange={ ( attribute ) =>
							setAttributes( { autocomplete: attribute } )
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
						label={ __( 'Description', 'snow-monkey-forms' ) }
						value={ description }
						onChange={ ( attribute ) =>
							setAttributes( { description: attribute } )
						}
					/>

					<ToggleControl
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
				<div className="smf-select-control">
					<select
						name={ name }
						value={ value }
						disabled="disabled"
						id={ id || undefined }
						className={ `smf-select-control__control ${ controlClass }` }
						data-validations={
							Object.keys(
								Object.fromEntries(
									Object.entries(
										JSON.parse( validations )
									).filter( ( [ , v ] ) => !! v )
								)
							).join( ' ' ) || undefined
						}
					>
						{ arrayedOptions.map( ( option ) => {
							const optionValue = Object.keys( option )[ 0 ];
							const optionLabel = Object.values( option )[ 0 ];
							return (
								<option
									value={ optionValue }
									key={ optionValue }
								>
									{ optionLabel }
								</option>
							);
						} ) }
					</select>
					<span className="smf-select-control__toggle"></span>
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
