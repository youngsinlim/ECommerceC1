<?php
/**
 * Shop section for the homepage.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_shop' ) ) :
	/**
	 * Shop section content.
	 *
	 * @since Hestia 1.0
	 * @modified 1.1.34
	 */
	function hestia_shop( $is_shortcode = false ) {
		$hide_section = get_theme_mod( 'hestia_shop_hide', false );
		if ( ! $is_shortcode && (bool) $hide_section === true ) {
			return;
		}
		if ( class_exists( 'woocommerce' ) ) :

			if ( current_user_can( 'edit_theme_options' ) ) {
				/* translators: 1 - link to customizer setting. 2 - 'customizer' */
				$hestia_shop_subtitle = get_theme_mod( 'hestia_shop_subtitle', sprintf( __( 'Change this subtitle in %s.', 'hestia' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=hestia_shop_subtitle' ) ), __( 'customizer', 'hestia' ) ) ) );
			} else {
				$hestia_shop_subtitle = get_theme_mod( 'hestia_shop_subtitle' );
			}
			$hestia_shop_title = get_theme_mod( 'hestia_shop_title', esc_html__( 'Products', 'hestia' ) );
			if ( $is_shortcode ) {
				$hestia_shop_title = '';
				$hestia_shop_subtitle = '';
			}

			$hestia_shop_items = get_theme_mod( 'hestia_shop_items', 4 );

			$class_to_add = 'container';
			if ( $is_shortcode ) {
				$class_to_add = '';
			}
			?>
			<section class="products section-gray" id="products" data-sorder="hestia_shop">
				<div class="<?php echo esc_attr( $class_to_add ); ?>">
					<div class="row">
						<div class="col-md-8 col-md-offset-2 text-center">
							<?php if ( ! empty( $hestia_shop_title ) || is_customize_preview() ) : ?>
								<h2 class="hestia-title"><?php echo esc_html( $hestia_shop_title ); ?></h2>
							<?php endif; ?>
							<?php if ( ! empty( $hestia_shop_subtitle ) || is_customize_preview() ) : ?>
								<h5 class="description"><?php echo wp_kses_post( $hestia_shop_subtitle ); ?></h5>
							<?php endif; ?>
						</div>
					</div>
					<?php hestia_shop_content( $hestia_shop_items ); ?>
				</div>
			</section>
			<?php
		endif;
	}

endif;


/**
 * Get content for shop section.
 *
 * @since 1.1.31
 * @access public
 * @param string $hestia_shop_items Number of items.
 * @param bool   $is_callback Flag to check if it's callback or not.
 */
function hestia_shop_content( $hestia_shop_items, $is_callback = false ) {
	if ( ! $is_callback ) {
	?>
		<div class="hestia-shop-content">
		<?php
	}

	$args = array(
		'post_type' => 'product',
	);
		$args['posts_per_page'] = ! empty( $hestia_shop_items ) ? absint( $hestia_shop_items ) : 4;

		$loop = new WP_Query( $args );

	if ( $loop->have_posts() ) :
		$i = 1;
		echo '<div class="row">';
		while ( $loop->have_posts() ) :
			$loop->the_post();
			global $product;
			global $post;
			?>
				<div class="col-sm-6 col-md-3 shop-item">
					<div class="card card-product">
						<?php if ( has_post_thumbnail() ) : ?>
						<div class="card-image">
							<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail( 'hestia-shop' ); ?>
							</a>
							<div class="ripple-container"></div>
						</div>
					<?php endif; ?>
						<div class="content">
							<?php
							if ( function_exists( 'wc_get_product_category_list' ) ) {
								$prod_id = get_the_ID();
								$product_categories = wc_get_product_category_list( $prod_id );
							} else {
								$product_categories = $product->get_categories();
							}

							if ( ! empty( $product_categories ) ) {

								$allowed_html = array(
									'a' => array(
										'href' => array(),
										'rel' => array(),
									),
								);

								echo '<h6 class="category">';

								echo wp_kses( $product_categories, $allowed_html );

								echo '</h6>';
							}
							?>

							<h4 class="card-title">

							<a class="shop-item-title-link" href="<?php the_permalink(); ?>"
						   title="<?php the_title_attribute(); ?>"><?php esc_html( the_title() ); ?></a>

						</h4>

						<?php if ( $post->post_excerpt ) : ?>

							<div class="card-description"><?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ); ?></div>

						<?php endif; ?>

						<div class="footer">

							<?php
							$product_price = $product->get_price_html();

							if ( ! empty( $product_price ) ) {

								echo '<div class="price"><h4>';

								echo wp_kses(
									$product_price, array(
										'span' => array(
											'class' => array(),
										),
										'del' => array(),
									)
								);

								echo '</h4></div>';

							}
							?>

								<div class="stats">
								<?php hestia_add_to_cart(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				if ( $i % 4 == 0 ) {
					echo '</div><!-- /.row -->';
					echo '<div class="row">';
				}
				$i++;
		endwhile;
		echo '</div>';

	endif;

	if ( ! $is_callback ) {
	?>
						</div>
						<?php
	}
}

if ( function_exists( 'hestia_shop' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 20, 'hestia_shop' );
	add_action( 'hestia_sections', 'hestia_shop', absint( $section_priority ) );
}
