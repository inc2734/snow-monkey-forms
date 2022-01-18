import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

export const registerBlock = ( block ) => {
	if ( ! block ) {
		return;
	}

	const { metadata, settings, name } = block;
	if ( metadata ) {
		if ( !! metadata.title ) {
			/* eslint @wordpress/i18n-no-variables: 0 */
			metadata.title = __( metadata.title, 'snow-monkey-blocks' );
			settings.title = metadata.title;
		}
		if ( !! metadata.description ) {
			/* eslint @wordpress/i18n-no-variables: 0 */
			metadata.description = __(
				metadata.description,
				'snow-monkey-blocks'
			);
			settings.description = metadata.description;
		}
		if ( !! metadata.keywords ) {
			/* eslint @wordpress/i18n-no-variables: 0 */
			metadata.keywords = __( metadata.keywords, 'snow-monkey-blocks' );
			settings.keywords = metadata.keywords;
		}
	}
	registerBlockType( { name, ...metadata }, settings );
};
