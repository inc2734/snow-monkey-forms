import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

import {
	NameControl,
	OptionsControl,
	ValueControl,
	IdControl,
	ClassControl,
} from '../components';
import { uniqId, optionsToJsonArray } from '../helper';
import withValidations from '../../hoc/with-validations';

const edit = ( { attributes, setAttributes } ) => {
	const { name, value, options, id, controlClass, description } = attributes;

	if ( '' === name ) {
		setAttributes( { name: `select-${ uniqId() }` } );
	}

	if ( '' === options ) {
		setAttributes( {
			options: 'value1\n"value2" : "label2"\n"value3" : "label3"',
		} );
	}

	const arrayedOptions = optionsToJsonArray( options );

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
				</PanelBody>
			</InspectorControls>
			<div className="smf-placeholder" data-name={ name }>
				<div className="smf-select-control">
					<select
						name={ name }
						value={ value }
						disabled="disabled"
						id={ id || undefined }
						className={ `smf-select-control__control ${ controlClass }` }
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

export default compose( withValidations )( edit );
