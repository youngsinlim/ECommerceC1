<?php
/**
 * Hestia functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package Hestia
 * @since Hestia 1.0
 */

define( 'HESTIA_VERSION', '1.1.39' );

define( 'HESTIA_VENDOR_VERSION', '1.0.1' );

define( 'HESTIA_PHP_INCLUDE', trailingslashit( get_template_directory() ) . 'inc/' );

require_once( HESTIA_PHP_INCLUDE . 'template-tags.php' );
require_once( HESTIA_PHP_INCLUDE . 'wp-bootstrap-navwalker/wp_bootstrap_navwalker.php' );
require_once( HESTIA_PHP_INCLUDE . 'customizer.php' );
require_once( HESTIA_PHP_INCLUDE . 'page-builder-extras.php' );
require_once( get_template_directory() . '/ti-prevdem/init-prevdem.php' );
if ( class_exists( 'woocommerce' ) ) {
	require_once( HESTIA_PHP_INCLUDE . 'woocommerce/functions.php' );
	require_once( HESTIA_PHP_INCLUDE . 'woocommerce/hooks.php' );
}

if ( ! function_exists( 'hestia_setup_theme' ) ) {
	/**
	 * Get the number of items in the cart.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_setup_theme() {
		// Using this feature you can set the maximum allowed width for any content in the theme, like oEmbeds and images added to posts.  https://codex.wordpress.org/Content_Width
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 750;
		}

		// Takes care of the <title> tag. https://codex.wordpress.org/Title_Tag
		add_theme_support( 'title-tag' );

		// Add theme support for custom logo. https://codex.wordpress.org/Theme_Logo
		add_theme_support(
			'custom-logo', array(
				'flex-width'  => true,
				'height'      => 100,
			)
		);

		// Loads texdomain. https://codex.wordpress.org/Function_Reference/load_theme_textdomain
		load_theme_textdomain( 'hestia', get_template_directory() . '/languages' );

		// Add automatic feed links support. https://codex.wordpress.org/Automatic_Feed_Links
		add_theme_support( 'automatic-feed-links' );

		// Add post thumbnails support. https://codex.wordpress.org/Post_Thumbnails
		add_theme_support( 'post-thumbnails' );

		// Add custom background support. https://codex.wordpress.org/Custom_Backgrounds
		add_theme_support(
			'custom-background', array(
				'default-color' => 'E5E5E5',
			)
		);

		// Add custom header support. https://codex.wordpress.org/Custom_Headers
		add_theme_support(
			'custom-header', array(
				// Height
				'height'        => 2000,
				// Flex height
				'flex-height'   => true,
				// Header image
				'default-image' => get_template_directory_uri() . '/assets/img/header.jpg',
				// Header text
				'header-text'   => false,
			)
		);

		$header_image = array(
			'library' => array(
				'url'           => get_template_directory_uri() . '/assets/img/header.jpg',
				'thumbnail_url' => get_template_directory_uri() . '/assets/img/header.jpg',
				'description'   => 'Library Ceiling',
			),
		);

		register_default_headers( $header_image );

		// Add selective Widget refresh support
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for html5
		add_theme_support( 'html5', array( 'search-form' ) );

		// This theme uses wp_nav_menu(). https://codex.wordpress.org/Function_Reference/register_nav_menu
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'hestia' ),
				'footer'  => esc_html__( 'Footer Menu', 'hestia' ),
			)
		);

		// Adding image sizes. https://developer.wordpress.org/reference/functions/add_image_size/
		add_image_size( 'hestia-blog', 360, 240, true );

		if ( class_exists( 'woocommerce' ) ) {
			add_image_size( 'hestia-shop', 230, 350, true );
		}

		// Add Portfolio Image size if Jetpack Portfolio CPT is enabled.
		if ( class_exists( 'Jetpack' ) ) {
			if ( Jetpack::is_module_active( 'custom-content-types' ) ) {
				add_image_size( 'hestia-portfolio', 360, 300, true );
			}
		}

		// Added WooCommerce support.
		if ( class_exists( 'woocommerce' ) ) {
			add_theme_support( 'woocommerce' );
		}

		// Added Jetpack Portfolio Support.
		if ( class_exists( 'Jetpack' ) ) {
			add_theme_support( 'jetpack-portfolio' );
		}

		/* Customizer upsell. */
		$info_path = HESTIA_PHP_INCLUDE . 'customizer-info/class/class-hestia-customizer-info-singleton.php';
		if ( file_exists( $info_path ) ) {
			require_once( $info_path );
		}

		/* WooCommerce support for latest gallery */
		if ( class_exists( 'WooCommerce' ) ) {
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}

		add_editor_style();
	}

	add_action( 'after_setup_theme', 'hestia_setup_theme' );
}// End if().


