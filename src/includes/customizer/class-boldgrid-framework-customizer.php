<?php
/**
 * Class: Boldgrid_Framework_Customizer.
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
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class BoldGrid_Framework_Customizer.
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
	 * BGTFW Scripts helper.
	 *
	 * @since  2.0.3
	 * @access protected
	 * @var    BoldGrid_Framework_Scripts $scripts BGTFW Scripts helper.
	 */
	protected $scripts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	public function __construct( $configs ) {

		// Ensure defaults are processed on customize load.
		$format        = new BoldGrid_Framework_Starter_Content( $configs );
		$this->configs = $format->set_configs( $configs );
		$this->scripts = new BoldGrid_Framework_Scripts( $configs );
		$this->presets = new BoldGrid_Framework_Customizer_Presets( $configs );
	}

	/**
	 * Add all kirki controls.
	 *
	 * @since 1.5.3
	 */
	public function kirki_controls() {
		global $wp_customize;
		remove_theme_support( 'widgets-block-editor' );

		foreach ( $this->configs['customizer']['controls'] as $control ) {
			if ( isset( $control['type'] ) && 'radio' !== $control['type'] ) {
				Kirki::add_field( 'bgtfw', $control );

				if ( strpos( $control['settings'], 'bgtfw_menu_' ) !== false &&
					( strpos( $control['settings'], 'main' ) !== false &&
						strpos( $control['settings'], 'sticky-main' ) === false ) &&
							$wp_customize ) {

					$menus = $this->configs['menu']['locations'];

					foreach ( $menus as $location => $description ) {
						$panel = new Boldgrid_Framework_Customizer_Panel(
							$wp_customize,
							"bgtfw_menu_location_$location",
							array(
								'title'      => $description,
								'panel'      => 'bgtfw_menus_panel',
								'capability' => 'edit_theme_options',
							)
						);

						$wp_customize->add_panel( $panel );

						$panel = new Boldgrid_Framework_Customizer_Panel(
							$wp_customize,
							"bgtfw_menu_items_$location",
							array(
								'title'      => __( 'Menu Items', 'bgtfw' ),
								'panel'      => "bgtfw_menu_location_$location",
								'capability' => 'edit_theme_options',
								'icon'       => 'dashicons-networking',
								'priority'   => 10,
							)
						);

						$wp_customize->add_panel( $panel );

						$section = new Boldgrid_Framework_Customizer_Section(
							$wp_customize,
							"bgtfw_menu_hamburgers_$location",
							array(
								'title'       => __( 'Hamburger Style', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the hamburger menu style for your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/changing-the-mobile-menu-button-style/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_location_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-menu',
								'priority'    => 20,
							)
						);

						$wp_customize->add_section( $section );

						Kirki::add_section(
							"bgtfw_menu_typography_$location",
							array(
								'title'       => __( 'Font', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the font for your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-menus-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_location_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-editor-textcolor',
								'priority'    => 30,
							)
						);

						Kirki::add_section(
							"bgtfw_menu_background_$location",
							array(
								'title'       => __( 'Background', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the background for your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-change-the-menu-background/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_location_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-format-image',
								'priority'    => 40,
							)
						);

						Kirki::add_section(
							"bgtfw_menu_border_$location",
							array(
								'title'       => __( 'Border', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the border of your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-change-the-menu-border/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_location_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-grid-view',
								'priority'    => 50,
							)
						);

						Kirki::add_section(
							"bgtfw_menu_margin_$location",
							array(
								'title'       => __( 'Margin', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the margins of your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/adding-margins-to-menu-locations/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_location_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-editor-outdent',
								'priority'    => 60,
							)
						);

						Kirki::add_section(
							"bgtfw_menu_padding_$location",
							array(
								'title'       => __( 'Padding', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the padding of your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-adjust-the-menu-padding/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_location_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-editor-indent',
								'priority'    => 70,
							)
						);

						Kirki::add_section(
							"bgtfw_menu_visibility_$location",
							array(
								'title'       => __( 'Device Visibility', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Adjust what devices can see your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-use-device-visibility-with-menus/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_location_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-welcome-view-site',
								'priority'    => 80,
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_standard_item_$location",
							array(
								'title'      => esc_html__( 'Standard Display', 'bgtfw' ),
								'panel'      => "bgtfw_menu_items_$location",
								'capability' => 'edit_theme_options',

							)
						);

						$panel = new Boldgrid_Framework_Customizer_Panel(
							$wp_customize,
							"bgtfw_menu_items_active_item_$location",
							array(
								'title'       => esc_html__( 'Active Link Style', 'bgtfw' ),
								// Translators: 1. Description.
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the active link style for your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/changing-the-active-link-style-in-menus/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_items_$location",
								'capability'  => 'edit_theme_options',
								'priority'    => 10,
							)
						);

						$wp_customize->add_panel( $panel );

						Kirki::add_section(
							"bgtfw_menu_items_active_link_color_$location",
							array(
								'title'      => esc_html__( 'Link Color', 'bgtfw' ),
								'panel'      => "bgtfw_menu_items_active_item_$location",
								'capability' => 'edit_theme_options',
								'icon'       => 'dashicons-art',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_active_link_background_$location",
							array(
								'title'      => esc_html__( 'Background Color', 'bgtfw' ),
								'panel'      => "bgtfw_menu_items_active_item_$location",
								'capability' => 'edit_theme_options',
								'icon'       => 'dashicons-format-image',
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_active_link_border_$location",
							array(
								'title'      => esc_html__( 'Border', 'bgtfw' ),
								'panel'      => "bgtfw_menu_items_active_item_$location",
								'capability' => 'edit_theme_options',
								'icon'       => 'dashicons-grid-view',
							)
						);

						$section = new Boldgrid_Framework_Customizer_Section(
							$wp_customize,
							"bgtfw_menu_items_hover_item_$location",
							array(
								'title'       => esc_html__( 'Hover Style', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the hover style for links in your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/changing-the-menu-link-styles/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_items_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-admin-links',
								'priority'    => 20,
							)
						);

						$wp_customize->add_section( $section );

						Kirki::add_section(
							"bgtfw_menu_items_link_color_$location",
							array(
								'title'       => esc_html__( 'Link Color', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the primary color for links in your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/changing-the-menu-link-styles/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_items_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-art',
								'priority'    => 30,
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_border_$location",
							array(
								'title'       => esc_html__( 'Border', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the menu borders in your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/changing-the-menu-link-styles/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_items_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-grid-view',
								'priority'    => 40,
							)
						);

						Kirki::add_section(
							"bgtfw_menu_items_spacing_$location",
							array(
								'title'       => esc_html__( 'Spacing', 'bgtfw' ),
								/* translators: %s: a menu location's description. */
								'description' => '<div class="bgtfw-description"><p>' . sprintf( esc_html__( 'Change the menu spacing in your %s location.', 'bgtfw' ), $description ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-change-the-spacing-for-menu-links/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></div></div>',
								'panel'       => "bgtfw_menu_items_$location",
								'capability'  => 'edit_theme_options',
								'icon'        => 'dashicons-editor-outdent',
								'priority'    => 50,
							)
						);
					}
				}
			} else {
				if ( $wp_customize ) {
					if ( isset( $control['type'] ) && 'radio' === $control['type'] ) {
						$setting = array();

						$setting['default'] = isset( $control['default'] ) ? $control['default'] : false;

						// Configs are set before page templates available can be determined, so check the controls and update choices.
						if ( empty( $control['choices'] ) && ( strpos( $control['section'], 'sidebar' ) !== false || strpos( $control['settings'], 'sidebar' ) !== false ) ) {
							$type               = ( strpos( $control['settings'], 'blog' ) !== false ) ? 'post' : 'page';
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
							$sanitize = $control['sanitize_callback'];
							unset( $control['sanitize_callback'] );
						} else {
							$sanitize = 'sanitize_html_class';
						}

						if ( isset( $control['sanitize_js_callback'] ) ) {
							$setting['sanitize_js_callback'] = $control['sanitize_js_callback'];
							unset( $control['sanitize_js_callback'] );
						}

						$wp_customize->add_setting( $control['settings'], array_merge( $setting, array( 'sanitize_callback' => $sanitize ) ) );

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
		<style id="bgtfw-control-styles"></style>
		<?php
	}

	/**
	 * Enqueue General customizer helper styles.
	 *
	 * @since    1.0.0
	 *
	 * @global   boolean   $is_edge    Is Edge.
	 *
	 * @access   protected
	 * @var      string    $configs    An array of the theme framework configurations
	 */
	public function enqueue_styles() {
		global $is_edge;

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

		// The regex css selectors in this file cause issues in MSEdge. Remove the font preview.
		if ( ! $is_edge ) {
			wp_enqueue_style(
				'boldgrid-customizer-controls-base',
				$this->configs['framework']['css_dir'] . 'customizer/font-family-controls.min.css',
				array(),
				$this->configs['version']
			);
		}

		wp_enqueue_style(
			'boldgrid-customizer-controls-bundle',
			$this->scripts->get_webpack_url( $this->configs['framework']['css_dir'], 'customizer/base-controls-bundle.min.css' ),
			array(),
			$this->configs['version']
		);

		wp_enqueue_style(
			'kirki-control-styles',
			$this->configs['framework']['root_uri'] . '/includes/kirki/controls/css/styles.css',
			array(),
			$this->configs['version']
		);
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
			$this->scripts->get_webpack_url( $this->configs['framework']['js_dir'], 'customizer/base-controls.min.js' ),
			array(
				'jquery',
				'customize-controls',
				'wp-i18n',
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'boldgrid-customizer-controls-base',
			$this->configs['framework']['js_dir'] . 'customizer/controls' . $suffix . '.js',
			array(
				'bgtfw-customizer-base-controls',
				'wp-i18n',
			),
			$this->configs['version'],
			true
		);

		wp_localize_script(
			'boldgrid-customizer-controls-base',
			'Boldgrid_Thememod_Markup',
			array(
				'html'                   => $this->get_transferred_theme_mod_markup(),
				'transferred_theme_mods' => get_theme_mod( 'transferred_theme_mods', array() ),
				'siteurl'                => get_option( 'siteurl' ),
			)
		);

		wp_register_script(
			'bgtfw-customizer-layout-homepage-controls',
			$this->configs['framework']['js_dir'] . 'customizer/layout/homepage/controls' . $suffix . '.js',
			array(
				'customize-controls',
				'boldgrid-customizer-controls-base',
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-customizer-layout-blog-blog-page-featured-images',
			$this->configs['framework']['js_dir'] . 'customizer/layout/blog/blog-page/layout/featured-images' . $suffix . '.js',
			array(
				'customize-controls',
				'boldgrid-customizer-controls-base',
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'boldgrid-customizer-widget-preview',
			$this->configs['framework']['js_dir'] . 'customizer/widget-preview' . $suffix . '.js',
			array(
				'jquery',
				'hoverIntent',
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-multislider',
			$this->scripts->get_webpack_url( $this->configs['framework']['js_dir'], 'multislider/multiSlider.js' ),
			array( 'jquery', 'boldgrid-customizer-controls-base' ),
			$this->configs['version'],
			true
		);

		wp_enqueue_script( 'bgtfw-multislider' );

		wp_enqueue_script( 'bgtfw-customizer-base-controls' );

		$initialize  = 'BOLDGRID = BOLDGRID || {};';
		$initialize .= 'BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};';
		$initialize .= 'BOLDGRID.CUSTOMIZER.data';

		$posts = wp_get_recent_posts( array( 'numposts' => 1 ) );
		$data  = array(
			'customizerOptions' => $this->configs['customizer-options'],
			'menu'              => array(
				'footerMenus' => $this->configs['menu']['footer_menus'],
			),
			'design'            => array(
				'blog'        => array(
					'posts' => array(
						'mostRecentPost' => ! empty( $posts[0]['ID'] ) ? $posts[0]['ID'] : null,
					),
				),
				'woocommerce' => array(
					'shopUrl' => esc_js(
						function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : get_option( 'siteurl' )
					),
				),
			),
			'hoverColors'       => include $this->configs['framework']['includes_dir'] . 'partials/hover-colors-only.php',
			'loadingTitle'      => __( 'Please wait while the Customizer loads...', 'bgtfw' ),
		);

		wp_localize_script( 'bgtfw-customizer-base-controls', $initialize, $data );
		wp_localize_script( 'bgtfw-customizer-base-controls', $this->scripts->get_asset_path(), array( $this->configs['framework']['root_uri'] ) );

		wp_enqueue_script( 'jquery-ui-accordion' );
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
			$this->scripts->get_webpack_url( $this->configs['framework']['js_dir'], 'customizer/customizer.min.js' ),
			array(
				'boldgrid-front-end-scripts',
				'customize-preview',
				'jquery-ui-widget',
				'jquery-ui-slider',
			),
			$this->configs['version'],
			true
		);

		$initialize  = 'BOLDGRID = BOLDGRID || {};';
		$initialize .= 'BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};';
		$initialize .= 'BOLDGRID.CUSTOMIZER.data';

		$data = array(
			'customizerOptions' => $this->configs['customizer-options'],
			'menu'              => array(
				'footerMenus' => $this->configs['menu']['footer_menus'],
			),
			'hoverColors'       => include $this->configs['framework']['includes_dir'] . 'partials/hover-colors-only.php',
		);

		wp_localize_script( 'boldgrid-theme-customizer', $initialize, $data );

		wp_register_script(
			'bgtfw-customizer-layout-blog-blog-page-live-preview',
			$this->configs['framework']['js_dir'] . 'customizer/layout/blog/blog-page/layout/live-preview' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer',
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-customizer-layout-blog-blog-page-layout-columns',
			$this->configs['framework']['js_dir'] . 'customizer/layout/blog/blog-page/layout/columns' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer',
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-customizer-header-layout-header-background',
			$this->configs['framework']['js_dir'] . 'customizer/header-layout/header-background' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer',
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-customizer-header-layout-header-container',
			$this->configs['framework']['js_dir'] . 'customizer/header-layout/header-container' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer',
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw-customizer-footer-layout-footer-container',
			$this->configs['framework']['js_dir'] . 'customizer/footer-layout/footer-container' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer',
			),
			$this->configs['version'],
			true
		);

		wp_register_script(
			'bgtfw_pages_blog_posts_layout_layout',
			$this->configs['framework']['js_dir'] . 'customizer/layout/blog/posts/layout/layout' . $suffix . '.js',
			array(
				'boldgrid-theme-customizer',
			),
			$this->configs['version'],
			true
		);

		wp_enqueue_script( 'boldgrid-theme-customizer' );
		wp_localize_script( 'boldgrid-theme-customizer', $this->scripts->get_asset_path(), array( $this->configs['framework']['root_uri'] ) );

		wp_enqueue_script( 'bgtfw-customizer-layout-blog-blog-page-live-preview' );
		wp_enqueue_script( 'bgtfw-customizer-layout-blog-blog-page-layout-columns' );
		wp_enqueue_script( 'bgtfw-customizer-header-layout-header-background' );
		wp_enqueue_script( 'bgtfw-customizer-footer-layout-footer-container' );
		wp_enqueue_script( 'bgtfw_pages_blog_posts_layout_layout' );

		wp_enqueue_style(
			'boldgrid-theme-framework-customizer-css',
			$this->configs['framework']['css_dir'] . 'customizer' . $suffix . '.css',
			array(),
			$this->configs['version']
		);
	}

	/**
	 * This markup is used to allow the user to choose to revert any theme mod changes.
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
	 * Let widgets tell the user to go to header and footer to change number of columns.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize $wp_customize WP_Customize Object.
	 */
	public function add_widget_help( $wp_customize ) {
		// Todo Add Description to widgets to tell the user to go to header and footer to change columns.
	}

	/**
	 * Customizer_reorganization.
	 *
	 * Remove control, Rename Panels.
	 *
	 * @param Object $wp_customize The WP_Customize object.
	 */
	public function customizer_reorganization( $wp_customize ) {

		// Move Homepage Settings to the Layouts Panel.
		if ( $wp_customize->get_section( 'static_front_page' ) ) {
			$section              = $wp_customize->get_section( 'static_front_page' );
			$section->title       = esc_html__( 'Homepage', 'bgtfw' );
			$section->description = '<div class="bgtfw-description"><p>' . esc_html__( 'Change your site\'s Homepage settings', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/using-the-homepage-settings/" target="_blank"><span class="dashicons"></span>Help</a></div></div>';
			$section->priority    = 5;
			$section->panel       = 'bgtfw_design_panel';
		}

		// Add colors section description.
		if ( $wp_customize->get_section( 'colors' ) ) {
			$section              = $wp_customize->get_section( 'colors' );
			$section->title       = esc_html__( 'Color Palette', 'bgtfw' );
			$section->description = '<div class="bgtfw-description"><p>' . __( 'Drag a color to a new spot to change what parts of the website are that color.<a href="#" data-action="open-color-picker"><span class="dashicons dashicons-admin-customizer"></span><strong>Click a color</strong></a> to change it. Use the "Suggest Palettes" button to get new color suggestions, and press the lock icons to freeze colors in place.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/customizing-the-color-palette/" target="_blank"><span class="dashicons"></span>Help</a></div></div>';
		}

		// Move and Rename Site Identity to Site Title & Logo.
		if ( $wp_customize->get_section( 'title_tagline' ) ) {
			$section              = $wp_customize->get_section( 'title_tagline' );
			$section->title       = esc_html__( 'Logo & Icon', 'bgtfw' );
			$section->description = '<div class="bgtfw-description"><p>' . esc_html__( 'Change your site\'s logo and favicon.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-your-site-title-logo-and-tagline-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>';
			$section->panel       = 'bgtfw_header';
			$section->priority    = 7;
		}

		// Change tagline control's section.
		if ( $wp_customize->get_control( 'blogdescription' ) ) {
			$control          = $wp_customize->get_control( 'blogdescription' );
			$control->section = 'bgtfw_tagline';
		}

		// Change site_title control's section.
		if ( $wp_customize->get_control( 'blogname' ) ) {
			$control          = $wp_customize->get_control( 'blogname' );
			$control->section = 'bgtfw_site_title';
		}

		// Move and rename wp custom header_image section.
		if ( $wp_customize->get_section( 'header_image' ) ) {
			$section              = $wp_customize->get_section( 'header_image' );
			$section->title       = esc_html__( 'Background', 'bgtfw' );
			$section->description = '<div class="bgtfw-description"><p>' . esc_html__( 'Change the background of your site\'s custom header.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-change-the-header-background-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>';
			$section->panel       = 'bgtfw_header';
			$section->priority    = 12;
		}

		// Change custom_css section title to add JS editor.
		if ( $wp_customize->get_section( 'custom_css' ) ) {
			$section              = $wp_customize->get_section( 'custom_css' );
			$section->title       = esc_html__( 'CSS/JS Editor', 'bgtfw' );
			$section->description = '<div class="bgtfw-description"><p>' . esc_html__( 'Manage custom CSS and JS code for your site.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/using-the-custom-css-and-js-editor-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>';
		}

		if ( $wp_customize->get_control( 'header_image' ) ) {
			$control           = $wp_customize->get_control( 'header_image' );
			$control->priority = 12;
		}

		if ( $wp_customize->get_control( 'header_video' ) ) {
			$control            = $wp_customize->get_control( 'header_video' );
			$control->transport = 'refresh';
		}

		if ( $wp_customize->get_control( 'external_header_video' ) ) {
			$control            = $wp_customize->get_control( 'external_header_video' );
			$control->transport = 'refresh';
		}

		// Add menus panel description.
		if ( $wp_customize->get_panel( 'menus' ) ) {
			$panel              = $wp_customize->get_panel( 'menus' );
			$panel->description = '<div class="bgtfw-description"><p>' . esc_html__( 'Manage the menus used on your site.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-menus-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>';
		}

		// Add widgets panel description.
		if ( $wp_customize->get_panel( 'widgets' ) ) {
			$panel              = $wp_customize->get_panel( 'widgets' );
			$panel->description = '<div class="bgtfw-description"><p>' . esc_html__( 'A Widget is a small block that performs a specific function. We have provided some prefilled widget areas for you. You can hover over the Widget Areas below to see where they are located on the page.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-header-and-footer-widgets-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>';
		}

		// Add description to custom_css control.
		if ( $wp_customize->get_control( 'custom_css' ) ) {
			$control              = $wp_customize->get_control( 'custom_css' );
			$control->description = esc_html__( 'Add custom CSS for this theme.', 'bgtfw' );
		}

		if ( $wp_customize->get_setting( 'custom_logo' ) ) {
			$setting = $wp_customize->get_setting( 'custom_logo' );
			$setting->transport = 'refresh';
		}

		// Remove Addition Control that conflict with site title.
		$wp_customize->remove_control( 'header_textcolor' );
		$wp_customize->remove_control( 'display_header_text' );
	}

	/**
	 * Set blogname theme mod to postMessage for instant previews.
	 *
	 * @since  1.0.0
	 *
	 * @param WP_Customize $wp_customize WP_Customize Object.
	 */
	public function blog_name( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	}

	/**
	 * Set tagline theme mod to postMessage for instant previews.
	 *
	 * @since  1.0.0
	 *
	 * @param WP_Customize $wp_customize WP_Customize Object.
	 */
	public function blog_description( $wp_customize ) {
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	}

	/**
	 * Create the theme mod settings for text contrast.
	 *
	 * @since  1.0.0
	 *
	 * @param WP_Customize $wp_customize WP_Customize Object.
	 */
	public function set_text_contrast( $wp_customize ) {
		$wp_customize->add_setting(
			'boldgrid_light_text',
			array(
				'default'           => $this->configs['customizer-options']['colors']['light_text'],
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_setting(
			'boldgrid_dark_text',
			array(
				'default'           => $this->configs['customizer-options']['colors']['dark_text'],
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_setting(
			'hide_boldgrid_attribution',
			array(
				'default'           => false,
				'type'              => 'theme_mod',
				'sanitize_callback' => function( $checked ) {
					return ( ( isset( $checked ) && true === (bool) $checked ) ? true : false );
				},
			)
		);
		$wp_customize->add_setting(
			'hide_wordpress_attribution',
			array(
				'default'           => false,
				'type'              => 'theme_mod',
				'sanitize_callback' => function( $checked ) {
					return ( ( isset( $checked ) && true === (bool) $checked ) ? true : false );
				},
			)
		);
		$wp_customize->add_setting(
			'hide_host_attribution',
			array(
				'default'           => false,
				'type'              => 'theme_mod',
				'sanitize_callback' => function( $checked ) {
					return ( ( isset( $checked ) && true === (bool) $checked ) ? true : false );
				},
			)
		);
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

		// Register custom sections.
		$wp_customize->register_section_type( 'Boldgrid_Framework_Customizer_Section_Upsell' );
		$wp_customize->register_section_type( 'Boldgrid_Framework_Customizer_Widgets_Section' );

		// Add section.
		$wp_customize->add_section(
			new Boldgrid_Framework_Customizer_Widgets_Section(
				$wp_customize,
				'bgtfw_widgets_section',
				array(
					'section_description' => esc_html__( 'You can add and remove widget areas in your header and footer layouts:', 'bgtfw' ),
					'header_title'        => esc_html__( 'Header Layout', 'bgtfw' ),
					'footer_title'        => esc_html__( 'Footer Layout', 'bgtfw' ),
					'panel'               => 'widgets',
					'priority'            => 9999,
				)
			)
		);

		// Add upsell section.
		$wp_customize->add_section(
			new Boldgrid_Framework_Customizer_Section_Upsell(
				$wp_customize,
				'bgtfw-upsell',
				array(
					'title'        => esc_html__( 'Get More Features', 'bgtfw' ),
					'upsell_text'  => esc_html__( 'Upgrade Crio', 'bgtfw' ),
					'upsell_title' => esc_html__( 'Upgrade Crio', 'bgtfw' ),
					'upsell_url'   => 'https://boldgrid.com/wordpress-themes/crio/?source=customize-main',
					'priority'     => 0,
				)
			)
		);
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
		$wp_customize->register_control_type( 'Boldgrid_Framework_Customizer_Control_Sortable_Accordion' );
		$wp_customize->register_control_type( 'Boldgrid_Framework_Customizer_Control_Dropdown_Menu' );

		add_filter(
			'kirki_control_types',
			function( $controls ) {
				$controls['bgtfw-palette-selector']   = 'Boldgrid_Framework_Customizer_Control_Palette_Selector';
				$controls['bgtfw-menu-hamburgers']    = 'Boldgrid_Framework_Customizer_Control_Menu_Hamburgers';
				$controls['bgtfw-sortable-accordion'] = 'Boldgrid_Framework_Customizer_Control_Sortable_Accordion';
				$controls['bgtfw-dropdown-menu']      = 'Boldgrid_Framework_Customizer_Control_Dropdown_Menu';
				return $controls;
			}
		);
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
			$menu_id    = $menu->term_id;
			$section_id = 'nav_menu[' . $menu_id . ']';
			$section    = $wp_customize->get_section( $section_id );

			if ( $section ) {
				$section->description = '<a target="_blank" class="boldgrid-icon-newtab dashicons-before dashicons-external" href="https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-menus-in-boldgrid-crio/">' . __( 'Menu Tutorial', 'bgtfw' ) . '</a>';
			}
		}
	}

	/**
	 * Render the custom CSS.
	 *
	 * @since 1.0.0
	 */
	public function custom_js_output() {
		echo '<script type="text/javascript" id="boldgrid-custom-js">' . get_theme_mod( 'custom_theme_js', '' ) . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Adds styles to head give an array of key value pairs.
	 * see example in Boldgrid_Framework_Customizer_Background::apply_background_styles.
	 *
	 * WARNING: These styles are currently being removed when the customizer loads.
	 * Doing this to prevent overrides to WordPress styles onchange
	 *
	 * @since 1.0.0
	 */
	public function add_head_styles() {
		$css_rules = apply_filters( 'boldgrid_add_head_styles', $css_rules = array() );
		$id        = 'boldgrid-override-styles';
		$css       = BoldGrid_Framework_Styles::convert_array_to_css( $css_rules, $id );
		Boldgrid_Framework_Customizer_Generic::add_inline_style( $id, $css );
	}

	/**
	 * Add Custom Column Width control.
	 *
	 * @since 2.7.0
	 */
	public function register_colwidth_control( $wp_customize ) {
		require_once $this->configs['framework']['includes_dir']
			. 'control/class-boldgrid-framework-control-col-width.php';
		$wp_customize->add_setting(
			'bgtfw_header_layout_custom_col_width',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => function( $value, $settings ) {
					return $value;
				},
			)
		);
		$wp_customize->add_control( new Boldgrid_Framework_Control_Col_Width( $this->configs, $wp_customize ) );
	}

	/**
	 * Add a nonce for Customizer for column nonces.
	 *
	 * @since 2.7.0
	 */
	public function header_column_nonces( $nonces ) {
		$nonces['bgtfw-header-columns'] = wp_create_nonce( 'bgtfw_header_columns' );
		return $nonces;
	}

	/**
	 * WP Ajax Header Columns.
	 *
	 * @since 2.7.0
	 */
	public static function wp_ajax_bgtfw_header_columns() {
		check_ajax_referer( 'bgtfw_header_columns', 'headerColumnsNonce' );
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( -1 );
		}

		$layout = $_POST['customHeaderLayout'];

		$markup = Boldgrid_Framework_Control_Col_Width::get_updated_markup( $layout );

		wp_send_json_success( array(
			'layout' => $layout,
			'markup' => $markup,
		) );

	}
}
