<?php

/**
 * Functions related to front page featured sections
 */


/**
 * Display featured content top
 */
function exalt_display_featured_content_top() { ?>
    <section class="exalt-featured-top">
        <?php 
            get_template_part( 'template-parts/featured', 'top' );
            dynamic_sidebar( 'exalt-magazine-1' );
        ?>
    </section>
    <?php
}
add_action( 'exalt_featured_section_top', 'exalt_display_featured_content_top' );
