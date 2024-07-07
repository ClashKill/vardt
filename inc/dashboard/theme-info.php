<?php

function exalt_enqueue_admin_scripts( $hook ) {
    if ( 'appearance_page_about-exalt-theme' != $hook ) {
        return;
    }
    wp_register_style( 'exalt-admin-css', get_template_directory_uri() . '/inc/dashboard/css/admin.css', false, '1.0.0' );
    wp_enqueue_style( 'exalt-admin-css' );
}
add_action( 'admin_enqueue_scripts', 'exalt_enqueue_admin_scripts' );

/**
 * Add admin notice when active theme
 */
function exalt_admin_notice() {
    ?>
    <div class="updated notice notice-info is-dismissible">
        <p><?php esc_html_e( 'Welcome to Exalt! To get started with Exalt please visit the theme Welcome page.', 'exalt' ); ?></p>
        <p><a class="button" href="<?php echo esc_url( admin_url( 'themes.php?page=about-exalt-theme' ) ); ?>"><?php _e( 'Get Started with Exalt', 'exalt' ) ?></a></p>
    </div>
    <?php
}


function exalt_activation_admin_notice(){
    global $pagenow;
    if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
        add_action( 'admin_notices', 'exalt_admin_notice' );
    }
}
add_action( 'load-themes.php',  'exalt_activation_admin_notice'  );


function exalt_add_themeinfo_page() {

    // Menu title can be displayed with recommended actions count.
    $menu_title = esc_html__( 'Exalt Theme', 'exalt' );

    add_theme_page( esc_html__( 'Exalt Theme', 'exalt' ), $menu_title , 'edit_theme_options', 'about-exalt-theme', 'exalt_themeinfo_page_render' );

}
add_action( 'admin_menu', 'exalt_add_themeinfo_page' );

function exalt_themeinfo_page_render() { ?>

    <div class="wrap about-wrap">

        <?php $theme_info = wp_get_theme(); ?>

        <h1><?php esc_html_e( 'Welcome to Exalt', 'exalt' ); ?></h1>

        <p><?php echo esc_html( $theme_info->get( 'Description' ) ); ?></p>

        <?php exalt_admin_welcome_page(); ?>

    </div><!-- .wrap .about-wrap -->

    <?php

}

function exalt_admin_welcome_page() {
    ?>
    <div class="th-theme-info-page">
        <div class="th-theme-info-page-inner">
            <div class="th-theme-page-infobox">
                <div class="th-theme-infobox-content">
                <h3><?php esc_html_e( 'Theme Customizer', 'exalt' ); ?></h3>
                <p><?php esc_html_e( 'All the Exalt theme settings are located at the customizer. Start customizing your website with customizer.', 'exalt' ) ?></p>
                <a class="button" target="_blank" href="<?php echo esc_url( admin_url( '/customize.php' ) ); ?>"><?php esc_html_e( 'Go to customizer','exalt' ); ?></a>
                </div>
            </div>

            <div class="th-theme-page-infobox">
            <div class="th-theme-infobox-content">
                <h3><?php esc_html_e( 'Theme Documentation', 'exalt' ); ?></h3>
                <p><?php esc_html_e( 'Need to learn all about Exalt? Read the theme documentation carefully.', 'exalt' ) ?></p>
                <a class="button" target="_blank" href="<?php echo esc_url( 'https://themezhut.com/exalt-wordpress-theme-documentation/' ); ?>"><?php esc_html_e( 'Read the documentation.','exalt' ); ?></a>
            </div>
            </div>

            <div class="th-theme-page-infobox">
            <div class="th-theme-infobox-content">
                <h3><?php esc_html_e( 'Theme Info', 'exalt' ); ?></h3>
                <p><?php esc_html_e( 'Know all the details about Exalt theme.', 'exalt' ) ?></p>
                <a class="button" target="_blank" href="<?php echo esc_url( 'https://themezhut.com/themes/exalt/' ); ?>"><?php esc_html_e( 'Theme Details.','exalt' ); ?></a>
            </div>
            </div>

            <div class="th-theme-page-infobox">
            <div class="th-theme-infobox-content">
                <h3><?php esc_html_e( 'Theme Demo', 'exalt' ); ?></h3>
                <p><?php esc_html_e( 'See the theme preview of free version.', 'exalt' ) ?></p>
                <a class="button" target="_blank" href="<?php echo esc_url( 'https://themezhut.com/demo/exalt/' ); ?>"><?php esc_html_e( 'Theme Preview','exalt' ); ?></a>    
            </div>
            </div>
        </div>
    </div>

    <?php
}