<?php
/*
Plugin Name: BuddyPress Private Community
Plugin URI: http://wordpress.org/extend/plugins/buddypress-private-community/
Description: This plugin makes your BuddyPress community private. In the default mode, only logged in members can view the social areas in full, logged out users have restricted access. You can restrict the access of logged out users by listing the pages/areas of your site that you'd like to make public to the world in the config file (See FAQs or the example config file for more information.) Alternatively, you can make your site accessible to logged out users, but restrict access to some private pages/areas of your site. This plugin also blocks widgets and RSS feeds by default, you can config which widgets you'd like to be blocked or not blocked and you can allow RSS feeds by changing the config file. This plugin is very flexible and can be set-up to work how you'd like it to work. See the FAQs or the example config file for more information about the two modes and configurable settings. If you're running BP not on the root of your domain or sub-domain, then you'll need to make a config file before this plugin will work - again see FAQs. Also, you can see this plugin in action here: <a href="http://www.englishpubpool.co.uk/bppc_test/about/">http://www.englishpubpool.co.uk/bppc_test/about/</a>

Version: 0.6
Author: NipponMonkey
Author URI: http://www.englishpubpool.co.uk/bppc_test/about/
License: GPL2
*/
/*  Copyright 2010  NipponMonkey  (email : nipponmonkeyweb@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Check if user config file exists, and load it if it exists. 
// This file can be used to override default settings. See FAQs for more info
if (file_exists(dirname(dirname( __FILE__ )) . '/buddypress-private-community-config/mm-buddypress-private-community-config.php'))
	require( dirname(dirname( __FILE__ )) . '/buddypress-private-community-config/mm-buddypress-private-community-config.php' );


// Initiate the static plugin class on init 
add_action('init', array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'initiate'), 1);


class MM_BUDDYPRESS_PRIVATE_COMMUNITY {
	/**
	 * Default mode. Blocks all pages apart from the ones listed in the ::$ALLOWED_URIS array.
	 * @var int
	 */
	const MODE_BLOCK_ALL_AND_ALLOW_SOME_URIS		= 1;
	/**
	 * Blocks no pages apart from the ones listed in the ::$NOT_ALLOWED_URIS array.
	 * @var int
	 */
	const MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS	= 2;
	/**
	 * The mode, can be changed in the config file.
	 * @var int
	 */
	public static $MODE								= MM_BUDDYPRESS_PRIVATE_COMMUNITY::MODE_BLOCK_ALL_AND_ALLOW_SOME_URIS;
	/**
	 * '' will redirect to the WP home page, otherwise provide an full URL. E.g. "http://my-domain.com/landing-page" or site_url('redirect-landing-page').
	 * @var string
	 */
	public static $REDIRECT_TO_URL 					= '';
	public static $BLOCK_RSS_FEEDS 					= TRUE;
	public static $BLOCK_RSS_FEEDS_WHEN_LOGGED_IN 	= TRUE;
	/**
	 * This will be displayed in the query part of the URL after a logged out user tries to access a private page.
	 * @var string
	 */
	public static $REDIRECT_HOOK 					= 'bp_pc_redir_to';
	/**
	 * This will block widgets from being displayed when the user is loggeed out. This will help to hide private content.
	 * When set to true, you can still allow some widgets by adding the widget's id to the ::$ALLOWED_WIDGET_IDS.
	 * @var Boolean
	 */
	public static $BLOCK_WIDGETS 					= TRUE;
	
	/* 
	 * Used in MODE_BLOCK_ALL_AND_ALLOW_SOME_URIS only. Array of allowed urls that non-logged in users have access to.
	 * E.g. array('/redirect-landing-page','/contacts','/about') 
	 * This array can be set in the config file - See example config file for more info.
	 * @var Array
	 */
	public static $ALLOWED_URIS 					= Array('');
	/*
	 * Used in MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS only.
	 * E.g. Array('/private-page-1*', '/private-info*')
	 * This array can be set in the config file - See example config file for more info.
	 * @var Array
	 */ 
	public static $NOT_ALLOWED_URIS 				= Array('');
	/**
	 * A list of widget ids that are allowed to be displayed. All other widgets are hidden.
	 * Used in MODE_BLOCK_ALL_AND_ALLOW_SOME_URIS only.
	 * E.g. Array('calendar-2'); where calendar-2 is a widget id.
	 * Widget ids can be found be inspecting their html, e.g <div id="calendar-2">{WIDGET}</div>
	 * @var Array
	 */
	public static $ALLOWED_WIDGET_IDS				= Array(); 
	/**
	 * A list of widget ids that are not allowed to be displayed. All other widgets are displayed.
	 * Used in MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS only.
	 * E.g. Array('calendar-2'); where calendar-2 is a widget id.
	 * Widget ids can be found be inspecting their html, e.g <div id="calendar-2">{WIDGET}</div>
	 * @var Array
	 */
	public static $NOT_ALLOWED_WIDGET_IDS 			= Array();
	
	/*
	 * e.g. http://my-domain.com/my-wp-installation, then ::$SUB_FOLDER=''
	 * e.g. http://my-domain.com/sub-folder-name/my-wp-installation, then ::$SUB_FOLDER='sub-folder-name'
	 * e.g. http://my-domain.com/sub/folder/name/my-wp-installation, then ::$SUB_FOLDER='sub/folder/name'
	 * Note, don't use a start or trailing '/'.
	 */ 
	public static $WP_SUB_FOLDER	 				= '';
	
	/**
	 * You can set your own feed messages that will be shown instead of your private content.
	 * E.g.
	 * 	$USE_CUSTOM_FEED_MESSAGES	= TRUE;
	 * 	$FEED_CHANNEL_TITLE			= 'My Blog';
	 * 	$FEED_CHANNEL_DESC			= 'This is a private blog. All feeds are disabled.';
	 * 	$FEED_ITEM_TITLE			= 'My Blog (All feeds are disabled)';
	 * 	$FEED_ITEM_DESC				= 'This is a private blog. All feeds are disabled.';
	 */
	public static $USE_CUSTOM_FEED_MESSAGES		= FALSE;
	public static $FEED_CHANNEL_TITLE			= '';
	public static $FEED_CHANNEL_DESC			= '';
	public static $FEED_ITEM_TITLE				= '';
	public static $FEED_ITEM_DESC				= '';
	
	/**
	 * Checks variables and adds actions.
	 */
	public static function initiate() {
		// Check if $REDIRECT_TO_URL has been set by the user, otherwise set the default value.
		if (self::$REDIRECT_TO_URL === '') {
			// Set the default value - the site_url
			self::$REDIRECT_TO_URL = site_url();
		}
		// Check ALLOWED URIS is an Array otherwise reset to default value
		if (is_array(self::$ALLOWED_URIS) === FALSE) {
			self::$ALLOWED_URIS = Array('');
		}
		MM_BUDDYPRESS_PRIVATE_COMMUNITY::handle_disable_feeds();
		MM_BUDDYPRESS_PRIVATE_COMMUNITY::handle_block_widgets();
		MM_BUDDYPRESS_PRIVATE_COMMUNITY::handle_restrict_access();
	}
	
	/**
	 * Blocks Feeds if $BLOCK_RSS_FEEDS === TRUE
	 * Allows feeds when user is logged in when $BLOCK_RSS_FEEDS === TRUE && $BLOCK_RSS_FEEDS_WHEN_LOGGED_IN === FALSE
	 * 
	 * Warning plugins might have their own feeds that don't go through the standard WP classes. These feeds won't be blocked.
	 * 
	 * But, plugins that integrate their feeds correctly using WP feed classes will have their feeds blocked.
	 */
	protected static function handle_disable_feeds() {
		if (MM_BUDDYPRESS_PRIVATE_COMMUNITY::$BLOCK_RSS_FEEDS === TRUE) {
			if (
				MM_BUDDYPRESS_PRIVATE_COMMUNITY::$BLOCK_RSS_FEEDS_WHEN_LOGGED_IN === TRUE
				|| (
					MM_BUDDYPRESS_PRIVATE_COMMUNITY::$BLOCK_RSS_FEEDS_WHEN_LOGGED_IN === FALSE 
					&& is_user_logged_in() === FALSE
				)
			) {
				/**
				 * Try to stop ALL RSS Feeds
				 */
				// WP feeds.
				add_action('do_feed', 		array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_start_xml'), 1);
				add_action('do_feed_rdf', 	array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_start_xml'), 1);
				add_action('do_feed_rss', 	array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_start_xml'), 1);
				add_action('do_feed_rss2', 	array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_start_xml'), 1);
				add_action('do_feed_atom', 	array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_start_xml'), 1);
				
				// BP Feeds: /buddypress/bp-activity/feeds/*.php
				add_action('bp_activity_favorites_feed', 	array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_end_xml'), 1);
				add_action('bp_activity_friends_feed', 		array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_end_xml'), 1);
				add_action('bp_activity_group_feed', 		array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_end_xml'), 1);
				add_action('bp_activity_mentions_feed', 	array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_end_xml'), 1);
				add_action('bp_activity_mygroups_feed', 	array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_end_xml'), 1);
				add_action('bp_activity_personal_feed', 	array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_end_xml'), 1);
				add_action('bp_activity_sitewide_feed', 	array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'disable_feed_end_xml'), 1);
			}
		}
	}
	
	/**
	 * This blocks the widgets from being displayed to logged out users.
	 */
	protected static function handle_block_widgets() {
		if (is_user_logged_in() === FALSE) {
			if (MM_BUDDYPRESS_PRIVATE_COMMUNITY::$BLOCK_WIDGETS === TRUE) {
				add_action('wp_head', array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'hide_wp_widgets_where_needed'), 1);
			}
		}
	}
	
	/**
	 * Restricts action to your site.
	 */
	protected static function handle_restrict_access() {
		add_action( 'get_header', array('MM_BUDDYPRESS_PRIVATE_COMMUNITY', 'restrict_access'), 1);
	}
	
	/**
	 * Creates a feed that gives a message saying the feed is disabled/private.
	 */
	public static function disable_feed_start_xml() {
		// Creates the start of the feed and then calls mm_bp_private_community_disable_feed_end_xml() to finish it off
		// with a standard private/disabled message.
		
		header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
		header('Status: 200 OK');
		echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?>';
		?>
	<rss version="2.0"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:wfw="http://wellformedweb.org/CommentAPI/"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:atom="http://www.w3.org/2005/Atom"
		<?php 
		MM_BUDDYPRESS_PRIVATE_COMMUNITY::disable_feed_end_xml();
	}
	

	/**
	 * Called from inside the rss tag in the xml document.
	 * First, it closes the opening rss tag "<rss [stuff] do_action(___)" with a ">".
	 * Then, it creates the feed channel tag and a feed item. Both saying the feed is private/unavailable.
	 */
	public static function disable_feed_end_xml() {
		if (self::$USE_CUSTOM_FEED_MESSAGES===FALSE) {
			$blog_name 							= get_blog_option( BP_ROOT_BLOG, 'blogname');
			self::$FEED_CHANNEL_TITLE			= $blog_name;
			self::$FEED_CHANNEL_DESC			= $blog_name . ' is a private site. All feeds are disabled.';
			self::$FEED_ITEM_TITLE				= $blog_name . ' (All feeds are disabled)';
			self::$FEED_ITEM_DESC				= self::$FEED_CHANNEL_DESC;
		}
		$link = site_url();
		?>
		>
	<channel>
		<title><![CDATA[<?php echo self::$FEED_CHANNEL_TITLE; ?>]]></title>
		<atom:link href="<?php echo $link; ?>" rel="self" type="application/rss+xml" />
		<link><?php echo $link; ?></link>
		<description><?php echo self::$FEED_CHANNEL_DESC; ?></description>
		<item>
			<guid><?php echo $link; ?></guid>
			<title><![CDATA[<?php echo self::$FEED_ITEM_TITLE; ?>]]></title>
			<link><?php echo $link; ?></link>
			<description><![CDATA[<?php echo self::$FEED_ITEM_DESC; ?>]]></description>
		</item>
	</channel>
</rss>
	<?php 
		exit();
	}
	
	
	/**
	 * Takes out all unwanted widgets running checks against allowed/not allowed widget ids set by the user in the config file - depending on the mode too.
	 *
	 */
	public static function hide_wp_widgets_where_needed() {
		global $wp_registered_widgets;
		if (MM_BUDDYPRESS_PRIVATE_COMMUNITY::$MODE===MM_BUDDYPRESS_PRIVATE_COMMUNITY::MODE_BLOCK_ALL_AND_ALLOW_SOME_URIS) {
			// Mainly not allowing access, so block widgets.
			$is_blocking = true;
			$widget_ids = MM_BUDDYPRESS_PRIVATE_COMMUNITY::$ALLOWED_WIDGET_IDS;
		}else if (MM_BUDDYPRESS_PRIVATE_COMMUNITY::$MODE===MM_BUDDYPRESS_PRIVATE_COMMUNITY::MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS) {
			// Mainly allowing access, so don't block widgets.
			$is_blocking = false;
			$widget_ids = MM_BUDDYPRESS_PRIVATE_COMMUNITY::$NOT_ALLOWED_WIDGET_IDS;
		}
		if (count($widget_ids)==0) {
			if ($is_blocking) $wp_registered_widgets = array();// block all. Otherwise allow all (hence no else!)
		}else {
			$allowed_widgets = array();
			foreach ($wp_registered_widgets as $widget_id=>$widget) {
				$was_found = false;
				foreach ($widget_ids as $allowed_id)  {
					if ($allowed_id==$widget_id) {
						$was_found = true;;
						if ($is_blocking) {
							// Widget found in allowed list - so keep it!
							$allowed_widgets[$widget_id] = $widget;
						}
						continue; // go to next widget
					}
				}
				if (!$is_blocking && !$was_found) {
					// Widget not found in not allowed list - so keep it! 
					$allowed_widgets[$widget_id] = $widget;
				}
			}
			$wp_registered_widgets = $allowed_widgets;
		}
	}

	/**
	 * Restricts access of logged out users
	 */ 
	public static function restrict_access() {
		global $bp;
		
		/**
		 * For any logged in user, if the URL contains this String as a get variable,
		 * then they will be redirected to URI that is passsed to it.
		 */
		$REDIRECT_QUERY_NAME = MM_BUDDYPRESS_PRIVATE_COMMUNITY::$REDIRECT_HOOK;
		
		/**
		 * If logged in then check to see if a redirect is possible - then redirect.
		 * Also check that the redirect URI starts with '/', otherwise it's not valid.
		 */
		if (is_user_logged_in()) {
			if (isset($_GET[$REDIRECT_QUERY_NAME]) && preg_match('/^\//', $_GET[$REDIRECT_QUERY_NAME])!=0) {
				// This always redirects logged in users when the $REDIRECT_QUERY_NAME is valid!
				$REDIRECT_TO = site_url(urldecode($_GET[$REDIRECT_QUERY_NAME]));
				bp_core_redirect($REDIRECT_TO);
				exit();
			}
			// User is logged in - so everything else is OK - normal WP/BP permission take control.
			return;
		}else {
			/**
			 * Logged out! Handle redirect to accessible page, if needed.
			 */
			$REDIRECT_TO = MM_BUDDYPRESS_PRIVATE_COMMUNITY::$REDIRECT_TO_URL;
			
			// Current page URI.
			$uri = $_SERVER['REQUEST_URI'];
			
			/**
			 * Prepare the URI for checking against the allowed URIs
			 * 	- Remove the sub folder from the URI.
			 * 	- Remove the the $REDIRECT_QUERY_NAME part if it has one
			 * 	- Remove the ? if the URI now ends with ?
			 * 	- Remove the final / if the URI now ends in /
			 * 
			 * E.g. "/to-my-bp-folder/events/?event_id=4&bella-redir=/members" will be converted to "/events/?event_id=4"
			 */
			if (MM_BUDDYPRESS_PRIVATE_COMMUNITY::$WP_SUB_FOLDER==''){
				// Don't have to trim the $WP_SUB_FOLDER folder.
				$uri_trimmed = $uri;
			}else {
				$uri_trimmed = substr($uri, strlen(MM_BUDDYPRESS_PRIVATE_COMMUNITY::$WP_SUB_FOLDER) + 1);
			}
			$uri_trimmed = preg_replace(
				array("/" . $REDIRECT_QUERY_NAME . "=[^&?]*/", 	"/\?$/", 	"/\/$/"), 
				array('',										'',			''), 
				$uri_trimmed
			);
			$uri_trimmed = strtolower($uri_trimmed);
			
			if (MM_BUDDYPRESS_PRIVATE_COMMUNITY::$MODE===MM_BUDDYPRESS_PRIVATE_COMMUNITY::MODE_BLOCK_ALL_AND_ALLOW_SOME_URIS) {
				/**
				 * Check to see if logged out user is allowed access to this page
				 */
				$allowed_uris = MM_BUDDYPRESS_PRIVATE_COMMUNITY::$ALLOWED_URIS;
				for ($i=0; $i < count($allowed_uris); $i++) {
					$cur_allowed_uri = strtolower($allowed_uris[$i]);
					if (
						(
							$cur_allowed_uri === $uri_trimmed 
						) || (
							strpos($cur_allowed_uri, '*') === strlen($cur_allowed_uri) - 1
							&& strpos($uri_trimmed, substr($cur_allowed_uri, 0, strlen($cur_allowed_uri) - 1)) === 0
						)
					) {
						// Is allowed access, so return.
						return;
					}
				}
			
				// If this part is reached, then the user is logged out and doesn't have access to this page
				// because it's not in the allowed list.
				// So, handle the redirect to the default accessible page.
				
				// Add redirect URL, if needed
				if (preg_match('/^\//', $uri_trimmed)===0) {
					$uri_trimmed = '/' . $uri_trimmed;
				} 
				// This assumes the $REDIRECT_TO doesn't already have a ? in the URL.
				// '/' added at the start to stop an extra redirect that BP seems to do to add it to the URL.
				$REDIRECT_TO .= '/?' . $REDIRECT_QUERY_NAME . '=' . urlencode($uri_trimmed);
				
				bp_core_redirect($REDIRECT_TO);
				exit();
			}else if (MM_BUDDYPRESS_PRIVATE_COMMUNITY::$MODE===MM_BUDDYPRESS_PRIVATE_COMMUNITY::MODE_BLOCK_NONE_AND_NOT_ALLOW_SOME_URIS) {
				/**
				 * Check to see if logged out user is allowed access to this page
				 */
				$not_allowed_uris = MM_BUDDYPRESS_PRIVATE_COMMUNITY::$NOT_ALLOWED_URIS;
				for ($i=0; $i < count($not_allowed_uris); $i++) {
					$cur_not_allowed_uri = strtolower($not_allowed_uris[$i]);
					if (
						(
							$cur_not_allowed_uri === $uri_trimmed 
						) || (
							strpos($cur_not_allowed_uri, '*') === strlen($cur_not_allowed_uri) - 1
							&& strpos($uri_trimmed, substr($cur_not_allowed_uri, 0, strlen($cur_not_allowed_uri) - 1)) === 0
						)
					) {
						// If this part is reached, then the user is logged out and doesn't have access to this page
						// because it's in the allowed list.
						// So, handle the redirect to the default accessible page.
						
						// Add redirect URL, if needed
						if (preg_match('/^\//', $uri_trimmed)===0) {
							$uri_trimmed = '/' . $uri_trimmed;
						} 
						// This assumes the $REDIRECT_TO doesn't already have a ? in the URL.
						// '/' added at the start to stop an extra redirect that BP seems to do to add it to the URL.
						$REDIRECT_TO .= '/?' . $REDIRECT_QUERY_NAME . '=' . urlencode($uri_trimmed);
						
						bp_core_redirect($REDIRECT_TO);
						exit();
					}
				}
				// The page didn't match a not allowed URI, so the page can be loaded.
				return;
			}else {
				die('Unknown BuddyPress Private Community Mode: mode=' . MM_BUDDYPRESS_PRIVATE_COMMUNITY::$MODE . '. Please check your config file.');
			}
			
		}
	}
}