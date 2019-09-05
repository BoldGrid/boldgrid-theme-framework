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
				'transport'   => 'refresh',
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
				'transport'   => 'refresh',
				'label'       => __( 'Hide WordPress Attribution', 'bgtfw' ),
				'section'     => 'boldgrid_footer_panel',
				'default'     => false,
				'priority'    => 40,
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

		// If the user hasn't disabled the footer, add the links.
		if ( get_theme_mod( 'boldgrid_enable_footer', true ) ) {
			// BoldGrid.com Link.
			if ( ! get_theme_mod( 'hide_boldgrid_attribution' ) ) {
				$theme_mods .= sprintf(
					'<span class="link boldgrid-attribution-link">%s <a href="%s" rel="nofollow" target="_blank">%s</a></span>',
					__( 'Built with', 'bgtfw' ),
					'http://boldgrid.com/',
					__( 'BoldGrid', 'bgtfw' )
				);
			}

			// WordPress.org Link.
			if ( ! get_theme_mod( 'hide_wordpress_attribution' ) ) {
				$theme_mods .= sprintf(
					'<span class="link wordpress-attribution-link">%s <a href="%s" rel="nofollow" target="_blank">%s</a></span>',
					__( 'Powered by', 'bgtfw' ),
					'https://wordpress.org/',
					__( 'WordPress', 'bgtfw' )
				);
			}

			// Host Link.
			$host_attribution = get_theme_mod( 'host_attribution' );
			if ( ! empty( $host_attribution ) && ! get_theme_mod( 'hide_host_attribution' ) ) {
				$theme_mods .= '<span class="link host-attribution-link">' . wp_kses(
					$host_attribution,
					[
						'a' => [
							'href'   => [],
							'target' => [],
							'rel'    => [],
							'title'  => [],
						],
					]
				) . '</span>';
			}
		}

		// Allow plugins or themes to add additional attribution links to footer.
		$additional_links = '';
		$additional_links = apply_filters( 'bgtfw_attribution_links', $additional_links );
		$theme_mods .= $additional_links;
		?>

		<span class="attribution-theme-mods"><?php echo $theme_mods ?></span>
		<?php
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
