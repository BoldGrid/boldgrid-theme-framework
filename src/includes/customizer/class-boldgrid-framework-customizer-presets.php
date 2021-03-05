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
		$this->configs               = $configs;
		$this->current_header_layout = get_theme_mod( 'bgtfw_header_layout' );
		$this->current_sticky_layout = get_theme_mod( 'bgtfw_sticky_header_layout' );
		$this->generic               = new Boldgrid_Framework_Customizer_Generic( $configs );
		$this->sticky_header         = new Boldgrid_Framework_Sticky_Header( $configs );
		$this->set_default_layout();
		$this->set_custom_layout();
		$this->add_theme_mods();
	}

	/**
	 * Add Theme Mods
	 *
	 * If the theme mods for each preset is not set yet.
	 * Set a theme mod for each one.
	 */
	public function add_theme_mods() {
		$presets = $this->configs['customizer-options']['presets'];
		foreach ( $presets as $preset_type => $preset_list ) {
			$theme_mod_type = 'bgtfw_' . $preset_type . '_layout_';
			foreach ( $preset_list as $preset_id => $preset ) {
				$theme_mod = $theme_mod_type . $preset_id;
				if ( get_theme_mod( $theme_mod ) !== $preset['config'] ) {
					set_theme_mod( $theme_mod, $preset['config'] );
				}
			}
		}
	}

	/**
	 * Set Default Layout.
	 *
	 * Since the default layout can be different from.
	 * one Inspiration to another, this will set a value as 'default'
	 * if a value is not already set.
	 *
	 * @since SINCEVERSION
	 */
	public function set_default_layout() {
		global $wp_customize;
		global $pagenow;
		$default_layout        = get_theme_mod( 'bgtfw_default_header_layout' );
		$default_sticky_layout = get_theme_mod( 'bgtfw_default_sticky_header_layout' );

		if ( ! $default_layout && $this->current_header_layout ) {
			set_theme_mod( 'bgtfw_default_header_layout', $this->current_header_layout );
		}

		if ( ! $default_sticky_layout && $this->current_sticky_layout ) {
			set_theme_mod( 'bgtfw_default_sticky_header_layout', $this->current_sticky_layout );
		}

		// if ( $default_layout === $this->current_header_layout ) {
		// 	set_theme_mod( 'bgtfw_header_preset', 'default' );
		// 	return;
		// }

		foreach ( $this->configs['customizer-options']['presets'] as $preset_id => $preset ) {
			if ( $this->current_header_layout === $preset ) {
				set_theme_mod( 'bgtfw_header_preset', $preset_id );
			}
		}
	}

	/**
	 * Set Custom Layout.
	 *
	 * If the custom layout option is not set yet.
	 * then we set the custom layout to match the current layout.
	 *
	 * @since SINCEVERSION
	 */
	public function set_custom_layout() {
		$default_layout = get_theme_mod( 'bgtfw_default_header_layout' );
		$custom_layout  = get_theme_mod( 'bgtfw_custom_header_layout' );
		if ( ! $custom_layout ) {
			set_theme_mod(
				'bgtfw_custom_header_layout',
				array(
					array(
						'container' => 'container',
						'items' => array(
							array(
								'type' => 'boldgrid_site_identity',
								'key' => 'branding',
								'align' => 'w',
								'display' => array(
									array(
										'selector' => '.custom-logo-link',
										'display' => 'show',
										'title' => __( 'Logo', 'bgtfw' ),
									),
									array(
										'selector' => '.site-title',
										'display' => 'show',
										'title' => __( 'Title', 'bgtfw' ),
									),
									array(
										'selector' => '.site-description',
										'display' => 'show',
										'title' => __( 'Tagline', 'bgtfw' ),
									),
								),
							),
							array(
								'type' => 'boldgrid_menu_main',
								'key' => 'menu',
								'align' => 'e',
							),
						),
					),
				)
			);
		}
	}

	/**
	 * Add a nonce for Customizer for option presets.
	 */
	function header_layout_nonces( $nonces ) {
		$nonces['bgtfw-header-preset']        = wp_create_nonce( 'bgtfw_header_layout' );
		$nonces['bgtfw-sticky-header-preset'] = wp_create_nonce( 'bgtfw_sticky_header_layout' );
		return $nonces;
	}

	/**
	 * WP Ajax BGTFW Header Layout.
	 *
	 * Handles Ajax calls for header layout refreshes.
	 *
	 * @since SINCEVERSION
	 */
	public function wp_ajax_bgtfw_header_layout() {
		global $wp_customize;
		check_ajax_referer( 'bgtfw_header_layout', 'headerPresetNonce' );
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( -1 );
		}

		$custom_layout = isset( $_POST['customLayout'] ) ? $_POST['customLayout'] : '';
		$preset        = isset( $_POST['headerPreset'] ) ? $_POST['headerPreset'] : '';

		$markup = '';
		$layout = '';

		if ( $custom_layout ) {
			error_log( 'custom_layout: ' . json_encode( $custom_layout ) );
			$markup = BoldGrid::dynamic_layout( 'bgtfw_header_layout', $preset, $custom_layout );
			$layout = $custom_layout;
		} elseif ( $preset ) {
			$markup = BoldGrid::dynamic_layout( 'bgtfw_header_layout', $preset );
			$layout = $this->configs['customizer-options']['presets']['header'][ $preset ]['config'];
		}

		$sliders = $this->generic->get_header_columns( $layout );

		wp_send_json_success( array(
			'markup' => $markup,
			'layout' => $layout,
			'sliders' => $sliders,
		) );
	}

	/**
	 * WP Ajax BGTFW Header Layout.
	 *
	 * Handles Ajax calls for header layout refreshes.
	 *
	 * @since SINCEVERSION
	 */
	public function wp_ajax_bgtfw_sticky_header_layout() {
		global $wp_customize;
		check_ajax_referer( 'bgtfw_sticky_header_layout', 'headerPresetNonce' );
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( -1 );
		}

		$custom_layout = isset( $_POST['customLayout'] ) ? $_POST['customLayout'] : '';
		$preset        = isset( $_POST['headerPreset'] ) ? $_POST['headerPreset'] : '';

		$markup = '';
		$layout = '';

		if ( $custom_layout ) {
			$markup = BoldGrid::dynamic_layout( 'bgtfw_sticky_header_layout', $preset, $custom_layout );
			$layout = $custom_layout;
		} elseif ( $preset ) {
			$markup = BoldGrid::dynamic_layout( 'bgtfw_sticky_header_layout', $preset );
			$layout = $this->configs['customizer-options']['presets']['sticky_header'][ $preset ]['config'];
		}

		wp_send_json_success( array(
			'markup' => $markup,
			'layout' => $layout,
		) );
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

		$presets     = array(
			'default' => get_template_directory_uri() . '/inc/boldgrid-theme-framework/assets/img/presets/default.svg',
		);

		$preset_keys = array_keys( $this->configs['customizer-options']['presets'][ $preset_type ] );

		foreach ( $preset_keys as $key ) {
			$presets[ $key ] = get_template_directory_uri() . '/inc/boldgrid-theme-framework/assets/img/presets/' . $key . '.svg';
		}

		return $presets;
	}

	/**
	 * Get Presets.
	 *
	 * This produces the 'preset' array for the kirki controls.
	 *
	 * @since SINCEVERSION
	 *
	 * @param string $preset_type The type of preset to return.
	 *
	 * @return array An array of preset configurations.
	 */
	public function get_presets( $preset_type ) {
		$preset_configs = $this->configs['customizer-options']['presets'][ $preset_type ];
		$presets        = array();
		foreach ( $preset_configs as $preset => $config ) {
			$presets[ $preset ] = array(
				'settings' => array(
					'bgtfw_' . $preset_type . '_layout' => $config['config'],
				),
			);
			if( 'header' === $preset_type && 'lshsbm' === $preset ) {
				$presets[ $preset ]['settings']['bgtfw_header_layout_position'] = 'header-left';
			} elseif ( 'header' === $preset_type ) {
				$presets[ $preset ]['settings']['bgtfw_header_layout_position'] = 'header-top';
			}
		}

		$default_layout = get_theme_mod( 'bgtfw_default_' . $preset_type . '_layout' );

		if ( $default_layout ) {
			$presets['default'] = array(
				'settings' => array(
					'bgtfw_' . $preset_type . '_layout' => $config['config'],
				),
			);
		}

		return $presets;
	}
}
