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
 * Class: Boldgrid_Framework_SCSS
 *
 * Functions for interfacing with Leafo\ScssPhp\Compiler
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
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Check to see if we are currently updating staging theme mods
	 *
	 * @since     1.0.0
	 * @return    string     $is_currently_updating_staging_mods     boolean
	 */
	public function is_currently_updating_staging_mods() {

		$is_currently_updating_staging_mods = false;
		if ( strpos( current_filter(), 'update_option_boldgrid_staging_theme_mods' ) !== false ||
				strpos( current_filter(), 'add_option_boldgrid_staging_theme_mods' ) !== false ) {

				$is_currently_updating_staging_mods = true;

		}

		return $is_currently_updating_staging_mods;
	}

	/**
	 * Get the template directory conditionally based on if we are currently updating staging theme mods
	 *
	 * @since     1.0.0
	 * @return    string    $template_directory    path of theme's template directory
	 */
	public function get_template_dir() {
		if ( $this->is_currently_updating_staging_mods() ) {

			$theme_root = get_theme_root( get_option( 'boldgrid_staging_template' ) );
			$template_directory = "$theme_root/" . get_option( 'boldgrid_staging_template' );
		} else {
			$template_directory = $this->configs['framework']['config_directory']['template'];
		}

		return $template_directory;
	}

	/**
	 * Look in the directory defined by the configs which holds the css files and return array
	 *
	 * @since     1.0.0
	 * @return    array    $files
	 */
	public function find_scss_files() {
		$files = array();

		$template_directory = $this->get_template_dir();

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
	 * Initialize the WP_Filesystem
	 *
	 * @since 1.1
	 */
	public function init_filesystem() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
	}

	/**
	 * Get the SCSS files content that need to be compiled
	 *
	 * @since     1.0.0
	 * @return    string    $pre_compile_content
	 */
	public function get_precompile_content() {
		// Initialize WP_Filesystem.
		$this->init_filesystem();
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
		$this->init_filesystem();
		global $wp_filesystem;

		$precompile_string = '';
		foreach ( $files as $file ) {
			$precompile_string .= $wp_filesystem->get_contents( $file );
		}

		return $precompile_string;
	}

	/**
	 * Get any additional variables from the configs that are needed.
	 *
	 * @since    1.0.0
	 * @return   string     $sass_file     SCSS file containing configuration data.
	 */
	public function get_additional_variables() {
		if ( false === empty( $this->configs['forced_color_palette_decoded'] ) ) {
			$palette_configs = $this->configs['forced_color_palette_decoded'];
		} else {
			$palette_configs = Boldgrid_Framework_Customizer_Colors::get_palette_configs();
		}

		$sass_file = '';
		if ( ! empty( $palette_configs ) ) {
			// Take a set of configurations and turn theme into scss.
			$theme_palettes = $palette_configs['state']['palettes'];
		} else {
			$palettes = $this->configs['customizer-options']['colors']['defaults'];
			$theme_palettes = Boldgrid_Framework_Customizer_Colors::get_simplified_external_palettes( $palettes );
		}

		$sass_file = $this->convert_palette_configs_to_scss( $theme_palettes );

		return $sass_file;
	}

	/**
	 * Convert an array of configurations into a string of SCSS
	 *
	 * @since  1.0.0
	 * @param  array $palette_configs Array containing color palette configs.
	 * @return string $sass_file       Contains preset configs as scss rules to compile.
	 */
	public function convert_palette_configs_to_scss( $palette_configs ) {
		$sass_file = '';

		// Create A sass file.
		$class_colors_prefix = '$colors: ';
		foreach ( $palette_configs as $palette_config ) {
			if ( ! empty( $palette_config['colors'] ) ) {

				$class_colors = $class_colors_prefix;
				foreach ( $palette_config['colors'] as $color_order => $color ) {
					$actual_order = $color_order + 1;
					$sass_file .= '$' . $palette_config['format'] . "_{$actual_order}:" . $color . ';';
					// Add text contrast variable for each color.
					$class_colors .= '$' . $palette_config['format']  . "_{$actual_order} ";
				}

				// Add Class Colors.
				if ( $class_colors !== $class_colors_prefix ) {
					$sass_file .= $class_colors . ';';
				}
			}

			if ( ! empty( $palette_config['neutral-color'] ) ) {
				$sass_file .= '$' . $palette_config['format'] . '-neutral-color:' . $palette_config['neutral-color'] . ';';
			}

			// Dark text and light text variables.
			$sass_file .= '$light_text:' . $this->configs['customizer-options']['colors']['light_text'] . ';';
			$sass_file .= '$dark_text:' . $this->configs['customizer-options']['colors']['dark_text'] . ';';
		}

		return $sass_file;

	}

	/**
	 * Compile the SCSS files using the Leafo compiler.
	 *
	 * @since      1.0.0
	 * @param string $content Content to compile.
	 * @return     string    $compiled   The compiled SCSS file.
	 */
	public function compile( $content ) {
		if ( ! class_exists( '\Leafo\ScssPhp\Compiler' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/scssphp/scss.inc.php';
		}

		$scss = new Compiler();
		$config_settings = $this->configs['customizer-options']['colors']['settings'];

		if ( $config_settings['minify_output'] ) {
			$scss->setFormatter( 'Leafo\ScssPhp\Formatter\Compressed' );
		}

		// TODO: Make sure we arent over compiling.
		try {
			$compiled = $scss->compile( $content );
		} catch ( \Exception $e ) {
			error_log( 'Failed SCSS Compile: ' . $e->getMessage() );
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
		$this->init_filesystem();
		global $wp_filesystem;

		$success = false;
		if ( $compiled ) {
			$config_settings = $this->configs['customizer-options']['colors']['settings'];

			if ( $this->is_currently_updating_staging_mods() ) {
				// Update the name of the css file.
				$basename = basename( $config_settings['output_css_name'], '.css' );
				$config_settings['output_css_name'] = str_ireplace( $basename, 'boldgrid-staging-colors', $config_settings['output_css_name'] );
			}


			// Update CSS file.
			$wp_filesystem->put_contents(
				$config_settings['output_css_name'],
				$compiled,
				FS_CHMOD_FILE
			);

			$success = true;
		}

		return $success;
	}

	/**
	 * Recompile sass palettes if needed.
	 *
	 * @since      1.0.3
	 */
	public function force_recompile_checker() {
		$recompile = false;
		$force_recompile = get_theme_mod( 'force_scss_recompile', array() );

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
	 * On theme update recompile scss files
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

			// Update the standard css file.
			$this->force_update_css();

			// Force Recompile On Staging.
			$staging_theme_mods = get_option( 'boldgrid_staging_theme_mods_' . $staging_stylesheet );

			// If color palette is set for staging theme, delete the theme mods and then save them again.
			// This is a hack to force hooks to run and recompile the sass files.
			if ( ! empty( $staging_theme_mods['boldgrid_color_palette'] ) ) {

				update_option( 'boldgrid_staging_theme_mods_' . $staging_stylesheet, array() );
				update_option( 'boldgrid_staging_theme_mods_' . $staging_stylesheet, $staging_theme_mods );

			}
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
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $force_update Force the update of css or not.
	 * @return string     @success     boolean
	 */
	public function update_css( $force_update = false ) {
		$files 				= $this->find_scss_files();
		$last_modified_time = $this->find_last_modified_time( $files );
		$last_compile_time 	= $this->find_last_compile_time();

		$success = false;
		if ( $force_update || ( ( $last_modified_time && $last_modified_time ) && $last_modified_time > $last_compile_time ) ) {
			$file_contents 		= $this->get_scss_file_contents( $files );
			$merged_contents 	= $this->get_additional_variables( ) . $file_contents;
			$compiled_content 	= $this->compile( $merged_contents );
			$success 			= $this->save_compiled_content( $compiled_content );

			set_theme_mod( 'boldgrid_compiled_css', $compiled_content );
		}

		return $success;
	}
}
