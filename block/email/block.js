'use strict';

const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { TextControl, ServerSideRender } = wp.components;
const { __ } = wp.i18n;

registerBlockType( 'snow-monkey-forms/email', {
	title: __( 'Email', 'snow-monkey-forms' ),
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
				<TextControl
					label={ __( 'value', 'snow-monkey-forms' ) }
					value={ value }
					onChange={ ( attribute ) => setAttributes( { value: attribute } ) }
				/>
			</Fragment>
		) : (
			<ServerSideRender
				block="snow-monkey-forms/email"
				attributes={ attributes }
			/>
		);
	},

	save() {
		return null;
	},
} );
