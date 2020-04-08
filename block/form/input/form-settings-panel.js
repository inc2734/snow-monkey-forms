import { useEntityProp } from '@wordpress/core-data';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function( { attributes, onChangeFormStyle } ) {
	const { formStyle } = attributes;

	const [ meta, setMeta ] = useEntityProp(
		'postType',
		'snow-monkey-forms',
		'meta'
	);

	return (
		<PanelBody title={ __( 'Form settings', 'snow-monkey-forms' ) }>
			<ToggleControl
				label={ __( 'Use confirm page', 'snow-monkey-forms' ) }
				checked={ meta.use_confirm_page }
				onChange={ ( value ) => setMeta( { use_confirm_page: value } ) }
			/>

			<ToggleControl
				label={ __( 'Use progress tracker', 'snow-monkey-forms' ) }
				checked={ meta.use_progress_tracker }
				onChange={ ( value ) =>
					setMeta( { use_progress_tracker: value } )
				}
			/>

			<SelectControl
				label={ __( 'Form style', 'snow-monkey-forms' ) }
				value={ formStyle }
				options={ [
					{
						value: '',
						label: __( 'Default', 'snow-monkey-forms' ),
					},
					{
						value: 'smf-form--simple-table',
						label: __( 'Simple table', 'snow-monkey-forms' ),
					},
					{
						value: 'smf-form--letter',
						label: __( 'Letter', 'snow-monkey-forms' ),
					},
					{
						value: 'smf-form--business',
						label: __( 'Business', 'snow-monkey-forms' ),
					},
				] }
				onChange={ onChangeFormStyle }
			/>
		</PanelBody>
	);
}