/**
 * Register widgets for the theme.
 *
 * @since Hestia 1.0
 */
function hestia_widgets_init() {

	register_sidebar(
		array(
			'name'        => esc_html__( 'Sidebar', 'hestia' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'        => esc_html__( 'Subscribe Section', 'hestia' ),
			'id'            => 'subscribe-widgets',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'        => esc_html__( 'Blog Subscribe Section', 'hestia' ),
			'id'            => 'blog-subscribe-widgets',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'        => esc_html__( 'Footer One', 'hestia' ),
			'id'            => 'footer-one-widgets',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'        => esc_html__( 'Footer Two', 'hestia' ),
			'id'            => 'footer-two-widgets',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'        => esc_html__( 'Footer Three', 'hestia' ),
			'id'            => 'footer-three-widgets',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'WooCommerce Sidebar', 'hestia' ),
			'id'            => 'sidebar-woocommerce',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		)
	);
}

add_action( 'widgets_init', 'hestia_widgets_init' );

/**
 * Register Fonts
 *
 * @return string
 */
function hestia_fonts_url() {
	$fonts_url = '';

	/*
     Translators: If there are characters in your language that are not
     * supported by Roboto or Roboto Slab, translate this to 'off'. Do not translate
     * into your own language.
	 */
	$roboto = _x( 'on', 'Roboto font: on or off', 'hestia' );
	$roboto_slab = _x( 'on', 'Roboto Slab font: on or off', 'hestia' );

	if ( 'off' !== $roboto || 'off' !== $roboto_slab ) {
		$font_families = array();

		if ( 'off' !== $roboto ) {
			$font_families[] = 'Roboto:300,400,500,700';
		}

		if ( 'off' !== $roboto_slab ) {
			$font_families[] = 'Roboto Slab:400,700';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}
	return $fonts_url;
}

/**
 * Registering and enqueuing scripts/stylesheets to header/footer.
 *
 * @since Hestia 1.0
 */
function hestia_scripts() {
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css', array(), HESTIA_VENDOR_VERSION );
	wp_style_add_data( 'bootstrap', 'rtl', 'replace' );
	wp_style_add_data( 'bootstrap', 'suffix', '.min' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css', array(), HESTIA_VENDOR_VERSION );
	wp_enqueue_style( 'hestia_style', get_stylesheet_uri(), array(), HESTIA_VERSION );
	wp_style_add_data( 'hestia_style', 'rtl', 'replace' );
	wp_enqueue_style( 'hestia_fonts', hestia_fonts_url(), array(), HESTIA_VERSION );
	if ( is_singular() ) {
		wp_enqueue_script( 'comment-reply' );
	}
	if ( is_customize_preview() ) {
		wp_enqueue_style( 'hestia-customizer-preview-style', get_template_directory_uri() . '/assets/css/customizer-preview.css', array(), HESTIA_VERSION );
	}
	wp_enqueue_script( 'jquery-bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), HESTIA_VENDOR_VERSION, true );
	wp_enqueue_script( 'jquery-hestia-material', get_template_directory_uri() . '/assets/js/material.js', array( 'jquery' ), HESTIA_VENDOR_VERSION, true );
	if ( ( 'page' === get_option( 'show_on_front' ) ) || is_home() || is_single() || is_archive() || is_search() ) {
		wp_enqueue_script( 'jquery-matchHeight', get_template_directory_uri() . '/assets/js/jquery.matchHeight.js', array( 'jquery' ), HESTIA_VENDOR_VERSION, true );
	}
	wp_enqueue_script( 'hestia_sections_match_height', get_template_directory_uri() . '/assets/js/sectionsMatchHeight.js', array( 'jquery' ),HESTIA_VERSION, true );
	wp_enqueue_script( 'hestia_scripts', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery-hestia-material', 'jquery-ui-core', 'hestia_sections_match_height' ),HESTIA_VERSION, true );

	$hestia_cart_url = '';
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		$cart_url = $woocommerce->cart->get_cart_url();
		if ( ! empty( $cart_url ) ) {
			$hestia_cart_url = $cart_url;
		}
	}
	wp_localize_script(
		'hestia_scripts', 'hestiaViewcart', array(
			'view_cart_label' => esc_html__( 'View cart', 'hestia' ), // label of View cart button,
		'view_cart_link' => esc_url( $hestia_cart_url ), // link of View cart button
		)
	);
}
add_action( 'wp_enqueue_scripts', 'hestia_scripts' );

/**
 * Add appropriate classes to body tag.
 *
 * @since Hestia 1.0
 */
function hestia_body_classes( $classes ) {
	if ( is_singular() ) {
		$classes[] = 'blog-post';
	}
	return $classes;
}

add_filter( 'body_class', 'hestia_body_classes' );

/**
 * Define excerpt length.
 *
 * @since Hestia 1.0
 */
function hestia_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}
	if ( ( 'page' === get_option( 'show_on_front' ) ) || is_single() ) {
		return 35;
	} elseif ( is_home() ) {
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			return 40;
		} else {
			return 75;
		}
	} else {
		return 50;
	}
}

