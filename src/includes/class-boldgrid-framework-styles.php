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

		$files = apply_filters( 'local_editor_styles', $files );

		// Enqueue styles for Gutenberg.
		$config = $this->configs;
		add_action( 'enqueue_block_editor_assets', function() use ( $files, $config ) {
			$gutenberg = array();
			foreach ( $files as $file ) {
				$handle = explode( '?', basename( $file ) );
				$handle = basename( basename( $handle[0], '.css' ), '.min' );
				$query = $handle[1];
				parse_str( $handle[1] );
				$gutenberg[] = array(
					'handle' => $handle,
					'file' => $file,
					'version' => ! empty( $version ) ? $version : null,
				);
			}

			foreach ( $gutenberg as $ss ) {
				wp_enqueue_style( $ss['handle'], $ss['file'], ! is_null( $ss['version'] ) && $ss['version'] );
			}

			// Add Kirki dynamically generated styles.
			$kirki_css = Kirki_Modules_CSS::get_instance();
			$styles = apply_filters( 'kirki_bgtfw_dynamic_css', $kirki_css::loop_controls( 'bgtfw' ) );
			$styles = apply_filters( 'boldgrid_mce_inline_styles', $styles );

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

			if ( is_customize_preview() ) {
				$bgtfw = new BoldGrid( $this->configs );
				$inline_css .= $bgtfw->main_menu_css();
			};

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
			$color = array_pop( $color );
			$css .= ".{$location}-menu-btn .hamburger-inner,.{$location}-menu-btn .hamburger-inner:before,.{$location}-menu-btn .hamburger-inner:after {background-color: {$color};}";
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
		$color = explode( ':', $color );
		$color = array_pop( $color );

		$background_color = get_theme_mod( "bgtfw_menu_items_hover_background_{$location}" );
		$background_color = explode( ':', $background_color );
		$background_color = array_pop( $background_color );

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
		$color = explode( ':', $color );
		$color = array_pop( $color );

		$menu_id = "#{$location}-menu";
		$css = "{$menu_id} > li.current-menu-item > a { color: {$color} !important; }";

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
		$generic = new Boldgrid_Framework_Customizer_Generic( $config );
		$menus = get_registered_nav_menus();
		foreach ( $menus as $location => $description ) {
			$generic->add_inline_style( "hover-{$location}", $this->hover_generate( $location ) );
			$generic->add_inline_style( "active-link-color-{$location}", $this->active_link_generate( $location ) );
		}
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
		wp_enqueue_style(
			'boldgrid-theme-framework',
			$this->configs['framework']['css_dir'] . 'boldgrid-theme-framework' . $suffix . '.css',
			array(),
			$this->configs['version']
		);

		/* Framework Base Styles */
		wp_enqueue_style(
			'bgtfw-smartmenus',
			$this->configs['framework']['css_dir'] . 'smartmenus/sm-core-css.css',
			array( 'bootstrap-styles' ),
			$this->configs['version']
		);

		/* Framework Base Styles */
		wp_enqueue_style(
			'bgtfw-smartmenus-bootstrap',
			$this->configs['framework']['css_dir'] . 'smartmenus/bootstrap/jquery.smartmenus.bootstrap.css',
			array( 'bootstrap-styles' ),
			$this->configs['version']
		);

		/* Framework Base Styles */
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
				'boldgrid-animate-css',
				$this->configs['framework']['css_dir'] . 'animate-css/animate' . $suffix . '.css',
				array(),
				$this->configs['version']
			);
		}

		// Add offcanvas styles.
		if ( true === $this->configs['scripts']['offcanvas-menu'] ) {
			wp_enqueue_style(
				'boldgrid-offcanvas-css',
				$this->configs['framework']['css_dir'] . 'offcanvas' . $suffix . '.css',
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

		$css = apply_filters( "$id-content", $css );

		return "<style id='{$id}' type='text/css'>{$css}</style>";
	}

	/**
	 * Add styles to the TinyMCE Editor to make it more WYSIWYG.
	 *
	 * @since     1.0.0
	 */
	public function add_editor_styling() {
		$local_files = $this->get_local_editor_styles();

		apply_filters( 'boldgrid_theme_framework_editor_styles', $local_files );
		add_editor_style( $local_files );
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

}
