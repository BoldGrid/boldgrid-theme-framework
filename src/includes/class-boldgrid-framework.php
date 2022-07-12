<?php
/**
 * Class: BoldGrid_Framework
 *
 * This is the main file for the BoldGrid Theme Framework that orchestrates all the
 * theme framework's functionality through hooks and filters.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework
 *
 * This is the main file for the BoldGrid Theme Framework that orchestrates all the
 * theme framework's functionality through hooks and filters.
 *
 * @since      1.0.0
 */
class BoldGrid_Framework {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Boldgrid_Seo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Boldgrid_Framework_Woocommerce
	 *
	 * @since 2.1.18
	 * @var Boldgrid_Framework_Woocommerce
	 */
	public $woo;

	/**
	 * The plugins configs
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $configs    An array of the plugins configurations
	 */
	protected $configs = array();

	/**
	 * Get the plugins configs
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @return      string    $configs    An array of the plugins configurations
	 */
	public function get_configs() {
		return $this->configs;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		// Assign a global for the framework instance.
		global $boldgrid_theme_framework;
		$boldgrid_theme_framework = $this;

		$this->load_dependencies();

		$this->assign_configurations();
		$this->add_config_filters();
		$this->load_theme_configs();
		$this->set_doing_cron();
		$this->upgrade();

		$this->define_theme_hooks();
		$this->define_admin_hooks();
		$this->define_global_hooks();
		$this->boldgrid_theme_setup();
		$this->layouts();
		$this->setup_menus();
		$this->boldgrid_widget_areas();
		$this->theme_customizer();
		$this->social_icons();
		$this->comments();
		$this->error_404();
		$this->search_forms();
		$this->pagination();
		$this->woocommerce();
		$this->title();
	}

	/**
	 * Check if DOING_CRON is defined.
	 *
	 * Though this is a simple method, the lengthy description is added below to server as a
	 * reference for why we're setting this var.
	 *
	 * There are several methods triggered during the 'after_switch_theme' action.
	 * Those hooks are intended to setup a new BoldGrid theme after it's been activated.
	 * Those hooks however are sometimes ran twice:
	 *  1st, they are ran by the WordPress cron, if DISABLE_WP_CRON is not set to true.
	 *  2nd, they are ran by an ajax call
	 * To only run those methods once, we'll check 'DOING_CRON' before adding the hooks.
	 *
	 * @since 1.0.5
	 */
	public function set_doing_cron() {
		$this->doing_cron = defined( 'DOING_CRON' );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up this theme:
	 *
	 * - Boldgrid_Framework_404                  The default template used for BoldGrid Theme 404 Pages.
	 * - Boldgrid_Framework_Admin                Defines internationalization functionality.
	 * - Boldgrid_Framework_Activate             Contains hooks ran on theme activation.
	 * - Boldgrid_Framework_Api                  Responsible for additional theme template hooks.
	 * - Boldgrid_Framework_Comments             Contains the template and actions for custom Bootstrap comment forms.
	 * - Boldgrid_Framework_Device_Preview       Adds the device previewer to the WP Customizer.
	 * - Boldgrid_Framework_Il8n                 Defines internationalization functionality.
	 * - Boldgrid_Framework_Loader               Orchestrates the hooks of the plugin.
	 * - Boldgrid_Framework_Menu                 Contains the hooks for registering nav menus and setting locations.
	 * - Boldgrid_Framework_Schema_Markup        Contains markup that theme's utilize to add schema.org markup.
	 * - Boldgrid_Framework_Scripts              Enqueue the javascript a theme utilizes.
	 * - Boldgrid_Framework_SCSS                 Responsible for converting SCSS to CSS in BoldGrid themes.
	 * - Boldgrid_Framework_Search_Forms         Default template utilized by WordPress' searchform.php.
	 * - Boldgrid_Framework_Setup                Runs hooks used during theme setup process on activation.
	 * - Boldgrid_Framework_Social_Media_Icons   Contains the hooks/filters for menus to add social media icons to them.
	 * - Boldgrid_Framework_Staging              Functionality needed in order to integrate with the staging plugin.
	 * - Boldgrid_Framework_Styles               Enqueue the CSS a theme utilizes.
	 * - Boldgrid_Framework_Widgets              The core widget functionality for a BoldGrid theme.
	 *
	 * This also loads the BoldGrid Framework's customizer files that provide additional functionality
	 * to the WordPress customizer.
	 *
	 * After the includes we then create an instance of the loader which will be used to register the
	 * hooks with WordPress.
	 *
	 * @var      $library_files     string    filenames to load ( class-boldgrid-framework- $filename .php )
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		// Load Compile Interface.
		require_once trailingslashit( __DIR__ ) . 'interface-boldgrid-framework-compile.php';
		// Include utility classes.
		$library_files = array(
			'404',
			'admin',
			'activate',
			'api',
			'comments',
			'compile-colors',
			'container',
			'container-width',
			'content',
			'custom-header',
			'editor',
			'edit-post-links',
			'element-class',
			'i18n',
			'layouts-post-meta',
			'links',
			'loader',
			'menu',
			'pagination',
			'ppb',
			'schema-markup',
			'scripts',
			'scss',
			'scss-compile',
			'search-forms',
			'setup',
			'social-media-icons',
			'staging',
			'starter-content',
			'sticky-header',
			'styles',
			'template-config',
			'title',
			'upgrade',
			'widgets',
			'woocommerce',
			'wp-fs',
			'wrapper',
		);

		foreach ( $library_files as $filename ) {
			require_once trailingslashit( __DIR__ ) . 'class-boldgrid-framework-' . $filename . '.php';
		}

		// Load Customizer Files.
		$this->load_customizer_files();

		// Load Pro Feature Cards.
		$this->load_pro_cards();

		/**
		 * Include the TGM_Plugin_Activation class.
		 */
		require_once trailingslashit( __DIR__ ) . 'tgm/class-tgm-plugin-activation.php';

		// Loader instance.
		$this->loader = new Boldgrid_Framework_Loader();
	}

