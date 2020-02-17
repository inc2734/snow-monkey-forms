import { registerBlockType } from '@wordpress/blocks';
import { Fragment } from '@wordpress/element';
import { TextControl, TextareaControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

registerBlockType( 'snow-monkey-forms/multi-checkbox', {
	title: __( 'Multi checkbox', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	supports: {
		customClassName: false,
	},

	edit( { attributes, setAttributes, isSelected } ) {
		const { name, values, options } = attributes;

		return !! isSelected ? (
			<Fragment>
				<TextControl
					label={ __( 'name', 'snow-monkey-forms' ) }
					value={ name }
					onChange={ ( attribute ) => setAttributes( { name: attribute } ) }
				/>
				<TextareaControl
					label={ __( 'options', 'snow-monkey-forms' ) }
					value={ options }
					help={ __( '"value" : "label"\u21B5', 'snow-monkey-forms' ) }
					onChange={ ( attribute ) => setAttributes( { options: attribute } ) }
				/>
				<TextareaControl
					label={ __( 'values', 'snow-monkey-forms' ) }
					value={ values }
					help={ __( 'value\u21B5', 'snow-monkey-forms' ) }
					onChange={ ( attribute ) => setAttributes( { values: attribute } ) }
				/>
			</Fragment>
		) : (
			<ServerSideRender
				block="snow-monkey-forms/multi-checkbox"
				attributes={ attributes }
			/>
		);
	},

	save() {
		return null;
	},
} );
