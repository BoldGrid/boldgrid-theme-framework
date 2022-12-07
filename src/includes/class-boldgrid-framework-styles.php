<?php
/**
 * Class: BoldGrid_Framework_Styles
 *
 * This contains the CSS styles that a theme will enqueue.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Styles
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Styles
 *
 * This contains the CSS styles that a theme will enqueue to the
 * front end of the site.
 *
 * @since      1.0.0
 */
class BoldGrid_Framework_Styles {

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
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Editor Button Fonts.
	 *
	 * Registers the button fonts to display in the ppb button.
	 *
	 * @since 2.12.0
	 */
	public function editor_button_fonts() {
		$is_editor = strpos( $_SERVER['REQUEST_URI'], '/post-new' ) !== false || strpos( $_SERVER['REQUEST_URI'], 'action=edit' ) !== false;

		if ( ! $is_editor ) {
			return;
		}

		$button_types = array(
			'primary'   => '.presets.palette-primary .button-primary',
			'secondary' => '.presets.palette-primary .button-secondary',
		);

		$css = '';

		foreach ( $button_types as $button_type => $selectors ) {
			$typography = get_theme_mod( 'bgtfw_button_' . $button_type . '_typography' );
			$css       .= $selectors . ' {';
			foreach ( $typography as $css_propery => $css_value ) {
				if ( empty( $css_value ) ) {
					continue;
				}

				$css .= $css_propery . ':' . $css_value . ';';
			}
			$css .= '}';
		}

		wp_register_style( 'bgtfw-editor-button-fonts', false );
		wp_add_inline_style( 'bgtfw-editor-button-fonts', $css );
		wp_enqueue_style( 'bgtfw-editor-button-fonts' );
	}

	/**
	 * Register Responsive Font Sizes.
	 *
	 * Registers the responsive font size css.
	 *
	 * @since 2.11.0
	 */
	public function register_responsive_font_sizes() {
		$css = $this->generate_responsive_font_css();

		wp_register_style( 'bgtfw-responsive-font-sizes', false );
		wp_add_inline_style( 'bgtfw-responsive-font-sizes', $css );
		wp_enqueue_style( 'bgtfw-responsive-font-sizes' );
	}

	/**
	 * Register weForms Styles.
	 *
	 * Registers the weForms styles.
	 *
	 * @since 2.15.0
	 */
	public function register_weforms_styles() {
		$label_color = get_theme_mod( 'bgtfw_weforms_label_color' );

		$label_color = 'var(--' . explode( ':', $label_color )[0] . ') !important;';

		$label_css = '.wpuf-theme-style .wpuf-label label { color: ' . $label_color . ' }';

		wp_register_style( 'bgtfw-weforms-label-color', false );
		wp_add_inline_style( 'bgtfw-weforms-label-color', $label_css );
		wp_enqueue_style( 'bgtfw-weforms-label-color' );

		$sub_label_color = get_theme_mod( 'bgtfw_weforms_sublabel_color' );

		$sub_label_color = 'var(--' . explode( ':', $sub_label_color )[0] . ') !important;';

		$sub_label_css = '.wpuf-theme-style .wpuf-form-sub-label { color: ' . $sub_label_color . ' }';

		wp_register_style( 'bgtfw-weforms-sublabel-color', false );
		wp_add_inline_style( 'bgtfw-weforms-sublabel-color', $sub_label_css );
		wp_enqueue_style( 'bgtfw-weforms-sublabel-color' );
	}

	/**
	 * Register Container Widths.
	 *
	 * @since 2.14.0
	 */
	public function register_container_widths() {
		$scss = new Boldgrid_Framework_SCSS( $this->configs );
		$css  = $scss->compile_widths();

		wp_register_style( 'bgtfw-container-widths', false, $this->configs['version'] );
		wp_add_inline_style( 'bgtfw-container-widths', $css );
		wp_enqueue_style( 'bgtfw-container-widths' );
	}

