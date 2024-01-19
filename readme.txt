=== Snow Monkey Forms ===
Contributors: inc2734, mimitips, imawc
Donate link: https://www.amazon.co.jp/registry/wishlist/39ANKRNSTNW40
Tags: gutenberg, block, blocks, editor, gutenberg blocks, page builder, form, forms, mail, email, contact
Stable tag: 6.2.0
Requires at least: 6.3
Tested up to: 6.4
Requires PHP: 7.4
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

= 6.2.0 =
* Added a release button to the file field.
* Added a method to the Responser class to retrieve form metadata and sender data. `$responser->get_meta()`

= 6.1.0 =
* Added legend setting to checkboxes and radio buttons.
* Fixed a bug that the layout of the progress bar and some of the settings screens could be corrupted depending on the theme.

= 6.0.5 =
* Fixed changes in 6.0.4 that affected the layout of radio buttons and checkboxes.

= 6.0.4 =
* Fixed a bug that when typing in a text field, a slight margin is sometimes added to the bottom of the fieldon iOS.
* Fixed a bug that the text color turns blue when selecting an item in the selectbox on iOS.

= 6.0.2 =
* Fix typo. `smf-radio-cuttons-control` to `smf-radio-button-control`.
* Support for servers without `finfo`.

= 6.0.1 =
* Fixed a bug that `smf.submit` did not fire.

= 6.0.0 =
* Update `apiVersion` of block.json is 2 to 3.
* Set `defer` attribute to `wp_enqueue_script()`.

= 5.2.0 =
* Added Reply-To setting function.
* Fixed a bug that could cause a Fatal Error when cookies are not saved.

= 5.1.2 =
* Fixed a directory traversal vulnerability (Fix omitted in v5.0.7 and v5.1.1). We strongly encourage you to update to it immediately.

= 5.1.1 =
* Fixed a directory traversal vulnerability (Fix omitted in v5.0.7). We strongly encourage you to update to it immediately.

= 5.1.0 =
* Add new setting for each blocks: "Description is also displayed on the confirmation screen"

= 5.0.7 =
* Fixed a directory traversal vulnerability. We strongly encourage you to update to it immediately.

= 5.0.6 =
* Fixed a bug that attached files were not given file extensions.

= 5.0.5 =
* Change `wp.components.IconButton` to `wp.components.Button`.

= 5.0.4 =
* Fixed a bug that prevented emails to the administrator from being sent.

= 5.0.3 =
* Fixed a bug that content for the completion screen was not output.

= 5.0.2 =
* Fixed a bug that prevented form selection after inserting a Snow Monkey Form block.

= 5.0.1 =
* Fixed a bug that Tel and url blocks were not supported.

= 5.0.0 =
* Requires WordPress 6.1 or later.
* Add filter hook `snow_monkey_forms/auto_reply_mailer/args` for changing auto reply mail settings.
* Fixed a bug where block names were not translated.
* Changed dynamic block template loading method.
* Change the loading method (+ handle) of the blocks assets.
* Updated translation file loading method.

= 4.0.3 =
* Fixed a bug that the focus ring was not displayed in the file block.

= 4.0.2 =
* Changed so that each form control can be placed as a descendant of an item, not just as a child of the item block.

= 4.0.1 =
* Fixed a bug where "Confirm" was displayed on the progress tracker even when the confirm screen was not used.

= 4.0.0 =
* Requires WordPress 6.0 or later.
* `snow_monkey_forms/administrator_mailer/headers` Add `responser` and `setting` as filter hook arguments.

= 3.1.2 =
* Fixed a bug that intended values were not sent when hooked to `snow_monkey_forms/control/attributes`.

= 3.1.1 =
* Update sass-basis@17.0.0

= 3.1.0 =
* Add filter hook `snow_monkey_forms/administrator_mailer/headers`.
* Add filter hook `snow_monkey_forms/auto_reply_mailer/headers`.
* Add filter hook `snow_monkey_forms/mailer/headers`.
* Add autocomplete setting to text, textarea, select, url and tel.

= 3.0.1 =
* Fixed a bug in select boxes, radio buttons, and checkboxes that if there is a space before or after an option, the item will not be selected/checked even if it is selected and sent.
* Fixed a bug that sometimes caused the progress tracker numbers to shift.

= 3.0.0 =
* Requires WordPress 5.9 or later.
* End of support for ie11.
* Changes due to changes in WordPress 5.9.
* Add filter hook `snow_monkey_forms/administrator_mailer/args` for changing administrator mail settings.
* Changed the file upload check to be the same as the WordPress file upload check when sending files in "File".

= 2.1.0 =
* Add filter hook `snow_monkey_forms/validator/error_message`.

