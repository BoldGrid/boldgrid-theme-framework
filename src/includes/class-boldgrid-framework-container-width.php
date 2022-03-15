<?php
/**
 * Class: Boldgrid_Framework_Container_Width
 *
 * This class contains methods used to modify the container widths.
 *
 * @since      2.14.0
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Container_Width
 *
 * TThis class contains methods used to modify the container widths.
 *
 * @since      2.14.0
 */
class Boldgrid_Framework_Container_Width {

	/**
	 *  Configs
	 *
	 * @since 2.14.0
	 * @var array $configs
	 */
	public $configs;

	/**
	 * Constructor.
	 *
	 * @since 2.14.0
	 *
	 * @param array $configs Framework configs.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Get Container Type
	 *
	 * @since 2.14.0
	 *
	 * @param WP_Post $post The post object.
	 *
	 * @return string The container type.
	 */
	public function get_container_type( $post ) {
		$container_type = 'container';
		switch ( $this->get_container_theme_mod( $post ) ) {
			case '':
			case 'full-width':
				$container_type = 'full-width';
				break;
			case 'container':
				$container_type = 'container';
				break;
			case 'max-full-width':
				$container_type = 'max-full-width';
				break;
		}

		return $container_type;
	}

	/**
	 * Get Container Theme Mod
	 *
	 * @since 2.14.0
	 *
	 * @param WP_Post $post The post object.
	 * @param bool    $max_full_width Whether or not to return the max-full-width container theme mod.
	 *
	 * @return string The container theme mod.
	 */
	public function get_container_theme_mod( $post, $max_full_width = false ) {
		$page_post_type = $this->get_page_post_type( $post );
		$theme_mod_name = 'bgtfw_' . $page_post_type . '_container' . ( $max_full_width ? '_max_width' : '' );
		return get_theme_mod( $theme_mod_name );
	}

	/**
	 * Get Page Post Type
	 *
	 * @since 2.14.0
	 *
	 * @param WP_Post $post WP Post Object.
	 *
	 * @return string The page post type.
	 */
	public function get_page_post_type( $post ) {
		global $boldgrid_theme_framework;
		$post_type = 'blog_page';

		// Home Page is a static page.
		if ( $boldgrid_theme_framework->woo->is_woocommerce_page() ) {
			$post_type = 'woocommerce';
		} elseif ( is_front_page() && 'page' === get_option( 'show_on_front' ) || is_page() ) {
			$post_type = 'pages';
		} elseif ( is_single() || is_attachment() || ( function_exists( 'is_shop' ) && is_shop() ) ) {
			$post_type = 'blog_posts';
		}
		return $post_type;
	}

	/**
	 * Get SCSS Variables
	 *
	 * @since 2.14.0
	 *
	 * @return array The SCSS variables.
	 */
	public function get_scss_variables() {
		$variables  = array(
			'pages-mw-large'         => '1920px',
			'pages-mw-desktop'       => '1200px',
			'pages-mw-tablet'        => '992px',
			'blog-posts-mw-large'    => '1920px',
			'blog-posts-mw-desktop'  => '1200px',
			'blog-posts-mw-tablet'   => '992px',
			'blog-page-mw-large'     => '1920px',
			'blog-page-mw-desktop'   => '1200px',
			'blog-page-mw-tablet'    => '992px',
			'woocommerce-mw-large'   => '1920px',
			'woocommerce-mw-desktop' => '1200px',
			'woocommerce-mw-tablet'  => '992px',
		);
		$post_types = array( 'woocommerce', 'pages', 'blog_posts', 'blog_page' );
		foreach ( $post_types as $post_type ) {
			$max_width_mod = get_theme_mod( 'bgtfw_' . $post_type . '_container_max_width' );
			if ( empty( $max_width_mod ) ) {
				$variables[ $post_type . '-mw-base' ] = '100%';
			} elseif ( isset( $max_width_mod[0]['media'] ) ) {
				foreach ( $max_width_mod as $media_set ) {
					foreach ( $media_set['media'] as $device ) {
						$variables[ str_replace( '_', '-', $post_type ) . '-mw-' . $device ] = $media_set['values']['maxWidth'] . $media_set['unit'];
					}
				}
			} elseif ( isset( $max_width_mod['media'] ) ) {
				foreach ( json_decode( $max_width_mod['media'], true ) as $device => $media_set ) {
					$variables[ str_replace( '_', '-', $post_type ) . '-mw-' . $device ] = $media_set['values']['maxWidth'] . $media_set['unit'];
				}
			}
		}
		return $variables;
	}

	/**
	 * Get Container Max Width
	 *
	 * @since 2.14.0
	 *
	 * @param WP_Post $post The post object.
	 *
	 * @return array The container width.
	 */
	public function get_max_width( $post ) {
		$max_width_mod = $this->get_container_theme_mod( $post, true );

		$max_width = array(
			'mw-large'   => '1920px',
			'mw-desktop' => '1200px',
			'mw-tablet'  => '992px',
		);

		if ( empty( $max_width_mod ) ) {
			return $max_width;
		}

		if ( isset( $max_width_mod[0]['media'] ) ) {
			foreach ( $max_width_mod as $media_set ) {
				foreach ( $media_set['media'] as $device ) {
					$max_width[ 'mw-' . $device ] = $media_set['values']['maxWidth'] . $media_set['unit'];
				}
			}
		} elseif ( isset( $max_width_mod['media'] ) ) {
			foreach ( json_decode( $max_width_mod['media'], true ) as $device => $media_set ) {
				$max_width[ 'mw-' . $device ] = $media_set['values']['maxWidth'] . $media_set['unit'];
			}
		}

		return $max_width;
	}
}
