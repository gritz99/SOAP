<?php

require_once( dirname(__FILE__) . '/admin/cheezcap.php');
require_once( dirname(__FILE__) . '/core/loader.php');

/** Tell WordPress to run cc_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'cc_setup' );
if ( ! function_exists( 'cc_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * To override cc_setup() in a child theme, add your own cc_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 * @uses $content_width To set a content width according to the sidebars.
 * @uses BP_DISABLE_ADMIN_BAR To disable the admin bar if set to disabled in the themesettings.
 *
 */
function cc_setup() {
    global $cap, $content_width;

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    // This theme uses post thumbnails
    if ( function_exists( 'add_theme_support' ) ) {
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 222, 160, true );
        add_image_size( 'slider-top-large', 1006, 250, true  );
        add_image_size( 'slider-large', 990, 250, true  );
        add_image_size( 'slider-middle', 756, 250, true  );
        add_image_size( 'slider-thumbnail', 80, 50, true );
        add_image_size( 'post-thumbnails', 222, 160, true  );
        add_image_size( 'single-post-thumbnail', 598, 372, true );
    }

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Make theme available for translation
    // Translations can be filed in the /languages/ directory
    load_theme_textdomain( 'cc', get_template_directory() . '/languages' );

    $locale = get_locale();
    $locale_file = get_template_directory() . "/languages/$locale.php";
    if ( is_readable( $locale_file ) )
        require_once( $locale_file );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'menu_top' => __( 'Header top menu', 'cc' ),
        'primary'  => __( 'Header bottom menu', 'cc' ),
    ) );
    
    // This theme allows users to set a custom background
    if($cap->add_custom_background == true){
        add_theme_support('custom-background');
    }
    // Your changeable header business starts here
    define( 'HEADER_TEXTCOLOR', '888888' );
    
    // No CSS, just an IMG call. The %s is a placeholder for the theme template directory URI.
    define( 'HEADER_IMAGE', '%s/images/default-header.png' );

    // The height and width of your custom header. You can hook into the theme's own filters to change these values.
    // Add a filter to cc_header_image_width and cc_header_image_height to change these values.
    define( 'HEADER_IMAGE_WIDTH', apply_filters( 'cc_header_image_width', 1000 ) );
    define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'cc_header_image_height', 233 ) );


    // Add a way for the custom header to be styled in the admin panel that controls
    // custom headers. See cc_admin_header_style(), below.
    if($cap->add_custom_image_header == true){
        $defaults = array(
            /*'default-image'          => '',
            'random-default'         => false,
            'width'                  => 0,
            'height'                 => 0,
            'flex-height'            => false,
            'flex-width'             => false,
            'default-text-color'     => '',
            'header-text'            => true,
            'uploads'                => true,*/
//            'wp-head-callback'       => 'cc_admin_header_style',
//            'admin-head-callback'    => 'cc_header_style',
            'admin-preview-callback' => 'cc_admin_header_image',
        );
        add_theme_support('custom-header',$defaults);
        //add_custom_image_header( 'cc_header_style', 'cc_admin_header_style', 'cc_admin_header_image' );
    }
    
    // Define Content with
    $content_width  = "670";
    if($cap->sidebar_position == "left and right"){
        $content_width  = "432";
    }
    
    // Define disable the admin bar
    if($cap->bp_login_bar_top == 'off' || $cap->bp_login_bar_top == __('off','cc') ) {
        define( 'BP_DISABLE_ADMIN_BAR', true );
    } 
}
endif;

