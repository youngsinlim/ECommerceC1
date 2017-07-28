<?php
/**
 * Customizer functionality for the theme.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

/**
 * Load customizer paths.
 *
 * @since 1.1.38
 */
function hestia_load_customize_controls() {
	$control_paths = array();
	$control_paths = apply_filters( 'hestia_controls_path', $control_paths );

	if ( empty( $control_paths ) ) {
		return;
	}

	foreach ( $control_paths as $control_path ) {
		if ( file_exists( $control_path ) ) {
			require_once( $control_path );
		}
	}

}
add_action( 'customize_register', 'hestia_load_customize_controls', 0 );


/**
 * Add JS to enable live previews.
 *
 * @since Hestia 1.0
 */
function hestia_customizer_live_preview() {
	wp_enqueue_script(
		'hestia-customizer-preview', get_template_directory_uri() . '/assets/js/customizer.js', array(
			'jquery',
			'customize-preview',
		), HESTIA_VERSION, true
	);
}

add_action( 'customize_preview_init', 'hestia_customizer_live_preview' );

/**
 * Register and enqueue customizer script
 *
 * @since Hestia 1.0
 */
function hestia_customizer_controls() {
	wp_enqueue_style( 'hestia-customizer-style', get_template_directory_uri() . '/assets/css/customizer-style.css', array(), HESTIA_VERSION );
	wp_enqueue_script( 'hestia_customize_controls', get_template_directory_uri() . '/assets/js/customizer-controls.js', array( 'jquery', 'customize-controls' ), HESTIA_VERSION, true );

}
add_action( 'customize_controls_enqueue_scripts', 'hestia_customizer_controls' );

if ( ! function_exists( 'hestia_sanitize_checkbox' ) ) :
	/**
	 * Sanitize checkbox output.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_sanitize_checkbox( $input ) {
		return ( isset( $input ) && true === (bool) $input ? true : false );
	}
endif;

if ( ! function_exists( 'hestia_sanitize_multiselect' ) ) :
	/**
	 * Sanitize multi select output.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_sanitize_multiselect( $input ) {
		if ( ! is_array( $input ) ) {
			$output = explode( ',', $input );
		} else {
			$output = $input;
		}

		if ( ! empty( $output ) ) {
			return array_map( 'sanitize_text_field', $output );
		} else {
			return array();
		}
	}
endif;

// Load Customizer repeater control.
$repeater_path = get_template_directory() . '/inc/customizer-repeater/functions.php';
if ( file_exists( $repeater_path ) ) {
	require_once( $repeater_path );
}
// Load Customizer repeater control.
$plugin_installer = get_template_directory() . '/inc/plugin-install/class-hestia-plugin-install-helper.php';
if ( file_exists( $plugin_installer ) ) {
	require_once( $plugin_installer );
}

/**
 * Register panels for Customizer.
 *
 * @since Hestia 1.0
 */
function hestia_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'hestia_appearance_settings', array(
			'priority' => 25,
			'title' => esc_html__( 'Appearance Settings', 'hestia' ),
		)
	);

	$wp_customize->add_panel(
		'hestia_frontpage_sections', array(
			'priority' => 30,
			'title' => esc_html__( 'Frontpage Sections', 'hestia' ),
			'description' => esc_html__( 'Drag and drop panels to change the order of sections.','hestia' ),
		)
	);

	$wp_customize->add_panel(
		'hestia_blog_settings', array(
			'priority' => 35,
			'title' => esc_html__( 'Blog Settings', 'hestia' ),
		)
	);

	$wp_customize->get_section( 'header_image' )->panel = 'hestia_appearance_settings';
	$wp_customize->get_section( 'background_image' )->panel = 'hestia_appearance_settings';
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'custom_logo' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'custom_logo', array(
				'selector' => '.navbar-brand',
				'settings' => 'custom_logo',
				'render_callback' => 'hestia_custom_logo_callback',
			)
		);
	}

	/* Controls used for selective refresh on Sidebar placeholder */

	$wp_customize->add_setting(
		'hestia_placeholder_sidebar_1', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'hestia_placeholder_sidebar_1', array(
			'type' => 'hidden',
			'section'  => 'header_image',
			'priority' => 10,
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'hestia_placeholder_sidebar_1', array(
				'selector'        => '.hestia-widget-placeholder.sidebar-1',
				'settings'        => 'hestia_placeholder_sidebar_1',
				'render_callback' => '',
			)
		);
	}

	$wp_customize->add_setting(
		'hestia_placeholder_sidebar_woocommerce', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'hestia_placeholder_sidebar_woocommerce', array(
			'type' => 'hidden',
			'section'  => 'header_image',
			'priority' => 10,
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'hestia_placeholder_sidebar_woocommerce', array(
				'selector'        => '.hestia-widget-placeholder.sidebar-woocommerce',
				'settings'        => 'hestia_placeholder_sidebar_woocommerce',
				'render_callback' => '',
			)
		);
	}

}

add_action( 'customize_register', 'hestia_customize_register' );

/**
 * Custom logo callback function.
 *
 * @return string
 */
function hestia_custom_logo_callback() {
	if ( get_theme_mod( 'custom_logo' ) ) {
		$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) , 'full' );
		$logo = '<img src="' . esc_url( $logo[0] ) . '">';
	} else {
		if ( is_front_page() ) {
			$logo = '<h1>' . get_bloginfo( 'name' ) . '</h1>';
		} else {
			$logo = '<p>' . get_bloginfo( 'name' ) . '</p>';
		}
	}
	return $logo;
}

/**
 * Function to sanitize alpha color.
 *
 * @param string $input Hex or RGBA color.
 *
 * @return string|void
 */
function hestia_sanitize_colors( $input ) {
	// Is this an rgba color or a hex?
	$mode = ( false === strpos( $input, 'rgba' ) ) ? 'hex' : 'rgba';

	if ( 'rgba' === $mode ) {
		return hestia_sanitize_rgba( $input );
	} else {
		return sanitize_hex_color( $input );
	}
}

/**
 * Sanitize rgba color.
 *
 * @param string $value Color in rgba format.
 *
 * @return string
 */
function hestia_sanitize_rgba( $value ) {
	$red = 'rgba(0,0,0,0)';
	$green = 'rgba(0,0,0,0)';
	$blue = 'rgba(0,0,0,0)';
	$alpha = 'rgba(0,0,0,0)';   // If empty or an array return transparent
	if ( empty( $value ) || is_array( $value ) ) {
		return '';
	}

	// By now we know the string is formatted as an rgba color so we need to further sanitize it.
	$value = str_replace( ' ', '', $value );
	sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
	return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}

/**
 * Callback for WooCommerce customizer controls.
 *
 * @return bool
 */
function hestia_woocommerce_check() {
	if ( class_exists( 'woocommerce' ) ) {
		return true;
	}
}