add_filter( 'excerpt_length', 'hestia_excerpt_length', 999 );

/**
 * Replace excerpt "Read More" text with a link.
 *
 * @since Hestia 1.0
 */
function hestia_excerpt_more( $more ) {
	global $post;
	if ( ( ( 'page' === get_option( 'show_on_front' ) ) ) || is_single() || is_archive() || is_home() ) {
		return '<a class="moretag" href="' . esc_url( get_permalink( $post->ID ) ) . '"> ' . esc_html__( 'Read more&hellip;', 'hestia' ) . '</a>';
	}
	return $more;
}
add_filter( 'excerpt_more', 'hestia_excerpt_more' );

/**
 * Move comment field above user details.
 *
 * @since Hestia 1.0
 */
function hestia_comment_message( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}

add_filter( 'comment_form_fields', 'hestia_comment_message' );

/**
 * Define Allowed Files to be included.
 *
 * @since Hestia 1.0
 */
function hestia_filter_features( $array ) {
	return array_merge(
		$array, array(
			'features/import-zerif-content',

			'features/feature-themeisle-lite-manager',

			'features/feature-general-settings',
			'features/feature-blog-settings',
			'features/feature-general-credits',
			'features/feature-slider-section',
			'features/feature-big-title-section',
			'features/feature-features-section',
			'features/feature-features-on-product',
			'features/feature-about-section',
			'features/feature-shop-section',
			'features/feature-portfolio-section',
			'features/feature-team-section',
			'features/feature-pricing-section',
			'features/feature-testimonials-section',
			'features/feature-subscribe-section',
			'features/feature-blog-section',
			'features/feature-contact-section',
			'features/feature-color-settings',
			'features/feature-advanced-color-settings',
			'features/feature-section-ordering',
			'features/feature-theme-info-section',

			'sections/feature-blog-authors-section',
			'sections/feature-blog-subscribe-section',
			'sections/hestia-slider-section',
			'sections/hestia-big-title-section',
			'sections/hestia-features-section',
			'sections/hestia-about-section',
			'sections/hestia-shop-section',
			'sections/hestia-portfolio-section',
			'sections/hestia-team-section',
			'sections/hestia-pricing-section',
			'sections/hestia-testimonials-section',
			'sections/hestia-subscribe-section',
			'sections/hestia-blog-section',
			'sections/hestia-contact-section',
			'sections/hestia-authors-blog-section',
			'sections/hestia-subscribe-blog-section',

			'customizer-theme-info/class-customizer-theme-info-root',
			'features/feature-pro-manager',
			'features/feature-about-page',
			'companion/customizer',

			'wpml-pll/functions',
			'legacy',
			'shortcodes/functions',

			'typography/typography-settings',
			'typography/typography-pro-settings',

		)
	);
}

