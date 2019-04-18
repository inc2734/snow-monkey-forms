'use strict';

const { merge } = lodash;

const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody, ToggleControl } = wp.components;
const { createHigherOrderComponent } = wp.compose;
const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

const withInspectorControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		const { attributes, setAttributes } = props;
		const { validations } = attributes;

		if ( typeof validations === 'undefined' ) {
			return <BlockEdit { ...props } />;
		}

		const parsedValidations = JSON.parse( validations );

		return (
			<Fragment>
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
			</Fragment>
		);
	};
}, 'withInspectorControls' );

addFilter( 'editor.BlockEdit', 'snow-monkey-forms/withInspectorControls/validations', withInspectorControls );
