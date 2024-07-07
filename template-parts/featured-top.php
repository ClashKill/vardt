<?php
/**
 * Displays featured content on the Blog page.
 */

if ( false == get_theme_mod( 'exalt_show_featured_content', true ) ) {
    return;
}

    
do_action( 'exalt_before_featured_content' ); 

    $exalt_fps_source = get_theme_mod( 'exalt_featured_posts_source', 'latest' );
    $exalt_fps_args = array();

    if ( 'category' === $exalt_fps_source ) {
        $exalt_fps_category = get_theme_mod( 'exalt_featured_posts_category', '' );
        $exalt_fps_args = array(
            'cat'                   => $exalt_fps_category,
            //'ignore_sticky_posts'   => true,
            'posts_per_page'        => 5,
        );
    } elseif ( 'tag' === $exalt_fps_source ) {
        $exalt_fps_tag = get_theme_mod( 'exalt_featured_posts_tag', '' );
        $exalt_fps_args = array(
            'tag'                   => $exalt_fps_tag,
            //'ignore_sticky_posts'   => true,
            'posts_per_page'        => 5,
        );
    } else {
        $exalt_fps_args = array(
            'posts_per_page'        => 5,
            //'ignore_sticky_posts'   => true,
        );
    }

    $exalt_fps_posts = new WP_Query( $exalt_fps_args );

    if ( $exalt_fps_posts->have_posts() ) : 
    $exalt_fp_counter = 1;

    ?>

    <div class="exalt-fp1">

    <?php    

        while( $exalt_fps_posts->have_posts() ) :

            $exalt_fps_posts->the_post();

            if ( $exalt_fp_counter == 1 ) { ?>
                <div class="exalt-fp1-left">
                    <article class="exalt-fp1-lg">
                        <?php if ( has_post_thumbnail() ) { ?>
                            <div class="exalt-fp1-lg-img">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'bam-large', array( 'class' => 'bam-fpw-img') ); ?></a>
                            </div>
                        <?php } ?>

                        <div class="exalt-fp-overlay">
                            <a class="exalt-fp-link-overlay" href="<?php the_permalink(); ?>" rel="bookmark"></a>
                        </div>

                        <div class="exalt-fp1-details exalt-fp-meta">
                            <?php exalt_categories(); ?>
                            <?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
                            <div class="entry-meta">
                                <?php exalt_entry_meta(); ?>
                            </div><!-- .entry-meta -->
                        </div>
                    </article>
                </div>

                <div class="exalt-fp1-right">

            <?php } else {
                ?>
                    <article class="exalt-fp1-sm">
                        <?php if ( has_post_thumbnail() ) { ?>
                            <div class="exalt-fp1-sm-img">
                                <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">	
                                    <?php the_post_thumbnail( 'exalt-thumbnail' ); ?>
                                </a>
                            </div>
                        <?php } elseif ( false == get_theme_mod( 'exalt_remove_placeholder', false ) ) { ?>
                            <div class="exalt-fp1-sm-img">
                                <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">	
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/sm-img.png' ); ?>" />
                                </a>
                            </div>
                        <?php } ?>
                        <div class="exalt-fp1-sm-details">
                            <?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
                            <div class="entry-meta"><?php echo exalt_posted_on(); ?></div>
                        </div>
                    </article>
                        
                <?php
            }

            $exalt_fp_counter++;
        endwhile;
        wp_reset_postdata();

        ?>
                </div><!-- exalt-fp1-right -->


    </div><!-- .exalt-fp1 -->
    <?php
    endif;
?>

<?php do_action( 'exalt_after_featured_content' ); ?>