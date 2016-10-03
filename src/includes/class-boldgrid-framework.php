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

		$this->load_dependencies( );

		$this->assign_configurations();
		$this->add_config_filters();
		$this->load_theme_configs();
		$this->set_doing_cron();

		$this->define_theme_hooks( );
		$this->define_admin_hooks( );
		$this->define_global_hooks( );
		$this->boldgrid_theme_setup( );
		$this->setup_menus( );
		$this->boldgrid_widget_areas( );
		$this->boldgrid_theme_developer_tools( );
		$this->theme_customizer( );
		$this->social_icons( );
		$this->comments( );
		$this->error_404( );
		$this->search_forms( );
		$this->ninja_forms( );

		// TODO: find place for TEMP.
		require_once( trailingslashit( __DIR__ ) . 'black-studio-tinymce-widget/black-studio-tinymce-widget.php' );
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
	 * 	1st, they are ran by the WordPress cron, if DISABLE_WP_CRON is not set to true.
	 * 	2nd, they are ran by an ajax call
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
	 * - Boldgrid_Framework_Ninja_Forms          Adds filters for Ninja Forms to have Bootstrap styles applied.
	 * - Boldgrid_Framework_Pointer              Responsible for the WordPress pointer functionality seen in customizer.
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
		require_once( trailingslashit( __DIR__ ) . 'interface-boldgrid-framework-compile.php' );
		// Include utility classes.
		$library_files = array(
			'404',
			'admin',
			'activate',
			'api',
			'comments',
			'compile-colors',
			'editor',
			'i18n',
			'loader',
			'menu',
			'ninja-forms',
			'pointer',
			'schema-markup',
			'scripts',
			'scss',
			'scss-compile',
			'search-forms',
			'setup',
			'social-media-icons',
			'staging',
			'styles',
			'template-config',
			'widgets',
			'wp-fs',
			'wrapper',
		);

		foreach ( $library_files as $filename ) {
			require_once( trailingslashit( __DIR__ ) . 'class-boldgrid-framework-' . $filename . '.php' );
		}

		// Load Customizer Files.
		$this->load_customizer_files();

		/**
		 * Load the WP Bootstrap Custom Nav Walker.
		 *
		 * @link https://github.com/twittem/wp-bootstrap-navwalker
		 * @since 1.0.0
		 */
		require_once trailingslashit( __DIR__ ) . 'partials/wp_bootstrap_navwalker.php';

		/**
		 * Include the TGM_Plugin_Activation class.
		 */
		require_once trailingslashit( __DIR__ ) . 'tgm/class-tgm-plugin-activation.php';



		// Loader instance.
		$this->loader = new Boldgrid_Framework_Loader( );
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
	 * Include additional configuration options to assign.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function assign_configurations() {
		$this->configs = include plugin_dir_path( dirname( __FILE__ ) ) . 'includes/configs/configs.php';
		// Based on the configs already assigned, set config values to help assign later values.
		$this->assign_dynamic_configs();
		// Assign configs.
		$this->assign_configs();
		$this->assign_configs( 'customizer-options' );
		$this->assign_configs( 'components' );
	}

	/**
	 * Assign theme mod configs.
	 *
	 * @since    1.1.5
	 * @access   protected.
	 */
	public function add_config_filters() {
		$effects = new BoldGrid_Framework_Customizer_Effects( $this->configs );
		$typography = new Boldgrid_Framework_Customizer_Typography( $this->configs );
		$template_config = new Boldgrid_Framework_Template_Config( $this->configs );
		add_filter( 'boldgrid_theme_framework_config', array( $effects, 'enable_configs'), 20 );
		add_filter( 'boldgrid_theme_framework_config', array( $typography, 'set_configs'), 20 );
		add_filter( 'boldgrid_theme_framework_config', 'BoldGrid::get_inspiration_configs', 5 );

		if ( ! is_admin() ) {
			add_filter( 'boldgrid_theme_framework_config', array( $template_config, 'remove_theme_container' ), 50 );
		}
	}

	/**
	 * Set configs that will be used to create other configs.
	 *
	 * @since    1.1.4
	 * @access   protected.
	 */
	protected function assign_dynamic_configs() {

		$theme_directory = get_template_directory();
		$theme_directory_uri = get_template_directory_uri();

		// If we are using an authors child theme, paths need to be changed to look at the child.
		$menu = new Boldgrid_Framework_Menu( $this->configs );
		if ( is_child_theme() && false === $menu->is_user_child() ) {
			$theme_directory = get_stylesheet_directory();
			$theme_directory_uri = get_stylesheet_directory_uri();
		}

		$this->configs['framework']['config_directory']['template'] = $theme_directory;
		$this->configs['framework']['config_directory']['uri'] = $theme_directory_uri;
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
		$path = __DIR__ . '/configs/'. $folder;
		foreach ( glob( $path . '/*.config.php' ) as $filename ) {
			$option = basename( str_replace( '.config.php', '', $filename ) );
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
		$this->configs['directories']['BOLDGRID_THEME_VER'] = $this->configs['theme_name'];
	}

	/**
	 * This defines the core functionality of the themes and associated template actions.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_theme_hooks() {
		$styles  = new BoldGrid_Framework_Styles( $this->configs );
		$scripts = new BoldGrid_Framework_Scripts( $this->configs );
		$boldgrid_theme   = new BoldGrid( $this->configs );
		$template         = new Boldgrid_Framework_Template_Config( $this->configs );

		// Load Theme Wrapper.
		if ( true === $this->configs['boldgrid-parent-theme'] ) {
			$wrapper  = new Boldgrid_Framework_Wrapper();
			$this->loader->add_filter( 'template_include', $wrapper, 'wrap', 109 );
		}

		// Register Locations.
		$this->loader->add_action( 'boldgrid-theme-location', $template, 'do_location_action', 10, 2 );
		$this->loader->add_action( 'wp_footer', $template, 'print_styles', 10, 2 );

		// Add Theme Styles.
		$this->loader->add_action( 'wp_enqueue_scripts', $styles, 'boldgrid_enqueue_styles' );
		$this->loader->add_action( 'after_setup_theme',  $styles, 'add_editor_styling' );
		$this->loader->add_filter( 'mce_css', $styles, 'add_cache_busting' );
		$this->loader->add_filter( 'local_editor_styles', $styles, 'enqueue_editor_buttons' );

		// Add Theme Scripts.
		$this->loader->add_action( 'wp_enqueue_scripts', $scripts, 'boldgrid_enqueue_scripts' );

		// Setup Header Metadata.
		$this->loader->add_action( 'boldgrid_head_top',     $boldgrid_theme,   'boldgrid_meta_charset',  105 );
		$this->loader->add_action( 'boldgrid_head_top',     $boldgrid_theme,   'boldgrid_enable_xua',    106 );
		$this->loader->add_action( 'boldgrid_head_top',     $boldgrid_theme,   'boldgrid_meta_viewport', 107 );
		$this->loader->add_action( 'boldgrid_head_top',     $boldgrid_theme,   'boldgrid_link_profile',  108 );
		$this->loader->add_action( 'boldgrid_head_top',     $boldgrid_theme,   'boldgrid_link_pingback', 109 );

		// Setup Sticky Footer.
		$this->loader->add_action( 'boldgrid_header_before', $boldgrid_theme, 'boldgrid_sticky_top',    10 );
		$this->loader->add_action( 'boldgrid_footer_before', $boldgrid_theme, 'boldgrid_sticky_bottom', 15 );

		// Additional theme functionality.
		$this->loader->add_action( 'wp',                            $boldgrid_theme,   'setup_author' );
		$this->loader->add_filter( 'body_class',                    $boldgrid_theme,   'body_classes' );
		$this->loader->add_filter( 'wp_page_menu_args',             $boldgrid_theme,   'page_menu_args' );
		$this->loader->add_filter( 'boldgrid_print_tagline',        $boldgrid_theme,   'print_tagline' );
		$this->loader->add_filter( 'bolgrid_call_to_action',        $boldgrid_theme,   'call_to_action' );
		$this->loader->add_filter( 'bolgrid_call_to_action_button', $boldgrid_theme,   'cta_button' );
		$this->loader->add_filter( 'boldgrid_contact_phone',        $boldgrid_theme,   'contact_phone' );
		$this->loader->add_filter( 'boldgrid_site_title',           $boldgrid_theme,   'site_logo_or_title' );
		$this->loader->add_filter( 'boldgrid_site_identity',        $boldgrid_theme,   'print_title_tagline' );
		$this->loader->add_filter( 'boldgrid_primary_navigation',   $boldgrid_theme,   'print_primary_navigation' );
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

		if ( ! $this->doing_cron ) {
			$this->loader->add_action( 'after_switch_theme', $menu, 'disable_advanced_nav_options' );
			$this->loader->add_action( 'after_switch_theme', $menu, 'transfer_menus', 10, 2 );
		}
	}

	/**
	 * This contains hooks that impact the admin side of the theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$admin = new BoldGrid_Framework_Admin( $this->configs );
		$activate = new Boldgrid_Framework_Activate( $this->configs );
		$editor = new Boldgrid_Framework_Editor( $this->configs );
		// Actions.
		$this->loader->add_action( 'boldgrid_activate_framework', $activate, 'do_activate' );
		$this->loader->add_filter( 'update_option_boldgrid_staging_stylesheet', $activate, 'do_staging_activate', 99, 2 );
		$this->loader->add_action( 'boldgrid_framework_reset', $activate, 'reset' );
		$this->loader->add_action( 'wp_ajax_boldgrid_reset_theme_mods', $activate, 'undo_theme_mod_transfer' );

		if ( true === $this->configs['tgm']['enabled'] ) {
			$this->loader->add_action( 'tgmpa_register', $activate, 'register_required_plugins' );
		}

		if ( ! $this->doing_cron ) {
			$this->loader->add_action( 'after_switch_theme', $activate, 'do_activate' );
		}

		// Stop Wordpress from assigning widgets to our areas.
		remove_action( 'after_switch_theme', '_wp_sidebars_changed' );

		$this->loader->add_action( 'mce_external_plugins', $editor, 'add_tinymce_plugin' );

		// Add Kirki Fonts to WordPress Page/Post Editor.
		if ( true === $this->configs['customizer-options']['typography']['enabled'] ) {
			$this->loader->add_action( 'wp_loaded', $editor, 'add_google_fonts' );
		}

		$this->loader->add_action( 'init', $editor, 'add_post_title_toggle' );
		$this->loader->add_action( 'save_post', $editor, 'update_page_title_toggle', 10, 2 );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'admin_enqueue_scripts' );
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
		$this->loader->add_action( 'widgets_init', $widgets, 'sidebar_widgets' );
		$this->loader->add_action( 'customize_preview_init', $widgets, 'wrap_widget_areas' );
		$this->loader->add_action( 'dynamic_sidebar_params', $widgets, 'wrap_bg_widgets' );
	}

	/**
	 * This runs additional hooks that run on theme activation/setup.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function boldgrid_theme_setup() {
		$theme_setup = new BoldGrid_Framework_Setup( $this->configs );
		$compile = new Boldgrid_Framework_Scss_Compile( $this->configs );
		$color_compile = new Boldgrid_Framework_Compile_Colors( $this->configs );

		$this->loader->add_action( 'after_setup_theme', $theme_setup, 'boldgrid_setup' );

		// Add the active button styles from configs to the compiler file array if active.
		if ( true === $this->configs['components']['buttons']['enabled'] ) {
			$this->loader->add_filter( 'boldgrid_theme_helper_scss_files', $color_compile, 'get_button_color_files' );
		}

		// Save the compiled CSS when themes are activated and after they save customizer settings.
		if ( true === $this->configs['components']['bootstrap']['enabled'] ||
			 true === $this->configs['components']['buttons']['enabled'] ) {
				//$this->loader->add_action( 'customize_save_after', $compile, 'build' );
		}

		// TODO: Merge these standalone files into classes and our existing structure.
		$theme_setup->add_additional_setup();
	}

	/**
	 * This defines the core functionality of the framework's customizer options.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function theme_customizer() {
		self::customizer_base();
		self::customizer_background_controls();
		self::device_preview();
		self::customizer_edit_buttons();
		self::customizer_typography();
		self::customizer_colors();
		self::customizer_footer();
		self::customizer_kirki();
		self::customizer_effects();
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
		$this->loader->add_action( 'customize_register', $background, 'add_position' );
		$this->loader->add_action( 'customize_register', $background, 'add_color_picker' );
		$this->loader->add_action( 'customize_register', $background, 'add_background_size' );
		$this->loader->add_action( 'customize_register', $background, 'add_background_type' );
		$this->loader->add_action( 'customize_register', $background, 'add_background_crop', 11 );
		$this->loader->add_action( 'customize_register', $background, 'rearrange_menu', 999 );
		$this->loader->add_action( 'admin_enqueue_scripts', $background, 'register_control_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $background, 'register_front_end_scripts' );
		$this->loader->add_filter( 'boldgrid_add_head_styles', $background, 'add_head_styles_filter' );
	}

	/**
	 * This defines the core functionality of the framework's customizer edit buttons.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_edit_buttons() {
		$edit = new Boldgrid_Framework_Customizer_Edit( $this->configs );
		$this->loader->add_action( 'wp_enqueue_scripts', $edit, 'wp_enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $edit, 'wp_footer' );
		$this->loader->add_action( 'wp_nav_menu_args', $edit, 'wp_nav_menu_args' );
		$this->loader->add_filter( 'has_nav_menu', $edit, 'has_nav_menu', 10, 2 );
	}

	/**
	 * This defines the core functionality of the framework's customizer typography controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_typography() {
		$typography = new BoldGrid_Framework_Customizer_Typography( $this->configs );
		// Add Typography Controls.
		if ( true === $this->configs['customizer-options']['typography']['enabled'] ) {
			$this->loader->add_action( 'customize_controls_enqueue_scripts', $typography, 'enqueue_scripts' );
			$this->loader->add_action( 'customize_register', $typography, 'typography_panel' );
			$this->loader->add_filter( 'kirki/controls', $typography, 'headings_typography_controls' );
			$this->loader->add_filter( 'kirki/controls', $typography, 'alternate_headings_typography_controls' );
			$this->loader->add_filter( 'kirki/controls', $typography, 'navigation_typography_controls' );
			$this->loader->add_filter( 'kirki/controls', $typography, 'body_typography_controls' );
			$this->loader->add_action( 'wp_head', $typography, 'headings_font_size_css' );
			$this->loader->add_filter( 'boldgrid_mce_inline_styles', $typography, 'headings_editor_styles' );
		}
		// Add Site title typography controls.
		$this->loader->add_action( 'customize_preview_init', $typography, 'live_preview' );
		$this->loader->add_filter( 'kirki/controls', $typography, 'site_identity_controls' );
		$this->loader->add_action( 'wp_head', $typography, 'title_text_shadow' );
	}

	/**
	 * This defines the core functionality of the framework's customizer color controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_colors() {
		$colors = new Boldgrid_Framework_Customizer_Colors( $this->configs );
		$stylesheet = get_stylesheet();
		$staging_stylesheet = get_option( 'boldgrid_staging_stylesheet', '' );
		// Color Palette Controls.
		$this->loader->add_action( 'customize_preview_init', $colors, 'enqueue_preview_color_palette' );
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
		$footer = new BoldGrid_Framework_Customizer_Footer( $this->configs );
		$this->loader->add_action( 'customize_register', $footer, 'footer_panel' );
		$this->loader->add_action( 'customize_register', $footer, 'add_enable_control' );
		$this->loader->add_action( 'customize_register', $footer, 'add_attrbution_control' );
		$this->loader->add_action( 'body_class', $footer, 'collapse_body_margin' );
		$this->loader->add_action( 'boldgrid_display_attribution_links', $footer, 'attribution_display_action' );
		$this->loader->add_action( 'boldgrid_footer_before', $footer, 'maybe_remove_all_footer_actions' );
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
	}

	/**
	 * This defines the core functionality of the framework's customizer base controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_base() {
		$base = new BoldGrid_Framework_Customizer( $this->configs );
		$this->loader->add_action( 'customize_register', $base, 'site_logo' );
		$this->loader->add_action( 'customize_register', $base, 'blog_name' );
		$this->loader->add_action( 'customize_register', $base, 'blog_description' );
		$this->loader->add_action( 'customize_register', $base, 'advanced_panel' );
		$this->loader->add_action( 'customize_register', $base, 'header_panel' );
		$this->loader->add_action( 'customize_register', $base, 'init_help' );
		$this->loader->add_action( 'customize_register', $base, 'customizer_reorganization' );
		$this->loader->add_action( 'customize_register', $base, 'set_text_contrast' );
		$this->loader->add_action( 'customize_register', $base, 'add_menu_description', 20 );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $base, 'custom_customize_enqueue' );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $base, 'enqueue_styles' );
		$this->loader->add_action( 'customize_preview_init', $base, 'add_help_overlay' );
		// Output custom CSS and JS to live site.
		$this->loader->add_action( 'wp_head', $base, 'custom_css_output' );
		// This hook can be used to add any styles to the head.
		$this->loader->add_action( 'wp_head',   $base, 'add_head_styles', 9001 );
		$this->loader->add_action( 'wp_footer', $base, 'custom_js_output' );
		// Custom Hooks for preview mode and live site.
		$this->loader->add_action( 'wp_head', $base, 'boldgrid_preview_hooks', 9999, 0 );
		// Display Widgets.
		$this->loader->add_action( 'boldgrid_footer_top',   $base, 'footer_widget_html' );
		$this->loader->add_action( 'boldgrid_header_bottom',   $base, 'header_widget_html' );
		// Display HTML.
		$this->loader->add_action( 'boldgrid_header_top', $base, 'display_header_html' );
		$this->loader->add_action( 'boldgrid_footer_bottom', $base, 'display_footer_html' );
		// Enqueue live preview javascript in Theme Customizer admin screen.
		$this->loader->add_action( 'customize_preview_init', $base, 'live_preview' );
	}

	/**
	 * This defines the core functionality of the framework's customizer effect controls.
	 *
	 * @since    1.2.3
	 * @access   private
	 */
	private function customizer_effects() {
		$effects = new BoldGrid_Framework_Customizer_Effects( $this->configs );
		// Add Page Effects Controls.
		$this->loader->add_action( 'customize_register', $effects, 'add_controls' );
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
		$device_preview = new BoldGrid_Framework_Device_Preview( $this->configs );
		// We don't need device previews if user is running on a mobile device or newer WP.
		$wp_version = version_compare( get_bloginfo( 'version' ), '4.4.2', '>' );

		if ( wp_is_mobile() || $wp_version ) {
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
		$this->loader->add_action( 'boldgrid_comments',     $comments, 'boldgrid_comments' );
		$this->loader->add_action( 'comment_form',          $comments, 'bootstrap_comment_form', 10, 1 );
		$this->loader->add_filter( 'comment_form_defaults', $comments, 'bootstrap_comment_form_defaults', 10, 1 );
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
	 * Add in Bootstrap CSS Classes to Ninja Forms Forms.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function ninja_forms() {
		$ninja_forms = new Boldgrid_Framework_Ninja_Forms( $this->configs );
		$this->loader->add_action( 'ninja_forms_field', $ninja_forms, 'forms_field', 10, 2 );
		$this->loader->add_action( 'ninja_forms_label_class', $ninja_forms, 'forms_label_class', 10, 2 );
		$this->loader->add_filter( 'ninja_forms_display_field_wrap_class', $ninja_forms, 'forms_field_wrap_class', 10, 2 );
		$this->loader->add_filter( 'ninja_forms_form_class', $ninja_forms, 'forms_form_class', 10, 2 );
		$this->loader->add_filter( 'ninja_forms_form_wrap_class', $ninja_forms, 'forms_form_wrap_class', 10, 2 );
		$this->loader->add_filter( 'ninja_forms_display_field_desc_class', $ninja_forms, 'field_description_class', 10, 2 );
		$this->loader->add_filter( 'ninja_forms_display_field_processing_error_class', $ninja_forms, 'field_error_message_class', 10, 2 );
		$this->loader->add_filter( 'ninja_forms_display_required_items_class', $ninja_forms, 'form_required_items_class', 10, 2 );
		$this->loader->add_filter( 'ninja_forms_display_response_message_class', $ninja_forms, 'form_response_message_class', 10, 2 );
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

		$scss = new Boldgrid_Framework_SCSS( $this->configs );
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
		$this->loader->add_action( 'upgrader_process_complete', $scss , 'theme_upgrader_process', 10, 3 );
	}


	/**
	 * This is responsible for loading the BoldGrid Theme Framework
	 * developer tools and experimental features.  Use at your own risk :)
	 *
	 * @since    1.0.0
	 */
	private function boldgrid_theme_developer_tools() {
		if ( defined( 'BOLDGRID_THEME_DEV' ) && BOLDGRID_THEME_DEV === true ) {

			require_once BOLDGRID_LIB_INC_DEV . 'developer-tools.php';
			require_once BOLDGRID_LIB_INC_DEV . 'boldgrid-theme-builder.php';

			$boldgrid_dev = new BoldGrid_Theme_Developer_Tools( );

			$this->loader->add_action( 'admin_bar_menu', $boldgrid_dev, 'boldgrid_tools', 99 );

		}
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
