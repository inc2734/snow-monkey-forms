'use strict';

const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { TextControl, TextareaControl } = wp.components;
const { __ } = wp.i18n;

registerBlockType( 'snow-monkey-forms/select', {
	title: __( 'Select', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	supports: {
		customClassName: false,
	},

	edit( { attributes, setAttributes } ) {
		const { name, value, options } = attributes;

		return (
			<Fragment>
				<TextControl
					label={ __( 'name', 'snow-monkey-forms' ) }
					value={ name }
					onChange={ ( attribute ) => setAttributes( { name: attribute } ) }
				/>
				<TextControl
					label={ __( 'value', 'snow-monkey-forms' ) }
					value={ value }
					onChange={ ( attribute ) => setAttributes( { value: attribute } ) }
				/>
				<TextareaControl
					label={ __( 'options', 'snow-monkey-forms' ) }
					value={ options }
					description={ __( '"value" : "label"&crarr;', 'snow-monkey-forms' ) }
					onChange={ ( attribute ) => setAttributes( { options: attribute } ) }
				/>
			</Fragment>
		);
	},

	save() {
		return null;
	},
} );