	/** Generate Responive Font CSS
	 *
	 * Generates responsive font css.
	 *
	 * @param string $css The css to be added to the page.
	 *
	 * @since 2.11.0
	 */
	public function generate_responsive_font_css( $css = '' ) {
		$responsive_controls = $this->configs['customizer-options']['typography']['responsive_font_controls'];
		// XS / Phone.
		$css .= '@media only screen and (max-width: 767px) {';
		foreach ( $responsive_controls as $control_id => $control_data ) {
			if ( 'bgtfw_headings_responsive_font_size' === $control_id ) {
				continue;
			}
			$theme_mod = json_decode( get_theme_mod( $control_id ), true );
			$font_size = ! empty( $theme_mod['phone'] ) ? $theme_mod['phone'] : false;
			if ( $font_size ) {
				$css .= $control_data['output_selector'];
				$css .= '{ font-size: ' . $font_size . '!important;}';
			}
		}
		$css .= '}';

		// SM / Tablet.
		$css .= '@media only screen and (min-width: 768px) and (max-width: 991px) {';
		foreach ( $responsive_controls as $control_id => $control_data ) {
			if ( 'bgtfw_headings_responsive_font_size' === $control_id ) {
				continue;
			}
			$theme_mod = json_decode( get_theme_mod( $control_id ), true );
			$font_size = ! empty( $theme_mod['tablet'] ) ? $theme_mod['tablet'] : false;
			if ( $font_size ) {
				$css .= $control_data['output_selector'];
				$css .= '{ font-size: ' . $font_size . '!important;}';
			}
		}
		$css .= '}';

		// MD / Desktop.
		$css .= '@media only screen and (min-width: 992px) and (max-width: 1199px) {';
		foreach ( $responsive_controls as $control_id => $control_data ) {
			if ( 'bgtfw_headings_responsive_font_size' === $control_id ) {
				continue;
			}
			$theme_mod = json_decode( get_theme_mod( $control_id ), true );
			$font_size = ! empty( $theme_mod['desktop'] ) ? $theme_mod['desktop'] : false;
			if ( $font_size ) {
				$css .= $control_data['output_selector'];
				$css .= '{ font-size: ' . $font_size . '!important;}';
			}
		}
		$css .= '}';

		// LG / Large Desktop.
		$css .= '@media only screen and (min-width: 1200px) {';
		foreach ( $responsive_controls as $control_id => $control_data ) {
			if ( 'bgtfw_headings_responsive_font_size' === $control_id ) {
				continue;
			}
			$theme_mod = json_decode( get_theme_mod( $control_id ), true );
			$font_size = ! empty( $theme_mod['large'] ) ? $theme_mod['large'] : false;
			if ( $font_size ) {
				$css .= $control_data['output_selector'];
				$css .= '{ font-size: ' . $font_size . '!important;}';
			}
		}
		$css .= '}';

		return $css;
	}

	/**
	 * Return a list of the editor styles that will be applied that are actually contained
	 * with the theme
	 *
	 * @return array
	 * @since 1.0.3
	 */
	public function get_local_editor_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$css_dir = $this->configs['framework']['css_dir'];
		$versions = $this->configs['styles']['versions'];

		$files = array(
			// uri() is required to enforce order.
			$css_dir . 'bootstrap/bootstrap.min.css',
			$css_dir . 'font-awesome/font-awesome' . $suffix . '.css?version=' . $versions['font-awesome'],
			$css_dir . 'boldgrid-theme-framework' . $suffix . '.css',
			$css_dir . 'components' . $suffix . '.css?version=' . $versions['boldgrid-components'],
			$this->configs['framework']['config_directory']['uri'] . '/style.css',
			Boldgrid_Framework_Customizer_Colors::get_colors_uri( $this->configs ),
		);

		$files = apply_filters( 'boldgrid_theme_framework_local_editor_styles', $files );