add_filter( 'hestia_filter_features', 'hestia_filter_features' );

/**
 * Include page builder support for frontpage sections hiding
 */
function hestia_pagebuilders_support( $array ) {
	return array_merge(
		$array, array(
			'features/feature-pagebuilder-frontpage',
		)
	);
}

if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	add_filter( 'hestia_filter_features', 'hestia_pagebuilders_support' );
}

/**
 * Include features files.
 *
 * @since Hestia 1.0
 */
function hestia_include_features() {
	$hestia_allowed_phps = array();
	$hestia_allowed_phps = apply_filters( 'hestia_filter_features',$hestia_allowed_phps );

	foreach ( $hestia_allowed_phps as $file ) {
		$hestia_file_to_include = HESTIA_PHP_INCLUDE . $file . '.php';
		if ( file_exists( $hestia_file_to_include ) ) {
			include_once( $hestia_file_to_include );
		}
	}
}

add_action( 'after_setup_theme','hestia_include_features', 0 );

/**
 * Adds inline style from customizer
 *
 * @since Hestia 1.0
 * @modified 1.1.30
 */
function hestia_inline_style() {
	$custom_css              = '';
	$hestia_features_content = get_theme_mod(
		'hestia_features_content', json_encode(
			array(
				array(
					'color'      => '#e91e63',
				),
				array(
					'color'      => '#00bcd4',
				),
				array(
					'color'      => '#4caf50',
				),
			)
		)
	);

	if ( ! empty( $hestia_features_content ) ) {
		$hestia_features_content = json_decode( $hestia_features_content );
		foreach ( $hestia_features_content as $key => $features_item ) {
			$box_nb = $key + 1;
			if ( ! empty( $features_item->color ) ) {
				$color = ! empty( $features_item->color ) ? apply_filters( 'hestia_translate_single_string', $features_item->color, 'Features section' ) : '';
				$custom_css .= '.feature-box:nth-child(' . esc_attr( $box_nb ) . ') .icon {
                            color: ' . esc_attr( $color ) . ';
				}';
			}
		}
	}
	wp_add_inline_style( 'hestia_style', $custom_css );
}

add_action( 'wp_enqueue_scripts', 'hestia_inline_style' );

// Add Reading Time to Blog Section.
add_action( 'hestia_blog_reading_time', 'hestia_reading_time' );

// Add Related Posts to Single Posts.
add_action( 'hestia_blog_related_posts', 'hestia_related_posts' );

// Add Social Icons to Single Posts.
add_action( 'hestia_blog_social_icons', 'hestia_social_icons' );

/**
 * Filter the front page template so it's bypassed entirely if the user selects
 * to display blog posts on their homepage instead of a static page.
 */
function hestia_filter_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template', 'hestia_filter_front_page_template' );

/**
 * Filter to modify input label in repeater control
 * You can filter by control id and input name.
 *
 * @param string $string Input label.
 * @param string $id Input id.
 * @param string $control Control name.
 *
 * @return string
 */
function hestia_repeater_labels( $string, $id, $control ) {

	if ( $id === 'hestia_slider_content' ) {
		if ( $control === 'customizer_repeater_text_control' ) {
			return esc_html__( 'Button Text','hestia' );
		}
	}
	return $string;
}
add_filter( 'repeater_input_labels_filter','hestia_repeater_labels', 10 , 3 );

/**
 * Filter to modify input type in repeater control
 * You can filter by control id and input name.
 *
 * @param string $string Input label.
 * @param string $id Input id.
 * @param string $control Control name.
 *
 * @return string
 */