	/**
	 * Include additional configuration options to assign.
	 *
	 * @since    1.1
	 * @access   private
	 */
	private function load_customizer_files() {
		// Load Kirki Framework.
		require_once __DIR__ . '/kirki/kirki.php';

		// Load Customizer Files.
		$plugin_path = __DIR__ . '/customizer';
		foreach ( glob( $plugin_path . '/*.php' ) as $filename ) {
			if ( false !== strpos( $filename, 'index.php' ) ) {
				continue;
			}
			require_once $filename;
		}
	}

	/**
	 * Include files for Pro Feature Cards
	 *
	 * @since    2.5.0
	 * @access   private
	 */
	private function load_pro_cards() {
		$path = __DIR__ . '/pro-feature-cards';
		foreach ( glob( $path . '/*.php' ) as $filename ) {
			if ( false !== strpos( $filename, 'index.php' ) ) {
				continue;
			}
			require_once $filename;
		}
	}

	/**
	 * Include additional configuration options to assign.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function assign_configurations() {
		global $wp_version;
		$this->configs = include plugin_dir_path( dirname( __FILE__ ) ) . 'includes/configs/configs.php';
		// Based on the configs already assigned, set config values to help assign later values.
		$this->assign_dynamic_configs();
		// Assign configs.
		$this->assign_configs();
		$this->assign_configs( 'customizer-options' );
		$this->assign_configs( 'customizer' );
		$this->assign_configs( 'components' );

		if ( version_compare( $wp_version, '5.4.99', 'gt' ) && isset( $this->configs['customizer']['controls']['bgtfw_preloader_type'] ) ) {
			unset( $this->configs['customizer']['controls']['bgtfw_preloader_type'] );
		}
	}

	/**
	 * Assign theme mod configs.
	 *
	 * @since    1.1.5
	 * @access   protected.
	 */
	public function add_config_filters() {
		$effects         = new BoldGrid_Framework_Customizer_Effects( $this->configs );
		$template_config = new Boldgrid_Framework_Template_Config( $this->configs );
		$activate        = new Boldgrid_Framework_Activate( $this->configs );
		$starter_content = new Boldgrid_Framework_Starter_Content( $this->configs );
		$custom_header   = new Boldgrid_Framework_Custom_Header( $this->configs );

		// Disable Kirki Telemetry.
		add_filter( 'kirki_telemetry', '__return_false' );

		// Set the is_editing_boldgrid_theme filter to true for any theme using BGTFW.
		add_filter( 'is_editing_boldgrid_theme', '__return_true', 20 );
		add_action( 'after_setup_theme', array( $starter_content, 'dynamic_theme_mod_filter' ) );

		// Add changeset UUIDs to post preview links.
		$this->loader->add_action( 'preview_post_link', $starter_content, 'add_post_preview_link_changeset', 10, 2 );

		add_filter( 'boldgrid_theme_framework_config', array( $starter_content, 'set_configs' ), 15 );
		add_filter( 'boldgrid_theme_framework_config', array( $effects, 'enable_configs' ), 20 );
		add_filter( 'boldgrid_theme_framework_config', array( $template_config, 'pagination_style' ), 20 );
		add_filter( 'boldgrid_theme_framework_config', array( $activate, 'tgm_override' ), 20 );
		add_filter( 'boldgrid_theme_framework_config', array( $custom_header, 'add_display_classes' ), 20 );
		add_filter( 'boldgrid_theme_framework_config', array( $custom_header, 'add_color_classes' ), 20 );

		// Adds the sidebar options to the page template selections.
		add_filter( 'theme_page_templates', array( $template_config, 'templates' ) );

		// Adds the sidebar options to the post template selections.
		add_filter( 'theme_post_templates', array( $template_config, 'templates' ) );
	}

	/**
	 * Set configs that will be used to create other configs.
	 *
	 * @since    1.1.4
	 * @access   protected.
	 */
	protected function assign_dynamic_configs() {

		$theme_directory     = get_template_directory();
		$theme_directory_uri = get_template_directory_uri();

		// If we are using an authors child theme, paths need to be changed to look at the child.
		$menu = new Boldgrid_Framework_Menu( $this->configs );
		if ( is_child_theme() && false === $menu->is_user_child() ) {
			$theme_directory     = get_stylesheet_directory();
			$theme_directory_uri = get_stylesheet_directory_uri();
		}

		$this->configs['framework']['config_directory']['template'] = $theme_directory;
		$this->configs['framework']['config_directory']['uri']      = $theme_directory_uri;
	}

	/**
	 * Include customizer configuration options to assign.
	 *
	 * Configuration files for the customizer are loaded from
	 * includes/configs/customizer-options/.
	 *
	 * @since    1.1
	 * @access   private
	 */
	private function assign_configs( $folder = '' ) {
		$path = __DIR__ . '/configs/' . $folder;
		foreach ( glob( $path . '/*.config.php' ) as $filename ) {
			$option = basename( str_replace( '.config.php', '', $filename ) );
			if ( ! false === strpos( $filename, 'customizer/controls/' ) ) {
				continue;
			}
			if ( ! empty( $folder ) ) {
				$this->configs[ $folder ][ $option ] = include $filename;
			} else {
				$this->configs[ $option ] = include $filename;
			}
		}
	}

	/**
	 * Merge the themes configs with the defaults for the plugin
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function load_theme_configs() {
		// Apply filter to framework configs.
		$this->configs = apply_filters( 'boldgrid_theme_framework_config', $this->configs );
		// Backwards Compatibility.
		$this->configs['directories']['BOLDGRID_THEME_NAME'] = $this->configs['version'];
		$this->configs['directories']['BOLDGRID_THEME_VER']  = $this->configs['theme_name'];
	}

	/**
	 * Run upgrade checks based on framework version.
	 *
	 * @since    1.3.6
	 * @access   public
	 */
	public function upgrade() {
		$upgrade = new Boldgrid_Framework_Upgrade( $this->configs );
		$this->loader->add_action( 'after_setup_theme', $upgrade, 'upgrade_db_check' );
	}

