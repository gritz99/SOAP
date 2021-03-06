<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/*
Plugin Name: Email Users
Version: 4.3.21
Plugin URI: http://email-users.vincentprat.info
Description: Allows the site editors to send an e-mail to the blog users. Credits to <a href="http://www.catalinionescu.com">Catalin Ionescu</a> who gave me some ideas for the plugin and has made a similar plugin. Bug reports and corrections by Cyril Crua, Pokey and Mike Walsh.
Author: MarvinLabs & Mike Walsh
Author URI: http://www.marvinlabs.com
*/

/*  Copyright 2006 Vincent Prat 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Version of the plugin
define( 'MAILUSERS_CURRENT_VERSION', '4.3.21' );

// i18n plugin domain
define( 'MAILUSERS_I18N_DOMAIN', 'email-users' );

// Capabilities used by the plugin
define( 'MAILUSERS_EMAIL_SINGLE_USER_CAP', 'email_single_user' );
define( 'MAILUSERS_EMAIL_MULTIPLE_USERS_CAP', 'email_multiple_users' );
define( 'MAILUSERS_EMAIL_USER_GROUPS_CAP', 'email_user_groups' );
define( 'MAILUSERS_NOTIFY_USERS_CAP', 'email_users_notify' );

// User meta
define( 'MAILUSERS_ACCEPT_NOTIFICATION_USER_META', 'email_users_accept_notifications' );
define( 'MAILUSERS_ACCEPT_MASS_EMAIL_USER_META', 'email_users_accept_mass_emails' );

// Debug
define( 'MAILUSERS_DEBUG', false);

/**
 * Initialise the internationalisation domain
 */
