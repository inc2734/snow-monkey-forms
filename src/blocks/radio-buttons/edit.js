import classnames from 'classnames';

import {
	InspectorControls,
	useBlockProps,
	RichText,
} from '@wordpress/block-editor';

import {
	PanelBody,
	TextControl,
	SelectControl,
	ToggleControl,
} from '@wordpress/components';

import { compose } from '@wordpress/compose';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { NameControl, OptionsControl, ValueControl } from '../components';
import { uniqId, optionsToJsonArray } from '../helper';
import withValidations from '../../../hoc/with-validations';

import metadata from './block.json';

const Edit = ( { attributes, setAttributes } ) => {
	const {
		name,
		grouping,
		legend,
		legendInvisible,
		value,
		options,
		direction,
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
			setAttributes( { name: `radio-buttons-${ uniqId() }` } );
		}

		if ( '' === options ) {
			setAttributes( {
				options: 'value1\n"value2" : "label2"\n"value3" : "label3"',
			} );
		}
	}, [ name, options ] );

	const arrayedOptions = optionsToJsonArray( options );

	const classes = classnames( 'smf-radio-buttons-control', {
		[ `smf-radio-buttons-control--${ direction }` ]: !! direction,
	} );

	const blockProps = useBlockProps( {
		className: 'smf-placeholder',
	} );

	const RadioButtons = () => (
		<div
			className="smf-radio-buttons-control__control"
			data-validations={
				Object.keys(
					Object.fromEntries(
						Object.entries( JSON.parse( validations ) ).filter(
							( [ , v ] ) => !! v
						)
					)
				).join( ' ' ) || undefined
			}
		>
			{ arrayedOptions.map( ( option ) => {
				const optionValue = Object.keys( option )[ 0 ];
				const optionLabel = Object.values( option )[ 0 ];

				return (
					<div className="smf-label" key={ optionValue }>
						<label htmlFor={ `${ name }-${ optionValue }` }>
							<span className="smf-radio-button-control">
								<input
									type="radio"
									name={ name }
									value={ optionValue }
									checked={ optionValue === value }
									disabled="disabled"
									className="smf-radio-button-control__control"
									id={ `${ name }-${ optionValue }` }
								/>
								<span className="smf-radio-button-control__label">
									{ optionLabel }
								</span>
							</span>
						</label>
					</div>
				);
			} ) }
		</div>
	);

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

					<ToggleControl
						label={ __( 'Grouping', 'snow-monkey-forms' ) }
						help={ __(
							'Enable if you want to group by fieldset and label by legend.',
							'snow-monkey-forms'
						) }
						checked={ grouping }
						onChange={ ( attribute ) => {
							setAttributes( {
								grouping: attribute,
							} );
						} }
					/>

					{ grouping && (
						<ToggleControl
							label={ __(
								'Make legend invisible',
								'snow-monkey-forms'
							) }
							help={ __(
								'When activated, the legend will not appear on the screen, but will be read by screen readers.',
								'snow-monkey-forms'
							) }
							checked={ legendInvisible }
							onChange={ ( attribute ) => {
								setAttributes( {
									legendInvisible: attribute,
								} );
							} }
						/>
					) }

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
				</PanelBody>

				<PanelBody
					title={ __( 'Block settings', 'snow-monkey-forms' ) }
				>
					<SelectControl
						label={ __( 'Direction', 'snow-monkey-forms' ) }
						value={ direction }
						options={ [
							{
								value: '',
								label: __( 'Default', 'snow-monkey-forms' ),
							},
							{
								value: 'horizontal',
								label: __( 'Horizontal', 'snow-monkey-forms' ),
							},
							{
								value: 'vertical',
								label: __( 'Vertical', 'snow-monkey-forms' ),
							},
						] }
						onChange={ ( attribute ) =>
							setAttributes( { direction: attribute } )
						}
					/>

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
				<div className={ classes }>
					{ grouping ? (
						<fieldset className="smf-control-fieldset">
							<RichText
								tagName="legend"
								value={ legend }
								onChange={ ( attribute ) =>
									setAttributes( { legend: attribute } )
								}
								placeholder={ __(
									'Label',
									'snow-monkey-forms'
								) }
								className={ classnames( 'smf-control-legend', {
									'screen-reader-text': legendInvisible,
								} ) }
							/>

							<RadioButtons />
						</fieldset>
					) : (
						<RadioButtons />
					) }
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
