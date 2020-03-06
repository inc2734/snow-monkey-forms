export function stringToNumber( value, defaultValue ) {
	if ( '' === value ) {
		return 0;
	}
	if ( null !== value.match( /^[0-9]+$/ ) ) {
		return parseInt( value );
	}
	return defaultValue;
}

export function uniqId() {
	const datetime = new Date().getTime();
	const random = Math.floor( Math.random() * ( 9999 - 1000 ) + 1000 );
	const uniq = datetime + random;
	return uniq.toString( 32 );
}
