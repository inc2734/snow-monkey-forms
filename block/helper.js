export function stringToNumber( value, defaultValue ) {
	if ( '' === value ) {
		return 0;
	}
	if ( null !== value.match( /^[0-9]+$/ ) ) {
		return parseInt( value );
	}
	return defaultValue;
}
