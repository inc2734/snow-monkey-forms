'use strict';

const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { TextControl, TextareaControl, ServerSideRender } = wp.components;
const { __ } = wp.i18n;

registerBlockType( 'snow-monkey-forms/textarea', {
	title: __( 'Textarea', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	parent: [ 'snow-monkey-forms/form--input' ],
	supports: {
		customClassName: false,
	},

	edit( { attributes, setAttributes, isSelected } ) {
		const { name, value } = attributes;

		return !! isSelected ? (
			<Fragment>
				<TextControl
					label={ __( 'name', 'snow-monkey-forms' ) }
					value={ name }
					onChange={ ( attribute ) => setAttributes( { name: attribute } ) }
				/>
				<TextareaControl
					label={ __( 'value', 'snow-monkey-forms' ) }
					value={ value }
					onChange={ ( attribute ) => setAttributes( { value: attribute } ) }
				/>
			</Fragment>
		) : (
			<ServerSideRender
				block="snow-monkey-forms/textarea"
				attributes={ attributes }
			/>
		);
	},

	save() {
		return null;
	},
} );
