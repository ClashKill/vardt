<?php
/**
 * Exalt Theme Customizer
 *
 * @package Exalt
 */

 /**
  * Set up customizer helpers early.
  */
function exalt_get_customizer_helpers() {
	require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';
}
add_action( 'customize_register', 'exalt_get_customizer_helpers', 1 );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function exalt_customize_register( $wp_customize ) {

	// Custom Controls.
	$wp_customize->register_control_type( 'Exalt_Responsive_Number_Control' );
	$wp_customize->register_control_type( 'Exalt_Slider_Control' );

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_control( 'blogname' )->priority         = 1;
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_control( 'blogdescription' )->priority  = 3;
	$wp_customize->get_control( 'background_color' )->priority  = 2;
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	// Hide the checkbox "Display site title and tagline"
	$wp_customize->remove_control( 'display_header_text' );

	$wp_customize->get_control( 'header_textcolor' )->priority 	= 1;
	$wp_customize->get_control( 'header_textcolor' )->label 	= esc_html__( 'Site title / tagline color', 'exalt' );
	$wp_customize->get_section( 'header_image' )->panel 		= 'exalt_panel_header';
	$wp_customize->get_section( 'header_image' )->priority 		= 50;

	// uri for the customizer images folder
	$images_uri = get_template_directory_uri() . '/inc/customizer/assets/images/'; 

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'exalt_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'exalt_customize_partial_blogdescription',
			)
		);
	}

	// Latest articles section title on front page?
	$wp_customize->add_setting(
		'exalt_blog_section_title',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'exalt_blog_section_title',
		array(
			'settings'			=> 'exalt_blog_section_title',
			'section'			=> 'static_front_page',
			'type'				=> 'text',
			'label'				=> esc_html__( 'Front page blog section title.', 'exalt' ),
			'active_callback'	=> 'exalt_is_showing_blog_on_front'
		)
	);

	// Hide site title
	$wp_customize->add_setting(
		'exalt_hide_site_title',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_hide_site_title',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Hide site title', 'exalt' ),
			'priority'	  => 2,
			'section'     => 'title_tagline',
		)
	);

	// Hide site title
	$wp_customize->add_setting(
		'exalt_hide_site_tagline',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_hide_site_tagline',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Hide site tagline', 'exalt' ),
			'priority'	  => 4,
			'section'     => 'title_tagline',
		)
	);

	// Logo Max Width
	$wp_customize->add_setting(
		'exalt_logo_max_width_desktop',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_blank'
		)
	);
	// Logo Max Width - Tab.
	$wp_customize->add_setting(
		'exalt_logo_max_width_tablet',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_blank'
		)
	);
	// Logo Max Width - Mobile.
	$wp_customize->add_setting(
		'exalt_logo_max_width_mobile',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_blank'
		)
	);
	$wp_customize->add_control( 
		new Exalt_Responsive_Number_Control( $wp_customize, 'exalt_logo_max_width',
		array(
			'label'         => esc_html__( 'Logo Max Width (px)', 'exalt' ),
			'section'       => 'title_tagline',
			'settings'      => array(
				'desktop'   => 'exalt_logo_max_width_desktop',
				'tablet'    => 'exalt_logo_max_width_tablet',
				'mobile'    => 'exalt_logo_max_width_mobile'
			),
			'active_callback'	=> 'exalt_has_custom_logo'
		)
	) );

	// Logo Max Height
	$wp_customize->add_setting(
		'exalt_logo_max_height_desktop',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
		)
	);
	// Logo Max Height - Tab.
	$wp_customize->add_setting(
		'exalt_logo_max_height_tablet',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
		)
	);
	// Logo Max Height - Mobile.
	$wp_customize->add_setting(
		'exalt_logo_max_height_mobile',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
		)
	);
	$wp_customize->add_control( 
		new Exalt_Responsive_Number_Control( $wp_customize, 'exalt_logo_max_height',
		array(
			'label'         => esc_html__( 'Logo Max Height (px)', 'exalt' ),
			'section'       => 'title_tagline',
			'settings'      => array(
				'desktop'   => 'exalt_logo_max_height_desktop',
				'tablet'    => 'exalt_logo_max_height_tablet',
				'mobile'    => 'exalt_logo_max_height_mobile'
			),
			'active_callback'	=> 'exalt_has_custom_logo'
		)
	) );

	// Color Section
	// Primary Color.
	$wp_customize->add_setting(
		'exalt_primary_color',
		array(
			'default'			=> '#FC5656',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_primary_color',
			array(
				'section'		    => 'colors',
				'priority'			=> 1,
				'label'			    => esc_html__( 'Theme Primary Color', 'exalt' ),
			)
		)
	);

	// Boxed Inner Background Color.
	$wp_customize->add_setting(
		'exalt_boxed_inner_bg_color',
		array(
			'default'			=> '#ffffff',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_boxed_inner_bg_color',
			array(
				'section'		    => 'colors',
				'label'			    => esc_html__( 'Inner Background Color', 'exalt' ),
				'active_callback'	=> 'exalt_is_boxed_layout_active'
			)
		)
	);

	// Text Color.
	$wp_customize->add_setting(
		'exalt_text_color',
		array(
			'default'			=> '#2c2b2b',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_text_color',
			array(
				'section'		    => 'colors',
				'label'			    => esc_html__( 'Text Color', 'exalt' ),
			)
		)
	);

	// Headings Text Color.
	$wp_customize->add_setting(
		'exalt_headings_text_color',
		array(
			'default'			=> '#2c2b2b',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_headings_text_color',
			array(
				'section'		    => 'colors',
				'label'			    => esc_html__( 'Headings Text Color', 'exalt' ),
			)
		)
	);

	// Link Color.
	$wp_customize->add_setting(
		'exalt_links_color',
		array(
			'default'			=> '#2c2b2b',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_links_color',
			array(
				'section'		    => 'colors',
				'label'			    => esc_html__( 'Links Color', 'exalt' ),
			)
		)
	);

	// Link Color - Hover .
	$wp_customize->add_setting(
		'exalt_links_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_links_hover_color',
			array(
				'section'		    => 'colors',
				'label'			    => esc_html__( 'Links Color:Hover', 'exalt' ),
			)
		)
	);

	// Button Background Color.
	$wp_customize->add_setting(
		'exalt_button_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_button_bg_color',
			array(
				'section'		    => 'colors',
				'label'			    => esc_html__( 'Button Background Color', 'exalt' ),
			)
		)
	);

	// Button Background Color - Hover .
	$wp_customize->add_setting(
		'exalt_button_bg_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_button_bg_hover_color',
			array(
				'section'		    => 'colors',
				'label'			    => esc_html__( 'Button Background Color:Hover', 'exalt' ),
			)
		)
	);

	// Button Text Color.
	$wp_customize->add_setting(
		'exalt_button_text_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_button_text_color',
			array(
				'section'		    => 'colors',
				'label'			    => esc_html__( 'Button Text Color', 'exalt' ),
			)
		)
	);

	// Button Text Color - Hover .
	$wp_customize->add_setting(
		'exalt_button_text_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_button_text_hover_color',
			array(
				'section'		    => 'colors',
				'label'			    => esc_html__( 'Button Text Color:Hover', 'exalt' ),
			)
		)
	);

	// Typography Options Section
	$wp_customize->add_section(
		'exalt_typography_section',
		array(
			'title' 		=> esc_html__( 'Typography', 'exalt' ),
			'description' 	=> esc_html__( 'If you select a "Google" font it will be automatically downloaded and served locally from your server.', 'exalt' ),
			'priority' 		=> 50
		)
	);

	$wp_customize->add_setting( 
		'exalt_font_family_1',
		array(
			'default'           => 'Inter',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 
		new Exalt_Fonts_Control( $wp_customize, 'exalt_font_family_1',
		array(
			'label'         => esc_html__( 'Body Font', 'exalt' ),
			'section'       => 'exalt_typography_section',
			'settings'      => 'exalt_font_family_1'
		)
	) );

	$wp_customize->add_setting( 
		'exalt_font_family_2',
		array(
			'default'           => 'Roboto Condensed',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 
		new Exalt_Fonts_Control( $wp_customize, 'exalt_font_family_2',
		array(
			'label'         => esc_html__( 'Headings Font', 'exalt' ),
			'section'       => 'exalt_typography_section',
			'settings'      => 'exalt_font_family_2'
		)
	) );

	$wp_customize->add_setting(
		'exalt_headings_font_weight',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_headings_font_weight',
		array(
			'settings'		=> 'exalt_headings_font_weight',
			'section'		=> 'exalt_typography_section',
			'type'			=> 'select',
			'label'			=> esc_html__( 'Headings Font Weight', 'exalt' ),
			'description'	=> esc_html__( 'Only the font supported font weights will be applied.', 'exalt' ),
			'choices'		=> array(
				''          => esc_html__( 'Default', 'exalt' ),
				'100'       => esc_html__( 'Thin: 100', 'exalt' ),
				'200'       => esc_html__( 'Extra Light: 200', 'exalt' ),
				'300'       => esc_html__( 'Light: 300', 'exalt' ),
				'400'       => esc_html__( 'Normal: 400', 'exalt' ),
				'500'       => esc_html__( 'Medium: 500', 'exalt' ),
				'600'       => esc_html__( 'Semi Bold: 600', 'exalt' ),
				'700'       => esc_html__( 'Bold: 700', 'exalt' ),
				'800'       => esc_html__( 'Extra Bold: 800', 'exalt' ),
				'900'       => esc_html__( 'Black: 900', 'exalt' )
			)
		)
	);

	// Site Title Font Size - Desktop.
	$wp_customize->add_setting(
		'exalt_site_title_desktop_font_size',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
		)
	);
	// Site Title Font Size - Tab.
	$wp_customize->add_setting(
		'exalt_site_title_tablet_font_size',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
		)
	);
	// Site Title Font Size - Mobile.
	$wp_customize->add_setting(
		'exalt_site_title_mobile_font_size',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
		)
	);
	$wp_customize->add_control( 
		new Exalt_Responsive_Number_Control( $wp_customize, 'exalt_site_title_font_size',
		array(
			'label'         => esc_html__( 'Site Title Font Size', 'exalt' ),
			'description' 	=> esc_html__( 'You can add: px-em-rem', 'exalt' ),
			'section'       => 'exalt_typography_section',
			'settings'      => array(
				'desktop'   => 'exalt_site_title_desktop_font_size',
				'tablet'    => 'exalt_site_title_tablet_font_size',
				'mobile'    => 'exalt_site_title_mobile_font_size'
			)
		)
	) );

	// Article Font Size - Desktop.
	$wp_customize->add_setting(
		'exalt_post_desktop_font_size',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
		)
	);
	// Article Font Size - Tab.
	$wp_customize->add_setting(
		'exalt_post_tablet_font_size',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
		)
	);
	// Article Font Size - Mobile.
	$wp_customize->add_setting(
		'exalt_post_mobile_font_size',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
		)
	);
	$wp_customize->add_control( 
		new Exalt_Responsive_Number_Control( $wp_customize, 'exalt_post_font_size',
		array(
			'label'         => esc_html__( 'Single Post Content Font Size', 'exalt' ),
			'description' 	=> esc_html__( 'You can add: px-em-rem', 'exalt' ),
			'section'       => 'exalt_typography_section',
			'settings'      => array(
				'desktop'   => 'exalt_post_desktop_font_size',
				'tablet'    => 'exalt_post_tablet_font_size',
				'mobile'    => 'exalt_post_mobile_font_size'
			)
		)
	) );

	// General Settings Panel
	$wp_customize->add_panel(
		'exalt_panel_general_settings',
		array(
			'priority' 			=> 190,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'General', 'exalt' )
		)
	);

	// General Settings Section
	$wp_customize->add_section(
		'exalt_site_layout_section',
		array(
			'title' => esc_html__( 'Site Layout', 'exalt' ),
			'panel' => 'exalt_panel_general_settings'
		)
	);

	// General - Site Layout
	$wp_customize->add_setting(
		'exalt_site_layout',
		array(
			'default' => 'boxed',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_site_layout',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Site Layout', 'exalt' ),
			'section' => 'exalt_site_layout_section',
			'choices' => array(
				'wide' => esc_html__( 'Wide', 'exalt' ),
				'boxed' => esc_html__( 'Boxed', 'exalt' )
			)
		)
	);

	// General - Site container width
	$wp_customize->add_setting( 
		'exalt_container_width',
		array(
			'default'           => 1280,
			'sanitize_callback' => 'exalt_sanitize_slider_number_input',
			'transport'         => 'postMessage'
		)
	);
	$wp_customize->add_control( 
		new Exalt_Slider_Control( $wp_customize, 'exalt_container_width',
		array(
			'label'         => esc_html__( 'Container Width (px)', 'exalt' ),
			'section'       => 'exalt_site_layout_section',
			'choices'       => array(
				'min'   => 300,
				'max'   => 2000,
				'step'  => 1,
			),
			'active_callback' => 'exalt_is_wide_layout_active'
		)
	) );

	// General - Boxed Layout width
	$wp_customize->add_setting( 
		'exalt_boxed_width',
		array(
			'default'           => 1380,
			'sanitize_callback' => 'exalt_sanitize_slider_number_input',
			'transport'         => 'postMessage'
		)
	);
	$wp_customize->add_control( 
		new Exalt_Slider_Control( $wp_customize, 'exalt_boxed_width',
		array(
			'label'         => esc_html__( 'Boxed Layout Width (px)', 'exalt' ),
			'section'       => 'exalt_site_layout_section',
			'choices'       => array(
				'min'   => 300,
				'max'   => 2000,
				'step'  => 1,
			),
			'active_callback' => 'exalt_is_boxed_layout_active'
		)
	) );

	// General - Sidebar width
	$wp_customize->add_setting( 
		'exalt_sidebar_width',
		array(
			'default'           => 29.6875,
			'sanitize_callback' => 'exalt_sanitize_slider_number_input',
			//'transport'         => 'postMessage'
		)
	);
	$wp_customize->add_control( 
		new Exalt_Slider_Control( $wp_customize, 'exalt_sidebar_width',
		array(
			'label'         => esc_html__( 'Sidebar Width (%)', 'exalt' ),
			'description'	=> esc_html__( 'This value applies only when the sidebar is active.', 'exalt' ),
			'section'       => 'exalt_site_layout_section',
			'choices'       => array(
				'min'   => 15,
				'max'   => 50,
				'step'  => 1,
			)
		)
	) );

	// Breadcrumb Settings Section
	$wp_customize->add_section(
		'exalt_breadcrumb_section',
		array(
			'title' => esc_html__( 'Breadcrumb', 'exalt' ),
			'panel' => 'exalt_panel_general_settings'
		)
	);

	$wp_customize->add_setting(
		'exalt_breadcrumb_source',
		array(
			'default' => 'none',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_breadcrumb_source',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Breadcrumb Source', 'exalt' ),
			'section' => 'exalt_breadcrumb_section',
			'choices' => array(
				'none' 			=> esc_html__( 'None', 'exalt' ),
				'yoast' 		=> esc_html__( 'Yoast SEO Breadcrumbs', 'exalt' ),
				'navxt' 		=> esc_html__( 'Breadcrumb NavXT', 'exalt' ),
				'rankmath' 		=> esc_html__( 'RankMath Breadcrumbs', 'exalt' ),
			)
		)
	);

	$wp_customize->add_setting(
		'exalt_breadcrumb_location',
		array(
			'default' => 'before-entry-header',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_breadcrumb_location',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Breadcrumb Location', 'exalt' ),
			'section' => 'exalt_breadcrumb_section',
			'choices' => array(
				'after-site-header'		=> esc_html__( 'After Site Header', 'exalt' ),
				'before-entry-header'	=> esc_html__( 'Before Article Header', 'exalt' )
			),
			'active_callback' => 'exalt_is_showing_breadcrumb'
		)
	);

	// General - Featured images rounded borders
	/*$wp_customize->add_setting(
		'exalt_images_rounded_borders',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_images_rounded_borders',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Make corners rounded on featured images', 'exalt' ),
			'section'     => 'exalt_site_layout_section',
		)
	);*/

	// Header Settings Panel
	$wp_customize->add_panel(
		'exalt_panel_header',
		array(
			'priority' 			=> 192,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'Header', 'exalt' )
		)
	);

	$wp_customize->add_section(
		'exalt_header_layout_section',
		array(
			'title' => esc_html__( 'Appearance', 'exalt' ),
			'priority' => 5,
			'panel'	=> 'exalt_panel_header'
		)
	);

	// Header Layout
	$wp_customize->add_setting(
		'exalt_header_layout',
		array(
			'default' => 'default',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_header_layout',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Header Layout', 'exalt' ),
			'section' => 'exalt_header_layout_section',
			'choices' => array(
				'default' => esc_html__( 'Default Layout', 'exalt' ),
				'single-line' => esc_html__( 'Single Line Layout', 'exalt' )
			)
		)
	);

	// Header Width.
	$wp_customize->add_setting(
		'exalt_header_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_header_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Header Width', 'exalt' ),
			'section' => 'exalt_header_layout_section',
			'choices' => array(
				'contained' => esc_html__( 'Contained', 'exalt' ),
				'full' => esc_html__( 'Full', 'exalt' )
			)
		)
	);

	// Header - Logo Alignment
	$wp_customize->add_setting(
		'exalt_logo_align',
		array(
			'default' => 'left',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_logo_align',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Logo Alignment', 'exalt' ),
			'section' => 'exalt_header_layout_section',
			'choices' => array(
				'left'		=> esc_html__( 'Left', 'exalt' ),
				'center'	=> esc_html__( 'Center', 'exalt' ),
				'right'		=> esc_html__( 'Right', 'exalt' )
			),
			'active_callback' => 'exalt_is_default_header'
		)
	);

	// Social Menu - show next to logo.
	$wp_customize->add_setting(
		'exalt_social_beside_logo',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_social_beside_logo',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Display Social Menu beside site branding', 'exalt' ),
			'section'     => 'exalt_header_layout_section',
			'active_callback' => 'exalt_is_default_header'
		)
	);

	// Social Menu - show next to logo.
	$wp_customize->add_setting(
		'exalt_social_beside_pmenu',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_social_beside_pmenu',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Display Social Menu beside primary menu', 'exalt' ),
			'section'     		=> 'exalt_header_layout_section',
			'active_callback' 	=> 'exalt_is_line_header'
		)
	);

	// Header Padding Top - Desktop
	$wp_customize->add_setting(
		'exalt_header_padding_top_desktop',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_blank'
		)
	);
	// Header Padding Top - Tablet
	$wp_customize->add_setting(
		'exalt_header_padding_top_tablet',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_blank'
		)
	);
	// Header Padding Top - Mobile
	$wp_customize->add_setting(
		'exalt_header_padding_top_mobile',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_blank'
		)
	);
	$wp_customize->add_control( 
		new Exalt_Responsive_Number_Control( $wp_customize, 'exalt_header_padding_top',
		array(
			'label'         => esc_html__( 'Header Padding Top (px)', 'exalt' ),
			'section'       => 'exalt_header_layout_section',
			'settings'      => array(
				'desktop'   => 'exalt_header_padding_top_desktop',
				'tablet'    => 'exalt_header_padding_top_tablet',
				'mobile'    => 'exalt_header_padding_top_mobile'
			)
		)
	) );

	// Header Padding Bottom - Desktop
	$wp_customize->add_setting(
		'exalt_header_padding_bottom_desktop',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_blank'
		)
	);
	// Header Padding Bottom - Tablet
	$wp_customize->add_setting(
		'exalt_header_padding_bottom_tablet',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_blank'
		)
	);
	// Header Padding Bottom - Mobile
	$wp_customize->add_setting(
		'exalt_header_padding_bottom_mobile',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_blank'
		)
	);
	$wp_customize->add_control( 
		new Exalt_Responsive_Number_Control( $wp_customize, 'exalt_header_padding_bottom',
		array(
			'label'         => esc_html__( 'Header Padding Bottom (px)', 'exalt' ),
			'section'       => 'exalt_header_layout_section',
			'settings'      => array(
				'desktop'   => 'exalt_header_padding_bottom_desktop',
				'tablet'    => 'exalt_header_padding_bottom_tablet',
				'mobile'    => 'exalt_header_padding_bottom_mobile'
			)
		)
	) );

	// Header BG Color
	$wp_customize->add_setting(
		'exalt_header_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_header_bg_color',
			array(
				'section'		    => 'exalt_header_layout_section',
				'label'			    => esc_html__( 'Header Background Color', 'exalt' ),
			)
		)
	);

	// Menu Section
	$wp_customize->add_section(
		'exalt_primary_menu_section',
		array(
			'title' => esc_html__( 'Primary Menu', 'exalt' ),
			'priority' => 10,
			'panel'	=> 'exalt_panel_header'
		)
	);

	// Header - Menu Width.
	$wp_customize->add_setting(
		'exalt_menu_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_menu_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Menu Width', 'exalt' ),
			'section' => 'exalt_primary_menu_section',
			'choices' => array(
				'contained' => esc_html__( 'Contained', 'exalt' ),
				'full' => esc_html__( 'Full', 'exalt' )
			),
			'active_callback' => 'exalt_is_default_header'
		)
	);

	// Header - Menu Inner Width.
	$wp_customize->add_setting(
		'exalt_menu_inner_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_menu_inner_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Menu Inner Width', 'exalt' ),
			'section' => 'exalt_primary_menu_section',
			'choices' => array(
				'contained' => esc_html__( 'Contained', 'exalt' ),
				'full' => esc_html__( 'Full', 'exalt' )
			),
			'active_callback' => 'exalt_is_default_header'
		)
	);

	// Header - Menu Alignment
	$wp_customize->add_setting(
		'exalt_menu_align',
		array(
			'default' => 'left',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_menu_align',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Menu Alignment', 'exalt' ),
			'section' => 'exalt_primary_menu_section',
			'choices' => array(
				'left'		=> esc_html__( 'Left', 'exalt' ),
				'center'	=> esc_html__( 'Center', 'exalt' ),
				'right'		=> esc_html__( 'Right', 'exalt' )
			),
			'priority'	=> 10,
			'active_callback' => 'exalt_is_default_header'
		)
	);

	// Menu - show Search on menu
	$wp_customize->add_setting(
		'exalt_show_search_onmenu',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_search_onmenu',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Display Search Box', 'exalt' ),
			'section'     => 'exalt_primary_menu_section',
			'priority'	  => 15,
		)
	);

	// Menu BG Color
	$wp_customize->add_setting(
		'exalt_menu_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_menu_bg_color',
			array(
				'section'		    => 'exalt_primary_menu_section',
				'label'			    => esc_html__( 'Menu Background Color', 'exalt' ),
				'priority'	  		=> 20,
			)
		)
	);

	// Menu Links Color
	$wp_customize->add_setting(
		'exalt_menu_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_menu_link_color',
			array(
				'section'		    => 'exalt_primary_menu_section',
				'label'			    => esc_html__( 'Menu Link Color', 'exalt' ),
				'priority'	  		=> 25,
			)
		)
	);

	// Menu Links Color: Hover
	$wp_customize->add_setting(
		'exalt_menu_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_menu_link_hover_color',
			array(
				'section'		    => 'exalt_primary_menu_section',
				'label'			    => esc_html__( 'Menu Link Color: Hover/Active', 'exalt' ),
				'priority'	  		=> 30,
			)
		)
	);

	// Menu Links Color: Action
	$wp_customize->add_setting(
		'exalt_menu_link_action_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_menu_link_action_hover_color',
			array(
				'section'		    => 'exalt_primary_menu_section',
				'label'			    => esc_html__( 'Menu Link Action Color: Hover/Active', 'exalt' ),
				'priority'	  		=> 35,
			)
		)
	);

	// Dropdown Menu BG Color
	$wp_customize->add_setting(
		'exalt_dropdown_menu_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_dropdown_menu_bg_color',
			array(
				'section'		    => 'exalt_primary_menu_section',
				'label'			    => esc_html__( 'Dropdown Menu Background Color', 'exalt' ),
				'priority'	  		=> 40,
			)
		)
	);

	// Dropdown Menu Link Color
	$wp_customize->add_setting(
		'exalt_dropdown_menu_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_dropdown_menu_link_color',
			array(
				'section'		    => 'exalt_primary_menu_section',
				'label'			    => esc_html__( 'Dropdown Menu Link Color', 'exalt' ),
				'priority'	  		=> 45,
			)
		)
	);

	// Dropdown Menu Link Hover Color
	$wp_customize->add_setting(
		'exalt_dropdown_menu_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_dropdown_menu_link_hover_color',
			array(
				'section'		    => 'exalt_primary_menu_section',
				'label'			    => esc_html__( 'Dropdown Menu Link Color: Hover/Active', 'exalt' ),
				'priority'	  		=> 50,
			)
		)
	);

	// Dropdown Menu Link Hover Background Color
	$wp_customize->add_setting(
		'exalt_dropdown_menu_link_hover_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_dropdown_menu_link_hover_bg_color',
			array(
				'section'		    => 'exalt_primary_menu_section',
				'label'			    => esc_html__( 'Dropdown Menu Link Background Color: Hover/Active', 'exalt' ),
				'priority'	  		=> 55,
			)
		)
	);

	// Top Bar Section
	$wp_customize->add_section(
		'exalt_topbar_section',
		array(
			'title' => esc_html__( 'Top Bar', 'exalt' ),
			'priority' => 15,
			'panel'	=> 'exalt_panel_header'
		)
	);

	// Header Width.
	$wp_customize->add_setting(
		'exalt_topbar_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_topbar_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Top Bar Inner Width', 'exalt' ),
			'section' => 'exalt_topbar_section',
			'choices' => array(
				'contained' => esc_html__( 'Contained', 'exalt' ),
				'full' => esc_html__( 'Full', 'exalt' )
			)
		)
	);

	// Topbar - show social on topbar
	$wp_customize->add_setting(
		'exalt_display_social_topbar',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_display_social_topbar',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Display social menu on topbar', 'exalt' ),
			'section'     => 'exalt_topbar_section',
		)
	);

	// Topbar BG Color
	$wp_customize->add_setting(
		'exalt_topbar_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_topbar_bg_color',
			array(
				'section'		    => 'exalt_topbar_section',
				'label'			    => esc_html__( 'Background Color', 'exalt' ),
			)
		)
	);

	// Topbar Links Color
	$wp_customize->add_setting(
		'exalt_topbar_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_topbar_link_color',
			array(
				'section'		    => 'exalt_topbar_section',
				'label'			    => esc_html__( 'Link Color', 'exalt' ),
			)
		)
	);

	// Menu Links Color: Hover
	$wp_customize->add_setting(
		'exalt_topbar_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_topbar_link_hover_color',
			array(
				'section'		    => 'exalt_topbar_section',
				'label'			    => esc_html__( 'Link Color: Hover/Active', 'exalt' ),
			)
		)
	);

	// Topbar Text Color
	$wp_customize->add_setting(
		'exalt_topbar_text_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_topbar_text_color',
			array(
				'section'		    => 'exalt_topbar_section',
				'label'			    => esc_html__( 'Text Color', 'exalt' ),
			)
		)
	);

	// Header CTA section
	$wp_customize->add_section(
		'exalt_header_cta_section',
		array(
			'title' => esc_html__( 'Call to Action Button', 'exalt' ),
			'priority' => 20,
			'panel'	=> 'exalt_panel_header'
		)
	);

	// Header - show header cta on desktop header
	$wp_customize->add_setting(
		'exalt_show_header_cta',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_header_cta',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Display on header', 'exalt' ),
			'section'     => 'exalt_header_cta_section',
		)
	);

	// Header - show header cta on mobile header
	$wp_customize->add_setting(
		'exalt_hide_cta_mobile',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_hide_cta_mobile',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Hide on Mobile Header', 'exalt' ),
			'section'     => 'exalt_header_cta_section',
		)
	);	

	$wp_customize->add_setting(
		'exalt_header_cta_txt',
		array(
			'default'			=> esc_html__( 'SUBSCRIBE', 'exalt' ),
			'sanitize_callback'	=> 'exalt_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'exalt_header_cta_txt',
		array(
			'section'		=> 'exalt_header_cta_section',
			'type'			=> 'text',
			'label'			=> esc_html__( 'Button Text', 'exalt' ),
		)
	);

	$wp_customize->add_setting(
		'exalt_header_cta_url',
		array(
			'default'			=> '',
			'sanitize_callback'	=> 'exalt_sanitize_url'
		)
	);
	$wp_customize->add_control(
		'exalt_header_cta_url',
		array(
			'section'		=> 'exalt_header_cta_section',
			'type'			=> 'url',
			'label'			=> esc_html__( 'Button URL', 'exalt' ),
		)
	);

	// Header - Cta open link in new window
	$wp_customize->add_setting(
		'exalt_header_cta_target',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_header_cta_target',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Open link in new window', 'exalt' ),
			'section'     => 'exalt_header_cta_section',
		)
	);

	// Slide-out Sidebar
	$wp_customize->add_section(
		'exalt_slideoutsb_section',
		array(
			'title' => esc_html__( 'Slide-out Sidebar', 'exalt' ),
			'priority' => 20,
			'panel'	=> 'exalt_panel_header'
		)
	);

	// Header - Show slideout sidebar
	$wp_customize->add_setting(
		'exalt_show_slideout_sb',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_slideout_sb',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Slide-out sidebar', 'exalt' ),
			'description' => sprintf(
				/* translators: %s: link to Slide Out Sidebar widget panel in Customizer. */
				esc_html__( 'Show a Slide-out sidebar in the header, which you can populate by adding widgets %1$s.', 'exalt' ),
				'<a rel="goto-section" href="#sidebar-widgets-header-1">' . esc_html__( 'here', 'exalt' ) . '</a>'
			),
			'section'     => 'exalt_slideoutsb_section',
		)
	);

	// Header - show Primary Menu on slide out sidebar
	$wp_customize->add_setting(
		'exalt_show_pmenu_onslideout',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_pmenu_onslideout',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Primary Menu on Slide-out sidebar', 'exalt' ),
			'section'     => 'exalt_slideoutsb_section',
			'active_callback'	=> 'exalt_is_slideout_active'
		)
	);

	// Header - slide out menu position
	$wp_customize->add_setting(
		'exalt_slideout_btn_loc',
		array(
			'default'           => 'primary-menu',
			'sanitize_callback' => 'exalt_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'exalt_slideout_btn_loc',
		array(
			'type'    => 'select',
			'label'   => esc_html__( 'Slide-out sidebar toggle button location', 'exalt' ),
			'choices' => array(
				'top-bar'		=> esc_html__( 'On top bar', 'exalt' ),
				'before-logo'	=> esc_html__( 'Before site title/logo', 'exalt' ),
				'primary-menu'	=> esc_html__( 'On Primary Menu', 'exalt' )
			),
			'section' => 'exalt_slideoutsb_section',
			'active_callback'	=> 'exalt_is_slideout_active'
		)
	);

	// Mobile Sidebar
	$wp_customize->add_section(
		'exalt_mobile_menu_section',
		array(
			'title' => esc_html__( 'Mobile Menu', 'exalt' ),
			'priority' => 20,
			'panel'	=> 'exalt_panel_header'
		)
	);

	// Header - show Primary Menu on mobile sidebar.
	$wp_customize->add_setting(
		'exalt_show_social_mobile_menu',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_social_mobile_menu',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Social Menu on Mobile Menu', 'exalt' ),
			'section'     => 'exalt_mobile_menu_section'
		)
	);

	// Header - Show Secondary Menu on mobile sidebar.
	$wp_customize->add_setting(
		'exalt_show_top_nav_on_mobile_menu',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_top_nav_on_mobile_menu',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Top Bar Menu on Mobile Menu', 'exalt' ),
			'description' => esc_html__( 'Top bar menu will display on the mobile menu just after the primary menu.', 'exalt' ),
			'section'     => 'exalt_mobile_menu_section'
		)
	);

	// Header - Show slide out sidebar widgets in Mobile Menu Sidebar
	$wp_customize->add_setting(
		'exalt_show_slideout_widgets_on_mobile_menu',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_slideout_widgets_on_mobile_menu',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Slide-out sidebar widgets on Mobile Menu', 'exalt' ),
			'section'     => 'exalt_mobile_menu_section',
			'active_callback'	=> 'exalt_is_slideout_active'
		)
	);

	// Header Image Location
	$wp_customize->add_setting(
		'exalt_header_image_location',
		array(
			'default' => 'before-header-inner',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_header_image_location',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Header Image Position', 'exalt' ),
			'section' => 'header_image',
			'choices' => array(
				'before-header-inner'	=> esc_html__( 'Before Logo + Content', 'exalt' ),
				'after-header-inner'	=> esc_html__( 'After Logo + Content', 'exalt' ),
				'before-site-header'	=> esc_html__( 'Before Site Header', 'exalt' ),
				'after-site-header'		=> esc_html__( 'After Site Header', 'exalt' ),
				'header-background'		=> esc_html__( 'Display as Header Background', 'exalt' ),
			)
		)
	);

	// Header image link to home?
	$wp_customize->add_setting(
		'exalt_link_header_image',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_link_header_image',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Link header image to homepage?', 'exalt' ),
			'section'     => 'header_image',
		)
	);

	// Featured Posts Section settings.
	$wp_customize->add_section(
		'exalt_featured_section',
		array(
			'title' => esc_html__( 'Featured Posts', 'exalt' ),
			'priority' => 193,
		)
	);

	// Show featured content
	$wp_customize->add_setting(
		'exalt_show_featured_content',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_featured_content',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Display featured posts.', 'exalt' ),
			'section'     => 'exalt_featured_section',
		)
	);

	// Featured Posts Source.
	$wp_customize->add_setting(
		'exalt_featured_posts_source',
		array(
			'default' => 'latest',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_featured_posts_source',
		array(
			'type' 		=> 'radio',
			'label' 	=> esc_html__( 'Featured Posts Source', 'exalt' ),
			'section' 	=> 'exalt_featured_section',
			'choices' 	=> array(
				'latest' 	=> esc_html__( 'Latest Posts', 'exalt' ),
				'category' 	=> esc_html__( 'By Category', 'exalt' ),
				'tag' 		=> esc_html__( 'By Tag', 'exalt' )
			)
		)
	);

	// Featured Posts Source - Category
	$wp_customize->add_setting(
		'exalt_featured_posts_category',
		array(
			'default'			=> '0',
			'sanitize_callback'	=> 'exalt_sanitize_category_dropdown'
		)
	);

	$wp_customize->add_control(
		new Exalt_Customize_Category_Control( 
			$wp_customize,
			'exalt_featured_posts_category', 
			array(
			    'label'   			=> esc_html__( 'Select the category for featured posts.', 'exalt' ),
			    'description'		=> esc_html__( 'Featured images of the posts from selected category will be displayed in the slider', 'exalt' ),
			    'section' 			=> 'exalt_featured_section',
				'active_callback'	=> 'exalt_is_fps_source_category'
			) 
		) 
	);

	// Featured Posts Source - Tag
	$wp_customize->add_setting(
		'exalt_featured_posts_tag',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'exalt_featured_posts_tag',
		array(
			'section'			=> 'exalt_featured_section',
			'type'				=> 'text',
			'label'				=> esc_html__( 'Enter the tag slug', 'exalt' ),
			'active_callback'	=> 'exalt_is_fps_source_tag'
		)
	);

	
	// Remove placeholder image
	$wp_customize->add_setting(
		'exalt_remove_placeholder',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_remove_placeholder',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Hide placeholder image.', 'exalt' ),
			'section'     => 'exalt_featured_section',
		)
	);

	// Blog Settings Panel
	$wp_customize->add_panel(
		'exalt_panel_blog',
		array(
			'priority' 			=> 194,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'Blog / Archive', 'exalt' )
		)
	);

	$wp_customize->add_section(
		'exalt_blog_layout_section',
		array(
			'title' => esc_html__( 'Layout', 'exalt' ),
			'priority' => 5,
			'panel'	=> 'exalt_panel_blog'
		)
	);

	// Archive Layout / Sidebar Alignment
	$wp_customize->add_setting(
		'exalt_archive_layout',
		array(
			'default'			=> 'right-sidebar',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		new Exalt_Radio_Image_Control( 
			$wp_customize,
			'exalt_archive_layout',
			array(
				'section'		=> 'exalt_blog_layout_section',
				'label'			=> esc_html__( 'Blog Layout', 'exalt' ),
				'choices'		=> array(
					'right-sidebar'	        => $images_uri . '2cr.png',
					'left-sidebar' 	        => $images_uri . '2cl.png',
					'no-sidebar' 		    => $images_uri . '1c.png',
					'center-content' 	    => $images_uri . '1cc.png'
				)
			)
		)
	);

	// Entries Layout
	$wp_customize->add_setting(
		'exalt_entries_layout',
		array(
			'default' => 'list',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_entries_layout',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Entries Layout', 'exalt' ),
			'section' => 'exalt_blog_layout_section',
			'choices' => array(
				'list' => esc_html__( 'List', 'exalt' ),
				'grid' => esc_html__( 'Grid', 'exalt' )
			)
		)
	);

	// Number of grid columns.
	$wp_customize->add_setting(
		'exalt_entries_grid_columns',
		array(
			'default' => '2',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_entries_grid_columns',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Number of grid columns', 'exalt' ),
			'section' => 'exalt_blog_layout_section',
			'choices' => array(
				'2' => esc_html__( '2', 'exalt' ),
				'3' => esc_html__( '3', 'exalt' ),
				'4' => esc_html__( '4', 'exalt' ),
				'5' => esc_html__( '5', 'exalt' ),
				'6' => esc_html__( '6', 'exalt' ),
			),
			'active_callback'	=> 'exalt_is_entries_grid'
		)
	);

	// Archive - Featured Image Position.
	$wp_customize->add_setting(
		'exalt_archive_thumbnail_position',
		array(
			'default' 			=> exalt_get_archive_image_position_default(),
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_archive_thumbnail_position',
		array(
			'type' => 'radio',
			'label' => esc_html__( 'Featured Image Position', 'exalt' ),
			'section' => 'exalt_blog_layout_section',
			'choices' => array(
				'before-header' => esc_html__( 'Before article header', 'exalt' ),
				'after-header' => esc_html__( 'After article header', 'exalt' ),
				'beside-article' => esc_html__( 'Beside article', 'exalt' ),
				'beside-content' => esc_html__( 'Beside article content', 'exalt' ),
				'hidden' => esc_html__( 'Hidden', 'exalt' ),
			)
		)
	);

	// Archive Featured Image Align
	$wp_customize->add_setting(
		'exalt_archive_thumbnail_align',
		array(
			'default' => 'left',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_archive_thumbnail_align',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Featured Image Align', 'exalt' ),
			'section' => 'exalt_blog_layout_section',
			'choices' => array(
				'left' => esc_html__( 'Left', 'exalt' ),
				'right' => esc_html__( 'Right', 'exalt' )
			),
			'active_callback'	=> 'exalt_thumbnail_align_active'
		)
	);

	// Archive - Leave featured image uncropped
	$wp_customize->add_setting(
		'exalt_archive_image_crop',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_archive_image_crop',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Crop featured image to theme defined size? (changes require regenerating thumbnails for existing featured images)', 'exalt' ),
			'section'     => 'exalt_blog_layout_section',
		)
	);

	// Archive - Pagination Style
	$wp_customize->add_setting(
		'exalt_pagination_type',
		array(
			'default' => 'page-numbers',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_pagination_type',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Blog Pagination Style', 'exalt' ),
			'section' => 'exalt_blog_layout_section',
			'choices' => array(
				'page-numbers' => esc_html__( 'Numbers', 'exalt' ),
				'next-prev' => esc_html__( 'Next/Prev', 'exalt' )
			)
		)
	);

	$wp_customize->add_section(
		'exalt_blog_meta_section',
		array(
			'title' => esc_html__( 'Post Meta', 'exalt' ),
			'priority' => 15,
			'panel'	=> 'exalt_panel_blog'
		)
	);

	// Archive - Show category list
	$wp_customize->add_setting(
		'exalt_show_cat_links',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_cat_links',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show category links', 'exalt' ),
			'section'     => 'exalt_blog_meta_section',
		)
	);

	// Archive - Show author
	$wp_customize->add_setting(
		'exalt_show_author',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_author',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show author', 'exalt' ),
			'section'     => 'exalt_blog_meta_section',
		)
	);

	// Archive - Show author avatar
	$wp_customize->add_setting(
		'exalt_show_author_avatar',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_author_avatar',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show author avatar', 'exalt' ),
			'section'     => 'exalt_blog_meta_section',
			'active_callback'	=> 'exalt_is_showing_author'
		)
	);

	// Archive - Show date
	$wp_customize->add_setting(
		'exalt_show_date',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_date',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show date', 'exalt' ),
			'section'     => 'exalt_blog_meta_section',
		)
	);

	// Archive - Show time ago format
	$wp_customize->add_setting(
		'exalt_time_ago',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_time_ago',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Use "time ago" date format', 'exalt' ),
			'section'     		=> 'exalt_blog_meta_section',
			'active_callback'	=> 'exalt_is_showing_date'
		)
	);

	// Archive - Cut off for "time ago" date in days
	$wp_customize->add_setting(
		'exalt_time_ago_date_count',
		array(
			'default'			=> 14,
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_absint'
		)
	);
	$wp_customize->add_control(
		'exalt_time_ago_date_count',
		array(
			'section'			=> 'exalt_blog_meta_section',
			'type'				=> 'number',
			'label'				=> esc_html__( 'Cut off for "time ago" date in days.', 'exalt' ),
		)
	);	

	// Archive - Show updated date
	$wp_customize->add_setting(
		'exalt_show_updated_date',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_updated_date',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Show "last updated" date.', 'exalt' ),
			'description' 		=> esc_html__( 'When paired with the "time ago" date format, the cut off for that format will automatically be switched to one day.', 'exalt' ),
			'section'     		=> 'exalt_blog_meta_section',
			'active_callback'	=> 'exalt_is_showing_date'
		)
	);

	// Archive - Show comments link
	$wp_customize->add_setting(
		'exalt_show_comments_link',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_comments_link',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show comments link', 'exalt' ),
			'section'     => 'exalt_blog_meta_section',
		)
	);

	// Blog Section Content / Excerpt
	$wp_customize->add_section(
		'exalt_blog_content_section',
		array(
			'title' => esc_html__( 'Content / Excerpt', 'exalt' ),
			'priority' => 20,
			'panel'	=> 'exalt_panel_blog'
		)
	);

	// Archive Featured Image Align
	$wp_customize->add_setting(
		'exalt_content_type',
		array(
			'default' => 'excerpt',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_content_type',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Content Type', 'exalt' ),
			'section' => 'exalt_blog_content_section',
			'choices' => array(
				'excerpt' 	=> esc_html__( 'Excerpt', 'exalt' ),
				'content' 	=> esc_html__( 'Content', 'exalt' ),
				'none'		=> esc_html__( 'None', 'exalt' )
			)
		)
	);

	// Excerpt length.
	$wp_customize->add_setting(
		'exalt_excerpt_length',
		array(
			'default'			=> 35,
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_number_absint'
		)
	);
	$wp_customize->add_control(
		'exalt_excerpt_length',
		array(
			'section'			=> 'exalt_blog_content_section',
			'type'				=> 'number',
			'label'				=> esc_html__( 'Excerpt Length', 'exalt' ),
			'active_callback'	=> 'exalt_is_excerpt_type'
		)
	);

	// Archive - Read More Link
	$wp_customize->add_setting(
		'exalt_read_more_type',
		array(
			'default' => 'link',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_read_more_type',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Read More Link Type', 'exalt' ),
			'section' => 'exalt_blog_content_section',
			'choices' => array(
				'link'		=> esc_html__( 'Link', 'exalt' ),
				'button' 	=> esc_html__( 'Button', 'exalt' ),
				'none'		=> esc_html__( 'None', 'exalt' )
			)
		)
	);

	// Post Settings Panel
	$wp_customize->add_panel(
		'exalt_panel_post',
		array(
			'priority' 			=> 196,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'Single Posts', 'exalt' )
		)
	);

	$wp_customize->add_section(
		'exalt_post_layout_section',
		array(
			'title' => esc_html__( 'Layout', 'exalt' ),
			'priority' => 5,
			'panel'	=> 'exalt_panel_post'
		)
	);

	// Post Layout / Sidebar Alignment
	$wp_customize->add_setting(
		'exalt_post_layout',
		array(
			'default'			=> 'right-sidebar',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		new Exalt_Radio_Image_Control( 
			$wp_customize,
			'exalt_post_layout',
			array(
				'section'		=> 'exalt_post_layout_section',
				'label'			=> esc_html__( 'Post Layout', 'exalt' ),
				'choices'		=> array(
					'right-sidebar'	        => $images_uri . '2cr.png',
					'left-sidebar' 	        => $images_uri . '2cl.png',
					'no-sidebar' 		    => $images_uri . '1c.png',
					'center-content' 	    => $images_uri . '1cc.png'
				)
			)
		)
	);

	// Post - Featured Image Position.
	$wp_customize->add_setting(
		'exalt_post_image_position',
		array(
			'default' => 'after-header',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_post_image_position',
		array(
			'type' => 'radio',
			'label' => esc_html__( 'Featured Image Position', 'exalt' ),
			'section' => 'exalt_post_layout_section',
			'choices' => array(
				'before-header' => esc_html__( 'Before article header', 'exalt' ),
				'after-header' => esc_html__( 'After article header', 'exalt' ),
				'hidden' => esc_html__( 'Hidden', 'exalt' ),
			)
		)
	);

	// Post Meta Section
	$wp_customize->add_section(
		'exalt_post_meta_section',
		array(
			'title' => esc_html__( 'Post Meta', 'exalt' ),
			'priority' => 10,
			'panel'	=> 'exalt_panel_post'
		)
	);

	// Post - Show category list
	$wp_customize->add_setting(
		'exalt_show_cat_links_s',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_cat_links_s',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show category links', 'exalt' ),
			'section'     => 'exalt_post_meta_section',
		)
	);

	// Post - Show author
	$wp_customize->add_setting(
		'exalt_show_author_s',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_author_s',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show author', 'exalt' ),
			'section'     => 'exalt_post_meta_section',
		)
	);

	// Post - Show author avatar
	$wp_customize->add_setting(
		'exalt_show_author_avatar_s',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_author_avatar_s',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show author avatar', 'exalt' ),
			'section'     => 'exalt_post_meta_section',
			'active_callback'	=> 'exalt_is_showing_author_s'
		)
	);

	// Post - Show date
	$wp_customize->add_setting(
		'exalt_show_date_s',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_date_s',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show date', 'exalt' ),
			'section'     => 'exalt_post_meta_section',
		)
	);

	// Post - Show time ago format
	$wp_customize->add_setting(
		'exalt_time_ago_s',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_time_ago_s',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Use "time ago" date format', 'exalt' ),
			'description' => sprintf(
				/* translators: %s: link to the setting - Cut off for "time ago" date in days. */
				esc_html__( 'You can set the number of cut off days from %1$s.', 'exalt' ),
				'<a rel="goto-control" href="#exalt_time_ago_date_count">' . esc_html__( 'Blog Settings', 'exalt' ) . '</a>'
			),
			'section'     		=> 'exalt_post_meta_section',
			'active_callback'	=> 'exalt_is_showing_date_s'
		)
	);

	// Post - Show updated date format
	$wp_customize->add_setting(
		'exalt_show_updated_date_s',
		array(
			'default'           => false,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_updated_date_s',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Show "last updated" date.', 'exalt' ),
			'description' 		=> esc_html__( 'When paired with the "time ago" date format, the cut off for that format will automatically be switched to one day.', 'exalt' ),
			'section'     		=> 'exalt_post_meta_section',
			'active_callback'	=> 'exalt_is_showing_date_s'
		)
	);

	// Post - Show comments
	$wp_customize->add_setting(
		'exalt_show_comments_link_s',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_comments_link_s',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show comments link', 'exalt' ),
			'section'     => 'exalt_post_meta_section',
		)
	);

	// Post - Show tags
	$wp_customize->add_setting(
		'exalt_show_tags_list_s',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_tags_list_s',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show tags list', 'exalt' ),
			'section'     => 'exalt_post_meta_section',
		)
	);

	// Post Meta Section
	$wp_customize->add_section(
		'exalt_post_content_section',
		array(
			'title' => esc_html__( 'Post Content', 'exalt' ),
			'priority' => 10,
			'panel'	=> 'exalt_panel_post'
		)
	);
	
	// Post - Show category list
	$wp_customize->add_setting(
		'exalt_post_previous_next',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_post_previous_next',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Display previous and next links at the bottom of each post.', 'exalt' ),
			'section'     => 'exalt_post_content_section',
		)
	);
	
	// Post - Show category list
	$wp_customize->add_setting(
		'exalt_show_author_bio',
		array(
			'default'           => true,
			'sanitize_callback' => 'exalt_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'exalt_show_author_bio',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Display author bio at the bottom of the post.', 'exalt' ),
			'section'     => 'exalt_post_content_section',
		)
	);

	// Page Settings Section
	$wp_customize->add_section(
		'exalt_page_section',
		array(
			'title' => esc_html__( 'Pages', 'exalt' ),
			'priority' => 198
		)
	);

	$wp_customize->add_setting(
		'exalt_page_layout',
		array(
			'default'			=> 'right-sidebar',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		new Exalt_Radio_Image_Control( 
			$wp_customize,
			'exalt_page_layout',
			array(
				'section'		=> 'exalt_page_section',
				'label'			=> esc_html__( 'Page Layout', 'exalt' ),
				'choices'		=> array(
					'right-sidebar'	        => $images_uri . '2cr.png',
					'left-sidebar' 	        => $images_uri . '2cl.png',
					'no-sidebar' 		    => $images_uri . '1c.png',
					'center-content' 	    => $images_uri . '1cc.png'
				)
			)
		)
	);

	// Footer Panel
	$wp_customize->add_panel(
		'exalt_panel_footer',
		array(
			'priority' 			=> 200,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'Footer', 'exalt' )
		)
	);

	// Footer Widgets
	$wp_customize->add_section(
		'exalt_footer_widgets_section',
		array(
			'title' => esc_html__( 'Footer Widgets', 'exalt' ),
			'priority' => 10,
			'panel'	=> 'exalt_panel_footer'
		)
	);

	// Footer Number of sidebars
	$wp_customize->add_setting(
		'exalt_footer_sidebar_count',
		array(
			'default' => '3',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_footer_sidebar_count',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Widget Columns', 'exalt' ),
			'section' => 'exalt_footer_widgets_section',
			'choices' => array(
				'1' => esc_html__( '1', 'exalt' ),
				'2' => esc_html__( '2', 'exalt' ),
				'3' => esc_html__( '3', 'exalt' ),
				'4' => esc_html__( '4', 'exalt' )
			)
		)
	);

	$wp_customize->add_setting(
		'exalt_footer_widget_area_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'exalt_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'exalt_footer_widget_area_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Widget Area Width', 'exalt' ),
			'section' => 'exalt_footer_widgets_section',
			'choices' => array(
				'full' 		=> esc_html__( 'Full', 'exalt' ),
				'contained' => esc_html__( 'Contained', 'exalt' )
			)
		)
	);

	// Footer Widget Area Bg Color.
	$wp_customize->add_setting(
		'exalt_footer_widget_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_footer_widget_bg_color',
			array(
				'section'		    => 'exalt_footer_widgets_section',
				'label'			    => esc_html__( 'Widget Area Background Color', 'exalt' ),
			)
		)
	);

	// Footer Widget Text Color.
	$wp_customize->add_setting(
		'exalt_footer_widget_text_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_footer_widget_text_color',
			array(
				'section'		    => 'exalt_footer_widgets_section',
				'label'			    => esc_html__( 'Widget Text Color', 'exalt' ),
			)
		)
	);

	// Footer Widget Links Color.
	$wp_customize->add_setting(
		'exalt_footer_widget_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_footer_widget_link_color',
			array(
				'section'		    => 'exalt_footer_widgets_section',
				'label'			    => esc_html__( 'Widget Link Color', 'exalt' ),
			)
		)
	);

	// Footer Widget Links Hover Color.
	$wp_customize->add_setting(
		'exalt_footer_widget_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_footer_widget_link_hover_color',
			array(
				'section'		    => 'exalt_footer_widgets_section',
				'label'			    => esc_html__( 'Widget Link Color:Hover', 'exalt' ),
			)
		)
	);

	$wp_customize->add_section(
		'exalt_footer_bottom_section',
		array(
			'title' => esc_html__( 'Footer Bottom', 'exalt' ),
			'priority' => 15,
			'panel'	=> 'exalt_panel_footer'
		)
	);

	$wp_customize->add_setting(
		'exalt_footer_copyright_text',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'exalt_footer_copyright_text',
		array(
			'section'		=> 'exalt_footer_bottom_section',
			'type'			=> 'textarea',
			'label'			=> esc_html__( 'Copyright Text', 'exalt' )
		)
	);

	// Footer bottom Area Bg Color.
	$wp_customize->add_setting(
		'exalt_footer_bottom_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_footer_bottom_bg_color',
			array(
				'section'		    => 'exalt_footer_bottom_section',
				'label'			    => esc_html__( 'Footer Bottom Area Background Color', 'exalt' ),
			)
		)
	);

	// Footer bottom Text Color.
	$wp_customize->add_setting(
		'exalt_footer_bottom_text_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_footer_bottom_text_color',
			array(
				'section'		    => 'exalt_footer_bottom_section',
				'label'			    => esc_html__( 'Footer Bottom Text Color', 'exalt' ),
			)
		)
	);

	// Footer bottom Links Color.
	$wp_customize->add_setting(
		'exalt_footer_bottom_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_footer_bottom_link_color',
			array(
				'section'		    => 'exalt_footer_bottom_section',
				'label'			    => esc_html__( 'Footer Bottom Link Color', 'exalt' ),
			)
		)
	);

	// Footer bottom Links Hover Color.
	$wp_customize->add_setting(
		'exalt_footer_bottom_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'exalt_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'exalt_footer_bottom_link_hover_color',
			array(
				'section'		    => 'exalt_footer_bottom_section',
				'label'			    => esc_html__( 'Footer Bottom Link Color:Hover', 'exalt' ),
			)
		)
	);

}
add_action( 'customize_register', 'exalt_customize_register' );

