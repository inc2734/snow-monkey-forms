import { registerBlockType } from '@wordpress/blocks';

import config from '../../../../src/js/config';
import metadata from './block.json';
import edit from './edit';
import save from './save';

registerBlockType( metadata.name, {
	icon: {
		foreground: config.blandColor,
		src: 'editor-ul',
	},
	edit,
	save,
} );
