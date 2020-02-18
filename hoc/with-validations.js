import { merge } from 'lodash';

import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const withValidations = createHigherOrderComponent(
	( WrappedComponent ) =>
		class extends Component {
			render() {
				const { attributes, setAttributes } = this.props;
				const { validations } = attributes;

				if ( typeof validations === 'undefined' ) {
					return <WrappedComponent { ...this.props } />;
				}

				const parsedValidations = JSON.parse( validations );

				return (
					<>
						<InspectorControls>
							<PanelBody
								title={ __(
									'Validation',
									'snow-monkey-forms'
								) }
							>
								<ToggleControl
									label={ __(
										'Required',
										'snow-monkey-forms'
									) }
									checked={ !! parsedValidations.required }
									onChange={ ( value ) => {
										setAttributes( {
											validations: JSON.stringify(
												merge( parsedValidations, {
													required: value,
												} )
											),
										} );
									} }
								/>
							</PanelBody>
						</InspectorControls>

						<WrappedComponent { ...this.props } />
					</>
				);
			}
		},
	'withValidations'
);

export default withValidations;