		// Enqueue styles for Gutenberg.
		$config = $this->configs;
		add_action( 'enqueue_block_editor_assets', function() use ( $files, $config ) {
			foreach ( $files as $file ) {
				$handle = explode( '?', basename( $file ) );
				$handle = basename( basename( $handle[0], '.css' ), '.min' );

				// Parse version information for style from asset passed.
				$version = null;
				$parsed = wp_parse_url( $file );

				if ( ! empty( $parsed['query'] ) ) {
					wp_parse_str( $parsed['query'], $data );
					if ( ! empty( $data['version'] ) ) {
						$version = $data['version'];
					}
				}

				wp_enqueue_style( $handle, $file, $version );
			}

			// Add Kirki dynamically generated styles.
			$kirki_css = Kirki_Modules_CSS::get_instance();
			$styles    = apply_filters( 'kirki_bgtfw_dynamic_css', $kirki_css::loop_controls( 'bgtfw' ) );
			$styles    = apply_filters( 'boldgrid_mce_inline_styles', $styles );

			wp_register_style( 'bgtfw-dynamic', false );
			wp_add_inline_style( 'bgtfw-dynamic', $styles );
			wp_enqueue_style( 'bgtfw-dynamic' );
		} );

		return $files;
	}

	/**
	 * Enqueue the button styles for our BoldGrid Theme.
	 *
	 * @since     1.3
	 */
	public function enqueue_buttons( $deps = array() ) {
		$button_configs = $this->configs['components']['buttons'];

		if ( true === $button_configs['enabled'] && file_exists( $button_configs['css_file'] ) ) {
			$last_mod = filemtime( $button_configs['css_file'] );
			wp_enqueue_style( 'boldgrid-buttons', $button_configs['css_uri'], $deps, $last_mod );
		}

	}

	/**
	 * Enqueue the color buttons for our BoldGrid Theme.
	 *
	 * @since     1.3
	 */
	public function enqueue_colors( $deps = array() ) {

		$config_settings = $this->configs['customizer-options']['colors'];
		if ( ! empty( $config_settings['enabled'] ) && file_exists( $config_settings['settings']['output_css_name'] ) ) {

			$version = '';
			$last_mod = filemtime( $config_settings['settings']['output_css_name'] );
			if ( $last_mod ) {
				$version = $last_mod;
			}

			$handle = 'boldgrid-color-palettes';
			$inline_override = true === $this->configs['framework']['inline_styles'];
			$is_changeset = ! empty( $_REQUEST['customize_changeset_uuid'] ) && ! is_customize_preview();

			if ( $inline_override || $is_changeset || is_customize_preview() ) {
				wp_register_style( $handle, false );
				wp_enqueue_style( $handle );
				$css = get_theme_mod( 'boldgrid_compiled_css', '' );
				wp_add_inline_style( $handle, $css );
			} else {
				wp_register_style(
					$handle,
					Boldgrid_Framework_Customizer_Colors::get_colors_uri( $this->configs ),
					$deps,
					$last_mod
				);
				wp_enqueue_style( $handle );
			}
		}

		if ( true === $this->configs['edit-post-links']['enabled'] ) {

			// Default colors.
			$background = $this->configs['edit-post-links']['default-colors']['background'];
			$color = $this->configs['edit-post-links']['default-colors']['fill'];

			if ( true === $this->configs['edit-post-links']['use-theme-colors'] ) {

				$helper = new Boldgrid_Framework_Compile_Colors( $this->configs );
				$palettes = $helper->get_palette();

				if ( ! empty( $palettes ) ) {
					$current_palette = $palettes['state']['active-palette'];
					$colors = is_array( $palettes['state']['palettes'][ $current_palette ]['colors'] ) ? $palettes['state']['palettes'][ $current_palette ]['colors'] : array();

					$light = $this->configs['customizer-options']['colors']['light_text'];
					$dark = $this->configs['customizer-options']['colors']['dark_text'];

					$index = preg_replace( '/[^0-9]/', '', $this->configs['edit-post-links']['default-theme-color'] );
					$index = ! empty( $index ) ? ( ( int ) $index ) - 1 : 0;

					$color = $helper->get_luminance( $colors[ $index ] );

					$lightness = abs( $color - $helper->get_luminance( $light ) );
					$darkness = abs( $color - $helper->get_luminance( $dark ) );

					$color = $lightness > $darkness ? $light : $dark;
					$background = $colors[ $index ];
				}
			}

			$inline_css = '.bgtfw-edit-link a{background:' . $background . '!important;border:2px solid ' . $color . '!important;color:' . $color . '!important;}';
			$inline_css .= '.bgtfw-edit-link a:focus{-webkit-box-shadow: 0 0 0 2px ' . $color . '!important;box-shadow: 0 0 0 2px ' . $color . '!important;}';
			$inline_css .= '.bgtfw-edit-link a svg{fill:' . $color . '!important;}';

			wp_add_inline_style( 'style', $inline_css );
		}
	}

	/**
	 * Adds custom CSS for hamburger menu locations.
	 *
	 * @since 2.0.0
	 *
	 * @param string $css CSS string being filtered.
	 *
	 * @return string $css Modified CSS to add to front end.
	 */
	public function hamburgers_css( $css = '' ) {
		$menus = get_registered_nav_menus();

		foreach ( $menus as $location => $description ) {
			$color = get_theme_mod( "bgtfw_menu_hamburger_{$location}_color" );
			$color = explode( ':', $color );
			$color_var = $color[0];
			$color = array_pop( $color );
			$location = str_replace( '_', '-', $location );
			$css .= ".{$location}-menu-btn .hamburger-inner,.{$location}-menu-btn .hamburger-inner:before,.{$location}-menu-btn .hamburger-inner:after {background-color: var(--{$color_var});}";
		}

		return $css;
	}

	/**
	 * Generate hover color CSS for nav menu locations.
	 *
	 * @since 2.0.0
	 *
	 * @param string $location Nav menu location to generate CSS for.
	 *
	 * @return string $css Generated CSS for nav menu location.
	 */
	public function hover_generate( $location = '' ) {
		if ( empty( $location ) ) {
			$location = 'main';
		}

		$color = get_theme_mod( "bgtfw_menu_items_hover_color_{$location}" );
		list( $color ) = explode( ':', $color );
		$color = "var(--{$color})";

		$background_color = get_theme_mod( "bgtfw_menu_items_hover_background_{$location}" );
		list( $background_color ) = explode( ':', $background_color );
		$background_color = "var(--{$background_color})";

		$location = str_replace( '_', '-', $location );
		$menu_id = "#{$location}-menu";

		$css = include $this->configs['framework']['includes_dir'] . 'partials/hover-colors-only.php';
		$css = sprintf( $css, $menu_id, $background_color, $color, $background_color );

		return $css;
	}

	/**
	 * Generate active link color CSS for nav menu locations.
	 *
	 * @since 2.0.0
	 *
	 * @param string $location Nav menu location to generate CSS for.
	 *
	 * @return string $css Generated CSS for nav menu location.
	 */
	public function active_link_generate( $location ) {
		$color = get_theme_mod( "bgtfw_menu_items_active_link_color_{$location}" );
		list( $color ) = explode( ':', $color );
		$color = "var(--{$color})";

		$location = str_replace( '_', '-', $location );
		$menu_id = "#{$location}-menu";
		$css = "{$menu_id} .current-menu-item > a:not( .btn ),{$menu_id} .current-menu-ancestor > a:not( .btn ),{$menu_id} .current-menu-parent > a:not( .btn ),{$menu_id} .current_page_parent > a:not( .btn ) { color: {$color}; }";

		return $css;
	}

	/**
	 * Generate hover link color CSS for nav menu locations.
	 *
	 * @since 2.0.0
	 *
	 * @param string $location Nav menu location to generate CSS for.
	 *
	 * @return string $css Generated CSS for nav menu location.
	 */
	public function hover_link_generate( $location ) {
		$color = get_theme_mod( "bgtfw_menu_items_hover_link_color_{$location}" );
		list( $color ) = explode( ':', $color );
		$color = "var(--{$color})";

		$location = str_replace( '_', '-', $location );
		$menu_id = "#{$location}-menu";
		$css = "{$menu_id} .hvr-none:not( .current-menu-item ) > a:not( .btn ):hover,{$menu_id} .hvr-none:not( .current-menu-ancestor ) > a:not( .btn ):hover,{$menu_id} .hvr-none:not( .current-menu-parent ) > a:not( .btn ):hover,{$menu_id} .hvr-none:not( .current_page_parent ) > a:not( .btn ):hover{ color: {$color}; }";

		return $css;
	}

	/**
	 * Adds custom CSS for hamburger menu locations.
	 *
	 * @since 2.0.0
	 */
	public function hover_css() {
		global $boldgrid_theme_framework;
		$config = $boldgrid_theme_framework->get_configs();
		$menus = get_registered_nav_menus();
		foreach ( $menus as $location => $description ) {
			Boldgrid_Framework_Customizer_Generic::add_inline_style( "hover-{$location}", $this->hover_generate( $location ) );
			Boldgrid_Framework_Customizer_Generic::add_inline_style( "active-link-color-{$location}", $this->active_link_generate( $location ) );
			Boldgrid_Framework_Customizer_Generic::add_inline_style( "hover-link-color-{$location}", $this->hover_link_generate( $location ) );
			Boldgrid_Framework_Customizer_Generic::add_inline_style( "menu-item-styles-{$location}", $this->menu_items_css( $location ) );
			Boldgrid_Framework_Customizer_Generic::add_inline_style( "menu-colors-{$location}", $this->menu_css( $location ) );
		}
	}

	/**
	 * Adds custom CSS for main menu based on header color/link color selections by user.
	 *
	 * @since 2.0.0
	 *
	 * @param string $location Menu location to generate CSS for.
	 *
	 * @return string $css Modified CSS to add to front end.
	 */
	public function menu_css( $location ) {
		$background_color         = get_theme_mod( "bgtfw_menu_background_{$location}" );
		$submenu_background_color = get_theme_mod( "bgtfw_menu_submenu_background_{$location}" );

		$in_footer = false;
		if ( strpos( $background_color, 'transparent' ) !== false ) {
			$background_color = 'header';

			if ( in_array( $location, $this->configs['menu']['footer_menus'], true ) ) {
				$background_color = 'footer';
				$in_footer        = true;
			}

			$background_color = get_theme_mod( "bgtfw_{$background_color}_color" );
		} else {
			$background_color = get_theme_mod( "bgtfw_menu_background_{$location}" );
		}

		$in_footer = false;
		if ( strpos( $submenu_background_color, 'transparent' ) !== false ) {
			$submenu_background_color = 'header';

			if ( in_array( $location, $this->configs['menu']['footer_menus'], true ) ) {
				$submenu_background_color = 'footer';
				$in_footer                = true;
			}

			$submenu_background_color = get_theme_mod( "bgtfw_{$submenu_background_color}_color" );
		} else {
			$submenu_background_color = get_theme_mod( "bgtfw_menu_submenu_background_{$location}" );
		}

		$background_color = explode( ':', $background_color );
		$background_color = array_pop( $background_color );

		$submenu_background_color = explode( ':', $submenu_background_color );
		$submenu_background_class = $submenu_background_color[0];
		$submenu_background_color = array_pop( $submenu_background_color );

		$color_obj    = ariColor::newColor( $background_color );
		$subcolor_obj = ariColor::newColor( $submenu_background_color );

		$css = '';

		$css .= $this->menu_items_css( $location );

		$location = str_replace( '_', '-', $location );

		if ( false === $in_footer ) {
			$css .= ".header-left #{$location}-menu, .header-right #{$location}-menu { background-color: " . $color_obj->toCSS( 'rgba' ) . '; }';
		}
		$subcolor_obj->alpha = 0.7;
		$css .= '@media (min-width: 768px) {';

		$subcolor_obj->alpha = 0.4;

		$css .= "#{$location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) {background-color: var(--{$submenu_background_class});}";
		$css .= "#{$location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a:not(.btn), ";
		$css .= "#{$location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a:not(.btn):hover, ";
		$css .= "#{$location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a:not(.btn):focus, ";
		$css .= "#{$location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a:not(.btn):active, ";
		$css .= "#{$location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a:not(.btn).highlighted, ";
		$css .= "#{$location}-menu.sm-clean span.scroll-up, #{$location}-menu.sm-clean span.scroll-down, ";
		$css .= "#{$location}-menu.sm-clean span.scroll-up:hover, #{$location}-menu.sm-clean span.scroll-down:hover ";
		$css .= "{ background-color: var(--{$submenu_background_class}); }";

		$css .= "#{$location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) { border: 1px solid var(--{$submenu_background_class});}";

		$css .= "#{$location}-menu.sm-clean > li.menu-item:not( .custom-sub-menu ) > ul.sub-menu:not(.custom-sub-menu):before, ";
		$css .= "#{$location}-menu.sm-clean > li.menu-item:not( .custom-sub-menu ) > ul.sub-menu:not(.custom-sub-menu):after ";
		$css .= "{ border-color: transparent transparent var(--{$submenu_background_class}) transparent; }";

		$css .= "#{$location}-menu.sm-clean > li.menu-item:not( .custom-sub-menu ) > ul.sub-menu.pointer-bottom:not(.custom-sub-menu):before, ";
		$css .= "#{$location}-menu.sm-clean > li.menu-item:not( .custom-sub-menu ) > ul.sub-menu.pointer-bottom:not(.custom-sub-menu):after ";
		$css .= "{ border-color: var(--{$submenu_background_class}) transparent transparent transparent; }";

		$css .= '}';

		return $css;
	}

	/**
	 * Menu Items Css
	 *
	 * Get css for various menu item css that is not obtained
	 * otherwise.
	 *
	 * @since 2.5.0
	 *
	 * @param string $location Menu location name.
	 *
	 * @return string CSS string.
	 */
	public function menu_items_css( $location ) {

		// This is a list of the menu item controls that do not get automatically added.
		$menu_items_controls = array(
			"bgtfw_menu_items_spacing_{$location}",
			"bgtfw_menu_items_border_{$location}",
			"bgtfw_menu_items_active_link_border_{$location}",
			"bgtfw_menu_items_border_radius_{$location}",
			"bgtfw_menu_items_active_link_border_radius_{$location}",
		);

		$css = '';
		foreach ( $menu_items_controls as $control ) {
			$theme_mod = get_theme_mod( $control );
			if ( ! empty( $theme_mod ) && isset( $theme_mod['css'] ) ) {
				$css .= $theme_mod['css'];
			}
		}

		return $css;
	}

	/**
	 * Get CSS Variables.
	 *
	 * @since 2.0.1
	 *
	 * @return string $inline_css Inline CSS for color variables.
	 */
	public function get_css_vars( $inline_css = '' ) {
		$helper = new Boldgrid_Framework_Compile_Colors( $this->configs );
		$active_palette = $helper->get_active_palette();
		$formatted_palette = $helper->color_format( $active_palette );
		$inline_css .= ':root {';

		if ( ! empty( $formatted_palette ) ) {

			$light = $this->configs['customizer-options']['colors']['light_text'];
			$dark = $this->configs['customizer-options']['colors']['dark_text'];

			$inline_css .= "--light-text:{$light};";
			$inline_css .= "--dark-text:{$dark};";
			$additional_css = '';
			foreach ( $formatted_palette as $property => $value ) {
				$contrast_color = $helper->get_luminance( $value );
				$lightness = abs( $contrast_color - $helper->get_luminance( $light ) );
				$darkness = abs( $contrast_color - $helper->get_luminance( $dark ) );
				$contrast_color = $lightness > $darkness ? 'light' : 'dark';
				$contrast_color = "var(--{$contrast_color}-text)";

				$inline_css .= "--{$property}:{$value};";
				$inline_css .= "--{$property}-text-contrast:{$contrast_color};";
				$property2 = str_replace( '-', '', $property );
				$additional_css .= ".{$property}-text-default, .{$property2}-text-default{color: var(--{$property}-text-contrast);}";
				$additional_css .= ".{$property}-text-contrast, .{$property2}-text-contrast, .{$property}-text-contrast-hover:hover, .{$property2}-text-contrast-hover:hover, .{$property}-text-contrast-hover:focus, .{$property2}-text-contrast-hover:focus { color: var(--{$property}-text-contrast) !important;}";
				$additional_css .= ".{$property}-color, .{$property2}-color{color: var(--{$property}) !important;}";
				$additional_css .= ".{$property}-background, .{$property2}-background{background: var(--{$property}) !important;}";
				$additional_css .= ".{$property}-background-color, .{$property2}-background-color{background-color: var(--{$property}) !important;}";
				$additional_css .= ".{$property}-border-color, .{$property2}-border-color{border-color: var(--{$property}) !important;}";
				$additional_css .= ".{$property}-outline-color, .{$property2}-outline-color{outline-color: var(--{$property}) !important;}";
			}
		}

		$inline_css .= '}';
		$inline_css .= $additional_css;

		return $inline_css;
	}

	/**
	 * Enqueue the styles for our BoldGrid Theme.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_enqueue_styles() {
		$configs = $this->configs;
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		/* Enqueue Fontawesome */
		$this->enqueue_fontawesome();

		/* Bootstrap */
		$bootstrap = get_stylesheet_directory_uri() . '/css/bootstrap.css';
		if ( ! file_exists( get_stylesheet_directory() . '/css/bootstrap.css' ) ||
			false === $this->configs['components']['bootstrap']['enabled'] ) {
				$bootstrap = $this->configs['framework']['css_dir'] . 'bootstrap/bootstrap' . $suffix . '.css';
		}

		wp_enqueue_style(
			'bootstrap-styles',
			$bootstrap,
			array(),
			'3.3.1'
		);

		/* Framework Base Styles */
		wp_register_style(
			'boldgrid-theme-framework',
			$this->configs['framework']['css_dir'] . 'boldgrid-theme-framework' . $suffix . '.css',
			array(),
			$this->configs['version']
		);

		$inline_css = $this->get_css_vars();

		wp_add_inline_style( 'boldgrid-theme-framework', $inline_css );

		wp_enqueue_style( 'boldgrid-theme-framework' );

		/* Hamburger Menu Styles */
		wp_register_style(
			'bgtfw-hamburgers',
			$this->configs['framework']['css_dir'] . 'hamburgers/hamburgers' . $suffix . '.css',
			array( 'boldgrid-theme-framework' ),
			$this->configs['version']
		);

		wp_add_inline_style( 'bgtfw-hamburgers', $this->hamburgers_css() );

		wp_enqueue_style( 'bgtfw-hamburgers' );

		wp_register_style(
			'hover.css',
			$this->configs['framework']['css_dir'] . 'hover.css/hover' . str_replace( '.', '-', $suffix ) . '.css',
			array( 'boldgrid-theme-framework' ),
			$this->configs['version']
		);

		wp_enqueue_style( 'hover.css' );

		$this->hover_css();

		/* Component Styles */
		wp_enqueue_style(
			'boldgrid-components',
			$this->configs['framework']['css_dir'] . 'components' . $suffix . '.css',
			array(),
			$this->configs['styles']['versions']['boldgrid-components']
		);

		/* Button Styles */
		$this->enqueue_buttons();

		/* If using a child theme, auto-load the parent theme style. */
		if ( is_child_theme( ) ) {
			wp_enqueue_style(
				'parent-style',
				trailingslashit( get_template_directory_uri() ) . 'style.css',
				array(
					'font-awesome',
					'bootstrap-styles',
					'boldgrid-theme-framework',
				),
				null
			);
		}

		// Add animate.css for animation effects if a theme requests it.
		if ( true === $this->configs['scripts']['animate-css'] ) {
			wp_enqueue_style(
				'animatecss',
				$this->configs['framework']['css_dir'] . 'animate-css/animate' . $suffix . '.css',
				array(),
				$this->configs['version']
			);
		}

		/* Always load active theme's style.css. */
		wp_enqueue_style(
			'style',
			get_stylesheet_uri(),
			array(
				'bootstrap-styles',
				'font-awesome',
			),
			null
		);
	}

	/**
	 * Adds the buttons.css to TinyMCE Editor.
	 *
	 * @since 1.2.4
	 *
	 * @return array $files An array of file uris to enqueue to TinyMCE.
	 */
	public function enqueue_editor_buttons( $files ) {
		if ( true === $this->configs['components']['buttons']['enabled'] &&
			file_exists( $this->configs['components']['buttons']['css_file'] ) ) {
				$colors = array_pop( $files );
				$files[] = $this->configs['components']['buttons']['css_uri'];
				$files[] = $colors;
		}

		return $files;
	}

	/**
	 * Given an array of css rules creates css string
	 *
	 * @since     1.0.0
	 *
	 * @param array  $css_rules Array of CSS rules to apply.
	 * @param string $id ID to give to style rule.
	 * @return    string CSS to apply.
	 */
	public static function convert_array_to_css( $css_rules, $id ) {
		// Convert array to css.
		$css = '';
		foreach ( $css_rules as $rule => $definitions ) {

			$def = '';
			foreach ( $definitions as $prop => $definition ) {
				$def .= $prop . ':' . $definition . ';';
			}

			$css .= sprintf( '%s { %s }', $rule, $def );
		}

		return apply_filters( "$id-content", $css );
	}

	/**
	 * Add styles to the TinyMCE Editor to make it more WYSIWYG.
	 *
	 * @since     1.0.0
	 */
	public function add_editor_styling() {
		global $wp_customize;

		$is_editor = strpos( $_SERVER['REQUEST_URI'], '/post-new' ) !== false || strpos( $_SERVER['REQUEST_URI'], 'action=edit' ) !== false;

		if ( $is_editor ) {
			$local_files = $this->get_local_editor_styles();
			apply_filters( 'boldgrid_theme_framework_editor_styles', $local_files );
			add_editor_style( $local_files );
		}
	}

	/**
	 * Add query string cache busting for color palette.css
	 * Uses the file last mod time for arg
	 *
	 * @since     1.0.3
	 *
	 * @param string $css CSS file to add cache bust string to.
	 */
	public function add_cache_busting( $css ) {
		$color_palette_css_name = $this->configs['customizer-options']['colors']['settings']['output_css_name'];
		$buttons_file = $this->configs['components']['buttons']['css_file'];

		// Files to add cache busting.
		$files = array(
			'color-palettes' => $color_palette_css_name,
			'boldgrid-buttons-css' => $buttons_file,
		);

		if ( empty( $css ) ) {
			return $css;
		}

		$styles = explode( ',',  $css );

		$mce_css = array();
		foreach ( $styles as $style ) {

			foreach ( $files as $search => $file ) {

				if ( false !== strpos( $style, $search ) ) {

					$time = time();
					if ( file_exists( $file ) ) {
						$time = filemtime( $file );
					}

					$added_query_arg = add_query_arg( 'framework-time', $time, $style );
					if ( $added_query_arg ) {
						$style = $added_query_arg;
					}
				}
			}

			$mce_css[] = $style;
		}

		return implode( ',', $mce_css );
	}

	/**
	 * Enqueue Font Awesome
	 *
	 * @since     1.0.0
	 */
	public function enqueue_fontawesome() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		/* Font Awesome */
		wp_enqueue_style(
			'font-awesome',
			$this->configs['framework']['css_dir'] . 'font-awesome/font-awesome' . $suffix . '.css',
			array(),
			$this->configs['styles']['versions']['font-awesome']
		);
		/* Custom Icons */
		wp_enqueue_style(
			'icomoon',
			$this->configs['framework']['css_dir'] . 'icomoon/style' . $suffix . '.css',
			array(),
			'1.0.0'
		);
	}

	/**
	 * Validate Fonts Directory
	 *
	 * This makes sure that the dowloaded fonts option
	 * is using the correct fonts directory. This is especially
	 * important when a Crio site is transferred from one server to another.
	 *
	 * @since 2.2.3
	 */
	public function validate_fonts_dir() {
		$kirki_downloaded_fonts = get_option( 'kirki_downloaded_font_files', array() );

		foreach ( $kirki_downloaded_fonts as $font_family => $files ) {
			if ( false === strpos( $files, ABSPATH ) ) {
				update_option( 'kirki_downloaded_font_files', array() );
			}
		}

	}
}
