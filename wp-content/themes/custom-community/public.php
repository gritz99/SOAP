<?php 
/*
 * Template Name: Public Default 
 */
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

						<?php wp_link_pages( array( 'before' => __( '<p><strong>Pages:</strong> ', 'cc' ), 'after' => '</p>', 'next_or_number' => 'number')); ?>
						

					</div>

				</div>

			<?php endwhile; endif; ?>

		</div><!-- .page -->
		</div><!-- .padder -->
	</div><!-- #content -->

	<style type="text/css">#sidebar{display:none;}</style>
	<style type="text/css">#search-bar{display:none;}</style>
	<style type="text/css">#access div.menu{display:none;}</style>
	<div id="publicsidebar" class="widgetarea">
		<div class="right-sidebar-padder">
			<div class="widget">
<?php do_action( 'bp_inside_after_sidebar' ) ?>
			</div>
		</div>
	</div>


	
<?php get_footer(); ?>
