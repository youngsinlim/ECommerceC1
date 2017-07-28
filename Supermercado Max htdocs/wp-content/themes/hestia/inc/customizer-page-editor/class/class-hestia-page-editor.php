<?php
/**
 * Page editor control
 *
 * @package Hestia
 * @since Hestia 1.1.3
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Class to create a custom tags control
 */
class Hestia_Page_Editor extends WP_Customize_Control {

	/**
	 * Flag to include sync scripts if needed
	 *
	 * @var bool|mixed
	 */
	private $needsync = false;

	/**
	 * Flag to do action admin_print_footer_scripts.
	 * This needs to be true only for one instance.
	 *
	 * @var bool|mixed
	 */
	private $include_admin_print_footer = false;

	/**
	 * Flag to load teeny.
	 *
	 * @var bool|mixed
	 */
	private $teeny = false;

	/**
	 * Hestia_Page_Editor constructor.
	 *
	 * @param WP_Customize_Manager $manager Manager.
	 * @param string               $id Id.
	 * @param array                $args Constructor args.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		if ( ! empty( $args['needsync'] ) ) {
			$this->needsync = $args['needsync'];
		}

		if ( ! empty( $args['include_admin_print_footer'] ) ) {
			$this->include_admin_print_footer = $args['include_admin_print_footer'];
		}

		if ( ! empty( $args['teeny'] ) ) {
			$this->teeny = $args['teeny'];
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * @since   1.1.0
	 * @access  public
	 * @updated Changed wp_enqueue_scripts order and dependencies.
	 */
	public function enqueue() {
		wp_enqueue_style( 'hestia_text_editor_css', get_template_directory_uri() . '/inc/customizer-page-editor/css/hestia-page-editor.css', array(), HESTIA_VERSION );
		wp_enqueue_script(
			'hestia_text_editor', get_template_directory_uri() . '/inc/customizer-page-editor/js/hestia-text-editor.js', array(
				'jquery',
				'hestia_controls_script',
			), HESTIA_VERSION, true
		);
		if ( $this->needsync === true ) {
			wp_enqueue_script( 'hestia_controls_script', get_template_directory_uri() . '/inc/customizer-page-editor/js/hestia-update-controls.js', array( 'jquery' ), HESTIA_VERSION, true );
			wp_localize_script(
				'hestia_controls_script', 'requestpost', array(
					'ajaxurl'           => admin_url( 'admin-ajax.php' ),
					'thumbnail_control' => 'hestia_feature_thumbnail', // name of image control that needs sync
				'editor_control'    => 'hestia_page_editor', // name of control (theme_mod) that needs sync
				'thumbnail_label'   => esc_html__( 'About background', 'hestia' ), // name of thumbnail control
				)
			);
		}
	}

	/**
	 * Render the content on the theme customizer page
	 */
	public function render_content() {
		?>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_textarea( $this->value() ); ?>">
		<?php
		$settings        = array(
			'textarea_name' => $this->id,
			'teeny'         => $this->teeny,
		);
		$control_content = $this->value();
		$frontpage_id    = get_option( 'page_on_front' );
		$page_content    = '';
		if ( $this->needsync === true ) {
			if ( ! empty( $frontpage_id ) ) {
				$page_content = get_post_field( 'post_content', $frontpage_id );
			}
		} else {
			$page_content = $this->value();
		}

		if ( $control_content !== $page_content ) {
			$control_content = $page_content;
		}

		wp_editor( $control_content, $this->id, $settings );

		if ( $this->include_admin_print_footer === true ) {
			do_action( 'admin_print_footer_scripts' );
		}
	}
}
