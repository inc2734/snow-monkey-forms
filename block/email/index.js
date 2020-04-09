import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import attributes from './attributes.json';
import icon from './icon';
import edit from './edit';
import save from './save';

registerBlockType( 'snow-monkey-forms/control-email', {
	title: __( 'email', 'snow-monkey-forms' ),
	icon,
	category: 'snow-monkey-forms',
	parent: [ 'snow-monkey-forms/item' ],
	supports: {
		customClassName: false,
	},
	attributes,
	edit,
	save,
} );
