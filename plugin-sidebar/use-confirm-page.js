import { ToggleControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
export default function() {
	const meta = useSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		return getEditedPostAttribute( 'meta' ).use_confirm_page;
	}, [] );

	const { editPost } = useDispatch( 'core/editor' );

	return (
		<ToggleControl
			label={ __( 'Use confirm page', 'snow-monkey-forms' ) }
			checked={ meta }
			onChange={ ( value ) =>
				editPost( {
					meta: { use_confirm_page: value },
				} )
			}
		/>
	);
}
