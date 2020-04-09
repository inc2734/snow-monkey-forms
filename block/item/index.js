import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import attributes from './attributes.json';
import edit from './edit';
import save from './save';
import deprecated from './deprecated';

registerBlockType( 'snow-monkey-forms/item', {
	title: __( 'Item', 'snow-monkey-forms' ),
	icon: 'text',
	category: 'snow-monkey-forms',
	parent: [ 'snow-monkey-forms/form--input' ],
	attributes,
	edit,
	save,
	deprecated,
} );
