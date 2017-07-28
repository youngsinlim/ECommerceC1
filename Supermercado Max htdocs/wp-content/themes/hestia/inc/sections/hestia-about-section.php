<?php
/**
 * About section for the homepage.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_about' ) ) :
	/**
	 * About section content.
	 *
	 * @since Hestia 1.0
	 * @modified 1.1.30
	 */
	function hestia_about() {
		$class_to_add = '';
		$hestia_frontpage_featured = '';

		$hide_section = get_theme_mod( 'hestia_about_hide', false );
		if ( (bool) $hide_section === true ) {
			return;
		}

		if ( is_customize_preview() ) {
			$hestia_frontpage_featured = get_theme_mod( 'hestia_feature_thumbnail' );
			if ( ! empty( $hestia_frontpage_featured ) ) {
				$class_to_add = 'section-image';
			}
		} else {
			if ( has_post_thumbnail() ) {
				$class_to_add = 'section-image';
				$hestia_frontpage_featured = get_the_post_thumbnail_url();
			}
		}
		?>
		<section class="about hestia-about 
		<?php
		if ( ! empty( $class_to_add ) ) {
			echo esc_attr( $class_to_add ); }
?>
" id="about" data-sorder="hestia_about" 
<?php
if ( ! empty( $hestia_frontpage_featured ) ) {
			echo 'style="background-image: url(\'' . esc_url( $hestia_frontpage_featured ) . '\')"'; }
?>
>
		<?php
		if ( is_customize_preview() ) {
		?>
				<div class="hestia-about-image"></div>
				<?php
		}
		?>
			<div class="container">
				<div class="row hestia-about-content">
					<?php
					// Show the selected frontpage content
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'frontpage' );
					endwhile;
					else : // I'm not sure it's possible to have no posts when this page is shown, but WTH
						get_template_part( 'template-parts/content', 'none' );
					endif;
					?>
				</div>
			</div>
		</section>
		<?php

	}

endif;

if ( function_exists( 'hestia_about' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 15, 'hestia_about' );
	add_action( 'hestia_sections', 'hestia_about', absint( $section_priority ) );
}