= 2.0.0 =
* Compatible with WordPress 5.8. 5.7 is not supported.
* Add maxlength to textarea block.
* Add label setting for item block.
* Fix bug that rows of textarea block is not updated.
* Fix bug that class of form block is not updated.

= 1.5.3 =
* Update sass-basis

= 1.5.2 =
* Fixed a bug that the screen was not displayed at the correct position when the screen transitioned on iOS.
* Add message for saved on reCAPTCHA settings page.

= 1.5.1 =
* Fixed a bug that the screen was not displayed at the correct position when the screen transitioned on iOS.

= 1.5.0 =
* Changed `snow_monkey_forms/control/attributes` to allow setting the initial values for select and textarea.

= 1.4.1 =
* Fixed bug that [object HTMLDivElement] was displayed during screen transition and forms were not displayed.

= 1.4.0 =
* Add `Snow_Monkey\Plugin\Forms\App\Model\Setting` as the 3rd argument to `snow_monkey_forms/complete/message`.
* Add `Snow_Monkey\Plugin\Forms\App\Model\Setting` as the 3rd argument to `snow_monkey_forms/system_error/message`.
* Fixed a bug in which select boxes, radio buttons, and check boxes sometimes malfunctioned when the choices were numeric.

= 1.3.1 =
* Fixed a bug in which sending failed if reCAPTCHA was not set.

= 1.3.0 =
* Add reCAPTCHA v3 settings.
* Add filter hook `snow_monkey_forms/spam/validate`.
* Add action hook `snow_monkey_forms/form/append`.
* Fixed a bug that the function to move to the top of the form when a button is pressed the screen transitions did not work in safari.
* Fixed a bug where the label setting of the Send button was not reflected.
* Fixed a bug that required checks did not work correctly when value was empty in the select box.

= 1.2.0 =
* Add filter hook `snow_monkey_forms/control/attributes`.
* Change block icons color.
* Fix bug that placeholder of textarea is not refrected.

= 1.1.0 =
* Add confirm/back/send button label settings.
* Change focus point.
* Fixed a bug that prevented radio buttons from being displayed vertically.

= 1.0.0 =
* WordPress 5.6 compatibility.

= 0.10.2 =
* Fixed a bug that prevented sending with IE and Safari.

= 0.10.1 =
* Add filter hook `snow_monkey_forms/complete/message`.
* Add filter hook `snow_monkey_forms/system_error/message`.
* Fixed a bug where the button loading icon would not disappear when form validation occurred.

= 0.9.2 =
* Fixed a bug where checkboxes, radio buttons, and select boxes would sometimes not render correctly if there were duplicate items.

= 0.9.1 =
* Fixed a bug that the Add Block button does not appear on the selected item block.
* Fixed a bug that global inserter in the item block was not grouped.

= 0.9.0 =
* Requires WordPress 5.5
* Remove jQuery

= 0.8.0 =
* Add filter hook `snow_monkey_forms/custom_mail_tag`.
* Add filter hook `snow_monkey_forms/administrator_mailer/skip`.
* Add filter hook `snow_monkey_forms/administrator_mailer/is_sended`.
* Add filter hook `snow_monkey_forms/auto_reply_mailer/skip`.
* Add filter hook `snow_monkey_forms/auto_reply_mailer/is_sended`.

= 0.7.1 =
* Fixed a bug that PHP classes autoload sometimes failed.

= 0.7.0 =
* Change controller name "Error" to "Invalid".
* Add custom DOM Event: `smf.beforesubmit` ex. `document.querySelector('.snow-monkey-form').addEventListener('smf.beforesubmit')`
* Add custom DOM Event: `smf.back` ex. `document.querySelector('.snow-monkey-form').addEventListener('smf.back')`
* Add custom DOM Event: `smf.confirm` ex. `document.querySelector('.snow-monkey-form').addEventListener('smf.confirm')`
* Add custom DOM Event: `smf.complete` ex. `document.querySelector('.snow-monkey-form').addEventListener('smf.complete')`
* Add custom DOM Event: `smf.invalid` ex. `document.querySelector('.snow-monkey-form').addEventListener('smf.invalid')`
* Add custom DOM Event: `smf.systemerror` ex. `document.querySelector('.snow-monkey-form').addEventListener('smf.systemerror')`
* Add custom DOM Event: `smf.submit` ex. `document.querySelector('.snow-monkey-form').addEventListener('smf.submit')`

= 0.6.0 =
* [Checkboxes] Add direction setting.
* [Radio buttons] Add direction setting.
* [Checkboxes] Default direction, vertical on smartphone size, horizontal on larger size.
* [Radio buttons] Default direction, vertical on smartphone size, horizontal on larger size.

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
