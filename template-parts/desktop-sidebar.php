<?php if ( true == get_theme_mod( 'exalt_show_slideout_sb', false ) ) : ?>
	<aside id="exalt-slideout-sidebar" class="exalt-slideout-sidebar">
		<div class="exalt-slideout-top">
			<button class="exalt-slideout-toggle">
				<?php echo exalt_the_icon_svg( 'close' ); ?>
			</button>
		</div>

		<?php if ( true === get_theme_mod( 'exalt_show_pmenu_onslideout', false ) ) : ?>
			<div class="exalt-mobile-menu-main exalt-mobile-menu">
				<?php exalt_primary_nav_sidebar(); ?>
			</div>
		<?php endif; ?>

		<?php dynamic_sidebar( 'header-1' ); ?>		
	</aside><!-- .exalt-slideout-sidebar -->
<?php endif; ?>