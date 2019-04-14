'use strict';

const { TextControl } = wp.components;
const { __ } = wp.i18n;
const { withSelect, withDispatch } = wp.data;

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

	return {
		auto_reply_email_to: getEditedPostAttribute( 'meta' )[ 'auto_reply_email_to' ],
	};
} )( Control );

export default withDispatch( ( dispatch ) => {
	const { editPost } = dispatch( 'core/editor' );

	return {
		setMetaFieldValue: ( value ) => editPost( { meta: { auto_reply_email_to: value } } ),
	};
} )( ControlWithData );
