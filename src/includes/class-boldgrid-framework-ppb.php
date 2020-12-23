<?php
/**
 * The class responsible for adding filters to the Post and Page Builder.
 *
 * @since      2.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework/PPB
 * @link       https://boldgrid.com
 */

/**
 * The class responsible for adding filters to the Post and Page Builder.
 *
 * @since      2.0.0
 */
class BoldGrid_Framework_PPB {

	/**
	 * BGTFW Configs.
	 *
	 * @since 2.0.0
	 *
	 * @var array $configs.
	 */
	protected $configs;

	/**
	 * Apply BGTFW cofigs.
	 *
	 * @since 2.0.0
	 *
	 * @param array $configs Theme configs.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Override the theme fonts within the post and page builder to use the fonts we provide.
	 *
	 * @since 2.0.0
	 *
	 * @param array $editor_configs Theme fonts.
	 */
	public function set_theme_fonts( $editor_configs ) {
		$fonts = array();
		$typography = new Boldgrid_Framework_Customizer_Typography( $this->configs );
		foreach ( $typography->get_typography_settings() as $typography_setting ) {
			$fonts[ $typography_setting['class_name'] ] = isset( $typography_setting['value']['font-family'] ) ? $typography_setting['value']['font-family'] : $typography_setting['value']['default']['font-family'];
		}

		$editor_configs['builder_config']['theme_fonts'] = array_unique( $fonts );

		return $editor_configs;
	}
}
