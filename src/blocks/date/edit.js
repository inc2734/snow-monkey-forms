import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import {
	NameControl,
	ValueControl,
	IdControl,
	ClassControl,
} from '../components';

import { uniqId } from '../helper';
import withValidations from '../../../hoc/with-validations';

const Edit = ( { attributes, setAttributes } ) => {
	const {
		name,
		value,
		min,
		max,
		id,
		controlClass,
		description,
		isDisplayDescriptionConfirm,
	} = attributes;

	useEffect( () => {
		if ( '' === name ) {
			setAttributes( { name: `date-${ uniqId() }` } );
		}
	} );

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

					<ValueControl
						value={ value }
						onChange={ ( attribute ) =>
							setAttributes( { value: attribute } )
						}
					/>

					<TextControl
						label={ __( 'Minimum date', 'snow-monkey-forms' ) }
						type="date"
						value={ min }
						onChange={ ( attribute ) =>
							setAttributes( { min: attribute } )
						}
					/>

					<TextControl
						label={ __( 'Maximum date', 'snow-monkey-forms' ) }
						type="date"
						value={ max }
						onChange={ ( attribute ) =>
							setAttributes( { max: attribute } )
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
				<div className="smf-text-control">
					<input
						type="date"
						name={ name }
						value={ value }
						min={ min || undefined }
						max={ max || undefined }
						disabled="disabled"
						id={ id || undefined }
						pattern="\d{4}-\d{2}-\d{2}"
						className={ `smf-text-control__control ${ controlClass }` }
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
