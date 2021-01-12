import { __ } from '@wordpress/i18n';

import config from '../../src/js/config';
import metadata from './block.json';
import Edit from './edit';
import Save from './save';
import deprecated from './deprecated';

const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Item', 'snow-monkey-forms' ),
	icon: {
		foreground: config.blandColor,
		src: 'text',
	},
	edit: Edit,
	save: Save,
	deprecated,
};
