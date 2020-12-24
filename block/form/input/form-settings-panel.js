import { useEntityProp } from '@wordpress/core-data';
import {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function ( { attributes, onChangeFormStyle } ) {
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

			{ meta.use_confirm_page && (
				<>
					<TextControl
						label={ __(
							'Confirm button label',
							'snow-monkey-forms'
						) }
						value={ meta.confirm_button_label }
						onChange={ ( value ) =>
							setMeta( { confirm_button_label: value } )
						}
					/>

					<TextControl
						label={ __( 'Back button label', 'snow-monkey-forms' ) }
						value={ meta.back_button_label }
						onChange={ ( value ) =>
							setMeta( { back_button_label: value } )
						}
					/>
				</>
			) }

			<TextControl
				label={ __( 'Send button label', 'snow-monkey-forms' ) }
				value={ meta.send_button_label }
				onChange={ ( value ) =>
					setMeta( { send_button_label: value } )
				}
			/>
		</PanelBody>
	);
}
