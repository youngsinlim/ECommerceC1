<?php
/**
 * Sync functions for control.
 *
 * @package Hestia
 * @since Hestia 1.1.3
 */

// Load Customizer page editor.
$page_editor_path = HESTIA_PHP_INCLUDE . 'customizer-page-editor/class/class-hestia-page-editor.php';
if ( file_exists( $page_editor_path ) ) {
	require_once( $page_editor_path );
}

/**
 * Sync frontpage content with customizer control
 *
 * @param string $value New value.
 * @param string $old_value Old value.
 *
 * @return mixed
 */
function hestia_sync_content_from_control( $value = '', $old_value = '' ) {
	$frontpage_id = get_option( 'page_on_front' );
	if ( ! empty( $frontpage_id ) ) {
		if ( ! wp_is_post_revision( $frontpage_id ) ) {

			// unhook this function so it doesn't loop infinitely
			remove_action( 'save_post', 'hestia_sync_control_from_page' );

			// update the post, which calls save_post again
			$post = array(
				'ID'           => $frontpage_id,
				'post_content' => wp_kses_post( $value ),
			);

			wp_update_post( $post );

			// re-hook this function
			add_action( 'save_post', 'hestia_sync_control_from_page' );
		}
	}

	return $value;
}

add_filter( 'pre_set_theme_mod_hestia_page_editor', 'hestia_sync_content_from_control', 10, 2 );


/**
 * Sync frontpage thumbnail with customizer control
 *
 * @param string $value New value.
 * @param string $old_value Old value.
 *
 * @return mixed
 */
function hestia_sync_thumbnail_from_control( $value, $old_value ) {

	$frontpage_id = get_option( 'page_on_front' );
	if ( ! empty( $frontpage_id ) ) {
		$thumbnail_id = attachment_url_to_postid( $value );
		update_post_meta( $frontpage_id, '_thumbnail_id', $thumbnail_id );
	}

	return $value;
}

add_filter( 'pre_set_theme_mod_hestia_feature_thumbnail', 'hestia_sync_thumbnail_from_control', 10, 2 );

/**
 * Sync page thumbnail and content with customizer control
 *
 * @param int $post_id Page id.
 */
function hestia_sync_control_from_page( $post_id, $ajax_call = false ) {
	if ( ! wp_is_post_revision( $post_id ) ) {
		$return_value = array();
		remove_action( 'save_post', 'hestia_sync_control_from_thumbnail' );

		$frontpage_id = get_option( 'page_on_front' );
		if ( (int) $frontpage_id !== (int) $post_id || empty( $frontpage_id ) ) {
			return;
		}

		$content = get_post_field( 'post_content', $frontpage_id );

		set_theme_mod( 'hestia_page_editor', $content );

		$hestia_frontpage_featured = '';
		if ( has_post_thumbnail( $frontpage_id ) ) {
			$hestia_frontpage_featured = get_the_post_thumbnail_url( $frontpage_id );
		}
		set_theme_mod( 'hestia_feature_thumbnail', $hestia_frontpage_featured );

		add_action( 'save_post', 'hestia_sync_control_from_thumbnail' );

		if ( $ajax_call === true ) {
			$return_value['post_content']   = $content;
			$return_value['post_thumbnail'] = $hestia_frontpage_featured;
			echo json_encode( $return_value );
		}
	}
	echo '';
}

add_action( 'save_post', 'hestia_sync_control_from_page' );

/**
 * Ajax call to sync page content and thumbnail when you switch to static frontpage
 */
function hestia_ajax_call() {
	$pid = $_POST['pid'];
	hestia_sync_control_from_page( $pid, true );
	die();
}

add_action( 'wp_ajax_hestia_ajax_call', 'hestia_ajax_call' );

/**
 * Change the default editor to html when using the tinyMce editor in customizer.
 *
 * @param string $r The current value of the default editor.
 *
 * @return string The new value of the editor, if we are in the customizer page.
 */
function hestia_set_default_editor( $r ) {
	if ( is_customize_preview() && function_exists( 'get_current_screen' ) ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			if ( $screen->id === 'customize' ) {
				return 'tmce';
			}
		}
	}

	return $r;
}

add_filter( 'wp_default_editor', 'hestia_set_default_editor' );

/**
 * Filters for text format
 */
add_filter( 'hestia_text', 'wptexturize' );
add_filter( 'hestia_text', 'convert_smilies' );
add_filter( 'hestia_text', 'convert_chars' );
add_filter( 'hestia_text', 'wpautop' );
add_filter( 'hestia_text', 'shortcode_unautop' );
add_filter( 'hestia_text', 'do_shortcode' );
