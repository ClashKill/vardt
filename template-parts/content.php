<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Exalt
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		// Before content hook
		do_action( 'exalt_before_content' );
	?>

	<div class="exalt-article-inner">
	
		<?php 
			// Before entry header hook.
			do_action( 'exalt_before_entry_header' );

			exalt_categories(); 
		?>
		
		<header class="entry-header">

			<?php
			// Before entry title hook.
			do_action( 'exalt_before_entry_title' );

			if ( is_singular() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;

			// After entry title hook.
			do_action( 'exalt_after_entry_title' );

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta">
					<?php exalt_entry_meta(); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>

		</header><!-- .entry-header -->

		<?php
			// After entry header hook.
			do_action( 'exalt_after_entry_header' );
		?>

		<div class="entry-content-wrapper">

			<?php
				// Before entry content hook.
				do_action( 'exalt_before_entry_content' );
			?>

			<div class="entry-content">
				<?php
					$exalt_content_type = get_theme_mod( 'exalt_content_type', 'excerpt' );
					if ( 'excerpt' === $exalt_content_type ) {
						the_excerpt();
					} elseif ( 'content' === $exalt_content_type ) {
						the_content();
					}

					exalt_read_more_button();
				?>
			</div><!-- .entry-content -->

			<?php 
				// After entry content hook.
				do_action( 'exalt_after_entry_content' );
			?>

		</div><!-- .entry-content-wrapper -->

	</div><!-- .exalt-article-inner -->

	<?php 
		// After content hook
		do_action( 'exalt_after_content' ); 
	?>

</article><!-- #post-<?php the_ID(); ?> -->
