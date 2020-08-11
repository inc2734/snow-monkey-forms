import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import edit from './edit';
import save from './save';

const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Form', 'snow-monkey-forms' ),
	icon: 'editor-ul',
	edit,
	save,
};
