import { TextControl, TextareaControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

export const NameControl = ( { value, onChange } ) => {
	const style = {};
	if ( '' === value ) {
		style.borderColor = '#d94f4f';
	}

	return (
		<TextControl
			label={ __( 'name', 'snow-monkey-forms' ) }
			help={ __(
				'Required. Input a unique machine-readable name.',
				'snow-monkey-forms'
			) }
			value={ value }
			onChange={ onChange }
			required
			style={ style }
		/>
	);
};

export const ValueControl = ( { value, onChange, multiple = false } ) => {
	const Control = ! multiple ? TextControl : TextareaControl;

	return (
		<Control
			label={ __( 'value', 'snow-monkey-forms' ) }
			help={ __( 'Optional. Initial value.', 'snow-monkey-forms' ) }
			value={ value }
			onChange={ onChange }
		/>
	);
};

export const ValuesControl = ( { value, onChange } ) => {
	return (
		<TextareaControl
			label={ __( 'value', 'snow-monkey-forms' ) }
			help={ sprintf(
				// translators: %1$s: line-break-char
				__(
					'Optional. Initial value. Enter in the following format: value%1$s',
					'snow-monkey-forms'
				),
				'\u21B5'
			) }
			value={ value }
			onChange={ onChange }
		/>
	);
};

export const PlaceholderControl = ( { value, onChange } ) => {
	return (
		<TextControl
			label={ __( 'placeholder', 'snow-monkey-forms' ) }
			value={ value }
			onChange={ onChange }
		/>
	);
};

export const MaxLengthControl = ( { value, onChange } ) => {
	return (
		<TextControl
			label={ __( 'maxlength', 'snow-monkey-forms' ) }
			help={ __(
				'Optional. Maximum number of characters. If 0, not restricted.',
				'snow-monkey-forms'
			) }
			value={ value }
			onChange={ onChange }
		/>
	);
};

export const SizeControl = ( { value, onChange } ) => {
	return (
		<TextControl
			label={ __( 'size', 'snow-monkey-forms' ) }
			help={ __(
				'Optional. The width of this item. If 0, not restricted.',
				'snow-monkey-forms'
			) }
			value={ value }
			onChange={ onChange }
		/>
	);
};

export const IdControl = ( { value, onChange } ) => {
	return (
		<TextControl
			label={ __( 'id', 'snow-monkey-forms' ) }
			value={ value }
			onChange={ onChange }
		/>
	);
};

export const ClassControl = ( { value, onChange } ) => {
	return (
		<TextControl
			label={ __( 'class', 'snow-monkey-forms' ) }
			help={ __(
				'Separate multiple classes with spaces.',
				'snow-monkey-forms'
			) }
			value={ value }
			onChange={ onChange }
		/>
	);
};

export const OptionsControl = ( { value, onChange } ) => {
	const style = {};
	if ( '' === value ) {
		style.borderColor = '#d94f4f';
	}

	return (
		<TextareaControl
			label={ __( 'options', 'snow-monkey-forms' ) }
			value={ value }
			help={ sprintf(
				// translators: %1$s: line-break-char
				__(
					'Required. Enter in the following format: "value" : "label"%1$s or value%1$s',
					'snow-monkey-forms'
				),
				'\u21B5'
			) }
			onChange={ onChange }
			required
			style={ style }
		/>
	);
};
