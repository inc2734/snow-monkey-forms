import { registerBlockType } from '@wordpress/blocks';
import { Fragment } from '@wordpress/element';
import { TextControl, TextareaControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

registerBlockType( 'snow-monkey-forms/select', {
	title: __( 'Select', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	supports: {
		customClassName: false,
	},

	edit( { attributes, setAttributes, isSelected } ) {
		const { name, value, options } = attributes;

		return !! isSelected ? (
			<Fragment>
				<TextControl
					label={ __( 'name', 'snow-monkey-forms' ) }
					value={ name }
					onChange={ ( attribute ) =>
						setAttributes( { name: attribute } )
					}
				/>
				<TextareaControl
					label={ __( 'options', 'snow-monkey-forms' ) }
					value={ options }
					help={ __(
						'"value" : "label"\u21B5',
						'snow-monkey-forms'
					) }
					onChange={ ( attribute ) =>
						setAttributes( { options: attribute } )
					}
				/>
				<TextControl
					label={ __( 'value', 'snow-monkey-forms' ) }
					value={ value }
					onChange={ ( attribute ) =>
						setAttributes( { value: attribute } )
					}
				/>
			</Fragment>
		) : (
			<ServerSideRender
				block="snow-monkey-forms/select"
				attributes={ attributes }
			/>
		);
	},

	save() {
		return null;
	},
} );
