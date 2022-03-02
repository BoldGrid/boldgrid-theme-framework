<?php
/**
 * Class: BoldGrid
 *
 * This class contains methods used to display theme markup
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Api
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid
 *
 * This class contains methods used to display theme markup
 *
 * @since      1.0.0
 */
class BoldGrid {

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
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Site Title.
	 *
	 * This will return the site title in an h1 tag.
	 *
	 * @since 1.0.0
	 */
	public static function site_title() {
		global $boldgrid_theme_framework;
		$configs = $boldgrid_theme_framework->get_configs();

		// Bug fix 9/28/15: https://codex.wordpress.org/Function_Reference/is_home.
		if ( ( ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) || ( is_front_page() && is_home() ) ) && ( 1 === did_action( 'boldgrid_site_identity' ) ) ) {
			$title_tag = $configs['template']['site-title-tag'];
		} else {
			$title_tag = 'p';
		}

		// Site title link.
		$display = get_theme_mod( 'bgtfw_site_title_display' ) === 'hide' ? ' screen-reader-text' : '';
		echo '<' . $title_tag . ' class="' . esc_attr( $configs['template']['site-title-classes'] ) . esc_attr( $display ) . '">' .
			'<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a>' .
			'</' . esc_html( $title_tag ) . '>';
	}

	/**
	 * Site Logo.
	 *
	 * This will return a logo from the WordPress customizer that is stored
	 * under the option for the boldgrid_logo_setting.
	 *
	 * @since    1.0.0
	 */
	public static function site_logo() {
		$image_attributes = wp_get_attachment_image_src( absint( get_theme_mod( 'boldgrid_logo_setting' ) ), 'full' );
		$alt = get_post_meta( get_theme_mod( 'boldgrid_logo_setting' ), '_wp_attachment_image_alt', true );
		$alt = empty( $alt ) ? '' : $alt;

		if ( $image_attributes ) { ?>
		<div class="site-title">
			<a class='logo-site-title' href="<?php echo esc_url( home_url( '/' ) ); ?>"  rel="home">
				<img alt="<?php echo esc_attr( $alt ); ?>" src="<?php echo esc_attr( $image_attributes[0] ); ?>" width="<?php echo esc_attr( $image_attributes[1] ); ?>" height="<?php echo esc_attr( $image_attributes[2] ); ?>" />
			</a>
		</div>
		<?php }
	}

	/**
	 * Site title or logo.
	 *
	 * This will return a logo from the WordPress customizer that is stored
	 * under the option for the boldgrid_logo_setting if a logo is updated.
	 * Otherwise, it will return the site title with BoldGrid::site_title(  ).
	 *
	 * @uses     BoldGrid::site_logo(  ); BoldGrid::site_title(  );
	 * @since    1.0.0
	 */
	public function site_logo_or_title() {
		$background_image = get_theme_mod( 'boldgrid_logo_setting' );
		$background_image ? self::site_logo() : self::site_title();
	}

	/**
	 * Print tagline under site title - boldgrid_print_tagline hook
	 *
	 * @since 1.0.0
	 */
	public function print_tagline() {
		// Retrieve blog tagline.
		$blog_info = get_bloginfo( 'description' );
		$display   = get_theme_mod( 'bgtfw_tagline_display' ) === 'hide' ? ' screen-reader-text' : '';

		if ( $blog_info ) {
			$classes = $this->configs['template']['tagline-classes'] . $display;
		} else {
			$classes = $this->configs['template']['tagline-classes'] . ' site-description invisible';
		}

		printf( wp_kses_post( $this->configs['template']['tagline'] ), esc_attr( $classes ), esc_html( $blog_info ) );
	}

	/**
	 * Print the sites title and tagline together.
	 *
	 * @since   1.0.0
	 */
	public function print_title_tagline() {
	?>
		<div <?php BoldGrid::add_class( 'site_branding', [ 'site-branding' ] ); ?>>
			<?php if ( function_exists( 'the_custom_logo' ) ) : ?>
				<?php the_custom_logo(); ?>
			<?php endif; ?>
			<?php do_action( 'boldgrid_site_title' ); ?>
			<?php do_action( 'boldgrid_print_tagline' ); ?>
		</div><!-- .site-branding -->
		<?php
	}

	/**
	 * Add blog container classes.
	 *
	 * @since 2.0.0
	 *
	 * @param array $classes Classes added to .site-content element.
	 *
	 * @return array $classes Filter classes on .site-content element.
	 */
	public function blog_container( $classes ) {
		global $boldgrid_theme_framework;
		$theme_mod_type = $boldgrid_theme_framework->woo->is_woocommerce_page() ? 'bgtfw_woocommerce_container' : 'bgtfw_blog_page_container';
		if ( is_single() || is_attachment() ) {
			$theme_mod = get_theme_mod( $theme_mod_type );
			if ( 'max-full-width' === $theme_mod ) {
				$classes[] = 'max-full-width';
				$classes[] = 'full-width';
			} else {
				$classes[] = $theme_mod = empty( $theme_mod ) ? 'full-width' : 'container';
			}
		}

		return $classes;
	}

	/**
	 * Add page container classes.
	 *
	 * @since 2.0.0
	 *
	 * @param array $classes Classes added to .site-content element.
	 *
	 * @return array $classes Filter classes on .site-content element.
	 */
	public function page_container( $classes ) {
		global $boldgrid_theme_framework;
		$theme_mod_type = $boldgrid_theme_framework->woo->is_woocommerce_page() ? 'bgtfw_woocommerce_container' : 'bgtfw_pages_container';
		if ( is_page() || ( $boldgrid_theme_framework->woo->is_woocommerce_page() && is_shop() ) ) {

			$theme_mod = get_theme_mod( $theme_mod_type );
			if ( 'max-full-width' === $theme_mod ) {
				$classes[] = 'max-full-width';
				$classes[] = 'full-width';
			} else {
				$classes[] = empty( $theme_mod ) ? 'full-width' : 'container';
			}

			error_log( 'page_container: ' . json_encode(
				array(
					'pages_container' => $theme_mod,
					'theme_mod_type' => $theme_mod_type,
					'classes'        => $classes,
				)
			) );
		}

		return $classes;
	}

	/**
	 * Add woocommerce container classes.
	 *
	 * @since 2.1.18
	 *
	 * @param array $classes Classes added to .site-content element.
	 *
	 * @return array $classes Filter classes on .site-content element.
	 */
	public function woocommerce_container( $classes ) {
		global $boldgrid_theme_framework;

		if ( $boldgrid_theme_framework->woo->is_woocommerce_page() ) {
			$theme_mod = get_theme_mod( 'bgtfw_woocommerce_container' );
			if ( 'max-full-width' === $theme_mod ) {
				$classes[] = 'max-full-width';
				$classes[] = 'full-width';
			} else {
				$classes[] = $theme_mod = empty( $theme_mod ) ? 'full-width' : 'container';
			}
		}

		return $classes;
	}

	/**
	 * Add blog page container classes.
	 *
	 * @since 2.0.0
	 *
	 * @param array $classes Classes added to .main element.
	 *
	 * @return array $classes Filter classes on .main element.
	 */
	public function blog_page_container( $classes ) {
		global $wp_query;
		global $boldgrid_theme_framework;

		$theme_mod_type = 'bgtfw_pages_container';

		if ( $boldgrid_theme_framework->woo->is_woocommerce_page() ) {
			$theme_mod_type = 'bgtfw_woocommerce_container';
		} elseif ( is_single() || is_attachment() || ( function_exists( 'is_shop' ) && is_shop() ) ) {
			$theme_mod_type = 'bgtfw_blog_posts_container';
		} elseif ( is_front_page() && is_home() ) {
			// Default Homepage ( Latest Posts ).
			$theme_mod_type      = 'bgtfw_blog_page_container';
		} elseif ( is_front_page() ) {
			// Static Homepage.
			return $classes;
		} elseif ( is_archive() ) {
			$theme_mod_type = 'bgtfw_blog_page_container';
		}

		error_log( 'blog_page_container: ' . json_encode(
			array(
				'blog_page_container' => $theme_mod,
				'theme_mod_type'      => $theme_mod_type,
				'classes'             => $classes,
			)
		) );

		$theme_mod = get_theme_mod( $theme_mod_type );

		if ( 'max-full-width' === $theme_mod ) {
			$classes[] = 'max-full-width';
			$classes[] = 'full-width';
		} else {
			$classes[] = $theme_mod = empty( $theme_mod ) ? 'full-width' : 'container';
		}

		return $classes;
	}

	/**
	 * Add title container classes.
	 *
	 * @since 2.0.3
	 *
	 * @param array $classes Classes added to .page-title-wrapper element.
	 *
	 * @return array $classes Filter classes on .main element.
	 */
	public function title_container( $classes ) {
		$class = 'full-width';

		if ( 'above' === get_theme_mod( 'bgtfw_global_title_position' ) ) {
			$class = get_theme_mod( 'bgtfw_global_title_background_container' );
		}

		$classes[] = $class;

		return $classes;
	}

	/**
	 * Add title content container classes.
	 *
	 * @since 2.0.3
	 *
	 * @param array $classes Classes added to .main element.
	 *
	 * @return array $classes Filter classes on .main element.
	 */
	public function title_content_container( $classes ) {
		$class = 'container';

		if ( 'above' === get_theme_mod( 'bgtfw_global_title_position' ) && 'full-width' === get_theme_mod( 'bgtfw_global_title_background_container' ) ) {
			$class = get_theme_mod( 'bgtfw_global_title_content_container' );
		}

		$classes[] = $class;

		return $classes;
	}

	/**
	 * Adds custom classes to the array of header classes.
	 *
	 * @param array $classes An array of header classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array $classes array of classes to be applied to the #masthead element.
	 */
	public function header_classes( $classes ) {
		// Do not add background color classess if this is a Custom Sticky Template.
		if ( ! in_array( 'template-sticky-header', $classes, true ) ) {
			$classes = array_merge( $classes, $this->get_background_color( 'bgtfw_header_color' ) );
		}
		return $classes;
	}

	/**
	 * Adds custom classes to the array of navi classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array $classes array of classes to be applied to the #navi element.
	 */
	public function navi_classes( $classes ) {
		$classes[] = self::get_container_classes( 'header' );
		return $classes;
	}

	/**
	 * Adds custom classes to the array of footer classes.
	 *
	 * @param array $classes An array of footer classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array $classes array of classes to be applied to the .site-footer element.
	 */
	public function footer_classes( $classes ) {
		$classes[] = get_theme_mod( 'bgtfw_footer_layouts' );
		// Do not add background classess if this is a Custom Footer Template.
		if ( ! in_array( 'template-footer', $classes, true ) ) {
			$classes = array_merge( $classes, $this->get_background_color( 'bgtfw_footer_color' ) );
		} else {
			$classes[] = 'color-neutral-background-color';
			$classes[] = 'color-neutral-text-color';
		}

		return $classes;
	}

	/**
	 * Adds custom classes to the array of inner footer classes.
	 *
	 * @param array $classes An array of footer classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array $classes array of classes to be applied to the #masthead element.
	 */
	public function inner_footer_classes( $classes ) {
		// Do not add background classess if this is a Custom Footer Template.
		if ( ! in_array( 'template-footer', $classes, true ) ) {
			$classes = array_merge(
				$classes,
				$this->get_background_color( 'bgtfw_footer_color' ),
				$this->get_link_color( 'bgtfw_footer_links' )
			);
		}

		return $classes;
	}

	/**
	 * Get color classes for a property, given a theme mod.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $color       Palette Selector theme_mod value.
	 * @param  array  $properties  A list of properties to check..
	 *
	 * @return array  $classes    Classes to add.
	 */
	public static function get_color_classes( $color, $properties ) {
		$color_class = explode( ':', $color );
		$color_class = array_shift( $color_class );
		if ( strpos( $color_class, 'neutral' ) === false ) {
			$color_class = str_replace( '-', '', $color_class );
		}

		$classes = array();
		foreach ( $properties as $property ) {
			$classes[] = $color_class . '-' . $property;
		}

		return $classes;
	}

	/**
	 * Get background colors.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $mod     Palette Selector theme_mod to get value for.
	 *
	 * @return array  $classes Classes for background color/text contrast.
	 */
	public function get_background_color( $mod ) {
		$color = get_theme_mod( $mod );
		$color_class = explode( ':', $color );
		$color_class = array_shift( $color_class );
		if ( strpos( $color_class, 'neutral' ) === false ) {
			$color_class = str_replace( '-', '', $color_class );
		}
		$classes[] = $color_class . '-background-color';
		$classes[] = $color_class . '-text-default';
		return $classes;
	}

	/**
	 * Get link colors.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $mod     Palette Selector theme_mod to get value for.
	 *
	 * @return array  $classes Classes for link colors.
	 */
	public function get_link_color( $mod ) {
		$color = get_theme_mod( $mod );
		$color = explode( ':', $color );
		$classes[] = array_shift( $color ) . '-link-color';
		return $classes;
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param     array $classes Classes for the body element.
	 * @return    array
	 * @since     1.0.0
	 */
	public function body_classes( $classes ) {
		global $post;

		if ( is_object( $post ) ) {
			$post_id = absint( $post->ID );
		}

		if ( ! is_front_page() && is_home() ) {
			$post_id = ( int ) get_option( 'page_for_posts' );
		}

		if ( get_theme_mod( '_boldgrid_theme_id' ) ) {
			$classes[] = 'inspirations-theme-' . get_theme_mod( '_boldgrid_theme_id' );
		}

		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author( ) ) {
			$classes[] = 'group-blog';
		}
		// Add class if sidebar is active.
		if ( $this->display_sidebar() ) {
			$classes[] = 'has-sidebar sidebar-1';
		}

		// Add class for page and post titles being hidden.
		if ( is_archive() ) {
			if ( 'hide' === get_theme_mod( 'bgtfw_pages_title_display' ) ) {
				$classes[] = 'page-header-hidden';
			}
		} else if ( $post ) {
			if ( is_page() || ( ! is_front_page() && is_home() ) ) {
				$keys = get_post_custom_keys( $post_id );
				if ( is_array( $keys ) && in_array( 'boldgrid_hide_page_title', $keys, true ) ) {
					$post_meta = get_post_meta( $post_id );
					if ( empty( $post_meta['boldgrid_hide_page_title'][0] ) ) {
						$classes[] = 'page-header-hidden';
					} else if ( 'global' === $post_meta['boldgrid_hide_page_title'][0] &&
						'hide' === get_theme_mod( 'bgtfw_pages_title_display' ) ) {
							$classes[] = 'customizer-page-header-hidden';
					} else if ( 1 === absint( $post_meta['boldgrid_hide_page_title'][0] ) ) {
						$classes[] = 'page-header-shown';
					}
				} else if ( 'hide' === get_theme_mod( 'bgtfw_pages_title_display' ) ) {
					$classes[] = 'customizer-page-header-hidden';
				}
			} else if ( is_single() ) {

				// Check if the key is set for the post meta.
				$keys = get_post_custom_keys( $post_id );
				if ( is_array( $keys ) && in_array( 'boldgrid_hide_page_title', $keys, true ) ) {
					$post_meta = get_post_meta( $post_id );

					// If the post meta is empty and global display for post titles is off, hide the header.
					if ( empty( $post_meta['boldgrid_hide_page_title'][0] ) ) {
						if ( 'none' === get_theme_mod( 'bgtfw_posts_meta_display' ) ) {
							$classes[] = 'customizer-page-header-hidden';
						}

					// If the post meta is set to use the global settings check those and hide the header.
					} else if ( 'global' === $post_meta['boldgrid_hide_page_title'][0] ) {
						if ( 'hide' === get_theme_mod( 'bgtfw_posts_title_display' ) &&
							'none' === get_theme_mod( 'bgtfw_posts_meta_display' ) ) {
								$classes[] = 'customizer-page-header-hidden';
						}
					} else if ( 1 === absint( $post_meta['boldgrid_hide_page_title'][0] ) ) {
						$classes[] = 'page-header-shown';
					}

				// Otherwise only rely on global settings for post title and meta.
				} else if ( 'hide' === get_theme_mod( 'bgtfw_posts_title_display' ) && 'none' === get_theme_mod( 'bgtfw_posts_meta_display' ) ) {
					$classes[] = 'customizer-page-header-hidden';
				}
			}
		}

		// Check if we are on a blog roll page (not archive).
		global $wp_query;

		if ( ( isset( $wp_query ) && ( bool ) $wp_query->is_posts_page ) || is_home() || is_archive() ) {
			$classes[] = 'col' . get_theme_mod( 'bgtfw_pages_blog_blog_page_layout_columns' );
		}

		if ( true === $this->configs['scripts']['boldgrid-sticky-nav'] ) {
			$classes[] = 'sticky-nav-enabled';
		}

		if ( true === $this->configs['scripts']['boldgrid-sticky-footer'] ) {
			$classes[] = 'sticky-footer-enabled';
		}

		if ( true === $this->configs['scripts']['wow-js'] ) {
			$classes[] = 'wow-js-enabled';
		}

		if ( true === $this->configs['scripts']['animate-css'] ) {
			$classes[] = 'animate-css-enabled';
		}

		if ( true === $this->configs['scripts']['options']['nicescroll']['enabled'] ) {
			$classes[] = 'nicescroll-enabled';
		}

		if ( 'show' === get_theme_mod( 'bgtfw_scroll_to_top_display' ) ) {
			$classes[] = 'goup-enabled';
		}

		$classes[] = 'standard-menu-enabled';

		if ( true === $this->configs['edit-post-links']['enabled'] ) {
			$classes[] = 'bgtfw-edit-links-shown';
		} else {
			$classes[] = 'bgtfw-edit-links-hidden';
		}

		$classes[] = 'custom-header';

		if ( get_theme_mod( 'bgtfw_fixed_header' ) || apply_filters( 'crio_premium_get_sticky_page_header', $post_id ) ) {
			if ( 'header-top' === get_theme_mod( 'bgtfw_header_layout_position' ) ) {
				$classes[] = 'header-slide-in';
			} else {
				$classes[] = 'header-fixed';
			}
		}

		if ( ! wp_is_mobile() ) {

			// Check if a YouTube video has been added to header background.
			if ( '' !== get_theme_mod( 'external_header_video' ) ) {
				if ( isset( $classes['has-video-header'] ) ) {
					unset( $classes['has-video-header'] );
				}
			}

			// Check if an uploaded video has been provided to give precedence over YouTube video.
			if ( 0 !== get_theme_mod( 'header_video' ) && '' !== get_theme_mod( 'header_video' ) ) {
				$classes[] = 'has-video-header';
				if ( isset( $classes['has-youtube-header'] ) ) {
					unset( $classes['has-youtube-header'] );
				}
			}
		}

		// Add a class if there is a custom header image.
		if ( has_header_image() ) {
			$classes[] = 'has-header-image';
		}

		$classes[] = get_theme_mod( 'bgtfw_header_layout_position' );

		if ( is_home() || is_archive() ) {
			$classes[] = get_theme_mod( 'bgtfw_blog_blog_page_sidebar' );
		} else {
			$layout = get_page_template_slug();

			if ( empty( $layout ) ) {
				$type = 'page' === get_post_type() ? 'page' : 'blog';
				$layout = get_theme_mod( 'bgtfw_layout_' . $type, '' );
			}

			$classes[] = sanitize_html_class( $layout );
		}

		$background_theme_mod = 'boldgrid_background_color';
		$background_image = get_theme_mod( 'background_image' );

		// Add class for parallax background option.
		if ( 'parallax' === get_theme_mod( 'background_attachment' ) ) {
			$classes[] = 'boldgrid-customizer-parallax';
		} else {
			if (
				'pattern' !== get_theme_mod( 'boldgrid_background_type' ) &&
				! empty( $background_image ) &&
				true === get_theme_mod( 'bgtfw_background_overlay' )
			) {
				$background_theme_mod = 'bgtfw_background_overlay_color';
			}
		}

		$background_color = get_theme_mod( $background_theme_mod );
		$background_color = explode( ':', $background_color );
		$background_color = array_shift( $background_color );

		if ( ! empty( $background_color ) ) {
			if ( strpos( $background_color, 'neutral' ) !== false ) {
				$classes[] = $background_color . '-background-color';
				$classes[] = $background_color . '-text-default';
			} else {
				$classes[] = str_replace( '-', '', $background_color ) . '-background-color';
				$classes[] = str_replace( '-', '', $background_color ) . '-text-default';
			}
		}

		// Add helper class for global page title positioning.
		$classes[] = 'page-title-' . get_theme_mod( 'bgtfw_global_title_position' );

		return array_unique( $classes );
	}

	/**
	 * Filters the nav menu css and adds classes to menu locations.
	 *
	 * @since 2.0.0
	 *
	 * @param array $config Array of bgtfw configurations.
	 */
	public function menu_border_color( $config ) {
		foreach ( $config['menu']['locations'] as $location => $description ) {

			// Filter per menu location.
			$filter = function( $classes, $item, $args ) use ( $location ) {

				// Verify location.
				if ( $args->theme_location === $location ) {

					// Only apply these to top level menu items, active menu items have their own controls.
					if ( empty( $item->menu_item_parent ) && ! in_array( 'current-menu-item', $classes ) ) {
						$color = get_theme_mod( "bgtfw_menu_items_border_color_{$location}" );
						$color = explode( ':', $color );
						$color = array_shift( $color );
						if ( strpos( $color, 'neutral' ) !== false ) {
							$color = $color . '-border-color';
						} else {
							$color = str_replace( '-', '', $color ) . '-border-color';
						}
						$classes[] = $color;
					}

					// Apply active menu item styles.
					if ( count( array_intersect( $classes, array( 'current-menu-item', 'current_page_parent', 'current-post-parent' ) ) ) ) {
						$classes[] = implode( ' ', $this->get_color_classes( get_theme_mod( "bgtfw_menu_items_active_link_background_{$location}" ), [ 'background-color' ] ) );
						$classes[] = implode( ' ', $this->get_color_classes( get_theme_mod( "bgtfw_menu_items_active_link_border_color_{$location}" ), [ 'border-color' ] ) );
					}

					// Apply to all other menu items that aren't active menu items.
					if ( ! count( array_intersect( $classes, array( 'current-menu-item', 'current_page_parent', 'current-post-parent' ) ) ) ) {
						$hover_effect = get_theme_mod( "bgtfw_menu_items_hover_effect_{$location}" );
						$hover_effect = $hover_effect ? $hover_effect : 'hvr-none';
						$classes[]    = $hover_effect;
					}
				}

				return $classes;
			};

			add_filter( 'nav_menu_css_class', $filter, 10, 3 );
		}
	}

	/**
	 * Apply the blog design to posts page.
	 *
	 * @since 2.0.0
	 *
	 * @param array $classes Array of classes to add to posts.
	 *
	 * @return array $classes Array of classes to add to posts.
	 */
	public function post_class( $classes ) {
		global $post;
		if ( ( isset( $wp_query ) && ( bool ) $wp_query->is_posts_page ) || is_home() || is_archive() ) {
			$classes = array_merge( $classes, [ 'design-1', 'wow', 'fadeIn' ], $this->get_background_color( 'bgtfw_blog_post_background_color' ) );
		}

		return $classes;
	}

	/**
	 * Apply the blog design to posts page.
	 *
	 * @since 2.0.0
	 *
	 * @param array $classes Array of classes to add to posts.
	 *
	 * @return array $classes Array of classes to add to posts.
	 */
	public function page_title_background_class( $classes ) {
		return array_merge( $classes, $this->get_background_color( 'bgtfw_global_title_background_color' ) );
	}

	/**
	 * Apply the blog design to posts page.
	 *
	 * @since 2.0.0
	 *
	 * @param array $classes Array of classes to add to posts.
	 *
	 * @return array $classes Array of classes to add to posts.
	 */
	public function blog_page_post_title_classes( $classes ) {
		global $wp_query;
		if ( ( isset( $wp_query ) && ( bool ) $wp_query->is_posts_page ) || is_home() || is_archive() ) {
			$classes = array_merge( $classes, $this->get_color_classes( get_theme_mod( 'bgtfw_blog_post_header_title_color' ), [ 'color', 'color-hover' ] ) );
		}

		return $classes;
	}

	/**
	 * Apply page title classes.
	 *
	 * @since 2.0.0
	 *
	 * @param array $classes Array of classes to add to posts.
	 *
	 * @return array $classes Array of classes to add to posts.
	 */
	public function page_title_classes( $classes ) {
		return array_merge( $classes, $this->get_color_classes( get_theme_mod( 'bgtfw_global_title_color' ), [ 'color', 'color-hover' ] ) );
	}

	/**
	 * Apply post title classes.
	 *
	 * @since 2.0.0
	 *
	 * @param array $classes Array of classes to add to posts.
	 *
	 * @return array $classes Array of classes to add to posts.
	 */
	public function post_title_classes( $classes ) {
		if ( is_single() || is_attachment() ) {
			$classes = array_merge( $classes, $this->get_color_classes( get_theme_mod( 'bgtfw_global_title_color' ), [ 'color', 'color-hover' ] ) );
		}

		return $classes;
	}

	/**
	 * Adds custom classes to the array of entry-header classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array $classes array of classes to be applied to the .entry-header element.
	 */
	public function entry_header_classes( $classes ) {
		global $post;
		if ( ( isset( $wp_query ) && ( bool ) $wp_query->is_posts_page ) || is_home() || is_archive() ) {
			$classes = array_merge( $classes, $this->get_background_color( 'bgtfw_blog_header_background_color' ) );
		}

		return $classes;
	}

	/**
	 * Page Menu Arguments.
	 *
	 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	 *
	 * @param    array $args Configuration arguments.
	 * @return   array $args Config args.
	 * @since    1.0.0
	 */
	public function page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}

	/**
	 *  BoldGrid Sticky Top.
	 *
	 *  Adds a wrapper around our site content, so we can implement a sticky footer across all themes.
	 *
	 *  @since 1.0.1
	 */
	public function boldgrid_sticky_top() {
		if ( true === $this->configs['scripts']['boldgrid-sticky-footer'] ) { ?>
			<div id="boldgrid-sticky-wrap">
		<?php }
	}

	/**
	 *  BoldGrid Sticky Bottom.
	 *
	 *  Closes out boldgrid_sticky_top and adds the push and filler blocks
	 *
	 *  @since 1.0.1
	 */
	public function boldgrid_sticky_bottom() {
		if ( true === $this->configs['scripts']['boldgrid-sticky-footer'] ) { ?>
			</div><!-- End of #boldgrid-sticky-wrap -->
		<?php }
	}

	/**
	 * Sidebar path helper.
	 *
	 * Just a helper function to load sidebar template.
	 *
	 * @since 1.1.10
	 * @return Wrapped instance with sidebar.
	 */
	public static function boldgrid_sidebar_path() {
		return new Boldgrid_Framework_Wrapper( 'templates/sidebar.php' );
	}

	/**
	 * Determine which pages should NOT display the sidebar
	 *
	 * @since 1.1.10
	 * @link https://codex.wordpress.org/Conditional_Tags
	 */
	public static function display_sidebar() {
		global $boldgrid_theme_framework;
		static $display;
		$configs = $boldgrid_theme_framework->get_configs();

		// The sidebar will NOT be displayed if ANY of the following return true.
		$conditions = $configs['template']['sidebar'];

		foreach ( $conditions as $condition ) {
			// Split [params]method to useable strings.
			preg_match( '/^\[.*\]/', $condition, $matches );
			$type = ! empty( $matches[0] ) ? $matches[0] : null;
			$param = str_replace( array( '[', ']' ), '', $type );
			$is_page_template = ( strpos( $condition, 'is_page_template' ) !== false );
			switch ( $param ) {
				// Use [parameter]condition as condition( 'parameter' ).
				case( preg_match( '/^\[.*\]/', $condition ) && ! $is_page_template ) :
					$conditions[] = ! function_exists( $condition ) ? : $condition( $param );
					break;
				// Use [default]is_page_template as is_page() && ! is_page_template().
				case ( $is_page_template && 'default' === $param ) :
					$conditions[] = is_page() && ! is_page_template();
					break;
				// Use [specific-template.php]is_page_template as is_page_template('specific-template.php').
				case ( $is_page_template ) :
					$conditions[] = is_page_template( $param );
					break;
				// No params found, so run a basic conditional.
				default :
					$conditions[] = ! function_exists( $condition ) ? : $condition();
			}
		}

		isset( $display ) || $display = ! in_array( true, $conditions, true );
		return apply_filters( 'boldgrid/display_sidebar', $display );
	}

	/**
	 * Determine if user has set homepage to NOT display the sidebar.
	 *
	 * @since 2.0.0
	 * @link https://codex.wordpress.org/Conditional_Tags
	 *
	 * @return Boolean $display Whether or not to display the sidebar on queried post.
	 */
	public function post_list_sidebar( $display ) {
		if ( is_home() || is_archive() ) {
			$display = 'no-sidebar' !== get_theme_mod( 'bgtfw_blog_blog_page_sidebar' );
		}

		return $display;
	}

	/**
	 * Framework version check.
	 *
	 * @since 1.3.5
	 *
	 * @param String $version Minimum required version to check against.
	 *
	 * @return Boolean Version is greater than or equal to passed in $version.
	 */
	public function framework_version( $version ) {
		return version_compare( $this->configs['framework-version'], $version, '>=' );
	}

	/**
	 * Adds markup for password protected pages/posts.
	 *
	 * @since 1.3.6
	 *
	 * @return String $output HTML to be rendered for password protected form.
	 */
	public function password_form() {
		global $post;
		$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
		$output = '
		<div class="container">
			<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="form-inline post-password-form" method="post">
				<p>' . __( 'This content is password protected. To view it please enter your password below:', 'bgtfw' ) . '</p>
				<label for="' . esc_attr( $label ) . '">' . __( 'Password:', 'bgtfw' ) . '</label>
				<input name="post_password" id="' . esc_attr( $label ) . '" type="password" size="20" class="form-control" />
				<button type="submit" name="Submit" class="button-primary">' . esc_attr_x( 'Enter', 'post password form', 'bgtfw' ) . '</button>
			</form>
		</div>';

		return $output;
	}

	/**
	 * Display the classes for the header element.
	 *
	 * Note: Boldgrid_Framework_Element_Class creates filters for adding and removing
	 * classes in the BGTFW codebase.  This class also handles escaping the output of
	 * the classes, and generates class="$classes" for HTML output.
	 *
	 * @since 2.8.0
	 *
	 * @param string       $element Element class is being added to.
	 * @param string|array $class   One or more classes to add to the class list.
	 */
	public static function add_class( $element = '', $class = '', $echo = true ) {
		$el = new Boldgrid_Framework_Element_Class( $element, $class );
		$html = (string) $el->html;

		if ( $echo ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $html;
		}
	}

	/**
	 * Get container classes for a location.
	 *
	 * @since 2.0.0
	 *
	 * @return string $container String containing classes to add to an element's container.
	 */
	public static function get_container_classes( $location = '' ) {
		$container = new Boldgrid_Framework_Container( $location );
		return $container->classes;
	}

	/**
	 * New Column Control.
	 *
	 * For users using the new column control,
	 * the values are stored differently.
	 *
	 * @since 2.7.0
	 *
	 * @param string $theme_mod The thememod to reference.
	 */
	public static function new_column_control( $theme_mod ) {
		$col_width_option = get_theme_mod( 'bgtfw_' . $theme_mod . '_layout_custom_col_width' );
		$col_width        = array(
			'lg'         => array(),
			'md'         => array(),
			'sm'         => array(),
			'xs'         => array(),
			'full_width' => array(),
		);

		foreach ( $col_width_option as $uid => $uid_values ) {
			if ( 'fullWidth' === $uid ) {
				foreach ( $uid_values as $index => $row ) {
					foreach ( $row as $device => $cols ) {
						switch ( $device ) {
							case 'large':
								$col_width['full_width'][ $index ]['lg'] = $cols;
								break;
							case 'desktop':
								$col_width['full_width'][ $index ]['md'] = $cols;
								break;
							case 'tablet':
								$col_width['full_width'][ $index ]['sm'] = $cols;
								break;
							case 'phone':
								$col_width['full_width'][ $index ]['xs'] = $cols;
								break;
						}
					}
				}
				continue;
			}
			foreach ( $uid_values as $device => $cols ) {
				switch ( $device ) {
					case 'large':
						$col_width['lg'][ $uid ] = $cols;
						break;
					case 'desktop':
						$col_width['md'][ $uid ] = $cols;
						break;
					case 'tablet':
						$col_width['sm'][ $uid ] = $cols;
						break;
					case 'phone':
						$col_width['xs'][ $uid ] = $cols;
						break;
				}
			}
		}

		return $col_width;
	}

	/**
	 * Get Column Width Array.
	 *
	 * @since 2.2.3
	 *
	 * @param string $theme_mod Theme Mod to parse.
	 *
	 * @return array Array of Column Width Values.
	 */
	public static function get_column_widths( $theme_mod, $preset, $layout_type ) {
		global $boldgrid_theme_framework;
		$configs = $boldgrid_theme_framework->get_configs();

		if ( 'footer' === $layout_type ) {
			return array();
		}

		if ( 'custom' === $preset && get_theme_mod( 'bgtfw_' . $layout_type . '_layout_custom_col_width' ) ) {
			return self::new_column_control( $layout_type );
		} elseif ( 'default' !== $preset ) {
			return array();
		}

		$device_column_widths = array();

		$type = ( false !== strpos( $layout_type, 'header' ) ) ? ( false !== strpos( $layout_type, 'sticky_header' ) )
			? 'sticky_header'
			: 'header'
			: 'footer';

		// if the theme_mod is a json array, we need to decode it to use it as a php array.
		$raw_col_widths = get_theme_mod(
			'bgtfw_' . $type . '_layout_col_width',
			$configs['customizer']['controls']['bgtfw_header_layout_col_width']['default'] );
		if ( is_string( $raw_col_widths ) ) {
			$col_width_theme_mod = json_decode( $raw_col_widths, true );
		} else {
			$col_width_theme_mod = isset( $raw_col_widths['media'] )
				? json_decode( $raw_col_widths['media'], true )
				: $raw_col_widths[0]['media'];
		}

		foreach ( $col_width_theme_mod as $device => $values ) {
			if ( isset( $values['media'][0] ) ) {
				$device_column_widths[ $values['media'][0] ] = $values['values'];
			} elseif ( is_string( $values ) ) {
				$device_column_widths[ $values ] = 6;
			} else {
				$device_column_widths[ $device ] = $values['values'];
			}
		}

		// if the various device sizes are not set, default to the setting for 'large' devices.
		$column_widths = array(
			'lg' => $device_column_widths['large'],
			'md' => isset( $device_column_widths['desktop'] ) ? $device_column_widths['desktop'] : $device_column_widths['large'],
			'sm' => isset( $device_column_widths['tablet'] ) ? $device_column_widths['tablet'] : $device_column_widths['large'],
			'xs' => isset( $device_column_widths['phone'] ) ? $device_column_widths['phone'] : $device_column_widths['large'],
		);

		return $column_widths;
	}

	/**
	 * Outputs dynamic layout elements.
	 *
	 * @since 2.0.3
	 *
	 * @param  string $theme_mod Theme mod to parse.
	 *
	 * @return string $markup    Rendered HTML for dyanmic layout element.
	 */
	public static function dynamic_layout( $theme_mod, $preset = null, $custom_layout = null ) {
		$layout_type = '';
		if ( false !== strpos( $theme_mod, 'sticky_header' ) ) {
			$layout_type = 'sticky_header';
		} elseif ( false !== strpos( $theme_mod, 'header' ) ) {
			$layout_type = 'header';
		} else {
			$layout_type = 'footer';
		}

		$markup        = '';
		$theme_mod     = self::create_uids( $theme_mod, $preset, $custom_layout );
		$preset        = $preset ? $preset : get_theme_mod( 'bgtfw_' . $layout_type . '_preset', 'default' );
		$column_widths = self::get_column_widths( $theme_mod, $preset, $layout_type );
		if ( 'default' === $preset && get_theme_mod( '_boldgrid_theme_id' ) ) {
			$hidden_items = array_diff(
				array( 'title', 'logo', 'description' ),
				array( 'title' )
			);
		} else {
			$hidden_items = array_diff(
				array( 'title', 'logo', 'description' ),
				get_theme_mod( 'bgtfw_' . $layout_type . '_preset_branding', array( 'title', 'logo' ) )
			);
		}
		if ( 'default' !== $preset && array( 'logo' ) === get_theme_mod( 'bgtfw_' . $layout_type . '_preset_branding' ) && ! get_theme_mod( 'custom_logo' ) ) {
			$hidden_items = array( 'logo', 'description' );
		}

		$hidden_item_classes = array();

		foreach ( $hidden_items as $index => $item ) {
			$hidden_item_classes[] = 'hide-' . $item;
		}

		if ( ! empty( $theme_mod ) ) {
			foreach ( $theme_mod as $section_index => $section ) {

				$section_number = $section_index + 1;
				// Skip loop if no items.
				if ( empty( $section['items'] ) ) {
					continue;
				}

				$markup .= '<div class="boldgrid-section ' . $preset . '-preset">';
				$markup .= '<div class="' . $section['container'] . '">';
				$chunks = array_chunk( $section['items'], 6, true );

				foreach ( $chunks as $chunk ) {
					$markup .= '<div class="row">';

					foreach ( $chunk as $col => $col_data ) {
						$md_col = ( 12 / count( $chunk ) );

						$col_uid = isset( $col_data['uid'] ) ? $col_data['uid'] : 'default_' . $col_data['key'];

						if ( isset( $column_widths['lg'][ $col_uid ] ) ) {
							// We have to set each of these values to either the correct device value or to the 'large' device value.
							$lg_col = $column_widths['lg'][ $col_uid ];
							$md_col = isset( $column_widths['md'][ $col_uid ] ) ? $column_widths['md'][ $col_uid ] : $column_widths['lg'][ $col_uid ];
							$sm_col = isset( $column_widths['sm'][ $col_uid ] ) ? $column_widths['sm'][ $col_uid ] : $column_widths['lg'][ $col_uid ];
							$xs_col = isset( $column_widths['xs'][ $col_uid ] ) ? $column_widths['xs'][ $col_uid ] : $column_widths['lg'][ $col_uid ];
						} else {
							// This ensures that if there are not specified column widths for this uid, that the defaults are used.
							$lg_col = $md_col;
							$sm_col = 12;
							$xs_col = 12;
						}

						// Adds support for 5-6 col.
						if ( 2.4 === $md_col ) {
							$md_col = '5s';
						}

						$col_x_full_width = [];
						if ( isset( $column_widths['full_width'] ) && isset( $column_widths['full_width'][ $section_index ] ) ) {
							foreach ( $column_widths['full_width'][ $section_index ] as $device => $full_width ) {
								if ( $full_width ) {
									$col_x_full_width[] = 'col-' . $device . '-full-width';
								}
							}
						}

						$col_x_full_width = implode( ' ', $col_x_full_width );

						if ( false === strpos( $col_uid, 'f' ) ) {
							$align   = isset( $col_data['align'] ) ? $col_data['align'] : '';
							$markup .= '<div class="col-lg-' . $lg_col . ' col-md-' . $md_col . ' col-sm-' . $sm_col . ' col-xs-' . $xs_col . ' ' . $col_uid . ' ' . $align;
							$markup .= $col_x_full_width ? ' ' . $col_x_full_width . '">' : '">';
						} else {
							$num     = ( 12 / count( $chunk ) );
							$align   = isset( $col_data['align'] ) ? $col_data['align'] : '';
							$markup .= '<div class="col-md-' . $num . ' col-sm-12 col-xs-12 ' . $col_uid . ' ' . $align . '">';
						}

						ob_start();
						switch ( $col_data['type'] ) {
							case strpos( $col_data['type'], 'boldgrid_menu_' ) !== false:
								$menu = str_replace( 'boldgrid_menu_', '', $col_data['type'] );
								if ( 'social' === $menu && false !== strpos( $col_uid, 'f' ) ) {
									$menu             = 'footer-social';
									$col_data['type'] = 'boldgrid_menu_footer-social';
								}
								echo '<div id="' . esc_attr( $menu . '-wrap' ) . '" ';
								$menu_classes = array( 'bgtfw-menu-wrap', 'flex-row', $col_data['align'] );
								$ham_classes  = get_theme_mod(
									'bgtfw_menu_hamburger_display_' . $menu,
									array(
										'ham-phone',
										'ham-tablet',
									)
								);
								$menu_classes = implode( ' ', array_merge( $menu_classes, $ham_classes ) );
								echo 'class="' . esc_attr( $menu_classes ) . '">';
								if ( empty( $col_data['align'] ) ) {
									$col_data['align'] = 'nw';
								}

								if ( $custom_layout ) {
									do_action( $col_data['type'], [ 'menu_class' => 'flex-row ' . $col_data['align'] ], array(), true );
								} else {
									do_action( $col_data['type'], [ 'menu_class' => 'flex-row ' . $col_data['align'] ] );
								}
								echo '</div>';
								break;
							case 'boldgrid_site_identity' === $col_data['type'] :
								$filter = function( $classes ) use ( $col_data, $preset, $hidden_item_classes ) {
									if ( empty( $col_data['align'] ) ) {
										$col_data['align'] = 'nw';
									}

									$classes = array(
										'site-branding',
										$col_data['align'],
									);

									if ( false !== strpos( $col_data['uid'], 'f' ) ) {
										/*
										 * For each item in the $col_data['display'] array, if the display is set to 'hide',
										 * then add that hidden class to the $classes array.
										 */
										foreach ( $col_data['display'] as $item ) {
											if ( 'hide' === $item['display'] ) {
												$classes[] = 'hide-' . strtolower( $item['title'] );
											}
										}

										$classes[] = 'footer-site-identity';
										return $classes;
									}

									if ( 'custom' === $preset || 'default' === $preset ) {
										return $classes;
									}

									$classes = array_merge( $classes, $hidden_item_classes );
									return $classes;
								};
								add_filter( 'bgtfw_site_branding_classes', $filter, 10 );
							case 'boldgrid_display_attribution_links' === $col_data['type'] :
								$filter = function( $classes ) use ( $col_data ) {
									if ( empty( $col_data['align'] ) ) {
										$col_data['align'] = 'nw';
									}
									$classes[] = 'flex-row';
									$classes[] = $col_data['align'];
									return $classes;
								};
								add_filter( 'bgtfw_attribution_theme_mods_classes', $filter, 10 );
							default:
								do_action( $col_data['type'] );
						}

						$markup .= ob_get_contents();
						ob_end_clean();
						$markup .= '</div>';
					}

					$markup .= '</div>';
				}

				$markup .= '</div>';
				$markup .= '</div>';
			}
		}

		return $markup;
	}

	/**
	 * Get Layout.
	 *
	 * Retrieve the layout for various presets.
	 *
	 * @since 2.7.0
	 *
	 * @param string $theme_mod     Name of the theme mod.
	 * @param string $preset        Name of the preset.
	 * @param array  $custom_layout Array of custom_layout configs.
	 */
	public static function get_layout( $theme_mod, $preset = null, $custom_layout = null ) {
		global $wp_customize;
		global $boldgrid_theme_framework;
		$configs     = $boldgrid_theme_framework->get_configs();
		$preset_type = str_replace( 'bgtfw_', '', $theme_mod );
		$preset_type = str_replace( '_layout', '', $preset_type );
		$preset      = $preset ? $preset : get_theme_mod( 'bgtfw_' . $preset_type . '_preset', 'default' );
		$layout      = get_theme_mod( $theme_mod . '_' . $preset, get_theme_mod( $theme_mod ) );

		if ( 'custom' === $preset && $custom_layout && 'footer' !== $preset_type ) {
			return $custom_layout;
		}

		if ( 'default' === $preset && get_option( 'fresh_site' ) && $wp_customize ) {
			$starter_content = get_theme_starter_content();
			$layout          = $starter_content['theme_mods'][ 'bgtfw_' . $preset_type . '_layout' ];
		} elseif ( 'default' === $preset ) {
			$unset_default_layout = isset( $configs['customizer']['controls'][ 'bgtfw_' . $preset_type . '_layout' ]['default'] ) ?
				$configs['customizer']['controls'][ 'bgtfw_' . $preset_type . '_layout' ]['default'] :
				$configs['customizer']['controls']['bgtfw_header_layout']['default'];
			$layout = (array) get_theme_mod( 'bgtfw_' . $preset_type . '_layout_default', $unset_default_layout );
		}

		if ( 'footer' === $preset_type ) {
			$layout = (array) get_theme_mod( 'bgtfw_footer_layout' );
		}

		return $layout;
	}

	/**
	 * Creates UIDs for dynamic layouts if none are passed in.
	 *
	 * @since 2.0.3
	 *
	 * @param string $theme_mod Theme mod of dynamic layout element.
	 *
	 * @return array $defaults Default parameters with uIDs added for items.
	 */
	public static function create_uids( $theme_mod, $preset = null, $custom_layout = null ) {
		$uid = ( false !== strpos( $theme_mod, 'header' ) ) ? ( false !== strpos( $theme_mod, 'sticky_header' ) ) ? 's' : 'h' : 'f';
		$defaults = self::get_layout( $theme_mod, $preset, $custom_layout );

		foreach ( $defaults as $key => $section ) {
			$base = $uid . $key;
			if ( ! empty( $section['items'] ) ) {
				foreach ( $section['items'] as $k => $v ) {
					if ( empty( $v['uid'] ) ) {
						$defaults[ $key ]['items'][ $k ]['uid'] = $base . $k;
					}
				}
			}
		}

		return $defaults;
	}

	/**
	 * Outputs dynamic header layout element.
	 *
	 * @since 2.0.3
	 *
	 * @return string Rendered HTML for dyanmic layout element.
	 */
	public static function dynamic_header() {
		return self::dynamic_layout( 'bgtfw_header_layout' );
	}

	/**
	 * Outputs dynamic header layout element.
	 *
	 * @since 2.0.3
	 *
	 * @return string Rendered HTML for dyanmic layout element.
	 */
	public static function dynamic_sticky_header( $preset = null ) {
		if ( ! is_front_page() && is_home() ) {
			$id = get_option( 'page_for_posts' );
		} else {
			$id = get_the_ID();
		}

		$page_header = apply_filters( 'crio_premium_get_sticky_page_header', $id );

		$sticky_template_class = get_theme_mod( 'bgtfw_sticky_page_headers_global_enabled' ) && ! empty( $page_header ) ? 'sticky-template-' . $page_header : '';

		$header_classes = array(
			'header',
			'sticky',
		);

		// Add neutral background colors to sticky headers.
		if ( ! empty( $sticky_template_class ) ) {
			$header_classes[] = $sticky_template_class;
			$header_classes[] = 'template-sticky-header';
			$header_classes[] = 'color-neutral-background-color';
			$header_classes[] = 'color-neutral-text-contrast';
		}

		$markup  = '';
		$markup .= '<header id="masthead-sticky" ' . BoldGrid::add_class( 'header', $header_classes, false ) . '>';
		ob_start();
		do_action( 'boldgrid_header_top' );
		$markup .= ob_get_clean();

		// If using Custom Templates, add the content here.
		if ( get_theme_mod( 'bgtfw_sticky_page_headers_global_enabled' ) && ! empty( $page_header ) ) {
			if ( 'disabled' !== $page_header ) {
				$markup .= apply_filters( 'the_content', get_post_field( 'post_content', $page_header ) );
			}
		// Otherwise, load the dynamic layout as usual.
		} else {
			$markup .= '<div class="custom-header-media">';
			$markup .= get_custom_header_markup();
			$markup .= '</div>';
			if ( $preset ) {
				$markup .= self::dynamic_layout( 'bgtfw_sticky_header_layout', $preset );
			} else {
				$markup .= self::dynamic_layout( 'bgtfw_sticky_header_layout' );
			}
		}
		ob_start();
		do_action( 'boldgrid_header_bottom' );
		$markup .= ob_get_clean();
		$markup .= '</header><!-- #masthead -->';
		return $markup;
	}

	/**
	 * Outputs dynamic footer layout element.
	 *
	 * @since 2.0.3
	 *
	 * @return string Rendered HTML for dyanmic layout element.
	 */
	public static function dynamic_footer() {
		if ( ! is_front_page() && is_home() ) {
			$id = get_option( 'page_for_posts' );
		} else {
			$id = get_the_ID();
		}

		$page_footer             = apply_filters( 'crio_premium_get_page_footer', $id );
		$footer_template_enabled = get_theme_mod( 'bgtfw_page_footers_global_enabled' );

		// Footer templates allow the option to disable the footer all together.
		if ( $footer_template_enabled && 'disabled' === $page_footer ) {
			return '';
		}

		if ( $footer_template_enabled && ! empty( $page_footer ) ) {
			return apply_filters( 'the_content', get_post_field( 'post_content', $page_footer ) );
		} else {
			return self::dynamic_layout( 'bgtfw_footer_layout' );
		}
	}

	/**
	 * Add button classes to the content.
	 *
	 * @since 2.12.0
	 *
	 * @param string $content The content.
	 *
	 * @return string the filtered content.
	 */
	public function add_button_classes( $content ) {
		$classes       = apply_filters( 'bgtfw_button_classes', array() );
		$fixed_content = preg_replace( '/([a|li][^>]*class="[^"]*)button-primary([^"]*")/', '$1button-primary ' . $classes['button-primary'] . '$2', $content );
		$fixed_content = preg_replace( '/([a|li][^>]*class="[^"]*)button-secondary([^"]*")/', '$1button-secondary ' . $classes['button-secondary'] . '$2', $fixed_content );
		return $fixed_content;
	}
}
