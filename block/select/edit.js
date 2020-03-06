import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

import {
	NameControl,
	OptionsControl,
	ValueControl,
	IdControl,
	ClassControl,
} from '../components';
import { uniqId } from '../helper';
import withValidations from '../../hoc/with-validations';

const edit = ( { attributes, setAttributes } ) => {
	const { name, value, options, id, controlClass, description } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Attributes', 'snow-monkey-forms' ) }>
					<NameControl
						value={ name || `select-${ uniqId() }` }
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

					<ValueControl
						value={ value }
						onChange={ ( attribute ) =>
							setAttributes( { value: attribute } )
						}
					/>

					<IdControl
						value={ id }
						onChange={ ( attribute ) =>
							setAttributes( { id: attribute } )
						}
					/>

					<ClassControl
						value={ controlClass }
						onChange={ ( attribute ) =>
							setAttributes( { controlClass: attribute } )
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
				block="snow-monkey-forms/control-select"
				attributes={ { ...attributes, disabled: true } }
			/>
		</>
	);
};

export default compose( withValidations )( edit );
