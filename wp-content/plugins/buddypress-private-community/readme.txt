=== BuddyPress Private Community ===
Contributors: NipponMonkey
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RWX65UE2TBFH2
Tags: private, community, privacy, secret, secure, buddypress, buddy, press, BP, admin, page, security, plugin
Requires at least: Only tested on 2.9+ (Requires PHP5)
Tested up to: 3.1 & 1.2.8
Stable tag: 0.6

This plugin makes your BuddyPress community private. Only logged in members can view the social areas in full. You can configure the default settings.


== Description ==
This plugin makes your BuddyPress community private. You can control which areas of your site are accessible to logged out users in two ways, "restrict site access, but allow some public pages" or "allow site access, but restrict some private pages". This plugin also can block widgets from logged out users and block all RSS feeds.

In the default mode (restrict access, but allow some public pages), logged out users only have access to your homepage, or access to a list of pages/areas that you'd like to make public. Logged in members have full site access.

If you visit the community when you're logged out and you're visiting a private page, then you're redirected to a landing page of your choice (defaults to the homepage).

You can set uris that are accessibly to non-logged in users using a special config php file - no database calls are needed.

In the alternative mode (allow site access, but restrict some private pages), logged out members have full site access, but you are able to make some pages/areas of your site private - so only logged in members are able to view them. This is the opposite to the default mode.

In the default mode, the plugin blocks all of your widgets from being visible to logged out users. This stops possible private information from being seen when non-logged in users visit your site.
You can change the config file so that some of your widgets are still displayed. In the alternative mode, all widgets are shown but you're able to block widgets that should only be displayed to members.

Also, all RSS feeds are blocked by default too.

You might also like to change these setting in your WordPress and BuddyPress settings.

* BP Setting: Hide admin bar for logged out users? = YES
* WP Setting: Membership - Anyone can register? = NO
* WP Privacy Settings: Site Visibility = I would like to block search engines, but allow normal visitors

See the FAQs or the example config file in the download for more information on configuring this plugin.

You can see an example of this plugin working in the default mode here: http://www.englishpubpool.co.uk/bppc_test/about/

* Please note, this plugin requires PHP 5.

If you'd like to ensure that users don't stay logged into your site after a set period of inactivity (for security reasons), then you could use this plugin:

* Inactivity Auto Sign Out plugin: http://wordpress.org/extend/plugins/inactivity-auto-sign-out-plugin/

== Installation ==

1. Upload the plugin's folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If your community is not in the root of your domain or subdomain, then you must make a config file. You must set the `::$WP_SUB_FOLDER` variable to the directory of your community. `E.g. for www.my-domain.com/bp/(BP SITE), ::$WP_SUB_FOLDER='bp'.` `For www.my-domain.com/charity/community/(BP SITE), ::$WP_SUB_FOLDER='charity/community'.` WARNING, please make sure you save the config file in the correct place - See FAQs for information about creating a config file. All it requires is making a new folder (in the correct place) and adding a PHP config with your settings. These settings will override the default settings.
4. That's it! 

You can change the default setting by creating a special config file. See FAQs for more information.
You can change:

* The MODE, two modes: block all pages from logged out users apart from a few public pages OR allow access to all pages apart from a few private (members only) ones.
* The WP_SUB_FOLDER, the directory where your BP site is, this must be changed if your BP site isn't at the root of your domain or sub-domain.
* The ALLOWED_URIS, these are the pages that are accessible to non-members. (Default = Array('')) (Used in default mode only)
* The NOT_ALLOWED_URIS, these are the pages that are not accessible to non-members. (Default = Array('')) (Used in alternative mode only)
* The REDIRECT_TO_URL, this is where you'll be redirected too if you try to access a private page when you're logged out. (Default = site_url())
* The REDIRECT_HOOK, this is a string that will appear in the URL that stores the private page that you were redirected from. This is used to redirect the user again after logging into your site.
* The BLOCK_RSS_FEEDS, if true then all of the WordPress and BuddyPress feeds will be blocked. (Default = TRUE)
* The BLOCK_WIDGETS, if true then the sidebar widgets won't be displayed to logged out users. This is useful as often private information is contained in the sidebar widgets. (Default = TRUE)
* The ALLOWED_WIDGET_IDS, an array of widget ids that are OK to display to logged out users (Used in default mode only).
* The NOT_ALLOWED_WIDGET_IDS, an array of widget ids that are not OK to be display to logged out users (Used in alternative mode only).
* You can also set custom messages that will appear in the RSS/ATOM feeds instead of your private content.

