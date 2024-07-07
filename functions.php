<?php
/**
 * Exalt functions and definitions
 *
 * @package Exalt
 */

if ( ! defined( 'EXALT_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'EXALT_VERSION', '1.0.5' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function exalt_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Exalt, use a find and replace
		* to change 'exalt' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'exalt', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );
	
	// Enqueue editor styles.
	add_editor_style( 'assets/css/editor-style.css' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'exalt-featured-image', 1300, 9999 );
	add_image_size( 'exalt-archive-image', 800, 533, true );
	add_image_size( 'exalt-archive-image-large', 1300, 867, true );
	add_image_size( 'exalt-thumbnail', 250, 170, true );

	if ( ! get_theme_mod( 'exalt_archive_image_crop', true ) ) {
		add_image_size( 'exalt-archive-image', 800, 9999, false );
		add_image_size( 'exalt-archive-image-large', 1300, 9999, false );
	}

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'exalt' ),
			'secondary' => esc_html__( 'Top Menu', 'exalt' ),
			'social' => esc_html__( 'Social Menu', 'exalt' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'bam_custom_background_args', array(
		'default-color' => '#ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'flex-height'	=> true,
			'flex-width' 	=> true,
		)
	);

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets' => array(
			'sidebar-1' => array(
				'recent-posts',
				'categories',
				'text_about'
			),
			'footer-1' => array(
				'text_business_info',
			),
			'footer-2' => array(
				'recent-posts',
			),
			'footer-3' => array(
				'text_about',
				'meta'
			),
		),
		'theme_mods' => array(
			'exalt_display_social_topbar' => false,
			'exalt_social_beside_logo' => true,
			'exalt_show_header_cta' => true,
			'exalt_show_slideout_sb' => true,
			'exalt_show_pmenu_onslideout' => true
		),
		'nav_menus' => array(
			// Assign a menu to the "menu-social" location.
			'social' => array(
				'name' => esc_html__( 'Social Menu', 'exalt' ),
				'items' => array(
					'link_facebook',
					'link_youtube',
					'link_twitter',
				),
			),
		)
	);

	$starter_content = apply_filters( 'exalt_starter_content', $starter_content );
	add_theme_support( 'starter-content', $starter_content );	
}
add_action( 'after_setup_theme', 'exalt_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function exalt_content_width() {

	$site_layout = get_theme_mod( 'exalt_site_layout', 'boxed' );
	if ( 'boxed' == $site_layout ) {
		$boxed_width = get_theme_mod( 'exalt_boxed_width', 1380 );
		$container_width = ( $boxed_width * 92.7536231884058 ) / 100;
	} else {
		$container_width = get_theme_mod( 'exalt_container_width', 1280 );
	}
	
	$sidebar_width = get_theme_mod( 'exalt_sidebar_width', 29.6875 );
	$layout = exalt_get_layout();

	if ( 'left-sidebar' === $layout || 'right-sidebar' === $layout ) {
		$content_width = $container_width * ( ( 100 - $sidebar_width ) / 100 );
	} elseif ( 'no-sidebar' === $layout ) {
		$content_width = $container_width;
	} else {
		$content_width = 900;
	}

	$GLOBALS['content_width'] = apply_filters( 'exalt_content_width', $content_width );

}
add_action( 'template_redirect', 'exalt_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function exalt_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'exalt' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'exalt' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Slide-out Sidebar', 'exalt' ),
			'id'            => 'header-1',
			'description'   => esc_html__( 'Add widgets here to appear in an off-screen sidebar when it is enabled under the Customizer Header Settings.', 'exalt' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Header Sidebar', 'exalt' ),
			'id'            => 'header-2',
			'description'   => esc_html__( 'Add widgets here to appear on the Header', 'exalt' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Below Header', 'exalt' ),
			'id'            => 'header-3',
			'description'   => esc_html__( 'Add widgets here to appear before the Header', 'exalt' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Magazine Builder', 'exalt' ),
			'description'   => esc_html__( 'Add Posts Blocks here.', 'exalt' ),
			'id'            => 'exalt-magazine-1',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'exalt' ),
			'id'            => 'footer-1',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'exalt' ),
			'id'            => 'footer-2',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'exalt' ),
			'id'            => 'footer-3',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 4', 'exalt' ),
			'id'            => 'footer-4',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'exalt_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function exalt_scripts() {
	wp_enqueue_style( 'exalt-style', get_stylesheet_uri(), array(), EXALT_VERSION );
	wp_style_add_data( 'exalt-style', 'rtl', 'replace' );

	wp_enqueue_script( 'exalt-main', get_template_directory_uri() . '/assets/js/main.js', array(), EXALT_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( ( ( is_home() && ! is_paged() ) || ( is_front_page() && ! is_paged() ) ) && true == get_theme_mod( 'exalt_show_featured_content', true ) && ! is_page_template( 'page-templates/template-fullwidth.php' ) ) {
		wp_enqueue_script( 'simplebar', get_template_directory_uri() . '/assets/js/simplebar.min.js', array(), EXALT_VERSION, true );
		wp_enqueue_style( 'simplebar', get_template_directory_uri() . '/assets/css/simplebar.min.css', array(), ''  );
	}
}
add_action( 'wp_enqueue_scripts', 'exalt_scripts' );

