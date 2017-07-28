<?php
/**
 * Typography settings.
 *
 * @package Hestia
 * @since 1.1.38
 */

/**
 * Include font selector functions.
 */
$font_selector_functions = HESTIA_PHP_INCLUDE . 'customizer-font-selector/functions.php';
if ( file_exists( $font_selector_functions ) ) {
	require_once( $font_selector_functions );
}

/**
 * Filter the array of customizer controls paths.
 *
 * @param array $array Customizer paths.
 *
 * @since 1.1.38
 * @return array
 */
function hestia_typography_controls( $array ) {
	$range_control_path = HESTIA_PHP_INCLUDE . 'customizer-range-value/class/class-hestia-customizer-range-value-control.php';
	$font_selector = HESTIA_PHP_INCLUDE . 'customizer-font-selector/class/class-hestia-font-selector.php';
	$multiple_checkbox = HESTIA_PHP_INCLUDE . 'customizer-multiple-checkbox/class/class-hestia-multiple-checkbox.php';
	return array_merge( $array, array( $range_control_path, $font_selector, $multiple_checkbox ) );
}
add_filter( 'hestia_controls_path', 'hestia_typography_controls' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 1.1.38
 */
function hestia_customize_preview() {
	wp_enqueue_script( 'hestia_customizer', get_template_directory_uri() . '/inc/typography/js/customizer.js', array( 'customize-preview', 'hestia_sections_match_height' ), HESTIA_VERSION, true );
}
add_action( 'customize_preview_init', 'hestia_customize_preview' );

/**
 * Customizer controls for typography settings.
 *
 * @param WP_Customize_Manager $wp_customize Customize manager.
 *
 * @since 1.1.38
 */
function hestia_typography_settings( $wp_customize ) {

	// Add typography panel.
	$wp_customize->add_section(
		'hestia_typography', array(
			'title' => esc_html__( 'Typography', 'hestia' ),
			'panel' => 'hestia_appearance_settings',
			'priority' => 25,
		)
	);

	if ( class_exists( 'Hestia_Multiple_Checkbox' ) ) {

		$wp_customize->add_setting(
			'hestia_font_subsets', array(
				'default' => array( 'latin' ),
				'sanitize_callback' => 'hestia_sanitize_multiple_checkbox',
			)
		);

		$wp_customize->add_control(
			new Hestia_Multiple_Checkbox(
				$wp_customize, 'hestia_font_subsets', array(
					'section' => 'hestia_typography',
					'label' => esc_html__( 'Font Subsets', 'hestia' ),
					'choices' => array(
						'latin' => 'latin',
						'latin-ext' => 'latin-ext',
						'cyrillic' => 'cyrillic',
						'cyrillic-ext' => 'cyrillic-ext',
						'greek' => 'greek',
						'greek-ext' => 'greek-ext',
						'vietnamese' => 'vietnamese',
					),
					'priority' => 10,
				)
			)
		);

	}

	if ( class_exists( 'Hestia_Font_Selector' ) ) {

		$wp_customize->add_setting(
			'hestia_headings_font', array(
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Hestia_Font_Selector(
				$wp_customize, 'hestia_headings_font', array(
					'label'             => esc_html__( 'Headings','hestia' ) . ' ' . esc_html__( 'font family', 'hestia' ),
					'section'           => 'hestia_typography',
					'priority'          => 15,
					'type'              => 'select',
				)
			)
		);

		$wp_customize->add_setting(
			'hestia_body_font', array(
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Hestia_Font_Selector(
				$wp_customize, 'hestia_body_font', array(
					'label'             => esc_html__( 'Body','hestia' ) . ' ' . esc_html__( 'font family', 'hestia' ),
					'section'           => 'hestia_typography',
					'priority'          => 20,
					'type'              => 'select',
				)
			)
		);
	}

	if ( class_exists( 'Hestia_Customizer_Range_Value_Control' ) ) {

		$wp_customize->register_control_type( 'Hestia_Customizer_Range_Value_Control' );

		$wp_customize->add_setting(
			'hestia_body_font_size', array(
				'sanitize_callback' => 'hestia_sanitize_range_value',
				'default' => 14,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new Hestia_Customizer_Range_Value_Control(
				$wp_customize, 'hestia_body_font_size', array(
					'label' => esc_html__( 'Body','hestia' ) . ' ' . esc_html__( 'font size', 'hestia' ) . ' ( ' . esc_html__( 'px','hestia' ) . ' )',
					'section' => 'hestia_typography',
					'type' => 'range-value',
					'input_attr' => array(
						'min' => 10,
						'max' => 20,
						'step' => 0.1,
					),
					'priority' => 25,
				)
			)
		);

		$wp_customize->add_setting(
			'hestia_headings_font_size', array(
				'sanitize_callback' => 'hestia_sanitize_range_value',
				'default' => 36.4,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new Hestia_Customizer_Range_Value_Control(
				$wp_customize, 'hestia_headings_font_size', array(
					'label' => esc_html__( 'Headings','hestia' ) . ' ' . esc_html__( 'font size', 'hestia' ) . ' ( ' . esc_html__( 'px','hestia' ) . ' )',
					'section' => 'hestia_typography',
					'type' => 'range-value',
					'input_attr' => array(
						'min' => 30,
						'max' => 50,
						'step' => 0.1,
					),
					'priority' => 30,
				)
			)
		);

	}// End if().
}
add_action( 'customize_register', 'hestia_typography_settings', 20 );


/**
 * Adds inline style from customizer
 *
 * @since 1.1.38
 */
function hestia_tipography_inline_style() {
	$custom_css = '';

	/**
	 * Body font size inline style.
	 */
	$custom_css .= hestia_get_inline_style( 'hestia_body_font_size', 'hestia_get_body_font_style', $custom_css );

	/**
	 * Headings inline style.
	 */
	$custom_css .= hestia_get_inline_style( 'hestia_headings_font_size', 'hestia_get_headings_font_style', $custom_css );

	/**
	 * Headings font family.
	 */
	$hestia_headings_font = get_theme_mod( 'hestia_headings_font' );
	if ( ! empty( $hestia_headings_font ) ) {
		hestia_enqueue_google_font( $hestia_headings_font );
		$custom_css .= 'h1, h2, h3, h4, h5, h6, .hestia-title , .info-title, .card-title, 
		.page-header.header-small .hestia-title, .page-header.header-small .title, .widget h5, .hestia-title, 
		.title, .card-title, .info-title, .footer-brand, .footer-big h4, .footer-big h5, .media .media-heading, 
		.carousel h1.hestia-title, .carousel h2.title, 
		.carousel span.sub-title, .woocommerce.single-product .product_title, .woocommerce .related.products h2{
            font-family: ' . $hestia_headings_font . ';
        }';
	}

	/**
	 * Body font family.
	 */
	$hestia_body_font = get_theme_mod( 'hestia_body_font' );
	if ( ! empty( $hestia_body_font ) ) {
		hestia_enqueue_google_font( $hestia_body_font );
		$custom_css .= 'body, ul, .tooltip-inner, .products .shop-item .added_to_cart, 
		.woocommerce-checkout #payment input[type=submit], .woocommerce-checkout input[type=submit], 
		.woocommerce-cart table.shop_table td.actions input[type=submit], 
		.woocommerce .cart-collaterals .cart_totals .checkout-button, .woocommerce button.button, 
		.woocommerce div[id^=woocommerce_widget_cart].widget .buttons .button, .woocommerce div.product form.cart .button, 
		.woocommerce #review_form #respond .form-submit , .added_to_cart.wc-forward, .woocommerce #respond input#submit,
		.woocommerce a.button{
            font-family: ' . $hestia_body_font . ';
        }';
	}

	wp_add_inline_style( 'hestia_style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'hestia_tipography_inline_style' );


/**
 * Get inline style for different controls
 *
 * @param string $control_name Control name.
 * @param string $function_name Function to be called.
 * @param string $custom_css Previous inline style.
 *
 * @since 1.1.38
 * @return string
 */
function hestia_get_inline_style( $control_name, $function_name, $custom_css ) {
	$control_value = get_theme_mod( $control_name );
	if ( hestia_is_json( $control_value ) ) {
		$control_value = json_decode( $control_value, true );
		foreach ( $control_value as $key => $value ) {
			$custom_css .= call_user_func( $function_name, $value, $key );
		}
	} else {
		$custom_css .= call_user_func( $function_name, $control_value );
	}
	return $custom_css;
}


/**
 * Function that returns inline style for body font style.
 *
 * @param int    $font_size Font size.
 * @param string $dimension Query dimension.
 *
 * @since 1.1.38
 * @return string
 */
function hestia_get_body_font_style( $font_size, $dimension = 'desktop' ) {
	$custom_css = '';
	if ( empty( $font_size ) ) {
		return '';
	}

	$woo_table = $font_size - 5;
	$custom_css .= '.woocommerce-cart table.shop_table th{
        font-size:' . floatval( $woo_table ) . 'px }';

	$woo_add_to_cart_buttons = $font_size - 4.2;
	$custom_css .= '.added_to_cart.wc-forward, .products .shop-item .added_to_cart{
        font-size:' . floatval( $woo_add_to_cart_buttons ) . 'px }';

	$woo_stars_font_size = $font_size - 2;
	$custom_css .= '.woocommerce.single-product .product .woocommerce-product-rating .star-rating,
    .woocommerce .star-rating,
    .woocommerce .woocommerce-breadcrumb,
    button, input[type="submit"], input[type="button"], .btn,
    .woocommerce .single-product div.product form.cart .button, .woocommerce #respond input#submit, 
    .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, 
    .woocommerce a.button.alt, .woocommerce button.button.alt,
    #secondary div[id^=woocommerce_price_filter] .button, .footer div[id^=woocommerce_price_filter] .button, .tooltip-inner{
        font-size:' . floatval( $woo_stars_font_size ) . 'px;
    }';

	$woo_table_font_size = $font_size - 1;
	$custom_css .= '.woocommerce-cart table.shop_table th {
        font-size:' . floatval( $woo_table_font_size ) . 'px;
    }';

	$custom_css .= 'p, ul, li, select, table, .form-group.label-floating label.control-label, 
    .form-group.label-placeholder label.control-label, .copyright,
    .woocommerce .product .card-product .card-description p,
    #secondary div[id^=woocommerce_layered_nav] ul li a, #secondary div[id^=woocommerce_product_categories] ul li a, 
    .footer div[id^=woocommerce_layered_nav] ul li a, .footer div[id^=woocommerce_product_categories] ul li a,
    #secondary div[id^=woocommerce_price_filter] .price_label, .footer div[id^=woocommerce_price_filter] .price_label,
    .woocommerce ul.product_list_widget li, .footer ul.product_list_widget li, ul.product_list_widget li,
    .woocommerce .woocommerce-result-count,
    .woocommerce div.product .woocommerce-tabs ul.tabs.wc-tabs li a,
    .variations tr .label,
    .woocommerce.single-product .section-text,
    .woocommerce div.product form.cart .reset_variations,
    .woocommerce.single-product .product .woocommerce-review-link,
    .woocommerce-cart .shop_table .actions .coupon .input-text,
    .woocommerce-cart table.shop_table td.actions input[type=submit],
    .woocommerce .cart-collaterals .cart_totals .checkout-button,
    .form-control,
    .woocommerce-checkout #payment ul.payment_methods li, .woocommerce-checkout #payment ul.payment_methods div, 
    .woocommerce-checkout #payment ul.payment_methods div p, .woocommerce-checkout #payment input[type=submit], .woocommerce-checkout input[type=submit]
    {
        font-size:' . floatval( $font_size ) . 'px;
    }';

	// For WooCommerce sidebar reviewer class
	$woo_widget_font_size = $font_size + 1;
	$custom_css .= '#secondary div[id^=woocommerce_recent_reviews] .reviewer, .footer div[id^=woocommerce_recent_reviews] .reviewer{
        font-size:' . floatval( $woo_widget_font_size ) . 'px;
    }';

	// For services card content.
	$larger_font_size = $font_size + 2;
	$custom_css .= '.hestia-features .hestia-info p, .hestia-features .info p, .features .hestia-info p, .features .info p,
    .woocommerce-cart table.shop_table .product-name a,
    .woocommerce-checkout .form-row label, .media p{
        font-size:' . floatval( $larger_font_size ) . 'px;
    }';

	// For .blog-post .section-text class
	$section_text_font_size = $font_size + 2.8;
	$custom_css .= '.blog-post .section-text{ font-size:' . floatval( $section_text_font_size ) . 'px; }';

	// For about content.
	$about_font_size = $font_size + 3.5;
	$custom_css .= '.hestia-about p{
        font-size:' . floatval( $about_font_size ) . 'px;
    }';

	// For slider sub-title
	$subtitle_font_size = $font_size + 4.2;
	$custom_css .= '.carousel span.sub-title, .media .media-heading, .card .footer .stats .fa{
        font-size:' . floatval( $subtitle_font_size ) . 'px;
    }';

	// For table > thead > tr > th
	$table_head_font_size = $font_size + 7;
	$custom_css .= 'table > thead > tr > th{ font-size:' . floatval( $table_head_font_size ) . 'px; }';

	if ( function_exists( 'hestia_add_media_query' ) ) {
		$custom_css = hestia_add_media_query( $dimension, $custom_css );
	}
	return $custom_css;
}

