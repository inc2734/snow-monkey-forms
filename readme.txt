=== Snow Monkey Forms ===
Contributors: inc2734, mimitips
Donate link: https://www.amazon.co.jp/registry/wishlist/39ANKRNSTNW40
Tags: gutenberg, block, blocks, editor, gutenberg blocks, page builder, form, forms, mail, email, contact
Requires at least: 5.4
Tested up to: 5.4
Requires PHP: 5.6
Stable tag: 0.5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The Snow Monkey Forms is a mail form plugin for the block editor.

== Description ==

The Snow Monkey Forms is a mail form plugin for the block editor.

GitHub: https://github.com/inc2734/snow-monkey-forms/

== Form fields ==

* Text
* email
* Tel
* URL
* File
* Textarea
* Select
* Radio buttons
* Checkboxes

== Installation ==

This plugin can be installed directly from your site.

1. Log in and navigate to Plugins → Add New.
1. Type “Snow Monkey Forms into the Search and hit Enter.
1. Locate the Snow Monkey Editor plugin in the list of search results and click Install Now.
1. Once installed, click the Activate link.
1. Snow Monkey Forms → Add New.
1. Add "Item" block into the input page field.
1. Add any form fields into a "Item" block.
1. Save the form settings.
1. Add "Snow Monkey Form" block into a post and page.

== Frequently Asked Questions ==

= Can the Snow Monkey Forms be used with any theme? =

Yes! You can use the Snow Monkey Forms with any theme, but we recommend using our <a href="https://snow-monkey.2inc.org/" target="_blank">Snow Monkey</a> theme for the best presentation.

== Changelog ==

= 0.5.4 =
* Optimized the display process of error message when sending fails.

= 0.5.3 =
* Fixed a bug that form cannot be submitted in CGI version PHP environment.

= 0.5.2 =
* Fixed a bug that email field cannot be inserted.

= 0.5.1 =
* Update icons. These icons reated by <a href="https://profiles.wordpress.org/mimitips/">mimitips</a>

= 0.5.0 =
* Update each blocks icons.
* Add "Display label column" setting to item block.

= 0.4.0 =
* Add item description setting.
* Add from/sender settings to administrator email settings.
* Add action hook `snow_monkey_forms/auto_reply_mailer/after_send`.
* Add action hook `snow_monkey_forms/administrator_mailer/after_send`.
* Rename filter hook `snow_monkey_forms_saved_file_survival_time` to `snow_monkey_forms/saved_files/survival_time`.
* Move email settings document panel to input page settings.
* Fixed a bug where the input page settings panel did not open when the inspector was closed.
* Fixed a bug that child blocks are displayed in the inserter of the complete page settings.

= 0.3.1 =
* Fix activate process error
* Fix uninstall process error

= 0.3.0 =
* CSS updates.
* Add uninstall process.

= 0.2.0 =
* Some updates.

= 0.1.0 =
* Initial release.

== Upgrade Notice ==

Nothing in particular.
