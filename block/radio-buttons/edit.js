import classnames from 'classnames';

import {
	InspectorControls,
	__experimentalBlock as Block,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

import { NameControl, OptionsControl, ValueControl } from '../components';
import { uniqId, optionsToJsonArray } from '../helper';
import withValidations from '../../hoc/with-validations';

const edit = ( { attributes, setAttributes } ) => {
	const { name, value, options, direction, description } = attributes;

	if ( '' === name ) {
		setAttributes( { name: `radio-buttons-${ uniqId() }` } );
	}

	if ( '' === options ) {
		setAttributes( {
			options: 'value1\n"value2" : "label2"\n"value3" : "label3"',
		} );
	}

	const arrayedOptions = optionsToJsonArray( options );

	const BlockWrapper = Block.div;
	const classes = classnames( 'smf-radio-buttons-control', {
		[ `smf-radio-buttons-control--${ direction }` ]: !! direction,
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
				</PanelBody>
			</InspectorControls>

			<BlockWrapper className="smf-placeholder" data-name={ name }>
				<div className={ classes }>
					<div className="smf-radio-buttons-control__control">
						{ arrayedOptions.map( ( option ) => {
							const optionValue = Object.keys( option )[ 0 ];
							const optionLabel = Object.values( option )[ 0 ];

							return (
								<div className="smf-label" key={ optionValue }>
									<label
										htmlFor={ `${ name }-${ optionValue }` }
									>
										<span className="smf-radio-button-control">
											<input
												type="radio"
												name={ name }
												value={ optionValue }
												checked={
													optionValue === value
												}
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
				</div>
				{ description && (
					<div className="smf-control-description">
						{ description }
					</div>
				) }
			</BlockWrapper>
		</>
	);
};

export default compose( withValidations )( edit );
