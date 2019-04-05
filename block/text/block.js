'use strict';

const { merge } = lodash;

const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody, TextControl, ToggleControl } = wp.components;
const { __ } = wp.i18n;

registerBlockType( 'snow-monkey-forms/text', {
	title: __( 'Text', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	supports: {
		customClassName: false,
	},

	edit( { attributes, setAttributes } ) {
		const { name, value, validations } = attributes;

		const parsedValidations = JSON.parse( validations );

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Validation', 'snow-monkey-forms' ) }>
						<ToggleControl
							label={ __( 'Required', 'snow-monkey-blocks' ) }
							checked={ !! parsedValidations['required'] }
							onChange={ ( value ) => {
								setAttributes( { validations: JSON.stringify( merge( parsedValidations, { required: value } ) ) } );
							} }
						/>
					</PanelBody>
				</InspectorControls>

				<TextControl
					label={ __( 'name', 'snow-monkey-forms' ) }
					value={ name }
					onChange={ ( value ) => setAttributes( { name: value } ) }
				/>
				<TextControl
					label={ __( 'value', 'snow-monkey-forms' ) }
					value={ value }
					onChange={ ( value ) => setAttributes( { value: value } ) }
				/>
			</Fragment>
		);
	},

	save() {
		return null;
	},
} );
