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

		// Check that get_page_templates() method is available in the customizer.
		if ( ! function_exists( 'get_page_templates' ) ) {
			require_once ABSPATH . 'wp-admin/includes/theme.php';
		}

		// Adds the "Page Layout" control the "Layout" section.
		// Adds the "Layout" section to the WordPress customizer.
		Kirki::add_panel(
			'bgtfw_layout', array(
				'title'          => __( 'Layout' ),
				'description'    => __( 'This section controls the layout of pages and posts on your website.' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		// Adds the "Blog" section to the WordPress customizer "Layout" panel.
		Kirki::add_section(
			'bgtfw_layout_blog', array(
				'title'          => __( 'Blog' ),
				'panel'        => 'bgtfw_layout',
				'description'    => __( 'This section controls the layout of pages and posts on your website.' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		// Adds the "Blog" section to the WordPress customizer "Layout" panel.
		Kirki::add_section(
			'bgtfw_layout_page', array(
				'title'          => __( 'Page' ),
				'panel'        => 'bgtfw_layout',
				'description'    => __( 'This section controls the global layout of pages on your website.' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		$post_templates = array_flip( get_page_templates( null, 'post' ) );
		Kirki::add_field(
			'bgtfw_layout_page', array(
				'type'        => 'radio',
				'settings'    => 'bgtfw_layout_blog',
				'label'       => __( 'Default Global Layout', 'bgtfw' ),
				'section'     => 'bgtfw_layout_blog',
				'default'     => 'none',
				'priority'    => 10,
				'choices'     => $post_templates,
			)
		);

		$page_templates = array_flip( get_page_templates( null, 'page' ) );
		Kirki::add_field(
			'bgtfw_layout_page', array(
				'type'        => 'radio',
				'settings'    => 'bgtfw_layout_page',
				'label'       => __( 'Default Page Layout', 'bgtfw' ),
				'section'     => 'bgtfw_layout_page',
				'default'     => 'none',
				'priority'    => 10,
				'choices'     => $page_templates,
			)
		);

		// Tagline Typography Settings.
		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'typography',
				'transport'   => 'auto',
				'settings'    => 'bgtfw_tagline_typography',
				'label'       => esc_attr__( 'Typography', 'bgtfw' ),
				'section'     => 'bgtfw_tagline',
				'default'     => array(
					'font-family'    => 'Roboto',
					'variant'        => 'regular',
					'font-size'      => '42px',
					'line-height'    => '1.5',
					'letter-spacing' => '0',
					'subsets'        => array( 'latin-ext' ),
					'color'          => '#333333',
					'text-transform' => 'none',
					'text-align'     => 'left'
				),
				'priority'    => 10,
				'output'      => array(
					array(
						'element' => '.site-branding .site-description',
					),
				),
			)
		);

		// Adds the "Header" section to the WordPress customizer.
		Kirki::add_panel(
			'bgtfw_header', array(
				'title'          => __( 'Header', 'bgtfw' ),
				'description'    => __( 'This section controls the appearance of your website\'s Header.', 'bgtfw' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		// Adds the "Layout" section to the WordPress customizer "Header" panel.
		Kirki::add_section(
			'bgtfw_header_layout', array(
				'title'          => __( 'Layout' ),
				'panel'        => 'bgtfw_header',
				'description'    => __( 'Choose from different layouts for your website\'s Header' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		// Fixed header toggle - Allows header to switch between fixed/static.
		Kirki::add_field(
			'bgtfw', array(
				'type'        => 'switch',
				'transport'   => 'postMessage',
				'settings'    => 'bgtfw_fixed_header',
				'label'       => esc_attr__( 'Sticky Header', 'bgtfw' ),
				'section'     => 'bgtfw_header_layout',
				'default'     => false,
				'priority'    => 10,
			)
		);

		// Header width control - used with header positioned on left or right.
		Kirki::add_field(
			'bgtfw', array(
				'type'        => 'slider',
				'settings'    => 'bgtfw_header_width',
				'transport'   => 'auto',
				'label'       => esc_attr__( 'Header Width', 'bgtfw' ),
				'section'     => 'bgtfw_header_layout',
				'default'     => 400,
				'choices'     => array(
					'min'  => '0',
					'max'  => '600',
					'step' => '1',
				),
				'active_callback' => array(
					array(
						'setting'  => 'bgtfw_header_layout_position',
						'operator' => '!=',
						'value'    => 'header-top',
					),
				),
				'output' => array(
					array(
						'media_query' => '@media only screen and (min-width : 768px)',
						'element'  => '.flexbox .header-left .site-header, .flexbox .header-right .site-header',
						'property' => 'flex',
						'value_pattern' => '0 0 $px',
					),
					array(
						'media_query' => '@media only screen and (min-width : 768px)',
						'element'  => '.flexbox .header-left .site-content, .flexbox .header-left.header-fixed .site-footer, .flexbox .header-right .site-content, .flexbox .header-right.header-fixed .site-footer',
						'property' => 'width',
						'value_pattern' => 'calc(100% - $px)',
					),
					array(
						'media_query' => '@media only screen and (min-width : 768px)',
						'element'  => '.flexbox .header-right.header-fixed .site-header, .flexbox .header-left.header-fixed .site-header, .header-right .wp-custom-header, .header-left .wp-custom-header, .header-right .site-header, .header-left .site-header, .header-left #masthead, .header-right #masthead',
						'property' => 'width',
						'value_pattern' => '$px',
					),
					array(
						'media_query' => '@media only screen and (min-width : 768px)',
						'element'  => '.flexbox .header-right.header-fixed .site-footer, .flexbox .header-right.header-fixed .site-content',
						'property' => 'margin-right',
						'value_pattern' => '$px',
					),
					array(
						'media_query' => '@media only screen and (min-width : 768px)',
						'element'  => '.flexbox .header-left.header-fixed .site-footer, .flexbox .header-left.header-fixed .site-content',
						'property' => 'margin-left',
						'value_pattern' => '$px',
					),
				),
			)
		);
/**
		// Header height control - used with header positioned on top.
		Kirki::add_field(
			'bgtfw', array(
				'type'        => 'slider',
				'settings'    => 'bgtfw_header_height',
				'transport'   => 'auto',
				'label'       => esc_attr__( 'Header Height', 'bgtfw' ),
				'section'     => 'bgtfw_header_layout',
				'default'     => 200,
				'choices'     => array(
					'min'  => '0',
					'max'  => '600',
					'step' => '1',
				),
				'output' => array(
					array(
						'media_query' => '@media only screen and (min-width : 768px)',
						'element'  => '.header-top #masthead',
						'property' => 'height',
						'value_pattern' => '$px',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'bgtfw_header_layout_position',
						'operator' => '==',
						'value'    => 'header-top',
					),
				),
			)
		);
*/
		// Adds the "Colors" section to the WordPress customizer "Header" panel.
		Kirki::add_section(
			'bgtfw_header_colors', array(
				'title'          => __( 'Colors' ),
				'panel'        => 'bgtfw_header',
				'description'    => esc_attr__( 'Change the colors used in your custom header.', 'bgtfw' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		// Header Background Color
		Kirki::add_field(
			'bgtfw', array(
				'type'        => 'color',
				'transport'   => 'auto',
				'settings'    => 'bgtfw_header_bg_color',
				'label'       => __( 'Background Color', 'bgtfw' ),
				'description' => esc_attr__( 'This controls the background color of your header.', 'bgtfw' ),
				'section'     => 'bgtfw_header_colors',
				'default'     => '#0088CC',
				'choices'     => array(
					'alpha' => true,
				),
				'output' => array(
					array(
						'element'  => '#masthead',
						'property' => 'background-color',
					),
				),
			)
		);

		// Header Text Color
		Kirki::add_field(
			'bgtfw', array(
				'type'        => 'color',
				'transport'   => 'auto',
				'settings'    => 'bgtfw_header_text_color',
				'label'       => __( 'Text Color', 'bgtfw' ),
				'description' => esc_attr__( 'This controls the primary color of text used inside of the header.  This will not impact things like your menu, site title, or tagline if you have selected different colors.', 'bgtfw' ),
				'section'     => 'bgtfw_header_colors',
				'default'     => '#0088CC',
				'choices'     => array(
					'alpha' => true,
				),
				'output' => array(
					array(
						'element'  => '#masthead',
						'property' => 'color',
					),
				),
			)
		);

		// Adds the "Colors" section to the WordPress customizer "Header" panel.
		Kirki::add_section(
			'bgtfw_site_title', array(
				'title'          => __( 'Site Title' ),
				'panel'        => 'bgtfw_header',
				'description'    => esc_attr__( "Change your site title and it's appearance.", 'bgtfw' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		// Tagline Typography Settings.
		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'typography',
				'transport'   => 'auto',
				'settings'    => 'bgtfw_site_title_typography',
				'label'       => esc_attr__( 'Typography', 'bgtfw' ),
				'section'     => 'bgtfw_site_title',
				'default'     => array(
					'font-family'    => 'Roboto',
					'variant'        => 'regular',
					'font-size'      => '42px',
					'line-height'    => '1.5',
					'letter-spacing' => '0',
					'subsets'        => array( 'latin-ext' ),
					'color'          => '#333333',
					'text-transform' => 'none',
					'text-align'     => 'left'
				),
				'priority'    => 10,
				'output'      => array(
					array(
						'element' => '.site-title > a',
					),
				),
			)
		);

		// Main Typography Settings.
		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'typography',
				'transport'   => 'auto',
				'settings'    => 'bgtfw_body_typography',
				'label'       => esc_attr__( 'Typography', 'bgtfw' ),
				'section'     => 'body_typography',
				'default'     => array(
					'font-family'    => 'Roboto',
					'variant'        => '100',
					'font-size'      => '18px',
					'line-height'    => '1.4',
					'letter-spacing' => '0',
					'subsets'        => array( 'latin-ext' ),
					'color'          => '#333333',
					'text-transform' => 'none',

				),
				'priority'    => 10,
				'output'      => array(
					array(
						'element' => 'body, p, .site-content, .site-footer',
					),
				),
			)
		);

		// Adds the "Colors" section to the WordPress customizer "Header" panel.
		Kirki::add_section(
			'bgtfw_tagline', array(
				'title'          => __( 'Tagline' ),
				'panel'        => 'bgtfw_header',
				'description'    => esc_attr__( "Change your site's tagline, and it's appearance.", 'bgtfw' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		// Adds the "Footer" panel to the WordPress customizer.
		Kirki::add_panel(
			'bgtfw_footer', array(
				'title'          => __( 'Footer', 'bgtfw' ),
				'description'    => __( 'This section controls the appearance of your website\'s Footer.', 'bgtfw' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
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

		// Adds the "Layout" section to the WordPress customizer "Footer" panel.
		Kirki::add_section(
			'bgtfw_footer_layout', array(
				'title'          => __( 'Layout', 'bgtfw' ),
				'panel'        => 'bgtfw_footer',
				'description'    => esc_attr__( 'Change the layout of your site\'s footer.', 'bgtfw' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		// Adds the enable/diable toggle for footer the "Layout" section of the "Footer" panel.
		Kirki::add_field(
			'bgtfw',
			array(
				'type' => 'switch',
				'settings' => 'boldgrid_enable_footer',
				'label' => __( 'Enable Footer', 'bgtfw' ),
				'section' => 'bgtfw_footer_layout',
				'default' => true,
				'priority' => 5,
			)
		);

		// Adds the "Widgets" section to the WordPress customizer "Footer" panel.
		Kirki::add_section(
			'bgtfw_footer_widgets', array(
				'title'          => __( 'Widgets', 'bgtfw' ),
				'panel'        => 'bgtfw_footer',
				'description'    => esc_attr__( 'Adjust your footer\'s widget sections.', 'bgtfw' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		Kirki::add_field(
			'bgtfw',
			array(
				'type'        => 'custom',
				'settings'     => 'boldgrid_footer_widget_help',
				'section'     => 'bgtfw_footer_widgets',
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
				'section'     => 'bgtfw_footer_widgets',
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
				'label'       => __( 'Footer Widget Areas', 'bgtfw' ),
				'description' => __( 'Select the number of footer widget columns you wish to display.', 'bgtfw' ),
				'type'        => 'number',
				'settings'    => 'boldgrid_footer_widgets',
				'priority'    => 80,
				'default'     => 0,
				'transport'   => 'auto',
				'choices'     => array(
					'min'  => 0,
					'max'  => 6,
					'step' => 1,
				),
				'section'     => 'bgtfw_footer_widgets',
				'partial_refresh' => array(
					'boldgrid_footer_widgets' => array(
						'selector'        => '#footer-widget-area',
						'render_callback' => function() {
							return $this->widget_row( 'footer', null );
						},
					),
				),
			)
		);

		// Adds the "Colors" section to the WordPress customizer "Footer" panel.
		Kirki::add_section(
			'bgtfw_footer_colors', array(
				'title'          => __( 'Colors' ),
				'panel'          => 'bgtfw_footer',
				'description'    => esc_attr__( 'Change the colors used in your custom footer.', 'bgtfw' ),
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
			)
		);

		// Footer Background Color.
		Kirki::add_field(
			'bgtfw', array(
				'type'        => 'color',
				'transport'   => 'auto',
				'settings'    => 'bgtfw_footer_bg_color',
				'label'       => __( 'Background Color', 'bgtfw' ),
				'description' => esc_attr__( 'This controls the background color of your footer.', 'bgtfw' ),
				'section'     => 'bgtfw_footer_colors',
				'default'     => '#0088CC',
				'choices'     => array(
					'alpha' => true,
				),
				'output' => array(
					array(
						'element'  => '.site-footer',
						'property' => 'background-color',
					),
				),
			)
		);
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
			'boldgrid-customizer-controls-base',
			$this->configs['framework']['js_dir'] . 'customizer/controls' . $suffix . '.js',
			array(
				'jquery',
				'customize-controls'
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
				'boldgrid-customizer-controls-base'
			),
			false,
			true
		);

		wp_register_script(
			'bgtfw-customizer-header-layout-controls',
			$this->configs['framework']['js_dir'] . 'customizer/header-layout/controls' . $suffix . '.js',
			array(
				'customize-controls',
				'boldgrid-customizer-controls-base'
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
				'boldgrid-customizer-controls-base'
			),
			false,
			true
		);

		wp_register_script(
			'boldgrid-customizer-widget-preview',
			$this->configs['framework']['js_dir'] . 'customizer/widget-preview' . $suffix . '.js',
			array(
				'jquery',
				'hoverIntent'
			),
			false,
			true
		);

		wp_localize_script(
			'boldgrid-customizer-required-helper',
			'BOLDGRID_Customizer_Required',
			$this->configs['customizer-options']['required']
		);

		wp_enqueue_script( 'boldgrid-customizer-required-helper' );
		wp_enqueue_script( 'bgtfw-customizer-header-layout-controls' );
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

		// Move Homepage Settings to the Layouts Panel.
		if ( $wp_customize->get_section( 'static_front_page' ) ) {
			$wp_customize->get_section( 'static_front_page' )->title = 'Homepage';
			$wp_customize->get_section( 'static_front_page' )->priority = 5;
			$wp_customize->get_section( 'static_front_page' )->panel = 'bgtfw_layout';
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

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_layout_homepage_sidebar' , array(
			'type'      => 'theme_mod',
			'default'   => 'no-sidebar',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_layout_homepage_sidebar', array(
			'label'       => esc_html__( 'Default Homepage Layout', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 30,
			'choices'     => array_flip( get_page_templates( null, 'post' ) ),
			'section'     => 'static_front_page',
			'active_callback' => function() {
				return get_option( 'show_on_front', 'posts' ) === 'posts' ? true : false;
			},
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
			'section'     => 'bgtfw_footer_layout',
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with basically no extra effort.
		$wp_customize->add_setting( 'bgtfw_header_layout_position' , array(
			'type'      => 'theme_mod',
			'default'   => 'header-top',
			'transport'   => 'postMessage',
		) );

		// Uses the 'radio' type in WordPress.
		$wp_customize->add_control( 'bgtfw_header_layout_position', array(
			'label'       => __( 'Header Position', 'bgtfw' ),
			'type'        => 'radio',
			'priority'    => 10,
			'choices'     => array(
				'header-top'   =>  esc_attr__( 'Header on Top', 'bgtfw' ),
				'header-left' => esc_attr__( 'Header on Left', 'bgtfw' ),
				'header-right'  =>  esc_attr__( 'Header on Right', 'bgtfw' ),
			),
			'section'     => 'bgtfw_header_layout',
		) );

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
	public function footer_widget_html() {
		$this->widget_row( 'footer' );
	}

	/**
	 * Generate a row of widgets to add to theme template.
	 *
	 * This is used to generate the markup for "dynamic" widget rows.  The prefix should
	 * match the theme_mod responsible
	 */
	public function widget_row( $prefix, $columns = null ) {
		if ( is_null( $columns ) ) {
			$columns = get_theme_mod( "boldgrid_{$prefix}_widgets", 0 );
		}

		if ( $columns > 0 ) {
			?>
				<div id="<?php echo $prefix; ?>-widget-area" class="bgtfw-widget-row">
					<?php
						for ( $i = 1; $i <= $columns; $i++ ) {
							bgtfw_widget( "{$prefix}-{$i}", true );
						}
					?>
				</div><!-- <?php echo $prefix; ?>-widget-area ends -->
			<?php
		}
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
	public function header_widget_html() {
		$this->widget_row( 'header' );
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
