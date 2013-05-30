=== Plugin Name ===
Contributors: Tubal
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6W2TXS68W7A8Y
Tags: youtube, shortcode, embed, video, player
Requires at least: 2.8
Tested up to: 3.5
Stable tag: 1.8.5

Embed customized Youtube videos & playlists anywhere using shortcodes

== Description ==

A really simple and very lightweight plugin to embed customized Youtube videos & playlists anywhere in your blog or website using wordpress shortcodes.

= Documentation, live demos and support =

All documentation and demos are located at the [plugin website](http://www.margenn.com/tubal/youtube_shortcode/).

The plugin website is proudly powered by Wordpress and nearly all posts show a live demo so you can check how Youtube videos behave under every scenario/configuration such as:

* Window resizing (resize the browser window to check how "fluid" videos adapt to fit)
* Mobile viewing (visit the website with your mobile and check how videos look)
* RSS viewing (subscribe to the feed and check how videos look in your RSS reader)
* Different parameters for the Youtube player

What's more, I've added [Uservoice feedback](http://youtubesc.uservoice.com) so you can share your ideas and issues. I want to hear from you. You can also post comments on every article.

I've moved all documentation to a website because I think it's great to have a real Wordpress environment set up and running with a working version of the plugin (latest) and a live demo for every piece of documentation.

= Features: =

* Supports **all official YouTube player customization parameters** and respects Youtube's default values: [Youtube official documentation](http://code.google.com/intl/en/apis/youtube/player_parameters.html "Official documentation"). You can override any default value.
* Supports **TinyMCE editor button** (1.7+ version) to make it easier for you to customize the Youtube video player (auto-inserts the shortcode).
* Supports **RSS readers, Mobile browsers and fluid/elastic layouts**.
* Supports **automatic calculation of video height**. Just set the Youtube player `width` and omit the `height` parameter in the shortcode. There's no need to set a height manually to get the perfect height.
* Supports **automatic localization of Flash videos** ("old embed code" only). This plugin detects the visitor's preferred language to set the default language of the Youtube player UI & the video's captions/subtitles (otherwise en_US would be set by default for every visitor).
* Supports **normal** Youtube URLs, **short** Youtube URLs, **embed** Youtube URLs and Youtube's **video ID** as value for `url` parameter.
* Supports **setting a different "title" attribute** for each Youtube video (useful for **SEO** & **Accessibility**).
* Supports **both the deprecated Youtube AS2 Flash player and the new iframe player (AS3 Flash or HTML5)**. This plugin uses Youtube's new embed code (iframe: AS3 or HTML5 player) by default but optionally you can use the deprecated AS2 player (Flash only). The new embed code (iframe) will take care of the environment capabilities. This way you know your video will work on most devices, including mobile devices (that have Adobe's Flash 10.1 installed or support CSS, iframes and basic Javascript) and RSS readers.
* Supports **web browsers with no Javascript support or with Javascript disabled**. If a web browser does not support Javascript or Javascript is disabled, Youtube's new embed code will not work. In that case this plugin will try to show the Youtube video using Adobe's Flash (If Flash is not installed or enabled, the plugin will show the user what to do).
* **Developer friendly:** `wp-content` directory can be renamed or moved safely to a different location. For more info read [Moving wp-content](http://codex.wordpress.org/Editing_wp-config.php#Moving_wp-content)
* Tested on IE 6+, Firefox 2+, Opera 8+, Safari 3+, Chrome 5+, iOS & Android (Webkit mobile), Opera mobile, Firefox mobile (Fennec) and RSS readers (such as Google Reader and NetNewsWire).

= Roadmap =

In version 1.9 I'll develop a settings page so that you can store configuration defaults for every Youtube video.

Support its development [donating a few bucks :)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6W2TXS68W7A8Y) (PayPal).

Plugin developed by Túbal Martín at [www.margenn.com](http://www.margenn.com).

== Installation ==

1. Upload `youtube-shortcode` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php echo do_shortcode('[youtube_sc url=any_youtube_video_url]'); ?>` in your templates or just `[youtube_sc url=any_youtube_video_url]` in a post/page text editor and you're done.

== Changelog ==

= 1.8.5 =
* Bugfix: $is_mobile property is not set. Sorry guys!

= 1.8.4 =
* Added new official parameter: `end`.
* Fixed issue reported by some users: Youtube button in wysiwyg editor screwed wp editor.
* Fixed warning triggered by some hosts due to the `tinymcebutton.js.php` file. Now its extension is just `.js`. 
* Improved mobile detection engine.
* Improved mobile support for Youtube videos.
* CSS code moved to external stylesheet so it can be cached.

= 1.8.3 =
* Added support for Youtube SSL URLs: `https://`
* Added support for Youtube embed URLs: `http://www.youtube.com/v/VIDEO_ID` and `http://www.youtube.com/embed/VIDEO_ID`
* Allows the TinyMCE button to appear on any rich text editor (i.e. text editor in a widget)
* Improved mobile detection
* Upgrading will not break any website you may have built with previous versions. Upgrade with confidence.

= 1.8.2 =
* Improved compatibility for mobile browsers.
* Added new custom parameter: `embedcode`.
* Upgrading will not break any website you may have built with previous versions. Upgrade with confidence.

= 1.8.1 =
* Added support for the Youtube AS2 player (Flash only). Amongst other things you can force HD playback by default (720p).
* Improved compatibility for RSS readers and mobile browsers.
* Added fallback solution for fixed-width videos (static layout) so that fixed-width videos don't break or overflow a design/layout.
* Improved automatic height calculation. 16:9 and 4:3 aspect ratios are now 100% precise.
* Upgrading will not break any website you may have built with previous versions. Upgrade with confidence.

= 1.8 =
* Built dedicated website for documentation, live demos and support (through comments and [Uservoice](http://youtubesc.uservoice.com)). [Check it now!](http://www.margenn.com/tubal/youtube_shortcode/)
* Added two new custom parameters: `ratio` and `class`.
* Added two aliases for the `url` paramater: `v` and `video`. Use the one you prefer.
* Added state-of-the-art support for all kind of website layouts. Now Youtube videos can be inserted in elastic & fluid layouts as well as mobile designs without breaking or overflowing the design/layout. Please check the live demos in the plugin website.
* Added state-of-the-art support for RSS readers. Youtube videos will show up perfectly in major RSS readers.
* It's been a huge improvement in terms of layout and compatibility. No other video plugin for wordpress that I know offers this kind of flexibility and compatibility.
* Upgrading will not break any website you may have built with previous versions. Upgrade with confidence.

= 1.7.4 =
* Added `h` and `w` as alternative shortcode parameter names to `height` and `width` respectively.
* Removed the "type" attribute from the iframe and added the "allowfullscreen" attribute when fullscreen is enabled.
* Improved dialog UI

= 1.7.3 =
* The `get_hl()` method is back to set the UI & captions/subtitles language according to the visitor's language (automatically detected) for a very specific case: When the visitor's browser does not support Javascript or it is disabled. In this case, the old embed code (Flash only) will be embedded so we have to detect the visitor's language (otherwise en_US would be set as default).
* Now the `get_hl()` method is invoked only once per page load. Before this release it was invoked for every shortcode/video so the plugin execution is faster.
* Added `<noembed>` tag to extend compatibility with browsers with no Javascript support and no Flash support. The following message (styled exactly like Youtube's) will be shown to the user on each video: The Adobe Flash Player is required for video playback. [Get the latest Flash Player](http://get.adobe.com/flashplayer/)
* Other internal improvements.

= 1.7.2 =
* Improvement: Removed `get_hl()` method (private use) since it isn't needed anymore. Youtube detects the user language and sets the UI & captions/subtitles language accordingly.
* Improvement: When javascript is disabled the CSS selector used to hide the iframe is now scoped by the class `youtube_sc`. Before this release the CSS selector used could override any iframe with a class `youtube-player`.
* Other improvements (faster execution & more robust). It's recommended to update.

= 1.7.1 =
* Added new custom parameter: `title`. Very useful for SEO and Accessibility (WCAG 1.0+). This parameter sets the "title" HTML attribute on both `iframe` & `object` HTML elements. Its value should be a description of the video's content or several tags related to it. Please, see example 4 to know how to use it in templates.
* Bugfix: TinyMCE editor: Shortcode wouldn't work if the comma separated list of video IDs in `playlist` had any space character.

= 1.7 =
* Added TinyMCE button so that users can insert and customize a Youtube video easily. The shortcode is automatically generated and inserted in the editor.
* Added new official parameters: color, theme
* Added donation link
* Flash player ActionScript version 3 by default (AS2 is deprecated according to Youtube official docs)
* `wp-content` directory can be renamed or moved to a different location. For more info read [Moving wp-content](http://codex.wordpress.org/Editing_wp-config.php#Moving_wp-content)
* Code refactored to clean up the gobal namespace (now there's only a class named Youtube_shortcode).

= 1.6 =
* The video's html wrapper tag, `div` by default, changes automatically to `p` if the shortcode is used in a post/page wysiwyg text editor to avoid weird cases with WP formatting functions and other filters or plugins.

= 1.5 =
* Added new official parameters: autohide, controls, modestbranding, origin, playlist

= 1.4 =
* Force "wmode=transparent" on Youtube's new embed code (iframe) to solve some design issues when Flash is used (layers appearing below Flash video).

= 1.3 =
* Youtube's new embed code (iframe) is from now on the preferred way of embedding videos and supports all customization parameters.
* swfobject javascript library removed. Client side detection of Adobe's Flash is no longer needed.

= 1.2 =
* Added "wmode=transparent" to solve some design issues (layers appearing below Flash video)

= 1.1 =
* Improved automatic height calculation.
* Added support for web browsers with no Javascript support.