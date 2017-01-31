<?php
/**
 * Class: BoldGrid_Framework_Staging
 *
 * Functionality needed in order to integrate with the staging plugin
 *
 * @since      1.0.3
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Staging
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Staging
 *
 * Functionality needed in order to integrate with the staging plugin
 *
 * @since      1.0.3
 */
class BoldGrid_Framework_Staging {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.3
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.3
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * After the user lauches staging, theme switch is not fired
	 * Instead the themes involved in the launch staging process have the launch staging
	 * flag added as of staging v1.0.2. On the themes next load run this process if launch staging is true
	 */
	public function launch_staging_process() {
		$launched_staging = get_theme_mod( 'launched_staging' );

		// Requires v 1.0.2 of staging plugin.
		if ( true === $launched_staging ) {
			set_theme_mod( 'launched_staging', false );
			$this->set_recompile_flags();
		}
	}

	/**
	 * On the launch of staging, set the recompile flags to true
	 */
	public function set_recompile_flags() {
		$force_recompile = array(
			'active' => true,
			'staging' => true,
		);

		set_theme_mod( 'force_scss_recompile', $force_recompile );
	}

	/**
	 * Get the current mode for loading this theme, staging or active.
	 *
	 * @since 1.4.0
	 *
	 * @return string Mode of page load, staging or active
	 */
	public function get_site_mode() {
		$css_file = $this->configs['customizer-options']['colors']['settings']['output_css_name'];
		return false !== strpos( basename( $css_file ), 'staging' ) ? 'staging' : 'active';
	}

	/**
	 * Check to see if we are currently updating staging theme mods
	 *
	 * @since     1.0.0
	 * @return    string     $is_updating_staging     boolean
	 */
	public function is_updating_staging() {
		$is_updating_staging = false;
		if ( strpos( current_filter(), 'update_option_boldgrid_staging_theme_mods' ) !== false ||
				strpos( current_filter(), 'add_option_boldgrid_staging_theme_mods' ) !== false ) {
					$is_updating_staging = true;
		}

		return $is_updating_staging;
	}

	/**
	 * Get the template directory conditionally based on if we are currently updating staging theme mods
	 *
	 * @since     1.0.0
	 * @return    string    $template_directory    path of theme's template directory
	 */
	public function get_template_dir() {
		if ( $this->is_updating_staging() ) {

			$theme_root = get_theme_root( get_option( 'boldgrid_staging_template' ) );
			$template_directory = "$theme_root/" . get_option( 'boldgrid_staging_template' );
		} else {
			$template_directory = $this->configs['framework']['config_directory']['template'];
		}

		return $template_directory;
	}
}
