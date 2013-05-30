<?php
/*
 * Template Name: Admin Only
 */

if (!$current_user->caps['administrator']) {
	wp_redirect( home_url() );
	exit;
}


?>
<?php get_header() ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<h2 class="pagetitle"><?php the_title(); ?></h2>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry">

						<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'cc' ) ); ?>
						<div class="clear"></div>
						<?php wp_link_pages( array( 'before' => __( '<p class="cc_pagecount"><strong>Pages:</strong> ', 'cc' ), 'after' => '</p>', 'next_or_number' => 'number')); ?>

					</div>
					<div class="clear"></div>
				</div>

			<?php endwhile; endif; ?>

		</div><!-- .page -->
		<?php cc_list_posts_on_page(); ?> 
		
		<div class="clear"></div>
		
		<?php do_action( 'bp_after_blog_page' ) ?>
		
		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer(); ?>