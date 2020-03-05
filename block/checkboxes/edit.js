import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

import { NameControl, OptionsControl, ValuesControl } from '../components';
import withValidations from '../../hoc/with-validations';

const edit = ( { attributes, setAttributes } ) => {
	const { name, options, values, delimiter, description } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Attributes', 'snow-monkey-forms' ) }>
					<NameControl
						value={ name }
						onChange={ ( attribute ) =>
							setAttributes( { name: attribute } )
						}
					/>

					<OptionsControl
						value={ options }
						onChange={ ( attribute ) =>
							setAttributes( { options: attribute } )
						}
					/>

					<ValuesControl
						value={ values }
						onChange={ ( attribute ) =>
							setAttributes( { values: attribute } )
						}
					/>

					<TextControl
						label={ __( 'Delimiter', 'snow-monkey-forms' ) }
						help={ __(
							'Optional. Character that separates each item.',
							'snow-monkey-forms'
						) }
						value={ delimiter }
						onChange={ ( attribute ) =>
							setAttributes( { delimiter: attribute } )
						}
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
				block="snow-monkey-forms/control-checkboxes"
				attributes={ { ...attributes, disabled: true } }
			/>
		</>
	);
};

export default compose( withValidations )( edit );
