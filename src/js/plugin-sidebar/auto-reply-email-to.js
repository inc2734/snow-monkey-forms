import { TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';

const Control = ( props ) => {
	return (
		<TextControl
			label={ __( 'To', 'snow-monkey-forms' ) }
			value={ props.auto_reply_email_to }
			onChange={ ( value ) => props.setMetaFieldValue( value ) }
		/>
	);
};

const ControlWithData = withSelect( ( select ) => {
	const { getEditedPostAttribute } = select( 'core/editor' );

	const meta = getEditedPostAttribute( 'meta' );

	return {
		auto_reply_email_to: meta.auto_reply_email_to,
	};
} )( Control );

export default withDispatch( ( dispatch ) => {
	const { editPost } = dispatch( 'core/editor' );

	return {
		setMetaFieldValue: ( value ) =>
			editPost( { meta: { auto_reply_email_to: value } } ),
	};
} )( ControlWithData );