/**
 * Handle SVG icons.
 */ 
require get_template_directory() . '/inc/class-exalt-svg-icons.php';

/**
 * Custom Nav Walker
 */
require get_template_directory() . '/inc/class-exalt-nav-walker.php';

/**
 * Meta boxes
 */
require get_template_directory() . '/inc/class-exalt-meta-boxes.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Add custom header background support.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/wptt-webfont-loader.php';
require get_template_directory() . '/inc/customizer/custom-controls/fonts/fonts.php';
require get_template_directory() . '/inc/customizer/customizer.php';
require get_template_directory() . '/inc/typography.php';

if ( ! function_exists( 'exalt_get_fonts_array' ) ) :
	/**
	 * Gets the user chosen fonts from customizer as an array.
	 */
	function exalt_get_fonts_array() {
		$fonts_arr = array();
		$body_font = get_theme_mod( 'exalt_font_family_1', 'Inter' );
		$headings_font = get_theme_mod( 'exalt_font_family_2', 'Roboto Condensed' );
	
		if ( $body_font && 'Inter' != $body_font ) {
			$fonts_arr[] = $body_font;
		}
	
		if ( $headings_font && 'Roboto Condensed' != $headings_font ) {
			$fonts_arr[] = $headings_font;
		}

		/**
		 * Since 1.0.0
		 */
		$fonts_arr = apply_filters( 'exalt_fonts_array', $fonts_arr );
	
		if ( empty( $fonts_arr ) ) {
			return;
		}

		return $fonts_arr;
	}

endif;

if ( ! function_exists( 'exalt_get_fonts_url' ) ) :
	/**
	 * Gets the font url.
	 */
	function exalt_get_fonts_url() {
		$fonts_arr = exalt_get_fonts_array();

		if ( empty( $fonts_arr ) ) {
			return;
		}
	
		$font_url = exalt_get_google_font_uri( $fonts_arr );

		return $font_url;
	}

endif;

/**
* Enqueue Google fonts.
*/
function exalt_load_fonts() {

	// Load default fonts.
	if ( 'Inter' == get_theme_mod( 'exalt_font_family_1', 'Inter' ) ) {
		wp_enqueue_style( 'exalt-font-inter', get_theme_file_uri( '/assets/css/font-inter.css' ), array(), EXALT_VERSION, 'all' );
	}
	if ( 'Roboto Condensed' == get_theme_mod( 'exalt_font_family_2', 'Roboto Condensed' ) ) {
		wp_enqueue_style( 'exalt-font-roboto-condensed', get_theme_file_uri( '/assets/css/font-roboto-condensed.css' ), array(), EXALT_VERSION, 'all' );
	}

	$font_url = exalt_get_fonts_url();

	if ( ! empty( $font_url ) ) {

		if ( ! is_admin() && ! is_customize_preview() ) {
			$font_url = wptt_get_webfont_url( esc_url_raw( $font_url ) );
		}
	
		// Load Google Fonts
		wp_enqueue_style( 'exalt-fonts', $font_url, array(), null, 'screen' );

	}

}
add_action( 'wp_enqueue_scripts', 'exalt_load_fonts' );

/**
 * Display custom color CSS in customizer and on frontend.
 */
function exalt_custom_css_wrap() {
	require_once get_parent_theme_file_path( 'inc/css-output.php' );
	?>

	<style type="text/css" id="exalt-custom-css">
		<?php echo wp_strip_all_tags( exalt_custom_css() ); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'exalt_custom_css_wrap' );

/**
 * Display custom font CSS in customizer and on frontend.
 */
function exalt_custom_typography_wrap() {
	if ( is_admin() ) {
		return;
	}
	?>

	<style type="text/css" id="exalt-fonts-css">
		<?php echo wp_strip_all_tags( exalt_custom_typography_css() ); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'exalt_custom_typography_wrap' );

/**
 * Block editor related functions. 
 */
require get_template_directory() . '/inc/block-editor.php';

/**
 * Theme Info Page.
 */
require get_template_directory() . '/inc/dashboard/theme-info.php';


/**
 * Load Structure.
 */
require get_template_directory() . '/inc/structure/header.php';
require get_template_directory() . '/inc/structure/navigation.php';
require get_template_directory() . '/inc/structure/featured.php';