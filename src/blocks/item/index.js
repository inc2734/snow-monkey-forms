import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';
import edit from './edit';
import save from './save';
import deprecated from './deprecated';
import config from '../../../src/js/config';

import './style.scss';
import './index.scss';

registerBlockType( metadata.name, {
	icon: {
		foreground: config.blandColor,
		src: 'text',
	},
	edit,
	save,
	deprecated,
} );
