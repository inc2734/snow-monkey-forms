import { merge } from 'lodash';

import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

const withInspectorControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		const { attributes, setAttributes } = props;
		const { validations } = attributes;

		if ( typeof validations === 'undefined' ) {
			return <BlockEdit { ...props } />;
		}

		const parsedValidations = JSON.parse( validations );

		return (
			<>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody title={ __( 'Validation', 'snow-monkey-forms' ) }>
						<ToggleControl
							label={ __( 'Required', 'snow-monkey-forms' ) }
							checked={ !! parsedValidations.required }
							onChange={ ( value ) => {
								setAttributes( { validations: JSON.stringify( merge( parsedValidations, { required: value } ) ) } );
							} }
						/>
					</PanelBody>
				</InspectorControls>
			</>
		);
	};
}, 'withInspectorControls' );

addFilter( 'editor.BlockEdit', 'snow-monkey-forms/withInspectorControls/validations', withInspectorControls );
