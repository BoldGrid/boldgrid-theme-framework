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

	public function get_container_theme_mod( $post, $max_full_width = false ) {
		$page_post_type = $this->get_page_post_type( $post );
		$theme_mod_name = 'bgtfw_' . $page_post_type . '_container' . ( $max_full_width ? '_max_width' : '' );
		return get_theme_mod( $theme_mod_name );
	}

	public function get_page_post_type( $post ) {
		global $boldgrid_theme_framework;
		$post_type = 'blog_page';

		// Home Page is a static page.
		if ( $boldgrid_theme_framework->woo->is_woocommerce_page() ) {
			error_log( 'Is Woo' );
			$post_type = 'woocommerce';
		} elseif ( is_front_page() && 'page' === get_option( 'show_on_front' ) || is_page() ) {
			error_log( 'Is pages' );
			$post_type = 'pages';
		} else if ( is_single() || is_attachment() || ( function_exists( 'is_shop' ) && is_shop() ) ) {
			error_log( 'Is posts' );
			$post_type = 'blog_posts';
		}
		return $post_type;
	}

	public function get_max_width( $post ) {
		$max_width_mod = $this->get_container_theme_mod( $post, true );

		error_log( 'max_width_mod' . json_encode( $max_width_mod ) );

		if ( empty( $max_width_mod ) ) {
			return array('mw-base' => '100%' );
		}

		$max_width = array();

		if ( isset ( $max_width_mod[0]['media'] ) ) {
			foreach ( $max_width_mod as $media_set ) {
				error_log( 'media_set' . json_encode( $media_set ) );
				foreach( $media_set['media'] as $device ) {
					error_log( 'device' . json_encode( $device ) );
					$max_width[ 'mw-' . $device ] = $media_set['values']['maxWidth'] . $media_set[ 'unit' ];
				}
			}
		} else if ( isset ( $max_width_mod['media'] ) ) {
			foreach ( json_decode( $max_width_mod['media'], true ) as $device => $media_set ) {
				error_log( $device . ' media_set: ' . json_encode( $media_set, true ) );
				$max_width[ 'mw-' . $device ] = $media_set['values']['maxWidth'] . $media_set[ 'unit' ];
			}
		} else {
			return array( 'mw-base' => '100%' );
		}
		error_log( json_encode( $max_width ) );
		return $max_width;
	}
}
