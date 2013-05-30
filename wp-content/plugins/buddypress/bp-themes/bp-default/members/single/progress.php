<?php

/**
 * BuddyPress - Users Progress
 *
 * @package BuddyPress
 * @subpackage bp-default
 * added by ICAD for ARG game
 
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul>
		<?php if ( bp_is_my_profile() ) : ?>
		
			<?php bp_get_options_nav(); ?>
		
		<?php endif; ?>
	</ul>
</div>

<?php

arg_print_user_achievement_stats(array('mode'=>'single'));

?>
