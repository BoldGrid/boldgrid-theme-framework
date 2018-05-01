<?php
/**
 * Class: Boldgrid_Framework_Title
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Title
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Boldgrid_Framework_Title Class
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Title {

	/**
	 * Add Controls to the customizer.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function add_customizer_controls( $wp_customize ) {
		$settings = array(
			array(
				'label' => esc_html__( 'Page Title', 'bgtfw' ),
				'section' => 'bgtfw_layout_page',
				'setting' => 'bgtfw_pages_display_title',
			),
			array(
				'label' => esc_html__( 'Post Title', 'bgtfw' ),
				'section' => 'bgtfw_pages_blog_posts_layout',
				'setting' => 'bgtfw_posts_display_title',
			),
		);

		foreach( $settings as $setting ) {
			$wp_customize->add_setting( $setting['setting'] , array(
				'type'      => 'theme_mod',
				'default'   => 'container',
				'transport'   => 'postMessage',
			) );

			$wp_customize->add_control( $setting['setting'], array(
				'label'       => esc_html__( 'Post Title', 'bgtfw' ),
				'type'        => 'radio',
				'priority'    => 40,
				'choices'     => array(
					'show' => esc_attr__( 'Show', 'bgtfw' ),
					'hide' => esc_attr__( 'Hide', 'bgtfw' ),
				),
				'section' => $setting['section'],
			) );
		}
	}

	/**
	 * Determine whether or not to show post title on a post/page.
	 *
	 * @since 2.0.0
	 *
	 * @param int $id Post id.
	 */
	public static function to_show( $id = 0 ) {

		// Never show the page title on the homepage.
		if( 'page_home.php' === get_page_template_slug() ) {
			return false;
		}

		$id = empty( $id ) ? get_the_ID() : $id;

		$post_meta = get_post_meta( $id, 'boldgrid_hide_page_title', true );

		// todo MORE WORK TO DO IN THIS METHOD.

		return true;
	}
}
