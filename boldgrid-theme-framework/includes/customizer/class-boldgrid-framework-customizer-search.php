<?php
/**
 * Class: BoldGrid_Framework_Customizer_Search
 *
 * This is used for the control search functionality in the WordPress customizer.
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * BoldGrid_Framework_Customizer_Search
 *
 * Responsible for the search functionality in the WordPress customizer.
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Customizer_Search {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     2.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Enqueue scripts in customizer.
	 *
	 * @since 2.0.0
	 */
	public function enqueue() {

		// Minify if script debug is off.
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'bgtfw-customizer-search-js',
			$this->configs['framework']['js_dir'] . 'customizer/search' . $suffix . '.js',
			array(),
			$this->configs['version'],
			true
		);
	}

	/**
	 * Print template for the "Customizer Search" functionality.
	 *
	 * @since 2.0.0
	 */
	public function print_templates() {
		?>
		<script type="text/html" id="tmpl-search-button">
			<button type="button" class="customize-search-toggle dashicons dashicons-search" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Search', 'bgtfw' ); ?></span></button>
		</script>
		<script type="text/html" id="tmpl-search-form">
			<div id="accordion-section-customizer-search" style="display: none;">
				<h4 class="customizer-search-section accordion-section-title">
					<span class="search-field-wrapper">
						<input type="text" placeholder="<?php _e( 'Search Controls...', 'bgtfw' ); ?>" name="customizer-search-input" autofocus="autofocus" id="customizer-search-input" class="customizer-search-input">
						<button type="button" class="button clear-search" tabindex="0"><?php _e( 'Clear', 'bgtfw' ); ?></button>
					</span>
				</h4>
			</div>
		</script>
		<?php
	}
}
