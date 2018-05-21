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
	 * Just a simple endpoint for some of the functionality to run.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_api_endpoint() {
		$this->body_classes( );
		$this->setup_author( );
		$this->page_menu_args( );
	}

	/**
	 * Header.
	 *
	 * This will output main <header> components
	 *
	 * @since 1.0.0
	 */
	private static function boldgrid_header() {
		do_action( 'boldgrid_header' );
	}

	/**
	 * Doctype.
	 *
	 * This will output <head> components
	 *
	 * @since 1.0.0
	 */
	public static function boldgrid_doctype() {
		print	'<!DOCTYPE html>';
	}

	/**
	 * XUA Meta Tag
	 *
	 * This will output X-UA compatible meta tags.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_enable_xua() {
	?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php
	}

	/**
	 * Meta Charset.
	 *
	 * This will output meta character set information.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_meta_charset() {
	?>
		<meta charset="<?php bloginfo( 'charset' ) ?>">
	<?php
	}

	/**
	 * Meta Viewport.
	 *
	 * This will output meta viewport size meta information.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_meta_viewport() {
	?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	}

	/**
	 * Link Profile.
	 *
	 * This will output profile link rel information.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_link_profile() {
	?>
		<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php
	}

	/**
	 * Link Pingback.
	 *
	 * This will add appropriate link pingback meta data to the head html tag
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_link_pingback() {
	?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ) ?>">
	<?php
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
		if ( is_front_page( ) && 'page' === get_option( 'show_on_front' ) ) {
			$title_tag = $configs['template']['site-title-tag'];
		} else {
			$title_tag = 'p';
		}

		// Site title link.
		$link = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a>';

		echo '<' . $title_tag . ' class="' . esc_attr( $configs['template']['site-title-classes'] )
			. '">' . $link . '</' . $title_tag . '>';
	}

	/**
	 * Is this a blog styled page.
	 *
	 * @since 1.5.1
	 *
	 * @return boolean Whether or not this page is styled as a post.
	 */
	public static function is_blog() {
		return is_single() || is_archive() || is_search() || is_home() || is_attachment();
	}

	/**
	 * Print the container class.
	 *
	 * @since 1.2
	 */
	public static function print_container_class( $location ) {
		global $boldgrid_theme_framework;
		$configs = $boldgrid_theme_framework->get_configs();
		$template = basename( get_page_template() );
		$template = ( $template ) ? $template : 'default';
		$class = isset( $configs['template']['pages'][ $template ][ $location ] ) ?
			$configs['template']['pages'][ $template ][ $location ] : 'container';

		if ( 'blog' == $location ) {
			$class = '';
			if ( self::is_blog() ) {
				$class = $configs['template']['pages']['blog'];
			}
			if ( ! empty( $configs['template']['pages'][ $template ]['main'] ) ) {
				$class .= $configs['template']['pages'][ $template ]['main'];
			}
		}

		print $class;
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
		$alt_tag = get_post_meta( get_theme_mod( 'boldgrid_logo_setting' ), '_wp_attachment_image_alt', true );

		$alt = '';
		if ( ! empty( $alt_tag ) ) {
			$alt = 'alt="' . $alt_tag . '"';
		}

		if ( $image_attributes ) { ?>
		<div class="site-title">
			<a class='logo-site-title' href="<?php echo esc_url( home_url( '/' ) ); ?>"  rel="home">
				<img <?php echo esc_attr( $alt ); ?> src="<?php echo esc_attr( $image_attributes[0] ); ?>" width="<?php echo esc_attr( $image_attributes[1] ); ?>" height="<?php echo esc_attr( $image_attributes[2] ); ?>" />
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

		if ( $blog_info && ! absint( get_theme_mod( 'boldgrid_logo_setting' ) ) ) {
			printf( $this->configs['template']['tagline'], $this->configs['template']['tagline-classes'], $blog_info );
		} else {
			printf( $this->configs['template']['tagline'], 'site-description invisible', $blog_info );
		}
	}

	/**
	 * Print the sites title and tagline together.
	 *
	 * @since   1.0.0
	 */
	public function print_title_tagline() {
	?>
		<div class="site-branding">
			<?php if ( function_exists( 'the_custom_logo' ) ) : ?>
				<?php the_custom_logo(); ?>
			<?php endif; ?>
			<?php do_action( 'boldgrid_site_title' ); ?>
			<?php do_action( 'boldgrid_print_tagline' ); ?>
		</div><!-- .site-branding -->
		<?php
	}

	/**
	 * Print the site's primary navigation using the Bootstrap navwalker.
	 *
	 * @since 1.0.0
	 */
	public function print_primary_navigation() {
		if ( has_nav_menu( 'primary' ) ) { ?>
			<nav id="site-navigation" class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#primary-navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div><!-- .navbar-header -->
				<?php do_action( 'boldgrid_menu_primary' ); ?>
				<?php if ( true === $this->configs['template']['navbar-search-form'] ) : ?>
					<?php get_template_part( 'templates/header/search' ); ?>
				<?php endif; ?>
			</nav><!-- #site-navigation -->
			<?php
		}
	}

	/**
	 * Print the site's primary navigation using the native WordPress navwalker.
	 *
	 * @since 2.0.0
	 */
	public function print_menu() {
		?>
			<div id="navi">
			<!-- Mobile toggle -->
			<input id="main-menu-state" type="checkbox" />
			<label class="main-menu-btn" for="main-menu-state">
				<span class="main-menu-btn-icon"></span><span class="sr-only">Toggle main menu visibility</span>
			</label>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => 'false', 'menu_id' => 'main-menu', 'menu_class' => 'sm bgtfw-menu main-menu' ) ); ?>
			</div>
		<?php
	}

	/**
	 * BoldGrid::skip_link(  );
	 *
	 * This is the markup that will render for screen reader users to skip to
	 * the main content.  This is the element targetted by skip-link-focus-fix.js
	 *
	 * @since    1.0.0
	 */
	public static function skip_link() {
	?>
			<a class="skip-link sr-only" href="#content"><?php esc_html_e( 'Skip to content', 'bgtfw' ); ?></a>
		<?php
	}

	/**
	 * Adds custom classes to the array of header classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array $classes array of classes to be applied to the #masthead element.
	 */
	public function header_classes( $classes ) {
		$classes[] = get_theme_mod( 'bgtfw_header_top_layouts', 'layout-6' );
		$classes = array_merge( $classes, $this->get_background_color( 'bgtfw_header_color' ) );
		return $classes;
	}

	/**
	 * Adds custom CSS for main menu based on header color/link color selections by user.
	 *
	 * @since 2.0.0
	 *
	 * @param string $css CSS string being filtered.
	 *
	 * @return string $css Modified CSS to add to front end.
	 */
	public function main_menu_css( $css = '' ) {
		$color = get_theme_mod( 'bgtfw_header_color' );
		$color = explode( ':', $color );
		$color = array_pop( $color );

		$color_obj = ariColor::newColor( $color );

		$color_obj->alpha = 0.7;
		$css .= '.header-left #main-menu, .header-right #main-menu { background-color:' . $color_obj->toCSS( 'rgba' ) . '; }';
		$css .= '@media (min-width: 768px) {';

		$color_obj->alpha = 0.4;
		$css .= '.sm-clean ul, .sm-clean ul a, .sm-clean ul a:hover, .sm-clean ul a:focus, .sm-clean ul a:active, .sm-clean ul a.highlighted, .sm-clean span.scroll-up, .sm-clean span.scroll-down, .sm-clean span.scroll-up:hover, .sm-clean span.scroll-down:hover { background-color:' . $color_obj->toCSS( 'rgba' ) . ';}';
		$css .= '.sm-clean ul { border: 1px solid ' . $color_obj->toCSS( 'rgba' ) . ';}';
		$css .= '.sm-clean > li > ul:before, .sm-clean > li > ul:after { border-color: transparent transparent ' . $color_obj->toCSS( 'rgba' ) . ' transparent;}';
		$css .= '}';

		return $css;
	}

	/**
	 * Adds custom classes to the array of inner header classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array $classes array of classes to be applied to the #masthead element.
	 */
	public function inner_header_classes( $classes ) {
		$classes = array_merge( $classes, $this->get_background_color( 'bgtfw_header_color' ) );
		$classes = array_merge( $classes, $this->get_link_color( 'bgtfw_header_links' ) );
		return $classes;
	}

	/**
	 * Adds custom classes to the array of footer classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array $classes array of classes to be applied to the .site-footer element.
	 */
	public function footer_classes( $classes ) {
		$classes[] = get_theme_mod( 'bgtfw_footer_layouts', 'layout-1' );
		$classes = array_merge( $classes, $this->get_background_color( 'bgtfw_footer_color' ) );
		return $classes;
	}

	/**
	 * Adds custom classes to the array of inner footer classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array $classes array of classes to be applied to the #masthead element.
	 */
	public function inner_footer_classes( $classes ) {
		$classes = array_merge( $classes, $this->get_background_color( 'bgtfw_footer_color' ) );
		$classes = array_merge( $classes, $this->get_link_color( 'bgtfw_footer_links' ) );
		return $classes;
	}

	/**
	 * Get color classes for a property, given a theme mod.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $mod        Palette Selector theme_mod to get value for.
	 * @param  array  $properties  A list of properties to check..
	 *
	 * @return array  $classes    Classes to add.
	 */
	public static function get_color_classes( $mod, $properties ) {
		$color = get_theme_mod( $mod );
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

		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author( ) ) {
			$classes[] = 'group-blog';
		}
		// Add class if sidebar is active.
		if ( $this->display_sidebar() ) {
			$classes[] = 'has-sidebar sidebar-1';
		}

		// Add class if post title is hidden.
		if ( $post && ( is_page() || is_single() ) ) {
			$post_meta = get_post_meta( $post->ID );
			if ( empty( $post_meta['boldgrid_hide_page_title'][0] ) && isset( $post_meta['boldgrid_hide_page_title'] ) ) {
				$classes[] = 'post-title-hidden';
			}
		}

		// Check if we are on a blog roll page (not archive).
		global $wp_query;

		if ( ( isset( $wp_query ) && ( bool ) $wp_query->is_posts_page ) || is_home() || is_archive() ) {
			$classes[] = 'col' . get_theme_mod( 'bgtfw_pages_blog_blog_page_layout_columns', '1' );
		}

		// Add class if bstw is disabled.
		if ( get_theme_mod( 'bstw_enabled' ) ) {
			$classes[] = 'bstw-disabled';
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

		if ( true === $this->configs['scripts']['options']['goup']['enabled'] ) {
			$classes[] = 'goup-enabled';
		}

		if ( true === $this->configs['scripts']['offcanvas-menu'] ) {
			$classes[] = 'offcanvas-menu-enabled';
		} else {
			$classes[] = 'standard-menu-enabled';
		}

		if ( true === $this->configs['edit-post-links']['enabled'] ) {
			$classes[] = 'bgtfw-edit-links-shown';
		} else {
			$classes[] = 'bgtfw-edit-links-hidden';
		}

		$classes[] = 'custom-header';

		if ( get_theme_mod( 'bgtfw_fixed_header' ) ) {
			$classes[] = 'header-fixed';
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

		$classes[] = get_theme_mod( 'bgtfw_header_layout_position', 'header-top' );

		if ( is_home() ) {
			$classes[] = get_theme_mod( 'bgtfw_blog_blog_page_sidebar', get_theme_mod( 'bgtfw_layout_blog', 'no-sidebar' ) );
		} else {
			$layout = get_page_template_slug();

			if ( empty( $layout ) ) {
				$type = 'page' === get_post_type() ? 'page' : 'blog';
				$layout = get_theme_mod( 'bgtfw_layout_' . $type, '' );
			}

			$classes[] = sanitize_html_class( $layout );
		}

		$background_color = get_theme_mod( 'boldgrid_background_color' );
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

		return array_unique( $classes );
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
			$classes[] = get_theme_mod( 'bgtfw_blog_layout', 'design-1' );
		}

		return $classes;
	}

	/**
	 * Sets the authordata global when viewing an author archive.
	 *
	 * This provides backwards compatibility with
	 * http://core.trac.wordpress.org/changeset/25574
	 *
	 * It removes the need to call the_post() and rewind_posts() in an author
	 * template to print information about the author.
	 *
	 * @global   WP_Query $wp_query WordPress Query object.
	 * @since    1.0.0
	 */
	public function setup_author() {
		global $wp_query;

		if ( $wp_query->is_author( ) && isset( $wp_query->post ) ) {
			$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
		}
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
	 *  Get the subcategory installed by inspiration.
	 *
	 *  @since 1.1.7
	 *
	 *  @return string $installed_subcategory_key
	 */
	public static function get_inspiration_configs( $configs ) {

		// Read installed option values.
		$boldgrid_install_options = get_option( 'boldgrid_install_options', array() );
		$installed_subcategory_key = ( ! empty( $boldgrid_install_options['subcategory_key'] ) ) ?
			$boldgrid_install_options['subcategory_key'] : null;

		$installed_subcategory_id = ( ! empty( $boldgrid_install_options['subcategory_id'] ) ) ?
			$boldgrid_install_options['subcategory_id'] : null;

		// Load Configs.
		$category_key_configs = array();
		$config_path = realpath( plugin_dir_path( __FILE__ ) . '/configs/category.config.php' );
		if ( ! $installed_subcategory_key && $config_path ) {
			$category_key_configs = include $config_path;
		}

		// If no key found but the id matches, set the key from configs.
		if ( ! $installed_subcategory_key && ! empty( $category_key_configs[ $installed_subcategory_id ] ) ) {
			$installed_subcategory_key = $category_key_configs[ $installed_subcategory_id ];
		}

		// Assign the subcategory lookup key to a config.
		$boldgrid_install_options['subcategory_key'] = $installed_subcategory_key;

		// Assign the resr of the install options to a config.
		$configs['inspiration'] = $boldgrid_install_options;

		return $configs;
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
			$name = str_ireplace( $type, '', $condition );
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
	public function homepage_sidebar( $display ) {

		if ( is_front_page() && is_home() ) {

			// Check if a setting has been saved for blog homepages.
			$homepage_sidebar = get_theme_mod( 'bgtfw_blog_blog_page_sidebar', false );

			if ( empty( $homepage_sidebar ) ) {
				// Nothing was saved, so check if there's a default global blog page setting set.
				$global_sidebar = get_theme_mod( 'bgtfw_layout_blog', false );
				$homepage_sidebar = empty( $global_sidebar ) ? 'no-sidebar' : $global_sidebar;
			}

			if ( 'no-sidebar' !== $homepage_sidebar ) {
				$display = true;
			}
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
		<div class="boldgrid-section">
			<div class="container">
				<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="form-inline post-password-form" method="post">
					<p>' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>
					<label for="' . $label . '">' . __( 'Password:' ) . ' <input name="post_password" id="' . $label . '" type="password" size="20" class="form-control" /></label><button type="submit" name="Submit" class="button-primary">' . esc_attr_x( 'Enter', 'post password form' ) . '</button>
				</form>
			</div>
		</div>';

		return $output;
	}
}
