import { TextControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

export default function() {
	const [ meta, setMeta ] = useEntityProp(
		'postType',
		'snow-monkey-forms',
		'meta'
	);

	return (
		<TextControl
			label={ __( 'To', 'snow-monkey-forms' ) }
			value={ meta.administrator_email_to }
			onChange={ ( value ) =>
				setMeta( { administrator_email_to: value } )
			}
		/>
	);
}
