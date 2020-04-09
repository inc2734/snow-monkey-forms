import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import attributes from './attributes.json';
import edit from './edit';
import save from './save';

registerBlockType( 'snow-monkey-forms/snow-monkey-form', {
	title: __( 'Snow Monkey Form', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	attributes,
	edit,
	save,
} );
