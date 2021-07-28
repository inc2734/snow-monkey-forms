import { registerBlockType } from '@wordpress/blocks';

import { __ } from '@wordpress/i18n';

const registerBlock = ( block ) => {
	if ( ! block ) {
		return;
	}

	const { metadata, settings, name } = block;
	if ( metadata ) {
		if ( !! metadata.title ) {
			/* eslint @wordpress/i18n-no-variables: 0 */
			metadata.title = __( metadata.title, 'snow-monkey-forms' );
			settings.title = metadata.title;
		}
		if ( !! metadata.description ) {
			/* eslint @wordpress/i18n-no-variables: 0 */
			metadata.description = __(
				metadata.description,
				'snow-monkey-forms'
			);
			settings.description = metadata.description;
		}
		if ( !! metadata.keywords ) {
			/* eslint @wordpress/i18n-no-variables: 0 */
			metadata.keywords = __( metadata.keywords, 'snow-monkey-forms' );
			settings.keywords = metadata.keywords;
		}
	}
	registerBlockType( { name, ...metadata }, settings );
};

import * as email from '../../block/email';
import * as complete from '../../block/form/complete';
import * as input from '../../block/form/input';
import * as item from '../../block/item';
import * as checkboxes from '../../block/checkboxes';
import * as radioButtons from '../../block/radio-buttons';
import * as select from '../../block/select';
import * as text from '../../block/text';
import * as textarea from '../../block/textarea';
import * as url from '../../block/url';
import * as tel from '../../block/tel';
import * as file from '../../block/file';
import * as snowMonkeyForm from '../../block/snow-monkey-form';

[
	email,
	complete,
	input,
	item,
	checkboxes,
	radioButtons,
	select,
	text,
	textarea,
	url,
	tel,
	file,
	snowMonkeyForm,
].forEach( ( block ) => registerBlock( block ) );
