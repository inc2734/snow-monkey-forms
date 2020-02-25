import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

import { stringToNumber } from '../helper';
import withValidations from '../../hoc/with-validations';

const edit = ( { attributes, setAttributes } ) => {
	const {
		name,
		value,
		placeholder,
		maxlength,
		size,
		description,
	} = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Attributes', 'snow-monkey-forms' ) }>
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

					<TextControl
						label={ __( 'placeholder', 'snow-monkey-forms' ) }
						value={ placeholder }
						onChange={ ( attribute ) =>
							setAttributes( { placeholder: attribute } )
						}
					/>

					<TextControl
						label={ __( 'maxlength', 'snow-monkey-forms' ) }
						value={ maxlength }
						help={ __(
							'If 0, not restricted.',
							'snow-monkey-forms'
						) }
						onChange={ ( attribute ) => {
							setAttributes( {
								maxlength: stringToNumber(
									attribute,
									maxlength
								),
							} );
						} }
					/>

					<TextControl
						label={ __( 'size', 'snow-monkey-forms' ) }
						value={ size }
						help={ __(
							'If 0, not restricted.',
							'snow-monkey-forms'
						) }
						onChange={ ( attribute ) => {
							setAttributes( {
								size: stringToNumber( attribute, size ),
							} );
						} }
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Block settings', 'snow-monkey-forms' ) }
				>
					<TextControl
						label={ __( 'Description', 'snow-monkey-forms' ) }
						value={ description }
						onChange={ ( attribute ) =>
							setAttributes( { description: attribute } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<ServerSideRender
				block="snow-monkey-forms/control-email"
				attributes={ { ...attributes, disabled: true } }
			/>
		</>
	);
};

export default compose( withValidations )( edit );
