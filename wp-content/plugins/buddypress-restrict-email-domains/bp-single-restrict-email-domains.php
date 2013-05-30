<?php

function bp_restrict_email_domains_validate_user_signup( $result ) {

	if ( bp_restrict_email_domains_is_email_address_unsafe( $result['user_email'] ) )
		$result['errors']->add('user_email',  __('You cannot use that email address to signup. We are having problems with them blocking some of our email. Please use another email provider.', 'bp-restrict-email-domains' ) );

	return $result;

}
add_filter( 'bp_core_validate_user_signup', 'bp_restrict_email_domains_validate_user_signup' );




//helpers

//from ms-functions.php
function bp_restrict_email_domains_is_email_address_unsafe( $user_email ) {

	$banned_names = get_site_option( 'banned_email_domains' );
	
	if ($banned_names && !is_array( $banned_names ))
		$banned_names = explode( "\n", $banned_names);

	if ( is_array( $banned_names ) && empty( $banned_names ) == false ) {
	
		$email_domain = strtolower( substr( $user_email, 1 + strpos( $user_email, '@' ) ) );
		
		foreach ( (array) $banned_names as $banned_domain ) {
			
			if ( $banned_domain == '' )
				continue;
			
			if (
				strstr( $email_domain, $banned_domain ) ||
				(
					strstr( $banned_domain, '/' ) &&
					preg_match( $banned_domain, $email_domain )
				)
			)
			return true;
		}
		
	}
	return false;
}

?>