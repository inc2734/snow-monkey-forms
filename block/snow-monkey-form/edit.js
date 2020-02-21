import { SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export default function( { attributes, setAttributes, isSelected } ) {
	const { formId } = attributes;

	return (
		<>
			{ isSelected ? (
				<SelectControl
					label={ __( 'Select a form', 'snow-monkey-forms' ) }
					value={ formId }
					options={ [
						{
							value: 0,
							label: __( 'Select this', 'snow-monkey-form' ),
						},
						{
							value: 14212,
							label: 14212,
						},
						{
							value: 14210,
							label: 14210,
						},
					] }
					onChange={ ( value ) =>
						setAttributes( { formId: parseInt( value ) } )
					}
				/>
			) : (
				<ServerSideRender
					block="snow-monkey-forms/snow-monkey-form"
					attributes={ attributes }
				/>
			) }
		</>
	);
}
