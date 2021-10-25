<?php
/**
 * Class: Boldgrid_Framework_Customizer_Colors
 *
 * This contains the color palette UI, and theme mod functionality
 * for the color palette selections in the WordPress customizer.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework_Customizer
 * @subpackage Boldgrid_Framework_Customizer_Colors
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Boldgrid_Framework_Customizer_Colors Class
 *
 * Class responsible for the color palette controls in customizer.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Customizer_Colors {

	/**
	 * WP_Customizer
	 *
	 * @var WP_Customizer
	 */
	private $wp_customize;

	/**
	 * Configuration array from the main plugin file
	 *
	 * @var array
	 */
	private $configs;

	/**
	 * Color Palettes that will be created
	 *
	 * @var array
	 */
	private $color_palettes = array();

	/**
	 * Setter for WP_Customizer
	 *
	 * @param array $s WP_Customizer object.
	 */
	public function set_wp_customize( $s ) {
		$this->wp_customize = $s;
	}

	/**
	 * Init the settings
	 *
	 * @param array $configs BoldGrid Theme Frameworkk Configs.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
		$this->colors = new Boldgrid_Framework_Compile_Colors( $this->configs );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( 'boldgird-theme-helper-color-palette',
			$this->configs['framework']['css_dir'] . 'customizer/controls' . $suffix . '.css',
			array( 'wp-color-picker', 'dashicons' ),
		$this->configs['version'], 'all' );
	}

	/**
	 * Action only runs in the customizer
	 *
	 * @param    array $wp_customize WP_Customizer object.
	 * @since    1.0.0
	 */
	public function customize_register_action( $wp_customize ) {
		require_once $this->configs['framework']['includes_dir']
			. 'control/class-boldgrid-framework-control-palette.php';

		$this->set_wp_customize( $wp_customize );
		$this->add_color_selection();
	}

	/**
	 * Sanitize user palettes data
	 *
	 * @param string $json Users data to sanitize.
	 * @return    array    $json     contains user's saved and sanitized color palettes.
	 * @since     1.0.0
	 */
	public function customize_sanitize_save_palettes( $json ) {
		return strip_tags( $json );
	}

	/**
	 * Enqueue Color palette preview JS on the customize page
	 *
	 * @since 1.0.0
	 */
	public function enqueue_preview_color_palette() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'boldgird-framework-customizer-color-palette-preview',
			$this->configs['framework']['js_dir'] . 'customizer/color-palette-preview' . $suffix . '.js',
		array( 'customize-preview', 'jquery' ), $this->configs['version'], false );
	}

	/**
	 * Add the color palette controls to the customizer
	 *
	 * @since 1.0.0
	 */
	public function add_color_selection() {
		if ( ! empty( $this->configs['customizer-options']['colors'] ) ) {
			// Add Palette Controls/Settings/Sections.
			$this->add_palette_controls();
		}
	}

	/**
	 * Add palette class to the body
	 *
	 * @param string $body_class CSS classes to add to the body.
	 * @since 1.0.0
	 * @return array $body_class CSS classes to add to the body.
	 */
	public function boldgrid_filter_body_class( $body_class ) {
		// Set the default format class.
		$default_palette_class = '';
		foreach ( $this->configs['customizer-options']['colors']['defaults'] as $default ) {
			if ( ! empty( $default['default'] ) ) {
				$default_palette_class = $default['format'];
			}
		}

		$body_class[] = get_theme_mod( 'boldgrid_palette_class', $default_palette_class );
		return $body_class;
	}

	/**
	 * Register admin Scripts.  The items are not enqueued until a control does so.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		$this->register_scripts();
	}

	/**
	 * Create a WP_Customizer setting and add a new control.
	 *
	 * @since 1.0.0
	 */
	public function add_palette_controls() {
		$this->wp_customize->add_setting(
			'boldgrid_compiled_css',
			array(
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			)
		);
		$this->wp_customize->add_control(
			new WP_Customize_Control(
				$this->wp_customize,
				'boldgrid_compiled_css',
				array(
					'section'        => 'colors',
					'settings'       => 'boldgrid_compiled_css',
				)
			)
		);
		$this->wp_customize->add_setting(
			'boldgrid_color_palette',
			array(
				'default' => '',
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			)
		);
		$this->wp_customize->add_control(
			new Boldgrid_Framework_Control_Palette(
				$this->wp_customize,
				'boldgrid-color-palette',
				array(
					'section' => 'colors',
					'settings' => 'boldgrid_color_palette',
					'priority' => 1,
					'choices' => array(
						'palettes' => $this,
					),
				)
			)
		);
	}

	/**
	 * Get the color palette theme mods and json decode.
	 *
	 * @return    array    $palette_configs    Theme's color palettes from configs.
	 */
	public static function get_palette_configs() {
		$theme_mod = get_theme_mod( 'boldgrid_color_palette' );
		$palette_configs = null;
		if ( ! empty( $theme_mod ) ) {
			$palette_configs = json_decode( $theme_mod, true );
		}

		return $palette_configs;
	}

	/**
	 * Get the uri of the color palettes.css output file.
	 *
	 * @since 1.1.4
	 *
	 * @param array $configs Array of BGTFW configurations.
	 *
	 * @return string color palettes uri
	 */
	public static function get_colors_uri( $configs ) {
		$output_css_name = $configs['customizer-options']['colors']['settings']['output_css_name'];

		return str_replace(
			$configs['framework']['config_directory']['template'],
			$configs['framework']['config_directory']['uri'],
			$output_css_name
		);
	}

	/**
	 * On change of a theme mod, update the color palette
	 *
	 * @since 0.1
	 *
	 * @param string $old_value Old value of theme mod.
	 * @param string $new_value New Value of theme mod.
	 */
	public function update_color_palette( $old_value, $new_value ) {
		$boldgrid_theme_helper_scss = null;
		$old_palette = ! empty( $old_value['boldgrid_color_palette'] ) ? $old_value['boldgrid_color_palette'] : null;
		$new_palette = ! empty( $new_value['boldgrid_color_palette'] ) ? $new_value['boldgrid_color_palette'] : null;

		if ( trim( $old_palette ) != trim( $new_palette ) ) {
			// Pass in the color palette that was updated to the compiler.
			$this->configs['forced_color_palette_decoded'] = null;
			if ( ! empty( $new_palette ) ) {
				$this->configs['forced_color_palette_decoded'] = json_decode( $new_palette,  true );
			}

			$boldgrid_theme_helper_scss = new Boldgrid_Framework_SCSS( $this->configs );
			$boldgrid_theme_helper_scss->update_css( true );
		}

		return $boldgrid_theme_helper_scss;
	}

	/**
	 * Update css for changesets.
	 *
	 * @since 1.5.1
	 *
	 * @param array $data Changeset data.
	 */
	public function changeset_data( $data ) {
		global $boldgrid_theme_framework;
		$slug = get_template();
		$changeset_status = ! empty( $_REQUEST['customize_changeset_status'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['customize_changeset_status'] ) ) : false;

		if ( ! get_post_status_object( $changeset_status ) || ! in_array( $changeset_status, array( 'draft', 'pending', 'publish', 'future' ), true ) ) {
			$changeset_status = false;
		}

		if ( 'publish' !== $changeset_status && ! empty( $data[ $slug . '::boldgrid_color_palette' ]['value'] ) ) {
			$boldgrid_theme_framework->changset_customization = true;
			$boldgrid_theme_framework->palette_changeset = $data[ $slug . '::boldgrid_color_palette' ]['value'];

			$boldgrid_scss = $this->update_color_palette( false, array(
				'boldgrid_color_palette' => $data[ $slug . '::boldgrid_color_palette' ]['value'],
			) );

			if ( $boldgrid_scss ) {
				$data[ $slug . '::boldgrid_compiled_css' ]['value'] = $boldgrid_scss->compiled_content;
			}
		}

		return $data;
	}

	/**
	 * Create the new css file based on the theme mod
	 *
	 * @since    0.1
	 */
	public function update_theme_mods() {
		$palette_configs = self::get_palette_configs();
		if ( ! empty( $palette_configs ) ) {
			$active_palette_class = $palette_configs['state']['active-palette'];

			// Update the body class based on the active palette.
			set_theme_mod( 'boldgrid_palette_class', $active_palette_class );
		}
	}

	/**
	 * Initialize the WP_Filesystem
	 *
	 * @since 1.1
	 */
	public function init_filesystem() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
	}

	/**
	 * From the XML file stored in the plugin get an array of colors to be merged into
	 * the array of color palettes
	 *
	 * @since     1.0.0
	 * @return    array    $top_color_palettes_array    selection of colour lover's top palettes
	 */
	public function get_top_colors() {
		// Initialize the WP Filesystem.
		$this->init_filesystem();
		global $wp_filesystem;

		$json_file = $this->configs['framework']['asset_dir'] . '/json/colourlovers-top.json';
		$top_color_palettes_array = array();
		if ( file_exists( $json_file ) ) {
			$top_color_palettes_array = json_decode( $wp_filesystem->get_contents( $json_file ), true );
		}

		return $top_color_palettes_array;
	}

	/**
	 * Creates the string used to identify a palette.
	 *
	 * @param array $palette Palette to create an ID for.
	 * @return   string
	 * @since    1.0.0
	 */
	public function create_palette_id( $palette ) {
		$neutral_color = ! empty( $palette['neutral-color'] ) ? serialize( $palette['neutral-color'] ) : '';

		return md5( serialize( $palette['colors'] ) . $neutral_color );
	}

	/**
	 * Create an array of all color palette data this will be passed to the color palette
	 * control and used to create mark up.
	 *
	 * @since    1.0.0
	 */
	public function get_color_palettes() {
		// Get theme Defined Color Palattes.
		$this->color_palettes['palettes'] = $this->configs['customizer-options']['colors']['defaults'];

		foreach ( $this->color_palettes['palettes'] as $key => $palette ) {
			$this->color_palettes['palettes'][ $key ]['copy_on_mod'] = true;

			// Convert preset palettes to RGB as our standard.
			foreach ( $this->color_palettes['palettes'][ $key ]['colors'] as $i => $color ) {
				$color = ariColor::newColor( $this->color_palettes['palettes'][ $key ]['colors'][ $i ] );
				$this->color_palettes['palettes'][ $key ]['colors'][ $i ] = $color->toCSS( 'rgb' );
			}

			// Do the same for neutral color.
			if ( ! empty( $this->color_palettes['palettes'][ $key ]['neutral-color'] ) ) {
				$color = ariColor::newColor( $this->color_palettes['palettes'][ $key ]['neutral-color'] );
				$this->color_palettes['palettes'][ $key ]['neutral-color'] = $color->toCSS( 'rgb' );
			}
		}

		$this->color_palettes['palette_formats'] = $this->get_color_formats( $this->color_palettes['palettes'] );
		$this->color_palettes['saved_palettes'] = $this->get_saved_color_settings();

		// Add the Saved colors to the list of palettes.
		$this->color_palettes['palettes'] = array_merge(
			$this->color_palettes['saved_palettes']['all'],
			$this->color_palettes['palettes']
		);

		$this->color_palettes['color-palette-size'] = 0;
		if ( ! empty( $this->color_palettes['palettes'] ) ) {
			$this->color_palettes['color-palette-size'] = count( $this->color_palettes['palettes'][0]['colors'] );
		}

		foreach ( $this->color_palettes['palettes'] as $key => $palette ) {
			$this->color_palettes['palettes'][ $key ]['palette_id'] = $this->create_palette_id( $palette );
		}

		// Update the color palettes array to add teh active attribute to the palette that the user has chosen.
		$this->set_active_palette();

		return $this->color_palettes;
	}

	/**
	 * Get the filter added palettes and format them for use in SCSS creation.
	 *
	 * @since    1.0.0
	 * @param array $palettes Palettes to format.
	 * @return   array    $reorginized_palettes
	 */
	public static function get_simplified_external_palettes( $palettes ) {
		$reorginized_palettes = array();
		foreach ( $palettes as $key => $palette ) {
			if ( ! empty( $palette['default'] ) ) {
				$default_palette = $palette;
			}
			if ( ! empty( $palette['format'] ) ) {
				$reorginized_palettes[ $palette['format'] ] = $palette;
			}
		}

		if ( ! empty( $default_palette ) ) {
			$reorginized_palettes[ $default_palette['format'] ] = $default_palette;
		}

		return $reorginized_palettes;
	}

	/**
	 * Modify the array of palettes that will be send to the view to include a property for the active
	 * palette
	 *
	 * @since    1.0.0
	 */
	public function set_active_palette() {
		// Find active Palettes.
		$active_palette_found = false;

		$saved_neutral_color = null;
		if ( ! empty( $this->color_palettes['saved_palettes']['neutral-color'] ) ) {
			$saved_neutral_color = $this->color_palettes['saved_palettes']['neutral-color'];
		}

		foreach ( $this->color_palettes['palettes'] as $key => $color_palette ) {

			// Grab the palette id.
			$palette_id = ! empty( $color_palette['palette_id'] ) ? $color_palette['palette_id'] : '';

			// Match based on filter added palettes.
			$palettes_id_match = false;
			if ( ! empty( $this->color_palettes['saved_palettes']['active-id'] ) ) {
				if ( $palette_id === $this->color_palettes['saved_palettes']['active-id'] ) {
					$palettes_id_match = true;
				}
			}

			$current_neutral_color = null;
			if ( ! empty( $color_palette['colors']['neutral-color'] ) ) {
				$current_neutral_color = $color_palette['colors']['neutral-color'];
			}

			// Check for a color match.
			if ( $palettes_id_match ||
				( $color_palette['colors'] === $this->color_palettes['saved_palettes']['active']
					&& $saved_neutral_color === $current_neutral_color ) ) {
				if ( false === $active_palette_found ) {
					$is_active = 'true';
					$this->color_palettes['palettes'][ $key ]['is_active'] = true;
					$active_palette_found = true;
				}
			}
		}

		// If none of the added palettes are the active palette, then add the active palette to the list.
		if ( ! $active_palette_found && ! empty( $this->color_palettes['saved_palettes']['active'] ) ) {
			$this->color_palettes['palettes'][] = array(
				'format' => $this->color_palettes['saved_palettes']['active_class'],
				'colors' => $this->color_palettes['saved_palettes']['active'],
				'neutral-color' => $this->color_palettes['saved_palettes']['neutral-color'],
				'is_active' => true,
			);
		}
	}

	/**
	 * Get all the formats or Body classes that will be used by the palettes switching
	 *
	 * @since     1.0.0
	 * @param     array $palettes Palettes to process and get format for.
	 * @return    array    $formats     Array containing body classes/formats for palette switch.
	 */
	public function get_color_formats( $palettes ) {
		$formats = array();
		foreach ( $palettes as $palette ) {
			$formats[] = $palette['format'];
		}

		return array_unique( $formats );
	}

	/**
	 * Grab the theme mods that were saved and determine which palette is the active palette
	 * return an array of the colors that are active. color order matters!
	 *
	 * @return    array    $palette_settings    array containing the color palette settings.
	 * @since     1.0.0
	 */
	public function get_saved_color_settings() {
		$active_class = get_theme_mod( 'boldgrid_palette_class', null );
		$palette_data = get_theme_mod( 'boldgrid_color_palette', '' );

		$palette_settings = array();
		$palette_settings['active'] = array();
		$palette_settings['active_class'] = array();
		$palette_settings['all'] = array();
		$palette_settings['active-id'] = null;

		if ( $active_class && $palette_data ) {
			$palette_data = json_decode( $palette_data, true );

			if ( ! empty( $palette_data['state']['palettes'][ $active_class ]['colors'] ) ) {

				$neutral_color = null;
				if ( ! empty( $palette_data['state']['palettes'][ $active_class ]['neutral-color'] ) ) {
					$neutral_color = $palette_data['state']['palettes'][ $active_class ]['neutral-color'];
				}

				$palette_settings['active'] = $palette_data['state']['palettes'][ $active_class ]['colors'];
				$palette_settings['active_class'] = $active_class;
				$palette_settings['neutral-color'] = $neutral_color;
				$palette_settings['all'] = $palette_data['state']['saved_palettes'];

				$palette_settings['active-id'] = null;
				if ( ! empty( $palette_data['state']['active-palette-id'] ) ) {
					$palette_settings['active-id'] = $palette_data['state']['active-palette-id'];
				} else {
					$palette_settings['active-id'] = $this->create_palette_id( $palette_data['state']['palettes'][ $active_class ] );
				}
}
		}
		return $palette_settings;
	}

	/**
	 * Enqueue the CSS to the front end of the website for the theme to use.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_front_end_styles() {

		$bgtfw_styles = new BoldGrid_Framework_Styles( $this->configs );
		$bgtfw_styles->enqueue_colors();

	}

	/**
	 * Gets the required variables for use in Javascript.
	 *
	 * @since 1.2.4
	 *
	 * @return array $vars An array of variables to localize to JS.
	 */
	public function get_js_vars() {
		$scss = new Boldgrid_Framework_SCSS( $this->configs );
		$vars = array(
			'WorkerUrl' => $this->configs['framework']['js_dir'] . 'sass-js/sass.worker.js',
			'ScssFormatFileContents' => $scss->get_precompile_content(),
			'output_css_filename' => self::get_colors_uri( $this->configs ),
		);

		if ( true === $this->configs['components']['buttons']['enabled'] ) {
			$vars['ButtonVariables'] = $this->colors->get_scss_variables();
			$s = $this->configs['components']['buttons']['variables'];
			$buttons = array();

			if ( ! empty( $s['button-primary-classes'] ) ) {
				$buttons['primary'] = $s['button-primary-classes'];
			}

			if ( ! empty( $s['button-secondary-classes'] ) ) {
				$buttons['secondary'] = $s['button-secondary-classes'];
			}

			if ( ! empty( $buttons ) ) {
				$vars['ButtonExtends'] = $buttons;
			}
			if ( ! empty( $s['ubtn-namespace'] ) ) {
				$vars['ButtonNamespace'] = $s['ubtn-namespace'];
			}
		}

		return $vars;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function register_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'boldgird-theme-helper-sass-compiler',
			$this->configs['framework']['js_dir'] . 'sass-js/sass.js',
			array(),
			$this->configs['version'],
			false
		);

		wp_register_script(
			'boldgird-theme-helper-sass-implementation',
			$this->configs['framework']['js_dir'] . 'customizer/sass-compiler' . $suffix . '.js',
			array( 'boldgird-theme-helper-sass-compiler' ),
			$this->configs['version'],
			false
		);

		wp_localize_script(
			'boldgird-theme-helper-sass-implementation',
			'BOLDGRIDSass',
			$this->get_js_vars()
		);

		wp_register_script(
			'boldgrid-theme-helper-color-picker-transparent',
			$this->configs['framework']['js_dir'] . 'customizer/transparent-color-picker' . $suffix . '.js',
			array( 'wp-color-picker', 'boldgird-theme-helper-sass-compiler' ),
			$this->configs['version'],
			false
		);

		wp_register_script(
			'boldgrid-theme-helper-brehaut-color-js',
			$this->configs['framework']['js_dir'] . 'color-js/color' . $suffix . '.js',
			array(),
			$this->configs['version'],
			false
		);

		wp_register_script(
			'boldgrid-theme-helper-color-palette-generate',
			$this->configs['framework']['js_dir'] . 'customizer/color-palette-generate' . $suffix . '.js',
			array( 'boldgrid-theme-helper-brehaut-color-js' ),
			$this->configs['version'],
			false
		);

		wp_localize_script(
			'boldgrid-theme-helper-color-palette-generate',
			'BOLDGRIDColorPalettes',
			array( 'palettes' => $this->get_top_colors() )
		);

		wp_register_script(
			'boldgird-theme-helper-color-palette',
			$this->configs['framework']['js_dir'] . 'customizer/color-palette' . $suffix . '.js',
			array(
				'wp-color-picker',
				'jquery-ui-widget',
				'boldgird-theme-helper-sass-implementation',
				'boldgrid-theme-helper-color-palette-generate',
				'boldgrid-theme-helper-color-picker-transparent',
			),
			$this->configs['version'],
			false
		);
	}
}
