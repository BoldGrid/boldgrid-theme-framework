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
	 * Enqueue scripts for the customizer.
	 *
	 * @since 2.0.0
	 */
	public function customize_controls_enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		/*
		 * Currently, we're enqueuing homepage.js, which handles dynamically adding
		 * "Configure Posts Page" links to Customizer > Design > Homepage.
		 */
		$handle = 'bgtfw-customizer-homepage';
		wp_register_script(
			$handle,
			$this->configs['framework']['js_dir'] . 'customizer/homepage' . $suffix . '.js',
			array(
				'jquery',
			),
			false,
			true
		);
		wp_enqueue_script( $handle );
		wp_localize_script( $handle, 'boldgridFrameworkCustomizerHomepage', array(
			'Configure' => __( 'Configure', 'bgtfw' ),
			'ConfigurePostsPage' => __( 'Configure Posts Page', 'bgtfw' ),
		));
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
		$post_type = isset( $this->configs['title'][ 'default_' . $post_type ] ) ? $post_type : 'post';

		return get_theme_mod( 'bgtfw_' . $post_type . 's_title_display' );
	}

	/**
	 * Display post title controls within "Post Attributes" meta box.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Post $post WordPress Post Object.
	 */
	public function meta_box_callback( $post ) {
		if ( ! in_array( $post->post_type, apply_filters( 'bgtfw_page_title_control_post_types', array( 'post', 'page' ) ), true ) ) {
			return;
		}

		$post_meta = get_post_meta( $post->ID, $this->configs['title']['hide'], true );
		$title     = sprintf( '%1$s %2$s', 'post' === $post->post_type ? __( 'Post: ', 'bgtfw' ) : __( 'Page: ', 'bgtfw' ), __( 'Title: ', 'bgtfw' ) );
		$k         = $this->get_global();

		$options = array(
			'global' => array(
				'name' => __( 'Use Global Setting ', 'bgtfw' ),
				'value' => 'global',
				'checked' => 'global' === $post_meta,
				'post_text' => esc_attr( $k ),
			),
			'show' => array(
				'name' => __( 'Show', 'bgtfw' ),
				'value' => '1',
				'checked' => '1' === $post_meta,
				'post_text' => $this->configs['title']['meta_box'][ $post->post_type ]['show_post_text'],
			),
			'hide' => array(
				'name' => __( 'Hide', 'bgtfw' ),
				'value' => '0',
				'checked' => '0' === $post_meta,
				'post_text' => $this->configs['title']['meta_box'][ $post->post_type ]['hide_post_text'],
			),
		);

		/**
		 * Filter the available control options.
		 *
		 * @since 2.1.0
		 *
		 * @param array $options   Default post options.
		 * @param array $post      Current Post Object.
		 * @param array $post_meta Post Meta Value.
		 */
		$options = apply_filters( 'bgtfw_page_title_options', $options, $post, $post_meta );
		$checked = false;
		foreach ( $options as $option ) {
			if ( $option['checked'] ) {
				$checked = true;
				break;
			}
		}

		if ( ! $checked ) {
			$options['global']['checked'] = true;
		}

		?>
		<div class="misc-pub-section bgtfw-misc-pub-section bgtfw-page-title">
			<?php
				if ( $options['global']['checked'] ) {
					$k = get_theme_mod( 'bgtfw_pages_title_display' );
					echo esc_html( $title ) . ': <span class="value-displayed">' . esc_html__( 'Use Global Setting ', 'bgtfw' ) . '<div class="template-subtitle">' . esc_html( $k ) . '</div></span>';
				} else {
					echo esc_html( $title ) . '<span class="value-displayed">...</span>';
				}
			?>
			<a class="edit" href="">
				<span aria-hidden="true"><?php esc_html_e( 'Edit', 'bgtfw' ); ?></span> <span class="screen-reader-text"><?php echo esc_html( $title ); ?></span>
			</a>
			<div class="options">
				<?php foreach ( $options as $option ) : ?>
				<label>
					<?php $value_displayed = $option['name'] . ( ! empty( $option['post_text'] ) ? sprintf( ' <span class="template-subtitle">%1$s</span>', $option['post_text'] ) : '' ); ?>
					<input type="radio" name="<?php echo esc_attr( $this->configs['title']['hide'] ); ?>" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['checked'] ); ?> data-default-option="<?php echo esc_attr( $option['checked'] ? '1' : '0' ); ?>" data-value-displayed="<?php echo esc_attr( $value_displayed ); ?>" />
					<span>
						<?php echo esc_html( $option['name'] ); ?>
						<?php if ( ! empty( $option['post_text'] ) ) : ?>
						<span class="template-subtitle"><?php echo esc_html( $option['post_text'] ); ?></span>
						<?php endif; ?>
					</span>
				</label>
				<?php endforeach; ?>
				<p>
					<a href="" class="button"><?php esc_html_e( 'OK', 'bgtfw' ); ?></a>
					<a href="" class="button-cancel"><?php esc_html_e( 'Cancel', 'bgtfw' ); ?></a>
				</p>
			</div>
		</div><?php
	}

	/**
	 * Update "show title" post meta after a post is updated.
	 *
	 * @since 2.0.0
	 *
	 * @param int $post_id The ID of the post being updated.
	 */
	public function post_updated( $post_id ) {
		if ( isset( $_POST[ $this->configs['title']['hide'] ] ) ) { // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification

			// Validate "show title" post meta, and abort on failure.
			$value = (string) sanitize_key( $_POST[ $this->configs['title']['hide'] ] );

			if ( ! in_array( $value, array( '0', '1', 'global' ), true ) ) {
				return;
			}

			/*
			 * Update our value.
			 *
			 * Posts actually use post meta, and the page_for_posts page uses a theme mod.
			 */
			delete_post_meta( $post_id, $this->configs['title']['hide'] );
			update_post_meta( $post_id, $this->configs['title']['hide'], $value );
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

		// This method only needs to be ran if we're looking at a page, post, archive, or blog.
		$is_single = is_page() || is_single();
		$is_multi  = is_home() || is_archive();
		$is_page   = is_page();

		$allowed = $is_single || $is_multi;

		if ( ! $allowed ) {
			return $title;
		}

		// Comments should display title regardless of post/page title being hidden.
		if ( doing_action( 'boldgrid_comments' ) ) {
			return $title;
		}

		/*
		 * When we're on a page, and the <div class="main"> tag has printed, we know we are inside the page content.
		 * And therefore must return the title unchanged.
		 */
		if ( $is_page && did_action( 'boldgrid_main_top' ) ) {
			return $title;
		}

		/*
		 * Unlike a page, where we only know if we're in the content after the main div is printed,
		 * a post has to determine whether or not the post header action has been completed
		 */
		if ( $is_single && did_action( 'bgtfw_after_post_header' ) ) {
			return $title;
		}

		/*
		 * The the_title filter is ran quite often. For example, when displaying nav menus, this filter
		 * is ran and can change a page's title in the nav. We're only interested in adjusting the
		 * title when displaying a post.
		 */
		if ( ( $is_multi && in_the_loop() ) || ( $is_single && ! in_the_loop() ) ) {
			return $title;
		}

		// Check for widget areas displayed within the main content and don't modify those.
		if ( did_action( 'dynamic_sidebar_before' ) && ! did_action( 'dynamic_sidebar_after' ) ) {
			return $title;
		}

		// Handle the title existing outside of the loop.
		if ( $is_multi ) {

			// Check that filter is being applied only inside of our main content area.
			if ( ! did_action( 'boldgrid_main_top' ) || did_action( 'boldgrid_main_bottom' ) ) {
				return $title;
			}
		}

		$post_meta = get_post_meta( $id, $this->configs['title']['hide'], true );

		$global = $this->get_global();
		$show   = '1' === $post_meta || ( ( 'global' === $post_meta || '' === $post_meta ) && 'show' === $global );

		return $show ? $title : '';
	}
}