== Frequently Asked Questions ==

= Why isn't my config file working? =

99% of the time is because you didn't save the config file in the correct place.

You MUST save the file in a new directory, not in this plugin's folder structure, but in the main plugins folder.

`
// You MUST save the file here:
/wp-content/plugins/buddypress-private-community-config/mm-buddypress-private-community-config.php

// NOT HERE:
/wp-content/plugins/buddypress-private-community/mm-buddypress-private-community-config.php

// NOR HERE:
/wp-content/plugins/buddypress-private-community/buddypress-private-community-config/mm-buddypress-private-community-config.php
`

This is to ensure that your config setting are not deleted when you update the plugin in the future.

See "How can I override the default settings?" for information about the setting that you can change.


= How can I override the default settings? =

You can create your own config file (that won't be overwritten when you update this plugin) that stores your preferred settings.
To stop this file from being overwritten when you update the plugin, you have to create a new folder, called "buddypress-private-community-config", in the WP wp-content/plugins/ directory (NOTE: this is not in the wp-content/plugins/buddypress-private-community folder to avoid overriding the file on update) and create a file called "mm-buddypress-private-community-config.php" in the new folder.
Because the file is in a seperate folder to the plugin, the config file won't be overwritten when you update the plugin later. Also, this method means no database calls are needed to run this plugin. So, it should be a fast plugin.

Here is an example config file, that should be saved in the new config directory. It might look confusing at first, but in most cases you only need to use some of these settings as required. See FAQs "How can I allow members to automatically register to my private community?" for a simple config file that allows user to register to your site.

`
<?php 
/**
 * BuddyPress Private Community: User Config File
 * 
 * This must be saved here:
 * 	- /wp-content/plugins/buddypress-private-community-config/mm-buddypress-private-community-config.php
 * IMPORTANT NOTE: The config file SHOULD NOT be contained in this plugins folder, it should have its own folder in the plugins directory as shown above.
 * This is to stop the file from being overwritten when the plugin is updated, and so no database calls are needed.
 * 
 * See FAQs and forum for more examples of config file setups. You'll need a config file if you're not running your BP community from the root of your domain or sub-domain.
 * http://buddypress.org/community/groups/buddypress-private-community/home/
 * 
 * You can change the below settings:
 */


/**
 * There are 2 modes:
 * 
 * 1, ::$MODE = ::MODE_BLOCK_ALL_AND_ALLOW_SOME_URIS (DEFAULT MODE)
 * 		- Blocks all pages/URIs apart from them listed in the ::$ALLOWED_URIS array from logged out users.
 * 		- Widgets can be unblocked by adding their ids to the ::$ALLOWED_WIDGET_IDS (when ::$BLOCK_WIDGETS=TRUE)
 * 
 * 2, ::$MODE = ::MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS (opposite to the above mode)
 * 		- Doesn't block any pages from logged out users apart from the URIs listed in the ::$NOT_ALLOWED_URIS
 * 		- Widgets can be blocked by adding them to the ::$NOT_ALLOWED_WIDGET_IDS
 * 
 */
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$MODE = MM_BUDDYPRESS_PRIVATE_COMMUNITY::MODE_BLOCK_ALL_AND_ALLOW_SOME_URIS;
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$MODE = MM_BUDDYPRESS_PRIVATE_COMMUNITY::MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS;

/*
 * If your community isn't on the root of your domain or sub-domain then you should set the ::$WP_SUB_FOLDER to the directory of your community.
 * e.g. http://my-domain.com/{my-wp-installation}, then ::$SUB_FOLDER=''
 * e.g. http://my-domain.com/sub-folder-name/{my-wp-installation}, then ::$SUB_FOLDER='sub-folder-name'
 * e.g. http://my-domain.com/sub/folder/name/{my-wp-installation}, then ::$SUB_FOLDER='sub/folder/name'
 * Note, don't use a start or trailing '/' otherwise the plugin won't work.
 */ 
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$WP_SUB_FOLDER	 				= 'my/private/community';
`


`
/**
 * $ALLOWED_URIS is an array of URIs that are accessible to everyone.
 * 
 * URIs should start with a '/' but not end with a '/'.
 * E.g.
 * 	- '/about' is OK, but '/about/' isn't valid!
 * 	- '/about?page=info' is OK too
 * 	- '' is the HOMEPAGE, 
 * 	- '/' is INVALID!
 * 
 * You can now also use the special character '*' at the end of your uris to allow access to greater areas of your site.
 * E.g.
 * 	- '/about/*' allows access to '/about/contacts' and '/about/public-page' but doesn't allow access to '/about'!!! You should add '/about' seperately.
 * 	- '/public*' allows access to '/public/posts' and '/public-not-really-public/private-posts'
 * 	- Array('/welcome', '/welcome/*', '/welcome?*') allows access to '/welcome/new-member' and '/welcome?message=new-member' but not access to '/welcome-new-member'
 * 
 * $ALLOWED_URIS must be an Array. All of the URIs listed will be accessible to all logged out users.
 * This array must contain the REDIRECT_TO_URL's URI, otherwise you'll get an infinite redirect loop!
 * 
 * E.g. This is OK as '/landing_page' is in the URIS array and is the REDIRECT_TO_URL page too.
 * MM_BUDDYPRESS_PRIVATE_COMMUNITY::$ALLOWED_URIS 		= Array('/landing_page', '/info', '/contacts');
 * MM_BUDDYPRESS_PRIVATE_COMMUNITY::$REDIRECT_TO_URL	= site_url() . '/landing_page';
 * 
 * Default = Array('') (Your homepage)
 */
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$ALLOWED_URIS			= Array('/contacts', '/about', '/welcome');
/**
 * Used in the ::MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS mode only. List of URIs that should be blocked.
 * Note, you should use the special char * to stop access to all sub folders and query strings.
 * array('/private', '/private/*', '/private?*') or array('/private*')
 */
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$NOT_ALLOWED_URIS		= Array('/private*');
/**
 * Currently the REDIRECT_TO_URL cannot include a '?' in the URL! 
 * This would result in an infinite redirect loop!
 * E.g.
 * 	- "site_url() . '/info'" is OK, but "site_url() . '/info?page=landing_page'" would result in an infinite redirect loop!
 * 
 * Obviously, this URL should exist on your site or on another site, if required.
 * 
 * Default = site_url() (Your homepage)
 */ 
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$REDIRECT_TO_URL		= site_url() . '/welcome';
/**
 * This string will be used to trigger redirects after a user logs in. This should be a unique string that won't clash with other query strings.
 * E.g. 
 * 	- REDIRECT_HOOK = 'my-redirect-hook';
 * 	- You'd get a URL like this when a logged out user tries to access a private page, 'http://my-domain.com/members':
 * 		- http://my-domain.com/about?my-redirect-hook=/members where "site_url() . '/about'" is your REDIRECT_TO_URL
 * 	- Now, when the user logs in, they will be automatically redirected to the 'http://my-domain.com/members' page that they just tried to access.
 * 
 * Default = 'bp_pc_redir_to'
 */
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$REDIRECT_HOOK			= 'my_redir_to';
/**
 * If true, this blocks all BuddyPress and WordPress sidebar widgets from displaying when the user is logged out.
 * Selected widgets can be displayed by adding their ids to the ::$ALLOWED_WIDGET_IDS.
 * Default = TRUE
 */
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$BLOCK_WIDGETS			= FALSE;

/**
 * A list of widget ids that are allowed to be displayed. All other widgets are hidden when ::$BLOCK_WIDGETS=TRUE.
 * Used in MODE_BLOCK_ALL_AND_ALLOW_SOME_URIS only.
 * E.g. Array('calendar-2'); where calendar-2 is a widget id.
 * Widget ids can be found be inspecting their html, e.g <div id="calendar-2">{WIDGET}</div>
 * @var Array
 */
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$ALLOWED_WIDGET_IDS		= Array('calendar-2');
/**
 * A list of widget ids that are not allowed to be displayed. All other widgets are displayed.
 * Used in MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS only.
 * E.g. Array('calendar-2'); where calendar-2 is a widget id.
 * Widget ids can be found be inspecting their html, e.g <div id="calendar-2">{WIDGET}</div>
 * @var Array
 */
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$NOT_ALLOWED_WIDGET_IDS 	= Array('private-widget-2');
`


`
/**
 * This blocks all BuddyPress and WordPress RSS feeds if TRUE.
 * Default = TRUE
 */
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$BLOCK_RSS_FEEDS		= FALSE;
/**
 * This allows you to give access to all the RSS feeds when the user is logged in.
 */
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$BLOCK_RSS_FEEDS_WHEN_LOGGED_IN	= FALSE;
/**
 * You can set your own feed messages that will be shown instead of your private content.
 * $USE_CUSTOM_FEED_MESSAGES must be set to TRUE otherwise the default messages will be shown.
 * If you set $USE_CUSTOM_FEED_MESSAGES = TRUE, then you must set the following variables:
 * $FEED_CHANNEL_TITLE, $FEED_CHANNEL_DESC, $FEED_ITEM_TITLE and $FEED_ITEM_DESC.
 */
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$USE_CUSTOM_FEED_MESSAGES	= TRUE;
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$FEED_CHANNEL_TITLE		= 'My Blog';
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$FEED_CHANNEL_DESC			= 'This is a private blog. All feeds are disabled.';
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$FEED_ITEM_TITLE			= 'My Blog (All feeds are disabled)';
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$FEED_ITEM_DESC			= 'This is a private blog. All feeds are disabled.';
`

= What other changes should I make to WordPress and BuddyPress to ensure my community is private? =

You might also like to change these settings in WordPress and BuddyPress:

* BP Setting: Hide admin bar for logged out users? = YES
* WP Setting: Membership - Anyone can register? = NO
* WP Privacy Settings: Site Visibility = I would like to block search engines, but allow normal visitors

If you'd like to ensure that users don't stay logged into your site after a set period of inactivity (for security reasons), then you could use this plugin:

* Inactivity Auto Sign Out plugin: http://wordpress.org/extend/plugins/inactivity-auto-sign-out-plugin/


= How can I allow members to automatically register to my private community? =

To allow registration to your community using the native BuddyPress forms, you should include these URIs in the allowed list.

`
// If you're running WP/BP from a sub folder, set this value to direct the plugin to the correct place.
// Don't set this or set it to '' if your WP/BP site is at the root of your domain or subdomain.
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$WP_SUB_FOLDER	 				= 'my/private/community'; // for www.my-domain.com/my/private/community/(BP SITE)
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$ALLOWED_URIS	= Array(
'/my-landing-page', 		// Where "/my-landing-page" is your default landing page of choice - this must match your your choice of ::$REDIRECT_TO_URL.
'/another-allowed-uri', 	// (Optional extra uri(s)) any other page(s) you'd to make public to non-members
'/register', 				// Allow access to the registration form
'/activate?key=*' 			// Allow access to the account activation URIs. The wildcard character * allows all activation codes to be accepted as valid URIs.
);
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$REDIRECT_TO_URL		= site_url() . '/my-landing-page';
//MM_BUDDYPRESS_PRIVATE_COMMUNITY::$REDIRECT_TO_URL		= site_url() . '/register'; // This would redirect all logged out users to your registration page.
`

Just add something like the above to your config file and all should work fine.

Note that, if BuddyPress changes its activate URI in the future, then of course this code would also have to be updated.


= How can I allow a widget to be displayed to logged out users? =

This can be done by adding the widget's id string to the ::$ALLOWED_WIDGET_IDS array. E.g.

`
// This line will let the widget with id=calendar-2 to be displayed when logged out users visit your site.
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$ALLOWED_WIDGET_IDS		= Array('calendar-2');
`

You can find the widget's id by looking at your site's source HTML. The widget id should be shown in the widget's main div. E.g.

`
<div id="calendar-2">
	// calendar widget!
</div>
`

You can add more widget ids easily:

`
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$ALLOWED_WIDGET_IDS		= Array('calendar-2', 'calendar-3', 'hallooo-widget-2');
`

Similarly, you can hide widget in the alternative mode in a similar way - e.g.

`
// Used in alternative mode only. (::MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS)
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$NOT_ALLOWED_WIDGET_IDS 	= Array('private-widget-2', 'top-secret-widget-1');
`


= What is the redirect hook for? =

The redirect hook is used to redirect a logged out member to a private page they tried to access when logged out. The redirect happens after they login.

This is important for when a member gets an email with a link to somewhere on the community that is private. If the member clicks on this link and isn't yet logged in, then they will be redirect to the default landing page.

When this happens, you notice that the redirect hook can be seen in the url, and it points the page that the member tried to access. Now, when the user successfully logs in, the plugin automatically handles a redirect to the page that the user originally tried to access.

This means the user doesn't have to go back to their emails and find the link again as the redirect is handled for them. 

You can change the key word/string used as the hook in the config file.


= How private will my BuddyPress community really be? =

Well, access to your community through PHP pages will be blocked to logged out users, but your images and uploaded files will still be accessible via their URLs as no PHP code is blocking access to jpgs, xls. etc...
You'd need to take other security measures if you want to protect your uploaded files.

But, as it's a private community, non-members won't know the URLs to these uploaded files - so they should be fairly safe from the outside world.

You might like to look into .htaccess configurations for extra security of your uploads.


= What is the redirect hook in the URL used for? =
When a logged out user tries to access a page that is private, they will be redirected to the default redirect URL with an extra redirect query hook in the URL to the private page.

This means that the private page's URI is stored in the URL for later reference. If the user now logs in to the site, they will be automatically redirected to the private page they just tried to access.

This is great for when you're sent a link to a private page in the community, but you're not logged in yet. You simply log in through the BuddyPress login form and you'll be taken to the page you wanted to see straight away.


= Can I use this plugin with PHP 4? =

Sorry, this plugin only works with PHP 5. WP and BP and moving to PHP 5 only, so we currently have no plans to make this plugin available for PHP 4.


= I'm getting a parse error! =

If you can a parse error like below:

`
Parse error: syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}' in mm-buddypress-private-community.php on line 39
`

This normally means you're not running PHP 5. This plugin requires PHP 5.

If this wasn't the problem, then you should try installing the plugin again. I've had one report where re-installing the plugin after getting this error fixed the bug - maybe from a bad download or something.


= I'm getting an infinite redirect loop error! =

This normally means your config file isn't set up correctly.

You must make sure that your redirect url is a public page. E.g. here is a simple config file:

`
// Here we make a BP page called 'welcome' th default redirect page, and make this a public page to avoid an infinite redirect loop.
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$WP_SUB_FOLDER	 		= 'my-community';
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$ALLOWED_URIS			= Array('/contacts', '/about', '/welcome');
MM_BUDDYPRESS_PRIVATE_COMMUNITY::$REDIRECT_TO_URL		= site_url('welcome');
`

Note that the redirect URL is set to the 'welcome' page. This page must be in the ::$ALLOWED_URIS, as can be seen above.

If the '/welcome' URI is removed from the ::$ALLOWED_URIS array, then an infinite redirect will occur.


== Changelog ==

= 0.6 =
* IMPORTANT! BREAKABLE UPDATE: Changed how sub-directory installations are handled. Now you must set the ::$WP_SUB_FOLDER variable to the folder where your community is. (my-domain.com/community/{BP_SITE}, then ::$WP_SUB_FOLDER='community'. ALLOWED_URIS would now look like this array('/about') instead of array('/community/about') in this example - meaning that the ALLOWES_URIS are always written in the same way relative to your BP site_url.) See FAQs or the example config file. 
* Added another mode allowing you to give access to logged out users but block a few private pages/URIs (MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS).
* Added the ability to display/block widgets (depending on the mode) by their id (ALLOWED_WIDGET_IDS & NOT_$ALLOWED_WIDGET_IDS).
* Changed how widgets are block, so they should be block on all themes (including two widget column themes).

= 0.5 =
* Added a new option for allowing feeds to not be blocked when the user is logged in, $BLOCK_RSS_FEEDS_WHEN_LOGGED_IN. This can be set to FALSE in the config file, it defaults to TRUE.
* Fixed bug in BP RSS feeds!

= 0.4 =
* Changed add_action calls to static functions/vars from add_action('action_name', 'MY_CLASS::STATIC') to add_action('action_name', array('MY_CLASS', 'STATIC')). The older method was only supported in PHP 5.2.3+, the new method has greater support in PHP.

= 0.3 =
* Fixed a bug in the RSS feeds.
* Added greater support for RSS/ATOM feed custom messages, that are shown instead of your private content.

= 0.2 =
* Added support for a new special character '*' at the end of the allowed URIs. E.g. '/public-blog/*' would allow access to '/public-blog/post-1' but not access to '/public-blog'. '/public-blog*' would allow access to '/public-blog/post-1' and '/public-blog-that-are-private'.

= 0.1 =
* The first version - designed for simple installation!
* Added a check to see if a user defined config file exists for overriding the default settings. This is useful as this file won't be overwritten when the plugin is updated - and requires no calls to the database.

= 0.0 =
* This version never actually existed!

== Upgrade Notice ==

= 0.6 =

IMPORTANT! 
If you're running BP not from the root of your domain or subdomain, 
then you'll have to update your config file after making this update. 
See the change log 0.6 for more information.

= 0.1 =

This plugin should upgrade with no changes needed by the user.