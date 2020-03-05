import { ToggleControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

export default function() {
	const [ meta, setMeta ] = useEntityProp(
		'postType',
		'snow-monkey-forms',
		'meta'
	);

	return (
		<ToggleControl
			label={ __( 'Use confirm page', 'snow-monkey-forms' ) }
			checked={ meta.use_confirm_page }
			onChange={ ( value ) => setMeta( { use_confirm_page: value } ) }
		/>
	);
}
