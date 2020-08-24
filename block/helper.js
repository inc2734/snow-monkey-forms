import { uniqBy } from 'lodash';

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
	const preOptionsArray = text.replace( /\r?\n/g, '\n' ).split( '\n' );

	const optionsMapArray = uniqBy(
		preOptionsArray.map( ( element ) => {
			const optionMap = ( () => {
				try {
					return JSON.parse( `{ ${ element } }` );
				} catch ( error ) {
					return { [ element ]: element };
				}
			} )();

			return {
				value: Object.keys( optionMap )[ 0 ],
				label: Object.values( optionMap )[ 0 ],
			};
		} ),
		'value'
	);

	return optionsMapArray.map( ( element ) => {
		const o = {};
		o[ element.value ] = element.label;
		return o;
	} );
}

export function valuesToJsonArray( text ) {
	const preValuesArray = optionsToJsonArray( text );

	return preValuesArray.map( ( element ) => {
		return Object.keys( element )[ 0 ];
	} );
}
