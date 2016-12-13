<?php
/**
 * Class: Boldgrid_Framework_Customizer_Footer
 *
 * This is the class responsible for adding the footer's functionality
 * to the footer.  It contains all controls for the custom panel in the
 * WordPress customizer under Advanced > Footer Settings.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Footer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Class: Boldgrid_Framework_Customizer_Footer
 *
 * This is the class responsible for adding the footer's functionality
 * to the footer.  It contains all controls for the custom panel in the
 * WordPress customizer under Advanced > Footer Settings.
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_Customizer_Footer {

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
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Add the Footer Panel to the WordPress Customizer.  This also
	 * adds the controls we need for the custom CSS and custom JS
	 * textareas.
	 *
	 * @since     1.0.0
	 *
	 * @param array $wp_customize WordPress customizer object.
	 */
	public function footer_panel( $wp_customize ) {

		$config = $this->configs['customizer-options']['footer_panel'];

		if ( true === $config ) {

			// It really doesn't matter if another plugin or the theme adds
			// the same section; they will merge.
			$wp_customize->add_section(
				'boldgrid_footer_panel',
				array(
					'title'    => __( 'Footer Settings', 'bgtfw' ),
					'priority' => 130, // After all core sections.
					'panel' => 'boldgrid_other',
					'description' => __( 'This section allows you to modify features that are not menus or widgets.', 'bgtfw' ),
				)
			);

			$footer_widget_control = $this->configs['customizer-options']['footer_controls']['widgets'];

			if ( true === $footer_widget_control ) {
				// 'theme_mod's are stored with the theme, so different themes can have
				// unique custom css rules with basically no extra effort.
				$wp_customize->add_setting(
					'boldgrid_footer_widgets',
					array(
						'type'      => 'theme_mod',
						'default'   => '0',
						'transport' => 'refresh',
					)
				);
				// Uses the 'radio' type in WordPress.
				$wp_customize->add_control(
					'boldgrid_footer_widgets',
					array(
						'label'       => __( 'Footer Widget Columns', 'bgtfw' ),
						'description' => __( 'Select the number of footer widget columns you wish to display.', 'bgtfw' ),
						'type'        => 'radio',
						'priority'    => 70,
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
					'',
					array(
						'type'        => 'custom',
						'settings'     => 'boldgrid_footer_widget_help',
						'section'     => 'boldgrid_footer_panel',
						'default'     => '<a class="button button-primary open-widgets-section">' . __( 'Continue to Widgets Section', 'bgtfw' ) . '</a>',
						'priority'    => 80,
						'description' => __( 'You can add widgets to your footer from the widgets section.', 'bgtfw' ),
					)
				);
				Kirki::add_field(
					'',
					array(
						'type'        => 'custom',
						'settings'     => 'boldgrid_edit_footer_widget_help',
						'section'     => 'boldgrid_footer_panel',
						'default'     => '<a data-focus-section="sidebar-widgets-boldgrid-widget-3" class="button button-primary" href="#">' .
							__( 'Edit Footer Widgets', 'bgtfw' ) . '</a>',
						'priority'    => 60,
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
			}

			$header_custom_html = $this->configs['customizer-options']['footer_controls']['custom_html'];

			if ( true === $header_custom_html ) {
				// 'theme_mod's are stored with the theme, so different themes
				// can have unique custom css rules with basically no extra effort.
				$wp_customize->add_setting(
					'boldgrid_footer_html',
					array(
						'type'      => 'theme_mod',
						'transport' => 'refresh',
					)
				);
				// Uses the `textarea` type added in WordPress 4.0.
				$wp_customize->add_control(
					'boldgrid_footer_html',
					array(
						'label'       => __( 'Custom Footer HTML', 'bgtfw' ),
						'description' => __( 'Add your custom HTML for your footer here', 'bgtfw' ),
						'type'        => 'textarea',
						'priority'    => 90,
						'section'     => 'boldgrid_footer_panel',
					)
				);
			}
		}

	}

	/**
	 * Adds the Contact Details section
	 *
	 * @since 1.3.5
	 */
	public function add_contact_control( $wp_customize ) {
		$year = date( 'Y' );
		$blogname = get_bloginfo( 'name' );

		Kirki::add_field( 'boldgrid_contact_details', array(
			'type'        => 'repeater',
			'label'       => esc_attr__( 'Contact Details', 'bgtfw' ),
			'section'     => 'boldgrid_footer_panel',
			'priority'    => 10,
			'row_label' => array(
					'type' => 'text',
					'value' => esc_attr__( 'Contact Block', 'bgtfw' ),
					'field' => 'text',
			),
			'settings'    => 'boldgrid_contact_details_setting',
			'default'     => array(
				array(
					'text' => esc_attr( "&copy;{$year} {$blogname}" ),
				),
				array(
					'text' => esc_attr( '202 Grid Blvd. Agloe, NY 12776' ),
				),
				array(
					'text' => esc_attr( '777-765-4321' ),
				),
				array(
					'text' => esc_attr( 'info@example.com' ),
				),
			),
			'fields' => array(
				'text' => array(
					'type'        => 'text',
					'label'       => esc_attr__( 'Text', 'bgtfw' ),
					'description' => esc_attr__( 'Enter the text to display in your contact details', 'bgtfw' ),
					'default'     => '',
				),
			)
		) );
	}

	/**
	 * This adds the group of controls in the customizer that are
	 * responsible for showing/hiding/editing the footer attribution
	 * links at the bottom of a user's page.
	 *
	 * @since 1.0.1
	 *
	 * @param array $wp_customize WordPress customizer object.
	 */
	public function add_attrbution_control( $wp_customize ) {

		$configs = $this->configs;
		Kirki::add_field(
			'',
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
			'',
			array(
				'type'        => 'checkbox',
				'settings'     => 'hide_boldgrid_attribution',
				'transport'   => 'postMessage',
				'label'       => __( 'Hide BoldGrid Attribution', 'bgtfw' ),
				'section'     => 'boldgrid_footer_panel',
				'default'     => false,
				'priority'    => 30,
			)
		);
		Kirki::add_field(
			'',
			array(
				'type'        => 'checkbox',
				'settings'     => 'hide_wordpress_attribution',
				'transport'   => 'postMessage',
				'label'       => __( 'Hide WordPress Attribution', 'bgtfw' ),
				'section'     => 'boldgrid_footer_panel',
				'default'     => false,
				'priority'    => 40,
			)
		);
		Kirki::add_field(
			'',
			array(
				'type'        => 'checkbox',
				'settings'     => 'hide_partner_attribution',
				'transport'   => 'postMessage',
				'label'       => __( 'Hide Partner Attribution', 'bgtfw' ),
				'section'     => 'boldgrid_footer_panel',
				'default'     => false,
				'priority'    => 50,
			)
		);
	}

	/**
	 *  Responsible for adding the attribution links to the footer of a BoldGrid theme.
	 *
	 *  @since     1.0.0
	 */
	public function attribution_display_action() {

		$theme_mods = '';
		$reseller_data = get_option( 'boldgrid_reseller', false );

		// If the user hasn't disabled the footer, add the links.
		if ( get_theme_mod( 'boldgrid_enable_footer', true ) ) {

			// BoldGrid.com Link.
			if ( ! get_theme_mod( 'hide_boldgrid_attribution' ) || is_customize_preview() ) {
				$theme_mods .= sprintf(
					'<span class="link boldgrid-attribution-link">%s <a href="%s" rel="nofollow" target="_blank">%s</a> | </span>',
					__( 'Built with', 'bgtfw' ),
					'http://boldgrid.com/',
					__( 'BoldGrid', 'bgtfw' )
				);
			}

			// WordPress.org Link.
			if ( ! get_theme_mod( 'hide_wordpress_attribution' ) || is_customize_preview() ) {
				$theme_mods .= sprintf(
					'<span class="link wordpress-attribution-link">%s <a href="%s" rel="nofollow" target="_blank">%s</a> | </span>',
					__( 'Powered by', 'bgtfw' ),
					'https://wordpress.org/',
					__( 'WordPress', 'bgtfw' )
				);
			}

			// Authorized Reseller/Partner Link.
			if ( ! get_theme_mod( 'hide_partner_attribution' ) || is_customize_preview() ) {
				if ( ! empty( $reseller_data['reseller_title'] ) ) {
					$theme_mods .= sprintf(
						'<span class="link reseller-attribution-link">%s <a href="%s" rel="nofollow" target="_blank">%s</a> | </span>',
						__( 'Support from', 'bgtfw' ),
						$reseller_data['reseller_website_url'],
						$reseller_data['reseller_title']
					);
				}
			}
		}

		// If theme configs have attribution_links declared, add the link.
		if ( ! empty( $this->configs['temp']['attribution_links'] ) ) {
			$theme_mods .= $this->attribution_link();
		} ?>

		<span class="attribution-theme-mods"><?php echo $theme_mods ?></span>
		<?php
	}

	/**
	 * Create the attribution link and keep link filterable for BoldGrid Staging
	 *
	 * @since 1.0.1
	 * @return string
	 */
	public function attribution_link() {

		$option = 'boldgrid_attribution';
		$option = apply_filters( 'boldgrid_attribution_filter', $option );
		$attribution_data = get_option( $option );
		$attribution_page = get_page_by_title( 'Attribution' );

		// If option is available use that or try to find the page by slug name.
		if ( ! empty( $attribution_data['page']['id'] ) ) {
			$link = '<a href="' . get_permalink( $attribution_data['page']['id'] ) . '">' . __( 'Special Thanks', 'bgtfw' ) . '</a>';
		} elseif ( $attribution_page ) {
			$link = '<a href="' . get_site_url( null, 'attribution' ) . '">' . __( 'Special Thanks', 'bgtfw' ) . '</a>';
		} else {
			$link = '';
		}

		return $link;
	}

	/**
	 *  This will remove all actions, menus, and widgets based on configs if the
	 *  user selects to disable their footer.
	 *
	 *  @since     1.0.0
	 */
	public function maybe_remove_all_footer_actions() {
		if ( ! get_theme_mod( 'boldgrid_enable_footer', true ) ) {
			$footer_actions = $this->configs['action']['inside_footer'];

			// This is the boldgrid_menu_footer_center section.
			foreach ( $this->configs['menu']['footer_menus'] as $menu ) {
				$footer_actions[] = $this->configs['menu']['action_prefix'] . $menu;
			}

			foreach ( $footer_actions as $footer_action ) {
				remove_all_actions( $footer_action );
			}

			foreach ( $this->configs['widget']['footer_widgets'] as $widget ) {
				unregister_sidebar( $widget );
			}
		}
	}

	/**
	 *  This adds the enable/disable switch in the customizer, so that a
	 *  user can enable/disable their footer.
	 *
	 *  @since    1.0.0
	 */
	public function add_enable_control() {
		Kirki::add_field(
			'',
			array(
				'type' => 'switch',
				'settings' => 'boldgrid_enable_footer',
				'label' => __( 'Enable Footer', 'bgtfw' ),
				'section' => 'boldgrid_footer_panel',
				'default' => true,
				'priority' => 5,
			)
		);
	}

	/**
	 *  If a user has selected to disable their footer, this will
	 *  pass along a new class to the <body> element called disabled-footer,
	 *  so that it can be targetted with appropriate CSS or JS.
	 *
	 * @param array $body_classes Classes to add to body of page.
	 * @return    string     $body_classes   String contains classes to add to body of page.
	 * @since     1.0.0
	 */
	public function collapse_body_margin( $body_classes ) {

		if ( ! get_theme_mod( 'boldgrid_enable_footer', true ) ) {
			$body_classes[] = 'disabled-footer';
		}

		return $body_classes;
	}
}
