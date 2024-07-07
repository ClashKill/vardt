<?php

function exalt_navigation_location() {
    $exalt_header_layout = exalt_get_header_layout();
    if ( 'default' == $exalt_header_layout ) {
        add_action( 'exalt_header_bottom', 'exalt_navigation_block' );
    } elseif ('single-line' == $exalt_header_layout ) {
        add_action( 'exalt_after_header_main', 'exalt_navigation_inline', 5 );
    }
}
add_action( 'wp', 'exalt_navigation_location' );

if ( ! function_exists( 'exalt_search_box' ) ) :
    /**
     * Displays the search 
     */
    function exalt_search_box() {
    
        if ( false === get_theme_mod( 'exalt_show_search_onmenu', true ) ) {
            return;
        }
    
        ?>
            <div class="exalt-search-container desktop-only">
                <button id="exalt-search-toggle">
                    <span class="exalt-search-icon"><?php exalt_the_icon_svg( 'search' ) ?></span>
                    <span class="exalt-close-icon"><?php exalt_the_icon_svg( 'close' ) ?></span>
                </button>
                <div id="exalt-search-box">
                    <?php get_search_form(); ?>
                </div><!-- exalt-search-box -->
            </div><!-- exalt-search-container -->
        <?php
    }

    add_action( 'exalt_after_primary_nav', 'exalt_search_box' );
    
endif;

if ( ! function_exists( 'exalt_navigation_block' ) ) :

    function exalt_navigation_block() {

        $exalt_menu_width = get_theme_mod( 'exalt_menu_width', 'contained' );
        $exalt_menu_class = '';
        if ( 'contained' == $exalt_menu_width ) {
            $exalt_menu_class = 'exalt-container';
        }

        $exalt_menu_inner_width = get_theme_mod( 'exalt_menu_inner_width', 'contained' );
        $exalt_menu_inner_class = '';
        if ( 'contained' == $exalt_menu_inner_width ) {
            $exalt_menu_inner_class = 'exalt-container';
        }

        ?>
            <div class="exalt-main-menu desktop-only <?php echo esc_attr( $exalt_menu_class ); ?>">
                <div class="exalt-menu-wrapper <?php echo esc_attr( $exalt_menu_inner_class ); ?>">
                    <?php do_action( 'exalt_before_primary_nav' ); ?>

                    <nav id="site-navigation" class="main-navigation exalt-menu">
                        <?php exalt_primary_nav(); ?>
                    </nav>

                    <?php do_action( 'exalt_after_primary_nav' ); ?>
                </div>
            </div>
        <?php
    }

endif;

if ( ! function_exists( 'exalt_navigation_inline' ) ) :

    function exalt_navigation_inline() {
        
        do_action( 'exalt_before_primary_nav' ); ?>

            <nav id="site-navigation" class="main-navigation exalt-menu desktop-only">
                <?php exalt_primary_nav(); ?>
            </nav>

        <?php

        do_action( 'exalt_after_primary_nav' ); 

    }

endif;

if ( ! function_exists( 'exalt_slide_out_menu_toggle' ) ) : 

    function exalt_slide_out_menu_toggle() {
       ?>
            <button class="exalt-slideout-toggle">
                <span class="exalt-menu-bars"><?php echo exalt_the_icon_svg( 'menu-bars' ); ?></span>
                <span class="exalt-menu-bars-close"><?php echo exalt_the_icon_svg( 'close' ); ?></span>
            </button>
        <?php 
    }
	
endif;

function exalt_slideout_menu_toggle_location() {

    if ( get_theme_mod( 'exalt_show_slideout_sb', false ) ) {

        $location = get_theme_mod( 'exalt_slideout_btn_loc', 'primary-menu' );

        if ( 'before-logo' === $location ) {
            add_action( 'exalt_before_header_main', 'exalt_slide_out_menu_toggle', 8 );
        } elseif ( 'top-bar' === $location ) {
            add_action( 'exalt_before_top_bar_main', 'exalt_slide_out_menu_toggle', 5 );
        } else {
            if ( 'single-line' === exalt_get_header_layout() ) {
                add_action( 'exalt_after_primary_nav', 'exalt_slide_out_menu_toggle', 15 );
            } else {
                add_action( 'exalt_after_primary_nav', 'exalt_slide_out_menu_toggle', 15 );
            }
        }

    }

}
add_action( 'wp', 'exalt_slideout_menu_toggle_location' );