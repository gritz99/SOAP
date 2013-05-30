=== Plugin Name ===
Contributors: picklewagon
Donate link: http://picklewagon.com/wordpress/new-user-approve/donate
Tags: users, registration
Requires at least: 3.2.1
Tested up to: 3.5
Stable tag: 1.4.2

New User Approve is a Wordpress plugin that allows a blog administrator to 
approve a user before they are able to access and login to the blog.

== Description ==

In a normal Wordpress blog, once a new user registers, the user is created in 
the database. Then an email is sent to the new user with their login 
credentials. Very simple. As it should be.

The New User Approve plugin modifies the registration process. When a user 
registers for the blog, the user gets created and then an email gets sent to 
the administrators of the site. An administrator then is expected to either 
approve or deny the registration request. An email is then sent to the user 
indicating whether they were approved or denied. If the user was approved, 
the email will include the login credentials. Until a user is approved, the 
user will not be able to login to the site.

== Installation ==

1. Upload new-user-approve to the wp-content/plugins directory
2. Activate the plugin through the Plugins menu in WordPress
3. No configuration necessary.

== Frequently Asked Questions ==

= Why am I not getting the emails when a new user registers? =

The New User Approve plugin uses the functions provided by WordPress to send
email. Make sure your host is setup correctly to send email if this happens.

= How do I customize the email address and/or name when sending notifications to users? =

This is not a function of the plugin but of WordPress. WordPress provides the
*wp_mail_from* and *wp_mail_from_name* filters to allow you to customize this.
There are also a number of plugins that provide a setting to change this to 
your liking.

