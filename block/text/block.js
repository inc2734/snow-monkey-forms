'use strict';

const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { TextControl } = wp.components;
const { __ } = wp.i18n;

registerBlockType( 'snow-monkey-forms/text', {
	title: __( 'Text', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	supports: {
		customClassName: false,
	},

	edit( { attributes, setAttributes } ) {
		const { name, value } = attributes;

		return (
			<Fragment>
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
