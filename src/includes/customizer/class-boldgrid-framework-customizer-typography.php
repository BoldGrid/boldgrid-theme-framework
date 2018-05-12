<?php
/**
 * Class: Boldgrid_Framework_Customizer_Typography
 *
 * This is the class responsible for adding the typography
 * section to the WordPress Customizer.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Typography
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Boldgrid_Framework_Customizer_Typography Class
 *
 * This is responsible for adding Typography section to the
 * WordPress customizer.
 *
 * @since 1.1
 */
class Boldgrid_Framework_Customizer_Typography {

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
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Enqueue the Javascript in the customizer.
	 *
	 * @since     1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( 'boldgrid-framework-customizer-typography',
			$this->configs['framework']['js_dir'] . 'customizer/typography-controls.js',
			array( 'jquery', 'customize-controls' ),
			$this->configs['version'],
			true
		);
		wp_enqueue_script( 'boldgrid-framework-customizer-typography' );
	}

	/**
	 * This outputs the javascript needed to automate the live settings preview.
	 * Also keep in mind that this function isn't necessary unless your settings
	 * are using 'transport'=>'postMessage' instead of the default 'transport'
	 * => 'refresh'
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action( 'customize_preview_init', $func )
	 * @since 1.0.0
	 */
	public function live_preview() {
		$handle = 'boldgrid-framework-customizer-typography-preview';
		wp_register_script(
			$handle,
			$this->configs['framework']['js_dir'] . 'customizer/typography-preview.js',
			array( 'jquery', 'customize-preview' ),
			$this->configs['version'],
			true
		);
		wp_enqueue_script( $handle );
		// Add data for script.
		$wp_scripts = wp_scripts();
		$font_configs = $this->configs['customizer-options']['typography']['selectors'];
		$wp_scripts->add_data( $handle, 'data', sprintf( 'var _typographyOptions = %s;', wp_json_encode( $font_configs ) ) );
		wp_localize_script( $handle, '_typographyClasses', $this->configs['template']['tagline-classes'] );
	}

	/**
	 * Add the Advanced Panel to the WordPress Customizer.  This also
	 * adds the controls we need for the custom CSS and custom JS
	 * textareas.
	 *
	 * @since 1.0.0
	 * @param array $wp_customize WordPress customizer object.
	 */
	public function typography_panel( $wp_customize ) {

	}

	/**
	 * Set the typography configs to the theme mods.
	 *
	 * @since 1.2.4
	 *
	 * @param array $framework_configs Array of bgtfw configuration options.
	 *
	 * @return array $framework_configs array of bgtfw configuration options.
	 */
	public function set_configs( $framework_configs ) {
		$configs = $framework_configs['customizer-options']['typography']['defaults'];

		$heading_font_family = get_theme_mod( 'heading_font_family', $configs['headings_font_family'] );
		$alt_font_family = get_theme_mod( 'alternate_headings_font_family', $configs['alternate_headings_font_family'] );
		$body_font_family = get_theme_mod( 'body_font_family', $configs['body_font_family'] );

		$framework_configs['customizer-options']['typography']['defaults']['alternate_headings_font_family'] = $alt_font_family;
		$framework_configs['customizer-options']['typography']['defaults']['body_font_family'] = $body_font_family;
		$framework_configs['customizer-options']['typography']['defaults']['headings_font_family'] = $heading_font_family;

		return $framework_configs;
	}

	/**
	 * Add the Main Text Typography Controls to the WordPress Customizer.
	 *
	 * @since     1.0.0
	 * @param array $controls Array of controls for Kirki.
	 * @return    array      $controls      array of controls to pass to Kirki.
	 */
	public function body_typography_controls( $controls ) {

		// Configuration option to check.
		$main_text_config = $this->configs['customizer-options']['typography']['controls']['main_text'];

		if ( true === $main_text_config ) {
			// Body Font Family Control.
			$controls['body_font_family'] = array(
				'type'     => 'select',
				'settings'  => 'body_font_family',
				'label'    => __( 'Font Family', 'bgtfw' ),
				'section'  => 'body_typography',
				'default'  => $this->configs['customizer-options']['typography']['defaults']['body_font_family'],
				'choices'  => kirki_Fonts::get_font_choices(),
				'output'   => array(
					array(
						'element'  => 'body, p, .site-content, .site-footer',
						'property' => 'font-family',
					),
				),
			);
			// Body Font Size Control.
			$controls['body_font_size'] = array(
				'type'     => 'slider',
				'settings'  => 'body_font_size',
				'transport' => 'postMessage',
				'label'    => __( 'Font Size', 'bgtfw' ),
				'section'  => 'body_typography',
				'default'  => $this->configs['customizer-options']['typography']['defaults']['body_font_size'],
				'choices'  => array(
					'min'  => 6,
					'max'  => 28,
					'step' => 1,
				),
				'output' => array(
					array(
						'element'  => 'body, p, .site-content, .site-footer',
						'property' => 'font-size',
						'units'    => 'px',
					),
				),
			);
			// Body Line Height Control.
			$controls['body_line_height'] = array(
				'type'     => 'slider',
				'settings'  => 'body_line_height',
				'transport' => 'postMessage',
				'label'    => __( 'Line Height', 'bgtfw' ),
				'section'  => 'body_typography',
				'default'  => $this->configs['customizer-options']['typography']['defaults']['body_line_height'],
				'choices'  => array(
					'min'  => 100,
					'max'  => 200,
					'step' => 1,
				),
				'output' => array(
					array(
						'element'  => 'body, p, .site-content, .site-footer',
						'property' => 'line-height',
						'units'    => '%',
					),
				),
			);
		}
		return $controls;
	}

	/**
	 * Add the Navigation Typography Controls to the WordPress Customizer.
	 *
	 * @since     1.0.0
	 * @param array $controls Array of controls for Kirki.
	 * @return    array      $controls      array of controls to pass to Kirki.
	 */
	public function navigation_typography_controls( $controls ) {

		// Configuration option to check.
		$navigation_config = $this->configs['customizer-options']['typography']['controls']['navigation'];

		if ( true === $navigation_config ) {
			// Create controls for all nav menus created.
			$boldgrid_menus = get_registered_nav_menus();
			foreach ( $boldgrid_menus as $location => $description ) {
				// Tagline Typography Settings.
				$controls[ 'navigation_' . $location . '_typography' ] = array(
					'type'        => 'typography',
					'transport'   => 'auto',
					'settings'    => 'navigation_' . $location . '_typography',
					'label'       => esc_attr( $description . ' ' . __( 'Typography', 'bgtfw' ) ),
					'section'     => 'navigation_typography',
					'default'     => array(
						'font-family'    => 'Roboto',
						'variant'        => 'regular',
						'font-size'      => $this->configs['customizer-options']['typography']['defaults']['navigation_font_size'],
						'line-height'    => '1.5',
						'letter-spacing' => '0',
						'subsets'        => array( 'latin-ext' ),
						'text-transform' => $this->configs['customizer-options']['typography']['defaults']['navigation_text_transform'],
					),
					'priority'    => 10,
					'output'      => array(
						array(
							'element' => '.' . str_replace( '_', '-', $location ) . '-menu li a',
						),
					),
				);
			}
		}

		return $controls;
	}

	/**
	 * Classes that represent the font families chosen for theme.
	 *
	 * @since 1.2.4
	 *
	 * @return string css.
	 */
	public function generate_font_classes() {
		$configs = $this->configs['customizer-options']['typography']['defaults'];

		$headings_font = get_theme_mod( 'bgtfw_headings_typography', false );
		$headings_font_family = ! empty( $headings_font['font-family'] ) ? $headings_font['font-family'] : $configs['headings_font_family'];

		$menu_font = get_theme_mod( 'navigation_main_typography', false );
		$menu_font_family = ! empty( $menu_font['font-family'] ) ? $menu_font['font-family'] : $configs['navigation_font_family'];

		$body_font = get_theme_mod( 'bgtfw_body_typography', false );
		$body_font_family = ! empty( $body_font['font-family'] ) ? $body_font['font-family'] : $configs['body_font_family'];

		$css = '';
		$css .= ".bg-font-family-menu { font-family: $menu_font_family !important }";
		$css .= ".bg-font-family-body { font-family: $body_font_family !important }";
		$css .= ".bg-font-family-heading { font-family: $headings_font_family !important }";

		return $css;
	}

	/**
	 * Adds font size CSS to style.css inline.
	 *
	 * @since 2.0.0
	 */
	public function add_font_size_css( $css ) {
		return $this->generate_font_size_css( $css );
	}

	/**
	 * Generates font sizes based on Bootstrap's LESS implementation.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $css CSS to append styles to.
	 *
	 * @return string $css Generated CSS styles.
	 */
	public function generate_font_size_css( $css = '' ) {
		$css .= $this->generate_body_css();
		$css .= $this->generate_headings_css();
		$css .= $this->generate_font_classes();

		return apply_filters( 'bgtfw_inline_css', $css );
	}

	/**
	 * Generates headings CSS to apply to frontend.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $css CSS to append body styles to.
	 *
	 * @return string $css CSS for body styles.
	 */
	public function generate_body_css( $css = '' ) {
		// Body Font.
		$body_font = get_theme_mod( 'bgtfw_body_typography' );
		$body_font_size = ! empty( $body_font['font-size'] ) ? $body_font['font-size'] : $this->configs['customizer-options']['typography']['defaults']['body_font_size'];

		$body_base = ( int ) preg_replace( '/[^0-9]./', '', $body_font_size );
		$body_unit = preg_replace( '/[^a-z]/i', '', $body_font_size );
		$body_unit = empty( $body_unit ) ? 'px' : $body_unit;

		// Blockquotes.
		$blockquote = $body_base * 1.25;
		$css .= 'blockquote, blockquote p, .mod-blockquote {font-size:' . $blockquote . $body_unit . ';}';

		return $css;
	}

	/**
	 * Generates headings CSS to apply to frontend.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $css CSS to append headings styles to.
	 *
	 * @return string $css CSS for headings styles.
	 */
	public function generate_headings_css( $css = '' ) {
		$headings_font = get_theme_mod( 'bgtfw_headings_typography', $this->configs['customizer-options']['typography']['defaults']['headings_font_size'] );

		$headings_font_size = ! empty( $headings_font['font-size'] ) ? $headings_font['font-size'] : $this->configs['customizer-options']['typography']['defaults']['headings_font_size'];

		$headings_base = ( int ) preg_replace( '/[^0-9]./', '', $headings_font_size );
		$headings_unit = preg_replace( '/[^a-z]/i', '', $headings_font_size );
		$headings_unit = empty( $headings_unit ) ? 'px' : $headings_unit;

		$selectors = $this->configs['customizer-options']['typography']['selectors'];

		foreach ( $selectors as $selector => $options ) {
			if ( 'subheadings' === $options['type'] ) {
				continue;
			}

			$css .= $selector . '{font-size:';

			if ( 'floor' === $options['round'] ) {
				$css .= floor( $headings_base * $options['amount'] );
			}

			if ( 'ceil' === $options['round'] ) {
				$css .= ceil( $headings_base * $options['amount'] );
			}

			$css .= "$headings_unit;}";
		}

		$css .= $this->generate_headings_color_css( 'bgtfw_header_headings_color', '.site-header :not(.bgtfw-widget-row)', $selectors );
		$css .= $this->generate_headings_color_css( 'bgtfw_footer_headings_color', '.site-footer :not(.bgtfw-widget-row)', $selectors );

		return $css;
	}

	/**
	 * Generates headings color CSS to apply to frontend.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $theme_mod Name of thememod to get color palette settings from.
	 * @param  string $section   CSS selector for section to apply heading colors to.
	 * @param  array  $selectors Array of heading CSS selectors from configs.
	 * @param  string $css       CSS to append headings styles to.
	 *
	 * @return string $css       Output CSS for headings color styles.
	 */
	public function generate_headings_color_css( $theme_mod, $section, $selectors = array(), $css = '' ) {
		$theme_mod = get_theme_mod( $theme_mod, false );

		if ( empty( $theme_mod ) ) {
			return;
		}

		$theme_mod = explode( ':', $theme_mod );
		$theme_mod = array_pop( $theme_mod );

		if ( empty( $selectors ) ) {
			$selectors = $this->configs['customizer-options']['typography']['selectors'];
		}

		$found = array();

		foreach ( $selectors as $selector => $options ) {
			if ( 'headings' === $options['type'] ) {
				$found[] = "$section $selector";
			}
		}

		$found = implode( ', ', $found );
		$css .= "$found{color:{$theme_mod};}";

		return $css;
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @param array $controls Array of controls for Kirki.
	 * @return    array      $controls      array of controls to pass to Kirki.
	 */
	public function site_identity_controls( $controls ) {

		$site_title_customizer = $this->configs['customizer-options']['site-title']['site-title'];
		if ( true === $site_title_customizer ) {

			$site_indentity_section = 'title_tagline';

			/**
			* Font Toggle
			*/
			$controls['boldgrid_font_toggle'] = array(
				'type'        => 'toggle',
				'settings'     => 'boldgrid_font_toggle',
				'label'       => __( 'Custom Font', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'     => false,
			);
			/**
			* Logo Fonts
			*/
			$controls['logo_font_family'] = array(
				'type'     => 'select',
				'settings'  => 'logo_font_family',
				'label'    => __( 'Font Family', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 'Oswald',
				'choices'  => kirki_Fonts::get_font_choices(),
				'output'   => array(
					array(
						'element'  => '.site-title',
						'property' => 'font-family',
					),
				),
			);
			$controls['logo_font_size'] = array(
				'type'     => 'slider',
				'settings'  => 'logo_font_size',
				'transport' => 'postMessage',
				'label'    => __( 'Font Size', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 55,
				'choices'  => array(
					'min'  => 1,
					'max'  => 250,
					'step' => 1,
				),
				'output' => array(
					array(
						'element'  => '.site-title',
						'property' => 'font-size',
						'units'    => 'px',
					),
				),
			);
			/**
			* Logo Styling
			*/
			$controls['logo_text_transform'] = array(
				'type'     => 'select',
				'settings'  => 'logo_text_transform',
				'transport' => 'postMessage',
				'label'    => __( 'Capitalization', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 'uppercase',
				'choices'  => array(
					'capitalize' => __( 'Capitalize', 'bgtfw' ),
					'lowercase' => __( 'Lowercase', 'bgtfw' ),
					'uppercase' => __( 'Uppercase', 'bgtfw' ),
					'none' => __( 'Unmodified', 'bgtfw' ),
				),
				'output'   => array(
					array(
						'element'  => '.site-title',
						'property' => 'text-transform',
					),
				),
			);
			$controls['logo_text_decoration'] = array(
				'type'     => 'select',
				'settings'  => 'logo_text_decoration',
				'transport' => 'postMessage',
				'label'    => __( 'Decoration', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 'none',
				'choices'  => array(
					'none' => __( 'Normal', 'bgtfw' ),
					'overline' => __( 'Overline', 'bgtfw' ),
					'underline' => __( 'Underline', 'bgtfw' ),
					'line-through' => __( 'Strikethrough', 'bgtfw' ),
				),
				'output' => array(
					array(
						'element'  => '.site-title a',
						'property' => 'text-decoration',
					),
				),
			);
			$controls['logo_text_decoration_hover'] = array(
				'type'     => 'select',
				'settings'  => 'logo_text_decoration_hover',
				'transport' => 'postMessage',
				'label'    => __( 'Decoration Hover', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 'underline',
				'choices'  => array(
					'none' => __( 'Normal', 'bgtfw' ),
					'overline' => __( 'Overline', 'bgtfw' ),
					'underline' => __( 'Underline', 'bgtfw' ),
					'line-through' => __( 'Strikethrough', 'bgtfw' ),
				),
				'output' => array(
					array(
						'element'  => '.site-title a:hover',
						'property' => 'text-decoration',
					),
					array(
						'element'  => '.site-title a:focus',
						'property' => 'text-decoration',
					),
				),
			);
			/**
			* Font Toggle
			*/
			$controls['boldgrid_position_toggle'] = array(
				'type'        => 'toggle',
				'settings'     => 'boldgrid_position_toggle',
				'label'       => __( 'Position', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'     => false,
			);
			/**
			* Logo Spacing
			*/
			$controls['logo_margin_top'] = array(
				'type'     => 'slider',
				'settings'  => 'logo_margin_top',
				'transport' => 'postMessage',
				'label'    => __( 'Top Margin', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 10,
				'choices'  => array(
					'min'  => -20,
					'max'  => 100,
					'step' => 1,
				),
				'output' => array(
					array(
						'element'  => '.site-title',
						'property' => 'margin-top',
						'units'    => 'px',
					),
				),
			);
			$controls['logo_margin_bottom'] = array(
				'type'     => 'slider',
				'settings'  => 'logo_margin_bottom',
				'transport' => 'postMessage',
				'label'    => __( 'Bottom Margin', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 20,
				'choices'  => array(
					'min'  => -20,
					'max'  => 100,
					'step' => 1,
				),
				'output' => array(
					array(
						'element'  => '.site-title',
						'property' => 'margin-bottom',
						'units'    => 'px',
					),
				),
			);
			$controls['logo_margin_left'] = array(
				'type'     => 'slider',
				'settings'  => 'logo_margin_left',
				'transport' => 'postMessage',
				'label'    => __( 'Horizontal Margin', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 0,
				'choices'  => array(
					'min'  => -50,
					'max'  => 50,
					'step' => 1,
				),
				'output' => array(
					array(
						'element'  => '.site-branding',
						'property' => 'margin-left',
						'units'    => 'px',
					),
				),
			);
			$controls['logo_line_height'] = array(
				'type'     => 'slider',
				'settings'  => 'logo_line_height',
				'transport' => 'postMessage',
				'label'    => __( 'Line Height', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 150,
				'choices'  => array(
					'min'  => 50,
					'max'  => 150,
					'step' => 1,
				),
				'output' => array(
					array(
						'element'  => '.site-title',
						'property' => 'line-height',
						'units'    => '%',
					),
				),
			);
			$controls['logo_letter_spacing'] = array(
				'type'     => 'slider',
				'settings'  => 'logo_letter_spacing',
				'transport' => 'postMessage',
				'label'    => __( 'Letter Spacing', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 1,
				'choices'  => array(
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				),
				'output' => array(
					array(
						'element'  => '.site-title',
						'property' => 'letter-spacing',
						'units'    => 'px',
					),
				),
			);
			$controls['logo_shadow_switch'] = array(
				'type'        => 'toggle',
				'transport' => 'postMessage',
				'settings'     => 'logo_shadow_switch',
				'label'       => __( 'Custom Shadow', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'     => 0,
			);
			$controls['logo_shadow_horizontal'] = array(
				'type'     => 'slider',
				'transport' => 'postMessage',
				'settings'  => 'logo_shadow_horizontal',
				'label'    => __( 'Horizontal Shadow', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 5,
				'choices'  => array(
					'min'  => -25,
					'max'  => 25,
					'step' => 1,
				),
			);
			$controls['logo_shadow_vertical'] = array(
				'type'     => 'slider',
				'settings'  => 'logo_shadow_vertical',
				'transport' => 'postMessage',
				'label'    => __( 'Vertical Shadow', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 5,
				'choices'  => array(
					'min'  => -25,
					'max'  => 25,
					'step' => 1,
				),
			);
			$controls['boldgrid_logo_size'] = array(
				'type'     => 'slider',
				'settings'  => 'boldgrid_logo_size',
				'label'    => __( 'Logo Size', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 260,
				'choices'  => array(
					'min'  => 120,
					'max'  => 555,
					'step' => 1,
				),
				'output' => array(
					array(
						'element'  => '.logo-site-title img',
						'property' => 'width',
						'units'    => 'px',
					),
				),
			);
			$controls['logo_shadow_blur'] = array(
				'type'     => 'slider',
				'transport' => 'postMessage',
				'settings'  => 'logo_shadow_blur',
				'label'    => __( 'Shadow Blur', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => 5,
				'choices'  => array(
					'min'  => 1,
					'max'  => 25,
					'step' => 1,
				),
			);
			$controls['logo_shadow_color'] = array(
				'type'     => 'color-alpha',
				'transport' => 'postMessage',
				'settings'  => 'logo_shadow_color',
				'label'    => __( 'Shadow Color', 'bgtfw' ),
				'section'  => $site_indentity_section,
				'default'  => '#000',
			);
		}
		return $controls;
	}

	/**
	 * Adds text shadow based on logo shadow selection
	 *
	 * @since     1.0.0
	 */
	public function title_text_shadow() {
		if ( get_theme_mod( 'logo_shadow_switch' ) ) : ?>
		<style type="text/css">
				.site-title { text-shadow:<?php echo esc_attr( get_theme_mod( 'logo_shadow_horizontal', '5' ) ); ?>px <?php echo esc_attr( get_theme_mod( 'logo_shadow_vertical', '5' ) ); ?>px <?php echo esc_attr( get_theme_mod( 'logo_shadow_blur', '5' ) ); ?>px <?php echo esc_attr( get_theme_mod( 'logo_shadow_color', '#000000' ) ); ?>; }
			</style>
		<?php endif;
	}
} // End of Class Boldgrid_Framework_Customizer_Typography