/**
 * Function that returns inline style for headings font style.
 *
 * @param int    $font_size Font size.
 * @param string $dimension Query dimension.
 *
 * @since 1.1.38
 * @return string
 */
function hestia_get_headings_font_style( $font_size, $dimension = 'desktop' ) {
	$custom_css = '';
	if ( empty( $font_size ) ) {
		return '';
	}

	$widget_title = $font_size - 14.56;
	$custom_css .= '.widget h5{ font-size: ' . floatval( $widget_title ) . 'px }';

	$big_title_size = $font_size + 30.8;
	$custom_css .= '.carousel h1.hestia-title, .carousel h2.title{ font-size: ' . floatval( $big_title_size ) . 'px }';

	$h1_size = $font_size + 16.8;
	$custom_css .= 'h1,.page-header.header-small .hestia-title, .page-header.header-small .title,
	.blog-post.blog-post-wrapper .section-text h1{ font-size: ' . floatval( $h1_size ) . 'px }';

	$h2_size = $font_size;
	$custom_css .= 'h2, .blog-post.blog-post-wrapper .section-text h2{ font-size: ' . floatval( $h2_size ) . 'px }';

	$h3_size = $font_size - 10.85;
	$custom_css .= 'h3, .blog-post.blog-post-wrapper .section-text h3{ font-size: ' . floatval( $h3_size ) . 'px }';

	$h4_size = $font_size - 18.2;
	$custom_css .= 'h4, .card-blog .card-title, .blog-post.blog-post-wrapper .section-text h4{ font-size: ' . floatval( $h4_size ) . 'px }';

	$h5_size = $font_size - 18.9;
	$custom_css .= 'h5, .blog-post.blog-post-wrapper .section-text h5{ font-size: ' . floatval( $h5_size ) . 'px }';

	$h6_size = $font_size - 23.8;
	$custom_css .= 'h6, .blog-post.blog-post-wrapper .section-text h6, .card-product .category{ font-size: ' . floatval( $h6_size ) . 'px }';

	$title_on_page = $font_size + 8.4;
	$custom_css .= '.archive .page-header.header-small .hestia-title, .blog .page-header.header-small .hestia-title, 
	.search .page-header.header-small .hestia-title, .archive .page-header.header-small .title, 
	.blog .page-header.header-small .title, .search .page-header.header-small .title{ font-size: ' . floatval( $title_on_page ) . 'px }';

	$product_title = $font_size + 12.4;
	$custom_css .= '.woocommerce.single-product .product_title, .woocommerce .related.products h2{
	    font-size: ' . floatval( $product_title ) . 'px
	}';

	$review_title = $font_size - 10.85;
	$custom_css .= '.woocommerce .comment-reply-title{
	    font-size: ' . floatval( $review_title ) . 'px
	}';
	if ( function_exists( 'hestia_add_media_query' ) ) {
		$custom_css = hestia_add_media_query( $dimension, $custom_css );
	}

	return $custom_css;
}

/**
 * Check if a string is in json format
 *
 * @param  string $string Input.
 *
 * @since 1.1.38
 * @return bool
 */
function hestia_is_json( $string ) {
	return is_string( $string ) && is_array( json_decode( $string, true ) ) ? true : false;
}
