<?php
/**
 * The Banners Section
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

		/* BANNERS */


		$shop_isle_banners_hide = get_theme_mod( 'shop_isle_banners_hide' );
		$shop_isle_banners_title = get_theme_mod( 'shop_isle_banners_title' );

if ( isset( $shop_isle_banners_hide ) && $shop_isle_banners_hide != 1 ) :
	echo '<section class="module-small home-banners">';
		elseif ( is_customize_preview() ) :
			echo '<section class="module-small home-banners shop_isle_hidden_if_not_customizer">';
		endif;

		if ( ( isset( $shop_isle_banners_hide ) && $shop_isle_banners_hide != 1) || is_customize_preview() ) :

			$shop_isle_banners = get_theme_mod( 'shop_isle_banners', json_encode( array(
				array(
					'image_url' => get_template_directory_uri() . '/assets/images/banner1.jpg',
					'link' => '#',
				),
				array(
					'image_url' => get_template_directory_uri() . '/assets/images/banner2.jpg',
					'link' => '#',
				),
				array(
					'image_url' => get_template_directory_uri() . '/assets/images/banner3.jpg',
					'link' => '#',
				),
			) ) );

			if ( ! empty( $shop_isle_banners ) ) :

				$shop_isle_banners_decoded = json_decode( $shop_isle_banners );

				if ( ! empty( $shop_isle_banners_decoded ) ) :

						echo '<div class="container">';

					if ( ! empty( $shop_isle_banners_title ) ) {
						echo '<div class="row">';
						echo '<div class="col-sm-6 col-sm-offset-3">';
						echo '<h2 class="module-title font-alt product-banners-title">' . $shop_isle_banners_title . '</h2>';
						echo '</div>';
						echo '</div>';

					} elseif ( is_customize_preview() ) {
						echo '<div class="row">';
						echo '<div class="col-sm-6 col-sm-offset-3">';
						echo '<h2 class="module-title font-alt product-banners-title shop_isle_hidden_if_not_customizer"></h2>';
						echo '</div>';
						echo '</div>';
					}

							echo '<div class="row shop_isle_bannerss_section">';

					foreach ( $shop_isle_banners_decoded as $shop_isle_banner ) :

						if ( ! empty( $shop_isle_banner->image_url ) ) {

							echo '<div class="col-sm-4"><div class="content-box mt-0 mb-0"><div class="content-box-image">';

							if ( ! empty( $shop_isle_banner->link ) ) {

								if ( function_exists( 'icl_t' ) && ! empty( $shop_isle_banner->id ) ) {
									$shop_isle_banner_link = icl_t( 'Banner ' . $shop_isle_banner->id, 'Banner link', $shop_isle_banner->link );
									$shop_isle_banner_image_url = icl_t( 'Banner ' . $shop_isle_banner->id, 'Banner image', $shop_isle_banner->image_url );
									echo '<a href="' . esc_url( $shop_isle_banner_link ) . '"><img src="' . esc_url( $shop_isle_banner_image_url ) . '"></a>';
								} else {
									echo '<a href="' . esc_url( $shop_isle_banner->link ) . '"><img src="' . esc_url( $shop_isle_banner->image_url ) . '"></a>';
								}
							} else {
								if ( function_exists( 'icl_t' ) && ! empty( $shop_isle_banner->id ) ) {
									$shop_isle_banner_image_url = icl_t( 'Banner ' . $shop_isle_banner->id, 'Banner image', $shop_isle_banner->image_url );
									echo '<a><img src="' . esc_url( $shop_isle_banner_image_url ) . '"></a>';
								} else {
									echo '<a><img src="' . esc_url( $shop_isle_banner->image_url ) . '"></a>';
								}
							}
							echo '</div></div></div>';

						}

								endforeach;

							echo '</div>';

						echo '</div>';

				endif;

			endif;

			echo '</section>';

			echo '<hr class="divider-w">';

		endif;	/* END BANNERS */


