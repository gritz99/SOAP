=== Email Users ===
Contributors: vprat, mpwalsh8
Donate link: http://www.marvinlabs.com
Tags: email, users, list, admin
Requires at least: 3.3
Tested up to: 3.4.2
Stable tag: 4.3.21

A plugin for WordPress which allows you to send an email to the registered blog users. Users can send personal emails to each other. Power users can email groups of users and even notify group of users of posts.

== Description ==

A plugin for WordPress which allows you to send an email to the registered blog users. Users can send personal emails to each other. Power users can email groups of users and even notify group of users of posts.

All the instructions for installation, the support forums, a FAQ, etc. can be found on the [plugin home page](http://www.marvinlabs.com).

This plugin is available under the GPL license, which means that it's free. If you use it for a commercial web site, if you appreciate my efforts or if you want to encourage me to develop and maintain it, please consider making a donation using Paypal, a secured payment solution. You just need to click the donate button on the [plugin home page](http://www.marvinlabs.com) and follow the instructions.

== Changelog ==

= Version 4.3.21 =
* Updated Spanish translation files.

= Version 4.3.20 =
* ReadMe file updates.

= Version 4.3.19 =
* Fixed missing DIV on landing page causing footer to appear in wrong spot.
* Added some more marketing information.
* Tweaked some more wording to be consistent with other areas of the plugin.
* Removed page layout development code.

= Version 4.3.18 =
* Updated plugin landing page to be cleaner and use modern WordPress styling.

= Version 4.3.17 =
* Fixed "%FROM_NAME%" does not get replaced properly in notifications when using override.
* Updated Plugin Settings page to be cleaner and use modern WordPress styling.

= Version 4.3.16 =
* Fixed "%FROM_NAME%" does not get replaced in notifications

= Version 4.3.15 =
* Replaced use of deprecated function *the_editor()* with *wp_editor().
* Fixed Javascript conflict which affects Dashboard and Menu Management resulting from enqueing the WordPress *'post'* library.
* Fixed bug where user settings are not saved correctly when toggling user setting control.
* Fixed bug when the dollar sign character ($) appears in the content of a page or post.
* Added option to include sender in recipient list.
* Numerous updates to make translation easier.
* Updated Spanish and French translation files.

= Version 4.3.14 =
* Bump in version number because one was missed in 4.3.13 preventing automatic updates from the WordPress plugin repository.  Duh.

= Version 4.3.13 =
* Bump in version number because one was missed in 4.3.12 preventing automatic updates from the WordPress plugin repository.

= Version 4.3.12 =
* Fixed bug(s) which prevented users with capabilities to email other users from doing so.
* Initial inclusion of Spanish language translation files (courtesy of Ponç J. Llaneras).
* Updated French language translation files.

= Version 4.3.11 =
* Fixed problem when using BCC limits where the last "chunk" of addresses were never sent the email.

= Version 4.3.10 =
* Fixed a problem with the "To:" header when sending email to a single user which appeared on some platforms (e.g. one IIS system that I know of).

= Version 4.3.9 =
* Removed some debug messages which slipped through, one of which caused a PHP error.

= Version 4.3.8 =
* Fixed a problem with Send to Users ignoring the Mass Email setting when selecting multiple users.
* Added messages to alert user when addresses were filtered out due to Mass Email setting.
* Added internationalization support for some additional messages.
* Inclusion of Russian language translation files.
* Fixed bug which resulted in duplicate emails for recipients when both roles and users were selected.

= Version 4.3.7 =
* Fixed minor typos.
* Fixed version number so Dashboard update will kick off.

= Version 4.3.6 =
* Fixed bug in User Settings Table Rows setting which stored number of rows in the wrong option field.
* Added options to set an "override" From Name and/or From Email Address which can be used when sending Mass or Post/Page Notifications.
* Added ability to override sender name and/or email address when sending Mass Email or Post/Page Notifications.  The default remains to use the name and email address from the currently logged in user.  When the Override Address is set on the Email Users Plugin Settings Page, the user will be presented with a Radio Button choice on email and notification pages where they can send the email using their login (default behavior) or select the Override Address and From Name.
* Added new option to enable process short codes embedded in posts and pages when sending notifications.
* Fixed a bunch of text messages to support translation.
* Initial inclusion of French language translation files (courtesy of Emilie DCCLXI).
* Inclusion of Persian language translation files.
* Fixed bug on User Settings page where number_format() warning was issued.  Reported on the WordPress.org Support Forum.

= Version 4.3.5 =
* Added some more values for the BCC limit setting
* Corrected one message for translation

= Version 4.3.4 =
* Fixed bug which caused some user recipients to be reported as having invalid email addresses.
* Added translation support to several error messages where it was missing.
* Fixed several more typos.

= Version 4.3.3 =
* Fixed typos which appears on the user profile page for the options to receive email and notifications.
* Added an option to allow the Admin User (requires role 'edit_users') to enable or disable users ability to control their Email Users settings.  By default users can control their own settings.

= Version 4.3.2 =
* Hid "Notify Users" submenu on Pages and Posts Menu for users who don't have the proper capability.
* Fixed problem where debug code was preventing mail from being sent to users.

= Version 4.3.1 =
* Migrated custom SQL query over to WordPress get_users() API.  Use of this API requires WordPress 3.3 or later.
* Fixed SQL bug in User Settings table when changing the column sort.
* Fixed plugin activation error which appears when running WordPress 3.4.

= Version 4.3.0 =
* Replaced slow, inefficient SQL queries to build "nice" user names with more efficient queries.  Thanks to the WP Hackers mailing list for the assistance.

= Version 4.2.0 =
* Fixed serious flaw in User Setting implementation which uses the WP_List_Table class.  The logic was not accounting for large number of users and would slow to a crawl because it was processing the entire list of users instead of just a subset for each page.

= Version 4.1.0 =
* Fixed bug which prevented default settings for a user from being added when a user was registered.
* Added new plugin options to set default state for notifications and mass email.  It is now possible to default new users to any combination of email and notifications settings.
 
= Version 4.0.0 =
* Code updated to use WordPress Menu API for Dashboard Menus.
* Code updated to use WordPress Options API for plugin settings.
* Updated plugin to eliminate WordPress deprecated function notices.
* Added new User Settings page to the pluin menu where bulk settings can be applied to one or more users.  This page makes reviewing user settings much easier than looking at users one at a time.
