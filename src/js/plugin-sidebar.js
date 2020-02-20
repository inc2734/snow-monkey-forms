import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import { __ } from '@wordpress/i18n';

import AdministratorEmailTo from '../../plugin-sidebar/administrator-email-to';
import AdministratorEmailSubject from '../../plugin-sidebar/administrator-email-subject';
import AdministratorEmailBody from '../../plugin-sidebar/administrator-email-body';
import AutoReplyEmailTo from '../../plugin-sidebar/auto-reply-email-to';
import AutoReplyEmailSubject from '../../plugin-sidebar/auto-reply-email-subject';
import AutoReplyEmailBody from '../../plugin-sidebar/auto-reply-email-body';
import UseConfirmPage from '../../plugin-sidebar/use-confirm-page';
import HelpButton from '../../plugin-sidebar/help-button';

registerPlugin( 'plugin-snow-monkey-form-sidebar', {
	render() {
		return (
			<>
				<PluginDocumentSettingPanel
					name="snow-monkey-form/form-settings"
					title={ __( 'Form settings', 'snow-monkey-forms' ) }
					opened={ true }
				>
					<UseConfirmPage />
				</PluginDocumentSettingPanel>

				<PluginDocumentSettingPanel
					name="snow-monkey-form/administrator-email"
					title={ __( 'Administrator email', 'snow-monkey-forms' ) }
					opened={ true }
				>
					<AdministratorEmailTo />
					<AdministratorEmailSubject />
					<AdministratorEmailBody />
					<HelpButton />
				</PluginDocumentSettingPanel>

				<PluginDocumentSettingPanel
					name="snow-monkey-form/auto-reply-email"
					title={ __( 'Auto reply email', 'snow-monkey-forms' ) }
					opened={ false }
				>
					<AutoReplyEmailTo />
					<AutoReplyEmailSubject />
					<AutoReplyEmailBody />
					<HelpButton />
				</PluginDocumentSettingPanel>
			</>
		);
	},
} );