/**
 * Gets the default image position based on the selected post layout.
 */
function exalt_get_archive_image_position_default() {
	$entries_layout = get_theme_mod( 'exalt_entries_layout', 'list' );
	if ( 'list' === $entries_layout ) {
		return 'beside-article';
	} elseif ( 'grid' === $entries_layout ) {
		return 'before-header';
	}
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function exalt_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function exalt_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function exalt_customize_preview_js() {
	wp_enqueue_script( 'exalt-customizer', get_template_directory_uri() . '/inc/customizer/assets/js/customizer.js', array( 'customize-preview' ), EXALT_VERSION, true );
}
add_action( 'customize_preview_init', 'exalt_customize_preview_js' );

/**
 * Enqueue the customizer stylesheet.
 */
function exalt_enqueue_customizer_stylesheets() {
    wp_register_style( 'exalt-customizer-css', get_template_directory_uri() . '/inc/customizer/assets/css/customizer.css', NULL, NULL, 'all' );
    wp_enqueue_style( 'exalt-customizer-css' );
}
add_action( 'customize_controls_print_styles', 'exalt_enqueue_customizer_stylesheets' );

/**
 * Enqueue Customize Control JS
 */
function exalt_enqueue_customize_control_scripts() {
	wp_enqueue_script( 'exalt-customizer-controls', get_template_directory_uri() . '/inc/customizer/assets/js/customizer-controls.js', array( 'jquery', 'customize-base' ), false, true );
}
add_action( 'customize_controls_enqueue_scripts', 'exalt_enqueue_customize_control_scripts' );

/**
 * Select sanitization callback.
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function exalt_sanitize_select( $input, $setting ) {
	
	// Ensure input is a slug.
	$input = sanitize_key( $input );
	
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Number sanitization.
 *
 * @param int                  $number  Number to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return int Sanitized number; otherwise, the setting default.
 */
function exalt_sanitize_number_absint( $number, $setting ) {
	// Ensure $number is an absolute integer (whole number, zero or greater).
	$number = absint( $number );
	
	// If the input is an absolute integer, return it; otherwise, return the default
	return ( $number ? $number : $setting->default );
}

/**
 * Check if the given value is a number or blank.
 */
function exalt_sanitize_number_blank( $number, $setting ) {

	if ( '' != $number ) {
		// Ensure $number is an absolute integer (whole number, zero or greater).
		$number = absint( $number );

		if ( $number >= 0 ) {
			return $number;
		} 
	}

	return $setting->default;

}

/**
 * Number Range sanitization.
 *
 * @param int                  $number  Number to check within the numeric range defined by the setting.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return int|string The number, if it is zero or greater and falls within the defined range; otherwise,
 *                    the setting default.
 */
function exalt_sanitize_number_range( $number, $setting ) {
	
	// Ensure input is an absolute integer.
	$number = absint( $number );
	
	// Get the input attributes associated with the setting.
	$atts = $setting->manager->get_control( $setting->id )->input_attrs;
	
	// Get minimum number in the range.
	$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );
	
	// Get maximum number in the range.
	$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );
	
	// Get step.
	$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );
	
	// If the number is within the valid range, return it; otherwise, return the default
	return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
}