	/**
	 * This defines the core functionality of the themes and associated template actions.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_theme_hooks() {
		$styles          = new BoldGrid_Framework_Styles( $this->configs );
		$scripts         = new BoldGrid_Framework_Scripts( $this->configs );
		$container_width = new Boldgrid_Framework_Container_Width( $this->configs );
		$boldgrid_theme  = new BoldGrid( $this->configs );

		// Load Theme Wrapper.
		if ( true === $this->configs['boldgrid-parent-theme'] ) {
			$wrapper = new Boldgrid_Framework_Wrapper();
			$this->loader->add_filter( 'template_include', $wrapper, 'wrap', 109 );
		}

		// Add Theme Styles.
		$this->loader->add_action( 'wp_enqueue_scripts', $styles, 'boldgrid_enqueue_styles' );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $styles, 'enqueue_fontawesome' );
		$this->loader->add_action( 'after_setup_theme', $styles, 'add_editor_styling' );
		$this->loader->add_action( 'wp_enqueue_scripts', $styles, 'register_responsive_font_sizes' );
		$this->loader->add_action( 'wp_enqueue_scripts', $styles, 'register_weforms_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $styles, 'editor_button_fonts' );
		$this->loader->add_filter( 'mce_css', $styles, 'add_cache_busting' );
		$this->loader->add_filter( 'boldgrid_theme_framework_local_editor_styles', $styles, 'enqueue_editor_buttons' );
		$this->loader->add_filter( 'boldgrid_mce_inline_styles', $styles, 'get_css_vars' );
		$this->loader->add_filter( 'boldgrid_mce_inline_styles', $styles, 'generate_responsive_font_css' );

		// Validate Theme Fonts Directory
		$this->loader->add_action( 'after_setup_theme', $styles, 'validate_fonts_dir' );

		// Add Theme Scripts.
		$this->loader->add_action( 'wp_enqueue_scripts', $scripts, 'boldgrid_enqueue_scripts' );
		$this->loader->add_filter( 'language_attributes', $scripts, 'modernizr' );

		$this->loader->add_filter( 'boldgrid/display_sidebar', $boldgrid_theme, 'post_list_sidebar' );

		// Add extra button classes.
		$this->loader->add_filter( 'bgtfw_button_classes', $scripts, 'get_button_classes', 10, 1 );
		$this->loader->add_filter( 'the_content', $boldgrid_theme, 'add_button_classes', 10, 1 );
		$this->loader->add_filter( 'wp_nav_menu_items', $boldgrid_theme, 'add_button_classes', 10, 2 );
		$this->loader->add_filter( 'widget_text', $boldgrid_theme, 'add_button_classes', 10, 1 );
		$this->loader->add_filter( 'the_editor_content', $boldgrid_theme, 'add_button_classes', 10, 2 );

		// Setup Sticky Footer.
		$this->loader->add_action( 'boldgrid_header_before', $boldgrid_theme, 'boldgrid_sticky_top', 10 );
		$this->loader->add_action( 'boldgrid_footer_before', $boldgrid_theme, 'boldgrid_sticky_bottom', 15 );

		// Additional theme functionality.
		$this->loader->add_filter( 'body_class', $boldgrid_theme, 'body_classes' );
		$this->loader->add_filter( 'post_class', $boldgrid_theme, 'post_class' );

		$this->loader->add_filter( 'bgtfw_get_container_type', $container_width, 'get_container_type', 10, 1 );
		$this->loader->add_filter( 'bgtfw_get_max_width', $container_width, 'get_max_width', 10, 1 );

		$this->loader->add_action( 'wp_enqueue_scripts', $styles, 'register_container_widths' );

		$this->loader->add_filter( 'bgtfw_entry_header_classes', $boldgrid_theme, 'entry_header_classes' );
		$this->loader->add_filter( 'bgtfw_header_classes', $boldgrid_theme, 'header_classes' );
		$this->loader->add_filter( 'bgtfw_navi_wrap_classes', $boldgrid_theme, 'header_classes' );
		$this->loader->add_filter( 'bgtfw_footer_classes', $boldgrid_theme, 'footer_classes' );
		$this->loader->add_filter( 'bgtfw_navi_classes', $boldgrid_theme, 'navi_classes' );
		$this->loader->add_filter( 'bgtfw_footer_content_classes', $boldgrid_theme, 'inner_footer_classes' );
		$this->loader->add_filter( 'bgtfw_main_wrapper_classes', $boldgrid_theme, 'blog_page_container' );
		$this->loader->add_filter( 'bgtfw_main_wrapper_classes', $boldgrid_theme, 'page_container' );
		$this->loader->add_filter( 'bgtfw_woocommerce_wrapper_classes', $boldgrid_theme, 'woocommerce_container' );
		$this->loader->add_filter( 'bgtfw_blog_page_post_title_classes', $boldgrid_theme, 'blog_page_post_title_classes' );
		$this->loader->add_filter( 'bgtfw_posts_title_classes', $boldgrid_theme, 'post_title_classes' );
		$this->loader->add_filter( 'bgtfw_pages_title_classes', $boldgrid_theme, 'page_title_classes' );
		$this->loader->add_filter( 'bgtfw_single_page_title_classes', $boldgrid_theme, 'page_title_background_class' );
		$this->loader->add_filter( 'bgtfw_page_page_title_classes', $boldgrid_theme, 'page_title_background_class' );
		$this->loader->add_filter( 'bgtfw_blog_page_title_classes', $boldgrid_theme, 'page_title_background_class' );
		$this->loader->add_filter( 'bgtfw_archive_page_title_classes', $boldgrid_theme, 'page_title_background_class' );

		// Title containers.
		$this->loader->add_filter( 'bgtfw_page_header_wrapper_classes', $boldgrid_theme, 'title_container' );
		$this->loader->add_filter( 'bgtfw_featured_image_classes', $boldgrid_theme, 'title_content_container' );
		$this->loader->add_filter( 'bgtfw_featured_image_page_classes', $boldgrid_theme, 'title_content_container' );
		$this->loader->add_filter( 'bgtfw_featured_image_single_classes', $boldgrid_theme, 'title_content_container' );

		$this->loader->add_filter( 'wp_page_menu_args', $boldgrid_theme, 'page_menu_args' );
		$this->loader->add_filter( 'boldgrid_print_tagline', $boldgrid_theme, 'print_tagline' );
		$this->loader->add_filter( 'boldgrid_site_title', $boldgrid_theme, 'site_title' );
		$this->loader->add_filter( 'boldgrid_site_identity', $boldgrid_theme, 'print_title_tagline' );

		// Sticky Header - Removed template_redirect as it was unnecessary and caused duplication of the sticky header sometimes.
		add_action(
			'boldgrid_header_after',
			function( $id ) {
				if ( $this->maybe_show_sticky_header( $id ) ) {
					?>
					<div <?php BoldGrid::add_class( 'sticky_header', array( 'bgtfw-sticky-header', 'site-header' ) ); ?>>
						<?php echo BoldGrid::dynamic_sticky_header(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<?php
				}
			},
			20
		);

		// Password protected post/page form.
		$this->loader->add_filter( 'the_password_form', $boldgrid_theme, 'password_form' );

		// Register dynamic menu hooks.
		$boldgrid_theme->menu_border_color( $this->configs );

		// Load Custom Header.
		$this->custom_header();
	}

	/**
	 * Maybe Show Sticky Header
	 *
	 * This method determines whether or not the HTML for the
	 * sticky header will be printed to the page. In some instances,
	 * it must be printed to the page, but then hidden via CSS / JS.
	 *
	 * @param int $id Page / Post ID.
	 *
	 * @since 2.11.0
	 */
	public function maybe_show_sticky_header( $id ) {
		/*
		 * If in the customizer, and fixed_header is enabled, we must always render the sticky header.
		 * Even if it's not going to be seen, it's still needed for the customizer to work properly.
		 */
		if ( is_customize_preview() || true === get_theme_mod( 'bgtfw_fixed_header' ) ) {
			return true;
		}

		/*
		 * Outside of the customizer, we only render the sticky header if 'header-top' AND 'fixed-header' are enabled.
		 * This prevents wonky stuff from happening when users have a side header selected.
		 */
		if ( true === get_theme_mod( 'bgtfw_fixed_header' ) && 'header-top' === get_theme_mod( 'bgtfw_header_layout_position', 'header-top' ) ) {
			return true;
		}

		$sticky_header_template = apply_filters( 'crio_premium_get_sticky_page_header', $id );

		// If the user has a Custom Sticky Template enabled for this page or post, ALWAYS render the sticky header markup.
		if ( ! empty( $sticky_header_template ) ) {
			return true;
		}

		return false;
	}

