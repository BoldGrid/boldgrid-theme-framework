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

		// Configuration option to check.
		$typography_config = $this->configs['customizer-options']['typography']['enabled'];

		if ( true === $typography_config ) {

			// Add the Typography Panel to main customizer view.
			$wp_customize->add_panel( 'boldgrid_typography', array(
				'title'       => __( 'Fonts', 'boldgrid' ),
				'description' => 'Manage your site typography settings.',
				'priority'    => 99,
			) );

			// Add Navigation to Typography Panel.
			$wp_customize->add_section( 'navigation_typography', array(
				'title'    => 'Navigation',
				'panel' => 'boldgrid_typography',
			) );

			// Add Headings to Typography Panel.
			$wp_customize->add_section( 'headings_typography', array(
				'title'    => 'Headings',
				'panel' => 'boldgrid_typography',
			) );

			// Add Subheadings to Typography Panel.
			$wp_customize->add_section( 'alternate_headings_typography', array(
				'title'    => 'Subheadings',
				'panel' => 'boldgrid_typography',
			) );

			// Add Body to Typography Panel.
			$wp_customize->add_section( 'body_typography', array(
				'title'    => 'Main Text',
				'panel' => 'boldgrid_typography',
			) );

		}

	}

	/**
	 * Add the Headings Typography Controls to the WordPress Customizer.
	 *
	 * @since     1.0.0
	 * @param array $controls Array of controls for Kirki.
	 * @return    array      $controls      array of controls to pass to Kirki.
	 */
	public function headings_typography_controls( $controls ) {

		// Configuration option to check.
		$headings_config = $this->configs['customizer-options']['typography']['controls']['headings'];

		if ( true === $headings_config ) {
			// Headings Font Family Control.
			$controls['headings_font_family'] = array(
				'type'     => 'select',
				'settings'  => 'heading_font_family',
				'label'    => __( 'Font Family', 'bgtfw' ),
				'section'  => 'headings_typography',
				'default'  => $this->configs['customizer-options']['typography']['defaults']['headings_font_family'],
				'choices'  => kirki_Fonts::get_font_choices(),
				'output'   => array(
					array(
						'element'  => 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6',
						'property' => 'font-family',
					),
				),
			);
			// Headings Font Size Control.
			$controls['headings_font_size'] = array(
				'type'     => 'slider',
				'settings'  => 'headings_font_size',
				'transport' => 'postMessage',
				'label'    => __( 'Font Size', 'bgtfw' ),
				'section'  => 'headings_typography',
				'default'  => $this->configs['customizer-options']['typography']['defaults']['headings_font_size'],
				'choices'  => array(
					'min'  => 12,
					'max'  => 50,
					'step' => 1,
				),
			);
			// Headings Text Transform Control.
			$controls['headings_text_transform'] = array(
				'type'     => 'select',
				'settings'  => 'headings_text_transform',
				'transport' => 'postMessage',
				'label'    => __( 'Capitalization', 'bgtfw' ),
				'section'  => 'headings_typography',
				'default'  => $this->configs['customizer-options']['typography']['defaults']['headings_text_transform'],
				'choices'  => array(
					'capitalize' => 'Capitalize',
					'lowercase' => 'Lowercase',
					'uppercase' => 'Uppercase',
					'none' => 'Unmodified',
				),
				'output'   => array(
					array(
						'element'  => 'h1, h2, h3, h4, h5, h6',
						'property' => 'text-transform',
					),
				),
			);
		}
		return $controls;
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
						'element'  => 'p, .site-content, .site-footer',
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
						'element'  => 'p, .site-content, .site-footer',
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
						'element'  => 'p, .site-content, .site-footer',
						'property' => 'line-height',
						'units'    => '%',
					),
				),
			);
		}
		return $controls;
	}

	/**
	 * Add the Alternate Headings Typography Controls to the WordPress Customizer.
	 *
	 * @since     1.0.0
	 * @param array $controls Array of controls for Kirki.
	 * @return    array      $controls      array of controls to pass to Kirki.
	 */
	public function alternate_headings_typography_controls( $controls ) {

		// Configuration option to check.
		$alternate_headings_config = $this->configs['customizer-options']['typography']['controls']['alternate_headings'];

		if ( true === $alternate_headings_config ) {
			// Alternate Headings Font Family Control.
			$controls['alternate_headings_font_family'] = array(
				'type'     => 'select',
				'settings'  => 'alternate_headings_font_family',
				'label'    => __( 'Font Family', 'bgtfw' ),
				'section'  => 'alternate_headings_typography',
				'default'  => $this->configs['customizer-options']['typography']['defaults']['alternate_headings_font_family'],
				'choices'  => kirki_Fonts::get_font_choices(),
				'output'  => array(
					array(
						'element'  => 'h1.alt-font, h2.alt-font, h3.alt-font, h4.alt-font, h5.alt-font, h6.alt-font, .h1.alt-font, .h2.alt-font, .h3.alt-font, .h4.alt-font, .h5.alt-font, .h6.alt-font',
						'property' => 'font-family',
					),
				),
			);
			// Alternate Headings Font Size Control.
			$controls['alternate_headings_font_size'] = array(
				'type'     => 'slider',
				'settings'  => 'alternate_headings_font_size',
				'transport' => 'postMessage',
				'label'    => __( 'Font Size', 'bgtfw' ),
				'section'  => 'alternate_headings_typography',
				'default'  => $this->configs['customizer-options']['typography']['defaults']['alternate_headings_font_size'],
				'choices'  => array(
					'min'  => 12,
					'max'  => 50,
					'step' => 1,
				),
			);
			// Alternate Headings Text Transform Control.
			$controls['alternate_headings_text_transform'] = array(
				'type'     => 'select',
				'settings'  => 'alternate_headings_text_transform',
				'transport' => 'postMessage',
				'label'    => __( 'Capitalization', 'bgtfw' ),
				'section'  => 'alternate_headings_typography',
				'default'  => $this->configs['customizer-options']['typography']['defaults']['alternate_headings_text_transform'],
				'choices'  => array(
					'capitalize' => 'Capitalize',
					'lowercase' => 'Lowercase',
					'uppercase' => 'Uppercase',
					'none' => 'Unmodified',
				),
				'output'   => array(
					array(
						'element'  => 'h1.alt-font, h2.alt-font, h3.alt-font, h4.alt-font, h5.alt-font, h6.alt-font',
						'property' => 'text-transform',
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
				// Navigation Font Family Controls.
				$controls[ 'navigation_'. $location .'_font_family' ] = array(
					'type'     => 'select',
					'settings'  => 'navigation_'. $location .'_font_family',
					'label'    => __( $description . ' Font', 'bgtfw' ),
					'section'  => 'navigation_typography',
					'default'  => $this->configs['customizer-options']['typography']['defaults']['navigation_font_family'],
					'choices'  => kirki_Fonts::get_font_choices(),
					'output'   => array(
						array(
							'element'  => '.' . str_replace( '_', '-', $location ) . '-menu ul li a',
							'property' => 'font-family',
						),
					),
				);
				// Navigation Font Size Controls.
				$controls[ 'navigation_' . $location . '_font_size' ] = array(
					'type'     => 'slider',
					'settings'  => 'navigation_' . $location . '_font_size',
					'transport' => 'postMessage',
					'label'    => __( $description. ' Font Size', 'bgtfw' ),
					'section'  => 'navigation_typography',
					'default'  => $this->configs['customizer-options']['typography']['defaults']['navigation_font_size'],
					'choices'  => array(
						'min'  => 6,
						'max'  => 28,
						'step' => 1,
					),
					'output' => array(
						array(
							'element'  => '.' . str_replace( '_', '-', $location ) . '-menu ul li a',
							'property' => 'font-size',
							'units'    => 'px',
						),
					),
				);
				// Navigation Font Size Controls.
				$controls[ 'navigation_' . $location . '_text_transform' ] = array(
					'type'     => 'select',
					'settings'  => 'navigation_' . $location . '_text_transform',
					'transport' => 'postMessage',
					'label'    => __( $description. ' Capitalization', 'bgtfw' ),
					'section'  => 'navigation_typography',
					'default'  => $this->configs['customizer-options']['typography']['defaults']['navigation_text_transform'],
					'choices'  => array(
						'capitalize' => 'Capitalize',
						'lowercase' => 'Lowercase',
						'uppercase' => 'Uppercase',
						'none' => 'Unmodified',
					),
					'output' => array(
						array(
							'element'  => '.' . str_replace( '_', '-', $location ) . '-menu ul li a',
							'property' => 'text-transform',
						),
					),
				);
			}
		}
		return $controls;
	}

	/**
	 * Heading size based on Bootstrap's LESS implementation.
	 *
	 * @since 1.0.0
	 */
	public function headings_font_size_css() {

		// Font size.
		$font_size_base = get_theme_mod( 'headings_font_size',
		$this->configs['customizer-options']['typography']['defaults']['headings_font_size'] );
		$alt_font_size_base = get_theme_mod( 'alternate_headings_font_size',
		$this->configs['customizer-options']['typography']['defaults']['alternate_headings_font_size'] );

		// Text Transform.
		$heading_text_transform = get_theme_mod( 'heading_text_transform',
		$this->configs['customizer-options']['typography']['defaults']['headings_text_transform'] );
		$alt_heading_text_transform = get_theme_mod( 'alternate_headings_text_transform',
		$this->configs['customizer-options']['typography']['defaults']['alternate_headings_text_transform'] );

		$heading_h1 = floor( $font_size_base * 2.6 );
		$heading_h2 = floor( $font_size_base * 2.15 );
		$heading_h3 = ceil( $font_size_base * 1.7 );
		$heading_h4 = ceil( $font_size_base * 1.25 );
		$heading_h5 = $font_size_base;
		$heading_h6 = ceil( $font_size_base * 0.85 );
		$alt_heading_h1 = floor( $alt_font_size_base * 2.6 );
		$alt_heading_h2 = floor( $alt_font_size_base * 2.15 );
		$alt_heading_h3 = ceil( $alt_font_size_base * 1.7 );
		$alt_heading_h4 = ceil( $alt_font_size_base * 1.25 );
		$alt_heading_h5 = $alt_font_size_base;
		$alt_heading_h6 = ceil( $alt_font_size_base * 0.85 );
		?>
		<style type="text/css">
			h1, .h1{ font-size: <?php print $heading_h1;?>px;}
			h1{text-transform: <?php print $heading_text_transform; ?>;}

			h2, .h2{ font-size: <?php print $heading_h2;?>px;}
			h2{text-transform: <?php print $heading_text_transform; ?>;}

			h3, .h3{ font-size: <?php print $heading_h3;?>px;}
			h3{text-transform: <?php print $heading_text_transform; ?>;}

			h4, .h4{ font-size: <?php print $heading_h4;?>px;}
			h4{text-transform: <?php print $heading_text_transform; ?>;}

			h5, .h5{ font-size: <?php print $heading_h5;?>px;}
			h5{text-transform: <?php print $heading_text_transform; ?>;}

			h6, .h6{ font-size: <?php print $heading_h6;?>px;}
			h6{text-transform: <?php print $heading_text_transform; ?>;}

			h1.alt-font, .h1.alt-font{ font-size: <?php print $alt_heading_h1;?>px;}
			h1.alt-font{text-transform: <?php print $alt_heading_text_transform; ?>;}

			h2.alt-font, .h2.alt-font{ font-size: <?php print $alt_heading_h2;?>px;}
			h2.alt-font{text-transform: <?php print $alt_heading_text_transform; ?>;}

			h3.alt-font, .h3.alt-font{ font-size: <?php print $alt_heading_h3;?>px;}
			h3.alt-font{text-transform: <?php print $alt_heading_text_transform; ?>;}

			h4.alt-font, .h4.alt-font{ font-size: <?php print $alt_heading_h4;?>px;}
			h4.alt-font{text-transform: <?php print $alt_heading_text_transform; ?>;}

			h5.alt-font, .h5.alt-font{ font-size: <?php print $alt_heading_h5;?>px;}
			h5.alt-font{text-transform: <?php print $alt_heading_text_transform; ?>;}

			h6.alt-font, .h6.alt-font{ font-size: <?php print $alt_heading_h6;?>px;}
			h6.alt-font{text-transform: <?php print $alt_heading_text_transform; ?>;}
		</style>
		<?php
	}

	/**
	 * Editor Typography Styles.
	 *
	 * Styles to add to the WordPress TinyMCE Editor.
	 *
	 * @param string $content CSS to add to the editor.
	 * @since 1.1
	 */
	public function headings_editor_styles( $content ) {

		// Font Size.
		$font_size_base = get_theme_mod( 'headings_font_size',
		$this->configs['customizer-options']['typography']['defaults']['headings_font_size'] );
		$alt_font_size_base = get_theme_mod( 'alternate_headings_font_size',
		$this->configs['customizer-options']['typography']['defaults']['alternate_headings_font_size'] );

		// Font Family.
		$font_family    = get_theme_mod( 'heading_font_family',
		$this->configs['customizer-options']['typography']['defaults']['headings_font_family'] );
		$alt_font_family = get_theme_mod( 'alternate_headings_font_family',
		$this->configs['customizer-options']['typography']['defaults']['alternate_headings_font_family'] );

		// Text Transform.
		$heading_text_transform = get_theme_mod( 'heading_text_transform',
		$this->configs['customizer-options']['typography']['defaults']['headings_text_transform'] );
		$alt_heading_text_transform = get_theme_mod( 'alternate_headings_text_transform',
		$this->configs['customizer-options']['typography']['defaults']['alternate_headings_text_transform'] );

		// Main Text Size, Family, and Line Height.
		$body_font_size = get_theme_mod( 'body_font_size',
		$this->configs['customizer-options']['typography']['defaults']['body_font_size'] );
		$body_font_family = get_theme_mod( 'body_font_family',
		$this->configs['customizer-options']['typography']['defaults']['body_font_family'] );
		$body_line_height = get_theme_mod( 'body_line_height',
		$this->configs['customizer-options']['typography']['defaults']['body_line_height'] );

		// Calculate heading sizes.
		$heading_h1 = floor( $font_size_base * 2.6 );
		$heading_h2 = floor( $font_size_base * 2.15 );
		$heading_h3 = ceil( $font_size_base * 1.7 );
		$heading_h4 = ceil( $font_size_base * 1.25 );
		$heading_h5 = $font_size_base;
		$heading_h6 = ceil( $font_size_base * 0.85 );
		$alt_heading_h1 = floor( $alt_font_size_base * 2.6 );
		$alt_heading_h2 = floor( $alt_font_size_base * 2.15 );
		$alt_heading_h3 = ceil( $alt_font_size_base * 1.7 );
		$alt_heading_h4 = ceil( $alt_font_size_base * 1.25 );
		$alt_heading_h5 = $alt_font_size_base;
		$alt_heading_h6 = ceil( $alt_font_size_base * 0.85 );
		$content = "
				.mce-content-body .h1,
				.mce-content-body .h2,
				.mce-content-body .h3,
				.mce-content-body .h4,
				.mce-content-body .h5,
				.mce-content-body .h6,
				.mce-content-body h1,
				.mce-content-body h2,
				.mce-content-body h3,
				.mce-content-body h4,
				.mce-content-body h5,
				.mce-content-body h6{ font-family: {$font_family}; }
				h1.alt-font,
				h2.alt-font,
				h3.alt-font,
				h4.alt-font,
				h5.alt-font,
				h6.alt-font,
				.h1.alt-font,
				.h2.alt-font,
				.h3.alt-font,
				.h4.alt-font,
				.h5.alt-font,
				.h6.alt-font{ font-family: {$alt_font_family}; }
				.mce-content-body, .mce-content-body p { font-family: {$body_font_family}; line-height: {$body_line_height}%; font-size: {$body_font_size}px; }";

		$content .= "
			.mce-content-body h1, .mce-content-body .h1{ font-size: {$heading_h1}px; }
			.mce-content-body h1{ text-transform: {$heading_text_transform}; }

			.mce-content-body h2, .mce-content-body .h2{ font-size: {$heading_h2}px;}
			.mce-content-body h2{ text-transform: {$heading_text_transform}; }

			.mce-content-body h3, .mce-content-body .h3{ font-size: {$heading_h3}px;}
			.mce-content-body h3{ text-transform: {$heading_text_transform}; }

			.mce-content-body h4, .mce-content-body .h4{ font-size: {$heading_h4}px; }
			.mce-content-body h4{ text-transform: {$heading_text_transform}; }

			.mce-content-body h5, .mce-content-body .h5{ font-size: {$heading_h5}px; }
			.mce-content-body h5{ text-transform: {$heading_text_transform}; }

			.mce-content-body h6, .mce-content-body .h6{ font-size: {$heading_h6}px; }
			.mce-content-body h6{ text-transform: {$heading_text_transform}; }

			h1.alt-font, .h1.alt-font{ font-size: {$alt_heading_h1}px;}
			text-transform: {$alt_heading_text_transform};}

			h2.alt-font, .h2.alt-font{ font-size: {$alt_heading_h2}px;}
			text-transform: {$alt_heading_text_transform};}

			h3.alt-font, .h3.alt-font{ font-size: {$alt_heading_h3}px;}
			text-transform: {$alt_heading_text_transform};}

			h4.alt-font, .h4.alt-font{ font-size: {$alt_heading_h4}px;}
			text-transform: {$alt_heading_text_transform};}

			h5.alt-font, .h5.alt-font{ font-size: {$alt_heading_h5}px;}
			text-transform: {$alt_heading_text_transform};}

			h6.alt-font, .h6.alt-font{ font-size: {$alt_heading_h6}px;}
			text-transform: {$alt_heading_text_transform};}";

		return $content;
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
					'capitalize' => 'Capitalize',
					'lowercase' => 'Lowercase',
					'uppercase' => 'Uppercase',
					'none' => 'Unmodified',
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
					'none' => 'Normal',
					'overline' => 'Overline',
					'underline' => 'Underline',
					'line-through' => 'Strikethrough',
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
					'none' => 'Normal',
					'overline' => 'Overline',
					'underline' => 'Underline',
					'line-through' => 'Strikethrough',
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
		if ( true === get_theme_mod( 'logo_shadow_switch' ) ) : ?>
		<style type="text/css">
				.site-title { text-shadow:<?php echo esc_attr( get_theme_mod( 'logo_shadow_horizontal', '5' ) ); ?>px <?php echo esc_attr( get_theme_mod( 'logo_shadow_vertical', '5' ) ); ?>px <?php echo esc_attr( get_theme_mod( 'logo_shadow_blur', '5' ) ); ?>px <?php echo esc_attr( get_theme_mod( 'logo_shadow_color', '#000000' ) ); ?>; }
			</style>
		<?php endif;
	}
} // End of Class Boldgrid_Framework_Customizer_Typography
