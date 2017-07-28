<?php
/**
 * Big Title section for the homepage.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_big_title' ) ) :
	/**
	 * Big title section content.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_big_title() {
	?>
		<div id="carousel-hestia-generic" class="carousel slide" data-ride="carousel">
			<div class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					<?php

					if ( current_user_can( 'edit_theme_options' ) ) {
						/* translators: 1 - link to customizer setting. 2 - 'customizer' */
						$hestia_big_title_title = get_theme_mod( 'hestia_big_title_title', sprintf( esc_html__( 'Change in %s','hestia' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=hestia_big_title_title' ) ), __( 'Customizer','hestia' ) ) ) );
						/* translators: 1 - link to customizer setting. 2 - 'customizer' */
						$hestia_big_title_text = get_theme_mod( 'hestia_big_title_text', sprintf( esc_html__( 'Change this subtitle in %s.','hestia' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=hestia_big_title_text' ) ), __( 'Customizer','hestia' ) ) ) );
						$hestia_big_title_button_text = get_theme_mod( 'hestia_big_title_button_text', esc_html__( 'Change in the Customizer', 'hestia' ) );
						$hestia_big_title_button_link = get_theme_mod( 'hestia_big_title_button_link', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=hestia_big_title_button_text' ) ) );
					} else {
						$hestia_big_title_title       = get_theme_mod( 'hestia_big_title_title' );
						$hestia_big_title_text        = get_theme_mod( 'hestia_big_title_text' );
						$hestia_big_title_button_text = get_theme_mod( 'hestia_big_title_button_text' );
						$hestia_big_title_button_link = get_theme_mod( 'hestia_big_title_button_link' );
					}
					$hestia_big_title_background  = get_theme_mod( 'hestia_big_title_background', get_template_directory_uri() . '/assets/img/slider2.jpg' );

					if ( ! empty( $hestia_big_title_background ) || ! empty( $hestia_big_title_title ) || ! empty( $hestia_big_title_text ) || ( ! empty( $hestia_big_title_button_text ) && ! empty( $hestia_big_title_button_link ) ) ) {
					?>
						<div class="item active">
							<div class="page-header header-filter" 
							<?php
							if ( ! empty( $hestia_big_title_background ) ) {
								echo 'style="background-image: url(' . esc_url( $hestia_big_title_background ) . ')"';}
?>
>
								<?php
								if ( is_customize_preview() ) {
								?>
									<div class="big-title-image"></div>
									<?php
								}
								?>
								<div class="container">
									<div class="row">
										<div class="col-md-8 col-md-offset-2 text-center">
											<?php if ( ! empty( $hestia_big_title_title ) ) { ?>
												<h1 class="hestia-title"><?php echo wp_kses_post( $hestia_big_title_title ); ?></h1>
											<?php } ?>
											<?php if ( ! empty( $hestia_big_title_text ) ) { ?>
												<span class="sub-title"><?php echo wp_kses_post( $hestia_big_title_text ); ?></span>
											<?php } ?>
											<?php if ( ! empty( $hestia_big_title_button_link ) && ! empty( $hestia_big_title_button_text ) ) { ?>
												<div class="buttons">
													<a href="<?php echo esc_url( $hestia_big_title_button_link ); ?>" title="<?php echo esc_html( $hestia_big_title_button_text ); ?>"
													   class="btn btn-primary btn-lg" <?php hestia_is_external_url( $hestia_big_title_button_link ); ?>><?php echo esc_html( $hestia_big_title_button_text ); ?></a>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'hestia_slider_compatibility' ) ) :

	/**
	 * Check for previously set slider and make theme compatible.
	 */
	function hestia_slider_compatibility() {
		$hestia_big_title_background  = get_theme_mod( 'hestia_big_title_background' );
		$hestia_big_title_title       = get_theme_mod( 'hestia_big_title_title' );
		$hestia_big_title_text        = get_theme_mod( 'hestia_big_title_text' );
		$hestia_big_title_button_text = get_theme_mod( 'hestia_big_title_button_text' );
		$hestia_big_title_button_link = get_theme_mod( 'hestia_big_title_button_link' );

		$hestia_slider_content = get_theme_mod( 'hestia_slider_content' );

		if ( ! empty( $hestia_big_title_background ) || ! empty( $hestia_big_title_title ) || ! empty( $hestia_big_title_text ) || ! empty( $hestia_big_title_button_text ) || ! empty( $hestia_big_title_button_link ) ) {
			hestia_big_title();
		} else {
			if ( ! empty( $hestia_slider_content ) ) {
				hestia_slider();
			} else {
				hestia_big_title();
			}
		}
	}
endif;

add_action( 'hestia_header', 'hestia_slider_compatibility' );