	/**
	 * This contains hooks for our theme's custom header implementation.
	 *
	 * @since 2.0.0
	 */
	private function custom_header() {
		$header = new Boldgrid_Framework_Custom_Header( $this->configs );
		$this->loader->add_action( 'after_setup_theme', $header, 'custom_header_setup' );
		$this->loader->add_filter( 'header_video_settings', $header, 'video_controls' );
	}

	/**
	 * This contains hooks for our theme's navigation menus to be registered, and
	 * pre-filled with menus in the locations.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function setup_menus() {
		$menu = new BoldGrid_Framework_Menu( $this->configs );
		$this->loader->add_action( 'after_setup_theme', $menu, 'register_navs' );
		$this->loader->add_action( 'after_setup_theme', $menu, 'add_dynamic_actions' );
		$this->loader->add_action( 'wp_nav_menu_args', $menu, 'wp_nav_menu_args' );

		if ( ! $this->doing_cron ) {
			$this->loader->add_action( 'after_switch_theme', $menu, 'disable_advanced_nav_options' );
		}
	}

	/**
	 * This contains hooks that impact the admin side of the theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$admin             = new BoldGrid_Framework_Admin( $this->configs );
		$activate          = new Boldgrid_Framework_Activate( $this->configs );
		$editor            = new Boldgrid_Framework_Editor( $this->configs );
		$boldgrid_ppb      = new Boldgrid_Framework_PPB( $this->configs );
		$pro_feature_cards = new BoldGrid_Framework_Pro_Feature_Cards( $this->configs );

		$this->loader->add_filter( 'bgtfw_upgrade_url_pro_features', $pro_feature_cards, 'get_upgrade_url', 0 );

		// This adds Pro Feature notice counts to the admin menu.
		$this->loader->add_action( 'admin_menu', $pro_feature_cards, 'show_notice_counts' );

		$content = new Boldgrid_Framework_Content( $this->configs );
		$this->loader->add_filter( 'excerpt_length', $content, 'excerpt_length', 999 );

		$generic = new Boldgrid_Framework_Customizer_Generic( $this->configs );
		$this->loader->add_action( 'wp_enqueue_scripts', $generic, 'add_styles' );
		$this->loader->add_action( 'bgtfw_generic_css_BoxShadow', $generic, 'box_shadow_css', 10, 3 );
		$this->loader->add_action( 'bgtfw_generic_css_Border', $generic, 'border_css', 10, 3 );

		if ( ! empty( $this->configs['starter-content'] ) ) {
			$starter_content = new Boldgrid_Framework_Starter_Content( $this->configs );
			$this->loader->add_action( 'after_setup_theme', $starter_content, 'add_theme_support' );
			$this->loader->add_filter( 'get_theme_starter_content', $starter_content, 'add_custom_logo', 9, 2 );
			$this->loader->add_filter( 'get_theme_starter_content', $starter_content, 'add_post_meta', 10, 2 );
			$this->loader->add_filter( 'get_theme_starter_content', $starter_content, 'post_content_callbacks' );
			$this->loader->add_filter( 'get_theme_starter_content', $starter_content, 'remove_custom_logo', 999, 2 );
		}

		// Edit post links.
		if ( true === $this->configs['edit-post-links']['enabled'] ) {
			$links = new Boldgrid_Framework_Edit_Post_Links( $this->configs );
			$this->loader->add_filter( 'edit_post_link', $links, 'get_link', 10, 3 );
		}

		// BoldGrid Post and Page Builder Support.
		$this->loader->add_filter( 'BoldgridEditor\PageBuilder', $boldgrid_ppb, 'set_theme_fonts' );

		// Actions.
		$this->loader->add_action( 'boldgrid_activate_framework', $activate, 'do_activate' );

		if ( true === $this->configs['tgm']['enabled'] ) {
			$this->loader->add_action( 'tgmpa_register', $activate, 'register_required_plugins' );
		}

		if ( ! $this->doing_cron ) {
			$this->loader->add_action( 'after_switch_theme', $activate, 'do_activate' );
		}

		// Stop WordPress from assigning widgets to our areas.
		remove_action( 'after_switch_theme', '_wp_sidebars_changed' );

		$this->loader->add_action( 'mce_external_plugins', $editor, 'add_tinymce_plugin' );

		// Gutenberg specific scripts/styles.
		$this->loader->add_action( 'enqueue_block_editor_assets', $editor, 'gutenberg_scripts' );

		// Add Kirki Fonts to WordPress Page/Post Editor.
		if ( true === $this->configs['customizer-options']['typography']['enabled'] && is_admin() ) {
			$this->loader->add_filter( 'kirki_dynamic_css_method', $editor, 'add_styles_method' );
			$this->loader->add_filter( 'mce_css', $editor, 'add_google_fonts' );
			$this->loader->add_action( 'admin_enqueue_scripts', $editor, 'enqueue_webfonts' );
		}

		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'admin_enqueue_scripts' );
		$this->loader->add_filter( 'tiny_mce_before_init', $editor, 'tinymce_body_class' );

		// If installing a plugin via tgmpa, then remove custom plugins_api hooks.
		$this->loader->add_action( 'init', $admin, 'remove_hooks' );

		$this->loader->add_action( 'bgtfw_pro_feature_cards', $pro_feature_cards, 'print_cards' );
	}

	/**
	 * This runs the hooks for our widget areas.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function boldgrid_widget_areas() {
		$widgets = new Boldgrid_Framework_Widgets( $this->configs );
		$this->loader->add_action( 'widgets_init', $widgets, 'create_config_widgets' );
		$this->loader->add_action( 'after_setup_theme', $widgets, 'add_dynamic_actions' );
		$this->loader->add_action( 'customize_preview_init', $widgets, 'wrap_widget_areas' );
		$this->loader->add_action( 'admin_head-widgets.php', $widgets, 'admin_sidebar_display' );
		$this->loader->add_action( 'sidebar_admin_setup', $widgets, 'sort_sidebars' );
	}

	/**
	 * This runs additional hooks that run on theme activation/setup.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function boldgrid_theme_setup() {
		$theme_setup   = new BoldGrid_Framework_Setup( $this->configs );
		$compile       = new Boldgrid_Framework_Scss_Compile( $this->configs );
		$color_compile = new Boldgrid_Framework_Compile_Colors( $this->configs );

		$this->loader->add_action( 'after_setup_theme', $theme_setup, 'boldgrid_setup' );
		// Add the active button styles from configs to the compiler file array if active.
		if ( true === $this->configs['components']['buttons']['enabled'] ) {
			$this->loader->add_filter( 'boldgrid_theme_helper_scss_files', $color_compile, 'get_button_color_files' );
		}

		// Remove .hentry from pages for valid schema markup.
		add_filter( 'post_class', array( 'BoldGrid_Framework_Schema', 'remove_hentry' ) );

		// TODO: Merge these standalone files into classes and our existing structure.
		$theme_setup->add_additional_setup();
	}

	/**
	 * This defines the core functionality of the framework's Layout section in the editor screens.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function layouts() {
		$layouts = new Boldgrid_Framework_Layouts_Post_Meta( $this->configs );

		/* Adds our custom meta box to page/post editor. */
		$this->loader->add_action( 'add_meta_boxes', $layouts, 'add' );