/**
 * HEX Color sanitization.
 *
 * @param string               $hex_color HEX color to sanitize.
 * @param WP_Customize_Setting $setting   Setting instance.
 * @return string The sanitized hex color if not null; otherwise, the setting default.
 */
function exalt_sanitize_hex_color( $hex_color, $setting ) {
	// Sanitize $input as a hex value without the hash prefix.
	$hex_color = sanitize_hex_color( $hex_color );
	
	// If $input is a valid hex value, return it; otherwise, return the default.
	return ( ! is_null( $hex_color ) ? $hex_color : $setting->default );
}

/**
 * Checkbox sanitization callback example.
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function exalt_sanitize_checkbox( $checked ) {
	// Boolean check.
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitization callback of Multiple Checkboxes Control
 */
function exalt_sanitize_multiple_checkboxes( $values ) {

	$multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

	return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
	
}

/**
 * HTML sanitization callback.
 *
 * @param string $html HTML to sanitize.
 * @return string Sanitized HTML.
 */
function exalt_sanitize_html( $html ) {
	return wp_filter_post_kses( $html );
}

/**
 * URL sanitization.
 *
 * @param string $url URL to sanitize.
 * @return string Sanitized URL.
 */
function exalt_sanitize_url( $url ) {
	return esc_url_raw( $url );
}

