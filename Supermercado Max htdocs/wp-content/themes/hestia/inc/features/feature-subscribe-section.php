<?php
/**
 * Customizer functionality for the Subscribe section.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

// Load Blog subscribe
$subscribe_path = trailingslashit( get_template_directory() ) . 'inc/customizer-subscribe-info/class-hestia-subscribe-info.php';
if ( file_exists( $subscribe_path ) ) {
	require_once( $subscribe_path );
}

/**
 * Hook controls for Subscribe section to Customizer.
 *
 * @since Hestia 1.0
 * @modified 1.1.30
 */
function hestia_subscribe_customize_register( $wp_customize ) {

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? true : false;

	$wp_customize->add_section(
		'hestia_subscribe', array(
			'title'    => esc_html__( 'Subscribe', 'hestia' ),
			'panel'    => 'hestia_frontpage_sections',
			'priority' => apply_filters( 'hestia_section_priority', 45, 'hestia_subscribe' ),
		)
	);

	$wp_customize->add_setting(
		'hestia_subscribe_hide', array(
			'sanitize_callback' => 'hestia_sanitize_checkbox',
			'default'           => true,
		)
	);

	$wp_customize->add_control(
		'hestia_subscribe_hide', array(
			'type'     => 'checkbox',
			'label'    => esc_html__( 'Disable section', 'hestia' ),
			'section'  => 'hestia_subscribe',
			'priority' => 1,
		)
	);

	$wp_customize->add_setting(
		'hestia_subscribe_background', array(
			'default'           => get_template_directory_uri() . '/assets/img/about.jpg',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => $selective_refresh ? 'postMessage' : 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, 'hestia_subscribe_background', array(
				'label'    => esc_html__( 'Background Image', 'hestia' ),
				'section'  => 'hestia_subscribe',
				'priority' => 5,
			)
		)
	);

	$wp_customize->add_setting(
		'hestia_subscribe_title', array(
			'default'           => esc_html__( 'Subscribe to our Newsletter', 'hestia' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh ? 'postMessage' : 'refresh',
		)
	);

	$wp_customize->add_control(
		'hestia_subscribe_title', array(
			'label'    => esc_html__( 'Section Title', 'hestia' ),
			'section'  => 'hestia_subscribe',
			'priority' => 10,
		)
	);

	$wp_customize->add_setting(
		'hestia_subscribe_subtitle', array(
			'default'           => esc_html__( 'Change this subtitle in the Customizer', 'hestia' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh ? 'postMessage' : 'refresh',
		)
	);

	$wp_customize->add_control(
		'hestia_subscribe_subtitle', array(
			'label'    => esc_html__( 'Section Subtitle', 'hestia' ),
			'section'  => 'hestia_subscribe',
			'priority' => 15,
		)
	);

	$wp_customize->add_setting(
		'hestia_subscribe_info', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new Hestia_Subscribe_Info(
			$wp_customize, 'hestia_subscribe_info', array(
				'label'      => esc_html__( 'Instructions', 'hestia' ),
				'section'    => 'hestia_subscribe',
				'capability' => 'install_plugins',
				'priority'   => 20,
			)
		)
	);
}
add_action( 'customize_register', 'hestia_subscribe_customize_register' );


/**
 * Add selective refresh for subscribe section controls.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @since 1.1.31
 * @access public
 */
function hestia_register_subscribe_partials( $wp_customize ) {
	// Abort if selective refresh is not available.
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		return;
	}

	$wp_customize->selective_refresh->add_partial(
		'hestia_subscribe_title', array(
			'selector'        => '.subscribe-line .title',
			'settings'        => 'hestia_subscribe_title',
			'render_callback' => 'hestia_subscribe_title_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'hestia_subscribe_subtitle', array(
			'selector'        => '.subscribe-line .subscribe-description',
			'settings'        => 'hestia_subscribe_subtitle',
			'render_callback' => 'hestia_subscribe_subtitle_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'hestia_subscribe_background', array(
			'selector' => '.hestia-subscribe-image',
			'settings' => 'hestia_subscribe_background',
			'render_callback' => 'hestia_subscribe_image_callback',
		)
	);
}
add_action( 'customize_register', 'hestia_register_subscribe_partials' );

/**
 * Render callback function for subscribe section title selective refresh
 *
 * @since   1.1.25
 * @access  public
 * @return string
 */
function hestia_subscribe_title_callback() {
	return get_theme_mod( 'hestia_subscribe_title' );
}

/**
 * Render callback function for subscribe section subtitle selective refresh
 *
 * @since   1.1.25
 * @access  public
 * @return string
 */
function hestia_subscribe_subtitle_callback() {
	return get_theme_mod( 'hestia_subscribe_subtitle' );
}

/**
 * Render callback for subscribe image selective refresh.
 *
 * @since   1.1.25
 * @access  public
 */
function hestia_subscribe_image_callback() {
	$hestia_subscribe_background = get_theme_mod( 'hestia_subscribe_background' );
	if ( ! empty( $hestia_subscribe_background ) ) { ?>
		<style class="hestia-subscribe-image-css">
			#subscribe {
				background-image: url(<?php echo esc_url( $hestia_subscribe_background ); ?>) !important;
			}
		</style>
		<?php
	}
}
