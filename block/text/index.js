import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import icon from './icon';
import Edit from './edit';
import Save from './save';

const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Text', 'snow-monkey-forms' ),
	icon,
	edit: Edit,
	save: Save,
};