/**
 * Email sanitization
 * @param string               $email   Email address to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string The sanitized email if not null; otherwise, the setting default.
 */
function exalt_sanitize_email( $email, $setting ) {
	// Strips out all characters that are not allowable in an email address.
	$email = sanitize_email( $email );
	
	// If $email is a valid email, return it; otherwise, return the default.
	return ( ! is_null( $email ) ? $email : $setting->default );
}

function exalt_sanitize_slider_number_input( $number, $setting ) {
	
	// Ensure input is a number.
	$number = (float)$number ;
	
	// Get the input attributes associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// Get minimum number in the range.
	$min = ( isset( $choices['min'] ) ? $choices['min'] : $number );
	
	// Get maximum number in the range.
	$max = ( isset( $choices['max'] ) ? $choices['max'] : $number );
	
	// Get step.
	$step = ( isset( $choices['step'] ) ? $choices['step'] : 1 );

	if ( $number <= $min ) {
		$number = $min;
	} elseif ( $number >= $max ) {
		$number = $max;
	}
	
	// If the number is within the valid range, return it; otherwise, return the default
	return ( is_numeric( $number / $step ) ? $number : $setting->default );
}

/**
 * Category dropdown sanitization.
 *
 * @param int $catid to sanitize.
 * @return int $cat_id.
 */
