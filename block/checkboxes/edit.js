import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

import { NameControl, OptionsControl, ValuesControl } from '../components';
import { uniqId, optionsToJsonArray, valuesToJsonArray } from '../helper';
import withValidations from '../../hoc/with-validations';

const edit = ( { attributes, setAttributes } ) => {
	const { name, options, values, delimiter, description } = attributes;

	if ( '' === name ) {
		setAttributes( { name: `checkboxes-${ uniqId() }` } );
	}

	if ( '' === options ) {
		setAttributes( {
			options: 'value1\n"value2" : "label2"\n"value3" : "label3"',
		} );
	}

	const arrayedOptions = optionsToJsonArray( options );
	const arrayedValues = valuesToJsonArray( values );

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

					<ValuesControl
						value={ values }
						onChange={ ( attribute ) =>
							setAttributes( { values: attribute } )
						}
					/>

					<TextControl
						label={ __( 'Delimiter', 'snow-monkey-forms' ) }
						help={ __(
							'Optional. Character that separates each item.',
							'snow-monkey-forms'
						) }
						value={ delimiter }
						onChange={ ( attribute ) =>
							setAttributes( { delimiter: attribute } )
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
				</PanelBody>
			</InspectorControls>
			<div className="smf-placeholder" data-name={ name }>
				<div className="smf-checkboxes-control">
					<div className="smf-checkboxes-control__control">
						{ arrayedOptions.map( ( option ) => {
							const optionValue = Object.keys( option )[ 0 ];
							const optionLabel = Object.values( option )[ 0 ];

							return (
								<label
									className="smf-label"
									key={ optionValue }
									htmlFor={ `${ name }-${ optionValue }` }
								>
									<span className="smf-checkbox-control">
										<input
											type="checkbox"
											name={ `${ name }[]` }
											value={ optionValue }
											checked={ arrayedValues.includes(
												optionValue
											) }
											disabled="disabled"
											className="smf-checkbox-control__control"
											id={ `${ name }-${ optionValue }` }
										/>
										<span className="smf-checkbox-control__label">
											{ optionLabel }
										</span>
									</span>
								</label>
							);
						} ) }
					</div>
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

export default compose( withValidations )( edit );
