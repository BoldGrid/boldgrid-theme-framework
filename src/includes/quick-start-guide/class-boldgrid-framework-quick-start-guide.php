<?php
/**
 * Class: Boldgrid_Framework_Quick_Start_Guide.
 *
 * This class is used to add the new Quick Start Guide to the Customizer.
 *
 * @since      2.7.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Quick_Start_Guide
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Quick_Start_Guide.
 *
 * This class is used to add the new Quick Start Guide to the Customizer.
 *
 * @since      2.7.0
 */
class Boldgrid_Framework_Quick_Start_Guide {

	/**
	 * Configs.
	 *
	 * BGTFW Configs Array.
	 *
	 * @since 2.7.0
	 * @access public
	 * @var Array
	 */
	public $configs = array();

	/**
	 * Constructor.
	 *
	 * @since 2.7.0
	 *
	 * @param Array $configs BGTFW Configs Array.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Register Scripts.
	 *
	 * Registers any scripts needed for the Quick Start Guide.
	 *
	 * @since 2.7.0
	 */
	public function register_scripts() {
		wp_register_script(
			'crio-quick-start-guide',
			$this->configs['framework']['js_dir'] . '/customizer/quick-start.js',
			array( 'jquery', 'customize-preview' ),
			true,
			$this->configs['version']
		);
	}

	/**
	 * Localize Scripts.
	 *
	 * Adds necessary data to the customize scripts, such as the nonce needed for ajax calls.
	 *
	 * @since 2.7.0
	 */
	public function localize_scripts() {
		$data = array(
			'nonce'   => wp_create_nonce( 'crio_get_quick_start_markup' ),
			'iconUrl' => get_template_directory_uri() . '/images/crio_logo.svg',
		);

		wp_localize_script(
			'crio-quick-start-guide',
			'crioQuickStartParams',
			$data
		);
	}

	/**
	 * Enqueue Scripts.
	 *
	 * This is the method fired by the 'customizer_preview_init' hook.
	 *
	 * @since 2.7.0
	 */
	public function enqueue_scripts() {
		error_log( 'customizer_preview_init fired' );
		$this->register_scripts();
		$this->localize_scripts();
		wp_enqueue_script( 'crio-quick-start-guide' );
	}
}