function exalt_sanitize_category_dropdown( $catid ) {
	// Ensure $catid is an absolute integer.
	return $cat_id = absint( $catid );
}

/**
 * Check if the grid style is active.
 */
function exalt_is_slideout_active( $control ) {
	if ( $control->manager->get_setting( 'exalt_show_slideout_sb' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the default header layout is active.
 */
function exalt_is_default_header( $control ) {
	if ( $control->manager->get_setting( 'exalt_header_layout' )->value() === 'default' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the line header layout is active.
 */
function exalt_is_line_header( $control ) {
	if ( $control->manager->get_setting( 'exalt_header_layout' )->value() === 'single-line' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the wide layout is active.
 */
function exalt_is_wide_layout_active( $control ) {
	if ( $control->manager->get_setting( 'exalt_site_layout' )->value() === 'wide' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the boxed layout is active.
 */
function exalt_is_boxed_layout_active( $control ) {
	if ( $control->manager->get_setting( 'exalt_site_layout' )->value() === 'boxed' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the grid layout is active.
 */
function exalt_is_entries_grid( $control ) {
	if ( $control->manager->get_setting( 'exalt_entries_layout' )->value() === 'grid' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the list layout is active.
 */
function exalt_is_entries_list( $control ) {
	if ( $control->manager->get_setting( 'exalt_entries_layout' )->value() === 'list' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the list layout is active.
 */
function exalt_is_excerpt_type( $control ) {
	if ( $control->manager->get_setting( 'exalt_content_type' )->value() === 'excerpt' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Checks featured image alignment should be active or not
 */
function exalt_thumbnail_align_active( $control ) {
	$thumbnail_position = $control->manager->get_setting( 'exalt_archive_thumbnail_position' )->value();
	if ( 'beside-article' === $thumbnail_position || 'beside-content' === $thumbnail_position ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Checks if exalt is showing author.
 */
function exalt_is_showing_author( $control ) {
	if ( $control->manager->get_setting( 'exalt_show_author' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Checks if exalt is showing author in single post.
 */
function exalt_is_showing_author_s( $control ) {
	if ( $control->manager->get_setting( 'exalt_show_author_s' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Checks if exalt is showing date
 */
function exalt_is_showing_date( $control ) {
	if ( $control->manager->get_setting( 'exalt_show_date' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Checks if exalt is showing date in single posts
 */
function exalt_is_showing_date_s( $control ) {
	if ( $control->manager->get_setting( 'exalt_show_date_s' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Checks if exalt is showing time ago
 */
function exalt_is_time_ago( $control ) {
	if ( ( $control->manager->get_setting( 'exalt_time_ago' )->value() === true ) || ( $control->manager->get_setting( 'exalt_time_ago_s' )->value() === true ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the custom logo has been set.
 */
function exalt_has_custom_logo() {
	if ( has_custom_logo() ) {
		return true;
	} else {
		return false;
	}
}


function exalt_is_showing_breadcrumb( $control ) {
	if ( $control->manager->get_setting( 'exalt_breadcrumb_source' )->value() !== 'none' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if blog is displaying on front page
 */
function exalt_is_showing_blog_on_front( $control ) {
	if ( 'posts' == get_option( 'show_on_front' ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the featured posts source is "Category"
 */
function exalt_is_fps_source_category( $control ) {
	if ( $control->manager->get_setting( 'exalt_featured_posts_source' )->value() === 'category' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the featured posts source is "Tag"
 */
function exalt_is_fps_source_tag( $control ) {
	if ( $control->manager->get_setting( 'exalt_featured_posts_source' )->value() === 'tag' ) {
		return true;
	} else {
		return false;
	}
}
