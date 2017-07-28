<?php
/**
 * The template for displaying all single posts and attachments.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

get_header();
	hestia_output_wrapper_header_start( false ); ?>
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1 text-center">
					<?php single_post_title( '<h1 class="hestia-title">', '</h1>' ); ?>
					<h4 class="author">
						<?php
						printf(
							/* translators: %1$s is Author name wrapped, %2$s is Date*/
							esc_html__( 'Published by %1$s on %2$s', 'hestia' ),
							sprintf(
								/* translators: %1$s is Author name, %2$s is Author link*/
								'<a href="%2$s"><b>%1$s</b></a>',
								esc_html( hestia_get_author( 'display_name' ) ),
								esc_url( get_author_posts_url( hestia_get_author( 'ID' ) ) )
							),
							/* translators: %s is Date */
							sprintf(
								'<time>%s</time>',
								esc_html( get_the_time( get_option( 'date_format' ) ) )
							)
						);
						?>
						</h4>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="<?php echo hestia_layout(); ?>">
	<div class="blog-post blog-post-wrapper">
		<div class="container">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'single' );
				endwhile;
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif;
			?>
		</div>
	</div>
</div>
<?php do_action( 'hestia_blog_related_posts' ); ?>
<div class="footer-wrapper">
	<?php get_footer(); ?>
