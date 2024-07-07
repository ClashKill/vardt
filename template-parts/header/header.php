<?php
/**
 * Header Template
 */
?>

<header id="masthead" class="site-header hide-header-search">

    <?php do_action( 'exalt_header_top' ); ?>

    <div class="exalt-header-inner-wrapper">

        <?php 
            /**
             * Before header inner action
             */
            do_action( 'exalt_before_header_inner' );
        ?>

        <div class="exalt-header-inner exalt-container">

            <?php do_action( 'exalt_before_header_main' ); ?>

            <?php do_action( 'exalt_header_main' ); ?>

            <?php do_action( 'exalt_after_header_main' ); ?>
        
        </div><!-- .exalt-header-inner -->

        <?php 
            /**
             * After header inner action
             */
            do_action( 'exalt_after_header_inner' );
        ?>

    </div><!-- .exalt-header-inner-wrapper -->

    <?php do_action( 'exalt_header_bottom' ); ?>

</header><!-- #masthead -->