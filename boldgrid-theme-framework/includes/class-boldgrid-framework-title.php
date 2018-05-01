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
	 * Config.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public static $config = array(
		// Post meta data that determines if title should show on individual page.
		'hide' => 'boldgrid_hide_page_title',
		// Theme mod, determines if title should display on pages.
		'page' => 'bgtfw_pages_display_title',
		// Theme mod, determines if title should display on posts.
		'post' => 'bgtfw_posts_display_title',
		'default_page' => '0',
		'default_post' => '1',
	);

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
				'setting' => self::$config['page'],
				'default' => self::$config['default_page'],
			),
			array(
				'label' => esc_html__( 'Post Title', 'bgtfw' ),
				'section' => 'bgtfw_pages_blog_posts_layout',
				'setting' => self::$config['post'],
				'default' => self::$config['default_post'],
			),
		);

		foreach( $settings as $setting ) {
			$wp_customize->add_setting( $setting['setting'] , array(
				'type'      => 'theme_mod',
				'default'   => $setting['default'],
			) );

			$wp_customize->add_control( $setting['setting'], array(
				'label'       => $setting['label'],
				'type'        => 'radio',
				'priority'    => 40,
				'choices'     => array(
					'1' => esc_attr__( 'Show', 'bgtfw' ),
					'0' => esc_attr__( 'Hide', 'bgtfw' ),
				),
				'section' => $setting['section'],
			) );
		}
	}

	/**
	 * Get theme mod value for displaying title.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $post_type page or post.
	 * @return string
	 */
	public static function get_global( $post_type = null ) {
		$post_type = empty( $post_type ) ? get_post_type() : $post_type;

		$default = self::$config[ 'default_' . $post_type ];

		return get_theme_mod( 'bgtfw_' . $post_type . 's_display_title', $default );
	}

	/**
	 * Display post title controls within "Post Attributes" meta box.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Post $post
	 */
	public function meta_box_callback( $post ) {
		if( ! in_array( $post->post_type, array( 'post', 'page' ), true ) ) {
			return;
		}

		$post_meta = get_post_meta( $post->ID, self::$config['hide'], true );
		$global = $this->get_global( $post->post_type );

		$options = array(
			array(
				'name' => __( 'Use Global Setting', 'bgtfw' ),
				'value' => 'global',
				'checked' => '' === $post_meta,
				'post_text' => '1' === $global ? __( 'Show', 'bgtfw' ) : __( 'Hide', 'bgtfw' ),
			),
			array(
				'name' => __( 'Show', 'bgtfw' ),
				'value' => '1',
				'checked' => '1' === $post_meta,
			),
			array(
				'name' => __( 'Hide', 'bgtfw' ),
				'value' => '0',
				'checked' => '0' === $post_meta,
			),
		);

		?><p class="post-attributes-label-wrapper">
			<label class="post-attributes-label" for="page_template">
				<?php echo 'post' === $post->post_type ? __( 'Post', 'bgtfw' ) : __( 'Page', 'bgtfw' ); ?> Title
			</label>
		</p><?php

		foreach( $options as $option ) {
			?><label>
				<input type="radio" name="<?php echo self::$config['hide']; ?>" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['checked'] ); ?> />
				<?php echo $option['name']; echo ! empty( $option['post_text'] ) ? sprintf( ' <span class="template-subtitle" style="display:inline;margin:0;">%1$s</span>', $option['post_text'] )  : ''; ?>
			</label><br /><?php
		}
	}

	/**
	 * Update "show title" post meta after a post is updated.
	 *
	 * @since 2.0.0
	 *
	 * @param int    $post_ID
	 * @param string $post_after
	 * @param string $post_before
	 */
	public function post_updated( $post_ID, $post_after, $post_before ) {
		if( isset( $_POST[self::$config['hide']] ) ) {
			delete_post_meta( $post_ID, self::$config['hide'] );

			if( in_array( $_POST[self::$config['hide']], array( '0', '1' ), true ) ) {
				update_post_meta( $post_ID, self::$config['hide'], $_POST[self::$config['hide']] );
			}
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

		$post_meta = get_post_meta( $id, self::$config['hide'], true );
		$global = self::get_global();

		return '1' === $post_meta || ( '' === $post_meta && '1' === $global );
	}
}
