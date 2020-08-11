import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import icon from './icon';
import edit from './edit';
import save from './save';

const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Radio buttons', 'snow-monkey-forms' ),
	icon,
	edit,
	save,
};
