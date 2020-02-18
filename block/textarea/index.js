import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import attributes from './attributes';
import edit from './edit';
import save from './save';

registerBlockType( 'snow-monkey-forms/textarea', {
	title: __( 'Textarea', 'snow-monkey-forms' ),
	icon: 'editor-ol',
	category: 'snow-monkey-forms',
	attributes,
	edit,
	save,
} );