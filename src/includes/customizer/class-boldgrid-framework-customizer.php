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

		/* Adds "Advanced" top level panel option to customizer. */
		Kirki::add_panel(
			'boldgrid_other',
			array(
				'title'       => __( 'Advanced', 'boldgrid' ),
				'description' => 'Additional BoldGrid Options',
				'priority'    => 120,
			)
		);
		Kirki::add_section(
			'advanced_edit',
			array(
				'title' => __( 'Custom JS & CSS', 'bgtfw' ),
				'panel' => 'boldgrid_other',
				'capability' => 'edit_theme_options',
				'description' => __( 'This section allows you to modify features that are not menus or widgets.', 'bgtfw' ),
				'priority' => 250, // After all core sections.
			)
		);
		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'code',
				'transport' => 'postMessage',
				'settings'    => 'custom_theme_css',
				'label'       => __( 'Custom Theme CSS', 'bgtfw' ),
				'help'        => __( 'This adds live CSS to your website.', 'bgtfw' ),
				'description' => __( 'Add custom CSS for this theme.', 'bgtfw' ),
				'section'     => 'advanced_edit',
				'default'     => '.boldgrid-css{ background: white; }',
				'priority'    => 10,
				'choices'     => array(
					'language' => 'css',
					'theme'    => 'base16-dark',
					'height'   => 100,
				),
			)
		);
		Kirki::add_field(
			'custom_theme_js',
			array(
				'type'        => 'code',
				'settings'    => 'custom_theme_js',
				'label'       => __( 'Custom Theme JS' ),
				'help'        => __( 'This adds live JavaScript to your website.', 'bgtfw' ),
				'description' => __( 'Add custom javascript for this theme.', 'bgtfw' ),
				'section'     => 'advanced_edit',
				'default'     => "// jQuery('body');",
				'priority'    => 10,
				'choices'     => array(
					'language' => 'javascript',
					'theme'    => 'base16-dark',
					'height'   => 100,
				),
			)
		);

		// Footer Section.
		Kirki::add_section(
			'boldgrid_footer_panel',
			array(
				'title' => __( 'Footer Settings', 'bgtfw' ),
				'priority' => 130, // After all core sections.
				'panel' => 'boldgrid_other',
				'capability' => 'edit_theme_options',
				'description' => __( 'This section allows you to modify features that are not menus or widgets.', 'bgtfw' ),
			)
		);
		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'custom',
				'settings'     => 'boldgrid_attribution_heading',
				'label'       => __( 'Attribution Control', 'bgtfw' ),
				'section'     => 'boldgrid_footer_panel',
				'default'     => '',
				'priority'    => 20,
			)
		);
		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'checkbox',
				'settings'     => 'hide_boldgrid_attribution',
				'transport'   => 'refresh',
				'label'       => __( 'Hide BoldGrid Attribution', 'bgtfw' ),
				'section'     => 'boldgrid_footer_panel',
				'default'     => false,
				'priority'    => 30,
			)
		);
		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'checkbox',
				'settings'     => 'hide_wordpress_attribution',
				'transport'   => 'refresh',
				'label'       => __( 'Hide WordPress Attribution', 'bgtfw' ),
				'section'     => 'boldgrid_footer_panel',
				'default'     => false,
				'priority'    => 40,
			)
		);
		// If there is host attribution markup, add a control to hide it.
		if ( get_theme_mod( 'host_attribution' ) ) {
			Kirki::add_field(
				'bgtfw',
				array(
					'type'      => 'checkbox',
					'settings'  => 'hide_host_attribution',
					'transport' => 'refresh',
					'label'     => __( 'Hide Host Attribution', 'bgtfw' ),
					'section'   => 'boldgrid_footer_panel',
					'default'   => false,
					'priority'  => 40,
				)
			);
		}
		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'custom',
				'settings'     => 'boldgrid_footer_widget_help',
				'section'     => 'boldgrid_footer_panel',
				'default'     => '<a class="button button-primary open-widgets-section">' . __( 'Continue to Widgets Section', 'bgtfw' ) . '</a>',
				'priority'    => 90,
				'description' => __( 'You can add widgets to your footer from the widgets section.', 'bgtfw' ),
			)
		);
		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'custom',
				'settings'     => 'boldgrid_edit_footer_widget_help',
				'section'     => 'boldgrid_footer_panel',
				'default'     => '<a data-focus-section="sidebar-widgets-boldgrid-widget-3" class="button button-primary" href="#">' .
					__( 'Edit Footer Widgets', 'bgtfw' ) . '</a>',
				'priority'    => 70,
				'description' => __( 'You can edit your default footer widgets from the widget panel.', 'bgtfw' ),
				'required' => array(
					array(
						'settings' => 'boldgrid_enable_footer',
						'operator' => '==',
						'value' => true,
					),
				),
			)
		);
		Kirki::add_field(
			'bgtfw',
			array(
				'label'       => __( 'Footer Widget Columns', 'bgtfw' ),
				'description' => __( 'Select the number of footer widget columns you wish to display.', 'bgtfw' ),
				'type'        => 'radio',
				'settings'    => 'boldgrid_footer_widgets',
				'priority'    => 80,
				'choices'     => array(
					'0'   => '0',
					'1'   => '1',
					'2'   => '2',
					'3'   => '3',
					'4'   => '4',
				),
				'section'     => 'boldgrid_footer_panel',
			)
		);
		Kirki::add_field(
			'bgtfw',
			array(
				'type' => 'switch',
				'settings' => 'boldgrid_enable_footer',
				'label' => __( 'Enable Footer', 'bgtfw' ),
				'section' => 'boldgrid_footer_panel',
				'default' => true,
				'priority' => 5,
			)
		);

		// Background Controls.
		// Add Background Color Control to Pattern&Color of Background Image Section.
		Kirki::add_field(
			'bgtfw',
			array(
				'type' => 'color',
				'settings' => 'boldgrid_background_color',
				'label' => __( 'Background Color', 'bgtfw' ),
				'section' => 'background_image',
				'transport' => 'postMessage',
				'default' => $configs['customizer-options']['background']['defaults']['boldgrid_background_color'],
				'priority' => 1,
				'choices' => array(),
			)
		);

		// Add Background Vertical Position Control.
		Kirki::add_field( 'bgtfw', array(
				'type' => 'slider',
				'settings' => 'boldgrid_background_vertical_position',
				'label' => __( 'Vertical Background Position', 'bgtfw' ),
				'section' => 'background_image',
				'transport' => 'postMessage',
				'default' => $configs['customizer-options']['background']['defaults']['boldgrid_background_vertical_position'],
				'priority' => 16,
				'choices' => array(
					'min' => - 100,
					'max' => 100,
					'step' => 1,
				),
			)
		);

		// Add Background Horizontal Position Control.
		Kirki::add_field( 'bgtfw', array(
				'type' => 'slider',
				'settings' => 'boldgrid_background_horizontal_position',
				'label' => __( 'Horizontal Background Position', 'bgtfw' ),
				'section' => 'background_image',
				'transport' => 'postMessage',
				'default' => $configs['customizer-options']['background']['defaults']['boldgrid_background_horizontal_position'],
				'priority' => 17,
				'choices' => array(
					'min' => - 100,
					'max' => 100,
					'step' => 1,
				),
			)
		);

		/* Contact Blocks */
		if ( 'disabled' == $this->configs['template']['call-to-action'] ) {
			Kirki::add_field(
				'bgtfw',
				array(
					'type'        => 'repeater',
					'label'       => esc_attr__( 'Contact Details', 'bgtfw' ),
					'section'     => 'boldgrid_footer_panel',
					'priority'    => 10,
					'row_label' => array(
						'field' => 'contact_block',
						'type' => 'field',
						'value' => esc_attr__( 'Contact Block', 'bgtfw' ),
					),
					'settings'    => 'boldgrid_contact_details_setting',
					'default'     => $configs['customizer-options']['contact-blocks']['defaults'],
					'fields' => array(
						'contact_block' => array(
							'type'        => 'text',
							'label'       => esc_attr__( 'Text', 'bgtfw' ),
							'description' => esc_attr__( 'Enter the text to display in your contact details', 'bgtfw' ),
							'default'     => '',
						),
					),
				)
			);
		}
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

		wp_register_script( 'boldgrid-customizer-controls-base',
			$this->configs['framework']['js_dir'] . 'customizer/controls' . $suffix . '.js',
		array( 'jquery', 'customize-controls' ), false, true );

		wp_localize_script( 'boldgrid-customizer-controls-base', 'Boldgrid_Thememod_Markup',
			array(
				'html' => $this->get_transferred_theme_mod_markup(),
				'transferred_theme_mods' => get_theme_mod( 'transferred_theme_mods', array() ),
				'siteurl' => get_option( 'siteurl' ),
			)
		);

		wp_register_script( 'boldgrid-customizer-required-helper',
			$this->configs['framework']['js_dir'] . 'customizer/required' . $suffix . '.js',
		array( 'jquery', 'customize-controls', 'boldgrid-customizer-controls-base' ), false, true );

		wp_register_script( 'boldgrid-customizer-widget-preview',
			$this->configs['framework']['js_dir'] . 'customizer/widget-preview' . $suffix . '.js',
		array( 'jquery', 'hoverIntent' ), false, true );

		wp_localize_script( 'boldgrid-customizer-required-helper',
			'BOLDGRID_Customizer_Required',
			$this->configs['customizer-options']['required']
		);

		wp_enqueue_script( 'boldgrid-customizer-required-helper' );
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
			array( 'jquery', 'customize-preview' ),
		$this->configs['version'], true );

		wp_enqueue_script( 'boldgrid-theme-customizer' );

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
	 * @since  1.0.0
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

		if ( true === $this->configs['customizer-options']['advanced_panel'] ) {
			// Add an "other" Panel.
			$wp_customize->add_panel( 'boldgrid_other', array(
				'title'       => __( 'Advanced', 'boldgrid' ),
				'description' => 'Additional BoldGrid Options',
				'priority'    => 120,
			) );
		}
			// Add an "other" Panel.
			$wp_customize->add_panel( 'boldgrid_other', array(
				'title'       => __( 'Layout', 'boldgrid' ),
				'description' => 'Site Layout Options',
				'priority'    => 70,
			) );
		// Move Static Front page to the Other Section.
		if ( $wp_customize->get_section( 'static_front_page' ) ) {
			$wp_customize->get_section( 'static_front_page' )->panel    = 'boldgrid_other';
		}

		// Rename Site Identity to Site Title & Logo.
		$wp_customize->get_section( 'title_tagline' )->title    = __( 'Site Title & Logo', 'bgtfw' );

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
	 * Add the Header Panel to the WordPress Customizer.  This also
	 * adds the controls we need for the custom CSS and custom JS
	 * textareas.
	 *
	 * @since 1.0.0
	 * @param Object $wp_customize The WP_Customize object.
	 */
	public function header_panel( $wp_customize ) {

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

			$header_custom_html = $this->configs['customizer-options']['header_controls']['custom_html'];

			if ( true === $header_custom_html ) {

				// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
				$wp_customize->add_setting( 'boldgrid_header_html' , array(
					'type'      => 'theme_mod',
				) );

				// Uses the `textarea` type added in WordPress 4.0.
				$wp_customize->add_control( 'boldgrid_header_html', array(
					'label'       => __( 'Custom Header HTML', 'bgtfw' ),
					'description' => __( 'Add your custom HTML for your header here', 'bgtfw' ) . ':',
					'type'        => 'textarea',
					'priority'    => 20,
					'section'     => 'boldgrid_header_panel',
				) );

			}
		}

	}

	/**
	 * Display Footer HTML
	 *
	 * @since 1.0.0
	 */
	public function display_footer_html() {

		echo wp_kses_post( get_theme_mod( 'boldgrid_footer_html', '' ) );

	}

	/**
	 * Display Header HTML
	 *
	 * @since 1.0.0
	 */
	public function display_header_html() {

		echo wp_kses_post( get_theme_mod( 'boldgrid_header_html', '' ) );

	}

	/**
	 * Add the Advanced Panel to the WordPress Customizer.  This also
	 * adds the controls we need for the custom CSS and custom JS
	 * textareas.
	 *
	 * @since 1.0.0
	 */
	public function advanced_panel( $wp_customize ) {

		$panel = $this->configs['customizer-options']['advanced_panel'];

		if ( true === $panel ) {
			// Which config to check?
			$css_editor = $this->configs['customizer-options']['advanced_controls']['css_editor'];

			// If active add control.
			if ( true === $css_editor ) {

			}

			// Which config to check?
			$js_editor = $this->configs['customizer-options']['advanced_controls']['js_editor'];

			// If active add control.
			if ( true === $js_editor ) {

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
				$section->description =
					'<a target="_blank" class="boldgrid-icon-newtab" href="https://www.boldgrid.com/support/working-with-menus-in-boldgrid/">' .
					__( 'Menu Tutorial', 'bgtfw' ) . '</a>';
			}
		}
	}

	/**
	 * Render the custom CSS.
	 *
	 * @since 1.0.0
	 */
	public function custom_css_output() {

		echo '<style type="text/css" id="boldgrid-custom-css">' . get_theme_mod( 'custom_theme_css', '' ) . '</style>';

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
	 * Footer Widget Columns.
	 *
	 * This will add the footer widget section to a BoldGrid
	 * theme.  This accepts $columns, which should be a number
	 * of columns to include.  Accepted values should be 1
	 * through 4, or leave empty for default behavior.
	 *
	 * @param    string $columns Number of columns that should display in footer.
	 * @since    1.0.0
	 */
	public function footer_widget_html( $columns = null ) {

		$columns = get_theme_mod( 'boldgrid_footer_widgets' );

		switch ( $columns ) :

			case 0 :

				return;

				break;

			case 1 :
				?>
				<div id="footer-widget-area" class="row">
					<div class="footer-widgets-1 col-md-12">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
					</div><!-- footer-widget-area ends -->
					<?php
					break;

			case 2 :
				?>
				<div id="footer-widget-area" class="row">
					<div class="footer-widgets-1 col-md-6">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
					<div class="footer-widgets-2 col-md-6">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
					</div><!-- footer-widget-area ends -->
					<?php
					break;

			case 3 :
				?>
				<div id="footer-widget-area" class="row">
					<div class="footer-widgets-1 col-md-4">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
					<div class="footer-widgets-2 col-md-4">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
					<div class="footer-widgets-3 col-md-4">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
					</div><!-- footer-widget-area ends -->
					<?php
					break;

			default :
				?>
				<div id="footer-widget-area" class="row">
					<div class="footer-widgets-1 col-md-3 col-sm-6">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
					<div class="footer-widgets-2 col-md-3 col-sm-6">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
					<div class="footer-widgets-3 col-md-3 col-sm-6">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
					<div class="footer-widgets-4 col-md-3 col-sm-6">
						<?php dynamic_sidebar( 'footer-4' ); ?>
					</div>
					</div><!-- footer-widget-area ends -->
					<?php
					break;

			endswitch;

	}

	/**
	 * Header Widget Columns.
	 *
	 * This will add the header widget section to a BoldGrid
	 * theme.  This accepts $columns, which should be a number
	 * of columns to include.  Accepted values should be 1
	 * through 4, or leave empty for default behavior.
	 *
	 * @return   html   Markup for the header widget area of a theme.
	 *
	 * @param   string $columns   Number of columns that should display in header.
	 *
	 * @package BoldGrid
	 * @since 1.0.0
	 */
	public function header_widget_html( $columns = null ) {

		$columns = get_theme_mod( 'boldgrid_header_widgets' );

		switch ( $columns ) :

			case 0 :

				return;

				break;

			case 1 :
				?>
				<div id="header-widget-area" class="row">
					<div class="header-widgets-1 col-xs-12">
						<?php dynamic_sidebar( 'header-1' ); ?>
					</div>
				</div><!-- header-widget-area ends -->
				<?php
				break;

			case 2 :
				?>
				<div id="header-widget-area" class="row">
					<div class="header-widgets-1 col-md-6">
						<?php dynamic_sidebar( 'header-1' ); ?>
					</div>
					<div class="header-widgets-2 col-md-6">
						<?php dynamic_sidebar( 'header-2' ); ?>
					</div>
				</div><!-- header-widget-area ends -->
				<?php
				break;

			case 3 :
				?>
				<div id="header-widget-area" class="row">
					<div class="header-widgets-1 col-md-4">
						<?php dynamic_sidebar( 'header-1' ); ?>
					</div>
					<div class="header-widgets-2 col-md-4">
						<?php dynamic_sidebar( 'header-2' ); ?>
					</div>
					<div class="header-widgets-3 col-md-4">
						<?php dynamic_sidebar( 'header-3' ); ?>
					</div>
				</div><!-- header-widget-area ends -->
				<?php
				break;

			case 4 :
				?>
				<div id="header-widget-area" class="row">
					<div class="header-widgets-1 col-md-3 col-sm-6">
						<?php dynamic_sidebar( 'header-1' ); ?>
					</div>
					<div class="header-widgets-2 col-md-3 col-sm-6">
						<?php dynamic_sidebar( 'header-2' ); ?>
					</div>
					<div class="header-widgets-3 col-md-3 col-sm-6">
						<?php dynamic_sidebar( 'header-3' ); ?>
					</div>
					<div class="header-widgets-4 col-md-3 col-sm-6">
						<?php dynamic_sidebar( 'header-4' ); ?>
					</div>
				</div><!-- header-widget-area ends -->
				<?php
				break;

		endswitch;

	}

	/**
	 * This adds a hook for the preview version of the site and a hook for the live version of the site
	 * so CSS or JS can be applied conditionally.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_preview_hooks() {

		if ( ! empty( $GLOBALS['wp_customize'] ) ) {

			do_action( 'wp_head_preview' );

		} else {

			do_action( 'wp_head_live' );

		}

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
		// Allow user to mod css.
		$css_rules = apply_filters( 'boldgrid_add_head_styles', $css_rules = array() );

		// Print styles.
		print BoldGrid_Framework_Styles::convert_array_to_css( $css_rules, 'boldgrid-override-styles' );
	}

	/**
	 * Add overlay on first visit
	 *
	 * @since 1.0.0
	 */
	public function add_help_overlay() {
		// @todo: enqueue properly.
		?>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<div id='boldgrid-customizer-overlay-help' class='overlay-help'>
			<div class='overlay-help-inside'>
				<div class='overlay-help-text'>
					<i id='close-help-popup' class="fa fa-times-circle fa-2x pull-right"></i>
					<h2><?php _e( 'Using the Customizer', 'bgtfw' ); ?></h2>
					<p>
					<?php _e( 'BoldGrid sites are highly customizable. Each of the menu items to the left correspond with an area
					of your site. Become familiar with everything\'s location to make full use of your site\'s features.', 'bgtfw' ); ?>
					</p>
				</div>
				<img src="<?php echo esc_url( $this->configs['framework']['admin_asset_dir'] ); ?>img/boldgrid-overlay.jpg">
			</div>
		</div>
		<?php
	}

	/**
	 * Remove the additional CSS section, introduced in 4.7, from the Customizer.
	 *
	 * @param Object $wp_customize The WP_Customize_Manager object.
	 *
	 * @since 1.3.3
	 */
	public function remove_css_section( $wp_customize ) {
		$wp_customize->remove_section( 'custom_css' );
	}
}