$is_mailusers_i18n_setup = false;
function mailusers_init_i18n() {
	global $is_mailusers_i18n_setup;

	if ($is_mailusers_i18n_setup == false) {
		load_plugin_textdomain(MAILUSERS_I18N_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/') ;
		$is_mailusers_i18n_setup = true;
	}
}

/**
 * Default values for the plugin settings
 */
function mailusers_get_default_plugin_settings($option = null)
{
	$default_plugin_settings = array(
		// Version of the email users plugin
		'mailusers_version' => mailusers_get_current_version(),
		// The default title to use when using the post notification functionality
		'mailusers_default_subject' => __('[%BLOG_NAME%] A post of interest: "%POST_TITLE%"', MAILUSERS_I18N_DOMAIN),
		// Mail User - The default body to use when using the post notification functionality
		'mailusers_default_body' => __('<p>Hello, </p><p>I would like to bring your attention on a new post published on the blog. Details of the post follow; I hope you will find it interesting.</p><p>Best regards, </p><p>%FROM_NAME%</p><hr><p><strong>%POST_TITLE%</strong></p><p>%POST_EXCERPT%</p><ul><li>Link to the post: <a href="%POST_URL%">%POST_URL%</a></li><li>Link to %BLOG_NAME%: <a href="%BLOG_URL%">%BLOG_URL%</a></li></ul>', MAILUSERS_I18N_DOMAIN),
		// Mail User - Default mail format (html or plain text)
		'mailusers_default_mail_format' => 'html',
		// Mail User - Default sort users by (none, display name, last name or first name)
		'mailusers_default_sort_users_by' => 'none',
		// Mail User - Maximum number of recipients in the BCC field
		'mailusers_max_bcc_recipients' => '0',
		// Mail User - Default setting for From Sender Name Override
		'mailusers_from_sender_name_override' => '',
		// Mail User - Default setting for From Sender Address Override
		'mailusers_from_sender_address_override' => '',
		// Mail User - Maximum number of rows to show in the User Settings table
		'mailusers_user_settings_table_rows' => '20',
		// Mail User - Default setting for Notifications
		'mailusers_default_notifications' => 'true',
		// Mail User - Default setting for Mass Email
		'mailusers_default_mass_email' => 'true',
		// Mail User - Default setting for User Control
		'mailusers_default_user_control' => 'true',
		// Mail User - Default setting for Short Code Processing
		'mailusers_shortcode_processing' => 'false',
		// Mail User - Default setting for Short Code Processing
		'mailusers_from_sender_exclude' => 'true'
	) ;

    if (array_key_exists($option, $default_plugin_settings))
        return $default_plugin_settings[$option] ;
    else
	    return $default_plugin_settings ;
}

/**
 * Reset plugin to use default settings
 */
function mailusers_reset_to_default_settings() {
	$plugin_settings = mailusers_get_default_plugin_settings() ;

	//  Update the options which will add them if they don't exist
	//  but WILL overwrite any existing settings back to the default.

	foreach ($plugin_settings as $key => $value)
		if ($key !== 'mailusers_version') update_option($key, $value) ;
}

/**
 * Set default values for the options (check against the version)
 */
register_activation_hook(__FILE__, 'mailusers_plugin_activation');
function mailusers_plugin_activation() {
	mailusers_init_i18n();

	$installed_version = mailusers_get_installed_version();

	if ( $installed_version==mailusers_get_current_version() ) {
		// do nothing
	}
	else if ( $installed_version=='' ) {
		$plugin_settings = mailusers_get_default_plugin_settings() ;

		//  Add the options which will add them if they don't
		//  exist but won't overwrite any existing settings.

		foreach ($plugin_settings as $key => $value)
			add_option($key, $value) ;

		mailusers_add_default_capabilities();
		mailusers_add_default_user_meta();

	} else if ( $installed_version>='2.0' && $installed_version<'3.0.0' ) {
		// Version 2.x, a bug was corrected in the template, update it
		$plugin_settings = mailusers_get_default_plugin_settings() ;

		//  Add the options which will add them if they don't
		//  exist but won't overwrite any existing settings.

		foreach ($plugin_settings as $key => $value)
			add_option($key, $value) ;

		delete_option('mailusers_mail_user_level');
		delete_option('mailusers_mail_method');
		delete_option('mailusers_smtp_port');
		delete_option('mailusers_smtp_server');
		delete_option('mailusers_smtp_user');
		delete_option('mailusers_smtp_authentication');
		delete_option('mailusers_smtp_password');

		// Remove old capabilities
		$role = get_role('editor');
		$role->remove_cap('email_users');

		mailusers_add_default_capabilities();
		mailusers_add_default_user_meta();
	} else {
	}

	// Update version number
	update_option( 'mailusers_version', mailusers_get_current_version() );
}

/**
* Plugin deactivation
*/
register_deactivation_hook( __FILE__, 'mailusers_plugin_deactivation' );
function mailusers_plugin_deactivation() {
	//  Force the activation hook to run again when reactivated
	update_option('mailusers_version', '');
}

/**
* Add default user meta information
*/
function mailusers_add_default_user_meta() {
	$users = get_users() ;
	foreach ($users as $user) {
		mailusers_user_register($user->ID);
	}
}

/**
* Add capabilities to roles by default
*/
function mailusers_add_default_capabilities() {
	$role = get_role('contributor');

    if ($role !== null) {
	    $role->add_cap(MAILUSERS_EMAIL_SINGLE_USER_CAP);
    }

	$role = get_role('author');

    if ($role !== null) {
	    $role->add_cap(MAILUSERS_EMAIL_SINGLE_USER_CAP);
	    $role->add_cap(MAILUSERS_EMAIL_MULTIPLE_USERS_CAP);
    }

	$role = get_role('editor');

    if ($role !== null) {
	    $role->add_cap(MAILUSERS_NOTIFY_USERS_CAP);
	    $role->add_cap(MAILUSERS_EMAIL_SINGLE_USER_CAP);
	    $role->add_cap(MAILUSERS_EMAIL_MULTIPLE_USERS_CAP);
	    $role->add_cap(MAILUSERS_EMAIL_USER_GROUPS_CAP);
    }

	$role = get_role('administrator');

    if ($role !== null) {
	    $role->add_cap(MAILUSERS_NOTIFY_USERS_CAP);
	    $role->add_cap(MAILUSERS_EMAIL_SINGLE_USER_CAP);
	    $role->add_cap(MAILUSERS_EMAIL_MULTIPLE_USERS_CAP);
	    $role->add_cap(MAILUSERS_EMAIL_USER_GROUPS_CAP);
    }
}

/**
 * Add the meta field when a user registers
 */
add_action('user_register', 'mailusers_user_register');
function mailusers_user_register($user_id) {
	mailusers_user_meta_init($user_id);
}

add_action('profile_update', 'mailusers_profile_update');
function mailusers_profile_update($user_id) {
	mailusers_user_meta_init($user_id);
}

/**
 * Add the meta field when a user registers
 */
function mailusers_user_meta_init($user_id) {
	$default = mailusers_get_default_notifications() == 'true' ? 'true' : 'false' ;
	if (get_user_meta($user_id, MAILUSERS_ACCEPT_NOTIFICATION_USER_META, true) == '')
		update_user_meta($user_id, MAILUSERS_ACCEPT_NOTIFICATION_USER_META, $default);

	$default = mailusers_get_default_mass_email() == 'true' ? 'true' : 'false' ;
	if (get_user_meta($user_id, MAILUSERS_ACCEPT_MASS_EMAIL_USER_META, true) == '')
		update_user_meta($user_id, MAILUSERS_ACCEPT_MASS_EMAIL_USER_META, $default);
}

/**
* Add a related link to the post edit page to create a template from current post
*/
add_action('submitpost_box', 'mailusers_post_relatedlink');
function mailusers_post_relatedlink() {
	global $post_ID;
	if (isset($post_ID) && current_user_can(MAILUSERS_NOTIFY_USERS_CAP)) {
?>
<div class="postbox">
<h3 class='hndle'><span><?php _e('Email Users', MAILUSERS_I18N_DOMAIN); ?></span></h3>
<div class="inside">
<p><img style="padding: 5px; vertical-align: middle;" src="<?php echo plugins_url('images/email.png' , __FILE__); ?>"</img><a href="admin.php?page=mailusers-send-notify-mail-post&post_id=<?php echo $post_ID; ?>"><?php _e('Notify Users About this Post', MAILUSERS_I18N_DOMAIN); ?></a></p>
</div>
</div>
<?php
	}
}

add_action('submitpage_box', 'mailusers_page_relatedlink');
function mailusers_page_relatedlink() {
	global $post_ID;
	if (isset($post_ID) && current_user_can(MAILUSERS_NOTIFY_USERS_CAP)) {
?>
<div class="postbox">
<h3 class='hndle'><span><?php _e('Email Users', MAILUSERS_I18N_DOMAIN); ?></span></h3>
<div class="inside">
<p><img style="padding: 5px; vertical-align: middle;" src="<?php echo plugins_url('images/email.png' , __FILE__); ?>"</img><a href="admin.php?page=mailusers-send-notify-mail-page&post_id=<?php echo $post_ID; ?>"><?php _e('Notify Users About this Page', MAILUSERS_I18N_DOMAIN); ?></a></p>
</div>
</div>
<?php
	}
}

/**
 * Add a new menu under Write:, visible for all users with access levels 8+ (administrator role).
 */
add_action( 'admin_menu', 'mailusers_add_pages' );
function mailusers_add_pages() {
    mailusers_init_i18n();

    add_posts_page(
	__('Notify Users', MAILUSERS_I18N_DOMAIN),
	__('Notify Users', MAILUSERS_I18N_DOMAIN),
	MAILUSERS_NOTIFY_USERS_CAP,
       	'mailusers-send-notify-mail-post',
       	'mailusers_send_notify_mail') ;

    add_pages_page(
	__('Notify Users', MAILUSERS_I18N_DOMAIN),
	__('Notify Users', MAILUSERS_I18N_DOMAIN),
	MAILUSERS_NOTIFY_USERS_CAP,
       	'mailusers-send-notify-mail-page',
       	'mailusers_send_notify_mail') ;

    add_options_page(
	__('Email Users', MAILUSERS_I18N_DOMAIN),
	__('Email Users', MAILUSERS_I18N_DOMAIN),
	'manage_options',
       	'mailusers-options-page',
       	'mailusers_options_page') ;

    add_menu_page(
	__('Email Users', MAILUSERS_I18N_DOMAIN), 
	__('Email Users', MAILUSERS_I18N_DOMAIN), 
	MAILUSERS_EMAIL_SINGLE_USER_CAP,
       	plugin_basename(__FILE__),
	'mailusers_overview_page',
	plugins_url( 'images/email.png' , __FILE__)) ;

    add_submenu_page(plugin_basename(__FILE__),
	__('Send to User(s)', MAILUSERS_I18N_DOMAIN), 
	__('Send to User(s)', MAILUSERS_I18N_DOMAIN), 
	MAILUSERS_EMAIL_SINGLE_USER_CAP,
       	'mailusers-send-to-user-page',
       	'mailusers_send_to_user_page') ;

    add_submenu_page(plugin_basename(__FILE__),
	__('Send to Group(s)', MAILUSERS_I18N_DOMAIN), 
	__('Send to Group(s)', MAILUSERS_I18N_DOMAIN), 
	MAILUSERS_EMAIL_USER_GROUPS_CAP,
       	'mailusers-send-to-group-page',
       	'mailusers_send_to_group_page') ;

    add_submenu_page(plugin_basename(__FILE__),
	__('User Settings', MAILUSERS_I18N_DOMAIN), 
	__('User Settings', MAILUSERS_I18N_DOMAIN), 
	'edit_users',
       	'mailusers-user-settings',
       	'mailusers_user_settings_page') ;
}

/**
 * Wrapper for the options page menu
 */
function mailusers_options_page() {
    require_once('email_users_set_options.php') ;
}

/**
 * Wrapper for the main email users menu page
 */
function mailusers_overview_page()
{
    require_once('email_users_overview.php') ;
}

/**
 * Wrapper for the email users send to user menu
 */
function mailusers_send_to_user_page()
{
    require_once('email_users_send_user_mail.php') ;
}

/**
 * Wrapper for the email users send to group menu
 */
function mailusers_send_to_group_page()
{
    require_once('email_users_send_group_mail.php') ;
}

/**
 * Wrapper for the email users noptify users menu
 */
function mailusers_send_notify_mail()
{
    require_once('email_users_send_notify_mail.php') ;
}

/**
 * Wrapper for the email users notify group menu
 */
function mailusers_notify_group_page()
{
    require_once('email_users_notify_form.php') ;
}

function mailusers_user_settings_page()
{
    require_once('email_users_user_settings.php') ;
}

/**
 * Wrapper for the email users noptify users menu
 */
function mailusers_set_options_page()
{
    require_once('email_users_set_options.php') ;
}

/**
 * Wrapper for the email users send group mail page
 */
function mailusers_send_group_mail_page()
{
    require_once('email_users_send_group_mail.php') ;
}

/**
 * Action hook to add e-mail options to current user profile
 */
add_action('show_user_profile', 'mailusers_user_profile_form');
function mailusers_user_profile_form() {
	global $user_ID;

    mailusers_edit_any_user_profile_form($user_ID);
}

/**
 * Action hook to add e-mail options to any user profile
 */
add_action('edit_user_profile', 'mailusers_edit_user_profile_form');
function mailusers_edit_user_profile_form() {
	global $profileuser;

    mailusers_edit_any_user_profile_form($profileuser->ID);
}

/**
 * Add a form to change user preferences in the profile
 */
function mailusers_edit_any_user_profile_form($uid) {
 
    //  Do we let users control their own settings?  If so, show the
    //  checkboxes as part of the profile.  If not, settings are hidden.
 
    if ((mailusers_get_default_user_control()=='true') || current_user_can('edit_users')) {
?>
	<h3><?php _e('Email Preferences', MAILUSERS_I18N_DOMAIN); ?></h3>

	<table class="form-table">
	<tbody>
		<tr>
			<th></th>
			<td>
				<input 	type="checkbox"
						name="<?php echo MAILUSERS_ACCEPT_NOTIFICATION_USER_META; ?>"
						id="<?php echo MAILUSERS_ACCEPT_NOTIFICATION_USER_META; ?>"
						value="true"
						<?php if (get_user_meta($uid, MAILUSERS_ACCEPT_NOTIFICATION_USER_META, true)=='true') echo 'checked="checked"'; ?> ></input>
				<?php _e('Accept to receive post or page notification emails', MAILUSERS_I18N_DOMAIN); ?><br/>
				<input 	type="checkbox"
						name="<?php echo MAILUSERS_ACCEPT_MASS_EMAIL_USER_META; ?>"
						id="<?php echo MAILUSERS_ACCEPT_MASS_EMAIL_USER_META; ?>"
						value="true"
						<?php if (get_user_meta($uid, MAILUSERS_ACCEPT_MASS_EMAIL_USER_META, true)=='true') echo 'checked="checked"'; ?> ></input>
				<?php _e('Accept to receive emails sent to multiple recipients (but still accept emails sent only to me)', MAILUSERS_I18N_DOMAIN); ?>
			</td>
		</tr>
	</tbody>
	</table>
<?php
    }
    else {
?>
<input 	type="hidden" name="<?php echo MAILUSERS_ACCEPT_NOTIFICATION_USER_META; ?>" id="<?php echo MAILUSERS_ACCEPT_NOTIFICATION_USER_META; ?>" value="<?php echo (get_user_meta($uid, MAILUSERS_ACCEPT_NOTIFICATION_USER_META, true) === 'true') ? "true" : "false"; ?>"></input>
<input 	type="hidden" name="<?php echo MAILUSERS_ACCEPT_MASS_EMAIL_USER_META; ?>" id="<?php echo MAILUSERS_ACCEPT_MASS_EMAIL_USER_META; ?>" value="<?php echo (get_user_meta($uid, MAILUSERS_ACCEPT_MASS_EMAIL_USER_META, true) === 'true') ? "true" : "false"; ?>"></input>
<?php
    }
}

/**
 * Action hook to update mailusers profile for current user
 */
add_action('personal_options_update', 'mailusers_user_profile_update');
function mailusers_user_profile_update() {
	global $user_ID;
	mailusers_any_user_profile_update($user_ID);
}

/**
 * Action hook to update mailusers profile for any user
 */
add_action('profile_update', 'mailusers_edit_user_profile_update');
function mailusers_edit_user_profile_update($uid) {
	mailusers_any_user_profile_update($uid);
}

/**
 * Save mailusers profile data for any user id
 */
function mailusers_any_user_profile_update($uid) {

	if (isset($_POST[MAILUSERS_ACCEPT_NOTIFICATION_USER_META])) {
	    $value = $_POST[MAILUSERS_ACCEPT_NOTIFICATION_USER_META] ;
		update_usermeta($uid, MAILUSERS_ACCEPT_NOTIFICATION_USER_META, $value);
	} else {
		update_usermeta($uid, MAILUSERS_ACCEPT_NOTIFICATION_USER_META, 'false');
	}

	if (isset($_POST[MAILUSERS_ACCEPT_MASS_EMAIL_USER_META])) {
	    $value = $_POST[MAILUSERS_ACCEPT_MASS_EMAIL_USER_META];
		update_usermeta($uid, MAILUSERS_ACCEPT_MASS_EMAIL_USER_META, $value);
	} else {
		update_usermeta($uid, MAILUSERS_ACCEPT_MASS_EMAIL_USER_META, 'false');
	}
}

/**
 * Enqueue scripts when needed
 *
 */
function email_users_enqueue_scripts($hook) {
    if (('email-users_page_mailusers-send-to-users-page' == $hook) ||
        ('email-users_page_mailusers-send-to-group-page' == $hook))
    {
	    wp_enqueue_script('word-count');
	    wp_enqueue_script('post');
	    wp_enqueue_script('editor');
	    wp_enqueue_script('media-upload');
    }
}
add_action('admin_enqueue_scripts', 'email_users_enqueue_scripts') ;

/**
 * Register settings for the WordPres Options API to work
 */
add_action('admin_init', 'mailusers_admin_init');
function mailusers_admin_init() {
    register_setting('email_users', 'mailusers_default_body') ;
    register_setting('email_users', 'mailusers_default_mail_format') ;
    register_setting('email_users', 'mailusers_default_mass_email') ;
    register_setting('email_users', 'mailusers_default_notifications') ;
    register_setting('email_users', 'mailusers_default_sort_users_by') ;
    register_setting('email_users', 'mailusers_default_subject') ;
    register_setting('email_users', 'mailusers_default_user_control') ;
    register_setting('email_users', 'mailusers_max_bcc_recipients') ;
    register_setting('email_users', 'mailusers_user_settings_table_rows') ;
    register_setting('email_users', 'mailusers_shortcode_processing') ;
    register_setting('email_users', 'mailusers_from_sender_exclude') ;
    register_setting('email_users', 'mailusers_from_sender_name_override') ;
    register_setting('email_users',
        'mailusers_from_sender_address_override', 'mailusers_from_sender_address_override_validate') ;
    register_setting('email_users', 'mailusers_version') ;
}

/**
 * Wrapper for the option 'mailusers_default_subject'
 */
function mailusers_get_default_subject() {
	return stripslashes(get_option( 'mailusers_default_subject' ));
}

/**
 * Wrapper for the option 'mailusers_default_subject'
 */
function mailusers_update_default_subject( $subject ) {
	return update_option( 'mailusers_default_subject', stripslashes($subject) );
}

/**
 * Wrapper for the option 'mailusers_default_body'
 */
function mailusers_get_default_body() {
	return stripslashes(get_option( 'mailusers_default_body' ));
}

/**
 * Wrapper for the option 'mailusers_default_body'
 */
function mailusers_update_default_body( $body ) {
	return update_option( 'mailusers_default_body', stripslashes($body) );
}

/**
 * Wrapper for the option 'mailusers_version'
 */
function mailusers_get_installed_version() {
	return get_option( 'mailusers_version' );
}

/**
 * Wrapper for the option 'mailusers_version'
 */
function mailusers_get_current_version() {
	return MAILUSERS_CURRENT_VERSION;
}

/**
 * Wrapper for the option default_mail_format
 */
function mailusers_get_default_mail_format() {
	return get_option( 'mailusers_default_mail_format' );
}

/**
 * Wrapper for the option default_mail_format
 */
function mailusers_update_default_mail_format( $default_mail_format ) {
	return update_option( 'mailusers_default_mail_format', $default_mail_format );
}

/**
 * Wrapper for the option default_sort_users_by
 */
function mailusers_get_default_sort_users_by() {
	return get_option( 'mailusers_default_sort_users_by' );
}

/**
 * Wrapper for the option default_sort_users_by
 */
function mailusers_update_default_sort_users_by( $default_sort_users_by ) {
	return update_option( 'mailusers_default_sort_users_by', $default_sort_users_by );
}

/**
 * Wrapper for the option mail_method
 */
function mailusers_get_max_bcc_recipients() {
	return get_option( 'mailusers_max_bcc_recipients' );
}

/**
 * Wrapper for the option max bcc recipients
 */
function mailusers_update_max_bcc_recipients( $max_bcc_recipients ) {
	return update_option( 'mailusers_max_bcc_recipients', $max_bcc_recipients );
}

/**
 * Wrapper for the user settings table rows option
 */
function mailusers_get_user_settings_table_rows() {
	return get_option( 'mailusers_user_settings_table_rows' );
}

/**
 * Wrapper for the user settings table rows option
 */
function mailusers_update_user_settings_table_rows( $user_settings_table_rows ) {
	return update_option( 'mailusers_user_settings_table_rows', $user_settings_table_rows );
}

/**
 * Wrapper for the from sender name override option
 */
function mailusers_get_from_sender_name_override() {
	return get_option( 'mailusers_from_sender_name_override' );
}

/**
 * Wrapper for the from sender name override option
 */
function mailusers_update_from_sender_name_override( $from_sender_name_override ) {
	return update_option( 'mailusers_from_sender_name_override', $from_sender_name_override );
}

/**
 * Wrapper for the from sender address override option
 */
function mailusers_get_from_sender_address_override() {
	return get_option( 'mailusers_from_sender_address_override' );
}

/**
 * Wrapper for the from sender address override option
 */
function mailusers_update_from_sender_address_override( $from_sender_address_override ) {
	return update_option( 'mailusers_from_sender_address_override', $from_sender_address_override );
}

/**
 * Wrapper for the from sender address override option validation
 */
function mailusers_from_sender_address_override_validate( $from_sender_address_override ) {
	return is_email($from_sender_address_override ) ? $from_sender_address_override : false ;
}

/**
 * Wrapper for the default notification setting
 */
function mailusers_get_default_notifications() {
	return get_option( 'mailusers_default_notifications' );
}

/**
 * Wrapper for the default notification setting
 */
function mailusers_update_default_notifications( $default_notifications ) {
	return update_option( 'mailusers_default_notifications', $default_notifications );
}

/**
 * Wrapper for the default mass email setting
 */
function mailusers_get_default_mass_email() {
	return get_option( 'mailusers_default_mass_email' );
}

/**
 * Wrapper for the default mass email setting
 */
function mailusers_update_default_mass_email( $default_mass_email ) {
	return update_option( 'mailusers_default_mass_email', $default_mass_email );
}

/**
 * Wrapper for the default mass email setting
 */
function mailusers_get_default_user_control() {
	return get_option( 'mailusers_default_user_control' );
}

/**
 * Wrapper for the default mass email setting
 */
function mailusers_update_default_user_control( $default_user_control ) {
	return update_option( 'mailusers_default_user_control', $default_user_control );
}

/**
 * Wrapper for the short code processing setting
 */
function mailusers_get_shortcode_processing() {
	return get_option( 'mailusers_shortcode_processing' );
}

/**
 * Wrapper for the short code processing setting
 */
function mailusers_update_shortcode_processing( $shortcode_processing ) {
	return update_option( 'mailusers_shortcode_processing', $shortcode_processing );
}

/**
 * Wrapper for the from send exclude setting
 */
function mailusers_get_from_sender_exclude() {
    $option = get_option( 'mailusers_from_sender_exclude' );

    if ($option === false)
        $option = mailusers_get_default_plugin_settings( 'mailusers_from_sender_exclude' );

    return $option;
}

/**
 * Wrapper for the from sender exclude setting
 */
function mailusers_update_from_sender_exclude( $from_sender_exclude ) {
	return update_option( 'mailusers_from_sender_exclude', $from_sender_exclude );
}

/**
 * Get the users
 * $meta_filter can be '', MAILUSERS_ACCEPT_NOTIFICATION_USER_META, or MAILUSERS_ACCEPT_MASS_EMAIL_USER_META
 */
function mailusers_get_users( $exclude_id='', $meta_filter = '', $args = array(), $sortby = null) {

	if ($sortby == null) $sortby = mailusers_get_default_sort_users_by();

    //  Set up the arguments for get_users()

    $args['exclude'] = $exclude_id ;
    $args['fields'] = 'all_with_meta' ;

    //  Apply the meta filter

    if ($meta_filter != '')
    {
        $args['meta_key'] = $meta_filter ;
        $args['meta_value'] = 'true' ;
    }

    //  Retrieve the list of users

	$users = get_users($args) ;

    //  Sort the users based on the plugin settings

    if ( ! empty( $users) ) {
		switch ($sortby) {
			case 'fl' :
			case 'flul' :
                usort( $users, 'mailusers_sort_users_by_first_name' );
				break;
			case 'lf' :
			case 'lful' :
                usort( $users, 'mailusers_sort_users_by_last_name' );
				break;
			case 'ul' :
			case 'uldn' :
			case 'ulfn' :
			case 'ulln' :
                usort( $users, 'mailusers_sort_users_by_user_login' );
				break;
			case 'display name' :
                usort( $users, 'mailusers_sort_users_by_display_name' );
				break;
			default:
				break;
		}

    }

    return $users ;
}

/**
 * Sort by last name
 */
function mailusers_sort_users_by_last_name( $a, $b ) {

    if ( $a->last_name == $b->last_name ) {
        return 0;
    }

    return ( $a->last_name < $b->last_name ) ? -1 : 1;
}

/**
 * Sort by first name
 */
function mailusers_sort_users_by_first_name( $a, $b ) {

    if ( $a->first_name == $b->first_name ) {
        return 0;
    }

    return ( $a->first_name < $b->first_name ) ? -1 : 1;
}

/**
 * Sort by display name
 */
function mailusers_sort_users_by_display_name( $a, $b ) {

    if ( $a->display_name == $b->display_name ) {
        return 0;
    }

    return ( $a->display_name < $b->display_name ) ? -1 : 1;
}

/**
 * Sort by user login
 */
function mailusers_sort_users_by_user_login( $a, $b ) {

    if ( $a->user_login == $b->user_login ) {
        return 0;
    }

    return ( $a->user_login < $b->user_login ) ? -1 : 1;
}

/**
 * Get the users based on roles
 * $meta_filter can be '', MAILUSERS_ACCEPT_NOTIFICATION_USER_META, or MAILUSERS_ACCEPT_MASS_EMAIL_USER_META
 */
function mailusers_get_roles( $exclude_id='', $meta_filter = '') {
	$roles = array();

	$wp_roles = new WP_Roles();
	foreach ($wp_roles->get_names() as $key => $value) {
		$users_in_role = mailusers_get_recipients_from_roles(array($key), $exclude_id, $meta_filter);
		if (!empty($users_in_role)) {
			$roles[$key] = $value;
		}
	}

	return $roles;
}

/**
 * Get the users given a role or an array of ids
 * $meta_filter can be '', MAILUSERS_ACCEPT_NOTIFICATION_USER_META, or MAILUSERS_ACCEPT_MASS_EMAIL_USER_META
 */
function mailusers_get_recipients_from_ids( $ids, $exclude_id='', $meta_filter = '') {
    return mailusers_get_users($exclude_id, $meta_filter, array('include' => $ids)) ;
}

/**
 * Get the users given a role or an array of roles
 * $meta_filter can be '', MAILUSERS_ACCEPT_NOTIFICATION_USER_META, or MAILUSERS_ACCEPT_MASS_EMAIL_USER_META
 */
function mailusers_get_recipients_from_roles($roles, $exclude_id='', $meta_filter = '') {

    $users = array() ;

    foreach ($roles as $role)
        $users = array_merge($users, mailusers_get_users($exclude_id, $meta_filter, array('role' => $role))) ;

    return $users ;
}

/**
 * Check Valid E-Mail Address
 */
function mailusers_is_valid_email($email) {
	if (function_exists('is_email')) {
		return is_email($email);
	}

	$regex = '/^[A-z0-9][\w.+-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
	return (preg_match($regex, $email));
}

/**
 * Protect against special characters (e.g. $) in the post content
 * being processed as part of the preg_replace() replacement string.
 *
 * @see http://www.procata.com/blog/archives/2005/11/13/two-preg_replace-escaping-gotchas/
 */
function mailusers_preg_quote($str) {
    return preg_replace('/(\$|\\\\)(?=\d)/', '\\\\\1', $str);
}

/**
 * Replace the template variables in a given text.
 */
function mailusers_replace_post_templates($text, $post_title, $post_excerpt, $post_url) {
	$text = preg_replace( '/%POST_TITLE%/', mailusers_preg_quote($post_title), $text );
	$text = preg_replace( '/%POST_EXCERPT%/', mailusers_preg_quote($post_excerpt), $text );
	$text = preg_replace( '/%POST_URL%/', mailusers_preg_quote($post_url), $text );
	return $text;
}

/**
 * Replace the template variables in a given text.
 */
function mailusers_replace_blog_templates($text) {
	$blog_url = get_option( 'home' );
	$blog_name = get_option( 'blogname' );

	$text = preg_replace( '/%BLOG_URL%/', mailusers_preg_quote($blog_url), $text );
	$text = preg_replace( '/%BLOG_NAME%/', mailusers_preg_quote($blog_name), $text );
	return $text;
}

/**
 * Replace the template variables in a given text.
 */
function mailusers_replace_sender_templates($text, $sender_name) {
	$text = preg_replace( '/%FROM_NAME%/', mailusers_preg_quote($sender_name), $text );
	return $text;
}

/**
 * Delivers email to recipients in HTML or plaintext
 *
 * Returns number of recipients addressed in emails or false on internal error.
 */
function mailusers_send_mail($recipients = array(), $subject = '', $message = '', $type='plaintext', $sender_name='', $sender_email='') {
	$num_sent = 0; // return value
	if ( (empty($recipients)) ) { return $num_sent; }
	if ('' == $message) { return false; }

	$headers  = "From: \"$sender_name\" <$sender_email>\n";
	$headers .= "Return-Path: <" . $sender_email . ">\n";
	$headers .= "Reply-To: \"" . $sender_name . "\" <" . $sender_email . ">\n";
	$headers .= "X-Mailer: PHP" . phpversion() . "\n";

	$subject = stripslashes($subject);
	$message = stripslashes($message);

	if ('html' == $type) {
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: " . get_bloginfo('html_type') . "; charset=\"". get_bloginfo('charset') . "\"\n";
		$mailtext = "<html><head><title>" . $subject . "</title></head><body>" . $message . "</body></html>";
	} else {
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/plain; charset=\"". get_bloginfo('charset') . "\"\n";
		$message = preg_replace('|&[^a][^m][^p].{0,3};|', '', $message);
		$message = preg_replace('|&amp;|', '&', $message);
		$mailtext = wordwrap(strip_tags($message), 80, "\n");
	}

	// If unique recipient, send mail using to field.
	//--
	if (count($recipients)==1) {
        $recipient = reset($recipients) ; // reset will return first value of the array!
		if (mailusers_is_valid_email($recipient->user_email)) {
            $to = sprintf("%s <%s>", $recipient->display_name, $recipient->user_email) . "\r\n" ;
			//$headers .= "To: \"" . $recipient->display_name . "\" <" . $recipient->user_email . ">\n";
			$headers .= "Cc: " . $sender_email . "\n\n";
			
			if (MAILUSERS_DEBUG) {
				mailusers_preprint_r(htmlentities($headers));
			}
			
			@wp_mail($to, $subject, $mailtext, $headers);
			$num_sent++;
		} else {
			echo '<div class="error fade"><p>' . sprintf(__('The email address (%s) of the user you are trying to send mail to is not a valid email address format.', MAILUSERS_I18N_DOMAIN), $recipient->user_email) . '</p></div>';
			return $num_sent;
		}
		return $num_sent;
	}

	// If multiple recipients, use the BCC field
	//--
	$bcc = '';
	$bcc_limit = mailusers_get_max_bcc_recipients();

	if ( $bcc_limit>0 && (count($recipients)>$bcc_limit) ) {
		$count = 0;
		$sender_emailed = false;

		//for ($i=0; $i<count($recipients); $i++) {
			//$recipient = $recipients[$i]->user_email;
        foreach ($recipients as $key=> $value) {
			$recipient = $recipients[$key]->user_email;

            if (!mailusers_is_valid_email($recipient)) {
                continue;
            }
            if ( empty($recipient) || ($sender_email == $recipient) ) {
                continue;
            }

			if ($bcc=='') {
				$bcc = "Bcc: $recipient";
			} else {
				$bcc .= ", $recipient";
			}

			$count++;

			if (($bcc_limit == $count) || ($num_sent==count($recipients)-1)) {
				if (!$sender_emailed) {
					$newheaders = $headers . "To: \"" . $sender_name . "\" <" . $sender_email . ">\n" . "$bcc\n\n";
					$sender_emailed = true;
				} else {
					$newheaders = $headers . "$bcc\n\n";
				}
					
				if (MAILUSERS_DEBUG) {
					mailusers_preprint_r($newheaders);
				}
			
				@wp_mail($sender_email, $subject, $mailtext, $newheaders);
				$count = 0;
				$bcc = '';
			}

			$num_sent++;
		}
	} else {
		$headers .= "To: \"" . $sender_name . "\" <" . $sender_email . ">\n";

        foreach ($recipients as $key=> $value) {
			$recipient = $recipients[$key]->user_email;

            if (!mailusers_is_valid_email($recipient)) {
                echo '<div class="error fade"><p>' . sprintf(__('Invalid email address ("%s") found.', MAILUSERS_I18N_DOMAIN), $recipient) . '</p></div>';
                continue;
            }

			if ( empty($recipient) || ($sender_email == $recipient) ) { continue; }

			if ($bcc=='') {
				$bcc = "Bcc: $recipient";
			} else {
				$bcc .= ", $recipient";
			}
			$num_sent++;
		}
		$newheaders = $headers . "$bcc\n\n";
					
		if (MAILUSERS_DEBUG) {
			mailusers_preprint_r($newheaders);
		}
				
		@wp_mail($sender_email, $subject, $mailtext, $newheaders);
	}

	return $num_sent;
}

if (MAILUSERS_DEBUG) :
/**
 * Debug functions
 */
function mailusers_preprint_r()
{
    $numargs = func_num_args() ;
    $arg_list = func_get_args() ;
    for ($i = 0; $i < $numargs; $i++) {
	    printf('<pre style="text-align:left;">%s</pre>', print_r($arg_list[$i], true)) ;
    }
}

function mailusers_whereami($x, $y)
{
	printf('<h2>%s::%s</h2>', basename($x), $y) ;
}
endif;
?>
