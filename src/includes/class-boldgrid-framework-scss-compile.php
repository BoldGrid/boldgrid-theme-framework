<?php
/**
 * Class: Boldgrid_Framework_SCSS
 *
 * Functions for interfacing with ScssPhp\ScssPhp\Compiler
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_SCSS
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

use ScssPhp\ScssPhp\Compiler;

/**
 * Class: Boldgrid_Framework_Bootstrap_Compile
 *
 * Functions for interfacing with ScssPhp\ScssPhp\Compiler
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_Scss_Compile implements Boldgrid_Framework_Compile {

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
		$this->staging = new Boldgrid_Framework_Staging( $this->configs );
	}

	/**
	 * Build Bootstrap from SCSS.
	 *
	 * Calls to compile bootstrap, and then save it.
	 *
	 * @since 1.1
	 */
	public function build() {
		if ( true === $this->configs['components']['bootstrap']['enabled'] ) {
			$this->build_bootstrap();
		}
		if ( true === $this->configs['components']['buttons']['enabled'] ) {
			$this->build_bgtfw();
		}
	}

	/**
	 * Build Bootstrap from SCSS.
	 *
	 * Calls to compile bootstrap, and then save it.
	 *
	 * @since 1.1
	 */
	public function build_bootstrap() {
		$dir = $this->configs['framework']['asset_dir'];
		// BoldGrid specific color variables to have available during compile.
		$variables = $this->colors->get_scss_variables();
		// Bootstrap variables to assign before compile.
		$bootstrap_variables = $this->configs['components']['bootstrap']['variables'];
		if ( ! empty( $bootstrap_variables ) ) {
			// Merge the arrays.
			$variables = array_merge( $variables, $this->configs['components']['bootstrap']['variables'] );
		}

		$css = $this->compile( $dir . 'scss/', '@import "bootstrap";', $variables );
		$this->wpfs->save( $css, get_stylesheet_directory() . '/css/bootstrap/bootstrap.css' );
	}

	/**
	 * Build BGTFW from SCSS.
	 *
	 * Calls to compile bgtfw, and then save it.
	 *
	 * @since 1.1
	 */
	public function build_bgtfw() {
		$dir = $this->staging->get_template_dir();
		// BoldGrid specific variables to have available during compile.
		$variables = $this->colors->get_scss_variables();
		// Variables to assign before compile.
		$variables = array_merge( $variables, $this->configs['components']['buttons']['variables'] );
		// Check the variables passed in to make sure they aren't empty for compile.
		$empty = false;

		foreach ( $variables as $variable ) {
			if ( empty( $variable ) ) {
				$empty = true;
				break;
			}
		}

		$css = '';
		if ( false === $empty ) {
			// Compile.
			$css = $this->compile( $dir . 'scss/', '@import "buttons";', $variables );

			$config_settings = $this->configs['components']['buttons'];

			if ( $this->staging->is_updating_staging() ) {
				// Update the name of the css file.
				$basename = basename( $config_settings['css_file'], '.css' );
				$config_settings['css_file'] = str_ireplace(
					$basename,
					'staging-buttons',
					$config_settings['css_file']
				);
			}

			// Save.
			if ( ! Boldgrid_Framework_SCSS::is_draft() ) {
				$this->wpfs->save( $css, $config_settings['css_file'] );
			}
		}

		return $css;
	}

	/**
	 * Compile Bootstrap SCSS to CSS.
	 *
	 * @since 1.1
	 * @return string $compiled_scss Contains compiled SCSS code.
	 */
	public function compile( $path, $content, $variables ) {
		if ( ! class_exists( '\ScssPhp\ScssPhp\Compiler' ) ) {
			require_once $this->configs['framework']['includes_dir'] . '/scssphp/scss.inc.php';
		}
		$scss = new Compiler();
		$path = $this->configs['framework']['asset_dir'] . 'scss/';
		$scss->setImportPaths( $path );
		$scss->setVariables( $variables );
		$compiled_scss = $scss->compile( $content );
		return $compiled_scss;
	}
}
