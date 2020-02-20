import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

import withValidations from '../../hoc/with-validations';

const edit = ( { attributes, setAttributes } ) => {
	const { name, label, value, checked } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Settings', 'snow-monkey-blocks' ) }>
					<TextControl
						label={ __( 'Label', 'snow-monkey-forms' ) }
						value={ label }
						onChange={ ( attribute ) =>
							setAttributes( { label: attribute } )
						}
					/>

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

					<ToggleControl
						label={ __( 'checked', 'snow-monkey-forms' ) }
						checked={ checked }
						onChange={ ( attribute ) =>
							setAttributes( { checked: attribute } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<ServerSideRender
				block="snow-monkey-forms/control-radio"
				attributes={ { ...attributes, disabled: true } }
			/>
		</>
	);
};

export default compose( withValidations )( edit );
