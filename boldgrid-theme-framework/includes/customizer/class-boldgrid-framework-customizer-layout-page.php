<?php
/**
 * Class: Boldgrid_Framework_Customizer_Contact_Blocks
 *
 * This is the class responsible for adding the contact blocks
 * that appear in the footer.
 *
 * @since      1.3.5
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Customizer_Contact Blocks
 *
 * This is the class responsible for adding the contact blocks
 * functionality to the footer.
 *
 * @since      1.3.5
 */
class Boldgrid_Framework_Customizer_Layout_Page {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.3.5
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.3.5
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Adds the Contact Details section
	 *
	 * @since 1.3.5
	 */
	public function add_controls( $wp_customize ) {
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

		// Adds the "Page Layout" control the "Layout" section.
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
	}
}
