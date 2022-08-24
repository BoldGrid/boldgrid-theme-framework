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
 * Class: Boldgrid_Framework_SCSS
 *
 * Functions for interfacing with ScssPhp\ScssPhp\Compiler
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_SCSS {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Value of the css after it becomes compiled.
	 *
	 * @since 1.5.3
	 *
	 * @var string CSS value.
	 */
	public $compiled_content;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
		$this->wpfs    = new Boldgrid_Framework_Wp_Fs();
		$this->colors  = new Boldgrid_Framework_Compile_Colors( $this->configs );
		$this->staging = new Boldgrid_Framework_Staging( $this->configs );
		$this->buttons = new Boldgrid_Framework_Scss_Compile( $this->configs );
	}

	/**
	 * Look in the directory defined by the configs which holds the css files and return array
	 *
	 * @since     1.0.0
	 * @return    array    $files
	 */
	public function find_scss_files() {
		$files = array();

		$template_directory = $this->staging->get_template_dir();

		$config_settings = $this->configs['customizer-options']['colors']['settings'];
		if ( ! empty( $config_settings['scss_directory'] ) ) {
			foreach ( $config_settings['scss_directory'] as $directory ) {
				$scss_full_path = $template_directory . $directory;

				// If dir path not relative or doesnt exist, check abspath.
				if ( ! file_exists( $scss_full_path ) ) {
					$scss_full_path = $directory;
				}

				if ( file_exists( $scss_full_path ) ) {

					$dir_files = scandir( $scss_full_path );
					$dir_files = array_diff( $dir_files, array( '..', '.' ) );

					foreach ( $dir_files as $key => $file ) {

						if ( 'scss' === pathinfo( $file, PATHINFO_EXTENSION ) ) {
							$files[] = $scss_full_path . DIRECTORY_SEPARATOR . $file;
						}
					}
				}
			}
		}

		// Remove files from the directory that you don't want compiled.
		$files = apply_filters( 'boldgrid_theme_helper_scss_files', $files );

		return $files;
	}

	/**
	 * Get the SCSS files content that need to be compiled
	 *
	 * @since     1.0.0
	 * @return    string    $pre_compile_content
	 */
	public function get_precompile_content() {
		// Initialize WP_Filesystem.
		$this->wpfs->init();
		global $wp_filesystem;

		$scss_files = $this->find_scss_files();
		$pre_compile_content = '';
		foreach ( $scss_files as $file ) {
			$pre_compile_content .= $wp_filesystem->get_contents( $file );
		}

		return $pre_compile_content;
	}

	/**
	 * Find last modified time
	 *
	 * @since 1.0.0
	 * @param array $files Files to find last modified out of.
	 * @return integer $last_modified_time The last modified UNIX timestamp.
	 */
	public function find_last_modified_time( $files ) {
		$last_modified_time = 0;

		foreach ( $files as $file ) {
			if ( file_exists( $file ) ) {
				$file_mod_time = filemtime( $file );
				if ( $file_mod_time > $last_modified_time ) {
					$last_modified_time = $file_mod_time;
				}
			}
		}

		return $last_modified_time;
	}

	/**
	 * Find the last time that the output css file was modified
	 *
	 * @since      1.0.0
	 * @return integer $last_modified_time 	The last modified UNIX timestamp
	 */
	public function find_last_compile_time() {
		$last_compile_time = 0;
		$config_settings = $this->configs['customizer-options']['colors']['settings'];
		if ( ! empty( $config_settings['output_css_name'] ) && file_exists( $config_settings['output_css_name'] ) ) {
			// Using general function for consistency use of filemtime.
			$last_compile_time = $this->find_last_modified_time( array( $config_settings['output_css_name'] ) );
		}

		return $last_compile_time;
	}

	/**
	 * Grab all of the SCSS file contents.
	 *
	 * @since 1.0.0
	 * @param array $files SCSS files to combine.
	 * @return string $precompile_string  Contents of all specified scss files.
	 */
	public function get_scss_file_contents( $files ) {
		$this->wpfs->init();
		global $wp_filesystem;

		$precompile_string = '';
		foreach ( $files as $file ) {
			$precompile_string .= $wp_filesystem->get_contents( $file );
		}

		return $precompile_string;
	}

	/**
	 * Compile the SCSS files using the ScssPhp compiler.
	 *
	 * @since      1.0.0
	 * @param string $content Content to compile.
	 * @return     string    $compiled   The compiled SCSS file.
	 */
	public function compile( $content, $path = null, $variables = null ) {
		if ( ! class_exists( '\ScssPhp\ScssPhp\Compiler' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/scssphp/scss.inc.php';
		}

		$scss = new Compiler();
		$config_settings = $this->configs['customizer-options']['colors']['settings'];

		if ( $config_settings['minify_output'] ) {
			$scss->setFormatter( 'ScssPhp\ScssPhp\Formatter\Compressed' );
		}

		$variables = $variables ? $variables : $this->colors->get_scss_variables();

		// Check the variables passed in to make sure they aren't empty for compile.
		$empty = false;

		foreach ( $variables as $variable ) {
			if ( empty( $variable ) ) {
				$empty = true;
				break;
			}
		}

		if ( $path ) {
			$scss->setImportPaths( $path );
		}

		if ( false === $empty ) {
			$scss->setVariables( $variables );

			// TODO: Make sure we arent over compiling.
			try {
				// BoldGrid specific variables to have available during compile.
				$compiled = $scss->compile( $content );
			} catch ( \Exception $e ) {
				error_log( 'Failed SCSS Compile: ' . $e->getMessage() );
			}
		}

		return ! empty( $compiled ) ? $compiled : null;
	}

	/**
	 * Save the compiled SCSS file to the themes .css output directory specified in the configs.
	 *
	 * @since  1.0.0
	 * @param  string $compiled Boolean.
	 * @return string $success  boolean
	 */
	public function save_compiled_content( $compiled ) {
		$success = false;
		if ( $compiled ) {
			$config_settings = $this->configs['customizer-options']['colors']['settings'];

			if ( $this->staging->is_updating_staging() ) {
				// Update the name of the css file.
				$basename = basename( $config_settings['output_css_name'], '.css' );
				$config_settings['output_css_name'] = str_ireplace(
					$basename,
					'boldgrid-staging-colors',
					$config_settings['output_css_name']
				);
			}

			$this->wpfs->save( $compiled, $config_settings['output_css_name'] );

			$success = true;
		}

		return $success;
	}

	/**
	 * Build BGTFW from SCSS.
	 *
	 * Calls to compile bgtfw, and then save it.
	 *
	 * @since 1.1
	 */
	public function compile_widths() {
		$container_width = new Boldgrid_Framework_Container_Width( $this->configs );
		$variables       = $container_width->get_scss_variables();
		$dir             = $this->configs['framework']['asset_dir'];

		$css = '';

		// Compile.
		$css = $this->compile( '@import "container-widths";', $dir . 'scss/', $variables );

		$config_settings              = $this->configs['components']['container-widths'];
		$config_settings['variables'] = $variables;

		return $css;
	}

	/**
	 * Check if we should perform a force compile.
	 *
	 * In the case the theme does not have a color palettes file, create one.
	 * Only happens at most once every hour to prevent a failure in creating the file from
	 * causing this process to always run.
	 *
	 * @since 1.4
	 *
	 * @return array Staging and Active Compile.
	 */
	public function get_force_compile() {
		$force_recompile = get_theme_mod( 'force_scss_recompile', array() );
		$css_file = $this->configs['customizer-options']['colors']['settings']['output_css_name'];
		$fail_safe_compile = get_theme_mod( 'fail_safe_compile' );
		$site_mode = $this->staging->get_site_mode();
		$already_compiling = ! empty( $force_recompile[ $site_mode ] );

		// 86400 == 1 Hour in seconds.
		$fail_safe_expired = ! $fail_safe_compile || ( time() > $fail_safe_compile + 3600 );
		if ( ! file_exists( $css_file ) && $fail_safe_expired && false === $already_compiling ) {
			set_theme_mod( 'fail_safe_compile', time() );
			$force_recompile[ $site_mode ] = true;
		}

		return $force_recompile;
	}

	/**
	 * Recompile sass palettes if needed.
	 *
	 * @since      1.0.3
	 */
	public function force_recompile_checker() {
		$recompile = false;
		$force_recompile = $this->get_force_compile();

		// If asked to recompile staging.
		if ( ! empty( $force_recompile['staging'] ) ) {

			// If customizer is loading staging.
			if ( ! empty( $this->configs['customizer-options']['colors']['settings']['staging'] ) ) {

				// Unset flag.
				$force_recompile['staging'] = false;

				$recompile = true;
			}
		}

		// If asked to recompile active.
		if ( ! empty( $force_recompile['active'] ) ) {

			// Unset flag.
			$force_recompile['active'] = false;

			$recompile = true;

		}

		// Recompile SCSS.
		if ( $recompile ) {

			set_theme_mod( 'force_scss_recompile', $force_recompile );
			$this->force_update_css();

		}
	}

	/**
	 * On theme update set the flags to recompile.
	 *
	 * This code runs from the old theme not the new.
	 *
	 * @since     1.0.0
	 * @param WP_Upgrader $upgrader WP_Upgrader.
	 * @param array       $data     array.
	 * @param WP_Theme    $theme    WP_Theme.
	 */
	public function theme_upgrader_process( $upgrader = null, $data = array(), $theme = null ) {
		$staging_stylesheet = get_option( 'boldgrid_staging_stylesheet' );
		$action 			= ! empty( $data['action'] ) ? $data['action'] : null;
		$type 				= ! empty( $data['type'] ) ? $data['type'] : null;
		$themes 			= ! empty( $data['themes'] ) ? $data['themes'] : array();
		$theme 				= ! empty( $data['theme'] ) ? $data['theme'] : false;

		$updating_current_theme = ( in_array( get_stylesheet(), $themes, true )
			|| in_array( $staging_stylesheet, $themes, true )
			|| get_stylesheet() === $themes
			|| get_option( 'boldgrid_staging_stylesheet' ) === $theme
		);

		// When updating the current theme.
		if ( ( 'theme' === $type ) && ( 'update' === $action ) && $updating_current_theme ) {
			$this->staging->set_recompile_flags();
		}
	}

	/**
	 * Update css from scss files
	 *
	 * @since      1.0.0
	 */
	public function force_update_css() {
		return $this->update_css( true );
	}

	/**
	 * Defer an update until a later time.
	 *
	 * @since      1.1.7
	 *
	 * @return boolean Whether or not the update was deferred.
	 */
	public function maybe_defer_update() {
		$is_update_deferred = false;

		$currently_updating_staging = $this->staging->is_updating_staging();
		$staging = get_option( 'boldgrid_staging_stylesheet', '' );
		$staging_registered = get_stylesheet() == $staging;

		// If the current stylesheet is not the staging stylesheet, but your updating staging.
		// Then postpone the update until later because the staging theme needs its hooks to run.
		if ( $currently_updating_staging && false === $staging_registered ) {
			$theme_mod_option = 'boldgrid_staging_theme_mods_' . $staging;
			$options = get_option( $theme_mod_option );
			$options['force_scss_recompile']['staging'] = true;
			update_option( $theme_mod_option, $options );
			$is_update_deferred = true;
		}

		return $is_update_deferred;
	}

	/**
	 * Is this a draft compile?
	 *
	 * @since 1.5.1
	 *
	 * @return boolean Is this a draft compile?
	 */
	public static function is_draft() {
		global $boldgrid_theme_framework;

		return ! empty( $boldgrid_theme_framework->changset_customization ) ||
			( ! empty( $_POST['customize_changeset_status'] ) && 'draft' === $_POST['customize_changeset_status'] ); // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $force_update Force the update of css or not.
	 * @return string     @success     boolean
	 */
	public function update_css( $force_update = false ) {
		$files              = $this->find_scss_files();
		$last_modified_time = $this->find_last_modified_time( $files );
		$last_compile_time  = $this->find_last_compile_time();
		$is_update_deferred = $this->maybe_defer_update();
		$is_expired_file = ( $last_modified_time && $last_modified_time ) && $last_modified_time > $last_compile_time;

		$success = false;
		if ( ( $force_update || $is_expired_file ) && ! $is_update_deferred ) {
			$file_contents = $this->get_scss_file_contents( $files );
			$this->compiled_content = $this->compile( $file_contents );

			if ( ! self::is_draft() ) {
				$success = $this->save_compiled_content( $this->compiled_content );
				set_theme_mod( 'boldgrid_compiled_css', $this->compiled_content );
			}

			$button_css = $this->buttons->build_bgtfw();

			$this->compiled_content = $button_css . $this->compiled_content;
		}

		return $success;
	}
}
