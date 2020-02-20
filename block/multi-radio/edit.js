import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

import withValidations from '../../hoc/with-validations';

const edit = ( { attributes, setAttributes } ) => {
	const { name, value, options } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody>
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
				</PanelBody>
			</InspectorControls>

			<ServerSideRender
				block="snow-monkey-forms/control-multi-radio"
				attributes={ { ...attributes, disabled: true } }
			/>
		</>
	);
};

export default compose( withValidations )( edit );
