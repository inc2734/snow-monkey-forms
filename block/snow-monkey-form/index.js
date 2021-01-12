import { __ } from '@wordpress/i18n';

import config from '../../src/js/config';
import metadata from './block.json';
import Edit from './edit';
import Save from './save';

const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Snow Monkey Form', 'snow-monkey-forms' ),
	icon: {
		foreground: config.blandColor,
		src: 'editor-ol',
	},
	edit: Edit,
	save: Save,
};
