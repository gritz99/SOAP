<?php
/*
 Plugin Name: BuddyPress Restrict Email Domains
 Plugin URI: http://wordpress.org/extend/plugins/buddypress-restrict-email-domains/
 Description: Enables restriction of email domains for a single (non-multisite) WordPress installation of BuddyPress
 Author: rich fuller - rich! @ etiviti
 Author URI: http://buddypress.org/developers/nuprn1/
 License: GNU GENERAL PUBLIC LICENSE 3.0 http://www.gnu.org/licenses/gpl.txt
 Version: 0.1.0
 Text Domain: bp-restrict-email-domains
 Site Wide Only: true
*/

/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function bp_restrict_email_domains_init() {

	//don't load for MS
	if ( is_multisite() )
		return false;

    require( dirname( __FILE__ ) . '/bp-single-restrict-email-domains.php' );
	
}
add_action( 'bp_init', 'bp_restrict_email_domains_init' );

//add admin_menu page
function bp_restrict_email_domains_admin_add_admin_menu() {
	global $bp;
	
	//if ( !is_super_admin() )
	if ( !is_site_admin() )
		return false;

	//don't load for MS
	if ( is_multisite() )
		return false;

	//Add the component's administration tab under the "BuddyPress" menu for site administrators
	require ( dirname( __FILE__ ) . '/admin/bp-restrict-email-domains-admin.php' );

	add_submenu_page( 'bp-general-settings', __( 'Restrict Email Admin', 'bp-restrict-email-domains' ), __( 'Restrict Email', 'bp-restrict-email-domains' ), 'manage_options', 'bp-restrict-email-domains-settings', 'bp_restrict_email_domains_admin' );	

	//set up defaults

}

//loader file never works - as it doesn't hook the admin_menu
if ( defined( 'BP_VERSION' ) ) {
	add_action( 'admin_menu', 'bp_restrict_email_domains_admin_init' );
} else {
	add_action( 'bp_init', 'bp_restrict_email_domains_admin_init');
}

function bp_restrict_email_domains_admin_init() {
	add_action( 'admin_menu', 'bp_restrict_email_domains_admin_add_admin_menu', 25 );
}

?>