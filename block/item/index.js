import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import edit from './edit';
import save from './save';
import deprecated from './deprecated';

const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Item', 'snow-monkey-forms' ),
	icon: 'text',
	edit,
	save,
	deprecated,
};
