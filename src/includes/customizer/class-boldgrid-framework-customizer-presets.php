<?php
/**
 * Customizer Presets Functionality.
 *
 * @link http://www.boldgrid.com
 *
 * @since SINCEVERSION
 *
 * @package Boldgrid_Theme_Framework_Customizer
 */


/**
 * Class: Boldgrid_Framework_Customizer_Presets.
 *
 * Stores and retrieves header layout presets.
 *
 * @since      SINCEVERSION
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */
class Boldgrid_Framework_Customizer_Presets {

	/**
	 * BGTFW Configs.
	 *
	 * @var array
	 */
	public $configs = array();

	/**
	 * Current Header Layout.
	 *
	 * @var array
	 */
	public $current_header_layout = array();

	/**
	 * Class Constructor.
	 *
	 * @since SINCEVERSION
	 *
	 * @param array $configs BGTFW Configs Array.
	 */
	public function __construct( $configs ) {
		error_log( json_encode( array_keys( $configs ) ) );
		$this->configs               = $configs;
		$this->current_header_layout = get_theme_mod( 'bgtfw_header_layout', array() );
	}

	/**
	 * Get Preset Choices.
	 *
	 * @since SINCEVERSION
	 *
	 * @param string $preset_type Type of preset ( header, footer, etc ).
	 */
	public function get_preset_choices( $preset_type ) {
		if ( ! array_key_exists( $preset_type, $this->configs['customizer-options']['presets'] ) ) {
			return array();
		}

		$presets     = array();
		$preset_keys = array_keys( $this->configs['customizer-options']['presets'][ $preset_type ] );

		foreach ( $preset_keys as $key ) {
			$presets[ $key ] = get_template_directory_uri() . '/inc/boldgrid-theme-framework/assets/img/presets/' . $key . '.png';
		}

		return $presets;
	}
}
