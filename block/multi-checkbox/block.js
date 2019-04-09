'use strict';

const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { TextControl, TextareaControl } = wp.components;
const { __ } = wp.i18n;

registerBlockType( 'snow-monkey-forms/multi-checkbox', {
	title: __( 'Multi checkbox', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	supports: {
		customClassName: false,
	},

	edit( { attributes, setAttributes } ) {
		const { name, values, options } = attributes;

		return (
			<Fragment>
				<TextControl
					label={ __( 'name', 'snow-monkey-forms' ) }
					value={ name }
					onChange={ ( attribute ) => setAttributes( { name: attribute } ) }
				/>
				<TextareaControl
					label={ __( 'values', 'snow-monkey-forms' ) }
					value={ values }
					help={ __( 'value\u21B5', 'snow-monkey-forms' ) }
					onChange={ ( attribute ) => setAttributes( { values: attribute } ) }
				/>
				<TextareaControl
					label={ __( 'options', 'snow-monkey-forms' ) }
					value={ options }
					help={ __( '"value" : "label"\u21B5', 'snow-monkey-forms' ) }
					onChange={ ( attribute ) => setAttributes( { options: attribute } ) }
				/>
			</Fragment>
		);
	},

	save() {
		return null;
	},
} );
