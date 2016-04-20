<?php

/**
 * Functionality used within Customizer.
 *
 * @since 1.1
 * @link http://www.boldgrid.com.
 * @package Boldgrid_Inspiration.
 * @subpackage Boldgrid_Inspiration/includes.
 * @author BoldGrid <wpb@boldgrid.com>.
 */
class Boldgrid_Framework_Customizer_Edit {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     xxx
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     xxx
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Enqueue scripts needed to add edit buttons to the customizer.
	 *
	 * Ideally, this method would hook into customize_preview_init. We need to get the page ID,
	 * which is not avaialable in that hook. Instead, we hook into wp_enqueue_scripts and check to
	 * see if we're in the is_customize_preview.
	 *
	 * @since 1.1
	 */
	public function wp_enqueue_scripts() {
		if ( is_customize_preview() ) {
			// Minify if script debug is off.
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			$stylesheet = get_stylesheet();

			wp_register_script(
				'boldgrid-framework-customizer-edit-js',
				$this->configs['framework']['js_dir'] . 'customizer/edit' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version']
			);

			wp_localize_script(
				'boldgrid-framework-customizer-edit-js',
				'boldgridFrameworkCustomizerEdit',
				array(
					'editPostLink' => get_edit_post_link( get_the_ID() ),
					'goThereNow' => __( 'Go there now', 'bgtfw' )
				)
			);

			wp_enqueue_script( 'boldgrid-framework-customizer-edit-js' );

			wp_register_style(
				'boldgrid-theme-framework--customizer-edit-css',
				$this->configs['framework']['css_dir'] . 'customizer/edit' . $suffix . '.css',
				array (),
				$this->configs['version']
			);

			wp_enqueue_style( 'boldgrid-theme-framework--customizer-edit-css' );

			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-effects-bounce' );
		}
	}

	/**
	 */
	public function wp_footer() {
		if ( is_customize_preview() ) {
			include dirname( dirname( __FILE__ ) ) . '/partials/customizer-edit.php';
		}
	}
}