<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Exalt
 */

get_header();

	if ( is_home() && ! is_paged() ) {
		do_action( 'exalt_featured_section_top' );
	} 

?>

	<main id="primary" class="site-main">

		<?php
		/**
		 * Before Main Content Hook
		 */
		do_action( 'exalt_before_main_content' );

		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			/**
			 * Before blog entries hook.
			 * @since 1.0.0
			 */
			do_action( 'exalt_before_blog_entries' );

			?>
				<div id="blog-entries">
					<?php

						/**
						 * Before index loop hook
						 */
						do_action( 'exalt_before_loop', 'index' );

						/* Start the Loop */
						while ( have_posts() ) :

							the_post();

							/*
							* Include the Post-Type-specific template for the content.
							* If you want to override this in a child theme, then include a file
							* called content-___.php (where ___ is the Post Type name) and that will be used instead.
							*/
							get_template_part( 'template-parts/content', get_post_type() );

						endwhile;

						/**
						 * After index loop hook
						 */
						do_action( 'exalt_after_loop', 'index' );
					?>
				</div><!-- #blog-entries -->
			<?php

		exalt_posts_pagination();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;

		/**
		 * After Main Content Hook
		 */
		do_action( 'exalt_after_main_content' );

		?>

	</main><!-- #main -->

<?php

$exalt_archive_layout = get_theme_mod( 'exalt_archive_layout', 'right-sidebar' );
if ( 'right-sidebar' === $exalt_archive_layout || 'left-sidebar' === $exalt_archive_layout ) {
	get_sidebar();
}

get_footer();
