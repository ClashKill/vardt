<aside id="exalt-mobile-sidebar" class="exalt-mobile-sidebar">
	<button class="exalt-mobile-menu-toggle">
		<span class="screen-reader-text"><?php esc_html_e( 'Close', 'exalt' ); ?></span>
		<?php exalt_the_icon_svg( 'close' ); ?>
	</button>

	<?php 
		if ( true === get_theme_mod( 'exalt_show_social_mobile_menu', true ) && has_nav_menu( 'social' ) ) {
			exalt_social_nav(); 
		}
	?>

	<div class="exalt-mobile-menu-main exalt-mobile-menu">
		<?php exalt_primary_nav_sidebar(); ?>
	</div>

	<?php if ( true === get_theme_mod( 'exalt_show_top_nav_on_mobile_menu', false ) && has_nav_menu( 'secondary' ) ) : ?>
		<div class="exalt-mobile-menu-secondary exalt-mobile-menu">
			<?php exalt_secondary_nav_mobile() ?>
		</div>
	<?php endif; ?>

	<?php 
		if ( true === get_theme_mod( 'exalt_show_slideout_widgets_on_mobile_menu', false ) ) {
			dynamic_sidebar( 'header-1' );
		} 
	?>
</aside><!-- .exalt-mobile-sidebar -->