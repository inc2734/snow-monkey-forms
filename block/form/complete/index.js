import { __ } from '@wordpress/i18n';

import config from '../../../src/js/config';
import metadata from './block.json';
import Edit from './edit';
import Save from './save';

const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Complete page', 'snow-monkey-forms' ),
	icon: {
		foreground: config.blandColor,
		src: 'editor-ul',
	},
	edit: Edit,
	save: Save,
};
