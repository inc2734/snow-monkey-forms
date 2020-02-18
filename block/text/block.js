import { registerBlockType } from '@wordpress/blocks';
import { TextControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

registerBlockType( 'snow-monkey-forms/text', {
	title: __( 'Text', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	supports: {
		customClassName: false,
	},

	edit( { attributes, setAttributes, isSelected } ) {
		const { name, value } = attributes;

		return !! isSelected ? (
			<>
				<TextControl
					label={ __( 'name', 'snow-monkey-forms' ) }
					value={ name }
					onChange={ ( attribute ) =>
						setAttributes( { name: attribute } )
					}
				/>
				<TextControl
					label={ __( 'value', 'snow-monkey-forms' ) }
					value={ value }
					onChange={ ( attribute ) =>
						setAttributes( { value: attribute } )
					}
				/>
			</>
		) : (
			<ServerSideRender
				block="snow-monkey-forms/text"
				attributes={ attributes }
			/>
		);
	},

	save() {
		return null;
	},
} );
