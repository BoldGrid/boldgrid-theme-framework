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
	 * BoldGrid::add_fonts( $fonts, $switch );
	 *
	 * Add custom Google Fonts to BoldGrid Theme From Array,
	 * and provide translators a way to disable the font if
	 * a character set is not available in their language.
	 *
	 * @param array  $fonts Fonts to add.
	 * @param string $switch Accepts 'on' or 'off'.
	 *
	 * @since 1.0.0
	 */
	public static function add_fonts( $fonts, $switch ) {

		if ( ! empty( $fonts ) ) {

			foreach ( $fonts as $font ) {

				$fonts_url = '';
				/** Translators: If there are characters in your language that are not
				 * supported by the included fonts, translate this to 'off'. Do not translate
				 * into your own language.
				 */
				$font_name = _x( $switch, $font . ' font: on or off', 'bgtfw' );

				$font_families = array();
				if ( 'off' !== $font_name ) {
					// Decode the url before encoding it to prevent double encode.
					$font = urldecode( $font );
					$font_families[] = $font;
				}

				$query_args = array(
				    'family' => urlencode( implode( '|', $font_families ) ),
				    'subset' => urlencode( 'latin,latin-ext' ),
				);

				$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
				return esc_url_raw( $fonts_url );

			}
		}

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
	public function boldgrid_link_pingback() { ?>
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
		// Bug fix 9/28/15: https://codex.wordpress.org/Function_Reference/is_home.
		if ( is_front_page( ) && 'page' === get_option( 'show_on_front' ) ) : ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php else : ?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php endif;
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
		$class = isset( $configs[ 'template' ][ 'pages' ][ $template ][ $location ] ) ?
			$configs[ 'template' ][ 'pages' ][ $template ][ $location ] : 'container';

		if ( 'blog' == $location ) {
			$class = '';
			if ( is_single() || is_archive() || is_search() || is_home() || is_attachment() ) {
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
	public function print_title_tagline() { ?>
		<div class="site-branding">
			<?php do_action( 'boldgrid_site_title' ); ?>
			<?php do_action( 'boldgrid_print_tagline' ); ?>
		</div><!-- .site-branding -->
		<?php
	}

	/**
	 * Print the sites primary navigation.
	 *
	 * @since   1.0.0
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
	 * Call to Action
	 *
	 * This will add the Call To Action section if not selected to be hidden
	 * from within the WordPress customizer.
	 *
	 * @since 1.0.0
	 */
	public function call_to_action() {
		if ( false === get_theme_mod( 'hide_cta' ) && false === get_theme_mod( 'hide_cta_slogan' ) ) {
			printf( $this->configs['template']['call-to-action'],
			get_theme_mod( 'cta_slogan', 'NOW FEATURING OUR NEW AND IMPROVED MODULAR SYSTEM.' ) );
		}
	}

	/**
	 * Call to Action Button
	 *
	 * This will add the call to action button link to the header section.
	 *
	 * @since   1.0.0
	 */
	public function cta_button() {
		if ( false === get_theme_mod( 'hide_cta' ) && false === get_theme_mod( 'hide_cta_button' ) ) {
			printf( $this->configs['template']['call-to-action-button'],
			get_theme_mod( 'cta_button_link', 'about-us' ), get_theme_mod( 'cta_button_text', 'LEARN MORE' ) . ' ');
		}
	}

	/**
	 * BoldGrid::contact_phone( $icon );
	 *
	 * This adds the markup for a "Contact Us" section. Currently,
	 * this will accept $icon, which can be used to replace the icon
	 * displayed.
	 *
	 * @todo  check for empty
	 * @todo  set a default icon if this stays the same from theme to theme.
	 *
	 * @var     $icon   string   Icon to be used for Social Phone section of site.
	 * @since   1.0.0
	 */
	public function contact_phone() {
		$contact_num = get_theme_mod( 'social_phone', '777-765-4321' );

		if ( get_theme_mod( 'hide_social_phone' ) == '' && $contact_num ) {
			printf( $this->configs['template']['contact-number'], $contact_num );
		}
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
			$classes[] = 'sidebar-1';
		}

		// Add class if post title is hidden.
		if ( $post && ( is_page() || is_single() ) ) {
			$post_meta = get_post_meta( $post->ID );
			if ( empty( $post_meta['boldgrid_hide_page_title'][0] ) && isset( $post_meta['boldgrid_hide_page_title'] ) ) {
				$classes[] = 'post-title-hidden';
			}
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
				<div id="boldgrid-sticky-filler"></div>
				<div id="boldgrid-sticky-push"></div>
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
		$config_path = realpath( plugin_dir_path ( __FILE__ ) . '/configs/category.config.php' );
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
				case ( $is_page_template && $param === 'default' ) :
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
}