if ( ! function_exists( 'cc_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in cc_setup().
 *
 */
function cc_admin_header_image() { ?>
    <div id="headimg">
        <?php
        if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
            $style = ' style="display:none;"';
        else
            $style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
        ?>
        <h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
        <div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
        <img src="<?php esc_url ( header_image() ); ?>" alt="" />
    </div>
<?php }
endif;

add_filter('widget_text', 'do_shortcode');
add_action('widgets_init', 'cc_widgets_init');
function cc_widgets_init(){
    register_sidebars( 1,
        array(
            'name'          => 'sidebar right',
            'id'            => 'sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'sidebar left',
            'id'            => 'leftsidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    ### Add Sidebars
    register_sidebars( 1,
        array(
            'name'          => 'header full width',
            'id'            => 'headerfullwidth',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'header left',
            'id'            => 'headerleft',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'header center',
            'id'            => 'headercenter',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'header right',
            'id'            => 'headerright',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'footer full width',
            'id'            => 'footerfullwidth',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'footer left',
            'id'            => 'footerleft',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'footer center',
            'id'            => 'footercenter',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'footer right',
            'id'            => 'footerright',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'member header',
            'id'            => 'memberheader',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'member header left',
            'id'            => 'memberheaderleft',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'member header center',
            'id'            => 'memberheadercenter',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'member header right',
            'id'            => 'memberheaderright',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'member sidebar left',
            'id'            => 'membersidebarleft',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'member sidebar right',
            'id'            => 'membersidebarright',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'group header',
            'id'            => 'groupheader',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'group header left',
            'id'            => 'groupheaderleft',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'group header center',
            'id'            => 'groupheadercenter',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'group header right',
            'id'            => 'groupheaderright',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'group sidebar left',
            'id'            => 'groupsidebarleft',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => 'group sidebar right',
            'id'            => 'groupsidebarright',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 15,
        array(
            'name'          => 'shortcode %1$s',
            'id'            => 'shortcode',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );

}
if($cap->buddydev_search == true && defined('BP_VERSION') && function_exists('bp_is_active')) {
        
    //* Add these code to your functions.php to allow Single Search page for all buddypress components*/
    //    Remove Buddypress search drowpdown for selecting members etc
    add_filter("bp_search_form_type_select", "cc_remove_search_dropdown"  );
    function cc_remove_search_dropdown($select_html){
        return '';
    }
    
    remove_action( 'init', 'bp_core_action_search_site', 5 );//force buddypress to not process the search/redirect
    add_action( 'init', 'cc_bp_buddydev_search', 10 );// custom handler for the search
    
    function cc_bp_buddydev_search(){
    global $bp;
        if ( $bp->current_component == BP_SEARCH_SLUG )//if thids is search page
            bp_core_load_template( apply_filters( 'bp_core_template_search_template', 'search-single' ) );//load the single searh template
    }
    add_action("advance-search","cc_show_search_results",1);//highest priority
    
    /* we just need to filter the query and change search_term=The search text*/
    function cc_show_search_results(){
        //filter the ajaxquerystring
         add_filter("bp_ajax_querystring","cc_global_search_qs",100,2);
    }
    
    //show the search results for member*/
    function cc_show_member_search(){ ?>
        <div class="memberss-search-result search-result">
            <h2 class="content-title"><?php _e("Members Results","cc");?></h2>
            <?php locate_template( array( 'members/members-loop.php' ), true ) ;  ?>
            <?php global $members_template;
            if($members_template->total_member_count>1):?>
                <a href="<?php echo bp_get_root_domain().'/'.BP_MEMBERS_SLUG.'/?s='.$_REQUEST['search-terms']?>" ><?php echo sprintf(__("View all %d matched Members",'cc'),$members_template->total_member_count);?></a>
            <?php endif; ?>
        </div>
        <?php    
    }
    
    //Hook Member results to search page
    add_action("advance-search","cc_show_member_search",10); //the priority defines where in page this result will show up(the order of member search in other searchs)
    function cc_show_groups_search(){?>
        <div class="groups-search-result search-result">
        <h2 class="content-title"><?php _e("Group Search","cc");?></h2>
        <?php locate_template( array('groups/groups-loop.php' ), true ) ;  ?>
        
        <a href="<?php echo bp_get_root_domain().'/'.BP_GROUPS_SLUG.'/?s='.$_REQUEST['search-terms']?>" ><?php _e("View All matched Groups","cc");?></a>
    </div>
        <?php
     //endif;
      }
    
    //Hook Groups results to search page
     if(bp_is_active( 'groups' ))
        add_action("advance-search","cc_show_groups_search",10);
    
    /**
     *
     * Show blog posts in search
     */
    function cc_show_site_blog_search(){
        ?>
     <div class="blog-search-result search-result">
     
      <h2 class="content-title"><?php _e("Blog Search","cc");?></h2>
       
       <?php locate_template( array( 'search-loop.php' ), true ) ;  ?>
       <a href="<?php echo bp_get_root_domain().'/?s='.$_REQUEST['search-terms']?>" ><?php _e("View All matched Posts","cc");?></a>
    </div>
       <?php
      }
    
    //Hook Blog Post results to search page
     add_action("advance-search","cc_show_site_blog_search",10);
    
    //show forums search
    function cc_show_forums_search(){
        ?>
     <div class="forums-search-result search-result">
       <h2 class="content-title"><?php _e("Forums Search","cc");?></h2>
      <?php locate_template( array( 'forums/forums-loop.php' ), true ) ;  ?>
      <a href="<?php echo bp_get_root_domain().'/'.BP_FORUMS_SLUG.'/?s='.$_REQUEST['search-terms']?>" ><?php _e("View All matched forum posts","cc");?></a>
    </div>
      <?php
      }
    
    //Hook Forums results to search page
    if ( bp_is_active( 'forums' ) && bp_is_active( 'groups' ) && ( function_exists( 'bp_forums_is_installed_correctly' )))
        add_action("advance-search","cc_show_forums_search",20);
    
    
    //show blogs search result
    
    function cc_show_blogs_search(){
    
    if(!is_multisite())
        return;
        
        ?>
      <div class="blogs-search-result search-result">
      <h2 class="content-title"><?php _e("Blogs Search","cc");?></h2>
      <?php locate_template( array( 'blogs/blogs-loop.php' ), true ) ;  ?>
      <a href="<?php echo bp_get_root_domain().'/'.BP_BLOGS_SLUG.'/?s='.$_REQUEST['search-terms']?>" ><?php _e("View All matched Blogs","cc");?></a>
     </div>
      <?php
      }
    
    //Hook Blogs results to search page if blogs comonent is active
     if(bp_is_active( 'blogs' ))
        add_action("advance-search","cc_show_blogs_search",10);
    
    
     //modify the query string with the search term
    function cc_global_search_qs(){
        if(empty($_REQUEST['search-terms']))
            return;
    
        return "search_terms=".$_REQUEST['search-terms'];
    }
    
    function cc_is_advance_search(){
    global $bp;
    if($bp->current_component == BP_SEARCH_SLUG)
        return true;
    return false;
    }
    remove_action( 'bp_init', 'bp_core_action_search_site', 7 );
        
}
//load current displaymode template - loop-list.php or loop-grid.php
function cc_get_displaymode($object){
    $_BP_COOKIE = &$_COOKIE;
    if ( isset( $_BP_COOKIE['bp-' . $object . '-displaymode'])) {
        get_template_part( "{$object}/{$object}-loop", $_BP_COOKIE['bp-' . $object . '-displaymode']);        
    }
    else{
        get_template_part( "{$object}/{$object}-loop",'list');    
    }
    
}
//check if displaymode grid
function cc_is_displaymode_grid($object){
    $_BP_COOKIE = &$_COOKIE;
    return ( isset( $_BP_COOKIE['bp-' . $object . '-displaymode']) && $_BP_COOKIE['bp-' . $object . '-displaymode'] == 'grid');
}

/**
 * Get pro version
 */
function cc_get_pro_version(){
   $pro_enabler = get_template_directory() . DIRECTORY_SEPARATOR . '_pro' . DIRECTORY_SEPARATOR . 'pro-enabler.php';
   if(file_exists($pro_enabler)){
       require_once $pro_enabler;
   }
}
/**
 * Get Admin styles
 */
function cc_add_admin_styles(){
    wp_enqueue_style('cc_admin', get_template_directory_uri() . '/_inc/css/admin.css');
}
add_action('admin_init', 'cc_add_admin_styles');

/**
 * Fix ...[]
 */
function cc_replace_read_more($text){
    return ' <a class="read-more-link" href="'. get_permalink() . '"><br />' .  __("read more...","cc") . '</a>';
}
add_filter('excerpt_more', 'cc_replace_read_more');

/**
 * Display the rate for us message
 */
function cc_add_rate_us_notice(){
    $hide_message = get_option('cc_hide_activation_message', false);
    if(!$hide_message){
        echo '<div class="update-nag cc-rate-it">
                '.cc_get_add_rate_us_message().'<a href="#" class="dismiss-activation-message">'.__('Dismiss', 'cc'). '</a>
            </div>';
    }
}

function cc_get_add_rate_us_message(){
    return 'Please rate for <a class="go-to-wordpress-repo" href="http://wordpress.org/extend/themes/custom-community" target="_blank">Custom Community</a> theme on WordPress.org';
}
/**
 * Ajax processor for show/hide Please rate for
 */
add_action('wp_ajax_dismiss_activation_message', 'cc_dismiss_activation_message');
function cc_dismiss_activation_message(){
    echo update_option('cc_hide_activation_message', $_POST['value']);
    die();
}











/*
 =============================================
 Functions added by ICAD for ARG use
 =============================================
*/


// DEFAULT VARIABLES USED IN SCRIPTS BELOW
$arg_goals['comments'] = 10;   // number of comments to gain level 1
$arg_goals['invites'] = 1;   // number of invites to gain level 2
$arg_goals['attends'] = 3;   // number of events to gain level 3
$arg_goals['hosts'] = 1;   // number of events to gain level 4
$arg_goals['acts'] = 1;   // number of events to gain level 5


/*
$bp->bp_nav[200] = array(
	'name' => 'Progress',
	'slug' => 'progress',
	'link' => site_url(). '/user-goals-single',
	'css_id' => 'general',
	'show_for_displayed_user' => 1,
	'position' => 200,
	'screen_function' => 'bp_settings_screen_general',
	'default_subnav_slug' => 'just-me'
);
*/

// WHITELIST CHECK
//==================================================================================
function arg_whitelist_check ($wl_email) {
	global $cap;
	$return = false;
	if (strlen($cap->bp_registration_whitelist) > 0) {
		$filter_list = explode(',', $cap->bp_registration_whitelist);
		foreach ($filter_list as $filter) {
			$filter = preg_replace('/\*/', '.+', trim($filter));
			if (preg_match('/^'.$filter.'$/', $wl_email)) $return = true;
		}
	}
	return $return;
}



// READ AND WRITE USER ACHIEVEMENT DATA
//==================================================================================
function arg_get_user_achievement_datum ($uid, $key, $proc='none') {
	global $wpdb;
	$q['levels'] = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id='$uid' AND meta_key='arg_user_achievement_levels';";
	$q['events_attend'] = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id='$uid' AND meta_key='arg_user_achievement_events_attend';";
	$q['events_host'] = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id='$uid' AND meta_key='arg_user_achievement_events_host';";
	$q['events_act'] = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id='$uid' AND meta_key='arg_user_achievement_events_act';";
	$q['invited'] = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id='$uid' AND meta_key='arg_user_achievement_invited';";
	$q['count_comments'] = "SELECT COUNT(*) FROM wp_comments WHERE user_id='$uid' AND comment_approved=1;";
	
	$return = $wpdb->get_var($q[$key]);
	
	if ($proc == 'explode') $return = explode(':', $return);
	
	return $return;
}

function arg_get_user_achievement_data ($key, $data=array()) {
	global $wpdb;
	$q['event_data'] = 'SELECT event_id, event_name, event_start_date FROM wp_em_events ORDER BY event_start_date ASC;';
	
	$return = $wpdb->get_results($q[$key]);
	
	return $return;
}

function arg_put_user_achievement_data ($uid, $key, $value, $proc='none') {
	global $wpdb;

	if ($proc == 'implode') $value = implode(':', $value);
	
	$q['levels_add'] = "INSERT INTO $wpdb->usermeta VALUES ('', '$uid', 'arg_user_achievement_levels', '$value');";
	$q['levels_upd'] = "UPDATE $wpdb->usermeta SET meta_value='$value' WHERE user_id='$uid' AND meta_key='arg_user_achievement_levels';";
	
	$q['events_attend_add'] = "INSERT INTO $wpdb->usermeta VALUES ('', '$uid', 'arg_user_achievement_events_attend', '$value');";
	$q['events_attend_upd'] = "UPDATE $wpdb->usermeta SET meta_value='$value' WHERE user_id='$uid' AND meta_key='arg_user_achievement_events_attend';";
	
	$q['events_host_add'] = "INSERT INTO $wpdb->usermeta VALUES ('', '$uid', 'arg_user_achievement_events_host', '$value');";
	$q['events_host_upd'] = "UPDATE $wpdb->usermeta SET meta_value='$value' WHERE user_id='$uid' AND meta_key='arg_user_achievement_events_host';";
	
	$q['events_act_add'] = "INSERT INTO $wpdb->usermeta VALUES ('', '$uid', 'arg_user_achievement_events_act', '$value');";
	$q['events_act_upd'] = "UPDATE $wpdb->usermeta SET meta_value='$value' WHERE user_id='$uid' AND meta_key='arg_user_achievement_events_act';";
	
	$q['invited_add'] = "INSERT INTO $wpdb->usermeta VALUES ('', '$uid', 'arg_user_achievement_invited', '$value');";
	$q['invited_upd'] = "UPDATE $wpdb->usermeta SET meta_value='$value' WHERE user_id='$uid' AND meta_key='arg_user_achievement_invited';";


	$test = arg_get_user_achievement_datum($uid, $key);
	if ($test == '') $return = $wpdb->query($q[$key."_add"]);
	else $return = $wpdb->query($q[$key."_upd"]);

	return $return;
}


// UPDATE USER STATISTICS
//==================================================================================
// will update the achievement statistics of the current user
function arg_user_achievement_levels_update () {

	global $arg_goals;
	global $current_user;
	if ($current_user->ID == 0) return false;

	// first update list of invites
	$users = get_users();
	// create a list of invitee ID => inviter e-mail or user name
	foreach ($users as $user) {
		if (xprofile_get_field_data('6',$user->data->ID)) $who_invited[$user->data->ID] = xprofile_get_field_data('6',$user->data->ID); // invitee ID => inviter e-mail or user name
		$users_by_email[$user->data->user_email] = $user->data->ID;
		$users_by_name[$user->data->user_nicename] = $user->data->ID;
	}
	// transform the data to a list of inviter ID to array of invitee IDs
	if (count($who_invited) > 0) foreach ($who_invited as $invitee=>$inviter_tag) {
		$inviter_id = 0;
		if ($users_by_email[$inviter_tag]) $inviter_id = $users_by_email[$inviter_tag];
		elseif ($users_by_name[$inviter_tag]) $inviter_id = $users_by_name[$inviter_tag];
		// check to see if inviter is invitee (considered cheating)
		if ($inviter_id != 0 and $inviter_id != $invitee) {
			if (!is_array($invited[$inviter_id])) $invited[$inviter_id] = array();
			array_push($invited[$inviter_id], $invitee);
		}
	}
	// save lists to user meta for each inviter
	if (count($invited) > 0) foreach ($invited as $inviter=>$invite_list) arg_put_user_achievement_data ($inviter, 'invited', $invite_list, 'implode');

	$levels = arg_get_user_achievement_datum ($current_user->ID, 'levels', 'explode');
	$level_count = 0;
	$levels[1] = 0;
	$comment_count = arg_get_user_achievement_datum ($current_user->ID, 'count_comments');
	if ($comment_count >= $arg_goals['comments']) {
		$levels[1] = 1;
		$level_count++;
	}
	// used array from above to determine level 2
	$levels[2] = 0;
	$invite_count = 0;
	if (count($invited[$current_user->ID]) > 0) {
		foreach ($invited[$current_user->ID] as $invitee) {
			$comment_count = arg_get_user_achievement_datum ($invitee, 'count_comments');
			if ($comment_count >= $arg_goals['comments']) $invite_count++;
		}
		if ($invite_count >= $arg_goals['invites']) {
			$levels[2] = 1;
			$level_count++;
		}
	}
	// the following levels are updated during event insertion on admin pages
	// they are only counted here for the total
	if ($levels[3]) $level_count++;
	if ($levels[4]) $level_count++;
	if ($levels[5]) $level_count++;
	
	$levels[0] = $level_count;
	if ($levels[0] == 0) $levels = array(0, 0, 0, 0, 0, 0);
	arg_put_user_achievement_data ($current_user->ID, 'levels', $levels, 'implode');
	return true;
}
add_action('bp_head', 'arg_user_achievement_levels_update');



// GET DATA FOR USER ACHIEVEMENT STATISTICS
//==================================================================================
function arg_print_user_achievement_stats ($atts) {

	extract( shortcode_atts( array('mode'=>'all'), $atts) );

	global $arg_goals;
	if ($mode == 'single') global $current_user;

	$user_data = array();
	
	// collect a list of all users
	// also create a list of users defined by user ID
	$users = get_users();
	$users_by_id = array();
	$who_invited = array();
	foreach ($users as $user) {
		if (isset($user->data->user_nicename)) {
			$user_data[$user->data->user_nicename]['uid'] = $user->data->ID;
			$user_data[$user->data->user_nicename]['name'] = $user->data->user_nicename;
			$user_data[$user->data->user_nicename]['display'] = $user->data->display_name;
			$user_data[$user->data->user_nicename]['email'] = $user->data->user_email;
			$user_data[$user->data->user_nicename]['db_levels'] = arg_get_user_achievement_datum($user->data->ID, 'levels');
			$user_data[$user->data->user_nicename]['events_attend'] = arg_get_user_achievement_datum($user->data->ID, 'events_attend');
			$user_data[$user->data->user_nicename]['events_host'] = arg_get_user_achievement_datum($user->data->ID, 'events_host');
			$user_data[$user->data->user_nicename]['events_act'] = arg_get_user_achievement_datum($user->data->ID, 'events_act');
			$user_data[$user->data->user_nicename]['invited'] = arg_get_user_achievement_datum($user->data->ID, 'invited');
			$user_data[$user->data->user_nicename]['comments_num'] = arg_get_user_achievement_datum ($user->data->ID, 'count_comments');
			$users_by_id[$user->data->ID] = $user->data->user_nicename;
			$users_by_email[$user->data->user_email] = $user->data->user_nicename;
			if (xprofile_get_field_data('7',$user->data->ID)) $user_data[$user->data->user_nicename]['sid'] = xprofile_get_field_data('7',$user->data->ID);
			// since we are running through each achievement for a user, we will confirm the individual levels as we go and reset database
			// db_levels above is a recording of which levels were in the database.
			$user_data[$user->data->user_nicename]['levels'] = array(0, 0, 0, 0, 0, 0);
		}
	}

	global $wpdb;

	// create a list of posts defined by post ID
	$posts = get_posts(array('posts_per_page'=>777));
	$posts_by_id = array();
	foreach ($posts as $post) {
		$posts_by_id[$post->ID] = $post->post_title;
	}

	// create a list of events defined by event ID
	$events = arg_get_user_achievement_data ('event_data');
	$events_by_id = array();
	foreach ($events as $event) {
		$events_by_id[$event->event_id] = array('name'=>$event->event_name, 'date'=>$event->event_start_date);
	}

	// gather data on user comments
	$comments = get_comments();
	foreach ($comments as $comment) {
		if ($comment->comment_approved != 1) continue;
		if (!$users_by_id[$comment->user_id]) continue;
		$user_name = $users_by_id[$comment->user_id];
		$user_data[$user_name]['comments_by_post'][$comment->comment_post_ID]['title'] = $posts_by_id[$comment->comment_post_ID];	
		$user_data[$user_name]['comments_by_post'][$comment->comment_post_ID]['comments']++;	
		$votes = $wpdb->get_results("SELECT vote FROM wp_gdsr_votes_log WHERE id=$comment->comment_ID");
		foreach ($votes as $vote) {
			if ($vote->vote == 1) $user_data[$user_name]['comments_by_post'][$comment->comment_post_ID]['likes']++;
			if ($vote->vote == -1) $user_data[$user_name]['comments_by_post'][$comment->comment_post_ID]['dislikes']++;
		}
	}


	// process information under each user
	foreach ($user_data as $key=>$user) {
		if ($user['comments_num'] >= $arg_goals['comments']) {
			$user_data[$key]['levels'][1] = 1;
		}
		if ($user['invited']) {
			$array = explode(':', $user['invited']);
			$user_data[$key]['confirm_invited_num'] = 0;
			foreach ($array as $id) {
				$user_data[$key]['invited_by_user'][$id] = $user_data[$users_by_id[$id]]['display']. ' ('. $users_by_id[$id]. ')';
				if ($user_data[$users_by_id[$id]]['comments_num'] >= $arg_goals['comments']) $user_data[$key]['confirm_invited_num']++;
			}
			if ($user_data[$key]['confirm_invited_num'] >= $arg_goals['invites']) $user_data[$key]['levels'][2] = 1;
		}
		if ($user['events_attend']) {
			$array = explode(':', $user['events_attend']);
			foreach ($array as $id)
				$user_data[$key]['events_attend_by_user'][$id] = $events_by_id[$id]['name']. ' ('. $events_by_id[$id]['date']. ')';
			if (count($array) >= $arg_goals['attends']) $user_data[$key]['levels'][3] = 1;
		}
		if ($user['events_host']) {
			$array = explode(':', $user['events_host']);
			foreach ($array as $id)
				$user_data[$key]['events_host_by_user'][$id] = $events_by_id[$id]['name']. ' ('. $events_by_id[$id]['date']. ')';
			if (count($array) >= $arg_goals['hosts']) $user_data[$key]['levels'][4] = 1;
		}
		if ($user['events_act']) {
			$array = explode(':', $user['events_act']);
			foreach ($array as $id)
				$user_data[$key]['events_act_by_user'][$id] = $events_by_id[$id]['name']. ' ('. $events_by_id[$id]['date']. ')';
			if (count($array) >= $arg_goals['acts']) $user_data[$key]['levels'][5] = 1;
		}
		$user_data[$key]['levels'][0] = $user_data[$key]['levels'][1] + $user_data[$key]['levels'][2] + $user_data[$key]['levels'][3] + $user_data[$key]['levels'][4] + $user_data[$key]['levels'][5];
		arg_put_user_achievement_data($user_data[$key]['uid'], 'levels', $user_data[$key]['levels'], 'implode');
	}


	if ($mode == 'single') $user_collection = array($current_user->data->user_nicename => $user_data[$current_user->data->user_nicename]);
	else $user_collection = $user_data;

	// have to make the local variables here global so they can be used in enclosed funcitons
	$GLOBALS['users_by_id'] = $users_by_id;
	$GLOBALS['user_data'] = $user_data;
	

	//---------------------------------------------
	function create_arg_user_stats_goals($level, $title, $requirements, $list, $list_items) {
?>
	<div class="arg_user_stats_goals">
		<h5>Goal: <?php print $title; ?> <div class="arg_user_stats_star <?php print ($level > 0 ? 'star_on' : 'star_off'); ?>"></div></h5>
		<div class="arg_user_stats_goals_head">
			<div class="arg_user_stats_goals_requirements"><?php print $requirements; ?></div>
		</div>
		<div class="arg_user_stats_goals_body">
			<div class="arg_users_stats_goals_list"><?php print $list; ?></div>
<?php
		if (count($list_items) > 0) {
			foreach ($list_items as $item) {
				print "\t\t\t".'<div class="arg_users_stats_goals_list_item">'. $item. '</div>'."\n";
			}
		}
		else print "\t\t\t".'<div class="arg_users_stats_goals_list_item"><i>none</i></div>'."\n";
?>
		</div>
	</div>
<?php
	}


	//---------------------------------------------
	function create_arg_user_stats ($this_user) {
?>
<div id="arg_user_stats_<?php print $this_user['uid']; ?>" class="arg_user_stats">
	<div class="arg_user_stats_header">
		<div class="arg_user_stats_avatar"><?php print bp_core_fetch_avatar('item_id='.$this_user['uid']); ?></div>
		<h3 class="arg_user_stats_name"><?php print $this_user['display']; ?> (<?php print $this_user['name']; ?>)</h3>
		<div class="arg_user_stats_data">
			Student ID: <?php print $this_user['sid']; ?><br />
			e-mail: <?php print $this_user['email']; ?>
		</div>
		<div class="arg_user_stats_stars">
<?php
		for ($x = $this_user['levels'][0]; $x > $this_user['levels'][0] - 5; $x--) {
			print "\t\t\t".'<div class="arg_user_stats_star ';
			print ($x > 0 ? 'star_on' : 'star_off');
			print '"></div>'."\n";
		}
?>
		</div>
	</div>
<?php

		global $users_by_id;
		global $arg_goals;
		global $user_data;

		// GOAL 1 -----------------------
		$array = array();
		if (is_array($this_user['comments_by_post'])) foreach ($this_user['comments_by_post'] as $comment_array) {
			$str = '&ldquo;'.$comment_array['title'].'&rdquo;: ';
			$str .= $comment_array['comments']. " comment". ($comment_array['comments'] == 1 ? ' ' : 's ');
			$str2 = '';
			$str2 .= ($comment_array['likes'] > 0 ? $comment_array['likes']. ' like'. ($comment_array['likes'] == 1 ? '' : 's') : '');
			$str2 .= ($comment_array['dislikes'] > 0 ? ($str2 ? ', ' : ''). $comment_array['dislikes']. ' dislike'. ($comment_array['dislikes'] == 1 ? '' : 's') : '');
			$str .= ($str2 ? "($str2)" : '');
			array_push($array, $str);
		}
		create_arg_user_stats_goals($this_user['levels'][1], 'Participate', 'Comments Needed: '. $arg_goals['comments']. '<br />Comments Posted: '. $this_user['comments_num'], 'List of your comments:', $array);
	
		// GOAL 2 -----------------------
		$array = array();
		if (is_array($this_user['invited_by_user'])) foreach ($this_user['invited_by_user'] as $invite_id=>$invite_name) {
			$str = $invite_name;
			if ($str) $str .= ' (participation level '. ($user_data[$users_by_id[$invite_id]]['levels'][1] ? ' ' : 'NOT '). 'achieved)';
			if ($str) array_push($array, $str);
		}
		create_arg_user_stats_goals($this_user['levels'][2], 'Invite', 'Invitations needed: '. $arg_goals['invites']. '<br />Invitations achieved: '. $this_user['confirm_invited_num']. '<br /><span class="small">(the person you invite needs to achieve the Participate goal for it to count)</span>' , 'People you’ve invited:', $array);

		// GOAL 3 -----------------------
		$array = array();
		if (is_array($this_user['events_attend_by_user'])) foreach ($this_user['events_attend_by_user'] as $id=>$data) {
			if ($data) array_push($array, $data);
		}
		create_arg_user_stats_goals($this_user['levels'][3], 'Attend', 'Events needed: '. $arg_goals['attends']. '<br />Events attended: '. count($this_user['events_attend_by_user']), 'List of events you’ve attended:', $array);

		// GOAL 4 -----------------------
		$array = array();
		if (is_array($this_user['events_host_by_user'])) foreach ($this_user['events_host_by_user'] as $id=>$data) {
			if ($data) array_push($array, $data);
		}
		create_arg_user_stats_goals($this_user['levels'][4], 'Educate', 'Events needed: '. $arg_goals['hosts']. '<br />Events achieved: '. count($this_user['events_host_by_user']), 'List of your events:', $array);

		// GOAL 5 -----------------------
		$array = array();
		if (is_array($this_user['events_act_by_user'])) foreach ($this_user['events_act_by_user'] as $id=>$data) {
			if ($data) array_push($array, $data);
		}
		create_arg_user_stats_goals($this_user['levels'][5], 'Act', 'Events needed: '. $arg_goals['acts']. '<br />Events achieved: '. count($this_user['events_act_by_user']), 'List of your events:', $array);

		print "</div>\n\n";
	}


	//---------------------------------------------
	// create options for pull down user menu when in multiuser mode
	$options = '';
	foreach ($user_data as $each_user) {
		$options .= '<option value="'.$each_user['uid'].'">'.$each_user['name'].'</option>';
	}
	
	$doc_id_array = array();
	foreach ($users_by_id as $id=>$name) {
		array_push($doc_id_array, "'arg_user_stats_$id'");
	}
	$doc_id_list = implode(',',$doc_id_array);



	//----------------------------------------------
	// start processing the web page
?>
<style type="text/css">
	.small { font-size: 90%; line-height:100%; }
	.arg_user_stats { margin:24px 0px; border:2px solid #AAA; padding:12px;<?php print ($mode == 'single' ? '' : ' display:none;'); ?> }
	.arg_user_stats_header { overflow:hidden; }
	.arg_user_stats_avatar { float:left; padding-right:12px; }
	.arg_user_stats_data { float:right; }
	.arg_user_stats_goals { margin:17px 0px; padding:12px 0px; border-top:1px solid #CCC; clear:both; }
	.arg_user_stats_goals_requirements { line-height:144%; }
	.arg_user_stats_goals_head { position:absolute; width:222px; }
	.arg_user_stats_goals_body { margin-left:244px; min-height:4em;}
	.arg_users_stats_goals_list { font-weight: bold; }
	.arg_user_stats_stars { height:24px; }
	.arg_user_stats_star { width:24px; height:24px; display:inline-block; }
	h5 .arg_user_stats_star { position:relative; top:5px; }
	.arg_user_stats_star.star_on { background-image:url('<?php bloginfo('template_directory'); ?>/images/stars24.png'); background-position:0px -48px; }
	.arg_user_stats_star.star_off { background-image:url('<?php bloginfo('template_directory'); ?>/images/stars24.png'); }
</style>

<?php if ($mode == 'all') : ?>
<script type="text/javascript">
	function showcard () {
		document.getElementById('arg_user_stats_default').style.display = 'none';
		var id = document.getElementById('arg_user_stats_select').value;
		var cards = [<?php print $doc_id_list; ?>];
		var i;
		for (i = 0; i < cards.length; ++i) {
			document.getElementById(cards[i]).style.display = 'none';
		}
		document.getElementById('arg_user_stats_'+id).style.display = 'block';
	}
</script>

<p><select id="arg_user_stats_select" name="arg_user_id" onchange="showcard()">';
	<option value="" disabled="disabled" selected="selected">Select a User</option><?php print $options; ?>
</select></p>

<div id="arg_user_stats_default">Select a user from the list above</div>
<?php endif; ?>
<?php 
	foreach ($user_collection as $each_user) {
		create_arg_user_stats($each_user);
	}

?>

<?php
/*
//	global $bp;
	print "<hr /><pre>";
	print "Users by ID: "; print_r($users_by_id);
	print "User Data: "; print_r($user_collection);
//	print "Invites: "; print_r($who_invited);
//	print "Something: "; print_r($bp->bp_nav);
//	print "DB: "; print_r($wpdb);
//	print "Template: "; print_r($activities_template);
//	print "Comment Dump: "; print_r($comments);
//	print "User Dump: "; print_r($users);
//	print "Post Dump: "; print_r($posts);
//	print "Current User: "; print_r($current_user);
	print "</pre>";
*/
}
add_shortcode('arg_goal_stats', 'arg_print_user_achievement_stats');





// UPDATE EVENT DATA FOR USERS
//==================================================================================
function arg_update_user_event_data ($atts) {

	extract( shortcode_atts( array('mode'=>'multi'), $atts) );


	global $arg_goals;

	$event_data = arg_get_user_achievement_data ('event_data');
	$events_by_id = array();
	foreach ($event_data as $event) {
		$events_by_id[$event->event_id] = array('name'=>$event->event_name, 'date'=>$event->event_start_date);
	}

?>
<style type="text/css">
	#arg_event_form h5 { margin-bottom: 1em; }
	#user_data_textarea { width: 24em; height: 39em; }
	.small { font-size: 90%; }
	.error { color: #C00; }
</style>

<?php


	$VARS = $_POST;
	if (!$VARS['mode']) $VARS['mode'] = 'form';
	if ($VARS['mode'] == 'continue') $VARS['mode'] = 'form';
	
	//--------------------------------
	if ($VARS['mode'] == 'submit') {
		// check data for errors
		$errors = '';
		if (!$VARS['curr_event']) $errors .= '<p class="error">You must select an event from the list.</p>';
		if (!$VARS['user_action']) $errors .= '<p class="error">You must state whether users are &lquo;attending&rquo;, &lquo;hosting&rquo;, or &lquo;acting on&rquo; this event.</p>';
		$VARS['user_data'] = preg_replace("/\n|\r/", "\n", $VARS['user_data']);
		$user_data_array_temp = explode("\n", $VARS['user_data']);
		$VARS['user_data_array'] = array();
		foreach ($user_data_array_temp as $line) {
			if (trim($line) == '') continue;
			array_push($VARS['user_data_array'], trim($line));
		}
		if (count($VARS['user_data_array']) == 0) $errors .= '<p class="error">You must enter data in the text area.</p>';
		
		if ($errors) {
			print $errors;
			$VARS['mode'] = 'form';
		}
		else $VARS['mode'] = 'process';
	}
	
	//--------------------------------
	if ($VARS['mode'] == 'process') {
		$users = get_users();
		foreach ($users as $user) {
			$VARS['users'][$user->data->ID]['display'] = $user->data->display_name;
			$VARS['users'][$user->data->ID]['levels'] = arg_get_user_achievement_datum($user->data->ID, 'levels', 'explode');
			if (count($VARS['users'][$user->data->ID]['levels']) != 6) $VARS['users'][$user->data->ID]['levels'] = array(0,0,0,0,0,0);
			$VARS['users'][$user->data->ID]['events_'.$VARS['user_action']] = arg_get_user_achievement_datum($user->data->ID, 'events_'.$VARS['user_action']);
			$VARS['u_by_name'][$user->data->user_nicename] = $user->data->ID;
			$VARS['u_by_email'][$user->data->user_email] = $user->data->ID;
			$sid = (xprofile_get_field_data('7',$user->data->ID) ? xprofile_get_field_data('7',$user->data->ID) : '');
			if ($sid) $VARS['u_by_sid'][$sid] = $user->data->ID;
		}
		
		foreach ($VARS['user_data_array'] as $string) {
			$uid = '';
			$key = '';
			if ($VARS['u_by_sid'][$string]) {
				$uid = $VARS['u_by_sid'][$string];
				$key = 'sid';
			}
			if ($VARS['u_by_email'][$string]) {
				$uid = $VARS['u_by_email'][$string];
				$key = 'email';
			}
			if ($VARS['u_by_name'][$string]) {
				$uid = $VARS['u_by_name'][$string];
				$key = 'name';
			}
			if ($uid != '') {
				if ($VARS['users'][$uid]['events_'.$VARS['user_action']] != '') {
					$user_events = explode(':', $VARS['users'][$uid]['events_'.$VARS['user_action']]);
					$user_events_count = count($user_events);
					if (in_array($VARS['curr_event'], $user_events)) {
						$VARS['already'][$uid] = $VARS['users'][$uid]['display']. ' ('. $key. ': '. $string. ')';
					}
					else {
						$VARS['users'][$uid]['events_'.$VARS['user_action']] .= ":". $VARS['curr_event'];
						$user_events_count++;
						$VARS['processed'][$uid] = $VARS['users'][$uid]['display']. ' ('. $key. ': '. $string. ')';
					}
				}
				else {
					$VARS['users'][$uid]['events_'.$VARS['user_action']] = $VARS['curr_event'];
					$user_events_count = 1;
					$VARS['processed'][$uid] = $VARS['users'][$uid]['display']. ' ('. $key. ': '. $string. ')';

				}
				// update events_action for user
				arg_put_user_achievement_data($uid, 'events_'.$VARS['user_action'], $VARS['users'][$uid]['events_'.$VARS['user_action']]);
				// check for level and update for user
				if ($VARS['user_action'] == 'attend' and $user_events_count >= $arg_goals['attends']) $VARS['users'][$uid]['levels'][3] = 1;
				if ($VARS['user_action'] == 'host' and $user_events_count >= $arg_goals['hosts']) $VARS['users'][$uid]['levels'][4] = 1;
				if ($VARS['user_action'] == 'act' and $user_events_count >= $arg_goals['acts']) $VARS['users'][$uid]['levels'][5] = 1;
			}
			else {
				$VARS['dumped'][$string] = $string. " -- unknown";
			}
		}
		
		// write all levels to the database
		foreach ($VARS['users'] as $uid=>$data) {
			$VARS['users'][$uid]['levels'][0] = $data['levels'][1] + $data['levels'][2] + $data['levels'][3] + $data['levels'][4] + $data['levels'][5];
			arg_put_user_achievement_data($uid, 'levels', $VARS['users'][$uid]['levels'], 'implode');
		}
		
		if (count($VARS['processed']) > 0) {
			print '<h5>The following users were added to <i>'. $events_by_id[$VARS['curr_event']]['name']. "</i></h5>\n";
			print "<ol>\n";
			foreach ($VARS['processed'] as $str) print '<li>'. $str. "</li>\n";
			print "</ol>\n";
		}
		if (count($VARS['already']) > 0) {
			print '<h5>The following users were already added to <i>'. $events_by_id[$VARS['curr_event']]['name']. "</i>, so they were not added again.</h5>\n";
			print "<ol>\n";
			foreach ($VARS['already'] as $str) print '<li>'. $str. "</li>\n";
			print "</ol>\n";
		}
		if (count($VARS['dumped']) > 0) {
			print "<h5>The following entries could not be associated with a user</h5>\n";
			print "<ol>\n";
			foreach ($VARS['dumped'] as $str) print '<li>'. $str. "</li>\n";
			print "</ol>\n";
		}
		if ($mode == 'single') $VARS['mode'] = 'form';
		else print '<form method="post" action="'. $PHP_SELF. '"><input type="submit" name="mode" value="continue" /></form>'."\n";
	}


	//--------------------------------
	if ($VARS['mode'] == 'form') {
		$now = date('Y-m-d');
		
?>
<form id="arg_event_form" name="arg_event_form" action="<?php print $PHP_SELF; ?>" method="post">
	<h5>Add users to an event</h5>
	<p>Select the event: <select name="curr_event">
		<?php
		$need_select = true;
		foreach ($events_by_id as $event_id=>$event) {
			print "\t\t".'<option value="'. $event_id. '"';
			if ($need_select) {
				if ($VARS['curr_event'] == $event_id) {
					print ' selected="selected"';
					$need_select = false;
				}
				elseif (!$VARS['curr_event'] and $event['date'] > $now) {
					print ' selected="selected"';
					$need_select = false;
				}
			}
			print '>'. $event['name']. ' ('. $event['date']. ")</option>\n";
		}
		?>
	</select></p>
	<p>Function of users listed below: <select name="user_action">
		<option value='attend'>Attend</option>
		<option value='host'>Educate</option>
		<option value='act'>Act</option>
	</select></p>
<?php if ($mode == 'single') : ?>
	<input type="text" name="user_data" value="<?php print $VARS['user_data']; ?>" />
<?php else: ?>
	<textarea name="user_data" id="user_data_textarea"><?php print $VARS['user_data']; ?></textarea>
	<p class="small">Enter one user per line. Users may be entered by Student ID, e-mail, or user name.</p>
<?php endif; ?>
	<p><input type="submit" name="mode" value="submit" /></p>
</form>
<?php
	}

//	print "<pre>";
//	print "Events: "; print_r($events_by_id);
//	print "VARS: "; print_r($VARS);
//	print "Template: "; print_r($activities_template);
//	print "Comment Dump: "; print_r($comments);
//	print "User Dump: "; print_r($users);
//	print "Post Dump: "; print_r($posts);
//	print "Current User: "; print_r($current_user);
//	print "</pre>";
}
add_shortcode('arg_event_interface', 'arg_update_user_event_data');



// CREATE THE DIVS TO DISPLAY STARS FOR USERS
//===========================================================================
function arg_create_level_stars ($user_id) {
	$return = '';
	$user_levels = arg_get_user_achievement_datum($user_id, 'levels', 'explode');
	for ($x = $user_levels[0]; $x > $user_levels[0] - 5; $x--) {
		$return .= '<div class="arg_user_stats_star ';
		$return .= ($x > 0 ? 'star_on' : 'star_off');
		$return .= '"></div>';
	}
	return $return;
}



/*
*/