<?php
/**
 * Blog section for the homepage.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_blog' ) ) :
	/**
	 * Blog section content.
	 *
	 * @since Hestia 1.0
	 * @modified 1.1.34
	 */
	function hestia_blog( $is_shortcode = false ) {
		$hide_section = get_theme_mod( 'hestia_blog_hide', false );
		if ( ! $is_shortcode && (bool) $hide_section === true ) {
			return;
		}

		if ( current_user_can( 'edit_theme_options' ) ) {
			/* translators: 1 - link to customizer setting. 2 - 'customizer' */
			$hestia_blog_subtitle = get_theme_mod( 'hestia_blog_subtitle', sprintf( __( 'Change this subtitle in %s.','hestia' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=hestia_blog_subtitle' ) ), __( 'customizer','hestia' ) ) ) );
		} else {
			$hestia_blog_subtitle = get_theme_mod( 'hestia_blog_subtitle' );
		}
		$hestia_blog_title = get_theme_mod( 'hestia_blog_title', __( 'Blog', 'hestia' ) );
		if ( $is_shortcode ) {
			$hestia_blog_title = '';
			$hestia_blog_subtitle = '';
		}
		$hestia_blog_items = get_theme_mod( 'hestia_blog_items', 3 );

		$class_to_add = 'container';
		if ( $is_shortcode ) {
			$class_to_add = '';
		}
		?>
		<section class="blogs hestia-blogs" id="blog" data-sorder="hestia_blog">
			<div class="<?php echo esc_attr( $class_to_add ); ?>">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 text-center">
					<?php if ( ! empty( $hestia_blog_title ) || is_customize_preview() ) : ?>
						<h2 class="hestia-title"><?php echo esc_html( $hestia_blog_title ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $hestia_blog_subtitle ) || is_customize_preview() ) : ?>
						<h5 class="description"><?php echo wp_kses_post( $hestia_blog_subtitle ); ?></h5>
					<?php endif; ?>
					</div>
				</div>
				<?php hestia_blog_content( $hestia_blog_items ); ?>
			</div>
		</section>
		<?php
	}

endif;

/**
 * Get content for blog section.
 *
 * @since 1.1.31
 * @access public
 * @param string $hestia_blog_items Number of items.
 * @param bool   $is_callback Flag to check if it's callback or not.
 */
function hestia_blog_content( $hestia_blog_items, $is_callback = false ) {
	if ( ! $is_callback ) {
	?>
		<div class="hestia-blog-content">
		<?php
	}

	$args = array(
		'ignore_sticky_posts' => true,
	);
		$args['posts_per_page'] = ! empty( $hestia_blog_items ) ? absint( $hestia_blog_items ) : 3;

		$loop = new WP_Query( $args );

		$allowed_html = array(
			'br' => array(),
			'em' => array(),
			'strong' => array(),
			'i' => array(
				'class' => array(),
			),
			'span' => array(),
		);

	if ( $loop->have_posts() ) :
		$i = 1;
		echo '<div class="row">';
		while ( $loop->have_posts() ) :
			$loop->the_post();
			?>
			<article class="col-md-4 hestia-blog-item">
				<div class="card card-plain card-blog">
					<?php if ( has_post_thumbnail() ) : ?>
					<div class="card-image">
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php the_post_thumbnail( 'hestia-blog' ); ?>
						</a>
					</div>
				<?php endif; ?>
					<div class="content">
						<h6 class="category"><?php hestia_category(); ?></h6>
						<h4 class="card-title">
							<a class="blog-item-title-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
								<?php echo wp_kses( force_balance_tags( get_the_title() ), $allowed_html ); ?>
							</a>
						</h4>
						<p class="card-description"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
					</div>
				</div>
			</article>
			<?php
			if ( $i % 3 == 0 ) {
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


if ( function_exists( 'hestia_blog' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 50, 'hestia_blog' );
	add_action( 'hestia_sections', 'hestia_blog', absint( $section_priority ), 2 );
}