		/* Adds our styles/scripts for the custom meta box on the new post and edit post screens only. */
		$this->loader->add_action( 'admin_head-post.php', $layouts, 'styles' );
		$this->loader->add_action( 'admin_head-post-new.php', $layouts, 'styles' );

		$this->loader->add_action( 'customize_controls_print_styles', $layouts, 'styles' );

		/* Handle edit, ok, and cancel options within our custom meta box. */
		$this->loader->add_action( 'admin_enqueue_scripts', $layouts, 'enqueue_scripts' );
	}

	/**
	 * This defines the core functionality of the framework's customizer options.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function theme_customizer() {
		self::customizer_base();
		self::widget_areas();
		self::customizer_background_controls();
		self::device_preview();
		self::customizer_typography();
		self::customizer_colors();
		self::customizer_footer();
		self::contact_blocks();
		self::customizer_kirki();
		self::customizer_effects();
		self::customizer_widget_meta();
		self::customizer_search();
		self::customizer_notifications();
		self::customizer_query();
		self::customizer_edit_buttons();
	}

	/**
	 * Customizer Edit Buttons.
	 *
	 * Defines hooks for customizer edit buttons.
	 *
	 * @since    2.9.0
	 * @access   private
	 */
	private function customizer_edit_buttons() {
		$edit_buttons = new Boldgrid_Framework_Customizer_Edit( $this->configs );
		$this->loader->add_action( 'customize_preview_init', $edit_buttons, 'generate_edit_params' );
		$this->loader->add_action( 'wp_enqueue_scripts', $edit_buttons, 'wp_enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $edit_buttons, 'wp_footer' );
	}

	/**
	 * This defines the core functionality of the framework's customizer start content import button.
	 *
	 * @since    2.1.1
	 * @access   private
	 */
	private function customizer_query() {
		$query = new Boldgrid_Framework_Customizer_Starter_Content_Query();
		$this->loader->add_action( 'customize_preview_init', $query, 'make_auto_drafts_queryable' );
		$this->loader->add_action( 'pre_get_posts', $query, 'set_main_query' );
		$this->loader->add_filter( 'widget_posts_args', $query, 'set_recent_posts_query' );
	}

	/**
	 * This defines the core functionality of the framework's customizer background controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_background_controls() {
		$background = new Boldgrid_Framework_Customizer_Background( $this->configs );
		$this->loader->add_action( 'customize_register', $background, 'add_patterns' );
		$this->loader->add_action( 'customize_register', $background, 'add_background_crop', 11 );
		$this->loader->add_action( 'customize_register', $background, 'rearrange_menu', 999 );
		$this->loader->add_action( 'wp_enqueue_scripts', $background, 'add_styles' );
		$this->loader->add_filter( 'boldgrid_mce_inline_styles', $background, 'add_editor_styles' );

		// Only do this on 4.7 and above.
		if ( version_compare( get_bloginfo( 'version' ), '4.6.2', '>=' ) ) {
			$this->loader->add_action( 'customize_register', $background, 'boldgrid_background_attachment', 999 );
			$this->loader->add_action( 'customize_sanitize_background_attachment', $background, 'pre_sanitize_attachment', 5 );
			$this->loader->add_filter( 'customize_sanitize_background_attachment', $background, 'post_sanitize_attachment', 20 );
		}
	}

	/**
	 * This defines the core functionality of the framework's customizer typography controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_typography() {
		$typography = new BoldGrid_Framework_Customizer_Typography( $this->configs );
		$this->loader->add_filter( 'boldgrid_mce_inline_styles', $typography, 'generate_font_size_css' );
		$this->loader->add_filter( 'boldgrid_mce_inline_styles', $typography, 'inline_font_css' );
		$this->loader->add_filter( 'boldgrid-override-styles-content', $typography, 'add_font_size_css' );
		$this->loader->add_action( 'wp_enqueue_scripts', $typography, 'override_kirki_styles' );
		$this->loader->add_filter( 'customize_refresh_nonces', $typography, 'header_column_nonces' );
		$this->loader->add_action( 'wp_ajax_responsive_font_sizes', $typography, 'wp_ajax_responsive_font_sizes' );

		/*
		 * Sometimes we need changes made in the customizer to be saved to the kirki styles.css
		 * before they have a chance to be enqueued by wp_enqueue_scripts. Therefore, we need to
		 * hook this to the 'customize_save_after' action hook.
		 */
		$this->loader->add_action( 'customize_save_after', $typography, 'override_kirki_styles' );

		$links = new BoldGrid_Framework_Links( $this->configs );
		$this->loader->add_filter( 'wp_enqueue_scripts', $links, 'add_styles_frontend' );
		$this->loader->add_filter( 'boldgrid_mce_inline_styles', $links, 'add_styles_editor' );

		$this->sticky_header();
	}

	/**
	 * This defines the core functionality of the framework's customizer sticky header.
	 *
	 * @since    2.0.3
	 * @access   private
	 */
	private function sticky_header() {
		$sticky = new BoldGrid_Framework_Sticky_Header( $this->configs );
		$this->loader->add_filter( 'wp_enqueue_scripts', $sticky, 'add_styles_frontend' );
	}

	/**
	 * This defines the core functionality of the framework's customizer color controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_colors() {
		$colors             = new Boldgrid_Framework_Customizer_Colors( $this->configs );
		$stylesheet         = get_stylesheet();
		$staging_stylesheet = get_option( 'boldgrid_staging_stylesheet', '' );

		// Color Palette Controls.
		$this->loader->add_action( 'customize_preview_init', $colors, 'enqueue_preview_color_palette' );
		$this->loader->add_filter( 'customize_changeset_save_data', $colors, 'changeset_data' );
		$this->loader->add_action( 'customize_save_after', $colors, 'update_theme_mods' );
		$this->loader->add_action( 'customize_register', $colors, 'customize_register_action' );
		$this->loader->add_action( 'admin_enqueue_scripts', $colors, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $colors, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $colors, 'enqueue_front_end_styles' );
		$this->loader->add_filter( 'customize_sanitize_boldgrid_color_palette', $colors, 'customize_sanitize_save_palettes' );
		$this->loader->add_filter( 'body_class', $colors, 'boldgrid_filter_body_class' );
		$this->loader->add_action( 'update_option_theme_mods_' . $stylesheet, $colors, 'update_color_palette', 10, 2 );
		$this->loader->add_action( 'update_option_boldgrid_staging_theme_mods_' . $staging_stylesheet, $colors, 'update_color_palette', 10, 2 );
		$this->loader->add_action( 'add_option_theme_mods_' . $stylesheet, $colors, 'update_color_palette', 10, 2 );
		$this->loader->add_action( 'add_option_boldgrid_staging_theme_mods_' . $staging_stylesheet, $colors, 'update_color_palette', 10, 2 );

		/** If the staging theme and the active theme are different, bind the opposite
		 * update actions. This is needed for "Launch Staging" or any other instances where
		 * the theme mods for the active theme mod are changed to staging
		 */
		if ( get_stylesheet() !== $staging_stylesheet ) {
			// When the staging theme updates active mods.
			$this->loader->add_action( 'update_option_theme_mods_' . $staging_stylesheet, $colors, 'update_color_palette', 10, 2 );
			// When the active theme updates staging mods.
			$this->loader->add_action( 'add_option_theme_mods_' . $staging_stylesheet, $colors, 'update_color_palette', 10, 2 );
			// When the staging theme updates active mods.
			$this->loader->add_action( 'update_option_boldgrid_staging_theme_mods_' . $stylesheet, $colors, 'update_color_palette', 10, 2 );
			// When the active theme updates staging mods.
			$this->loader->add_action( 'add_option_boldgrid_staging_theme_mods_' . $stylesheet, $colors, 'update_color_palette', 10, 2 );
		}
	}

	/**
	 * This defines the core functionality of the framework's customizer footer controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_footer() {
		$footer = new BoldGrid_Framework_Customizer_Footer();
		$this->loader->add_action( 'boldgrid_display_attribution_links', $footer, 'attribution_display_action' );
		add_action( 'customize_save_after', array( 'Boldgrid_Framework_Customizer_Footer', 'customize_links' ), 999 );
		add_action( 'customize_controls_print_styles', array( 'Boldgrid_Framework_Customizer_Footer', 'customize_attribution' ), 999 );
	}

	/**
	 * This defines the core functionality of the framework's contact blocks.
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function contact_blocks() {
		$contact_blocks = new Boldgrid_Framework_Customizer_Contact_Blocks( $this->configs );
		$this->loader->add_action( 'boldgrid_display_contact_block', $contact_blocks, 'contact_block_html' );
	}

	/**
	 * This defines the core functionality of the framework's customizer Kirki implementation.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_kirki() {
		$kirki = new Boldgrid_Framework_Customizer_Kirki( $this->configs );
		$this->loader->add_filter( 'kirki/config', $kirki, 'general_kirki_configs' );
		$this->loader->add_filter( 'kirki/bgtfw/l10n', $kirki, 'l10n' );
	}

	/**
	 * This defines the core functionality of the framework's customizer base controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_base() {
		$base = new BoldGrid_Framework_Customizer( $this->configs );

		// Load the default Kirki Configuration.
		$this->loader->add_action( 'init', $base, 'kirki_controls' );
		$this->loader->add_action( 'customize_register', $base, 'add_panels' );
		$this->loader->add_action( 'customize_register', $base, 'blog_name' );
		$this->loader->add_action( 'customize_register', $base, 'blog_description' );
		$this->loader->add_action( 'customize_register', $base, 'header_panel' );
		$this->loader->add_action( 'customize_register', $base, 'customizer_reorganization' );
		$this->loader->add_action( 'customize_register', $base, 'set_text_contrast' );
		$this->loader->add_action( 'customize_register', $base, 'add_menu_description', 20 );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $base, 'custom_customize_enqueue' );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $base, 'enqueue_styles' );
		$this->loader->add_action( 'customize_controls_print_styles', $base, 'control_styles' );

		// This hook can be used to add any styles to the head.
		$this->loader->add_action( 'wp_enqueue_scripts', $base, 'add_head_styles' );

		// Output custom JS to live site.
		$this->loader->add_action( 'wp_footer', $base, 'custom_js_output' );

		// Enqueue live preview javascript in Theme Customizer admin screen.
		$this->loader->add_action( 'customize_preview_init', $base, 'live_preview' );

		$this->loader->add_action( 'customize_register', $base, 'register_colwidth_control' );
		$this->loader->add_action( 'customize_register', $base, 'register_responsive_font_controls' );

		$this->loader->add_action( 'wp_ajax_bgtfw_header_preset', $base->presets, 'wp_ajax_bgtfw_header_layout' );
		$this->loader->add_filter( 'customize_refresh_nonces', $base->presets, 'header_layout_nonces' );
		$this->loader->add_action( 'customize_save_after', $base->presets, 'starter_content_defaults' );
		$this->loader->add_filter( 'customize_refresh_nonces', $base, 'header_column_nonces' );
		$this->loader->add_filter( 'customize_refresh_nonces', $base, 'container_width_nonce' );
		$this->loader->add_action( 'wp_ajax_bgtfw_container_width', $base, 'wp_ajax_bgtfw_container_width' );
		$this->loader->add_action( 'wp_ajax_bgtfw_header_columns', $base, 'wp_ajax_bgtfw_header_columns' );
	}

	/**
	 * Responsible for creating the dynamic widget area markup.
	 *
	 * @since 2.0.0
	 */
	private function widget_areas() {
		$widget_areas = new Boldgrid_Framework_Customizer_Widget_Areas();
	}

	/**
	 * This defines the core functionality of the framework's customizer effect controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_effects() {
		$effects = new BoldGrid_Framework_Customizer_Effects( $this->configs );
	}


	/**
	 * Add hooks to customizer register action.
	 *
	 * @since 2.0.0
	 */
	private function customizer_search() {
		$search = new Boldgrid_Framework_Customizer_Search( $this->configs );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $search, 'enqueue' );
		$this->loader->add_action( 'customize_controls_print_footer_scripts', $search, 'print_templates' );
	}

	/**
	 * This defines the core functionality of the extended widget meta controls for
	 * adding color and title fields to widget areas.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function customizer_widget_meta() {
		$widget_meta = new Boldgrid_Framework_Customizer_Widget_Meta( $this->configs );
		$this->loader->add_action( 'customize_register', $widget_meta, 'customize_register' );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $widget_meta, 'customize_controls_enqueue_scripts' );
		$this->loader->add_action( 'customize_controls_print_footer_scripts', $widget_meta, 'customize_controls_print_footer_scripts' );
		$this->loader->add_action( 'customize_preview_init', $widget_meta, 'customize_preview_init' );

		if ( ! is_admin() ) {
			$this->loader->add_action( 'dynamic_sidebar_before', $widget_meta, 'render_sidebar_start_tag', 5 );
			$this->loader->add_action( 'dynamic_sidebar_before', $widget_meta, 'render_sidebar_title', 9 );
		}

		if ( is_customize_preview() ) {
			$this->loader->add_action( 'wp_enqueue_scripts', $widget_meta, 'add_customizer_sidebar_styles' );
		} else {
			$this->loader->add_filter( 'bgtfw_inline_css', $widget_meta, 'add_frontend_sidebar_styles' );
		}
	}

	/**
	 * This defines the core functionality of the framework's notifications in the customizer.
	 *
	 * @since    2.1.1
	 * @access   private
	 */
	private function customizer_notifications() {
		if ( is_customize_preview() ) {
			$notifications = new Boldgrid_Framework_Customizer_Notification();

			$this->loader->add_action( 'customize_controls_print_footer_scripts', $notifications, 'print_template' );
		}
	}

	/**
	 * Load Device Previewer For Customizer
	 *
	 * This will load the Device Previewer in the WordPress Customizer
	 * if the user is not using a mobile device to access the site.  The
	 * Device Previewer should only be available for Desktop views.  This
	 * is not based on window width, based on actual device's UA.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function device_preview() {
		$device_preview = new Boldgrid_Framework_Device_Preview( $this->configs );

		$this->loader->add_filter( 'customize_previewable_devices', $device_preview, 'customize_previewable_devices' );
		$this->loader->add_action( 'customize_controls_print_styles', $device_preview, 'adjust_customizer_responsive_sizes' );

		// We don't need device previews if user is running on a mobile device or newer WP.
		$version = version_compare( get_bloginfo( 'version' ), '4.4.2', '>' );

		if ( wp_is_mobile() || $version ) {
			return;
		}

		$this->loader->add_action( 'customize_controls_enqueue_scripts', $device_preview, 'enqueue_scripts' );
		$this->loader->add_action( 'customize_controls_print_footer_scripts', $device_preview, 'print_templates' );
	}

	/**
	 * Adds the BoldGrid Theme Framework Bootstrap comment form template and functionality.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function comments() {
		$comments = new Boldgrid_Framework_Comments( $this->configs );
		$this->loader->add_action( 'boldgrid_comments', $comments, 'boldgrid_comments' );
	}

	/**
	 * Adds 404 markup for displaying the default 404 page template across all themes.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function error_404() {
		$four_oh_four = new Boldgrid_Framework_404( $this->configs );
		$this->loader->add_action( 'boldgrid_404', $four_oh_four, 'boldgrid_404_template' );
	}

	/**
	 * Render search forms in the BoldGrid Theme Framework.  This is called
	 * by WordPress via searchform.php which hooks into boldgrid_search_form.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function search_forms() {
		$search_forms = new Boldgrid_Framework_Search_Forms( $this->configs );
		$this->loader->add_action( 'boldgrid_search_form', $search_forms, 'boldgrid_search_template' );
	}

	/**
	 * Responsible for displaying custom pagination in bgtfw.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function pagination() {
		$pagination = new BoldGrid_Framework_Pagination();
		$this->loader->add_action( 'bgtfw_pagination_display', $pagination, 'create' );
	}

	/**
	 * Adds in wooCommerce specific functionality added by the BoldGrid Theme Framework.
	 *
	 * @since 1.4.1
	 * @access private
	 */
	private function woocommerce() {
		$woo       = new Boldgrid_Framework_Woocommerce( $this->configs );
		$this->woo = $woo;
		$this->loader->add_action( 'wp_loaded', $woo, 'remove_template_warnings', 99 );
		$this->loader->add_filter( 'customize_register', $woo, 'customizer', 20 );
		$this->loader->add_filter( 'woocommerce_loop_add_to_cart_link', $woo, 'buttons' );
		$this->loader->add_filter( 'woocommerce_sale_flash', $woo, 'woocommerce_custom_sale_text', 10, 3 );
		$this->loader->add_action( 'wp_enqueue_scripts', $woo, 'enqueue' );
		$this->loader->add_action( 'wp_enqueue_scripts', $woo, 'remove_select2', 100 );
		$this->loader->add_filter( 'woocommerce_breadcrumb_defaults', $woo, 'breadcrumbs' );
		$this->loader->add_filter( 'woocommerce_quantity_input_classes', $woo, 'quantity_input_classes' );
		$this->loader->add_action( 'woocommerce_before_quantity_input_field', $woo, 'quantity_input_before' );
		$this->loader->add_action( 'woocommerce_after_quantity_input_field', $woo, 'quantity_input_after' );
		$this->loader->add_action( 'woocommerce_before_cart', $this->woo, 'add_page_title' );
		$this->loader->add_action( 'woocommerce_before_checkout_form', $this->woo, 'add_page_title' );

		remove_all_actions( 'woocommerce_sidebar' );
		add_filter(
			'loop_shop_per_page',
			function( $cols ) {
				return 12;
			},
			20
		);
		add_action(
			'template_redirect',
			function() use ( $woo ) {
				if ( $woo->is_woocommerce_page() ) {
					add_action( 'boldgrid_main_top', array( $woo, 'add_container_open' ) );
					add_action( 'boldgrid_main_bottom', array( $woo, 'add_container_close' ) );
				}
			}
		);

		/**
		 * Change number of products that are displayed per page (shop page)
		 */
		add_filter( 'loop_shop_per_page', array( $woo, 'products_per_page' ), 20 );
	}

	/**
	 * Adds in page title functionality for bgtfw.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function title() {
		$title = new BoldGrid_Framework_Title( $this->configs );

		$this->loader->add_action( 'post_updated', $title, 'post_updated' );
		$this->loader->add_filter( 'the_title', $title, 'show_title', 10, 2 );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $title, 'customize_controls_enqueue_scripts' );
	}

	/**
	 * Adds filter to menus to add social icons for all menu locations
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function social_icons() {
		$social_icons = new Boldgrid_Framework_Social_Media_Icons( $this->configs );
		$this->loader->add_filter( 'wp_nav_menu_objects', $social_icons, 'wp_nav_menu_objects', 5, 2 );
	}

	/**
	 * Run the BoldGrid SCSS Compiler on theme activation and upgrades.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_global_hooks() {
		$auto_compile_enabled = defined( 'BOLDGRID_THEME_HELPER_SCSS_COMPILE' ) ? BOLDGRID_THEME_HELPER_SCSS_COMPILE : null;

		$scss    = new Boldgrid_Framework_SCSS( $this->configs );
		$staging = new Boldgrid_Framework_Staging( $this->configs );
		$compile = new Boldgrid_Framework_Scss_Compile( $this->configs );

		// If the user has access, and your configuration flag is set to on.
		if ( ( true === $this->configs['components']['bootstrap']['enabled'] ||
			true === $this->configs['components']['buttons']['enabled'] ) &&
			$auto_compile_enabled ) {
				$this->loader->add_action( 'wp_loaded', $compile, 'build' );
				$this->loader->add_action( 'wp_loaded', $scss, 'update_css' );
		}

		$this->loader->add_action( 'init', $staging, 'launch_staging_process', 998 );
		$this->loader->add_action( 'init', $scss, 'force_recompile_checker', 999 );

		if ( ! $this->doing_cron ) {
			$this->loader->add_action( 'after_switch_theme', $scss, 'force_update_css', 999 );
		}
		$this->loader->add_action( 'upgrader_process_complete', $scss, 'theme_upgrader_process', 10, 3 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Boldgrid_Framework_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}