* [wp mail from](http://wordpress.org/extend/plugins/wp-mailfrom/)
* [Mail From](http://wordpress.org/extend/plugins/mail-from/)

= Why is the password reset when approving a user? =

The password is generated again because, by default, the user will not be aware
of their password. By generating a new password, the email that notifies the
user can also give them the new password just like the email does when recieving
your password on a regular WordPress install. At approval time, it is impossible
to retrieve the user's password.

There is a filter available (new_user_approve_bypass_password_reset) to turn off
this feature.

== Screenshots ==

1. The backend to manage approving and denying users.

== Changelog ==

= 1.4.2 =
* fix password recovery bug if a user does not have an approve-status meta field
* add more translations
* tested with WordPress 3.5

= 1.4.1 =
* delete transient of user statuses when a user is deleted

= 1.4 =
* add filters
* honor the redirect if there is one set when registering
* add actions for when a user is approved or denied
* add a filter to bypass password reset
* add more translations
* add user counts by status to dashboard
* store the users by status in a transient

= 1.3.4 =
* remove unused screen_layout_columns filter
* tested with WordPress 3.4

= 1.3.3 =
* fix bug showing error message permanently on login page

= 1.3.2 =
* fix bug with allowing wrong passwords

= 1.3.1 =
* add czech, catalan, romanian translations
* fix formatting issues in readme.txt
* add a filter to modify who has access to approve and deny users
* remove deprecated function calls when a user resets a password
* don't allow a user to login without a password

= 1.3 =
* use the User API to retrieve a user instead of querying the db
* require at least WordPress 3.1
* add validate_user function to fix authentication problems
* add new translations
* get rid of plugin errors with WP_DEBUG set to true

= 1.2.6 =
* fix to include the deprecated code for user search

= 1.2.5 =
* add french translation

= 1.2.4 =
* add greek translation

= 1.2.3 =
* add danish translation

= 1.2.2 =
* fix localization to work correctly
* add polish translation

= 1.2.1 =
* check for the existence of the login_header function to make compatible with functions that remove it
* added "Other Notes" page in readme.txt with localization information.
* added belarusian translation files

= 1.2 =
* add localization support
* add a changelog to readme.txt
* remove plugin constants that have been defined since 2.6
* correct the use of db prepare statements/use prepare on all SQL statements
* add wp_enqueue_style for the admin style sheet

= 1.1.3 =
* replace calls to esc_url() with clean_url() to make plugin compatible with versions less than 2.8
 
= 1.1.2 =
* fix the admin ui tab interface for 2.8
* add a link to the users profile in the admin interface
* fix bug when using email address to retrieve lost password
* show blog title correctly on login screen
* use get_option() instead of get_settings()
 
= 1.1.1 =
* fix approve/deny links
* fix formatting issue with email to admin to approve user
 
= 1.1 =
* correctly display error message if registration is empty
* add a link to the options page from the plugin dashboard
* clean up code
* style updates
* if a user is created through the admin interface, set the status as approved instead of pending
* add avatars to user management admin page
* improvements to SQL used
* verify the user does not already exist before the process is started
* add nonces to approve and deny actions
* temporary fix for pagination bug

== Upgrade Notice ==

= 1.3 =
This version fixes some issues when authenticating users. Requires at least WordPress 3.1.

= 1.3.1 =
Download version 1.3.1 immediately! A bug was found in version 1.3 that allows a user to login without using password.

= 1.3.2 =
Download version 1.3.2 immediately! A bug was found in version 1.3 that allows a user to login using any password.

== Other Notes ==

= Filters =
* *new_user_approve_user_status* - modify the list of users shown in the tables
* *new_user_approve_request_approval_message* - modify the request approval message
* *new_user_approve_request_approval_subject* - modify the request approval subject
* *new_user_approve_approve_user_message* - modify the user approval message
* *new_user_approve_approve_user_subject* - modify the user approval subject
* *new_user_approve_deny_user_message* - modify the user denial message
* *new_user_approve_deny_user_subject* - modify the user denial subject
* *new_user_approve_pending_message* - modify message user sees after registration
* *new_user_approve_registration_message* - modify message after a successful registration
* *new_user_approve_register_instructions* - modify message that appears on registration screen
* *new_user_approve_pending_error* - error message shown to pending users when attempting to log in
* *new_user_approve_denied_error* - error message shown to denied users when attempting to log in

= Actions =
* *new_user_approve_user_approved* - after the user has been approved
* *new_user_approve_user_denied* - after the user has been denied
* *new_user_approve_approve_user* - when the user has been approved
* *new_user_approve_deny_user* - when the user has been denied

= Translations =
The plugin has been prepared to be translated. If you want to help to translate the plugin to your language, please have a look at the localization/new-user-approve.pot file which contains all defintions and may be used with a gettext editor like Poedit (Windows). More information can be found on the [Codex](http://codex.wordpress.org/Translating_WordPress).

When sending me your translation files, please send me your wordpress.org username as well.

* Belarusian translation by [Fat Cow](http://www.fatcow.com/)
* Catalan translation by [xoanet](http://profiles.wordpress.org/xoanet/)
* Croation translation by Nik
* Czech translation by [GazikT](http://profiles.wordpress.org/gazikt/)
* Danish translation by [GeorgWP](http://wordpress.org/support/profile/georgwp)
* Dutch translation by [Ronald Moolenaar](http://profiles.wordpress.org/moolie/)
* Finnish translation by Tonttu-ukko
* French translation by [Philippe Scoffoni](http://philippe.scoffoni.net/)
* German translation by Christoph Ploedt
* Greek translation by [Leftys](http://alt3rnet.info/)
* Hungarian translation by Gabor Varga
* Italian translation by [Pierfrancesco Marsiaj](http://profiles.wordpress.org/pierinux/)
* Lithuanian translation by [Ksaveras](http://profiles.wordpress.org/xawiers)
* Polish translation by [pik256](http://wordpress.org/support/profile/1271256)
* Romanian translation by [Web Hosting Geeks](http://webhostinggeeks.com/)
* Russion translation by [Alexey](http://wordpress.org/support/profile/asel)
* Spanish translation by [Eduardo Aranda](http://sinetiks.com/)
* Swedish translation by [Per Bj�levik](http://pastis.tauzero.se)
