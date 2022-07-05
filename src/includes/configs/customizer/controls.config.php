<?php
/**
 * Customizer Controls Configs.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since 2.0.0
 *
 * @return array Controls to create in the WordPress Customizer.
 */

global $boldgrid_theme_framework;
$bgtfw_configs = $boldgrid_theme_framework->get_configs();

// Check that get_page_templates() method is available in the customizer.
if ( ! function_exists( 'get_page_templates' ) ) {
	require_once ABSPATH . 'wp-admin/includes/theme.php';
}

$bgtfw_palette           = new Boldgrid_Framework_Compile_Colors( $bgtfw_configs );
$bgtfw_active_palette    = $bgtfw_palette->get_active_palette();
$bgtfw_formatted_palette = $bgtfw_palette->color_format( $bgtfw_active_palette );
$bgtfw_color_sanitize    = new Boldgrid_Framework_Customizer_Color_Sanitize();
$bgtfw_typography        = new Boldgrid_Framework_Customizer_Typography( $bgtfw_configs );
$bgtfw_generic           = new Boldgrid_Framework_Customizer_Generic( $bgtfw_configs );
$bgtfw_presets           = new Boldgrid_Framework_Customizer_Presets( $bgtfw_configs );
$bgtfw_partial_refresh   = new Boldgrid_Framework_Customizer_Partial_Refresh( $bgtfw_configs );

$background_image_controls = require dirname( __FILE__ ) . '/controls/background-image.controls.php';
$blog_page_controls        = require dirname( __FILE__ ) . '/controls/blog-page.controls.php';
$blog_post_controls        = require dirname( __FILE__ ) . '/controls/blog-post.controls.php';
$dropdown_controls         = require dirname( __FILE__ ) . '/controls/dropdown.controls.php';
$footer_generic_controls   = require dirname( __FILE__ ) . '/controls/footer-generic.controls.php';
$footer_layout_controls    = require dirname( __FILE__ ) . '/controls/footer-layout.controls.php';
$general_controls          = require dirname( __FILE__ ) . '/controls/general.controls.php';
$header_generic_controls   = require dirname( __FILE__ ) . '/controls/header-generic.controls.php';
$header_layout_controls    = require dirname( __FILE__ ) . '/controls/header-layout.controls.php';
$menu_controls             = require dirname( __FILE__ ) . '/controls/menu.controls.php';
$page_title_controls       = require dirname( __FILE__ ) . '/controls/page-title.controls.php';
$pages_controls            = require dirname( __FILE__ ) . '/controls/pages.controls.php';
$title_tagline_controls    = require dirname( __FILE__ ) . '/controls/title-tagline.controls.php';
$typography_controls       = require dirname( __FILE__ ) . '/controls/typography.controls.php';
$woocommerce_controls      = require dirname( __FILE__ ) . '/controls/woocommerce.controls.php';
$general_controls          = require dirname( __FILE__ ) . '/controls/general.controls.php';
$button_controls           = require dirname( __FILE__ ) . '/controls/buttons.controls.php';
$container_width_controls  = require dirname( __FILE__ ) . '/controls/container-width.controls.php';
$weforms_controls          = require dirname( __FILE__ ) . '/controls/weforms.controls.php';

return array_merge(
	$background_image_controls,
	$blog_page_controls,
	$blog_post_controls,
	$dropdown_controls,
	$footer_generic_controls,
	$footer_layout_controls,
	$general_controls,
	$header_generic_controls,
	$header_layout_controls,
	$menu_controls,
	$page_title_controls,
	$pages_controls,
	$title_tagline_controls,
	$typography_controls,
	$woocommerce_controls,
	$button_controls,
	$container_width_controls,
	$weforms_controls,
	$general_controls
);
