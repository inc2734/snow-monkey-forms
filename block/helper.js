import { uniq } from 'lodash';

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
	const baseUniqId = datetime + random;
	return baseUniqId.toString( 32 );
}

export function optionsToJsonArray( text ) {
	const optionsArray = text.replace( /\r?\n/g, '\n' ).split( '\n' );

	return optionsArray.map( ( option ) => {
		try {
			return JSON.parse( `{ ${ option } }` );
		} catch ( error ) {
			return { [ option ]: option };
		}
	} );
}

export function valuesToJsonArray( text ) {
	return uniq( text.replace( /\r?\n/g, '\n' ).split( '\n' ) );
}
