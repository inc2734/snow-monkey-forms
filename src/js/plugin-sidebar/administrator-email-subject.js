'use strict';

const { TextControl } = wp.components;
const { __ } = wp.i18n;
const { withSelect, withDispatch } = wp.data;

const Control = ( props ) => {
	return (
		<TextControl
			label={ __( 'Subject', 'snow-monkey-forms' ) }
			value={ props.administrator_email_subject }
			onChange={ ( value ) => props.setMetaFieldValue( value ) }
		/>
	);
};

const ControlWithData = withSelect( ( select ) => {
	const { getEditedPostAttribute } = select( 'core/editor' );

	return {
		administrator_email_subject: getEditedPostAttribute( 'meta' )[ 'administrator_email_subject' ],
	};
} )( Control );

export default withDispatch( ( dispatch ) => {
	const { editPost } = dispatch( 'core/editor' );

	return {
		setMetaFieldValue: ( value ) => editPost( { meta: { administrator_email_subject: value } } ),
	};
} )( ControlWithData );
