'use strict';

const { Fragment } = wp.element;
const { PluginSidebar, PluginMoreMenuItem } = wp.editPost;
const { PanelBody } = wp.components;
const { dispatch } = wp.data;
const { registerPlugin } = wp.plugins;
const { __ } = wp.i18n;

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
				<Fragment>
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
				</Fragment>
			);
		},
	}
);
