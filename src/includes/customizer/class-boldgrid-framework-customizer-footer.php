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
		}

		// Allow plugins or themes to add additional attribution links to footer.
		$additional_links = '';
		$additional_links = apply_filters( 'bgtfw_attribution_links', $additional_links );
		$theme_mods .= $additional_links;

		$allowed = [
			'a' => [
				'href' => [],
				'title' => [],
				'rel' => [],
				'target' => [],
			],
			'span' => [
				'class'=> []
			]
		];
		?>
		<span class="attribution-theme-mods"><?php echo wp_kses( $theme_mods, $allowed ); ?></span>
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
