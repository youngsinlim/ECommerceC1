<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package shop-isle
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function shop_isle_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function shop_isle_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {
		$classes[]	= 'no-wc-breadcrumb';
	}

	/**
	 * What is this?!
	 * Take the blue pill, close this file and forget you saw the following code.
	 * Or take the red pill, filter shop_isle_make_me_cute and see how deep the rabbit hole goes...
	 */
	$cute	= apply_filters( 'shop_isle_make_me_cute', false );

	if ( true === $cute ) {
		$classes[] = 'shop-isle-cute';
	}

	return $classes;
}

if ( ! function_exists( 'is_woocommerce_activated' ) ) {

	/**
	 * Query WooCommerce activation
	 */
	function is_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/**
 * Schema type
 */
function shop_isle_html_tag_schema() {
	$schema 	= 'http://schema.org/';
	$type 		= 'WebPage';

	// Is single post
	if ( is_singular( 'post' ) ) {
		$type 	= 'Article';
	} // End if().
	elseif ( is_author() ) {
		$type 	= 'ProfilePage';
	} // Is search results page
	elseif ( is_search() ) {
		$type 	= 'SearchResultsPage';
	}

	echo 'itemscope="itemscope" itemtype="' . esc_attr( $schema ) . esc_attr( $type ) . '"';
}


/**
 * Add meta box for page header description - save meta box
 *
 * @since  1.0.0
 */
function shop_isle_custom_add_save( $post_id ) {
	$parent_id = wp_is_post_revision( $post_id );
	if ( $parent_id ) {
		$post_id = $parent_id;
	}
	if ( isset( $_POST['shop_isle_page_description'] ) ) {
		shop_isle_update_custom_meta( $post_id, $_POST['shop_isle_page_description'], 'shop_isle_page_description' );
	}
}

/**
 * Add meta box for page header description - update meta box
 *
 * @since  1.0.0
 */
function shop_isle_update_custom_meta( $post_id, $newvalue, $field_name ) {
	// To create new meta
	if ( ! get_post_meta( $post_id, $field_name ) ) {
		add_post_meta( $post_id, $field_name, $newvalue );
	} else {
		// or to update existing meta
		update_post_meta( $post_id, $field_name, $newvalue );
	}
}
