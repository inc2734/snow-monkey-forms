import { PluginSidebar, PluginMoreMenuItem } from '@wordpress/edit-post';
import { PanelBody } from '@wordpress/components';
import { dispatch } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';
import { __ } from '@wordpress/i18n';

import AdministratorEmailToControl from './plugin-sidebar/administrator-email-to';
import AdministratorEmailSubjectControl from './plugin-sidebar/administrator-email-subject';
import AdministratorEmailBodyControl from './plugin-sidebar/administrator-email-body';
import AutoReplyEmailToControl from './plugin-sidebar/auto-reply-email-to';
import AutoReplyEmailSubjectControl from './plugin-sidebar/auto-reply-email-subject';
import AutoReplyEmailBodyControl from './plugin-sidebar/auto-reply-email-body';

registerPlugin(
	'plugin-snow-monkey-form-sidebar',
	{
		render() {
			return (
				<>
					<PluginSidebar
						name="plugin-snow-monkey-form-sidebar"
						icon="feedback"
						title={ __( 'Form settings', 'snow-monkey-forms' ) }
					>
						<PanelBody
							title={ __( 'Administrator email', 'snow-monkey-forms' ) }
							initialOpen={ true }
						>
							<AdministratorEmailToControl />
							<AdministratorEmailSubjectControl />
							<AdministratorEmailBodyControl />
						</PanelBody>

						<PanelBody
							title={ __( 'Auto reply email', 'snow-monkey-forms' ) }
							initialOpen={ true }
						>
							<AutoReplyEmailToControl />
							<AutoReplyEmailSubjectControl />
							<AutoReplyEmailBodyControl />
						</PanelBody>
					</PluginSidebar>

					<PluginMoreMenuItem
						icon="carrot"
						onClick={ () => dispatch( 'core/edit-post' ).togglePinnedPluginItem( 'plugin-snow-monkey-form-sidebar/snow-monkey-form-sidebar' ) }
					>
						{ __( 'Form settings', 'snow-monkey-forms' ) }
					</PluginMoreMenuItem>
				</>
			);
		},
	}
);
