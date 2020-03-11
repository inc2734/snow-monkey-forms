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
			label={ __( 'Use progress tracker', 'snow-monkey-forms' ) }
			checked={ meta.use_progress_tracker }
			onChange={ ( value ) => setMeta( { use_progress_tracker: value } ) }
		/>
	);
}
