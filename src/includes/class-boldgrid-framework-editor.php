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

/**
 * Class: Boldgrid_Framework_Editor
 *
 * Responsible for Editor functionality.
 *
 * @since 1.0.6
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
	 * @param array $configs Array of bgtfw configuration options.
	 * @since 1.0.6
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Theme framework mce plugin responsible for adding inline styles to editor.
	 *
	 * @param array $plugin_array Array of tinymce plugins.
	 * @return string
	 * @since 1.0.6
	 */
	public function add_tinymce_plugin( $plugin_array ) {
		global $pagenow;

		$valid_pages = array(
			'customize.php',
			'post.php',
			'post-new.php',
		);

		$valid_post_types = array(
			'page',
			'post',
			'bg_block',
			'crio_page_header',
		);

		if ( ! empty( $pagenow ) && ! in_array( $pagenow, $valid_pages ) ) {
			return $plugin_array;
		}
		// Currently only pages and posts are supported. @since 1.3.1
		if ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) {
			if ( ! in_array( $this->get_post_type(), $valid_post_types ) ) {
				return $plugin_array;
			}
		}

		$mce_inline_styles = '';
		$mce_inline_styles = apply_filters( 'boldgrid_mce_inline_styles', $mce_inline_styles );

		$kirki_css = Kirki_Modules_CSS::get_instance();
		$mce_inline_styles .= apply_filters( 'kirki_global_dynamic_css', $kirki_css::loop_controls( 'global' ) );
		$mce_inline_styles .= apply_filters( 'kirki_bgtfw_dynamic_css', $kirki_css::loop_controls( 'bgtfw' ) );

		wp_localize_script( 'mce-view', 'BOLDGRID_THEME_FRAMEWORK',
			array(
				'Editor' => array(
					'mce_inline_styles' => $mce_inline_styles,
				),
				'post_id' => ! empty( $_REQUEST['post'] ) ? absint( $_REQUEST['post'] ) : null,
			) );

		$editor_js_file = $this->configs['framework']['admin_asset_dir'] . 'js/editor.js';

		$plugin_array['boldgrid_theme_framework'] = $editor_js_file;

		// This call could be moved elsewhere. Essentially, load this css when edit any array( 'page', 'post' ).
		wp_enqueue_style(
			'editor',
			$this->configs['framework']['css_dir'] . 'editor.css'
		);

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
		$current_post_id = ! empty( $_REQUEST['post'] ) ? absint( $_REQUEST['post'] ) : null;
		$current_post = get_post( $current_post_id );

		/*
		 * Determine the current post type.
		 *
		 * The post type is "post", unless specified by $current_post->post_type or
		 * $_GET['post_type'].
		*/
		if ( ! empty( $current_post->post_type ) ) {
			$current_post_type = $current_post->post_type;
		} elseif ( isset( $_GET['post_type'] ) ) {
			$current_post_type = sanitize_key( $_GET['post_type'] );
		} else {
			$current_post_type = 'post';
		}

		return $current_post_type;
	}

	/**
	 * What method to use to add styles?
	 *
	 * This sets Kirki config to compile CSS to a file instead of adding
	 * inline.
	 *
	 * @since 2.0.0
	 *
	 * @return string Name of method of adding CSS styles.
	 */
	public function add_styles_method() {
		return 'file';
	}

	/**
	 * Call kirki to load fonts using the webfont loader.
	 *
	 * This will load the fonts in the primary document, the styles are then copied into
	 * tinymce after loaded.
	 *
	 * @since 2.0.0
	 *
	 * @global string $pagenow
	 */
	public function enqueue_webfonts() {
		global $pagenow;

		// Don't add styles on non editor.
		if ( $pagenow && ! in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) ) {
			return;
		}

		foreach ( array_keys( Kirki::$config ) as $config_id ) {
			$web_fonts = Kirki_Modules_Webfont_Loader::get_instance();
			Kirki_Modules_Webfont_Loader::$load = true;
			$web_fonts->enqueue_scripts();

			$async = new Kirki_Modules_Webfonts_Async(
				$config_id,
				Kirki_Modules_Webfonts::get_instance(),
				Kirki_Fonts_Google::get_instance()
			);

			$async->webfont_loader();
			$async->webfont_loader_script();
		}
	}

	/**
	 * Enqueue Google fonts to the TinyMCE Editor frame.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $mce_css CSS being added to the TinyMCE instance.
	 *
	 * @return string $mce_css The modified CSS string to add to the TinyMCE instance.
	 */
	public function add_google_fonts( $mce_css ) {
		if ( ! empty( $mce_css ) ) {
			$mce_css .= ',';
		}
		$upload_dir = wp_upload_dir();
		return $mce_css . esc_url_raw( $upload_dir['baseurl'] . '/kirki-css/styles.css' );
	}

	/**
	 * Add default template class to TinyMCE.
	 *
	 * Adds the page-template-default class to the editor on initial load
	 * if that template is in use.
	 *
	 * @since 1.3.6
	 *
	 * @return string $mce Contains classes to add to TinyMCE.
	 */
	public function tinymce_body_class( $mce ) {
		$palette = get_theme_mod( 'boldgrid_palette_class' );
		$pattern = get_theme_mod( 'boldgrid_background_pattern' );

		if ( ! isset( $mce['body_class'] ) ) {
			$mce['body_class'] = $palette;
		} else {
			$mce['body_class'] .= " $palette";
		}

		$api = new BoldGrid( $this->configs );

		// Get the current post, check if it's a page and add our body classes.
		if ( $post = get_post() ) {
			if ( 'page' === $post->post_type ) {
				$template = get_page_template_slug();

				// If not the default template generate class.
				if ( '' === $template ) {
					$mce['body_class'] .= ' page-template-default';
				}
			}
		}

		return $mce;
	}

	/**
	 * Enqueue block JavaScript and CSS for the editor
	 */
	public function gutenberg_scripts() {

		// Enqueue block editor JS
		wp_enqueue_script(
			'bgtfw-gutenberg',
			$this->configs['framework']['admin_asset_dir'] . 'js/gutenberg.js',
			[ 'wp-edit-post' ],
			$this->configs['framework-version'],
			true
		);
	}
}
