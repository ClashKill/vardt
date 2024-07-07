<?php
/**
 * The template for displaying author info.
 * 
 * @package Exalt
 */

if ( false === get_theme_mod( 'exalt_show_author_bio', true ) ) {
    return;
}

$exalt_author_avatar = get_avatar( get_the_author_meta( 'ID' ), 80 );
$exalt_posts_author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
?>

<div class="exalt-author-bio">
    <?php if ( $exalt_author_avatar ) : ?>
        <div class="exalt-author-image">
            <a href="<?php echo esc_url( $exalt_posts_author_url ); ?>" rel="author">
                <?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $exalt_author_avatar;
                ?>
            </a>
        </div>
    <?php endif; ?>
    <div class="exalt-author-content">
        <div class="exalt-author-name"><a href="<?php echo esc_url( $exalt_posts_author_url ); ?>" rel="author"><?php echo esc_html( get_the_author() );?></a></div>
        <div class="exalt-author-description"><?php echo wp_kses_post( wpautop( get_the_author_meta( 'description' ) ) ); ?></div>
        <a class="exalt-author-link" href="<?php echo esc_url( $exalt_posts_author_url ); ?>" rel="author">
            <?php
                /* translators: %s is the current author's name. */
                printf( esc_html__( 'More by %s', 'exalt' ), esc_html( get_the_author() ) );
            ?>
        </a>
    </div>
</div>