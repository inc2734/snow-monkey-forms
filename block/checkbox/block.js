'use strict';

const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { TextControl, ToggleControl } = wp.components;
const { __ } = wp.i18n;

registerBlockType( 'snow-monkey-forms/checkbox', {
	title: __( 'Checkbox', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	supports: {
		customClassName: false,
	},

	edit( { attributes, setAttributes } ) {
		const { name, label, value, checked } = attributes;

		return (
			<Fragment>
				<TextControl
					label={ __( 'Label', 'snow-monkey-forms' ) }
					value={ label }
					onChange={ ( attribute ) => setAttributes( { label: attribute } ) }
				/>
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
				<ToggleControl
					label={ __( 'checked', 'snow-monkey-forms' ) }
					checked={ checked }
					onChange={ ( attribute ) => setAttributes( { checked: attribute } ) }
				/>
			</Fragment>
		);
	},

	save() {
		return null;
	},
} );
