<?php
/**
 * Class: Boldgrid_Framework_Editor
 *
 * @since 1.0.6
 * @package Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Editor
 * @author BoldGrid <support@boldgrid.com>
 * @link https://boldgrid.com
 */
class Boldgrid_Framework_Editor {

	/**
	 * Global Framework configurations
	 *
	 * @var array $configs
	 * @since 1.0.6
	 */
	protected $configs;

	/**
	 * Pass in configs
	 *
	 * @param array $configs
	 * @since 1.0.6
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Save page title toggle via ajax call.
	 *
	 * @since 1.0.7
	 */
	public function update_page_title_toggle( $post_id, $post ) {
		$post_id = ! empty( $post_id ) ? $post_id : null;

		// If this is a revision, get real post ID.
		if ( $parent_id = wp_is_post_revision( $post_id ) ) {
			$post_id = $parent_id;
		}

		$status = isset( $_POST['boldgrid-display-post-title'] ) ? intval( $_POST['boldgrid-display-post-title'] ) : null;
		if ( $post_id && false == is_null( $status ) ) {
			$post_meta = get_post_meta( $post_id );
			if ( ! empty( $post_meta ) ) {
				// Save post meta.
				update_post_meta( $post_id, 'boldgrid_hide_page_title', $status );
			}
		}

	}

	/**
	 * Display a post title display control on the page and post editor.
	 *
	 * @since 1.0.7
	 */
	public function add_post_title_toggle() {
		global $pagenow;

		$post_id = ! empty( $_REQUEST['post'] ) ? $_REQUEST['post'] : null;

		if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {

			$template_file = null;

			if ( false == empty( $post_id ) ) {
				$post = get_post( $post_id );

				if ( ! $post ) {
					return;
				}

				// If the post type is not page or post that do not display.
				if ( false == in_array( $post->post_type, array( 'post', 'page' ) ) ) {
					return;
				}

				$post_meta = get_post_meta( $post->ID );
				$display_page_title = ! empty( $post_meta['boldgrid_hide_page_title'][0] ) || ! isset( $post_meta['boldgrid_hide_page_title'] );
				$template_file = get_post_meta( $post->ID, '_wp_page_template', true );

				// Don't allow modification on home page.
				$disabled = '';
				if ( 'page_home.php' == $template_file ) {
					$display_page_title = false;
					$disabled = 'disabled="disabled"';
				}

				$post_type = 'page';
				if ( $post->post_type == 'post' ) {
					$post_type = 'post';
				}
			} else {
				$post_type = ! empty( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : null;
				if ( $post_type != 'page' ) {
					$post_type = 'post';
				}

				$display_page_title = true;
				$disabled = '';
			}

			add_action( 'edit_form_after_title',
				function () use ( $post_type, $display_page_title, $disabled, $template_file ) {
					$checked = checked( $display_page_title, true, false );
					$message = "The {$post_type} title displays as a heading at the top of your {$post_type}. Your BoldGrid theme supports this feature.";
					if ( 'page_home.php' === $template_file ) {
						$message = "The Home template does not support adding a page title.  You can change the template from the dropdown box in the Page Attributes section.";
					}
					echo <<<HTML
						<div id="boldgrid-hide-post-title">
							<input style='display:none' type='checkbox' value='0' checked='checked' name='boldgrid-display-post-title'>
							<label>
							<input value="1" name="boldgrid-display-post-title" {$checked} {$disabled} type='checkbox'> Display
							 $post_type  title </label><span class="dashicons dashicons-editor-help"></span>
							<span class="spinner"></span>
							<div class='boldgrid-tooltip'>
								<div class="boldgrid-tooltip-arrow">
								</div>
								<div class="boldgrid-tooltip-inner">
									{$message}
								</div>
							</div>
						</div>
HTML;
			} );
		}
	}

	/**
	 * Theme framework mce plugin responsible for adding inline styles to editor.
	 *
	 * @param array $plugin_array.
	 * @return string
	 * @since 1.0.6
	 */
	public function add_tinymce_plugin( $plugin_array ) {
		global $pagenow;

		$valid_pages = array(
			'customize.php',
			'post.php',
			'post-new.php'
		);

		$valid_post_types = array(
			'page',
			'post',
		);

		if ( ! empty( $pagenow ) && ! in_array( $pagenow, $valid_pages ) ) {
			return;
		}

		// Currently only pages and posts are supported. @since 1.3.1
		if( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) {
			if( ! in_array( $this->get_post_type(), $valid_post_types ) ) {
				return;
			}
		}

		$mce_inline_styles = '';
		$mce_inline_styles = apply_filters( 'boldgrid_mce_inline_styles', $mce_inline_styles );

		wp_localize_script( 'mce-view', 'BOLDGRID_THEME_FRAMEWORK',
			array(
				'Editor' => array(
					'mce_inline_styles' => $mce_inline_styles,
				),
				'post_id' => ! empty( $_REQUEST['post'] ) ? $_REQUEST['post'] : null,
			) );

		$editor_js_file = $this->configs['framework']['admin_asset_dir'] . 'js/editor.js';

		$plugin_array['boldgrid_theme_framework'] = $editor_js_file;

		return $plugin_array;
	}

	/**
	 * Get the current post type.
	 *
	 * This method is meant to be ran from either 'post.php' or 'post-new.php'. Ran from anywhere
	 * else, and you may get unexpected results.
	 *
	 * @since 1.3.2
	 */
	public function get_post_type() {
		$current_post_id = ! empty( $_REQUEST['post'] ) ? $_REQUEST['post'] : null;
		$current_post = get_post( $current_post_id );

		/*
		 * Determine the current post type.
		 *
		 * The post type is "post", unless specified by $current_post->post_type or
		 * $_GET['post_type'].
		*/
		if( ! empty( $current_post->post_type ) ) {
			$current_post_type = $current_post->post_type;
		} elseif( isset( $_GET['post_type'] ) ) {
			$current_post_type = $_GET['post_type'];
		} else {
			$current_post_type = 'post';
		}

		return $current_post_type;
	}

	/**
	 * Genereate a kirki google fonts link.
	 *
	 * @since 1.1.5
	 *
	 * @global array $wp_styles List of enqueued styles.
	 *
	 * @return string Url to google fonts.
	 */
	public function kirki_google_link() {

		global $wp_styles;

		$link = false;
		$kirki_handle = 'kirki_google_fonts';

		// Force kirki to enqueue styles.
		$google_fonts = Kirki_Fonts_Google::get_instance();
		$google_fonts->enqueue();

		// Grab kirkis url.
		if ( ! empty( $wp_styles->registered[ $kirki_handle ]->src ) ) {
			$link = $wp_styles->registered[ $kirki_handle ]->src;
		}

		// Deregister the style.
		wp_dequeue_style ( $kirki_handle );

		return $link;
	}

	/**
	 * Enqueue Google fonts if necessary
	 *
	 * @global string $pagenow current page.
	 */
	public function add_google_fonts() {

		global $pagenow;

		$valid_pages = array (
			'post.php',
			'post-new.php',
			'customize.php',
		);

		if ( false === in_array( $pagenow, $valid_pages ) ) {
			return;
		}

		$config = apply_filters( 'kirki/config', array() );

		/**
		 * If we have set $config['disable_google_fonts'] to true
		 * then do not proceed any further.
		 */
		if ( isset( $config['disable_google_fonts'] ) && true == $config['disable_google_fonts'] ) {
			return;
		}
		$link = $this->kirki_google_link();
		if ( $link ) {
			add_editor_style( $link );
		}
	}
}
