import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import attributes from './attributes.json';
import edit from './edit';
import save from './save';

registerBlockType( 'snow-monkey-forms/form--input', {
	title: __( 'Form', 'snow-monkey-forms' ),
	icon: 'editor-ul',
	category: 'snow-monkey-forms',
	parent: [ false ],
	supports: {
		customClassName: true,
		className: false,
		inserter: false,
		multiple: false,
		reusable: false,
	},

	attributes,
	edit,
	save,
} );
