import {
	SelectControl,
	TextControl,
	TextareaControl,
} from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

export const NameControl = ( { value, onChange } ) => {
	const style = {};
	if ( '' === value ) {
		style.borderColor = '#d94f4f';
	}

	return (
		<TextControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
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
			__next40pxDefaultSize
			__nextHasNoMarginBottom
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
			__nextHasNoMarginBottom
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
			__next40pxDefaultSize
			__nextHasNoMarginBottom
			label={ __( 'placeholder', 'snow-monkey-forms' ) }
			value={ value }
			onChange={ onChange }
		/>
	);
};

export const MaxLengthControl = ( { value, onChange } ) => {
	return (
		<TextControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
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
			__next40pxDefaultSize
			__nextHasNoMarginBottom
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

export const AutocompleteControl = ( { value, onChange, options = [] } ) => {
	const defaultOptions = [
		'',
		'name',
		'honorific-prefix',
		'given-name',
		'additional-name',
		'family-name',
		'honorific-suffix',
		'nickname',
		'email',
		'username',
		'new-password',
		'current-password',
		'one-time-code',
		'organization-title',
		'organization',
		'street-address',
		'address-line1',
		'address-line2',
		'address-line3',
		'address-level4',
		'address-level3',
		'address-level2',
		'address-level1',
		'country',
		'country-name',
		'postal-code',
		'cc-name',
		'cc-given-name',
		'cc-additional-name',
		'cc-family-name',
		'cc-number',
		'cc-exp',
		'cc-exp-month',
		'cc-exp-year',
		'cc-csc',
		'cc-type',
		'transaction-currency',
		'transaction-amount',
		'language',
		'bday',
		'bday-day',
		'bday-month',
		'bday-year',
		'sex',
		'tel',
		'tel-country-code',
		'tel-national',
		'tel-area-code',
		'tel-local',
		'tel-extension',
		'impp',
		'url',
		'photo',
	];

	const newOptions =
		1 > options.length
			? defaultOptions.map( ( option ) => {
					return {
						value: option,
						label: option,
					};
			  } )
			: options;

	return (
		<SelectControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
			label={ __( 'autocomplete', 'snow-monkey-forms' ) }
			value={ value }
			options={ newOptions }
			onChange={ onChange }
		/>
	);
};

export const IdControl = ( { value, onChange } ) => {
	return (
		<TextControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
			label={ __( 'id', 'snow-monkey-forms' ) }
			value={ value }
			onChange={ onChange }
		/>
	);
};

export const ClassControl = ( { value, onChange } ) => {
	return (
		<TextControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
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
			__nextHasNoMarginBottom
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
