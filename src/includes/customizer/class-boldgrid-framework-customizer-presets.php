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
				if ( ! get_theme_mod( $theme_mod ) ) {
					set_theme_mod( $theme_mod, $preset['config'] );
				}
			}
		}
	}

	public function get_branding_notices() {
		$markup  = '<p class="branding_notice logo">'
			. esc_html__( 'You do not have a logo set. ', 'bgtfw' )
			. '<a href="#">' . esc_html__( 'Click Here', 'bgtfw' )
			. ' </a>' . esc_html__( ' to set your logo.', 'bgtfw' )
			. '</p>';
		$markup .= '<p class="branding_notice title">'
			. esc_html__( 'You do not have a site title set. ', 'bgtfw' )
			. '<a href="#">' . esc_html__( 'Click Here', 'bgtfw' )
			. ' </a>' . esc_html__( ' to set your site title.', 'bgtfw' )
			. '</p>';
		$markup .= '<p class="branding_notice description">'
			. esc_html__( 'You do not have a tagline set. ', 'bgtfw' )
			. '<a href="#">' . esc_html__( 'Click Here', 'bgtfw' )
			. ' </a>' . esc_html__( ' to set your tagline.', 'bgtfw' )
			. '</p>';

		return $markup;
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
		$default_layout = get_theme_mod( 'bgtfw_default_header_layout' );

		if ( ! $default_layout && $this->current_header_layout ) {
			set_theme_mod( 'bgtfw_default_header_layout', $this->current_header_layout );
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
		$default_layout       = get_theme_mod( 'bgtfw_default_header_layout' );
		$custom_layout        = get_theme_mod( 'bgtfw_header_layout_custom' );
		$custom_sticky_layout = get_theme_mod( 'bgtfw_custom_sticky_layout' );
		if ( ! $custom_layout ) {
			set_theme_mod(
				'bgtfw_header_layout_custom',
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
										'display' => 'hide',
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

		if ( ! $custom_sticky_layout ) {
			set_theme_mod(
				'bgtfw_custom_sticky_layout',
				array(
					array(
						'container' => 'container',
						'items'     => array(
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
										'display' => 'hide',
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
	public function header_layout_nonces( $nonces ) {
		$nonces['bgtfw-header-preset'] = wp_create_nonce( 'bgtfw_header_layout' );
		return $nonces;
	}

	public function get_custom_layout( $header_type ) {
		$defaults = array(
			'header'        => get_theme_mod( 'bgtfw_header_layout_custom' ),
			'sticky_header' => get_theme_mod( 'bgtfw_custom_sticky_layout' ),
		);

		return $defaults[ $header_type ];
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

		if ( empty( $_POST['headerPreset'] ) ) {
			wp_send_json_error( 'mytheme_missing_preset_parameter' );
		}

		$preset = sanitize_text_field( wp_unslash( $_POST['headerPreset'] ) );

		$layout = '';
		if ( ! empty( $_POST['customHeaderLayout'] ) ) {
			$custom_layout = $_POST['customHeaderLayout'];
			$layout        = $custom_layout;
			$markup        = BoldGrid::dynamic_layout( 'bgtfw_header_layout', $preset, $custom_layout );
		} else {
			$layout = get_theme_mod( 'bgtfw_header_layout_' . $preset );
			$markup = BoldGrid::dynamic_layout( 'bgtfw_header_layout', $preset );
		}

		wp_send_json_success( array(
			'layout' => $layout,
			'markup' => $markup,
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

		$presets['custom'] = get_template_directory_uri() . '/inc/boldgrid-theme-framework/assets/img/presets/custom.svg';

		return $presets;
	}
}
