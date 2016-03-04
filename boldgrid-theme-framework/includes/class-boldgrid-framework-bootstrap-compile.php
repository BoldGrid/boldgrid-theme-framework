<?php
/**
 * Class: Boldgrid_Framework_SCSS
 *
 * Functions for interfacing with Leafo\ScssPhp\Compiler
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_SCSS
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Bootstrap_Compile
 *
 * Functions for interfacing with Leafo\ScssPhp\Compiler
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_Bootstrap_Compile {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Build Bootstrap from SCSS.
	 *
	 * Calls to compile bootstrap, and then save it.
	 *
	 * @since 1.1
	 */
	public function bootstrap_build() {
		$css = $this->compile_bootstrap( );
		$this->save_compiled_scss( $css );
	}

	/**
	 * Initialize the WP_Filesystem.
	 *
	 * @since 1.1
	 * @global $wp_filesystem WordPress Filesystem global.
	 */
	public function init_filesystem() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
	}

	/**
	 * Get Active Palette Colors.
	 *
	 * @since 1.1
	 * @return array $boldgrid_colors Array containing SCSS variable name.
	 */
	public function get_active_palette() {
		$boldgrid_colors = array();
		$palettes = json_decode( get_theme_mod( 'boldgrid_color_palette' ), true );

		if ( null !== $palettes ) {
			$current_palette = $palettes['state']['active-palette'];
			$colors = $palettes['state']['palettes'][$current_palette]['colors'];
			$i = 0;

			foreach ( $colors as $color ) {
				$i++;
				$boldgrid_colors[$current_palette.'_'.$i] = $color;
			}
		}

		return $boldgrid_colors;
	}

	/**
	 * Compile Bootstrap SCSS to CSS.
	 *
	 * @since 1.1
	 * @return string $compiled_scss Contains compiled SCSS code.
	 */
	public function compile_bootstrap() {
		if ( ! class_exists( '\Leafo\ScssPhp\Compiler' ) ) {
			require_once $this->configs['framework']['includes_dir'] . '/scssphp/scss.inc.php';
		}
		$scss = new scssc();
		$path = $this->configs['framework']['asset_dir'] . 'scss/';
		$scss->setImportPaths( $path );

		if ( $this->configs['bootstrap'] ) {
			$variables = array_merge( $this->get_active_palette(), $this->configs['bootstrap'] );
			$scss->setVariables( $variables );
		}

		$compiled_scss = $scss->compile( '@import "bootstrap";' );

		return $compiled_scss;
	}

	/**
	 * Save Compiled SCSS.
	 *
	 * @since 1.1
	 * @param string $compiled_scss Contains the compiled Bootstrap SCSS to save.
	 */
	public function save_compiled_scss( $compiled_scss ) {
		global $wp_filesystem;
		$this->init_filesystem();
		// Write output to Bootstrap CSS file.
		$file = $this->configs['framework']['asset_dir'] . 'css/bootstrap/bootstrap.min.css';
		$wp_filesystem->put_contents( $file, $compiled_scss, FS_CHMOD_FILE );
	}
}
