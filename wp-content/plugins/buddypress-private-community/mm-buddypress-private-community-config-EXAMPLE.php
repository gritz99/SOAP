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
 * 	- This is very useful for when you are linked to the site from an email and you're not logged in yet. They page you're trying to access in stored and you're automatically redirected to it after you login through the BuddyPress login form.
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
