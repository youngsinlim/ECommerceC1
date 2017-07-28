<?php
/**
 * Subscribe section for the homepage.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_subscribe' ) ) :
	/**
	 * Subscribe section content.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_subscribe() {
		$hide_section = get_theme_mod( 'hestia_subscribe_hide', true );
		if ( (bool) $hide_section === true ) {
			return;
		}

		if ( current_user_can( 'edit_theme_options' ) ) {
			/* translators: 1 - link to customizer setting. 2 - 'customizer' */
			$hestia_subscribe_subtitle = get_theme_mod( 'hestia_subscribe_subtitle', sprintf( esc_html__( 'Change this subtitle in %s.','hestia' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=hestia_subscribe_subtitle' ) ), esc_html__( 'customizer','hestia' ) ) ) );
		} else {
			$hestia_subscribe_subtitle = get_theme_mod( 'hestia_subscribe_subtitle' );
		}

		$hestia_subscribe_title = get_theme_mod( 'hestia_subscribe_title', esc_html__( 'Subscribe to our Newsletter', 'hestia' ) );
			?>
		<section class="subscribe-line subscribe-line-image" id="subscribe" data-sorder="hestia_subscribe" style="background-image: url('<?php echo get_theme_mod( 'hestia_subscribe_background', get_template_directory_uri() . '/assets/img/about.jpg' ); ?>');">
			<?php
			if ( is_customize_preview() ) {
			?>
				<div class="hestia-subscribe-image"></div>
				<?php
			}
			?>
			<div class="container">
				<div class="row text-center">
					<div class="col-md-8 col-md-offset-2 text-center">
					<?php if ( ! empty( $hestia_subscribe_title ) || is_customize_preview() ) : ?>
						<h2 class="hestia-title"><?php echo esc_html( $hestia_subscribe_title ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $hestia_subscribe_subtitle ) || is_customize_preview() ) : ?>
						<h5 class="subscribe-description"><?php echo wp_kses_post( $hestia_subscribe_subtitle ); ?></h5>
					<?php endif; ?>
					</div>
				</div>
				<?php if ( is_active_sidebar( 'subscribe-widgets' ) ) : ?>
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="card card-raised card-form-horizontal">
							<div class="content">
								<div class="row">
								<?php dynamic_sidebar( 'subscribe-widgets' ); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
endif;

if ( function_exists( 'hestia_subscribe' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 45, 'hestia_subscribe' );
	add_action( 'hestia_sections', 'hestia_subscribe', absint( $section_priority ) );
}
