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
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       array $configs The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
	 *
	 * @param array $configs The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Get theme mod value for displaying title.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $post_type page or post.
	 * @return string
	 */
	public function get_global( $post_type = null ) {
		$post_type = empty( $post_type ) ? get_post_type() : $post_type;
		$post_type = ! empty( $this->configs['title'][ 'default_' . $post_type ] ) ? $post_type : 'post';
		$default = $this->configs['title'][ 'default_' . $post_type ];

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

		$post_meta = get_post_meta( $post->ID, $this->configs['title']['hide'], true );
		$global = $this->get_global( $post->post_type );

		$title = sprintf( '%1$s %2$s', 'post' === $post->post_type ? __( 'Post', 'bgtfw' ) : __( 'Page', 'bgtfw' ), __( 'Title', 'bgtfw' ) );

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
				'post_text' => $this->configs['title']['meta_box'][$post->post_type]['show_post_text'],
			),
			array(
				'name' => __( 'Hide', 'bgtfw' ),
				'value' => '0',
				'checked' => '0' === $post_meta,
				'post_text' => $this->configs['title']['meta_box'][$post->post_type]['hide_post_text'],
			),
		);
		?>

		<div class="misc-pub-section bgtfw-misc-pub-section bgtfw-page-title">
			<?php echo $title; ?>: <span class="value-displayed">...</span>
			<a class="edit" href="">
				<span aria-hidden="true"><?php echo __( 'Edit', 'bgtfw' ); ?></span> <span class="screen-reader-text"><?php echo $title; ?></span>
			</a>
			<div class="options">
				<?php foreach( $options as $option ) {
					$value_displayed = $option['name'] . ( ! empty( $option['post_text'] ) ? sprintf( ' <span class="template-subtitle">%1$s</span>', $option['post_text'] )  : '' );
				?><label>
					<input type="radio" name="<?php echo $this->configs['title']['hide']; ?>" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['checked'] ); ?> data-default-option="<?php echo $option['checked'] ? '1' : '0'; ?>" data-value-displayed="<?php echo esc_attr( $value_displayed ); ?>" />
					<?php echo $value_displayed; ?>
				</label>
				<?php } ?>
				<p>
					<a href="" class="button">OK</a>
					<a href="" class="button-cancel">Cancel</a>
				</p>
			</div>
		</div><?php
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
		if ( isset( $_POST[ $this->configs['title']['hide'] ] ) ) {
			delete_post_meta( $post_ID, $this->configs['title']['hide'] );

			if ( in_array( $_POST[$this->configs['title']['hide']], array( '0', '1' ), true ) ) {
				update_post_meta( $post_ID, $this->configs['title']['hide'], $_POST[ $this->configs['title']['hide'] ] );
			}
		}
	}

	/**
	 * Filter a post's title within the_title filter.
	 *
	 * Determine whether or not to show post title on a post/page.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $title The post title.
	 * @param  int    $id    The post ID.
	 * @return string
	 */
	public function show_title( $title, $id ) {

		// This method only needs to be ran if we're looking at a single page / post.
		$is_single_post = is_page() || is_single();
		if ( ! $is_single_post ) {
			return $title;
		}

		/*
		 * The the_title filter is ran quite often. For example, when displaying nav menus, this filter
		 * is ran and can change a page's title in the nav. We're only interested in adjusting the
		 * title when displaying a post.
		 */
		if ( ! in_the_loop() ) {
			return $title;
		}

		$post_meta = get_post_meta( $id, $this->configs['title']['hide'], true );
		$global = $this->get_global();
		$show = '1' === $post_meta || ( '' === $post_meta && '1' === $global );

		return $show ? $title : '';
	}
}
