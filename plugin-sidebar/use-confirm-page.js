import { ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';

const Control = ( props ) => {
	return (
		<ToggleControl
			label={ __( 'Use confirm page', 'snow-monkey-forms' ) }
			checked={ props.use_confirm_page }
			onChange={ ( value ) => props.setMetaFieldValue( value ) }
		/>
	);
};

const ControlWithData = withSelect( ( select ) => {
	const { getEditedPostAttribute } = select( 'core/editor' );

	const meta = getEditedPostAttribute( 'meta' );

	return {
		use_confirm_page: meta.use_confirm_page,
	};
} )( Control );

export default withDispatch( ( dispatch ) => {
	const { editPost } = dispatch( 'core/editor' );

	return {
		setMetaFieldValue: ( value ) =>
			editPost( { meta: { use_confirm_page: value } } ),
	};
} )( ControlWithData );
