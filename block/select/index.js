import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import attributes from './attributes';
import icon from './icon';
import edit from './edit';
import save from './save';

registerBlockType( 'snow-monkey-forms/control-select', {
	title: __( 'Select', 'snow-monkey-forms' ),
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
