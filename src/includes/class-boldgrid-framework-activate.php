<?php
/**
 * Class: Boldgrid_Framework_Activate
 *
 * This class contians code that will run on activation
 * of a theme that utilizes the BoldGrid Theme Framework.
 *
 * @since 1.0.0
 * @package Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Activate
 * @author BoldGrid <support@boldgrid.com>
 * @link https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Activate
 *
 * This class contians code that will run on activation
 * of a theme that utilizes the BoldGrid Theme Framework.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Activate {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $configs The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $configs The BoldGrid Theme Framework configurations.
	 * @since 1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
		$this->scss    = new Boldgrid_Framework_SCSS( $this->configs );
		$this->color   = new Boldgrid_Framework_Customizer_Colors( $this->configs );
	}

	/**
	 * Activate Boldgrid theme framework
	 *
	 * @since 1.0.0
	 */
	public function do_activate() {
		$option = 'theme_mods_' . get_stylesheet();
		$this->set_palette( $option );
	}

	/**
	 * Check & set the default palette theme mod and compile the css
	 * for a user that has not selected a new palette and uses
	 * the theme's defined default palette.
	 *
	 * @since 1.1.7
	 */
	public function set_palette( $option ) {
		$options = get_option( $option, array() );

		// Check that colors are enabled and defaults exist.
		$enabled = $this->configs['customizer-options']['colors']['enabled'];
		$palette = $this->configs['customizer-options']['colors']['defaults'];

		// If there's not a palette set by user, then set it.
		if ( ! array_key_exists( 'boldgrid_color_palette', $options ) && $enabled && $palette ) {

			// Normalize default passed in palettes from configs to RGB.
			foreach ( $palette as $index => $settings ) {

				// Convert default colors to RGBs if alternate format was passed in configs.
				foreach ( $palette[ $index ]['colors'] as $color_index => $color ) {
					$palette[ $index ]['colors'][ $color_index ] = ariColor::newColor( $color )->toCSS( 'rgb' );
				}

				// Convert neutral color to RGB if alternate format was passed in configs.
				if ( isset( $palette[ $index ]['neutral-color'] ) ) {
					$palette[ $index ]['neutral-color'] = ariColor::newColor( $palette[ $index ]['neutral-color'] )->toCSS( 'rgb' );
				}
			}

			// Initizalize $theme_mod array.
			$theme_mod = array();
			// Get assigned default palette for category/theme.
			$default_palette = $this->color->get_simplified_external_palettes( $palette );
			// Reset to access without specifying palette format as it can change.
			$active_palette = reset( $default_palette );
			// Find acitve palette format.
			$format = $active_palette['format'];
			// Set the theme mod array values.
			$theme_mod['state'] = array(
				'active-palette' => $format,
				'active-palette-id' => $this->color->create_palette_id( $active_palette ),
				'palettes' => $default_palette,
				'saved_palettes' => array(),
			);
			// This is not needed for theme mod.
			unset( $theme_mod['state']['palettes'][ $format ]['default'] );
			// Encode to pass to JS.
			$encoded_theme_mod = wp_json_encode( $theme_mod );

			// Set the theme mods.
			$options['boldgrid_color_palette'] = $encoded_theme_mod;
			$options['boldgrid_palette_class'] = $format;
			// Update the theme mods.
			update_option( $option, $options );
		}
	}

	/**
	 * Override the tgm configurations based on active plugins.
	 *
	 * @since 1.5.4
	 *
	 * @param  array $configs  BGTFW Configs.
	 * @return array           BGTFW Configs.
	 */
	public function tgm_override( $configs ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		foreach ( $configs['tgm']['renamed_plugins'] as $renamed ) {

			// Check for renamed plugins.
			$plugins = get_plugins();

			foreach ( $plugins as $plugin => $args ) {

				// Check if old name matches installed plugins.
				$name = strtolower( str_replace( '-', ' ', $renamed['old_name'] ) );
				if ( isset( $args['Name'] ) && ( strtolower( $args['Name'] === $name ) ) ) {

					// Check if plugin is active.
					if ( class_exists( str_replace( ' ', '_', ucwords( $name ) ) ) ) {

						// Remove recommended plugin from configs.
						$configs['tgm']['plugins'] = $this->remove_recommended_plugin( $configs, $renamed['new_name'] );
					}
				}
			}
		}

		return $configs;
	}

	/**
	 * Remove a reccomended plugin from the configs.
	 *
	 * @since 1.5.4
	 *
	 * @param  array $configs              BGTFW Configs.
	 * @param  array $disabled_plugin_name List of disabled names.
	 * @return array $configs              BGTFW Configs.
	 */
	public function remove_recommended_plugin( $configs, $disabled_plugin_name ) {
		$plugins = [];

		foreach ( $configs['tgm']['plugins'] as $plugin ) {
			if ( $disabled_plugin_name !== $plugin['slug'] ) {
				$plugins[] = $plugin;
			}
		}

		$configs['tgm']['plugins'] = $plugins;

		return $configs;
	}

	/**
	 * Register the required plugins for this theme.
	 *
	 * @since 1.5.4
	 */
	public function register_required_plugins() {
		tgmpa( $this->configs['tgm']['plugins'], $this->configs['tgm']['configs'] );
	}
}
