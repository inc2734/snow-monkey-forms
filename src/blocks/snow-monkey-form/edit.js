import { SelectControl, Placeholder } from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export default function ( { attributes, setAttributes, isSelected } ) {
	const { formId } = attributes;

	const forms = useSelect( ( select ) => {
		return (
			select( 'core' ).getEntityRecords(
				'postType',
				'snow-monkey-forms',
				{
					per_page: -1,
				}
			) || []
		);
	} );

	const options = useMemo( () => {
		return forms.map( ( form ) => {
			return {
				value: form.id,
				label: `${ form.id }: ${ form.title.rendered }`,
			};
		} );
	}, [ forms ] );

	return (
		<>
			{ isSelected ? (
				<Placeholder
					icon="editor-ul"
					label={ __( 'Select a form', 'snow-monkey-forms' ) }
				>
					<SelectControl
						value={ formId }
						options={ [
							{
								value: 0,
								label: __(
									'Select a form',
									'snow-monkey-forms'
								),
							},
							...options,
						] }
						onChange={ ( value ) =>
							setAttributes( { formId: parseInt( value ) } )
						}
					/>
				</Placeholder>
			) : (
				<ServerSideRender
					block="snow-monkey-forms/snow-monkey-form"
					attributes={ attributes }
				/>
			) }
		</>
	);
}
