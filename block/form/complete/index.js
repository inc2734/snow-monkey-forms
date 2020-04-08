import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import edit from './edit';
import save from './save';

registerBlockType( 'snow-monkey-forms/form--complete', {
	title: __( 'Complete page', 'snow-monkey-forms' ),
	icon: 'editor-ul',
	category: 'snow-monkey-forms',
	parent: [ false ],
	supports: {
		customClassName: false,
		inserter: false,
		multiple: false,
		reusable: false,
	},

	edit,
	save,
} );
