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

use Leafo\ScssPhp\Compiler;

/**
 * Class: Boldgrid_Framework_Bootstrap_Compile
 *
 * Functions for interfacing with Leafo\ScssPhp\Compiler
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_Bgtfw_Compile implements Boldgrid_Framework_Compile {

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
		$this->colors  = new Boldgrid_Framework_Compile_Colors( $this->configs );
		$this->wpfs    = new Boldgrid_Framework_Wp_Fs();
	}

	/**
	 * Build Bootstrap from SCSS.
	 *
	 * Calls to compile bootstrap, and then save it.
	 *
	 * @since 1.1
	 */
	public function build() {
		$css = $this->compile();
		$this->wpfs->save( $css, $this->configs['framework']['asset_dir'] . 'css/bgtfw/bgtfw.min.css' );
	}

	/**
	 * Compile Bootstrap SCSS to CSS.
	 *
	 * @since 1.1
	 * @return string $compiled_scss Contains compiled SCSS code.
	 */
	public function compile() {
		if ( ! class_exists( '\Leafo\ScssPhp\Compiler' ) ) {
			require_once $this->configs['framework']['includes_dir'] . '/scssphp/scss.inc.php';
		}
		$scss = new Compiler();
		$path = $this->configs['framework']['asset_dir'] . 'scss/';
		$scss->setImportPaths( $path );

		if ( $this->configs['bootstrap'] ) {
			// BoldGrid specific variables to have available during compile.
			$color_variables = array_merge( $this->colors->get_active_palette(), $this->colors->get_text_contrast() );
			// Variables to assign before compile.
			$variables = array_merge( $color_variables, $this->configs['components']['buttons']['variables'] );
			// Set the Variables.
			$scss->setVariables( $variables );
		}

		$compiled_scss = $scss->compile( '@import "buttons/buttons";' );

		return $compiled_scss;
	}
}