function hestia_repeater_input_types( $string, $id, $control ) {

	if ( $id === 'hestia_slider_content' ) {
		if ( $control === 'customizer_repeater_text_control' ) {
			return '';
		}
		if ( $control === 'customizer_repeater_subtitle_control' ) {
			return 'textarea';

		}
	}
	return $string;
}
add_filter( 'hestia_repeater_input_types_filter','hestia_repeater_input_types', 10 , 3 );

add_action( 'wp_ajax_nopriv_request_post', 'hestia_requestpost' );
add_action( 'wp_ajax_request_post', 'hestia_requestpost' );
/**
 * Ajax function for refresh purposes.
 */
function hestia_requestpost() {
	$pid = absint( $_POST['pid'] );

	if ( ! empty( $pid ) && $pid !== 0 ) {
		hestia_sync_control_from_page( $pid, true );
	}
	echo '';
	die();
}

/**
 * Add starter content for fresh sites
 *
 * @since 1.1.8
 * @modified 1.1.31
 */
function hestia_starter_content() {
	$default_home_content = '<div class="col-md-5"><h3>' . esc_html__( 'About Hestia', 'hestia' ) . '</h3>' . esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Hestia.', 'hestia' ) . '</div><div class="col-md-6 col-md-offset-1"><img class="size-medium alignright" src="' . esc_url( get_template_directory_uri() . '/assets/img/about-content.png' ) . '"/></div>';
	$default_home_featured_image = get_template_directory_uri() . '/assets/img/contact.jpg';

	/*
	 * Starter Content Support
	 */
	add_theme_support(
		'starter-content', array(
			'attachments' => array(
				'featured-image-home' => array(
					'post_title' => 'Featured Image Homepage',
					'post_content' => 'The featured image for the front page.',
					'file' => 'assets/img/contact.jpg',
				),
			),
			'posts' => array(
				'home' => array(
					'post_content' => $default_home_content,
					'thumbnail' => '{{featured-image-home}}',
				),
				'blog',
			),

			'nav_menus' => array(
				'primary' => array(
					'name'  => esc_html__( 'Primary Menu', 'hestia' ),
					'items' => array(
						'page_home',
						'page_blog',
					),
				),
			),

			'options' => array(
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{blog}}',
				'hestia_page_editor' => $default_home_content,
				'hestia_feature_thumbnail' => $default_home_featured_image,
			),
		)
	);
}
add_action( 'after_setup_theme', 'hestia_starter_content' );

/**
 * Save activation time.
 *
 * @since 1.1.22
 * @access public
 */
function hestia_time_activated() {
	update_option( 'hestia_time_activated', time() );
}
add_action( 'after_switch_theme', 'hestia_time_activated' );

/**
 * Check if 12 hours have passed since theme was activated and show upsells in customizer if yes.
 *
 * @since 1.1.22
 * @access public
 * @return bool
 */
function hestia_ready_for_upsells() {
	$activation_time = get_option( 'hestia_time_activated' );
	if ( ! empty( $activation_time ) ) {
		$current_time    = time();
		$time_difference = 43200;
		if ( $current_time >= $activation_time + $time_difference ) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}

/**
 * Upgrade link to BeaverBuilder
 */
function hestia_bb_upgrade_link() {
	return 'https://www.wpbeaverbuilder.com/?fla=101&campaign=hestia';
}

add_filter( 'fl_builder_upgrade_url', 'hestia_bb_upgrade_link' );




add_action( 'wp_ajax_dismissed_notice_handler', 'hestia_ajax_notice_handler' );

add_action( 'wp_ajax_nopriv_dismissed_notice_handler', 'hestia_ajax_notice_handler' );
/**
 * AJAX handler to store the state of dismissible notices.
 */
function hestia_ajax_notice_handler() {
	$control_id = sanitize_text_field( wp_unslash( $_POST['control'] ) );
	update_option( 'dismissed-' . $control_id, true );
	echo $control_id;
	die();
}

/**
 * Check if URL is external
 */
function hestia_is_external_url( $url ) {
	$link_url = parse_url( $url );
	$home_url = parse_url( home_url() );

	if ( ! empty( $link_url['host'] ) ) {
		if ( $link_url['host'] !== $home_url['host'] ) {
			echo 'target="_blank"';
		}
	}
}
