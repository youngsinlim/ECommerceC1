<?php
/*
Plugin Name: Shop Isle Companion
Plugin URI: https://github.com/Codeinwp/shop-isle-companion
Description: Add a slider to the front page, add new sections to the about page template in Shop Isle.
Version: 1.0.7
Author: Themeisle
Author URI: http://themeisle.com
Text Domain: shop-isle-companion
Domain Path: /languages
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

$current_theme = wp_get_theme();

if ( ! function_exists( 'add_action' ) ) {
    die('Nothing to do...');
}

/**
 * Register the activation hook.
 */
register_activation_hook( __FILE__, 'shop_isle_companion_activated' );

/**
 * Add activation action
 */
function shop_isle_companion_activated() {
    do_action('shop_isle_companion_activation');
}

/**
 * Filter to replace big title section with slider.
 */
add_filter ( 'shop-isle-subheader', 'shop_isle_companion_slider');

/**
 * Function used for subheader filter/
 * @return string
 */
function shop_isle_companion_slider() {
    return plugin_dir_path( __FILE__ ) . 'content-slider.php';
}

/**
 * Include customizer controls.
 */
require plugin_dir_path( __FILE__ ) . 'customizer.php';

/**
 * Include template loader.
 */
require plugin_dir_path( __FILE__ ) . 'class-template-loader.php';


add_action('shop-isle-about-page-after-content', 'shop_isle_companion_about_addon');
/**
 * Function to import customizer big title settings into first slide.
 */

function shop_isle_get_wporg_options() {
    /* import shop isle options */
    $shop_isle_mods = get_option('theme_mods_shop-isle');

    if (!empty($shop_isle_mods)) {

        $new_slider = new stdClass();

        foreach ($shop_isle_mods as $shop_isle_mod_k => $shop_isle_mod_v) {

            /* migrate Big title section to Slider section */
            if (($shop_isle_mod_k == 'shop_isle_big_title_image') || ($shop_isle_mod_k == 'shop_isle_big_title_title') || ($shop_isle_mod_k == 'shop_isle_big_title_subtitle') || ($shop_isle_mod_k == 'shop_isle_big_title_button_label') || ($shop_isle_mod_k == 'shop_isle_big_title_button_link')) {

                if ($shop_isle_mod_k == 'shop_isle_big_title_image') {
                    if (!empty($shop_isle_mod_v)) {
                        $new_slider->image_url = $shop_isle_mod_v;
                    } else {
                        $new_slider->image_url = '';
                    }
                }

                if ($shop_isle_mod_k == 'shop_isle_big_title_title') {
                    if (!empty($shop_isle_mod_v)) {
                        $new_slider->text = $shop_isle_mod_v;
                    } else {
                        $new_slider->text = '';
                    }
                }

                if ($shop_isle_mod_k == 'shop_isle_big_title_subtitle') {
                    if (!empty($shop_isle_mod_v)) {
                        $new_slider->subtext = $shop_isle_mod_v;
                    } else {
                        $new_slider->subtext = '';
                    }
                }

                if ($shop_isle_mod_k == 'shop_isle_big_title_button_label') {
                    if (!empty($shop_isle_mod_v)) {
                        $new_slider->label = $shop_isle_mod_v;
                    } else {
                        $new_slider->label = '';
                    }
                }

                if ($shop_isle_mod_k == 'shop_isle_big_title_button_link') {
                    if (!empty($shop_isle_mod_v)) {
                        $new_slider->link = $shop_isle_mod_v;
                    } else {
                        $new_slider->link = '';
                    }
                }

                if ( !empty($new_slider->image_url) || !empty($new_slider->text) || !empty($new_slider->subtext) || !empty($new_slider->link) ) {
                    $new_slider_encode = json_encode(array($new_slider));
                    set_theme_mod('shop_isle_slider', $new_slider_encode);
                }

            } else {

                set_theme_mod($shop_isle_mod_k, $shop_isle_mod_v);
            }
        }
    }

}

/*
 * Import customizer options from Lite version
 */
add_action( 'shop_isle_companion_activation', 'shop_isle_get_wporg_options' );



function shop_isle_companion_themeisle_sdk(){
	require dirname(__FILE__).'/vendor/themeisle/load.php';
	themeisle_sdk_register (
		array(
			'product_slug'=>'shop-isle-companion',
			'store_url'=>'https://themeisle.com',
			'store_name'=>'Themeisle',
			'product_type'=>'plugin',
			'wordpress_available'=>false,
			'paid'=>false,
		)
	);
}

shop_isle_companion_themeisle_sdk(); 

 
