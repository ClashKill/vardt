<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Exalt
 */

?>
	</div><!-- .exalt-container -->
	</div><!-- .site-content -->

	<?php
		/**
		 * Before Footer Hook
		 */
		do_action( 'exalt_before_footer' ); 
	?>

	<footer id="colophon" class="site-footer">

		<?php
			$exalt_footer_sidebar_count = get_theme_mod( 'exalt_footer_sidebar_count', '3' );
		?>

		<div class="exalt-footer-widget-area">
			<div class="exalt-container exalt-footer-widgets-inner">
				<div class="exalt-footer-column">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div><!-- .exalt-footer-column -->

				<?php if ( $exalt_footer_sidebar_count >= 2 ) : ?>
					<div class="exalt-footer-column">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div><!-- .exalt-footer-column -->
				<?php endif; ?>

				<?php if ( $exalt_footer_sidebar_count >= 3 ) : ?>
					<div class="exalt-footer-column">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div><!-- .exalt-footer-column -->
				<?php endif; ?>

				<?php if ( $exalt_footer_sidebar_count >= 4 ) : ?>
					<div class="exalt-footer-column">
						<?php dynamic_sidebar( 'footer-4' ); ?>
					</div><!-- .exalt-footer-column -->
				<?php endif; ?>
			</div><!-- .exalt-footer-widgets-inner -->
		</div><!-- .exalt-footer-widget-area -->

		<div class="exalt-footer-bottom">
			<div class="exalt-container exalt-footer-site-info">
				<div class="exalt-footer-copyright">
					<?php 
						$exalt_copyright_text = get_theme_mod( 'exalt_footer_copyright_text', '' ); 

						if ( ! empty( $exalt_copyright_text ) ) {
							echo wp_kses_post( $exalt_copyright_text );
						} else {
							$exalt_site_link = '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" >' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
							/* translators: 1: Year 2: Site URL. */
							printf( esc_html__( 'Copyright &#169; %1$s %2$s.', 'exalt' ), date_i18n( 'Y' ), $exalt_site_link ); // WPCS: XSS OK.
						}		
					?>
				</div><!-- .exalt-footer-copyright -->

				<div class="exalt-designer-credit">
					<?php
						/* translators: 1: WordPress 2: Theme Author. */
						printf( esc_html__( 'Powered by %1$s and %2$s.', 'exalt' ),
							'<a href="https://wordpress.org" target="_blank">WordPress</a>',
							'<a href="https://themezhut.com/themes/exalt/" target="_blank">Exalt</a>'
						); 
					?>
				</div><!-- .exalt-designer-credit" -->
			</div><!-- .exalt-container -->
		</div><!-- .exalt-footer-bottom -->
	</footer><!-- #colophon -->

	<?php
		/**
		 * After Footer hook
		 */
		do_action( 'exalt_after_footer' ); 
	?>

</div><!-- #page -->

<?php
get_template_part( 'template-parts/mobile', 'sidebar' );
get_template_part( 'template-parts/desktop', 'sidebar' );
?>

<?php wp_footer(); ?>

</body>
</html>
