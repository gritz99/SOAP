<?php
/*
Plugin Name: Youtube shortcode
Plugin URI: http://www.margenn.com/tubal/youtube_shortcode/
Description: Embed Youtube videos using shortcodes
Version: 1.8.5
Author: Túbal Martín
Author URI: http://www.margenn.com
License: GPL2

Copyright 2011-2012  Túbal Martín  (email : tubalmartin@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

if (!class_exists('Youtube_shortcode')):

class Youtube_shortcode {

    var $plugin_version = '185'; // version 1.8.5
    var $hl; // Host language code
    var $is_feed;
    var $is_mobile = false;

    // Constructor / initialize plugin
    function Youtube_shortcode()
    {
        // Load our shortcode only on the frontend
        if ( !is_admin() )
        {
            // Is it a mobile phone?
            $this->detect_mobile();
            // Guess once host language (used for Youtube's old embed code = Flash only)
            $this->get_hl();
            // do_shortcode() is registered as a default filter on 'the_content' with a priority of 11.
            // To make videos appear on post excerpts follow this guide: http://bloggingsquared.com/blogging-tips/make-shortcodes-work-in-all-wordpress-post-excerpts/
            add_shortcode('youtube_sc', array($this, 'output_html'));
            add_action('wp_enqueue_scripts', array($this, 'output_css'));
        }
        // Allow the button to appear on any rich text editor (i.e. text editor in a widget)
        else
        {
            add_action('admin_init', array($this, 'setup_tinymce_button'));
        }
    }


    static function encode_str($str)
    {
        return str_replace(array(' ', '"', "'", '=', '[', ']', '/'), array('%20', '%22', '%27', '%3D', '%5B', '%5D', '%2F'), (string) $str);
    }


    function decode_str($str)
    {
        return str_replace(array('%20', '%22', '%27', '%3D', '%5B', '%5D', '%2F'), array(' ', '&quot;', "'", '=', '[', ']', '/'), (string) $str);
    }


    function output_css()
    {
        wp_register_style('YoutubeShortcodeMargenn', plugins_url('youtube-shortcode.css', __FILE__));
        wp_enqueue_style('YoutubeShortcodeMargenn');
    }


    function output_html( $atts, $content = null )
    {
        if ( !isset($this->is_feed) )
        {
            $this->is_feed = is_feed();
        }

        $default_width  = '560';
        $default_height = '340';
        $default_ratio  = '16:9';
        $default_title  = 'YouTube video player';

        extract( shortcode_atts( array(
            // custom parameters
            'url' => '',
            'v' => '', // Alias of url
            'video' => '', // Alias of url
            'title' => $default_title, // SEO & WCAG 1.0+
            'width' => $default_width, // minimum: 200
            'height' => $default_height,
            'w' => $default_width, // Alias of width
            'h' => $default_height, // Alias of height
            'ratio' => $default_ratio, // or 4:3
            'class' => '', // Additional CSS class name(s)
            'embedcode' => 'new', // new = iframe. old = object.
            'version' => '3',
            'nocookie' => '0', // privacy mode
            // Youtube's official player parameters & default values
            // Reference: https://developers.google.com/youtube/player_parameters
            'autohide' => '2',
            'autoplay' => '0',
            'cc_load_policy' => '', // user's account setting. To force subtitles set to 1
            'color' => 'red',
            'controls' => '1',
            'disablekb' => '0',
            'enablejsapi' => '0',
            'end' => '0',
            'fs' => '0',
            'iv_load_policy' => '1', // or 3
            //'list' => '',
            //'listType' => '',
            'loop' => '0',
            'modestbranding' => '0',
            'origin' => '',
            'playerapiid' => '',
            'playlist' => '',
            'rel' => '1',
            'showinfo' => '1',
            'start' => '',
            'theme' => 'dark',
            // Deprecated parameters only used in the AS2 Player API
            'border' => '0',
            'color1' => 'b1b1b1',
            'color2' => 'cfcfcf',
            'egm' => '0',
            'hd' => '0',
            'showsearch' => '1'    
        ), $atts ) );

        // Handle alternative parameter names (aliases)
        $width  = $w != $default_width ? $w : $width;
        $height = $h != $default_height ? $h : $height;
        $url    = trim((empty($url) ? (empty($v) ? $video : $v) : $url));

        $width  = $this->is_mobile ? '100%' : ((int) $width < 200 ? '200' : ($this->is_feed && (int) $width > 650 ? '650' : $width));
        $height = $this->is_mobile ? '100%' : ((($width != $default_width && $height == $default_height) || ($this->is_feed && $width == '650')) ? (string) (floor((int) $width / ($ratio == $default_ratio ? 1.78 : 1.34)) + 25) : $height);
        $static = $this->is_mobile ? false : (($width != $default_width || $height != $default_height) ? true : false);
        $title  = $title != $default_title ? $this->decode_str((string) $title) : $default_title;
        $ratio  = $ratio == $default_ratio ? 'widescreen' : 'fourthree';
        $wtag   = in_the_loop() ? 'p' : 'div'; // Add support for Youtube shortcode in posts
        $itag   = $wtag == 'div' ? 'div' : 'span';

        //$video_id  = !empty($list) ? '' : '/'.$this->get_video_id($url);
        $video_id = $this->get_video_id($url);
        $video_url = 'http://'.$this->get_server_host($url).'youtube' . ((bool) $nocookie ? '-nocookie' : '') . '.com/%s/'.$video_id.'?version='.$version .
            ($autohide !== '2' ? '&amp;autohide=' . $autohide : '') .
            ((bool) $autoplay ? '&amp;autoplay=1' : '') .
            (!empty($cc_load_policy) ? '&amp;cc_load_policy=' . $cc_load_policy : '') .
            ($color != 'red' ? '&amp;color=' . $color : '') .
            (!(bool) $controls ? '&amp;controls=0' : '') .
            ((bool) $disablekb ? '&amp;disablekb=1' : '') .
            ((bool) $enablejsapi ? '&amp;enablejsapi=1' : '') .
            ((bool) $end ? '&amp;end=' . $end : '') .
            ((bool) $fs ? '&amp;fs=1' : '') .
            ($iv_load_policy === '3' ? '&amp;iv_load_policy=' . $iv_load_policy : '') .
            //(!empty($list) ? '&amp;list=' . $list : '') .
            //(!empty($listType) ? '&amp;listType=' . $listType : '') .
            ((bool) $loop ? '&amp;loop=1' : '') .
            ((bool) $modestbranding ? '&amp;modestbranding=1' : '') .
            (!empty($origin) ? '&amp;origin=' . $origin : '') .
            (!empty($playerapiid) ? '&amp;playerapiid=' . $playerapiid : '') .
            (!empty($playlist) ? '&amp;playlist=' . $playlist : '') .
            (!(bool) $rel ? '&amp;rel=0' : '') .
            (!(bool) $showinfo ? '&amp;showinfo=0' : '') .
            (!empty($start) ? '&amp;start=' . $start : '') .
            ($theme != 'dark' ? '&amp;theme=' . $theme : '') .
            // Deprecated parameters only used in the AS2 Player API
            ((bool) $border ? '&amp;border=1' : '') .
            ($color1 != 'b1b1b1' ? '&amp;color1=0x' . $color1 : '') .
            ($color2 != 'cfcfcf' ? '&amp;color2=0x' . $color2 : '') .
            ((bool) $egm ? '&amp;egm=1' : '') .
            ((bool) $hd ? '&amp;hd=1' : '') .
            (!(bool) $showsearch ? '&amp;showsearch=0' : '');

        $flash_only_url     = sprintf($video_url, 'v') . '&amp;hl='.$this->hl;
        $flash_or_html5_url = sprintf($video_url, 'embed') . '&amp;wmode=transparent';
        
        $watch_on_youtube = '<a href="http://www.youtube.com/watch?v='.$video_id.'" target="_blank" title="Watch on YouTube">Watch this video on YouTube</a>.';
        $class_or_style   = $this->is_feed ? 'style="background-color:#000;display:block;margin-bottom:0;max-width:100%;"' : 'class="yp"';
        $fs               = (bool) $fs;

        $old_embed_code = '<object width="'.$width.'" height="'.$height.'" '.$class_or_style.' title="'.$title.'">' .
                                '<param name="movie" value="'.$flash_only_url.'">' .
                                ($fs ? '<param name="allowfullscreen" value="true">' : '') .
                                '<param name="allowscriptaccess" value="always">' .
                                '<param name="wmode" value="transparent">' .
                                '<embed src="'.$flash_only_url.'" width="'.$width.'" height="'.$height.'" '.$class_or_style.' type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always"' . ($fs ? ' allowfullscreen="true"' : '') . '>' .
                                ($this->is_feed ? '' : 
                                '<span style="display:block;margin-top:15px;">The Adobe Flash Player is required for video playback.<br><a href="http://get.adobe.com/flashplayer/" title="Install from Adobe">Get the latest Flash Player</a> or '.$watch_on_youtube.'</span>') .
                          '</object>';
        $iframe_code    = '<iframe src="'.$flash_or_html5_url.'" width="'.$width.'" height="'.$height.'" '.$class_or_style.' title="'.$title.'" frameborder="0" allowfullscreen></iframe>';

        // AS2 or Old embed code ?
        $OEC = (int) $version == 2 || $embedcode == 'old' ? true : false;

        // HTML OUTPUT
        $output = '';

        // Feeds
        if ($this->is_feed) {
            $output .= ($OEC ? $old_embed_code : $iframe_code).'<p style="font-size:11px;margin-top:0;">'.$watch_on_youtube.'</p>';
        // Mobile Browsers
        } else if ($this->is_mobile) {
            // Embedded Youtube player
            if ($this->ios || $this->android) {
                $link = 'vnd.youtube:'.$video_id;
            // Link to Youtube's mobile site
            } else {
                $link = 'http://m.youtube.com/watch?v='.$video_id;
            }
            $output .= '<'.$wtag.' class="youtube_sc mobile '.$class.'">' .
                            '<a href="'.$link.'" title="'.$title.'">' .
                                '<img src="http://i.ytimg.com/vi/'.$video_id.'/hqdefault.jpg" />' .
                                '<span class="play-button-outer" title="Click to play video">' .
                                    '<span class="play-button"></span>' .
                                '</span>' .
                            '</a>' .
                       '</'.$wtag.'>';
        // Desktop Browsers
        } else {
            $output .= '<'.$wtag.' class="youtube_sc '.$class.($static ? '' : ' fluid '.$ratio).'"'.($static ? ' style="width:'.$width.'px;height:'.$height.'px;"' : '').'>';
            $output .= !$static ? '<'.$itag.' class="inner block">' : '';

            $output .= $OEC ? $old_embed_code :
                '<noscript>'
                    .'<style type="text/css">.youtube_sc iframe.yp{display:none;}</style>'
                    .$old_embed_code
                .'</noscript>'
                .$iframe_code;

            $output .= !$static ? '</'.$itag.'>' : '';
            $output .= '</'.$wtag.'>';
        }

        return $output;
    }


    function get_video_id($url)
    {
        if (preg_match('#^https?\://(?:(?:[a-z0-9-_\.]+\.|)youtube\.com/(?:watch\?v=|v/|embed/)|youtu\.be/)([a-z0-9-_]+)|^([a-z0-9-_]+)$#i', $url, $matches) > 0) {
            return $matches[empty($matches[1]) ? 2 : 1];
        }
        // default video ID on error
        return 'QRS8MkLhQmM';
    }


    function get_server_host($url)
    {
        if (preg_match('/^http\:\/\/([a-zA-Z0-9\-\_\.]+\.)(?:youtube\.com|youtu\.be)/i', $url, $matches)) {
            return $matches[1];
        }

        return 'www.';
    }


    function get_hl()
    {
        $this->hl = 'en_US';

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && ! empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $hl_codes = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

            if (count($hl_codes) > 0) {
                $hl_codes = explode(';', $hl_codes[0]);
                $hl_code = strtolower($hl_codes[0]);

                $hl_codes = array(
                    'cs_CZ' => array('cs'), // CZECH
                    'de_DE' => array('de','de-de','de-at','de-lu','de-li','de-ch'), // GERMAN
                    'nl_NL' => array('nl','nl-be'), // DUTCH
                    'en_GB' => array('en-gb'), // ENGLISH UK
                    'es_ES' => array('es','es-es','es-ar'), // SPANISH (SPAIN & ARGENTINA)
                    'es_MX' => array('es-mx','es-bo','es-cl','es-co','es-cr','es-do','es-ec','es-sv','es-gt','es-hn'), // SPANISH (MEXICO aka latin)
                    'fr_FR' => array('fr','fr-fr','fr-ca','fr-be','fr-lu','fr-mc','fr-ch'), // FRENCH
                    'it_IT' => array('it','it-ch'), // ITALIAN
                    'ja_JP' => array('ja'), // JAPANESE
                    'ko_KR' => array('ko','ko-kp','ko-kr'), // KOREAN
                    'pl_PL' => array('pl'), // POLISH
                    'pt_PT' => array('pt'), // PORTUGUESE
                    'pt_BR' => array('pt-br'), // PORTUGUESE (BRAZIL)
                    'ru_RU' => array('ru','ru-mo'), // RUSSIAN
                    'sv_SE' => array('sv','sv-fi','sv-sv'), // SWEDISH
                    'zh_TW' => array('zh','zh-tw','zh-hk','zh-cn','zh-sg') // CHINESE
                );

                foreach ($hl_codes as $k => $v) {
                    if (in_array($hl_code, $v)) {
                        $this->hl = $k;
                        return;
                    }
                }
            }
        }
    }


    // Detects most mobile phones/tablets.
    // Based on Mobile Detector plugin: http://wordpress.org/extend/plugins/mobile-detector/
    function detect_mobile()
    {
        $regexps = array(
            // Devices
            'device' => array (
                'iphone' => 'iphone',
                'ipad' => 'ipad',
                'kindle' => 'kindle|silk',
            ),
            // OSs
            'os' => array(
                'ios' => 'ip(hone|ad|od)',
                'android' => 'android',
                'webos' => '(web|hpw)os',
                'palmos' => 'palm(\s?os|source)',
                'windows' => 'windows (phone|ce)',
                'symbian' => 'symbian(\s?os|)|symbos',
                'bbos' => 'blackberry(.*?version\/\d+|\d+\/\d+)',
                'bada' => 'bada'
            ),
            // Browsers
            'browser' => array(
                'opera_mobile' => 'opera (mobi|tablet|mini)', // Opera Mobile or Mini
                'webkit_mobile' => '(android|nokia|symbianos|webos|hpwos|blackberry|bolt|silk).*?webkit|webkit.*?(mobile|crmo|kindle|bolt|skyfire|ninesky|dolfin|iris|teashark|tear)|atomicbrowser', // Webkit mobile
                'ff_mobile' => 'fennec|(mobile|tablet).*?firefox', // Firefox mobile https://developer.mozilla.org/en/Gecko_user_agent_string_reference
                'ie_mobile' => 'iemobile|windows (ce|phone)|(zune|xbl)wp\d{1}', // IE mobile
                'netfront' => 'netfront|blazer|jasmine', // Netfront
                'uc_browser' => 'ucweb' // UC browser
            ),
            // UA Fallbacks
            'fallback' => array(
                 'other' => 'nokia|motorola|sony|ericsson|lge?(-|;|\/|\s)|htc|samsung|asus|mobile|phone|tablet|pocket|wap|wireless|up\.browser|up\.link|j2me|midp|cldc|kddi|mmp|obigo|novarra|teleca|openwave|uzardweb|pre\/|hiptop|minimo|avantgo|plucker|xiino|elaine|vodafone|sprint|o2'
            )
        );

        // User Agent detection
        $ua = '';

        // Header names taken from DeviceAtlas
        $ua_headers = array(
            'HTTP_X_DEVICE_USER_AGENT',
            'HTTP_X_ORIGINAL_USER_AGENT',
            'HTTP_X_OPERAMINI_PHONE_UA',
            'HTTP_X_SKYFIRE_PHONE',
            'HTTP_X_BOLT_PHONE_UA',
            'HTTP_USER_AGENT'
        );

        foreach ($ua_headers as $header) {
            if (array_key_exists($header, $_SERVER)) {
                $ua = $_SERVER[$header];
                break;
            }
        }

        $isset_ua = !empty($ua); 

        foreach ($regexps as $group) {
            foreach ($group as $name => $regex) {
                // If match, add it to $dvars, set to true and check next group
                if ($isset_ua && preg_match('/'.$regex.'/i', $ua)) {
                    $this->$name = $this->is_mobile = true;
                    break;
                }
            }
        }

        // HTTP HEADERS fallback detection
        if ($this->is_mobile === false) {
            if (false !== strpos($_SERVER['HTTP_ACCEPT'], 'text/vnd.wap.wml')
                || false !== strpos($_SERVER['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml')
                || isset($_SERVER['HTTP_X_WAP_PROFILE'])
                || isset($_SERVER['HTTP_PROFILE'])
            ) {
                $this->is_mobile = true;
            }
        }
    }

    
    // TinyMCE Button

    // Set up our TinyMCE button
    function setup_tinymce_button()
    {
        if (get_user_option('rich_editing') == 'true' && current_user_can('edit_posts')) {
            add_action('admin_print_scripts', array($this, 'output_tinymce_dialog_vars'));
            add_filter('mce_external_plugins', array($this, 'add_tinymce_button_script'));
            add_filter('mce_buttons', array($this, 'register_tinymce_button'));
        }
    }


    // Register our TinyMCE button
    function register_tinymce_button($buttons) {
        array_push($buttons, '|', 'YoutubeShortcodeButtonMargenn');
        return $buttons;
    }


    // Register our TinyMCE Script
    function add_tinymce_button_script($plugin_array) {
        $plugin_array['YoutubeShortcodeMargenn'] = plugins_url('tinymcebutton.js', __FILE__);
        return $plugin_array;
    }


    function output_tinymce_dialog_vars()
    {
        $data = array(
            'pluginVersion' => $this->plugin_version,
            'includesUrl' => includes_url(),
            'pluginsUrl' => plugins_url()
        );

        ?>
        <script type="text/javascript">
        // <![CDATA[
            window.youtubeShortcodeDialogData = <?php echo json_encode($data); ?>;
        // ]]>
        </script>
        <?php
    }

}

// Create just one instance per request
new Youtube_shortcode();

endif;