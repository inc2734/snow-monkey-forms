import { TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';

const Control = ( props ) => {
	return (
		<TextareaControl
			label={ __( 'Body', 'snow-monkey-forms' ) }
			value={ props.auto_reply_email_body }
			onChange={ ( value ) => props.setMetaFieldValue( value ) }
		/>
	);
};

const ControlWithData = withSelect( ( select ) => {
	const { getEditedPostAttribute } = select( 'core/editor' );

	return {
		auto_reply_email_body: getEditedPostAttribute( 'meta' )[ 'auto_reply_email_body' ],
	};
} )( Control );

export default withDispatch( ( dispatch ) => {
	const { editPost } = dispatch( 'core/editor' );

	return {
		setMetaFieldValue: ( value ) => editPost( { meta: { auto_reply_email_body: value } } ),
	};
} )( ControlWithData );
