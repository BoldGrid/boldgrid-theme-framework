<?php
/**
 * Class: Boldgrid_Framework_Customizer
 *
 * This is used to define some of the BoldGrid specific customizer controls.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Class BoldGrid_Framework_Customizer
 *
 * Responsible for some framework customizer controls.
 *
 * @since 1.0.0
 */
class BoldGrid_Framework_Customizer {

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

		// Ensure defaults are processed on customize load.
		$format = new BoldGrid_Framework_Starter_Content( $configs );
		$this->configs = $format->set_configs( $configs );
	}

	/**
	 * Add all kitki controls.
	 *
	 * @since 1.5.3
	 */
	public function kirki_controls() {
		global $wp_customize;

		foreach ( $this->configs['customizer']['controls'] as $control ) {
			if ( 'radio' !== $control['type'] ) {
				Kirki::add_field( 'bgtfw', $control );

				if ( strpos( $control['settings'], 'bgtfw_menu_' ) !== false &&
					strpos( $control['settings'], 'main' ) !== false && $wp_customize ) {

					$menus = get_registered_nav_menus();

					foreach ( $menus as $location => $description ) {
						$panel = new Boldgrid_Framework_Customizer_Panel(
							$wp_customize,
							"bgtfw_menu_location_$location",
							array(
								'title' => $description,
								'panel' => 'bgtfw_menus_panel',
								'capability' => 'edit_theme_options',
							)
						);

						$wp_customize->add_panel( $panel );

						$panel = new Boldgrid_Framework_Customizer_Panel(
							$wp_customize,
							"bgtfw_menu_items_$location",
							array(
								'title' => __( 'Menu Items', 'bgtfw' ),
								'panel' => "bgtfw_menu_location_$location",
								'capability' => 'edit_theme_options',
							)
						);

						$wp_customize->add_panel( $panel );

						Kirki::add_section(
							"bgtfw_menu_hamburgers_$location",
							array(
								'title' => __( 'Hamburger Style', 'bgtfw' ),
								'panel' => "bgtfw_menu_location_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-menu',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_typography_$location",
							array(
								'title' => __( 'Font', 'bgtfw' ),
								'panel' => "bgtfw_menu_location_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-editor-textcolor',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_background_$location",
							array(
								'title' => __( 'Background', 'bgtfw' ),
								'panel' => "bgtfw_menu_location_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-format-image',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_border_$location",
							array(
								'title' => __( 'Border', 'bgtfw' ),
								'panel' => "bgtfw_menu_location_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-grid-view',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_margin_$location",
							array(
								'title' => __( 'Margin', 'bgtfw' ),
								'panel' => "bgtfw_menu_location_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-editor-outdent',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_padding_$location",
							array(
								'title' => __( 'Padding', 'bgtfw' ),
								'panel' => "bgtfw_menu_location_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-editor-indent',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_standard_item_$location",
							array(
								'title' => __( 'Standard Display', 'bgtfw' ),
								'panel' => "bgtfw_menu_items_$location",
								'capability' => 'edit_theme_options',

							)
						);

						$panel = new Boldgrid_Framework_Customizer_Panel(
							$wp_customize,
							"bgtfw_menu_items_active_item_$location",
							array(
								'title' => __( 'Active Link Style', 'bgtfw' ),
								'panel' => "bgtfw_menu_items_$location",
								'capability' => 'edit_theme_options',
							)
						);

						$wp_customize->add_panel( $panel );

						Kirki::add_section(
							"bgtfw_menu_items_active_link_color_$location",
							array(
								'title' => __( 'Link Color', 'bgtfw' ),
								'panel' => "bgtfw_menu_items_active_item_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-art',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_active_link_background_$location",
							array(
								'title' => __( 'Background Color', 'bgtfw' ),
								'panel' => "bgtfw_menu_items_active_item_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-format-image',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_active_link_border_$location",
							array(
								'title' => __( 'Border', 'bgtfw' ),
								'panel' => "bgtfw_menu_items_active_item_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-grid-view',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_hover_item_$location",
							array(
								'title' => __( 'Hover Style', 'bgtfw' ),
								'panel' => "bgtfw_menu_items_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-admin-links',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_link_color_$location",
							array(
								'title' => __( 'Link Color', 'bgtfw' ),
								'panel' => "bgtfw_menu_items_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-art',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_border_$location",
							array(
								'title' => __( 'Border', 'bgtfw' ),
								'panel' => "bgtfw_menu_items_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-grid-view',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_spacing_$location",
							array(
								'title' => __( 'Spacing', 'bgtfw' ),
								'panel' => "bgtfw_menu_items_$location",
								'capability' => 'edit_theme_options',
								'icon' => 'dashicons-editor-outdent',
							)
						);
					}
				}
			} else {
				if ( $wp_customize ) {
					if ( 'radio' === $control['type'] ) {
						$setting = array();

						$setting['default'] = isset( $control['default'] ) ? $control['default'] : false;

						// Configs are set before page templates available can be determined, so check the controls and update choices.
						if ( empty( $control['choices'] ) && strpos( $control['default'], 'sidebar' ) !== false ) {
							$type = ( strpos( $control['settings'], 'blog' ) !== false ) ? 'post' : 'page';
							$control['choices'] = array_flip( get_page_templates( null, $type ) );
						}

						unset( $control['default'] );

						$setting['capability'] = isset( $control['capability'] ) ? $control['capability'] : 'edit_theme_options';
						unset( $control['capability'] );

						$setting['transport'] = isset( $control['transport'] ) ? $control['transport'] : 'refresh';
						unset( $control['transport'] );

						$setting['type'] = isset( $control['option_type'] ) ? $control['option_type'] : 'theme_mod';
						unset( $control['option_type'] );

						if ( isset( $control['theme_supports'] ) ) {
							$setting['theme_supports'] = $control['theme_supports'];
							unset( $control['theme_supports'] );
						}
						if ( isset( $control['sanitize_callback'] ) ) {
							$setting['sanitize_callback'] = $control['sanitize_callback'];
							unset( $control['sanitize_callback'] );
						}
						if ( isset( $control['sanitize_js_callback'] ) ) {
							$setting['sanitize_js_callback'] = $control['sanitize_js_callback'];
							unset( $control['sanitize_js_callback'] );
						}

						$wp_customize->add_setting( $control['settings'], $setting );

						$setting['setting'] = isset( $control['setting'] ) ? $control['setting'] : $control['settings'];
						unset( $control['setting'] );

						$wp_customize->add_control( $setting['setting'], $control );
					}
				}
			}
		}
	}

	/**
	 * Adds the bgtfw-control-styles stylesheet to the customizer
	 * controls.
	 *
	 * @since 2.0.0
	 */
	public function control_styles() {
		?>
		<style id="bgtfw-control-styles">
		</style>
		<?php
	}

	/**
	 * Enqueue General customizer helper styles.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $configs    An array of the theme framework configurations
	 */
	public function enqueue_styles() {
		wp_register_style(
			'bgtfw-menu-hamburgers',
			$this->configs['framework']['css_dir'] . 'hamburgers/hamburgers.min.css',
			array(),
			$this->configs['version']
		);

		wp_register_style(
			'bgtfw-menu-hovers',
			$this->configs['framework']['css_dir'] . 'hamburgers/hamburgers.min.css',
			array(),
			$this->configs['version']
		);

		wp_enqueue_style( 'bgtfw-menu-hamburgers' );

		wp_enqueue_style( 'boldgrid-customizer-controls-base',
			$this->configs['framework']['css_dir'] . 'customizer/font-family-controls.min.css' );

		wp_enqueue_style( 'boldgrid-customizer-controls-bundle',
			$this->configs['framework']['css_dir'] . 'base-controls-bundle.min.css' );
	}

	/**
	 * Enqueue General customizer helper scripts.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $configs    An array of the theme framework configurations
	 */
	public function custom_customize_enqueue() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'bgtfw-customizer-base-controls',
			$this->configs['framework']['js_dir'] . 'customizer/base-controls.min.js',
			array(
				'jquery',
				'customize-controls',
			),
			false,
			true
		);

		wp_register_script(
			'boldgrid-customizer-controls-base',
			$this->configs['framework']['js_dir'] . 'customizer/controls' . $suffix . '.js',
			array(
				'bgtfw-customizer-base-controls'
			),
			false,
			true
		);

		wp_localize_script(
			'boldgrid-customizer-controls-base',
			'Boldgrid_Thememod_Markup',
			array(
				'html' => $this->get_transferred_theme_mod_markup(),
				'transferred_theme_mods' => get_theme_mod( 'transferred_theme_mods', array() ),
				'siteurl' => get_option( 'siteurl' ),
			)
		);

		wp_register_script(
			'bgtfw-customizer-layout-homepage-controls',
			$this->configs['framework']['js_dir'] . 'customizer/layout/homepage/controls' . $suffix . '.js',
			array(
				'customize-controls',
				'boldgrid-customizer-controls-base',
			),
			false,
			true
		);

		wp_register_script(
			'bgtfw-customizer-layout-blog-blog-page-featured-images',
			$this->configs['framework']['js_dir'] . 'customizer/layout/blog/blog-page/layout/featured-images' . $suffix . '.js',
			array(
				'customize-controls',
				'boldgrid-customizer-controls-base',
			),
			false,
			true
		);

		wp_register_script(
			'bgtfw-customizer-header-layout-controls',
			$this->configs['framework']['js_dir'] . 'customizer/header-layout/controls' . $suffix . '.js',
			array(
				'customize-controls',
				'boldgrid-customizer-controls-base',
			),
			false,
			true
		);

		wp_register_script(
			'boldgrid-customizer-widget-preview',
			$this->configs['framework']['js_dir'] . 'customizer/widget-preview' . $suffix . '.js',
			array(
				'jquery',
				'hoverIntent',
			),
			false,
			true
		);

		wp_enqueue_script( 'bgtfw-customizer-base-controls' );

		$initialize = 'BOLDGRID = BOLDGRID || {};';
		$initialize .= 'BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};';
		$initialize .= 'BOLDGRID.CUSTOMIZER.data';

		$data = [
			'customizerOptions' => $this->configs['customizer-options'],
			'menu' => [
				'footerMenus' => $this->configs['menu']['footer_menus'],
			],
			'design' => [
				'blog' => [
					'posts' => [
						'mostRecentPost' => wp_get_recent_posts( array( 'numposts' => 1 ) )[0]['ID'],
					],
				],
			],
			'hoverColors' => include $this->configs['framework']['includes_dir'] . 'partials/hover-colors-only.php',
		];

		wp_localize_script( 'bgtfw-customizer-base-controls', $initialize, $data );

		wp_enqueue_script( 'bgtfw-customizer-header-layout-controls' );
		wp_enqueue_script( 'bgtfw-customizer-layout-blog-blog-page-featured-images' );
		wp_enqueue_script( 'bgtfw-customizer-layout-homepage-controls' );
		wp_enqueue_script( 'boldgrid-customizer-widget-preview' );
	}

	/**
	 * Enqueues scripts/styles for the live preview in customizer.
	 *
	 * @since  1.0.0
	 */
	public function live_preview() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Force minifies file for customizer.
		wp_register_script(
			'boldgrid-theme-customizer',
			$this->configs['framework']['js_dir'] . 'customizer/customizer.min.js',
			array(
				'boldgrid-front-end-scripts',
				'customize-preview',
			),
			$this->configs['version'],
			true
		);

		$initialize = 'BOLDGRID = BOLDGRID || {};';
		$initialize .= 'BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};';
		$initialize .= 'BOLDGRID.CUSTOMIZER.data';

		$data = [
			'customizerOptions' => $this->configs['customizer-options'],
			'menu' => [
				'footerMenus' => $this->configs['menu']['footer_menus'],
			],
			'hoverColors' => include $this->configs['framework']['includes_dir'] . 'partials/hover-colors-only.php',
		];

		wp_localize_script( 'boldgrid-theme-customizer', $initialize, $data );

		wp_register_script(
			'bgtfw-customizer-layout-blog-blog-page-live-preview',
			$this->configs['framework']['js_dir'] . 'customizer/layout/blog/blog-page/layout/live-preview' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer'
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-customizer-layout-blog-blog-page-layout-columns',
			$this->configs['framework']['js_dir'] . 'customizer/layout/blog/blog-page/layout/columns' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer'
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-customizer-header-layout-header-background',
			$this->configs['framework']['js_dir'] . 'customizer/header-layout/header-background' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer'
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-customizer-header-layout-header-container',
			$this->configs['framework']['js_dir'] . 'customizer/header-layout/header-container' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer'
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-customizer-footer-layout-footer-container',
			$this->configs['framework']['js_dir'] . 'customizer/footer-layout/footer-container' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer'
			),
			$this->configs['version'],
			true
		);

		wp_enqueue_script( 'boldgrid-theme-customizer' );
		wp_enqueue_script( 'bgtfw-customizer-layout-blog-blog-page-live-preview' );
		wp_enqueue_script( 'bgtfw-customizer-layout-blog-blog-page-layout-columns' );
		wp_enqueue_script( 'bgtfw-customizer-header-layout-header-background' );
		wp_enqueue_script( 'bgtfw-customizer-header-layout-header-container' );
		wp_enqueue_script( 'bgtfw-customizer-footer-layout-footer-container' );

		wp_enqueue_style(
			'boldgrid-theme-framework-customizer-css',
			$this->configs['framework']['css_dir'] . 'customizer' . $suffix . '.css',
			array(),
			$this->configs['version']
		);
	}

	/**
	 * This markup is used to allow the user to choose to revert any theme mod changes
	 *
	 * @return string
	 */
	public function get_transferred_theme_mod_markup() {
		return <<<HTML
		<div class="overlay-prompt">
			<div class="overlay-content">
				<h3>Transferred Theme Modifications</h3>
				<p>Following your recent theme switch, your previous theme modifications
				were transferred to this theme. You can choose to undo these changes or
				accept them and continue modifying your new theme.
				</p>
				<button class="button button-primary" type="button" id="accept-theme-mod-changes">Accept</button>
				<button id="undo-theme-mod-changes" type="button" class="button">Undo</button>
				<div class="spinner"></div>
			</div>
		</div>
HTML;
	}

	/**
	 * Add widget help.
	 *
	 * Let widgets tell the user to go to header and footer to change number of
	 * columns.
	 *
	 * @since 1.0.0
	 */
	public function add_widget_help( $wp_customize ) {
		// Todo Add Description to widgets to tell the user to go to header and footer to change columns.
	}

	/**
	 * Customizer_reorganization
	 * Remove control, Rename Panels
	 *
	 * @param Object $wp_customize The WP_Customize object.
	 */
	public function customizer_reorganization( $wp_customize ) {

		// Move Homepage Settings to the Layouts Panel.
		if ( $wp_customize->get_section( 'static_front_page' ) ) {
			$wp_customize->get_section( 'static_front_page' )->title = 'Homepage';
			$wp_customize->get_section( 'static_front_page' )->priority = 1;
			$wp_customize->get_section( 'static_front_page' )->panel = 'bgtfw_design_panel';
		}

		// Move and Rename Site Identity to Site Title & Logo.
		if ( $section = $wp_customize->get_section( 'title_tagline' ) ) {
			$section->title = __( 'Logo & Icon', 'bgtfw' );
			$section->panel = 'bgtfw_header';
			$section->priority = 7;
		}

		if ( $tagline = $wp_customize->get_control( 'blogdescription' ) ) {
			$tagline->section = 'bgtfw_tagline';
		}

		if ( $title = $wp_customize->get_control( 'blogname' ) ) {
			$title->section = 'bgtfw_site_title';
		}

		if ( $header_image = $wp_customize->get_section( 'header_image' ) ) {
			$header_image->title = __( 'Background', 'bgtfw' );
			$header_image->panel = 'bgtfw_header';
			$header_image->priority = 12;
		}

		if ( $section = $wp_customize->get_section( 'custom_css' ) ) {
			$section->title = __( 'CSS/JS Editor', 'bgtfw' );
		}

		if ( $control = $wp_customize->get_control( 'custom_css' ) ) {
			$control->description = __( 'Add custom CSS for this theme.', 'bgtfw' );
		}

		// Remove Addition Control that conflict with site title.
		$wp_customize->remove_control( 'header_textcolor' );
		$wp_customize->remove_control( 'display_header_text' );
	}

	/**
	 * Set blogname theme mod to postMessage for instant previews.
	 *
	 * @since  1.0.0
	 */
	public function blog_name( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	}

	/**
	 * Set tagline theme mod to postMessage for instant previews.
	 *
	 * @since  1.0.0
	 */
	public function blog_description( $wp_customize ) {
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	}

	/**
	 * Create the theme mod settings for text contrast.
	 *
	 * @since  1.0.0
	 */
	public function set_text_contrast( $wp_customize ) {
		$wp_customize->add_setting( 'boldgrid_light_text', array(
			'default'    => $this->configs['customizer-options']['colors']['light_text'],
			'type' => 'theme_mod',
		) );
		$wp_customize->add_setting( 'boldgrid_dark_text', array(
			'default'    => $this->configs['customizer-options']['colors']['dark_text'],
			'type' => 'theme_mod',
		) );
	}

	/**
	 * Adds panels to WordPress customizer.
	 *
	 * @since 2.0.0
	 *
	 * @param Object $wp_customize The WP_Customize object.
	 */
	public function add_panels( $wp_customize ) {
		$wp_customize->register_panel_type( 'Boldgrid_Framework_Customizer_Panel' );
		foreach ( $this->configs['customizer']['panels'] as $name => $panel ) {
			$panel = new Boldgrid_Framework_Customizer_Panel( $wp_customize, $name, $panel );
			$wp_customize->add_panel( $panel );
		}

		$wp_customize->register_section_type( 'Boldgrid_Framework_Customizer_Section' );
		foreach ( $this->configs['customizer']['sections'] as $name => $section ) {
			$section = new Boldgrid_Framework_Customizer_Section( $wp_customize, $name, $section );
			$wp_customize->add_section( $section );
		}
	}

	/**
	 * Add the Header Panel to the WordPress Customizer.  This also
	 * adds the controls we need for the custom CSS and custom JS
	 * textareas.
	 *
	 * @since 1.0.0
	 * @param Object $wp_customize The WP_Customize object.
	 */
	public function header_panel( $wp_customize ) {

		// Registers our custom section type and controls.
		$wp_customize->register_control_type( 'Boldgrid_Framework_Customizer_Control_Palette_Selector' );
		$wp_customize->register_control_type( 'Boldgrid_Framework_Customizer_Control_Menu_Hamburgers' );

		add_filter( 'kirki_control_types', function( $controls ) {
			$controls['bgtfw-palette-selector'] = 'Boldgrid_Framework_Customizer_Control_Palette_Selector';
			$controls['bgtfw-menu-hamburgers'] = 'Boldgrid_Framework_Customizer_Control_Menu_Hamburgers';
			return $controls;
		} );
	}

	/**
	 * Add tutorials link to each section in the menus panel.
	 *
	 * @since 1.2
	 *
	 * @param string $wp_customize WP Customize.
	 */
	public function add_menu_description( $wp_customize ) {
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu ) {
			$menu_id = $menu->term_id;
			$section_id = 'nav_menu[' . $menu_id . ']';
			$section = $wp_customize->get_section( $section_id );

			if ( $section ) {
				$section->description = '<a target="_blank" class="boldgrid-icon-newtab" href="https://www.boldgrid.com/support/working-with-menus-in-boldgrid/">' . __( 'Menu Tutorial', 'bgtfw' ) . '</a>';
			}
		}
	}

	/**
	 * Render the custom CSS.
	 *
	 * @since 1.0.0
	 */
	public function custom_js_output() {
		echo '<script type="text/javascript" id="boldgrid-custom-js">' . get_theme_mod( 'custom_theme_js', '' ) . '</script>';
	}

	/**
	 * Adds styles to head give an array of key value pairs
	 * see example in Boldgrid_Framework_Customizer_Background::apply_background_styles
	 *
	 * WARNING: These styles are currently being removed when the customizer loads
	 * Doing this to prevent overrides to wordpress styles onchange
	 *
	 * @since 1.0.0
	 */
	public function add_head_styles() {
		$css_rules = apply_filters( 'boldgrid_add_head_styles', $css_rules = array() );
		print BoldGrid_Framework_Styles::convert_array_to_css( $css_rules, 'boldgrid-override-styles' );
	}
}
