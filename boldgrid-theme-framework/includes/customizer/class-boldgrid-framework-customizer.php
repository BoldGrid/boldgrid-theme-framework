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
		$this->configs = $configs;
	}

	/**
	 * Add all kitki controls.
	 *
	 * @since 1.5.3
	 */
	public function kirki_controls() {

		global $boldgrid_theme_framework;
		$configs = $boldgrid_theme_framework->get_configs();

		foreach( $this->configs['customizer']['controls'] as $control ) {
			Kirki::add_field( 'bgtfw', $control );
		}

		foreach( $this->configs['customizer']['sections'] as $name => $section ) {
			Kirki::add_section( $name, $section );
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
		wp_enqueue_style( 'boldgrid-customizer-controls-base',
			$this->configs['framework']['css_dir'] . 'customizer/font-family-controls.min.css' );
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
			$this->configs['framework']['js_dir'] . 'customizer/base-controls' . $suffix . '.js',
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
			'boldgrid-customizer-required-helper',
			$this->configs['framework']['js_dir'] . 'customizer/required' . $suffix . '.js',
			array(
				'jquery',
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

		wp_localize_script(
			'boldgrid-customizer-required-helper',
			'BOLDGRID_Customizer_Required',
			$this->configs['customizer-options']['required']
		);

		wp_enqueue_script( 'bgtfw-customizer-base-controls' );
		wp_enqueue_script( 'boldgrid-customizer-required-helper' );
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

		wp_register_script( 'boldgrid-theme-customizer',
			$this->configs['framework']['js_dir'] . 'customizer/customizer' . $suffix . '.js',
			array(
				'jquery',
				'customize-preview',
			),
			$this->configs['version'],
			true
		);

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
	 * Site Logo Customizer Control
	 *
	 * Responsible for displaying the site customizer logo control.
	 *
	 * @since  1.0.0
	 */
	public function site_logo( $wp_customize ) {

		$config = $this->configs['customizer-options']['site_logo'];

		if ( true === $config ) {

			$wp_customize->add_setting( 'boldgrid_logo_setting', array(
				'default'       => '', // Default setting/value to save
				'capability'    => 'edit_theme_options', // Optional. Special permissions for accessing this setting.
				'transport'     => 'refresh', // What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
			) );

			$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'boldgrid_logo_setting', array(
				'section'     => 'title_tagline',
				'label'       => __( 'Site Logo' ),
				'priority'    => 50,
				'description' => 'If you have a logo to use for your site, it will replace your Site Title.  Press "Remove" to remove your site logo and use a Site Title instead.',
				'flex_width'  => true, // Allow any width, making the specified value recommended. False by default.
				'flex_height' => true, // Require the resulting image to be exactly as tall as the height attribute (default).
				'width'       => 520,
				'height'      => 160,
			) ) );
		}
	}

	/**
	 * Not in use
	 * This code was created to change the crop size to twice the recommended to
	 * allow for unpixelated resizing.
	 *
	 * @since 1.0
	 */
	public function change_logo_crop_size() {
		$callback = function ( $payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {

			global $boldgrid_overwrote_cropping;
			if ( $boldgrid_overwrote_cropping ) {
				return null;
			}

			$boldgrid_overwrote_cropping = true;

			$correct_context = ! empty( $_POST['context'] ) ? ( 'boldgrid_logo_setting' === $_POST['context'] ) : false;
			$crop_action = ! empty( $_POST['action'] ) ? ( 'crop-image' === $_POST['action'] ) : false;
			$customizer_on = ! empty( $_POST['wp_customize'] ) ? true : false;

			if ( $crop_action && $customizer_on && $correct_context ) {
				return image_resize_dimensions( $orig_w * 2, $orig_h * 2, $dest_w, $dest_h, $crop );
			}
		};

		add_filter( 'image_resize_dimensions', $callback, 10, 6 );
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

		// Register custom panel type.
		$wp_customize->register_panel_type( 'Boldgrid_Framework_Customizer_Panel' );

		foreach( $this->configs['customizer']['panels'] as $name => $panel ) {
			$panel = new Boldgrid_Framework_Customizer_Panel( $wp_customize, $name, $panel );
			$wp_customize->add_panel( $panel );
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
		$wp_customize->register_section_type( 'Boldgrid_Framework_Customizer_Section' );

		// Register our custom control with Kirki
		$wp_customize->register_control_type( 'Boldgrid_Framework_Customizer_Control_Palette_Selector' );

		add_filter( 'kirki_control_types', function( $controls ) {
			$controls['bgtfw-palette-selector'] = 'Boldgrid_Framework_Customizer_Control_Palette_Selector';
			return $controls;
		} );

		// Add example section and controls to the middle (second) panel
		$wp_customize->add_section( 'bgtfw_pages_blog_blog_page_layout', array(
			'title' => 'Layout',
			'panel' => 'bgtfw_blog_blog_page_panel',
			'priority' => 2,
		));

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_pages_blog_blog_page_layout_content' , array(
			'type'      => 'theme_mod',
			'default'   => 'excerpt',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_pages_blog_blog_page_layout_content', array(
			'label'       => esc_html__( 'Post Content Display', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 40,
			'choices'     => array(
				'excerpt' => esc_attr__( 'Post Excerpt', 'bgtfw' ),
				'content' => esc_attr__( 'Full Content', 'bgtfw' ),
			),
			'section' => 'bgtfw_pages_blog_blog_page_layout',
		) );

		// Add example section and controls to the middle (second) panel
		$wp_customize->add_section( 'bgtfw_blog_blog_page_panel_sidebar', array(
			'title' => __( 'Sidebar', 'bgtfw' ),
			'panel' => 'bgtfw_blog_blog_page_panel',
			'priority' => 4,
		));

		// Add example section and controls to the middle (second) panel
		$wp_customize->add_section( 'bgtfw_pages_blog_posts_layout', array(
			'title' => 'Layout',
			'panel' => 'bgtfw_blog_posts_panel',
			'priority' => 2,
		));

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_pages_blog_posts_layout_layout' , array(
			'type'      => 'theme_mod',
			'default'   => 'container',
			'transport'   => 'postMessage',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_pages_blog_posts_layout_layout', array(
			'label'       => esc_html__( 'Layout', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 40,
			'choices'     => array(
				'container' => esc_attr__( 'Contained', 'bgtfw' ),
				'container-fluid' => esc_attr__( 'Full Width', 'bgtfw' ),
			),
			'section' => 'bgtfw_pages_blog_posts_layout',
		) );

		// Add example section and controls to the middle (second) panel
		$wp_customize->add_section( 'bgtfw_pages_blog_posts_sidebar', array(
			'title' => __( 'Sidebar', 'bgtfw' ),
			'panel' => 'bgtfw_blog_posts_panel',
			'priority' => 4,
		));

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_layout_blog' , array(
			'type'      => 'theme_mod',
			'default'   => 'no-sidebar',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_layout_blog', array(
			'label'       => esc_html__( 'Sidebar Display', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 10,
			'choices'     => array_flip( get_page_templates( null, 'post' ) ),
			'section'     => 'bgtfw_pages_blog_posts_sidebar',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_blog_blog_page_settings' , array(
			'type'      => 'theme_mod',
			'default'   => 'no-sidebar',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_blog_blog_page_settings', array(
			'label'       => esc_html__( 'Homepage Sidebar Display', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 10,
			'choices'     => array_flip( get_page_templates( null, 'post' ) ),
			'section'     => 'bgtfw_blog_blog_page_settings',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_blog_layout' , array(
			'type'      => 'theme_mod',
			'default'   => 'layout-1',
			'transport'   => 'postMessage',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_blog_layout', array(
			'label'       => esc_html__( 'Design', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 40,
			'choices'     => array(
				'design-1' => esc_attr__( 'Design 1', 'bgtfw' ),
				'design-2' => esc_attr__( 'Design 2', 'bgtfw' ),
				'design-3' => esc_attr__( 'Design 3', 'bgtfw' ),
				'design-4' => esc_attr__( 'Design 4', 'bgtfw' ),
			),
			'section' => 'bgtfw_pages_blog_blog_page_layout',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_blog_blog_page_sidebar' , array(
			'type'      => 'theme_mod',
			'default'   => 'no-sidebar',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_blog_blog_page_sidebar', array(
			'label'       => esc_html__( 'Homepage Sidebar Display', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 30,
			'choices'     => array_flip( get_page_templates( null, 'post' ) ),
			'section'     => 'static_front_page',
			'active_callback' => function() {
				return get_option( 'show_on_front', 'posts' ) === 'posts' ? true : false;
			},
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_blog_blog_page_sidebar2', array(
			'label'       => esc_html__( 'Sidebar Options', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 10,
			'choices'     => array_flip( get_page_templates( null, 'post' ) ),
			'section'     => 'bgtfw_blog_blog_page_panel_sidebar',
			'settings'    => 'bgtfw_blog_blog_page_sidebar',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_layout_blog_layout' , array(
			'type'      => 'theme_mod',
			'default'   => 'layout-1',
			'transport'   => 'postMessage',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_layout_blog_layout', array(
			'label'       => esc_html__( 'Homepage Blog Layout', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 40,
			'choices'     => array(
				'layout-1' => esc_attr__( 'Layout 1', 'bgtfw' ),
				'layout-2' => esc_attr__( 'Layout 2', 'bgtfw' ),
				'layout-3' => esc_attr__( 'Layout 3', 'bgtfw' ),
				'layout-4' => esc_attr__( 'Layout 4', 'bgtfw' ),
				'layout-5' => esc_attr__( 'Layout 5', 'bgtfw' ),
				'layout-6' => esc_attr__( 'Layout 6', 'bgtfw' ),
			),
			'section'     => 'static_front_page',
			'active_callback' => function() {
				return get_option( 'show_on_front', 'posts' ) === 'posts' ? true : false;
			},
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_layout_blog_layout', array(
			'label'       => esc_html__( 'Layout', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 40,
			'choices'     => array(
				'layout-1' => esc_attr__( 'Layout 1', 'bgtfw' ),
				'layout-2' => esc_attr__( 'Layout 2', 'bgtfw' ),
				'layout-3' => esc_attr__( 'Layout 3', 'bgtfw' ),
				'layout-4' => esc_attr__( 'Layout 4', 'bgtfw' ),
				'layout-5' => esc_attr__( 'Layout 5', 'bgtfw' ),
				'layout-6' => esc_attr__( 'Layout 6', 'bgtfw' ),
			),
			'section' => 'bgtfw_layout_blog',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_header_top_layouts' , array(
			'type'      => 'theme_mod',
			'default'   => 'layout-1',
			'transport'   => 'postMessage',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_header_top_layouts', array(
			'label'       => esc_html__( 'Layout', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 30,
			'choices'     => array(
				'layout-1' => esc_attr__( 'Layout 1', 'bgtfw' ),
				'layout-2' => esc_attr__( 'Layout 2', 'bgtfw' ),
				'layout-3' => esc_attr__( 'Layout 3', 'bgtfw' ),
				'layout-4' => esc_attr__( 'Layout 4', 'bgtfw' ),
				'layout-5' => esc_attr__( 'Layout 5', 'bgtfw' ),
				'layout-6' => esc_attr__( 'Layout 6', 'bgtfw' ),
			),
			'section'     => 'bgtfw_header_layout',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'header_container' , array(
			'type'      => 'theme_mod',
			'default'   => '',
			'transport'   => 'postMessage',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'header_container', array(
			'label'       => esc_html__( 'Header Container', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 30,
			'choices'     => array(
				'' => esc_attr__( 'Full Width', 'bgtfw' ),
				'container' => esc_attr__( 'Fixed Width', 'bgtfw' ),
			),
			'section'     => 'bgtfw_header_layout',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_footer_layouts' , array(
			'type'      => 'theme_mod',
			'default'   => 'layout-1',
			'transport'   => 'postMessage',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_footer_layouts', array(
			'label'       => esc_html__( 'Layout', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 10,
			'choices'     => array(
				'layout-1' => esc_attr__( 'Layout 1', 'bgtfw' ),
				'layout-2' => esc_attr__( 'Layout 2', 'bgtfw' ),
				'layout-3' => esc_attr__( 'Layout 3', 'bgtfw' ),
				'layout-4' => esc_attr__( 'Layout 4', 'bgtfw' ),
				'layout-5' => esc_attr__( 'Layout 5', 'bgtfw' ),
				'layout-6' => esc_attr__( 'Layout 6', 'bgtfw' ),
				'layout-7' => esc_attr__( 'Layout 7', 'bgtfw' ),
				'layout-8' => esc_attr__( 'Layout 8', 'bgtfw' ),
			),
			'section'     => 'boldgrid_footer_panel',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'footer_container' , array(
			'type'      => 'theme_mod',
			'default'   => '',
			'transport'   => 'postMessage',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'footer_container', array(
			'label'       => esc_html__( 'Footer Container', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 10,
			'choices'     => array(
				'' => esc_attr__( 'Full Width', 'bgtfw' ),
				'container' => esc_attr__( 'Fixed Width', 'bgtfw' ),
			),
			'section'     => 'boldgrid_footer_panel',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting(
			'bgtfw_header_layout_position',
			array(
				'type' => 'theme_mod',
				'default' => 'header-top',
				'transport' => 'postMessage',
			)
		);

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control(
			'bgtfw_header_layout_position',
			array(
				'label' => __( 'Header Position', 'bgtfw' ),
				'type' => 'radio',
				'priority' => 10,
				'choices' => array(
					'header-top' => esc_attr__( 'Header on Top', 'bgtfw' ),
					'header-left' => esc_attr__( 'Header on Left', 'bgtfw' ),
					'header-right' => esc_attr__( 'Header on Right', 'bgtfw' ),
				),
				'section' => 'bgtfw_header_layout',
			)
		);

		$config = $this->configs['customizer-options']['header_panel'];

		if ( true === $config ) {

			// It really doesn't matter if another plugin or the theme adds the same section; they will merge.
			$wp_customize->add_section( 'boldgrid_header_panel', array(
				'title'    => __( 'Header Settings', 'bgtfw' ),
				'panel' => 'boldgrid_other',
				'priority' => 120, // After all core sections.
			) );

			$header_widget_control = $this->configs['customizer-options']['header_controls']['widgets'];

			if ( true === $header_widget_control ) {

				// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
				$wp_customize->add_setting( 'boldgrid_header_widgets' , array(
					'type'      => 'theme_mod',
					'default'   => '0',
				) );

				// Uses the 'radio' type in WordPress.
				$wp_customize->add_control( 'boldgrid_header_widgets', array(
					'label'       => __( 'Header Widgets', 'bgtfw' ),
					'description' => __( 'Select the number of widget areas you wish to display', 'bgtfw' ) . ':',
					'type'        => 'radio',
					'priority'    => 10,
					'choices'     => array(
						'0'   => '0',
						'1'   => '1',
						'2'   => '2',
						'3'   => '3',
						'4'   => '4',
					),
					'section'     => 'boldgrid_header_panel',
				) );

				$header_settings = function ( $controls ) {
					$controls['boldgrid_header_widget_help'] = array(
						'type'        => 'custom',
						'setting'     => 'boldgrid_header_widget_help',
						'section'     => 'boldgrid_header_panel',
						'default'     => '<a class="button button-primary open-widgets-section">' . __( 'Continue to Widgets Section', 'bgtfw' ) . '</a>',
						'priority'    => 15,
						'description' => __( 'You can add widgets to your header from the widgets section.', 'bgtfw' ),
					);

					return $controls;
				};

				add_filter( 'kirki/controls', $header_settings );
			}
		}
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
